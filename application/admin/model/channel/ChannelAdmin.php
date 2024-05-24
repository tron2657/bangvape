<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/6/23
 * Time: 17:16
 */

namespace app\admin\model\channel;


use app\admin\model\user\User;
use app\osapi\model\user\UserModel;
use basic\ModelBasic;
use traits\ModelTrait;

class ChannelAdmin extends ModelBasic
{
    use ModelTrait;

    /**
     * 获取频道管理员列表
     * @param $channel_id
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public static function getChannelAdminList($channel_id)
    {
        $map['status']=1;
        $map['channel_id']=$channel_id;
        $admin_uids=self::where($map)->column('uid');
        $user_info=UserModel::where(['uid'=>['in',$admin_uids],'status'=>1])->field('uid,nickname')->select();
        if(count($user_info)){
            $user_info=$user_info->toArray();
        }
        return $user_info;
    }


    /**
     * 重置管理员列表
     * @param $channel_id
     * @param $admin_users
     * @param $do_uid
     * @param int $is_add
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public static function resetChannelAdmin($channel_id,$admin_users,$do_uid,$is_add=0)
    {
        if(!$channel_id||!$do_uid){
            return false;
        }
        if(!$is_add){
            $map['channel_id']=$channel_id;
            self::where($map)->setField('status',-1);
        }
        $admin_users=explode(',',$admin_users);
        $admin_users=array_unique($admin_users);
        foreach ($admin_users as $val){
            self::_add_one_log($channel_id,$val,$do_uid);
        }
        unset($val);
        return true;
    }

    /**
     * 增加一条管理员记录
     * @param $channel_id
     * @param $uid
     * @param $do_uid
     * @return object
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    private static function _add_one_log($channel_id,$uid,$do_uid)
    {
        $data['channel_id']=$channel_id;
        $data['uid']=$uid;
        $data['do_uid']=$do_uid;
        $data['create_time']=time();
        $data['status']=1;
        $res=self::set($data);
        return $res;
    }


    public static function getListPage($map,$page=1,$r=20,$order='id desc')
    {
        $data=($data=self::where($map)->order($order)->page($page,$r)->select()) && count($data) ? $data->toArray() :[];
        foreach ($data as &$val){
            //用户信息获取
            $user_info=User::field('nickname,avatar')->find($val['uid']);
            $val['user_nickname']=$user_info['nickname'].'【'.$val['uid'].'】';
            $val['avatar']=get_root_path($user_info['avatar']);

            $val['channel_name']=(Channel::where('id',$val['channel_id'])->value('title')).'【'.$val['channel_id'].'】';


            $val['do_nickname']=User::where('uid',$val['do_uid'])->value('nickname');

            $val['create_time_show']=time_format($val['create_time']);
        }
        unset($val);
        $count=self::where($map)->count();
        return compact('count','data');
    }
}