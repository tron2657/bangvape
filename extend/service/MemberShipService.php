<?php
 
namespace service;
use think\Db;

use app\admin\model\system\SystemGroupData;
use think\Cache;
use app\admin\model\ump\StoreCoupon as CouponModel;
use app\admin\model\ump\StoreCouponUser as CouponUserModel;
use app\admin\model\system\SystemConfig;
use app\admin\model\user\MemberCouponPlan;

class MemberShipService extends GroupDataService
{
 

    /**
     * 会员权益
     * @param $config_name
     * @param int $limit
     * @return mixed
     */
    public static function config()
    {
        
        if(Cache::has('system_config_cache'))
        {
            return Cache::get('system_config_cache');
        }

        $arr=[];
        $view= Db::view('system_config_tab','eng_title')
        ->view('system_config','*','system_config_tab.id=system_config.config_tab_id')
        ->where('eng_title','like','membership_%')
        ->select();
        // ->where('eng_title','MemberShip')
     
        foreach($view as $item)
        {            
            if(!isset($arr[$item['eng_title']]))
            {
                $arr[$item['eng_title']]=[];
            }

            $obj=&$arr[$item['eng_title']];
            // $obj[$item['menu_name']]= trim($item['value'],'"');
            $obj[$item['menu_name']]=   json_decode($item['value'],true)?:'';
        }

        Cache::set('system_config_cache',$arr,5);
        return $arr;
        // // GroupDataService::getDataDic('membership_interests',3)?:[];
        // return parent::getDataDic('membership_interests',3)?:[];
    }


    public static function grant_gifit($uid,$seq,$group_id,$souce,&$insertData)
    { 
        
        $config=SystemConfig::getMore('membership_liquan_enable_gifit,membership_liquan_gifit_vip_days,membership_liquan_gifit_vip_coupon,membership_liquan_limit_exhcnage_product_id');
        if(!isset($config['membership_liquan_limit_exhcnage_product_id']))
        {  
           $config['membership_liquan_limit_exhcnage_product_id']='';
        }
        
        if(!isset($config['membership_liquan_enable_gifit']))//是否开启礼物赠送的功能
        {  
           $config['membership_liquan_enable_gifit']=0;
        }
        if(!isset($config['membership_liquan_gifit_vip_days']))//满足条件后，默认赠送的优惠券天数，默认30天为一个月
        {  
           $config['membership_liquan_gifit_vip_days']=30;
        }
        if(!isset($config['membership_liquan_gifit_vip_coupon']))//满足条件后，默认赠送的优惠券的Ids
        {  
           $config['membership_liquan_gifit_vip_coupon']=false;
        }
   

        if($config['membership_liquan_limit_exhcnage_product_id']!='')
        {
         
           $souce['limit_product_ids']=explode(',',$config['membership_liquan_limit_exhcnage_product_id']);
        }

        if($config['membership_liquan_enable_gifit']==1)//开启礼物赠送的功能
        {
             
            //赠送优惠券的ID            
            $cids=explode(',',$config['membership_liquan_gifit_vip_coupon']);     
            $end=end($insertData);
            foreach($cids as $cid)
            {
                $coupon = CouponModel::get($cid)->toArray();
                if(!$coupon)
                {
                    array_push($err_msg,$cid.'数据不存在!');
                }
                else
                {        
                
                    $item['start_time']=bcadd(bcmul(1, 86400, 0), $end['end_time'], 0);
                    $item['end_time']=bcadd(bcmul($config['membership_liquan_gifit_vip_days'], 86400, 0), $end['end_time'], 0);
                    $item['end_time']=bcadd(86399, $item['end_time'], 0);//处理为 年月日 23:59:59
                    $data=
                    [
                        'cid'=>$cid,
                        'uid'=>$uid,
                        'coupon_title'=>$coupon['title'],
                        'coupon_price'=>$coupon['coupon_price'],
                        'use_min_price'=>$coupon['use_min_price'], 
                        'plan_grant_time'=>time(),                   
                        'start_time'=>$item['start_time'],
                        'seq'=>$seq,
                        'is_fail'=>1,
                        'end_time'=>$item['end_time'],
                        'souce_type'=>3,//三是代表额外赠送的优惠券
                        'group_id'=>$group_id,                    
                        'attach'=>addslashes(json_encode($souce))
                    ];     
                                                       
                    $insertData[]=$data;
                  
                    // Db::name('member_coupon_plan')->insertAll($insertData);
                    // \app\admin\model\user\MemberCouponPlan::insertAll($insertData);
                }
            }
        }
    
    }

    // public static function get_grant_frist_coupon()
    // {

    //     $grant_time=[
    //         'start_time'=>$time,
    //         'end_time'=>$overdue_time,
    //         'is_kt'=>true
    //   ];

    //     self::get_grant_coupon($uid,[''])
    // }

    /**
     * 获取赠送的优惠券
     *
     * @param [type] $uid
     * @param [type] $grant_time
     * @param [type] $souce
     * @return array
     */
    public static function get_grant_coupon($uid,$grant_time,$souce,$souce_type=0){ 
 
        $config=SystemConfig::getMore('membership_liquan_grant_method,membership_liquan_grant_cyle_days,membership_liquan_coupon,membership_liquan_enable_gifit,membership_liquan_gifit_vip_days,membership_liquan_gifit_vip_coupon');
        if(!isset($config['membership_liquan_grant_cyle_days']) )
        {
            $config['membership_liquan_grant_cyle_days']=90;
        }
        $cyle_days=intval($config['membership_liquan_grant_cyle_days']);
        $start=strtotime(date('Y-m-d', $grant_time['start_time']));//获得当天日期
        $end=strtotime(date('Y-m-d ',$grant_time['end_time']));//获得结束日期时间
        $coupon_data=[];
        $seq=1;
        for($i=$start;$i<$end;$i=bcadd(bcmul($cyle_days, 86400, 0), $i, 0))
        {
            $item=[
                'seq'=>$seq,
                'start_time'=>$i,
                'end_time'=>bcadd(bcmul($cyle_days, 86400, 0), $i, 0)
            ];
            $item['end_time']=bcadd(86399, $item['end_time'], 0);//处理为 年月日 23:59:59
            if($seq>1)
            {
                $item['start_time']=bcadd(bcmul(1, 86400, 0), $item['start_time'], 0);
            }
            $coupon_data[]=$item;          
        
            $seq=$seq+1;
        }
        
        //
        $err_msg=[];
        $success_data=[];
 
        
        $grant_method=$config['membership_liquan_grant_method'];//发放方式
        $cids=$config['membership_liquan_coupon'];//赠送优惠券的ID        
        $group_id=md5(uniqid(mt_rand(), true));

        $cids=explode(',',$cids);     
        $insertData=[];           
        foreach($cids as $cid)
        {
            $coupon = CouponModel::get($cid)->toArray();
            if(!$coupon)
            {
                array_push($err_msg,$cid.'数据不存在!');
            }
            else
            {        
                foreach($coupon_data as $item)
                {
                    $data=
                    [
                        'cid'=>$cid,
                        'uid'=>$uid,
                        'coupon_title'=>$coupon['title'],
                        'coupon_price'=>$coupon['coupon_price'],
                        'use_min_price'=>$coupon['use_min_price'], 
                        'plan_grant_time'=>time(),                   
                        'start_time'=>$item['start_time'],
                        'seq'=>$item['seq'],
                        'is_fail'=>$item['seq']==1?0:1,//第一张优惠券激活状态，剩余的在后面的领取核销的阶段进行激活
                        'end_time'=>$item['end_time'],
                        'souce_type'=>$souce_type,
                        'group_id'=>$group_id,
                        'attach'=>addslashes(json_encode($souce))
                    ];    
                 
                 
                    
                    if($grant_method==1)//按照优惠券生效时间发放,延期发放
                    {
                        if($grant_time['is_kt']==true)//如果是开通默认送第一章券
                        {
                            if($item['seq']==1)//如果是第一张券，立即赠送
                            {
                                $data['plan_grant_time']=time();
                            }
                        }else{
                            $data['plan_grant_time']=$item['start_time'];
                        }                                                               
                    }              
                                                      
                    $insertData[]=$data;
                } 
                // Db::name('member_coupon_plan')->insertAll($insertData);
                // \app\admin\model\user\MemberCouponPlan::insertAll($insertData);
            }
        }
        // self::grant_gifit($uid,$seq,$group_id,$souce,$insertData);
        return $insertData;
    }

    /**
     * 赠送每月VIP用户优惠券  (制定计划)
     *
     * @return void
     */
    public static function grant_vip_user($uid,$grant_time,$souce)
    {
        $res=[
            'success'=>false,
            'msg'=>'',
            'data'=>null
        ];

        $souce['member_ship']=Db::name('member_ship')->get($souce['member_id'])->find();
        
        $start=strtotime(date('Y-m-d', $grant_time['start_time']));//获得当天日期
        $end=strtotime(date('Y-m-d',$grant_time['end_time']));//获得结束日期时间
        $coupon_data=[];
        $seq=1;
 
        for($i=$start;$i<$end;$i=bcadd(bcmul(30, 86400, 0), $i, 0))
        {
            $item=[
                'seq'=>$seq,
                'start_time'=>$i,
                'end_time'=>bcadd(bcmul(30, 86400, 0), $i, 0)
            ];
            $item['end_time']=bcadd(86399, $item['end_time'], 0);//处理为 年月日 23:59:59
            if($seq>1)
            {
                $item['start_time']=bcadd(bcmul(1, 86400, 0), $item['start_time'], 0);
            }
            $coupon_data[]=$item;
            
            $seq=$seq+1;
        }
 
        $err_msg=[];
        $success_data=[];
 
        $config=SystemConfig::getMore('membership_liquan_grant_method,membership_liquan_coupon');
        $grant_method=$config['membership_liquan_grant_method'];//发放方式
        $cids=$config['membership_liquan_coupon'];//赠送优惠券的ID        
        $group_id=md5(uniqid(mt_rand(), true));

        $cids=explode(',',$cids);        
        foreach($cids as $cid)
        {
            $coupon = CouponModel::get($cid)->toArray();
            if(!$coupon)
            {
                array_push($err_msg,$cid.'数据不存在!');
            }
            else
            {     
                $insertData=[];           
                foreach($coupon_data as $item)
                {
                    $data=
                    [
                        'cid'=>$cid,
                        'uid'=>$uid,
                        'coupon_title'=>$coupon['title'],
                        'coupon_price'=>$coupon['coupon_price'],
                        'use_min_price'=>$coupon['use_min_price'], 
                        'plan_grant_time'=>time(),                   
                        'start_time'=>$item['start_time'],
                        'seq'=>$item['seq'],
                        'end_time'=>$item['end_time'],
                        'souce_type'=>0,
                        'group_id'=>$group_id,
                        'attach'=>addslashes(json_encode([
                            'order_id'=>$souce['order_id'],
                            'member_id'=>$souce['member_id'],
                            'member_text'=> $souce['member_ship']['title']
                            ]))
                    ];    
                 
                    
                    if($grant_method==1)//按照优惠券生效时间发放,延期发放
                    {
                        if($grant_time['is_kt']==true)//如果是开通默认送第一章券
                        {
                            if($item['seq']==1)//如果是第一张券，立即赠送
                            {
                                $data['plan_grant_time']=time();
                            }
                        }else{
                            $data['plan_grant_time']=$item['start_time'];
                        }                                                               
                    }              
                                                      
                    $insertData[]=$data;
                } 
                Db::name('member_coupon_plan')->insertAll($insertData);
                // \app\admin\model\user\MemberCouponPlan::insertAll($insertData);
            }

        }

        \app\admin\model\user\MemberCouponPlan::grant_coupon($uid);
        if(count($err_msg)==0)
        {
            $res['success']=true;
            $res['data']=$success_data;
            $res['msg']='发放计划成功!';

            // return Json::successful('',$success_data);
        }
        else{
            $msg=implode(',',$err_msg);
            $res['success']=false;            
            $res['msg']=$msg;
            // return Json::fail($msg );
        }  
        
        return $res;
  
    }
 
}