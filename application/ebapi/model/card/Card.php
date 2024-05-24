<?php
namespace app\ebapi\model\card;

use basic\ModelBasic;
use think\Cache;
use traits\ModelTrait;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreCart;
use app\ebapi\model\user\User;
use app\core\model\user\UserBill;
use app\ebapi\controller\StoreApi;
use app\ebapi\model\card\CardStatus;
use app\ebapi\model\user\UserWallet;
use app\osapi\model\common\Support;
class Card extends ModelBasic
{
    use ModelTrait;

    public static function addCardFromOrder($order_id){
        if(self::be(['order_id'=>$order_id])) return self::setErrorInfo('已生成过卡');
        $order=StoreOrder::where('order_id',$order_id)->find();
        if(!$order)return self::setErrorInfo('订单不存在');
        $cart_id=$order['cart_id'][0];
        $cart=StoreCart::where('id',$cart_id)->find();
        if(!$cart)return self::setErrorInfo('订单信息不存在');
        self::beginTrans();
        for($i=1;$i<=$order['total_num'];$i++){
            $pay_price=bcdiv($order['pay_price'],$order['total_num'],3);      
            $newCard=[
                'order_id'=>$order_id,
                'uid'=>$order['uid'],
                'product_id'=>$cart['product_id'],
                'pay_price'=>$pay_price,
                'status'=>0,
                'send_times_left'=>1,
                'send_path'=>'/'.$order['uid'],
                'add_time'=>time()
            ];
            $card=self::set($newCard,true);
            if(!$card)return self::setErrorInfo('礼品卡生成失败!');
            CardStatus::status($card['id'],'card_create','账户激活',time(),'+1','账户激活次数+1');
        }
        self::commitTrans();
        return true;
        // $newCard=[
        //     'order_id'=>$order_id,
        //     'uid'=>$order['uid'],
        //     'product_id'=>$cart['product_id'],
        //     'pay_price'=>$order['pay_price'],
        //     'status'=>0,
        //     'send_times_left'=>1,
        //     'send_path'=>'/'.$order['uid'],
        //     'add_time'=>time()
        // ]; 
        // $card=self::set($newCard,true);
        // if(!$card)return self::setErrorInfo('礼品卡生成失败!');
        // CardStatus::status($card['id'],'card_create','账户激活',time(),'+1','账户激活次数+1');
        // return $newCard;
    }

    public static function getNewOrderId()
    {
        $count = (int) self::where('add_time', ['>=', strtotime(date("Y-m-d"))], ['<', strtotime(date("Y-m-d", strtotime('+1 day')))])->count();
        return 'ctb' . date('YmdHis', time()) . (10000 + $count + 1);
    }


     /**
     * 转入余额 $status=1兑换 3转余额
     */
    public static function cardToBalance($id,$status=3){
        $userId=get_uid();
        $card=self::where(['id'=>$id,'uid'=>$userId])->find();
        if(!$card) return false;
        if($card['status']!=0) return false;
        $user=User::where('uid',$card['uid'])->find();
        // $user=User::getUserInfo($card['uid']);
        self::beginTrans();
        // $res1 = self::where('id'$id)->update(['status'=>2,'pay_time'=>time()]);
        $res1 = self::where('id',$id)->update(['status'=>$status,'send_times_left'=>0]);
        $res2 = UserBill::income('用户礼品卡转余额',$card['uid'],'now_money','recharge',$card['pay_price'],$card['id'],$user['now_money'],'用户礼品卡转余额'.floatval($card['pay_price']).'元');
        // $res3 = User::edit(['now_money'=>bcadd($user['now_money'],$card['pay_price'],2)],$card['uid'],'uid');
        $res3 = UserWallet::bcInc($card['uid'], 'all_money', $card['pay_price'], 'uid');
        $res4 = UserWallet::bcInc($card['uid'], 'enable_money', $card['pay_price'], 'uid');

        $order_id=self::getNewOrderId();
        $user_order=[
            'order_id'=>$order_id,
            'uid'=>$userId,
            'amount_type'=>1,
            'status'=>1,
            'create_time'=>time(),
            'bind_table'=>'card_order',
            'order_type'=>3,
            'amount'=>$card['pay_price'],
            'pay_time'=>time(),
            'pay_type'=>'yue',
             'unique'=>md5(time()),
            'info'=>'礼品卡转余额',
        ];

        $user_order=db('user_order')->insert($user_order);
        if(!$user_order)  return self::setErrorInfo('订单生成失败!',true);


        // if($order['gain_integral']>0)
        // {
        //     // 转入余额进行减分操作
        //     $user_score=User::where('uid',$userId)->value('buy');
        //     $nums=$user_score-$order['gain_integral'];
        //     $res3=User::where('uid',$order['uid'])->update(['buy'=>$nums]);
        //     if($res3===false) return self::setErrorInfo('积分扣除失败!',true);
        //     $log=[];
        //     $log['buy']=$order['gain_integral'];
        //     Support::jiafenlog($order['uid'],'转入余额赠送积分扣除',$log,0,'行为');
        // }

        // self::where('id',$id)->update(['status'=>3]);
        $remark=$card['pay_price'].'元';
        CardStatus::status($id,'trance_balance','转入余额',time(),$remark,$remark);
        $res = $res1 && $res2 && $res3;
        self::checkTrans($res);

        if($res)
        {
            // 获取订单
            $order=\app\ebapi\model\store\StoreOrder::where('order_id',$card['order_id'])->find()  ;
            if($order['score_num']>0)
            {
                $user_score=User::where('uid',$order['uid'])->value('buy');
                $nums=$user_score+$order['score_num'];
                $res3=User::where('uid',$order['uid'])->update(['buy'=>$nums]);
                if($res3===false) return self::setErrorInfo('积分恢复失败!',true);
                $log=[];
                $log['buy']=$order['score_num'];
                Support::jiafenlog($order['uid'],'转入余额积分抵现返还',$log,1,'行为');
            }
        }
      

        return $res;
    }

}