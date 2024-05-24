<?php


namespace app\admin\controller\knowledge;

class StoreCoupon extends \app\admin\controller\AuthController
{
    public function index()
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""]], $this->request);
        $this->assign("where", $where);
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPage($where));
        return $this->fetch();
    }
    public function create()
    {
        $f = [];
        $f[] = \service\FormBuilder::input("title", "优惠券名称");
        $f[] = \service\FormBuilder::number("coupon_price", "优惠券面值", 0)->min(0);
        $f[] = \service\FormBuilder::number("use_min_price", "优惠券最低消费")->min(0);
        $f[] = \service\FormBuilder::number("coupon_time", "优惠券有效期限")->min(0);
        $f[] = \service\FormBuilder::number("sort", "排序");
        $form = \service\FormBuilder::make_post_form("添加优惠券", $f, \think\Url::build("save"));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function save(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["title", "coupon_price", "use_min_price", "coupon_time", "sort"], $request);
        $data["status"] = 1;
        if (!$data["title"]) {
            return \service\JsonService::fail("请输入优惠券名称");
        }
        if (!$data["coupon_price"]) {
            return \service\JsonService::fail("请输入优惠券面值");
        }
        if (!$data["coupon_time"]) {
            return \service\JsonService::fail("请输入优惠券有效期限");
        }
        $data["add_time"] = time();
        \app\admin\model\ump\StoreCoupon::set($data);
        return \service\JsonService::successful("添加优惠券成功!");
    }
    public function edit($id)
    {
        $coupon = \app\admin\model\ump\StoreCoupon::get($id);
        if (!$coupon) {
            return \service\JsonService::fail("数据不存在!");
        }
        $f = [];
        $f[] = \service\FormBuilder::input("title", "优惠券名称", $coupon->getData("title"));
        $f[] = \service\FormBuilder::number("coupon_price", "优惠券面值", $coupon->getData("coupon_price"))->min(0);
        $f[] = \service\FormBuilder::number("use_min_price", "优惠券最低消费", $coupon->getData("use_min_price"))->min(0);
        $f[] = \service\FormBuilder::number("coupon_time", "优惠券有效期限", $coupon->getData("coupon_time"))->min(0);
        $f[] = \service\FormBuilder::number("sort", "排序", $coupon->getData("sort"));
        $form = \service\FormBuilder::make_post_form("添加优惠券", $f, \think\Url::build("update", ["id" => $id]));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["title", "coupon_price", "use_min_price", "coupon_time", "sort"], $request);
        $data["status"] = 1;
        if (!$data["title"]) {
            return \service\JsonService::fail("请输入优惠券名称");
        }
        if (!$data["coupon_price"]) {
            return \service\JsonService::fail("请输入优惠券面值");
        }
        if (!$data["coupon_time"]) {
            return \service\JsonService::fail("请输入优惠券有效期限");
        }
        \app\admin\model\ump\StoreCoupon::edit($data, $id);
        return \service\JsonService::successful("修改成功!");
    }
    public function delete($id)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        $data["is_del"] = 1;
        if (!\app\admin\model\ump\StoreCoupon::edit($data, $id)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCoupon::getErrorInfo("删除失败,请稍候再试!"));
        }
        return \service\JsonService::successful("删除成功!");
    }
    public function status($id)
    {
        if (!$id) {
            return \service\JsonService::fail("数据不存在!");
        }
        if (!\app\admin\model\ump\StoreCoupon::editIsDel($id)) {
            return \service\JsonService::fail(\app\admin\model\ump\StoreCoupon::getErrorInfo("修改失败,请稍候再试!"));
        }
        return \service\JsonService::successful("修改成功!");
    }
    public function grant_subscribe()
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""], ["is_del", 0]], $this->request);
        $this->assign("where", $where);
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPageCoupon($where));
        return $this->fetch();
    }
    public function grant_all()
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""], ["is_del", 0]], $this->request);
        $this->assign("where", $where);
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPageCoupon($where));
        return $this->fetch();
    }
    public function grant($id)
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""], ["is_del", 0]], $this->request);
        $nickname = \app\admin\model\wechat\WechatUser::where("uid", "IN", $id)->column("uid,nickname");
        $this->assign("where", $where);
        $this->assign("uid", $id);
        $this->assign("nickname", implode(",", $nickname));
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPageCoupon($where));
        return $this->fetch();
    }
    public function issue($id)
    {
        if (!\app\admin\model\ump\StoreCoupon::be(["id" => $id, "status" => 1, "is_del" => 0])) {
            return $this->failed("发布的优惠劵已失效或不存在!");
        }
        $f = [];
        $f[] = \service\FormBuilder::input("id", "优惠劵ID", $id)->disabled(1);
        $f[] = \service\FormBuilder::dateTimeRange("range_date", "领取时间")->placeholder("不填为永久有效");
        $f[] = \service\FormBuilder::number("count", "发布数量", 0)->min(0)->placeholder("不填或填0,为不限量");
        $f[] = \service\FormBuilder::radio("is_permanent", "是否不限量", 0)->options([["label" => "限量", "value" => 0], ["label" => "不限量", "value" => 1]]);
        $f[] = \service\FormBuilder::radio("status", "状态", 1)->options([["label" => "开启", "value" => 1], ["label" => "关闭", "value" => 0]]);
        $form = \service\FormBuilder::make_post_form("添加优惠券", $f, \think\Url::build("update_issue", ["id" => $id]));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update_issue(\think\Request $request, $id)
    {
        list($_id, $rangeTime, $count, $status) = \service\UtilService::postMore(["id", ["range_date", ["", ""]], ["count", 0], ["status", 0]], $request, true);
        if ($_id != $id) {
            return \service\JsonService::fail("操作失败,信息不对称");
        }
        if (!$count) {
            $count = 0;
        }
        if (!\app\admin\model\ump\StoreCoupon::be(["id" => $id, "status" => 1, "is_del" => 0])) {
            return \service\JsonService::fail("发布的优惠劵已失效或不存在!");
        }
        if (count($rangeTime) != 2) {
            return \service\JsonService::fail("请选择正确的时间区间");
        }
        list($startTime, $endTime) = $rangeTime;
        if (!$startTime) {
            $startTime = 0;
        }
        if (!$endTime) {
            $endTime = 0;
        }
        if (!$startTime && $endTime) {
            return \service\JsonService::fail("请选择正确的开始时间");
        }
        if ($startTime && !$endTime) {
            return \service\JsonService::fail("请选择正确的结束时间");
        }
        if (\app\admin\model\ump\StoreCouponIssue::setIssue($id, $count, strtotime($startTime), strtotime($endTime), $count, $status)) {
            return \service\JsonService::successful("发布优惠劵成功!");
        }
        return \service\JsonService::fail("发布优惠劵失败!");
    }
    public function grant_group()
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""], ["is_del", 0]], $this->request);
        $group = \app\admin\model\wechat\WechatUser::getUserGroup();
        $this->assign("where", $where);
        $this->assign("group", json_encode($group));
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPageCoupon($where));
        return $this->fetch();
    }
    public function grant_tag()
    {
        $where = \service\UtilService::getMore([["status", ""], ["title", ""], ["is_del", 0]], $this->request);
        $tag = \app\admin\model\wechat\WechatUser::getUserTag();
        $this->assign("where", $where);
        $this->assign("tag", json_encode($tag));
        $this->assign(\app\admin\model\ump\StoreCoupon::systemPageCoupon($where));
        return $this->fetch();
    }
}

?>