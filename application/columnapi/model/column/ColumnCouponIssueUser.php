<?php
/**
 * 知识商城优惠券前台用户领取记录表
 * 2020-10
 */

namespace app\columnapi\model\column;


use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponIssueUser extends ModelBasic
{
    use ModelTrait;

    public static function addUserIssue($uid,$issue_coupon_id)
    {
        $add_time = time();
        return self::set(compact('uid','issue_coupon_id','add_time'));
    }
}