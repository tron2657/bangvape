<?php
 

namespace app\core\behavior;

use app\admin\model\event\Event;
use app\routine\model\Order\StoreOrder ;
use app\routine\model\routine\RoutineTemplate;
 
use app\routine\model\user\WechatUser;
 
use service\SystemConfigService;
use service\WechatTemplateService;
use think\Db;
use think\Exception;
use app\core\util;
use app\admin\model\system\SystemConfig;
use app\ebapi\model\user;
class ActiveBehavior
{

  /**
   * 组队成功后
   *
   * @return void
   */
  public static function activeFinishAfter($enroll)
  {
 
    
    // $res= RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($enroll['uid']),RoutineTemplateService::COUPON_EXPIRE, [                          
    //   'time1'=>['value'=>date('Y/m/d H:i',$item['end_time'])],
    //   'thing2'=>['value'=>'优惠券到期'],
    //   'thing3'=>['value'=>'您好，您有一张优惠券即将到期，请尽快使用'],
    // ],'','/packageC/coupon-page/mycoupon');
  // echo('用户('.$item['uid'].')-优惠券ID('.$item['cid'].')的提醒,结果'.$res['errmsg'].'<br>');
  }
  
}