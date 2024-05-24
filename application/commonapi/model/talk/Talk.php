<?php
/**
 *  聊天记录
 * Created by PhpStorm.
 * User: zxh
 * Date: 2020/4/29
 * Time: 14:26
 */


namespace app\commonapi\model\talk;

use app\osapi\model\user\UserModel;
use service\JsonService;
use think\Cache;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Url;


class Talk extends ModelBasic
{
    /**
     * 聊天记录检测创建
     * @param $uid
     * @param $to_uid
     * @return int|string
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public static function check_talk($uid,$to_uid){
        $talk=self::whereOr(['uid'=>$uid,'to_uid'=>$to_uid])->whereOr(['uid'=>$to_uid,'to_uid'=>$uid])->find();
        if($talk){
            $id=$talk['id'];
        }else{
            $data['create_time']=time();
            $data['status']=1;
            $data['uid']=$uid;
            $data['to_uid']=$to_uid;
            $id=self::insertGetId($to_uid);
        }
        return $id;
    }

    /**
     * 获取我的会话列表
     * @param $uid
     * @param $page
     * @param $limit
     * @return array
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public static function get_my_talk($uid,$page,$limit){
        $map['uid|to_uid']=$uid;
        $talk_list=self::where($map)->page($page,$limit)->order('update_time desc')->select();
        foreach ($talk_list as $v){
            $user_id=$uid==$v['uid']?$v['to_uid']:$v['uid'];
            $v['user']=UserModel::where(['uid'=>$user_id])->field('avatar,nickname,uid')->find();
        }
        unset($v);
        $count=self::where($map)->count();
        return ['list'=>$talk_list,'count'=>$count];
    }

    /**
     * 获取会话
     * @param $talk
     * @return array|false|\PDOStatement|string|\think\Model
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public static function get_talk($talk){
        return self::where(['id'=>$talk])->find();
    }
}