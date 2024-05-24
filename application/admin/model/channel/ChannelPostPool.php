<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/4
 * Time: 15:22
 */

namespace app\admin\model\channel;


use app\admin\model\user\User;
use basic\ModelBasic;
use traits\ModelTrait;

class ChannelPostPool extends ModelBasic
{
    use ModelTrait;

    public static function getListPage($map,$page=1,$r=20,$order='id asc')
    {
        $data=($data=self::where($map)->order($order)->page($page,$r)->select()) && count($data) ? $data->toArray() :[];
        foreach ($data as &$val){
            //帖子详情处理
            $val['post_data']=ChannelPost::dealPostData($val['post_id']);

            $val['recommend_user_nickname']=User::where('uid',$val['recommend_uid'])->value('nickname');
            $val['create_time_show']=time_format($val['create_time']);
            $val['post_long_show']=Channel::dealPostLongToTitle($val['post_long']);
        }
        unset($val);
        $count=self::where($map)->count();
        return compact('count','data');
    }
}