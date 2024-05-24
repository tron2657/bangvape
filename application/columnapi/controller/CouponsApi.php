<?php


namespace app\columnapi\controller;

class CouponsApi extends AuthController
{
    public function get_use_coupons()
    {
        $types = osx_input("types", "", "text");
        switch ($types) {
            case 0:
                break;
            case "":
                $list = \app\columnapi\model\column\ColumnCouponUser::getUserAllCoupon($this->userInfo["uid"]);
                break;
            case 1:
                $list = \app\columnapi\model\column\ColumnCouponUser::getUserValidCoupon($this->userInfo["uid"]);
                break;
            case 2:
                $list = \app\columnapi\model\column\ColumnCouponUser::getUserAlreadyUsedCoupon($this->userInfo["uid"]);
                break;
            default:
                $list = \app\columnapi\model\column\ColumnCouponUser::getUserBeOverdueCoupon($this->userInfo["uid"]);
                $v["add_time"] = date("Y/m/d", $v["add_time"]);
                $v["end_time"] = date("Y/m/d", $v["end_time"]);
                return \service\JsonService::successful($list);
        }
    }
    public function get_use_coupon()
    {
        return \service\JsonService::successful("", \app\columnapi\model\column\ColumnCouponUser::getUserAllCoupon($this->userInfo["uid"]));
    }
    public function get_use_coupon_order()
    {
        $totalPrice = osx_input("totalPrice", 0, "intval");
        return \service\JsonService::successful(\app\columnapi\model\column\ColumnCouponUser::beUsableCouponList($this->userInfo["uid"], $totalPrice));
    }
    public function get_user_coupon_order()
    {
        $totalPrice = osx_input("totalPrice", 0, "intval");
        return \service\JsonService::successful(\app\columnapi\model\column\ColumnCouponUser::beUsableCouponList($this->userInfo["uid"], $totalPrice));
    }
    public function user_get_coupon()
    {
        $couponId = osx_input("couponId", 0, "intval");
        if (!$couponId || !is_numeric($couponId)) {
            return \service\JsonService::fail("参数错误!");
        }
        if (\app\columnapi\model\column\ColumnCouponIssue::issueUserCoupon($couponId, $this->userInfo["uid"])) {
            return \service\JsonService::successful("领取成功");
        }
        return \service\JsonService::fail(\app\columnapi\model\column\ColumnCouponIssue::getErrorInfo("领取失败!"));
    }
    public function get_coupon_rope()
    {
        $couponId = osx_input("couponId", 0, "intval");
        if (!$couponId) {
            return \service\JsonService::fail("参数错误");
        }
        $couponUser = \app\columnapi\model\column\ColumnCouponUser::validAddressWhere()->where("id", $couponId)->where("uid", $this->userInfo["uid"])->find();
        return \service\JsonService::successful($couponUser);
    }
    public function get_issue_coupon_list()
    {
        $limit = osx_input("limit", 2, "intval");
        return \service\JsonService::successful(\app\columnapi\model\column\ColumnCouponIssue::getIssueCouponList($this->uid, $limit));
    }
}

?>