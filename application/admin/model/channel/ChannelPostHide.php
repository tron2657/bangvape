<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/6/25
 * Time: 14:11
 */

namespace app\admin\model\channel;

use app\admin\model\user\User;
use basic\ModelBasic;
use traits\ModelTrait;

class ChannelPostHide extends ModelBasic
{
    use ModelTrait;

    public static function getPostListPage($map,$page=1,$r=20,$order='id desc')
    {
        $data=($data=self::where($map)->order($order)->page($page,$r)->select()) && count($data) ? $data->toArray() :[];
        foreach ($data as &$val){
            //帖子详情处理
            $val['post_data']=ChannelPost::dealPostData($val['post_id']);

            $val['hide_user_nickname']=User::where('uid',$val['uid'])->value('nickname');
            $val['create_time_show']=time_format($val['create_time']);
        }
        unset($val);
        $count=self::where($map)->count();

        return compact('count','data');
    }
}