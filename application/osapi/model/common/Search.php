<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/6/13
 * Time: 14:41
 */

namespace app\osapi\model\common;


use app\osapi\model\BaseModel;
use app\osapi\model\user\UserModel;


class Search extends BaseModel
{

    /**
     * 新增搜索记录
     * @author qhy
     */
    public static function addSearch($keyword,$uid,$model,$access=''){
        $data['keyword']=$keyword;
        $data['uid']=$uid;
        $data['model']=$model;
        $data['create_time']=time();
        if($access==null){
            $access='';
        }
        $data['source']=$access;
        self::insert($data);
        return true;
    }

}
























