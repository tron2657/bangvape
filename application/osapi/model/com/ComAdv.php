<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/5/30
 * Time: 14:52
 */

namespace app\osapi\model\com;


use app\osapi\model\BaseModel;
use app\osapi\model\com\ComForumMember;

class ComAdv extends BaseModel
{

    /**
     * 获取广告
     */
    public static function getAdv($type){
        $map=[
            'type'=>$type,
            'status'=>1,
        ];
        $list=self::where($map)->order('sort asc')->select()->toArray();
        if(in_array($type,array('1','3','6','10','12','13'))){
            foreach($list as &$value){
                // $value['pic']=thumb_path($value['pic'],700,350);
                $value['link_url']=link_select_url($value['url']);
            }
            unset($value);
        }else{
            foreach($list as &$value){
                // $value['pic']=thumb_path($value['pic'],700,200);
                $value['link_url']=link_select_url($value['url']);
                
            }
            unset($value);
        }
        if (!empty($list)) {
            foreach ($list as &$value) {
                $value['platform'] = db('com_adv_platform')->where('adv_id', $value['id'])->column('platform');
            }
            unset($value);
        }
        return $list;
    }

}