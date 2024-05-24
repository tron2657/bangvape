<?php
 
namespace app\admin\model\user;

use traits\ModelTrait;
use basic\ModelBasic;
use app\admin\model\user\MemberCard as MemberCardMode;
use app\osapi\model\user\UserModel;
use think\Db;
use think\Exception;

/**
 * 会员优惠券计划 model
 * Class User
 * @package app\admin\model\user
 */

class MemberCouponPlan extends ModelBasic
{
    use ModelTrait;


    public static function set_fail_state($uid)
    {
        self::where('uid',$uid)->where('is_fail',1)->update(['is_fail'=>0]);
    }

    /**
     * 获取优惠券的扩展信息
     *
     * @param [type] $coupon_id
     * @return array
     */
    public static function get_coupon_attach($coupon_id)
    {
       $item= self::where(['cuid'=>$coupon_id])->find();
       $item['attach']= json_decode(stripslashes($item['attach']));
       return $item['attach'];
    }

    /**
     * 是否是免费的兑换一箱酒的优惠券
     */
    public static function is_free_coupon($coupon_id){
        $item= self::where(['cuid'=>$coupon_id])->find();
        $item['attach']= json_decode(stripslashes($item['attach']));
        return $item['attach']['limit_product_ids'];
    }

    // public static function grant_first_coupon($uid,$rule)
    // {
    //     'end_time'=>bcadd(bcmul($cyle_days, 86400, 0), $i, 0)
    // }

    /*
        赠送优惠券
     */
    public static function grant_coupon($uid=0){
   
        $where=[
            'status'=>0,
            'plan_grant_time'=>['<=',time()],
            'is_fail'=>0
        ];
        if($uid>0)
        {
            $where['uid']=$uid;
        }
        $data=self::where($where)
        // ->where('status',0)//待发放
        // ->where('plan_grant_time','<=',time())//小于当前当前时间点的优惠券
        ->select();//查找所有待发放的优惠券计划

 
        foreach($data as &$item)
        {
            $insertData=[
                'cid'=>$item['cid'],
                'uid'=>$item['uid'],
                'coupon_title'=>$item['coupon_title'],
                'coupon_price'=>$item['coupon_price'],
                'use_min_price'=>$item['use_min_price'],
                'type'=>'system',
                'add_time'=>$item['start_time'],
                'end_time'=>$item['end_time']
            ];
            $cuid=Db::name('store_coupon_user')->insertGetId($insertData);
            Db::name('member_coupon_plan')
            ->where('id',$item['id'])
            ->where('status',0)
            ->where('cuid',0)
            ->update(
                [
                    'status'=>1,
                    'grant_time'=>time(),
                    'cuid'=>$cuid
                ]
            );  
        }


 

        // self::checkTrans();
        // $res=true;

        // self::checkTrans($res)


        // $data = array();
        // foreach ($user as $k=>$v){
        //     $time=time();
        //     $data[$k]['cid'] = $coupon['id'];
        //     $data[$k]['uid'] = $v;
        //     $data[$k]['coupon_title'] = $coupon['title'];
        //     $data[$k]['coupon_price'] = $coupon['coupon_price'];
        //     $data[$k]['use_min_price'] = $coupon['use_min_price'];
        //     $data[$k]['type']='system';
        //     $data[$k]['add_time'] = $time;//开始时间为当时时间
        //     $data[$k]['end_time'] = strtotime(date('Y-m', $time) . '-' . date('t', $time) . ' 23:59:59'); //t为当月天数,28至31天= //结束时间为
        // }
        // $data_num = array_chunk($data,30);
        // self::beginTrans();
        // $res = true;
        // foreach ($data_num as $k=>$v){
        //   $res = $res && self::insertAll($v);
        // }
        // self::checkTrans($res);
        // return $res;
    }


    
    /**
     * @param $where
     * @return array
     */
    public static function systemPage($where){
        $model = new self;
        if($where['status'] != '')  $model = $model->where('status',$where['status']);
        if($where['nickname']!='')
        {
            $model=$model->where('uid',$where['nickname']);
        }
        if($where['is_fail']!='')
        {
            $model=$model->where('is_fail',$where['is_fail']);
        }
        // $model = $model->where('is_fail',$where['is_fail']);
        $model = $model->order('plan_grant_time desc,seq asc');
        $data=$model->page((int)$where['page'], (int)$where['limit'])->select()
        ->each(function($item){
            $user=\think\Db::name('user')->where(['uid'=>$item['uid']])->find();
            $item['nickname']=$user['nickname'].'/'.$user['uid'];
            $item['start_time']=date('Y-m-d',$item['start_time']);
            $item['end_time']=date('Y-m-d',$item['end_time']);
            $item['plan_grant_time']=date('Y-m-d H:i',$item['plan_grant_time']);
            $item['grant_time']=$item['grant_time']>0?date('Y-m-d H:i',$item['grant_time']):'待发放';
            $item['coupon_time']=$item['start_time'].'至'.$item['end_time'];
            $item['coupon']='优惠券名称:'.$item['coupon_title']
                    .'<br>序号:'.$item['seq']                    
                    .'<br>优惠券面额:'.$item['coupon_price'].'<br>优惠券最低消费:'.$item['use_min_price'].'<br>优惠券有效期:<br>'.$item['coupon_time'];
            if($item['status']==1)
            {
                $item['status']='状态:已发放';
                $item['status'].='<br>发放时间:'.$item['grant_time'];
            }
            else
            {
                $item['status']='未发放';
            }
            $item['souce_type_text']='';
            if($item['souce_type']==0)
            {
                $item['souce_type_text']='会员订单';
            }
            if($item['souce_type']==1)
            {
                $item['souce_type_text']='活动激活';
            }

            if($item['souce_type']==3)
            {
                $item['souce_type_text']='活动激活(赠送)';
            }

            if($item['attach']!=null)
            {
                $item['attach']= json_decode(stripslashes($item['attach']));
            }
        });

        $count=$model->count();
        return ['count' => $count, 'data' => $data];
    }

}
