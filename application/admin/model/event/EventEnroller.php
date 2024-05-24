<?php

namespace app\admin\model\event;

use app\osapi\model\BaseModel;


/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class EventEnroller extends BaseModel
{

    /**
     * 获取核销列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @return array
     */
    public static function get_check_list($map,$page,$limit,$order='check_time desc'){
        $data=self::where($map)->page($page,$limit)->order($order)->select();
        foreach ($data as &$v){
            $v['user']=db('user')->where(['uid'=>$v['uid']])->value('nickname');
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            $v['check_time']=$v['check_time']?date('Y-m-d H:i:s',$v['check_time']):'未核销';
            $v['check_user']=$v['check_uid']?db('user')->where(['uid'=>$v['check_uid']])->value('nickname'):'未核销';
        }
        $count=self::where($map)->count();
        return compact('data','count');
    }


}