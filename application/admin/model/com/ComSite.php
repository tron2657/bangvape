<?php
/**
 *
 * @author: cyx<cyx@ourstu.com>
 * @day: 2019/4/12
 */

namespace app\admin\model\com;

use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\user\User as UserModel;
/**
 * 版块 model
 * Class ComForum
 * @package app\admin\model\com
 */
class ComSite extends ModelBasic
{
    use ModelTrait;

    public static function NavList($where){
    	$map                 = [];
    	$map['type']         = $where['type'];
    	if($where['status'] != ''){
    		$map['status'] = $where['status'];
    	}
    	if($where['name']){
    		$map['name'] = ['like', "%{$where['name']}%"];
    	}
    	$model = self::where($map)->field(['*']);
    	$model->page((int)$where['page'], (int)$where['limit']);
    	$data = ($data = $model->select()) && count($data) ? $data->toArray() : [];
    	$count = self::where($map)->count();
        return compact('count', 'data');
    }

    /**
     * 列表加载 统计数量
     * @param $list
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.26
     */
    public static function views($list){
        $ids=array_column($list,'id');
        $is_census=self::where(['id'=>1])->value('read_census');
        if($is_census==1){
            db('com_thread')->where(['id'=>['in',$ids]])->setInc('view_count',1);
        }
    }

    /**
     * 点赞 评论之类操作 增加阅读量
     * @param $id
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.26
     */
    public static function views_one($id){
        $is_census=self::where(['id'=>1])->value('read_census');
        if($is_census==1){
            db('com_thread')->where(['id'=>$id])->setInc('view_count',1);
        }
    }
}