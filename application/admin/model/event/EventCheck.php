<?php

namespace app\admin\model\event;

use app\admin\model\com\ComForum;
use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class EventCheck extends ModelBasic
{
    public static function addCheck($data){
        $data['status']=1;
        $data['create_time']=time();
        return self::insertGetId($data);
    }

    /**
     * 获取列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_list($map,$page,$limit,$order='create_time desc'){
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        foreach ($data as &$v){
            $v['nickname']=db('user')->where(['uid'=>$v['uid']])->value('nickname');
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
        }
        unset($v);
        $count=self::where($map)->count();
        return compact('data','count');
    }
}