<?php

 
namespace app\ebapi\model\member;

use app\admin\model\system\SystemConfig;
use app\admin\model\ump\StoreCouponUser;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Db;
use app\ebapi\model\user\User;
use app\ebapi\model\member\MemberRecord;
use app\core\model\UserLevel;

class MemberShip extends ModelBasic
{
    use ModelTrait;

    public static function membershipList(){
        $list=self::where('is_publish',1)->where('is_del',0)->where('type',1)->where('is_free',0)->where('is_permanent',0)->order('sort DESC')->select();
        $list=$list ? $list->toArray() :[];
        foreach ($list as &$vc){
            $vc['sale']=bcsub($vc['original_price'],$vc['price'],2);
            switch ($vc['vip_day']){
                case 30:
                    $vc['unit']='月';
                  break;
                case 90:
                    $vc['unit']='季';
                   break;
                case 365:
                    $vc['unit']='年';
                   break;
                case -1:
                    $vc['unit']='永久';
                    break;
            }
        }
        return $list;
    }

    public static function getUserMember($order,$userInfo){
        $time=strtotime(date('Y-m-d', time()));
         
        $grant_time=[
            'start_time'=>$time,
            'end_time'=>0,
            'is_kt'=>true
        ];
        $member=self::where('is_publish',1)->where('is_del',0)->where('type',1)->where('id',$order['member_id'])->find();
        if(!$member) return false;
        $is_permanent=0;
        if($member['is_permanent']){
            $is_permanent=1;
            $overdue_time=0;
        }else {
            if($userInfo['overdue_time']==0)//第一次开通会员
            {               
                $overdue_time = bcadd(bcmul($member['vip_day'], 86400, 0),$time,0); 
        
            }
            else if($userInfo['overdue_time']>0 && time()<= $userInfo['overdue_time'])  //正在会员期间续费
            {
                $overdue_time = bcadd(bcmul($member['vip_day'], 86400, 0), $userInfo['overdue_time'], 0);
                $grant_time['start_time']=$userInfo['overdue_time'];       
                $grant_time['is_kt']=false;
            }
            else if(time()>$userInfo['overdue_time'])//已过期，再次续费
            {
                $overdue_time = bcadd(bcmul($member['vip_day'], 86400, 0), $time, 0);
 
            } 
            $grant_time['end_time']=$overdue_time; 
        }
        $data=[
            'oid'=>$order['id'],
            'uid'=>$order['uid'],
            'price'=>$member['price'],
            'validity'=>$member['vip_day'],
            'purchase_time'=>$time,
            'is_permanent'=>$is_permanent,
            'is_free'=>$member['is_free'],
            'overdue_time'=>$overdue_time,
            'add_time'=>time(),
        ];
        $res1=MemberRecord::set($data);
        if($res1) $res2=User::edit(['level'=>1,'overdue_time'=>$overdue_time,'is_permanent'=>$is_permanent],$order['uid'],'uid');
        // 赠送每月的优惠券给VIP用户
        \service\MemberShipService::grant_vip_user($order['uid'],$grant_time,$order);
        
        // $res2=UserLevel::setUserLevel($order['uid'],$member['level_id']);

        $res3=$res2 && $res1;
        return $res3;
    }

    /**价格最低的会员
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function memberMinOne(){
         $member=self::where('is_publish',1)->where('is_del',0)->where('type',1)->where('is_free',0)->order('price ASC')->find();
        if($member) {
            $member=$member->toArray();
            switch ($member['vip_day']){
                case 30:
                    $member['unit']='月';
                  break;
                case 90:
                    $member['unit']='季';
                   break;
                case 365:
                    $member['unit']='年';
                   break;
                case -1:
                    $member['unit']='永久';
                    break;
            }
        }
        return $member;
    }

    /**
     * 免费
     */
    public static function memberFree($uid){
        $free=self::where('is_publish',1)->where('is_del',0)->where('type',1)->where('is_free',1)->find();
        $data['free']=$free ? $free->toArray() :[];
        $data['is_record'] = 0;
        if($data['free']) {
            $record = MemberRecord::where('uid', $uid)->where('is_free', 1)->find();
            if ($record!=null && count($record)>0) $data['is_record'] = 1;
        }
        return $data;
    }

    /**
     * 会员过期 true 过期，false 未过期
     */
    public static function memberExpiration($uid){

        $config=SystemConfig::getMore('open_membership');
        if(isset($config['open_membership'])){
            if($config['open_membership']=='1') {//开通会员权益模块
                $user=User::where('uid',$uid)->find();
                if($user['overdue_time']==0 )
                    return true;//不是会员默认为已过期
                // if($user['level'] && $user['is_permanent']==0 &&  bcsub($user['overdue_time'],time(),0)<=0){
                if($user['is_permanent']==0 &&  bcsub($user['overdue_time'],time(),0)<=0){
                        User::edit(['level'=>0],$uid,'uid');
                        return true;
                }
                return false;
            }
            return true;        
        } 
        return true;
    }

    public static function isMemberExpiration($uid)
    {
        $userCount=User::where('uid',$uid)
        ->where('level','>',0)
        ->count();
        return $userCount>0;
    }
}