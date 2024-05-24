<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/18
 */

namespace app\core\behavior;

use app\admin\model\event\Event;
use app\routine\model\Order\StoreOrder ;
use app\routine\model\routine\RoutineTemplate;
use app\routine\model\user\User;
use app\routine\model\user\WechatUser;
use app\routine\model\user\UserAddress;
use app\admin\model\order\StoreOrder as StoreOrderAdminModel;
use service\SystemConfigService;
use service\WechatTemplateService;
use think\Db;
use think\Exception;
use app\admin\model\system\SystemConfig;
class EventBehavior
{

 
 
    /**
     * 核销人核销完毕(执行后)
     * @param $order
     * @param $uid
     */
    public static function eventSureCheckAfter($enroll, $code='',$successFun=null,$sccessFunParms=null)
    { 
      $uid=$enroll['uid'];
      $config=SystemConfig::getMore('membership_liquan_grant_vip_days,membership_liquan_gifit_vip_month,membership_liquan_enable_event_active,membership_liquan_limit_exhcnage_product_id');
      if(!isset($config['membership_liquan_grant_vip_days']))//默认赠送优惠券天数
      {  
         $config['membership_liquan_grant_vip_days']=360;
      }
      if(!isset($config['membership_liquan_enable_event_active']))
      {  
         $config['membership_liquan_enable_event_active']=0;
      }

     
      if($config['membership_liquan_enable_event_active']==0)
      {
    
         echo('未开启优惠券赠送活动');
         return;
      }

      //判断是否有计划的优惠券
      $query=Db::name('member_coupon_plan')->where('uid',$enroll['uid'])->where('souce_type',3);
      $user_plan_count=$query->count();
      if($user_plan_count>0)
      {        
 
         return;            
      }
 
      Db::startTrans();

      try
      { 
     

         $time=strtotime(date('Y-m-d', time()));
         $overdue_time = bcadd(bcmul($config['membership_liquan_grant_vip_days'], 86400, 0), $time, 0);
 

         $grant_time=[
               'start_time'=>$time,
               'end_time'=>$overdue_time,
               'is_kt'=>true
         ];

         Db::name('user')->where('uid',$uid)->where('overdue_time',0)->update(
            [
               'overdue_time'=>$overdue_time
            ]
         );

         $data=[
               'event_id'=>$enroll['event_id'],
               'uid'=>$enroll['uid'],
               'purchase_time'=>$time,
               'overdue_time'=>$overdue_time,
               'add_time'=>time(),
         ];

         Db::name('member_event_record')->insert($data);

         $event=Event::get($enroll['event_id']);
         $souce=[
            'event_id'=>$enroll['event_id'],
            'event_text'=>$event['title'],
            'enroller_id'=>$enroll['id'],
            'enroller_code'=>$enroll['code'],
            'limit_product_ids'=> []
         ];

         
         $grant_coupon=\service\MemberShipService::get_grant_coupon($enroll['uid'],$grant_time,$souce,1);

         Db::name('member_coupon_plan')->insertAll($grant_coupon);

         if($successFun!=null)
         {
            $successFun($sccessFunParms);
         }

         Db::commit();

         \app\admin\model\user\MemberCouponPlan::grant_coupon($uid);
         
      }
      catch(Exception $ex)
      {
      
         Db::rollback();
         throw $ex;
      }

     
    }
    public static function eventSureCheck()
    {
      //  echo('eventSureCheck');
      //  echo('<br>');
    }
 

    /**
     * 核销人核销完毕(执行前)
     * @param $order
     * @param $uid
     */
    public static function eventSureCheckBefore()
    {
      //  echo('eventSureCheckBefore');
      //  echo('<br>');
    }
 
}