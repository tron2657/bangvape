<?php

namespace app\admin\model\event;

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
class EventCategory extends ModelBasic
{
    /**
     * 新增内容
     * @param $data
     * @return int|string
     */
    public static function addDate($data){
        $data['status']=1;
        $data['create_time']=time();
        return self::insertGetId($data);
    }

    /**
     * 编辑内容
     * @param $data
     * @return $this|int|string
     */
    public static function editDate($data){
        if($data['id']){
            return self::where(['id'=>$data['id']])->update($data);
        }else{
            return self::addDate($data);
        }
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
        $pid=self::check_value(['pid'=>0]);
        foreach ($data as &$v){
            $v['pid_name']=$pid[$v['pid']]['label'];
        }
        unset($v);
        $count=self::where($map)->count();
        return compact('data','count');
    }

    /**
     * 后台获取顶级分类列表
     */
    public static function get_check_pid(){
        $map=['status'=>1,'pid'=>0];
        return self::check_value($map);
    }

    /**
     * 后台获取分类列表
     */
    public static function get_check_id(){
        $map['status']=1;
        return self::check_value($map);
    }

    /**
     *获取分类形成数组选择
     */
    public  static function check_value($map){
        $data=self::where($map)->select();
        $check_pid[0]=['value'=>0,'label'=>'顶级分类'];
        foreach ($data as $v){
            $check_pid[$v['id']]=['value'=>$v['id'],'label'=>$v['name']];
        }
        unset($v);
        return $check_pid;
    }

    public static function get_cate_tree(){
        $data=self::where(['status'=>1])->select()->toArray();
        $list=UtilService::sortListTier($data);
        return $list;
    }
}