<?php
/**
 *
 * @author: cyx<cyx@ourstu.com>
 * @day: 2019/4/12
 */

namespace app\osapi\model\com;

use app\osapi\model\BaseModel;
use app\osapi\model\user\UserModel;
/**
 * 版块 model
 * Class ComForum
 * @package app\admin\model\com
 */
class MessageRead extends BaseModel
{
    /**
     * 创建阅读消息
     */
    public static function createMessageRead($uid,$message_id,$is_popup,$type){
        $data['uid']=$uid;
        $data['message_id']=$message_id;
        $data['type']=$type;
        $data['create_time']=time();
        if($is_popup==1){
            $data['is_popup']=0;
        }else{
            $data['is_popup']=1;
            $data['popup_time']=time();
        }
        $res=self::add($data);
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 获取用户消息是否已读
     */
    public static function getUserRead($uid,$message_id){
        $res=self::where('uid',$uid)->where('message_id',$message_id)->where('type','neq',6)->where('is_read',1)->find();
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 获取用户消息是否已弹窗
     */
    public static function getUserPopup($uid,$message_id){
        $res=self::where('uid',$uid)->where('message_id',$message_id)->where('is_popup',1)->find();
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 获取用户未读信息条数
     */
    public static function getMessageCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>1
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取用户未读评论条数
     */
    public static function getReplyCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>2
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取用户未读被赞条数
     */
    public static function getSupportCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>3
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取用户未读被关注数
     */
    public static function getFollowCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>4
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取用户未读新动态条数
     */
    public static function getNewSendCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>5
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取用户未读自定义消息条数
     */
    public static function getNoticeCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>7
        ];
        $count=self::where($map)->count();
        return $count;
    }


    /**
     * 获取用户未读自定义消息条数
     */
    public static function getMessageNewCount($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>6
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 获取所有消息类型数量
     * @param $uid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_all_read_count($uid){
        $map=[
            'uid'=>$uid,
            'is_read'=>0,
            'type'=>['in',1,2,3,4,5,6,7]
        ];
        $count=self::where($map)->field('type,count(*)')->group('type')->select()->toArray();
        return $count;
    }
}