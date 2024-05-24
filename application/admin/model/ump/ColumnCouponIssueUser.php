<?php
/**
 * 知识商城优惠券前台用户领取记录表
 * 2020-10
 */

namespace app\admin\model\ump;


use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponIssueUser extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';

    public static function systemCouponIssuePage($issue_coupon_id)
    {
        $model = self::alias('A')->field('B.nickname,B.avatar,A.add_time')
            ->join('__USER__ B','A.uid = B.uid')
            ->where('A.issue_coupon_id',$issue_coupon_id);
        return self::page($model,function($item){
            $item['add_time'] = $item['add_time'] == 0 ? '未知' : date('Y/m/d H:i',$item['add_time']);
            return $item;
        });
    }
}