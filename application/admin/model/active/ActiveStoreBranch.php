<?php

namespace app\admin\model\active;

use app\admin\model\com\ComForum;
use service\PHPExcelService;
use think\Cache;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class ActiveStoreBranch extends ModelBasic
{


    public static function getEvent($id){
        $data=self::get($id);
        if(empty($data)){
            $field=self::getTableFields();
            foreach ($field as $v){
                $data[$v]='';
            }
            unset($v);
        }
        return $data;
    }
    
    /**
     * 新增内容
     * @param $data
     * @return int|string
     */
    public static function addData($data){       
        $id=self::insertGetId($data);;
        return $id;
    }

    /**
     * 编辑内容
     * @param $data
     * @return $this|int|string
     */
    public static function editData($data){
        if($data['id']){            
            return self::where(['id'=>$data['id']])->update($data);
        }else{
            return self::addData($data);
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
    public static function get_list($map,$page,$limit,$order='id desc'){
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();       
        $count=self::where($map)->count();
        return compact('data','count');
    }

}