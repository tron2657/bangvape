<?php

namespace app\admin\model\event;

use basic\ModelBasic;
use app\admin\model\system\SystemConfig;
use app\osapi\lib\ChuanglanSmsApi;
use app\osapi\model\user\UserModel;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class EventMessage extends ModelBasic
{

    public static function send_message($message_type,$event_id,$uid=0){
        $set=MessageTemplate::getMessageSet($message_type);
        //消息模板
        $event=Event::where(['id'=>$event_id])->find();
        $template=str_replace('{活动主题}', $event['title'],$set['template']);;
        $template=str_replace('{开始时间}', date('Y-m-d H:i:s',$event['start_time']),$template);
        $template=str_replace('{结束时间}', date('Y-m-d H:i:s',$event['end_time']),$template);
        $send_uid_nickname=db('user')->where(['uid'=>$event['uid']])->value('nickname');
        $template=str_replace('{发起人昵称}', $send_uid_nickname,$template);
        switch ($event['price_type']){
            case 1:
                $score_type=SystemConfig::getValue('event_type_pay');
                $score_type_name=db('system_rule')->where('flag',$score_type)->value('name');
                $price=$event['price'].$score_type_name;
                break;
            case 2:
                $price=$event['price'].'元';
                break;
            default: $price='免费';
        }
        $template=str_replace('{支付费用}',$price,$template);
        $template=str_replace('{取消原因}',$event['cancel_reason'],$template);
        if($message_type==56){
            self::send_message_one($set,$uid,$template,$event_id);
        }else{
            $uids=db('event_enroller')->where(['event_id'=>$event_id,'status'=>1])->column('uid');
            foreach ($uids as $v){
                self::send_message_one($set,$v,$template,$event_id);
            }
        }
    }

    public static function send_message_one($set,$uid,$template,$event_id){
        if($set['id']==56||$set['id']==57){
            $nickname=db('user')->where(['uid'=>$uid])->value('nickname');
            $template=str_replace('{用户名}', $nickname,$template);
        }

        $now_uid=get_uid();
        if($set['status']==1){
            $message_id=Message::sendMessage($uid,$now_uid,$template,1,$set['title'],1,'','event',$event_id);
            $read_id=MessageRead::createMessageRead($uid,$message_id,$set['popup'],1);
        }
        if($set['sms']==1&&$set['status']==1){
            $account=UserModel::where('uid',$uid)->value('phone');
            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
            $template='【'.$config['cl_sms_sign'].'】'.$template;
            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
            $sms=json_decode($sms,true);
            if ($sms['code']==0) {
                $read_data['is_sms']=1;
                $read_data['sms_time']=time();
                MessageRead::where('id',$read_id)->update($read_data);
            }
        }
    }

}