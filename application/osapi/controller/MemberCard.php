<?php


namespace app\osapi\controller;

use app\admin\model\event\EventEnroller;
use app\osapi\model\com\ComForumMember;
use app\osapi\model\com\ComThread;
use app\osapi\model\user\UserFollow;
use app\admin\model\system\SystemConfig;
use app\admin\model\user\MemberCard as UserMemberCard;
use think\Exception;

class MemberCard extends Base
{
    
 
    private function check_active($cardsn)
    {
        $uid=$this->_needLogin();      
       
        try{

            if(UserMemberCard::where('use_uid',$uid)->count()>0)
            {
                return ['is_fail'=>true,'err_code'=>'003','data'=>null, 'msg'=>'您已经激活过VIP用户，无法再次激活哦'];
            }
            if($cardsn==null || $cardsn=='')
            {
                return ['is_fail'=>true,'err_code'=>'006','data'=>null, 'msg'=>'当前序列号参数错误，为空值'];
            }
            $card=UserMemberCard::where('card_sn',$cardsn)->find();
            if($card==null)
            {
                return ['is_fail'=>true,'err_code'=>'004','data'=>null, 'msg'=>'当前序列号不存在'.$cardsn];
            }
            if($card['status']==0)
            {
                return ['is_fail'=>true,'err_code'=>'005','data'=>null, 'msg'=>'当前序列号已经失效'];
            }
            if($card['use_uid'] &&  $card['use_uid']>0)
            {
              
                if($card['use_uid']!=$uid)
                {
                    $create_time= date('Y-m-d H:i',$card['create_time']) ;            
                    return ['is_fail'=>true,'err_code'=>'001','data'=>null, 'msg'=>'当前瓶盖已被其他用户在'.$create_time.'激活'];
                }else
                {
                    return ['is_fail'=>true,'err_code'=>'002','data'=>null, 'msg'=>'当前瓶盖已激活'];
                }
            }
            return ['is_fail'=>false,'data'=>$card];
        }catch(\Exception $ex){
            throw $ex;
        }    
    }

    /**
     * 序列号是否被当前用户激活
     */
    public function vaild_active($cardsn,$event_id)
    {
         
        try{

            $res=self::check_active($cardsn,$event_id);
            return $this->apiSuccess($res);
        }catch(\Exception $ex){
            return $this->apiSuccess(false,$ex->getMessage());
        }        
    }

    /**
     * 自动完成报名跟活动核销
     */
    private function auto_enroll($event_id)
    {
        //自动完成报名的程序
 
        $uid=$this->_needLogin();
 
         //不能重复报名
         $enroll_count=EventEnroller::where(['uid'=>$uid])->where('status','in','1,2')->count();
         if($enroll_count==0){
            $code=$uid;
            EventEnroller::insert([
                'uid'=>$uid,
                'event_id'=>$event_id,
                'status'=>1,
                'check_uid'=>$uid,
                'check_time'=>time(),
                'create_time'=>time(),
                'code'=>$code
            ]);
         }
  
             
    }

    /**
     * 激活VIP卡
     */
    public function card_active($cardsn,$event_id)
    {
        // return $this->apiFailed(false,'此功能暂未开放');
        try{
            $uid=$this->_needLogin();      
            self::auto_enroll($event_id);
            $card= self::check_active($cardsn);
            if($card['is_fail']==true)
            {
                throw new Exception($card['msg']);
            }
            UserMemberCard::active($cardsn,$uid,$event_id);
            
            return $this->apiSuccess(true,'激活成功');

        }catch(Exception $ex)
        {
            return $this->apiFailed(false,$ex->getMessage());
        }
    }

}