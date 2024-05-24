<?php

namespace app\ebapi\model\trial;

use app\admin\model\com\ComForum;
use app\osapi\model\BaseModel;
use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class TrialField extends BaseModel
{

    /**
     * 获取提交数据
     * @param $id
     * @return array
     */
    public static function getEventField($id){
        $map['status']=1;
        $map['event_id']=$id;
        $data=self::where($map)->column('field');
        return $data;
    }

    /**
     * 获取报名字段
     * @param $id
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
   public static function get_event_datum($id){
       $map['status']=1;
       $map['event_id']=$id;
       $event_field=self::where($map)->select()->toArray();
       $field=array_column($event_field,'field');
       $field_choose=array_column($event_field,'is_need','field');
       $datum=db('certification_datum')->where(['field'=>['in',$field]])->field('field,form_type,input_tips,name,setting')->select();
       $user=db('user')->where(['uid'=>get_uid()])->field('nickname,sex,phone')->find();
       $field_must=['nc'=>'nickname','xb'=>'sex','sjh'=>'phone'];
       foreach ($datum as &$v){
           $v['is_need']=$field_choose[$v['field']];
           if(array_key_exists($v['field'],$field_must)&&$user){
                if($v['field']=='xb'){
                    if($user[$field_must[$v['field']]]==0){
                        $v['content']='保密';
                    }elseif ($user[$field_must[$v['field']]]==1){
                        $v['content']='男';
                    }else{
                        $v['content']='女';
                    }
                }else{
                    $v['content']=$user[$field_must[$v['field']]];
                }
           }else{
               $v['content']='';
           }
       }
       return $datum;
   }

}