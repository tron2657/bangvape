<?php


namespace app\admin\controller\knowledge;

class StoreCouponUser extends \app\admin\controller\AuthController
{
    public function index()
    {
        $where = \service\UtilService::getMore([["status", ""], ["is_fail", ""], ["coupon_title", ""], ["nickname", ""]], $this->request);
        $this->assign("where", $where);
        $this->assign(\app\admin\model\ump\StoreCouponUser::systemPage($where));
        return $this->fetch();
    }
    public function grant_subscribe($id)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = \app\admin\model\wechat\WechatUser::getSubscribe("uid");
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }
    public function grant_all($id)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = \app\admin\model\wechat\WechatUser::getUserAll("uid");
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }
    public function grant($id, $uid)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = explode(",", $uid);
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }

    //赠送VIP会员，每个X个月XXX样的优惠券
    public function grant_vip($id, $uid)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = explode(",", $uid);
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }


    public function grant_group($id, \think\Request $request)
    {
        $data = \service\UtilService::postMore([["group", 0]], $request);
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = \app\admin\model\wechat\WechatUser::where("groupid", $data["group"])->column("uid", "uid");
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }
    public function grant_tag($id, \think\Request $request)
    {
        $data = \service\UtilService::postMore([["tag", 0]], $request);
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $coupon = \app\admin\model\ump\StoreCoupon::get($id)->toArray();
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $user = \app\admin\model\wechat\WechatUser::where("tagid_list", "LIKE", "%" . $data["tag"] . "%")->column("uid", "uid");
        if (!\app\admin\model\ump\StoreCouponUser::setCoupon($coupon, $user)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCouponUser::getErrorInfo("发放失败,请稍候再试!"));
        }
        return \service\JsonService::successful("发放成功!");
    }
}

?>