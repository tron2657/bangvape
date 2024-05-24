<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\card;

use traits\ModelTrait;
use basic\ModelBasic;

/**
 * 礼品卡赠送记录
 * Class CardStatus
 * @package app\admin\model\store
 */
class CardSend extends ModelBasic
{
    use ModelTrait;

     /**
     * @param $where
     * @return array
     */
    public static function systemPage($card_id){
        $model = new self;
        $model = $model->where('card_id',$card_id);
        $model = $model->order('add_time desc');
        return self::page($model);
    }
}