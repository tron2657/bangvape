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

class CardExchangeLog extends ModelBasic
{
    use ModelTrait;
    public static function addLog($card_id,$order_id){
        $uid=get_uid();
        $newlog=[
            'uid'=>$uid,
            'card_id'=>$card_id,
            'order_id'=>$order_id,
            'add_time'=>time(),
            'status'=>0//0正常-1退款
        ];
        $res=self::set($newlog,true);
        return true;
    }
}
?>