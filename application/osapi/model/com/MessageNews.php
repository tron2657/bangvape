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
use app\osapi\model\com\MessageRead;
use think\Cache;

class MessageNews extends BaseModel
{

    /**
     * 获取运营消息
     */
    public static function getMessageNews($page,$row){
        $uid=get_uid();
        $message_ids=MessageRead::where('uid',$uid)->where('type',6)->column('message_id');
        $message_new_all=self::where('status',1)->where('id','in',$message_ids)->where('send_time','<',time())->where('end_time','>',time())->page($page,$row)->order('send_time desc,create_time desc')->select()->toArray();
        foreach($message_new_all as &$value){
            $value['content']=text($value['content']);
            $value['logo']=get_root_path($value['logo']);
            $value['logo_150']=thumb_path($value['logo'],150,150);
            $value['logo_350']=thumb_path($value['logo'],350,350);
            $value['logo_750']=thumb_path($value['logo'],750,750);
            $value['create_time']=time_to_show($value['create_time']);
        }
        unset($value);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',6)->where('uid',$uid)->where('is_read',0)->update($data);
        return $message_new_all;
    }

    /**
     * 获取用户最新的一条营销消息
     */
    public static function getUserMessageNew($uid){
        $message=Cache::get('message_new_uid_'.$uid);
        if(!$message){
            $message_id=MessageRead::where('uid',$uid)->where('type',6)->order('create_time desc')->find();
            $message=self::where('id',$message_id['message_id'])->cache('message_new_uid_'.$uid,3600)->find();
        }
        return $message;
    }

}