<?php

namespace app\admin\model\active;

use app\admin\model\com\ComForum;
use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 活动报名字段 model
 * Class ComForum
 * @package app\admin\model\com
 */
class ActiveField extends ModelBasic
{

    /**
     * 获取绑定的列表
     * @param $id
     * @return array
     */
    public static function getEventField($id){
        $map['status']=1;
        $map['event_id']=$id;
        $data=self::where($map)->select()->toArray();
        return $data;
    }

    /**
     * 设置用户组绑定内容
     * @param $event_id
     * @param $data
     * @return bool
     */
    public static function set_bind_field($event_id,$data){
        self::startTrans();
        $res=self::where(['event_id'=>$event_id])->delete();
        $res2=self::insertAll($data);
        if($res!==false&&$res2){
            self::commit();
            return true;
        }else{
            self::rollback();
            return false;
        }
    }

}