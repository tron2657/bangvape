<?php


namespace app\admin\model\com;

use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\com\ComForum as ForumModel;
/**
 * Class ComThreadClass
 * @package app\admin\model\store
 */
class ComThreadClassExtend extends ModelBasic
{
    use ModelTrait;

    // 自动写入时间戳
    protected $autoWriteTimestamp = 'datetime';
    protected $dateFormat         = 'Y-m-d H:i:s';


    public static function get_runing_state($st_time,$et_time){
        $time=time();
        // \app\admin\model\com\ComThreadClassExtend::get_runing_state($classItem['tg_start_time'],$classItem['tg_end_time']);/
        $state=0;
        if($time>=$st_time &&  $time<$et_time)
        {
            $state=1;//进行中
        }
        else if($time<$st_time)
        {
            $state=2;//未开始
        }
        else if($time> $et_time)
        {
            $state=3;//已结束
        }      
        return $state;  
    }
  
}