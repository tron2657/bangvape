<?php
/**
 * @author xzj
 * @date 2020-9
 */

namespace app\admin\model\store;

use traits\ModelTrait;
use basic\ModelBasic;

class StoreProductServices extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;

    /**
     * 商品服务普通列表
     * @author xzj
     * @date 2020-9
     * @param $where
     * @return array
     * @throws \think\Exception
     */
    public static function ServiceList($where) {
        $filter = ['is_del' => $where['is_del']];
        if ($where['status'] != '') {
            $filter['status'] = $where['status'];
        }
        $data = ($data = self::where($filter)->order('id desc')->page((int)$where['page'], (int)$where['limit'])->select()) && count($data) ? $data->toArray() : [];
        $count = self::where('is_del',$where['is_del'])->count();
        return compact('count', 'data');
    }

    /**
     * 商品服务checkbox列表
     * @author xzj
     * @date 2020-9
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function ServicesOptionList() {
        $data = self::where(['is_del'=>0, 'status'=>1])->order(['sort'=>'desc'])->select()->toArray();
        $options = [];
        foreach ($data as $v) {
            $options[] = ['label' => $v['name'], 'value' => $v['id']];
        }
        return $options;
    }
}