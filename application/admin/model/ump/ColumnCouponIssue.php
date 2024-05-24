<?php
/**
 * 知识商城优惠券前台领取表
 * 2020-10
 */

namespace app\admin\model\ump;


use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponIssue extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';

    public static function stsypage($where){
        $model = self::alias('A')->field('A.*,B.title')->join('__COLUMN_COUPON__ B','A.cid = B.id')->where('A.is_del',0)->order('A.add_time DESC');
        if(isset($where['status']) && $where['status']!=''){
            $model=$model->where('A.status',$where['status']);
        }
        if(isset($where['coupon_title']) && $where['coupon_title']!=''){
            $model=$model->where('B.title','LIKE',"%$where[coupon_title]%");
        }
        return self::page($model);
    }

    public static function setIssue($cid,$total_count = 0,$start_time = 0,$end_time = 0,$remain_count = 0,$status = 0)
    {
        return self::set(compact('cid','start_time','end_time','total_count','remain_count','status'));
    }
}