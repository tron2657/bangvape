<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/13
 */

namespace app\admin\model\system;

use traits\ModelTrait;
use basic\ModelBasic;

/**
 * 设置会员vip model
 * Class SystemVip
 * @package app\admin\model\system
 */
class SystemRenwu extends ModelBasic
{
    use ModelTrait;

    public static function setAddTimeAttr()
    {
        return time();
    }
    public static function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    /*
     * 获取查询条件
     * */
    public static function setWhere($where,$alert='',$model=null)
    {
        $model=$model===null ? new self() : $model;
        if($alert) $model=$model->alias($alert);
        $alert=$alert ? $alert.'.': '';
        if(isset($where['is_show']) && $where['is_show']!=='') $model=$model->where("{$alert}is_show",$where['is_show']);
        return $model;
    }
    /*
     * 查找系统设置的会员等级列表
     * */
    public static function getSytemList($where)
    {
        $leixing = [
           1 => '日常任务',
           2 => '每周任务',
           3 => '新手任务',
           4 => '自定义任务',
        ] ;
        $data=self::where('is_del',0)->order('id asc')->page((int)$where['page'],(int)$where['limit'])->select();
        $data=count($data) ? $data->toArray() : [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                if($data[$key]['type'] == 1){
                    $data[$key]['leixing'] = $leixing[$data[$key]['leixing']] ;
                }else{
                    $data[$key]['leixing'] = '自定义任务' ;
                }

            }
        }

        $count=self::where('is_del',0)->count();
        return compact('data','count');
    }

}








