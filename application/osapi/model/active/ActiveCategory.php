<?php

namespace app\osapi\model\event;

use app\osapi\model\BaseModel;


/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class ActiveCategory extends BaseModel
{
    /**
     * 获取所有分类
     */
    public static function get_event_cate_list(){
        $cate_pid=self::where(['pid'=>0,'status'=>1])->order('sort desc')->select();
        $cate_id=self::where(['pid'=>['gt',0],'status'=>1])->order('sort desc')->select();
        $newArr=[];
        foreach ($cate_id as $k => $val) {
            $newArr[$val['pid']][] = $val;
        }
        unset($k,$val);
        foreach ($cate_pid as &$v) {
            if(array_key_exists($v['id'],$newArr)){
                $v['child']=$newArr[$v['id']];
            }else{
                $v['child']=[];
            }
        }
        unset($v);
        return $cate_pid;
    }
}