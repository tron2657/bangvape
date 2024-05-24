<?php


namespace app\columnapi\controller;

class UserApi extends AuthController
{
    public static function whiteList()
    {
        return ["userCard"];
    }
    public function get_sign_month_list()
    {
        $page = osx_input("page", 1, "intval");
        $limit = osx_input("limit", 10, "intval");
        return \service\JsonService::successful(\app\core\model\UserSign::getSignMonthList($this->uid, $page, $limit));
    }
    public function get_sign_list()
    {
        $page = osx_input("page", 1, "intval");
        $limit = osx_input("limit", 10, "intval");
        return \service\JsonService::successful(\app\core\model\UserSign::getSignList($this->uid, $page, $limit));
    }
    public function get_my_user_info()
    {
        list($isSgin, $isIntegral, $isall) = \service\UtilService::getMore([["isSgin", 0], ["isIntegral", 0], ["isall", 0]], $this->request, true);
        if ($isSgin || $isall) {
            $this->userInfo["sum_sgin_day"] = \app\core\model\UserSign::getSignSumDay($this->uid);
            $this->userInfo["is_day_sgin"] = \app\core\model\UserSign::getToDayIsSign($this->uid);
            $this->userInfo["is_YesterDay_sgin"] = \app\core\model\UserSign::getYesterDayIsSign($this->uid);
            if (!$this->userInfo["is_day_sgin"] && !$this->userInfo["is_YesterDay_sgin"]) {
                $this->userInfo["sign_num"] = 0;
            }
        }
        if ($isIntegral || $isall) {
            $this->userInfo["sum_integral"] = (array) \app\core\model\UserBill::getRecordCount($this->uid, "integral", "sign,system_add,gain");
            $this->userInfo["deduction_integral"] = (array) \app\core\model\UserBill::getRecordCount($this->uid, "integral", "deduction") ?: 0;
            $this->userInfo["today_integral"] = (array) \app\core\model\UserBill::getRecordCount($this->uid, "integral", "sign,system_add,gain", "today");
        }
        unset($this->userInfo["pwd"]);
        $this->userInfo["integral"] = (array) $this->userInfo["integral"];
        return \service\JsonService::successful($this->userInfo);
    }
    public function get_user_info_uid()
    {
        $userId = osx_input("userId", 0, "intval");
        if (!$userId) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\columnapi\model\user\User::getUserInfo($userId);
        if ($res) {
            return \service\JsonService::successful($res);
        }
        return \service\JsonService::fail(\app\columnapi\model\user\User::getErrorInfo());
    }
    public function my()
    {
        $this->userInfo["couponCount"] = \app\columnapi\model\store\StoreCouponUser::getUserValidCouponCount($this->userInfo["uid"]);
        $this->userInfo["like"] = \app\columnapi\model\store\StoreProductRelation::getUserIdCollect($this->userInfo["uid"]);
        $this->userInfo["orderStatusNum"] = \app\columnapi\model\store\StoreOrder::getOrderStatusNum($this->userInfo["uid"]);
        $this->userInfo["notice"] = UserNotice::getNotice($this->userInfo["uid"]);
        $this->userInfo["brokerage"] = \app\core\model\UserBill::getBrokerage($this->uid);
        $this->userInfo["recharge"] = \app\core\model\UserBill::getRecharge($this->uid);
        $this->userInfo["orderStatusSum"] = \app\columnapi\model\store\StoreOrder::getOrderStatusSum($this->uid);
        $this->userInfo["extractTotalPrice"] = UserExtract::userExtractTotalPrice($this->uid);
        $this->userInfo["extractPrice"] = 0 < (object) bcsub($this->userInfo["brokerage"], $this->userInfo["extractTotalPrice"], 2) ?: 0;
        $this->userInfo["statu"] = (array) \app\core\util\SystemConfigService::get("store_brokerage_statu");
        $vipId = \app\core\model\UserLevel::getUserLevel($this->uid);
        $this->userInfo["vip"] = $vipId !== false ? true : false;
        if ($this->userInfo["vip"]) {
            $this->userInfo["vip_id"] = $vipId;
            $this->userInfo["vip_icon"] = \app\core\model\UserLevel::getUserLevelInfo($vipId, "icon");
            $this->userInfo["vip_name"] = \app\core\model\UserLevel::getUserLevelInfo($vipId, "name");
        }
        unset($this->userInfo["pwd"]);
        return \service\JsonService::successful($this->userInfo);
    }
    public function user_sign()
    {
        $signed = \app\core\model\UserSign::getToDayIsSign($this->userInfo["uid"]);
        if ($signed) {
            return \service\JsonService::fail("已签到");
        }
        if (false !== ($integral = \app\core\model\UserSign::sign($this->uid))) {
            return \service\JsonService::successful("签到获得" . floatval($integral) . "积分", ["integral" => $integral]);
        }
        return \service\JsonService::fail(\app\core\model\UserSign::getErrorInfo("签到失败"));
    }
    public function get_user_address()
    {
        $addressId = osx_input("addressId", "", "text");
        $addressInfo = [];
        if ($addressId && is_numeric($addressId) && UserAddress::be(["is_del" => 0, "id" => $addressId, "uid" => $this->userInfo["uid"]])) {
            $addressInfo = UserAddress::find($addressId);
        }
        return \service\JsonService::successful($addressInfo);
    }
    public function user_default_address()
    {
        $defaultAddress = UserAddress::getUserDefaultAddress($this->userInfo["uid"], "id,real_name,phone,province,city,district,detail,is_default");
        if ($defaultAddress) {
            return \service\JsonService::successful("ok", $defaultAddress);
        }
        return \service\JsonService::successful("empty", []);
    }
    public function remove_user_address()
    {
        $addressId = osx_input("addressId", "", "text");
        if (!$addressId || !is_numeric($addressId)) {
            return \service\JsonService::fail("参数错误!");
        }
        if (!UserAddress::be(["is_del" => 0, "id" => $addressId, "uid" => $this->userInfo["uid"]])) {
            return \service\JsonService::fail("地址不存在!");
        }
        if (UserAddress::edit(["is_del" => "1"], $addressId, "id")) {
            return \service\JsonService::successful();
        }
        return \service\JsonService::fail("删除地址失败!");
    }
    public function get_user_order_list()
    {
        list($type, $page, $limit, $search) = \service\UtilService::getMore([["type", ""], ["page", ""], ["limit", ""], ["search", ""]], $this->request, true);
        return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::getUserOrderSearchList($this->uid, $type, $page, $limit, $search));
    }
    public function get_user_order_list_zg()
    {
        list($type, $page, $limit, $search) = \service\UtilService::getMore([["type", ""], ["page", ""], ["limit", ""], ["search", ""]], $this->request, true);
        return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::getUserOrderSearchListZg($this->uid, $type, $page, $limit, $search));
    }
    public function get_order()
    {
        $uni = osx_input("uni", "", "text");
        if ($uni == "") {
            return \service\JsonService::fail("参数错误");
        }
        $order = \app\columnapi\model\store\StoreOrder::getUserOrderDetail($this->userInfo["uid"], $uni);
        if ($order != NULL) {
            $order = $order->toArray();
        }
        $out_time = \app\admin\model\system\SystemConfig::getValue("close_order_time");
        $receiving_time = \app\admin\model\system\SystemConfig::getValue("receiving_goods_time");
        $time = time() - $out_time * 3600;
        if ($order["paid"] == 0 && $order["add_time"] < $time) {
            \app\columnapi\model\store\StoreOrder::cancelOrder($order["order_id"]);
            \service\JsonService::fail("订单已超时，已自动取消订单");
        }
        $score_cash = abs(\app\admin\model\system\SystemConfig::getValue("score_cash"));
        $order["score_num_pay"] = $order["score_num"] * $score_cash;
        $order["add_time_y"] = date("Y-m-d", $order["add_time"]);
        $order["add_time_h"] = date("H:i:s", $order["add_time"]);
        $people = db("store_pink")->where("id", $order["pink_id"])->value("people");
        $people_all = db("store_pink")->where("k_id", $order["pink_id"])->count();
        $people_all = $people_all + 1;
        $all = $people - $people_all;
        if ($all == 0) {
            $order["is_pink_success"] = 1;
        } else {
            $order["is_pink_success"] = 0;
        }
        if ($order["seckill_id"]) {
            $seckill = StoreSeckill::getValidProduct($order["seckill_id"]);
            $order["seckill_id"] = $seckill["id"];
            $order["stop_time"] = $seckill["stop_time"];
            $order["product_id"] = $seckill["product_id"];
        }
        if ($order["status"] == 0) {
            $order["out_time"] = $out_time * 3600 + $order["add_time"] - time();
            $order["out_time"] = 0 < $order["out_time"] ? $order["out_time"] : 0;
        }
        if ($order["status"] == 2) {
            $order["receiving_time"] = $receiving_time * 24 * 3600 + $order["delivery_time"] - time();
            $order["receiving_time"] = 0 < $order["receiving_time"] ? $order["receiving_time"] : 0;
        }
        if (!$order) {
            return \service\JsonService::fail("订单不存在");
        }
        return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::tidyOrder($order, true, true));
    }
    public function user_remove_order()
    {
        $uni = osx_input("uni", "", "text");
        if (!$uni) {
            return \service\JsonService::fail("参数错误!");
        }
        $res = \app\columnapi\model\store\StoreOrder::removeOrder($uni, $this->userInfo["uid"]);
        if ($res) {
            return \service\JsonService::successful();
        }
        return \service\JsonService::fail(\app\columnapi\model\store\StoreOrder::getErrorInfo());
    }
    public function bind_mobile(\think\Request $request)
    {
        list($iv, $cache_key, $encryptedData) = \service\UtilService::postMore([["iv", ""], ["cache_key", ""], ["encryptedData", ""]], $request, true);
        $iv = urldecode(urlencode($iv));
        try {
            if (!\think\Cache::has("eb_api_code_" . $cache_key)) {
                return \service\JsonService::fail("获取手机号失败");
            }
            $session_key = \think\Cache::get("eb_api_code_" . $cache_key);
            $userInfo = \service\MiniProgramService::encryptor($session_key, $iv, $encryptedData);
            if (!empty($userInfo["purePhoneNumber"])) {
                if (\app\columnapi\model\user\User::edit(["phone" => $userInfo["purePhoneNumber"]], $this->userInfo["uid"])) {
                    return \service\JsonService::successful("绑定成功", ["phone" => $userInfo["purePhoneNumber"]]);
                }
                return \service\JsonService::fail("绑定失败");
            }
            return \service\JsonService::fail("获取手机号失败");
        } catch (\Exception $e) {
            return \service\JsonService::fail("error", $e->getMessage());
        }
    }
    public function user_take_order()
    {
        $uni = osx_input("uni", "", "text");
        if (!$uni) {
            return \service\JsonService::fail("参数错误!");
        }
        $res = \app\columnapi\model\store\StoreOrder::takeOrder($uni, $this->userInfo["uid"]);
        if ($res) {
            return \service\JsonService::successful();
        }
        return \service\JsonService::fail(\app\columnapi\model\store\StoreOrder::getErrorInfo());
    }
    public function user_wechat_recharge()
    {
        $price = osx_input("price", 0, "intval");
        if (!$price || $price <= 0) {
            return \service\JsonService::fail("参数错误");
        }
        $storeMinRecharge = \app\core\util\SystemConfigService::get("store_user_min_recharge");
        if ($price < $storeMinRecharge) {
            return \service\JsonService::fail("充值金额不能低于" . $storeMinRecharge);
        }
        $rechargeOrder = UserRecharge::addRecharge($this->userInfo["uid"], $price);
        if (!$rechargeOrder->result) {
            return \service\JsonService::fail("充值订单生成失败!");
        }
        try {
            return \service\JsonService::successful(UserRecharge::jsPay($rechargeOrder));
        } catch (\Exception $e) {
            return \service\JsonService::fail($e->getMessage());
        }
    }
    public function user_balance_list()
    {
        $first = osx_input("first", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        return \service\JsonService::successful(\app\core\model\UserBill::userBillList($this->uid, $first, $limit, "now_money"));
    }
    public function user_integral_list()
    {
        $page = osx_input("page", 1, "intval");
        $limit = osx_input("limit", 8, "intval");
        return \service\JsonService::successful(\app\core\model\UserBill::userBillList($this->uid, $page, $limit));
    }
    public function get_spread_list()
    {
        $first = osx_input("first", 0, "intval");
        $limit = osx_input("limit", 20, "intval");
        return \service\JsonService::successful(\app\columnapi\model\user\User::getSpreadList($this->uid, $first, $limit));
    }
    public function get_spread_list_two()
    {
        $two_uid = osx_input("two_uid", 0, "intval");
        $limit = osx_input("limit", 20, "intval");
        $first = osx_input("first", 0, "intval");
        return \service\JsonService::successful(\app\columnapi\model\user\User::getSpreadList($two_uid, $first, $limit));
    }
    public function user_address_list()
    {
        $page = osx_input("page", 1, "intval");
        $limit = osx_input("limit", 8, "intval");
        $list = UserAddress::getUserValidAddressList($this->userInfo["uid"], $page, $limit, "id,real_name,phone,province,city,district,detail,is_default");
        return \service\JsonService::successful($list);
    }
    public function see_notice()
    {
        $nid = osx_input("nid", 1, "intval");
        UserNotice::seeNotice($this->userInfo["uid"], $nid);
        return \service\JsonService::successful();
    }
    public function user_extract()
    {
        list($lists) = \service\UtilService::postMore([["lists", []]], $this->request, true);
        if (UserExtract::userExtract($this->userInfo, $lists)) {
            return \service\JsonService::successful("申请提现成功!");
        }
        return \service\JsonService::fail(UserExtract::getErrorInfo("提现失败"));
    }
    public function subordinateOrderlist()
    {
        $first = osx_input("post.first", 0, "intval");
        $limit = osx_input("post.limit", 8, "intval");
        $xUid = osx_input("post.xUid", 0, "intval");
        $status = osx_input("post.status", 0, "intval");
        switch ($status) {
            case 0:
                $type = "";
                break;
            case 1:
                $type = 4;
                break;
            case 2:
                $type = 3;
                return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::getSubordinateOrderlist($xUid, $this->uid, $type, $first, $limit));
                break;
            default:
                return \service\JsonService::fail();
        }
    }
    public function subordinateOrderlistmoney()
    {
        $status = osx_input("status", 0, "intval");
        $type = "";
        if ($status == 1) {
            $type = 4;
        } else {
            if ($status == 2) {
                $type = 3;
            }
        }
        $arr = \app\columnapi\model\user\User::where("spread_uid", $this->userInfo["uid"])->column("uid");
        $list = \app\columnapi\model\store\StoreOrder::getUserOrderCount(implode(",", $arr), $type);
        $price = [];
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $price[] = $v;
            }
        }
        $cont = count($list);
        $sum = array_sum($price);
        return \service\JsonService::successful(["cont" => $cont, "sum" => $sum]);
    }
    public function extract()
    {
        $first = osx_input("post.first", 0, "intval");
        $limit = osx_input("post.limit", 8, "intval");
        return \service\JsonService::successful(UserExtract::extractList($this->uid, $first, $limit));
    }
    public function user_comment_product(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["unique"], $request);
        if (!$data["unique"]) {
            return \service\JsonService::fail("参数错误!");
        }
        $cartInfo = \app\columnapi\model\store\StoreOrderCartInfo::where("unique", $data["unique"])->find();
        $uid = $this->userInfo["uid"];
        if (!$cartInfo || $uid != $cartInfo["cart_info"]["uid"]) {
            return \service\JsonService::fail("评价产品不存在!");
        }
        if (\app\columnapi\model\column\ColumnProductReply::be(["oid" => $cartInfo["oid"], "unique" => $data["unique"]])) {
            return \service\JsonService::fail("该产品已评价!");
        }
        $group = \service\UtilService::postMore([["comment", ""], ["pics", ""], ["product_score", 5], ["service_score", 5]], \think\Request::instance());
        $group["comment"] = htmlspecialchars(trim($group["comment"]));
        $group["comment"] = \app\commonapi\controller\Sensitive::sensitive($group["comment"], "商城评论");
        if ($group["product_score"] < 1) {
            return \service\JsonService::fail("请为产品评分");
        }
        if ($group["service_score"] < 1) {
            return \service\JsonService::fail("请为商家服务评分");
        }
        if ($cartInfo["cart_info"]["combination_id"]) {
            $productId = $cartInfo["cart_info"]["product_id"];
        } else {
            if ($cartInfo["cart_info"]["seckill_id"]) {
                $productId = $cartInfo["cart_info"]["product_id"];
            } else {
                if ($cartInfo["cart_info"]["bargain_id"]) {
                    $productId = $cartInfo["cart_info"]["product_id"];
                } else {
                    $productId = $cartInfo["product_id"];
                }
            }
        }
        $group = array_merge($group, ["uid" => $uid, "oid" => $cartInfo["oid"], "unique" => $data["unique"], "product_id" => $productId, "reply_type" => "product"]);
        \app\columnapi\model\column\ColumnProductReply::beginTrans();
        $res = \app\columnapi\model\column\ColumnProductReply::reply($group, "product");
        \app\commonapi\model\Gong::actionadd("fazhishishangpinpingjia", "column_product_reply", "uid");
        if (!$res) {
            \app\columnapi\model\column\ColumnProductReply::rollbackTrans();
            return \service\JsonService::fail("评价失败!");
        }
        try {
            \app\columnapi\model\store\StoreOrder::checkOrderOver($cartInfo["oid"]);
            \app\columnapi\model\column\ColumnProductReply::commitTrans();
            return \service\JsonService::successful();
        } catch (\Exception $e) {
            \app\columnapi\model\column\ColumnProductReply::rollbackTrans();
            return \service\JsonService::fail($e->getMessage());
        }
    }
    public function express()
    {
        $uni = osx_input("uni", "");
        if (!$uni || !($order = \app\columnapi\model\store\StoreOrder::getUserOrderDetail($this->uid, $uni))) {
            return \service\JsonService::fail("查询订单不存在!");
        }
        if ($order["delivery_type"] != "express" || !$order["delivery_id"]) {
            return \service\JsonService::fail("该订单不存在快递单号!");
        }
        $cacheName = $uni . $order["delivery_id"];
        \service\CacheService::rm($cacheName);
        $result = \service\CacheService::get($cacheName, NULL);
        if ($result === NULL) {
            $result = \Api\Express::query($order["delivery_id"]);
            if (is_array($result) && isset($result["result"]) && isset($result["result"]["deliverystatus"]) && 3 <= $result["result"]["deliverystatus"]) {
                $cacheTime = 0;
            } else {
                $cacheTime = 1800;
            }
            \service\CacheService::set($cacheName, $result, $cacheTime);
        }
        return \service\JsonService::successful(["order" => \app\columnapi\model\store\StoreOrder::tidyOrder($order, true), "express" => $result ? $result : []]);
    }
    public function edit_user_address()
    {
        $request = \think\Request::instance();
        if (!$request->isPost()) {
            return \service\JsonService::fail("参数错误!");
        }
        $addressInfo = \service\UtilService::postMore([["city", ""], ["district", ""], ["province", ""], ["is_default", false], ["real_name", ""], ["post_code", ""], ["phone", ""], ["detail", ""], ["id", 0]], $request);
        $addressInfo["is_default"] = $addressInfo["is_default"] ? 1 : 0;
        $addressInfo["uid"] = $this->userInfo["uid"];
        if ($addressInfo["id"] && UserAddress::be(["id" => $addressInfo["id"], "uid" => $this->userInfo["uid"], "is_del" => 0])) {
            $id = $addressInfo["id"];
            unset($addressInfo["id"]);
            if (UserAddress::edit($addressInfo, $id, "id")) {
                if ($addressInfo["is_default"]) {
                    UserAddress::setDefaultAddress($id, $this->userInfo["uid"]);
                }
                return \service\JsonService::successful();
            }
            return \service\JsonService::fail("编辑收货地址失败!");
        }
        if ($address = UserAddress::set($addressInfo, true)) {
            if ($addressInfo["is_default"]) {
                UserAddress::setDefaultAddress($address->id, $this->userInfo["uid"]);
            }
            return \service\JsonService::successful(["id" => $address->id]);
        }
        return \service\JsonService::fail("添加收货地址失败!");
    }
    public function get_notice_list()
    {
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $list = UserNotice::getNoticeList($this->userInfo["uid"], $page, $limit);
        return \service\JsonService::successful($list);
    }
    public function yesterday_commission()
    {
        return \service\JsonService::successful(\app\core\model\UserBill::yesterdayCommissionSum($this->uid));
    }
    public function extractsum()
    {
        return \service\JsonService::successful(UserExtract::extractSum($this->uid));
    }
    public function spread_uid()
    {
        $spread_uid = osx_input("post.spread_uid", 0, "intval");
        if ($spread_uid) {
            if (!$this->userInfo["spread_uid"]) {
                $res = \app\columnapi\model\user\User::edit(["spread_uid" => $spread_uid], $this->userInfo["uid"]);
                if ($res) {
                    return \service\JsonService::successful("绑定成功");
                }
                return \service\JsonService::successful("绑定失败");
            }
            return \service\JsonService::fail("已存在被推荐人");
        }
        return \service\JsonService::fail("没有推荐人");
    }
    public function set_user_default_address()
    {
        $addressId = osx_input("addressId", 0, "intval");
        if (!$addressId || !is_numeric($addressId)) {
            return \service\JsonService::fail("参数错误!");
        }
        if (!UserAddress::be(["is_del" => 0, "id" => $addressId, "uid" => $this->userInfo["uid"]])) {
            return \service\JsonService::fail("地址不存在!");
        }
        $res = UserAddress::setDefaultAddress($addressId, $this->userInfo["uid"]);
        if (!$res) {
            return \service\JsonService::fail("地址不存在!");
        }
        return \service\JsonService::successful();
    }
    public function get_code()
    {
        header("content-type:image/jpg");
        if (!$this->userInfo["uid"]) {
            return \service\JsonService::fail("授权失败，请重新授权");
        }
        $path = makePathToUrl("routine/code");
        if ($path == "") {
            return \service\JsonService::fail("生成上传目录失败,请检查权限!");
        }
        $picname = $path . "/" . $this->userInfo["uid"] . ".jpg";
        $domain = \app\core\util\SystemConfigService::get("site_url") . "/";
        $domainTop = substr($domain, 0, 5);
        if ($domainTop != "https") {
            $domain = "https:" . substr($domain, 5, strlen($domain));
        }
        if (file_exists($picname)) {
            return \service\JsonService::successful($domain . $picname);
        }
        $res = \app\core\model\routine\RoutineCode::getCode($this->userInfo["uid"], $picname);
        if ($res) {
            file_put_contents($picname, $res);
            return \service\JsonService::successful($domain . $picname);
        }
        return \service\JsonService::fail("二维码生成失败");
    }
    public function edit_user()
    {
        $formid = osx_input("formid", 0, "intval");
        list($avatar, $nickname) = \service\UtilService::postMore([["avatar", ""], ["nickname", ""]], $this->request, true);
        \app\core\model\routine\RoutineFormId::SetFormId($formid, $this->uid);
        if (\app\columnapi\model\user\User::editUser($avatar, $nickname, $this->uid)) {
            return \service\JsonService::successful("修改成功");
        }
        return \service\JsonService::fail("");
    }
    public function get_user_bill_list()
    {
        $page = osx_input("page", 1, "intval");
        $limit = osx_input("limit", 8, "intval");
        $type = osx_input("type", 0, "intval");
        return \service\JsonService::successful(\app\core\model\UserBill::getUserBillList($this->uid, $page, $limit, $type));
    }
    public function get_activity()
    {
        $data["is_bargin"] = StoreBargain::validBargain() ? true : false;
        $data["is_pink"] = StoreCombination::getPinkIsOpen() ? true : false;
        $data["is_seckill"] = StoreSeckill::getSeckillCount() ? true : false;
        return \service\JsonService::successful($data);
    }
    public function get_record_list_count()
    {
        $type = osx_input("type", 3, "intval");
        $count = 0;
        if ($type == 3) {
            $count = \app\core\model\UserBill::getRecordCount($this->uid, "now_money", "brokerage");
        } else {
            if ($type == 4) {
                $count = UserExtract::userExtractTotalPrice($this->uid);
            }
        }
        $count = $count ? $count : 0;
        \service\JsonService::successful("", $count);
    }
    public function get_record_order_list()
    {
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $category = osx_input("category", "now_money", "text");
        $type = osx_input("type", "brokerage", "text");
        $data["list"] = [];
        $data["count"] = 0;
        $data["list"] = \app\core\model\UserBill::getRecordList($this->uid, $page, $limit, $category, $type);
        $count = \app\core\model\UserBill::getRecordOrderCount($this->uid, $category, $type);
        $data["count"] = $count ? $count : 0;
        if (!count($data["list"])) {
            return \service\JsonService::successful([]);
        }
        $value["child"] = \app\core\model\UserBill::getRecordOrderListDraw($this->uid, $value["time"], $category, $type);
        $value["count"] = count($value["child"]);
        return \service\JsonService::successful($data);
    }
    public function user_spread_new_list()
    {
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $grade = osx_input("grade", 0, "intval");
        $keyword = osx_input("keyword", "", "text");
        $sort = osx_input("sort", "", "text");
        if (!$keyword) {
            $keyword = "";
        }
        $data["list"] = \app\columnapi\model\user\User::getUserSpreadGrade($this->userInfo["uid"], bcadd($grade, 1, 0), $sort, $keyword, $page, $limit);
        $data["total"] = \app\columnapi\model\user\User::getSpreadCount($this->uid);
        $data["totalLevel"] = \app\columnapi\model\user\User::getSpreadLevelCount($this->uid);
        return \service\JsonService::successful($data);
    }
    public function user_spread_banner_list()
    {
        header("content-type:image/jpg");
        try {
            $routineSpreadBanner = \app\core\util\GroupDataService::getData("routine_spread_banner");
            if (!count($routineSpreadBanner)) {
                return \service\JsonService::fail("暂无海报");
            }
            $pathCode = makePathToUrl("routine/code", 3);
            if ($pathCode == "") {
                return \service\JsonService::fail("生成上传目录失败,请检查权限!");
            }
            $picName = $pathCode . DS . $this->userInfo["uid"] . ".jpg";
            $picName = trim(str_replace(DS, "/", $picName, $loop));
            $res = \app\core\model\routine\RoutineCode::getShareCode($this->uid, "spread", "", $picName);
            if ($res) {
                file_put_contents($picName, $res);
                $res = true;
                $url = \app\core\util\SystemConfigService::get("site_url") . "/";
                $domainTop = substr($url, 0, 5);
                if ($domainTop != "https") {
                    $url = "https:" . substr($url, 5, strlen($url));
                }
                $pathCode = makePathToUrl("routine/poster", 3);
                $config = ["image" => [["url" => ROOT_PATH . $picName, "stream" => 0, "left" => 114, "top" => 790, "right" => 0, "bottom" => 0, "width" => 120, "height" => 120, "opacity" => 100]], "text" => [["text" => $this->userInfo["nickname"], "left" => 250, "top" => 840, "fontPath" => ROOT_PATH . "public/static/font/SourceHanSansCN-Bold.otf", "fontSize" => 16, "fontColor" => "40,40,40", "angle" => 0], ["text" => "邀请您加入" . \app\core\util\SystemConfigService::get("website_name"), "left" => 250, "top" => 880, "fontPath" => ROOT_PATH . "public/static/font/SourceHanSansCN-Normal.otf", "fontSize" => 16, "fontColor" => "40,40,40", "angle" => 0]], "background" => $item["pic"]];
                $filename = ROOT_PATH . $pathCode . "/" . $item["id"] . "_" . $this->uid . ".png";
                $res = $res && \service\UtilService::setSharePoster($config, $filename);
                if ($res) {
                    $item["poster"] = $url . $pathCode . "/" . $item["id"] . "_" . $this->uid . ".png";
                }
                if ($res) {
                    return \service\JsonService::successful($routineSpreadBanner);
                }
                return \service\JsonService::fail("生成图片失败");
            } else {
                return \service\JsonService::fail("二维码生成失败");
            }
        } catch (\Exception $e) {
            return \service\JsonService::fail("生成图片时，系统错误", ["line" => $e->getLine(), "message" => $e->getMessage()]);
        }
    }
}

?>