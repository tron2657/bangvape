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
 * 礼品卡记录
 * Class CardStatus
 * @package app\admin\model\store
 */
class CardStatus extends ModelBasic
{
    use ModelTrait;

    /**
     * @param $oid
     * @param $type
     * @param $message
     */
   public static function setStatus($card_id,$type,$message){
       $data['card_id'] = (int)$oid;
       $data['change_type'] = $type;
       $data['change_message'] = $message;
       $data['change_time'] = time();
       self::set($data);
   }

    /**
     * @param $where
     * @return array
     */
    public static function systemPage($card_id){
        $model = new self;
        $model = $model->where('card_id',$card_id);
        $model = $model->order('change_time asc');
        return self::page($model);
    }
    /**
     * @param $where
     * @return array
     */
    public static function systemPageMer($card_id){
        $model = new self;
        $model = $model->where('card_id',$card_id);
//        $model = $model->where('change_type','LIKE','mer_%');
        $model = $model->order('change_time asc');
        return self::page($model);
    }
    
    public static function status($card_id,$change_type,$change_message,$change_time = null,$remark='',$sys_remark='')
    {
        if($change_time == null) $change_time = time();
        return self::set(compact('card_id','change_type','change_message','change_time','remark','sys_remark'));
    }
}