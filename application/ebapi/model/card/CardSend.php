<?php
namespace app\ebapi\model\card;

use basic\ModelBasic;
use think\Cache;
use traits\ModelTrait;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreCart;
use app\ebapi\model\user\User;
use app\core\model\user\UserBill;
use app\ebapi\model\card\CardStatus;
use app\ebapi\model\card\Card;

class CardSend extends ModelBasic
{
    use ModelTrait;

    /**
     * 赠送
     */
    public static function send_to($card_id,$uid,$message){
        $card=Card::where(['id'=>$card_id,'uid'=>$uid,'status'=>0])->find();
        if(!$card) return self::setErrorInfo('礼品卡不存在',false,true);
        if($card['status']!=0)  self::setErrorInfo('礼品卡不存在',false,true);
        $cardSendInfo=[
            'send_uid'=>$uid,
            'card_id'=>$card_id,
            'message'=>$message,
            'recieve_code'=>create_rand(7),
            'status'=>0,
            'add_time'=>time()
        ];
        self::beginTrans();
        $res1= Card::where(['id'=>$card_id])->update(['status'=>2,'send_times_left'=>0]);
        $res2=self::set($cardSendInfo,true);
        $res3=CardStatus::status($card_id,'send','赠送',time(),'-1','-1');
        $res=$res1&&$res2&&$res3;
        
        self::checkTrans($res);
        if($res){
            // $result->result=true;
            // $result->recieve_code=$cardSendInfo['recieve_code'];
            $result['result']=true;
            $result['recieve_code']=$cardSendInfo['recieve_code'];
            return $result;
        }
        return self::setErrorInfo('出错了',false,true);
    }

    /**
     * 取消赠送
     */
    public static function send_cancel($card_id,$uid){
        $card=Card::where(['id'=>$card_id,'uid'=>$uid])->find();
        if(!$card) return false;
        if($card['status']!=2) return false;
        self::beginTrans();
        $res1= Card::where(['id'=>$card_id])->update(['status'=>0,'send_times_left'=>1]);
        $res2=self::where('card_id',$card_id)->update(['status'=>-1]);
        $res3=CardStatus::status($card_id,'send','撤销赠送',time(),'+1','+1');
        $res=$res1&&$res2;
        self::checkTrans($res);
        return $res;
    }

    public static function recieve_card($recieve_code){
        $card=self::where(['s.recieve_code'=>$recieve_code])->alias('s')->join('Card c','s.card_id=c.id')
        ->join('StoreProduct p','c.product_id=p.id')
        ->join('User u','s.send_uid=u.uid')
        ->field('s.message,s.recieve_code,s.status,p.store_name,p.image,u.nickname,u.avatar')->find();
        return $card;
    }
    
    /**
     * 确认接收礼品卡
     */
    public static function recieve_card_confirm($recieve_code){    
        $card_send=self::where(['recieve_code'=>$recieve_code,'status'=>0])->find();
        $card=Card::where(['id'=>$card_send['card_id'],'status'=>2])->find();
        if(!$card)return self::setErrorInfo('礼品卡不存在，或已领取');;
        $uid=get_uid();
        self::beginTrans();
        //礼品卡换人
        if($card['uid']==$uid){
            return self::setErrorInfo('不能自己送自己');
        }
        $send_path=$card['send_path'].'/'.$uid;
        $res1=Card::where(['id'=>$card_send['card_id']])->update(['from_uid'=>$card['uid'],'uid'=>$uid,'send_path'=>$send_path,'status'=>0,'send_times_left'=>1]);
        $res2=self::where('recieve_code',$recieve_code)->update(['status'=>1,'recieve_uid'=>$uid,'recieve_time'=>time()]);
        $res=$res1&&$res2;
        self::checkTrans($res);
        // $result->result=true;
        // return $result;
        return $res;
    }

    /**
     * 赠送历史
     */
    public static function send_history($status,$uid){
        if($status=='send'){
            $list=db('card_send')->where(['send_uid'=>$uid])->alias('s')->join('Card c','s.card_id=c.id')
            ->join('StoreProduct p','c.product_id=p.id')
            ->join('User u ',' s.recieve_uid=u.uid','left')
            ->field('s.card_id,s.message,s.recieve_code,s.status,p.store_name,p.image,u.nickname,u.avatar,s.add_time')->select();


            // $list= CardSend::where(['send_uid'=>$uid])->alias('s')->join('Card c','s.card_id=c.id')
            // ->join('StoreProduct p','c.product_id=p.id')
            // ->join(' left join User u ON s.recieve_uid=u.uid')
            // ->field('s.card_id,s.message,s.recieve_code,s.status,p.store_name,p.image,u.nickname,u.avatar,s.add_time')->select();
            return $list;
        }else{
            $list= CardSend::where(['recieve_uid'=>$uid])->alias('s')->join('Card c','s.card_id=c.id')
            ->join('StoreProduct p','c.product_id=p.id')
            ->join('User u','s.send_uid=u.uid','left')
            ->field('s.card_id,s.message,s.recieve_code,s.status,p.store_name,p.image,u.nickname,s.add_time')->select();
            return $list;
        }
    }

    public static function card_send_history($card_id){
        $list=CardSend::where(['card_id'=>$card_id])
        ->field('add_time,status')->select();
        return $list;
    }
}