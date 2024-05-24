<?php
/**
 * 知识商城优惠券
 * 2020-10
 */
namespace app\admin\model\ump;

use traits\ModelTrait;
use basic\ModelBasic;

class ColumnCoupon extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';

    public static function systemPage($where){
        $model = new self;
        if($where['status'] != '')  $model = $model->where('status',$where['status']);
        if($where['title'] != '')  $model = $model->where('title','LIKE',"%$where[title]%");
        $model = $model->where('is_del',0);
        if (isset($where['status']) && $where['status'] != '') $model = $model->where('status', $where['status']);
        $model = $model->order('sort desc,id desc');
        return self::page($model,$where);
    }

    public static function editIsDel($id){
        $data['status'] = 0;
        self::beginTrans();
        $res1 = self::edit($data,$id);
        $res2 = false !== ColumnCouponUser::where('cid',$id)->setField('is_fail',1);
        $res3 = false !== ColumnCouponIssue::where('cid',$id)->setField('status',-1);
        $res  = $res1 && $res2 && $res3;
        self::checkTrans($res);
        return $res;
    }
}