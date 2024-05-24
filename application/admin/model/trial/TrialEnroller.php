<?php

namespace app\admin\model\trial;

use app\osapi\model\BaseModel;


/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class TrialEnroller extends BaseModel
{

    /**
     * 获取活动列表
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
            $v['check_time']=$v['check_time']?date('Y-m-d H:i:s',$v['check_time']):'';
            $v['draw_time']=$v['draw_time']?date('Y-m-d H:i:s',$v['draw_time']):'';
            $v['finish_time']=$v['finish_time']?date('Y-m-d H:i:s',$v['finish_time']):'';
            $v['check_user']=$v['check_uid']?db('system_admin')->where(['id'=>$v['check_uid']])->value('account'):'';
            $v['order_oid']=$v['order_id']?db('store_order')->where(['order_id'=>$v['order_id']])->value('id'):'';
        }
        $count=self::where($map)->count();
        return compact('data','count');
    }
 

 
    
}