<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/28
 */

namespace app\ebapi\model\card;

use basic\ModelBasic;
use traits\ModelTrait;

class CardStatus extends ModelBasic
{
    use ModelTrait;

    /**
     * card_create 账户激活    trance_balance  转入余额  send 赠送  exchange 兑换 
     */
    public static function status($card_id,$change_type,$change_message,$change_time = null,$remark='',$sys_remark='')
    {
        if($change_time == null) $change_time = time();
        return self::set(compact('card_id','change_type','change_message','change_time','remark','sys_remark'));
    }

    public static function getTime($card_id,$change_type)
    {
        return self::where('card_id',$card_id)->where('change_type',$change_type)->value('change_time');
    }

}