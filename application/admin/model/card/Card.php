<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\card;


use app\admin\model\system\SystemConfig;
use app\admin\model\ump\StoreCouponUser;
use app\admin\model\wechat\WechatUser;
use app\admin\model\ump\StorePink;
use app\admin\model\order\StoreOrderCartInfo;
use app\admin\model\order\StoreOrder;
use app\admin\model\store\StoreProduct;
use app\admin\model\routine\RoutineFormId;
use app\core\model\routine\RoutineTemplate;
use service\ProgramTemplateService;
use service\PHPExcelService;
use traits\ModelTrait;
use basic\ModelBasic;
use service\WechatTemplateService;
use think\Url;
use think\Db;
use app\admin\model\user\User;
use app\admin\model\user\UserBill;
/**
 * 订单管理Model
 * Class StoreOrder
 * @package app\admin\model\store
 */
class Card extends ModelBasic
{
    use ModelTrait;

     /**
     * @param $where
     * @return array
     */
    public static function systemPage($where,$is_type = 0){
        $model = new self;
        if($where['order_id']!=''){
            $model=$model->where('r.order_id','LIKE',"%$where[order_id]%");
        }
        if($where['product_id'])  $model = $model->where('r.product_id',$where['product_id']);
        $model = $model->alias('r')->join('__USER__ u','u.uid=r.uid');
        $model = $model->join('__STORE_PRODUCT__ p','p.id=r.product_id');
        $model=$model->join('__USER__ uf','uf.uid=r.from_uid');
        $model = $model->field('r.*,u.nickname,u.avatar,p.store_name,uf.nickname as fromname');
        $model = $model->order('r.add_time desc,r.is_reply asc');
        return self::page($model,function($itme){

        },$where);
    }

    public static function cardList($where){
        $model=self::getModelObject($where);
        $model=$model->order('id desc')->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            $item['nickname'] = User::where('uid',$item['uid'])->value('nickname');
            $item['store_name'] = StoreProduct::where('id',$item['product_id'])->value('store_name');
            $item['fromname']=User::where('uid',$item['from_uid'])->value('nickname');
            $item['add_time'] = time_format($item['add_time']);
            if($item['status']==0){
                $sstr="正常";
            }elseif ($item['status']==1) {
                $sstr="已兑换";
            }elseif ($item['status']==2) {
                $sstr="赠送中";
            }elseif ($item['status']==3) {
                $sstr="已转余额";
            }
            $item['status']=$sstr;
        }
        $count=self::getModelObject($where)->count();
        return compact('count','data');
    }


    /**
     * 获取连表MOdel
     * @param $model
     * @return object
     */
    public static function getModelObject($where=[]){
        $model=new self();
        if(!empty($where)){
            // data 日期
            $model->where(function($query) use($where){
                switch ($where['data']) {
                    case 'yesterday':
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                        $query->whereTime('add_time', $where['data']);
                        break;
                    case 'quarter':
                        $start = strtotime(Carbon::now()->startOfQuarter());
                        $end   = strtotime(Carbon::now()->endOfQuarter());
                        $query->whereTime('add_time', 'between', [$start, $end]);
                        break;
                    case '':
                        ;
                        break;
                    default:
                        $between = explode(' - ', $where['data']);
                        $query->whereTime('add_time', 'between', [$between[0], $between[1]]);
                        break;
                }
            });
            // if(isset($where['is_reply']) && $where['is_reply']!=''){
            //     $model = $model->where('is_reply',$where['is_reply']);
            // }
            if(isset($where['order_id']) && $where['order_id']!=''){
                $model = $model->where('order_id','LIKE',"%$where[order_id]%");
            }
            // if(isset($where['status']) && $where['status']!=''){
            //     $model = $model->where('is_del',$where['status']);
            // }
        }
        return $model;
    }

    /**
     * 礼品卡还原
     */
    public static function cardRevert($id){
        $res= self::where(['id'=>$id])->update(['status'=>0,'send_times_left'=>1]);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}