<?php


namespace app\columnapi\controller;

class AuthApi extends AuthController
{
    public static function whiteList()
    {
        return ["time_out_order", "user_message_order"];
    }
    public function get_cart_list()
    {
        return \service\JsonService::successful(\app\columnapi\model\store\StoreCart::getUserProductCartList($this->userInfo["uid"]));
    }
    public function get_order_pay_info()
    {
        $order_id = osx_input("order_id", "", "text");
        if ($order_id == "") {
            return \service\JsonService::fail("缺少参数");
        }
        return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::tidyOrder(\app\columnapi\model\store\StoreOrder::where("order_id", $order_id)->find()));
    }
    public function confirm_order(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["cartId"], $request);
        $cartId = $data["cartId"];
        if (!is_string($cartId) || !$cartId) {
            return \service\JsonService::fail("请提交购买的商品!");
        }
        $cartGroup = \app\columnapi\model\store\StoreCart::getUserProductCartList($this->userInfo["uid"], $cartId, 1);
        if (count($cartGroup["invalid"])) {
            return \service\JsonService::fail($cartGroup["invalid"][0]["productInfo"]["store_name"] . "已失效!");
        }
        if (!$cartGroup["valid"]) {
            return \service\JsonService::fail("请提交购买的商品!!");
        }
        $cartInfo = $cartGroup["valid"];
        $priceGroup = \app\columnapi\model\store\StoreOrder::getOrderPriceGroup($cartInfo);
        $other = ["offlinePostage" => \app\core\util\SystemConfigService::get("offline_postage"), "integralRatio" => \app\core\util\SystemConfigService::get("integral_ratio")];
        $usableCoupon = \app\columnapi\model\store\StoreCouponUser::beUsableCoupon($this->userInfo["uid"], $priceGroup["totalPrice"]);
        $cartIdA = explode(",", $cartId);
        if (1 < count($cartIdA)) {
            $seckill_id = 0;
        } else {
            $seckillinfo = \app\columnapi\model\store\StoreCart::where("id", $cartId)->find();
            if (0 < (array) $seckillinfo["seckill_id"]) {
                $seckill_id = $seckillinfo["seckill_id"];
            } else {
                $seckill_id = 0;
            }
        }
        $data["usableCoupon"] = $usableCoupon;
        $data["seckill_id"] = $seckill_id;
        $data["cartInfo"] = $cartInfo;
        $data["priceGroup"] = $priceGroup;
        $data["orderKey"] = \app\columnapi\model\store\StoreOrder::cacheOrderInfo($this->userInfo["uid"], $cartInfo, $priceGroup, $other);
        $data["offlinePostage"] = $other["offlinePostage"];
        $vipId = \app\core\model\UserLevel::getUserLevel($this->uid);
        $this->userInfo["vip"] = $vipId !== false ? true : false;
        if ($this->userInfo["vip"]) {
            $this->userInfo["vip_id"] = $vipId;
            $this->userInfo["discount"] = \app\core\model\UserLevel::getUserLevelInfo($vipId, "discount");
        }
        $data["userInfo"] = $this->userInfo;
        $data["integralRatio"] = $other["integralRatio"];
        return \service\JsonService::successful($data);
    }
    public function cacheorder(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["cartId"], $request);
        $cartId = $data["cartId"];
        if (!is_string($cartId) || !$cartId) {
            return \service\JsonService::fail("请提交购买的商品!");
        }
        $cartGroup = \app\columnapi\model\store\StoreCart::getUserZgCartList($this->userInfo["uid"], $cartId, 1);
        if (count($cartGroup["invalid"])) {
            return \service\JsonService::fail($cartGroup["invalid"][0]["productInfo"]["store_name"] . "已失效!");
        }
        if (!$cartGroup["valid"]) {
            return \service\JsonService::fail("请提交购买的商品!!");
        }
        $cartInfo = $cartGroup["valid"];
        $priceGroup = \app\columnapi\model\store\StoreOrder::getOrderPriceGroup($cartInfo);
        $other = ["offlinePostage" => \app\core\util\SystemConfigService::get("offline_postage"), "integralRatio" => \app\core\util\SystemConfigService::get("integral_ratio")];
        $usableCoupon = \app\columnapi\model\store\StoreCouponUser::beUsableCoupon($this->userInfo["uid"], $priceGroup["totalPrice"]);
        $cartIdA = explode(",", $cartId);
        if (1 < count($cartIdA)) {
            $seckill_id = 0;
        } else {
            $seckillinfo = \app\columnapi\model\store\StoreCart::where("id", $cartId)->find();
            if (0 < (array) $seckillinfo["seckill_id"]) {
                $seckill_id = $seckillinfo["seckill_id"];
            } else {
                $seckill_id = 0;
            }
        }
        $data["usableCoupon"] = $usableCoupon;
        $data["seckill_id"] = $seckill_id;
        $data["cartInfo"] = $cartInfo;
        $data["priceGroup"] = $priceGroup;
        $data["orderKey"] = \app\columnapi\model\store\StoreOrder::cacheOrderInfo($this->userInfo["uid"], $cartInfo, $priceGroup, $other);
        $data["offlinePostage"] = $other["offlinePostage"];
        $data["userInfo"] = $this->userInfo;
        $data["integralRatio"] = $other["integralRatio"];
        return \service\JsonService::successful($data);
    }
    public function get_order_data()
    {
        return \service\JsonService::successful(\app\columnapi\model\store\StoreOrder::getOrderData($this->uid));
    }
    public function unique()
    {
        $productId = $_GET["productId"];
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $uniqueId = \app\columnapi\model\store\StoreProductAttrValue::where("product_id", $productId)->value("unique");
        $data = $this->set_cart($productId, $cartNum = 1, $uniqueId);
        if ($data) {
            return \service\JsonService::successful("ok");
        }
    }
    public function set_cart()
    {
        $productId = osx_input("productId", "", "text");
        $cartNum = osx_input("cartNum", 1, "text");
        $uniqueId = osx_input("uniqueId", "", "text");
        $type = osx_input("type", "product", "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $cart_limit = \app\admin\model\system\SystemConfig::getValue("cart_limit");
        if ($cart_limit < $cartNum) {
            return \service\JsonService::fail("加入购物车数量最高限制为" . $cart_limit . "件");
        }
        $res = \app\columnapi\model\store\StoreCart::setCart($this->userInfo["uid"], $productId, $cartNum, $uniqueId, $type, "", "", "", "", "add_to_cart");
        if (!$res->result) {
            return \service\JsonService::fail(\app\columnapi\model\store\StoreCart::getErrorInfo());
        }
        return \service\JsonService::successful("ok", ["cartId" => $res->id]);
    }
    public function now_buy_zg()
    {
        $productId = osx_input("productId", "", "text");
        $cartNum = osx_input("cartNum", 1, "intval");
        $uniqueId = osx_input("uniqueId", "", "text");
        $combinationId = osx_input("combinationId", 0, "intval");
        $secKillId = osx_input("secKillId", 0, "intval");
        $bargainId = osx_input("bargainId", 0, "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $pid = db("column_text")->where("id", $productId)->value("pid");
        if ($pid != 0) {
            return \service\JsonService::fail(\app\columnapi\model\store\StoreCart::getErrorInfo("请购买专栏"));
        }
        $res = \app\columnapi\model\store\StoreCart::setCart($this->userInfo["uid"], $productId, $cartNum, $uniqueId, "is_zg", 1, $combinationId, $secKillId, $bargainId);
        if (!$res->result) {
            return \service\JsonService::fail(\app\columnapi\model\store\StoreCart::getErrorInfo());
        }
        return \service\JsonService::successful("ok", ["cartId" => $res->id]);
    }
    public function get_cart_num()
    {
        return \service\JsonService::successful("ok", \app\columnapi\model\store\StoreCart::getUserCartNum($this->userInfo["uid"], "is_zg"));
    }
    public function change_cart_num()
    {
        $cartId = osx_input("cartId", "", "text");
        $cartNum = osx_input("cartNum", "", "text");
        if (!$cartId || !$cartNum || !is_numeric($cartId) || !is_numeric($cartNum)) {
            return \service\JsonService::fail("参数错误!");
        }
        $cart_limit = \app\admin\model\system\SystemConfig::getValue("cart_limit");
        if ($cart_limit < $cartNum) {
            return \service\JsonService::fail("加入购物车数量最高限制为" . $cart_limit . "件");
        }
        $res = \app\columnapi\model\store\StoreCart::changeUserCartNum($cartId, $cartNum, $this->userInfo["uid"]);
        if ($res) {
            return \service\JsonService::successful();
        }
        return \service\JsonService::fail(\app\columnapi\model\store\StoreCart::getErrorInfo("修改失败"));
    }
    public function remove_cart()
    {
        $ids = osx_input("ids", "", "text");
        if (!$ids) {
            return \service\JsonService::fail("参数错误!");
        }
        $res = \app\columnapi\model\store\StoreCart::removeUserCart($this->userInfo["uid"], $ids);
        if ($res) {
            return \service\JsonService::successful("删除成功");
        }
        return \service\JsonService::fail("删除失败!");
    }
    public function create_zg_order()
    {
        list($midkey, $addressId, $couponId, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId, $is_zg) = \service\UtilService::postMore(["midkey", "addressId", "couponId", "useIntegral", "mark", ["combinationId", 0], ["pinkId", 0], ["seckill_id", 0], ["formId", ""], ["bargainId", ""], ["is_zg", "1"]], \think\Request::instance(), true);
        if (!$midkey) {
            return \service\JsonService::fail("参数错误!");
        }
        $iv = "1234567890123412";
        $key = "201707eggplant99";
        $midkey = trim(openssl_decrypt(base64_decode($midkey), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv));
        $close_order_time = \app\admin\model\system\SystemConfig::getMore("close_order_time");
        $close_order_time = $close_order_time["close_order_time"];
        if (\app\columnapi\model\store\StoreOrder::be(["uid" => $this->userInfo["uid"], "paid" => 0, "is_del" => 0, "is_zg" => 1, "add_time" => ["gt", time() - $close_order_time * 3600]])) {
            return \service\JsonService::fail("您还有未支付的订单，请先取消或者支付订单以后再下单");
        }
        if (\app\columnapi\model\store\StoreOrder::be(["order_id|unique" => $midkey, "uid" => $this->userInfo["uid"], "is_del" => 0])) {
            return \service\JsonService::status("extend_order", "该订单已生成", ["orderId" => $midkey, "key" => $midkey]);
        }
        $order = \app\columnapi\model\store\StoreOrder::ZgCreateOrderNew($this->userInfo["uid"], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId, $is_zg);
        $orderId = $order["order_id"];
        $info = compact("orderId", "midkey");
        if ($orderId) {
            \app\core\model\routine\RoutineFormId::SetFormId($formId, $this->uid);
            \app\commonapi\model\Gong::actionadd("goumaizhishishangpin", "store_order", "uid");
            return \service\JsonService::status("success", "订单创建成功", $info);
        }
        return \service\JsonService::fail(\app\columnapi\model\store\StoreOrder::getErrorInfo("订单生成失败!"));
    }
    public function notify()
    {
        \app\core\util\WechatService::handleNotify();
    }
    public function time_out()
    {
        $uni = osx_input("uni", "", "text");
        if (!$uni) {
            return \service\JsonService::fail("参数错误!");
        }
        $order = \app\columnapi\model\store\StoreOrder::getUserOrderDetail($this->userInfo["uid"], $uni);
        if (!$order) {
            return \service\JsonService::fail("订单不存在!");
        }
        $time = time() - 86400;
        if ($order["paid"] == 0 && $order["add_time"] < $time) {
            \app\columnapi\model\store\StoreOrder::cancelOrder($order["order_id"]);
            $data = 0;
            \service\JsonService::fail($data);
        } else {
            $data = 1;
            return \service\JsonService::successful($data);
        }
    }
    public function pay_order_new()
    {
        $uni = osx_input("uni", "", "text");
        $paytype = osx_input("paytype", "weixin", "text");
        $bill_type = osx_input("bill_type", "pay_product", "text");
        if (!$uni) {
            return \service\JsonService::fail("参数错误!");
        }
        $order = \app\columnapi\model\store\StoreOrder::getUserOrderDetail($this->userInfo["uid"], $uni);
        if (!$order) {
            return \service\JsonService::fail("订单不存在!");
        }
        if ($order["paid"]) {
            return \service\JsonService::fail("该订单已支付!");
        }
        if ($order["pink_id"] && \app\columnapi\model\store\StorePink::isPinkStatus($order["pink_id"])) {
            return \service\JsonService::fail("该订单已失效!");
        }
        if ($order["pay_price"] == 0 && $order["is_zg"] == 1) {
            $res = \app\columnapi\model\store\StoreOrder::yuePay($order["order_id"], $this->userInfo["uid"], "", $bill_type);
            if ($res) {
                return \service\JsonService::successful("购买成功");
            }
            return \service\JsonService::fail("购买失败");
        }
        $order["pay_type"] = $paytype;
        switch ($order["pay_type"]) {
            case "weixin":
                $status = db("pay_set")->where("type", "weixin")->value("status");
                if ($status == 0) {
                    return \service\JsonService::fail("该支付未开启!");
                }
                try {
                    $jsConfig = \app\columnapi\model\store\StoreOrder::jsPay($order);
                    if (isset($jsConfig["package"]) && $jsConfig["package"]) {
                        $jsConfig["package"] = str_replace("prepay_id=", "", $jsConfig["package"]);
                        $i = 0;
                        while ($i < 3) {
                            \app\core\model\routine\RoutineFormId::SetFormId($jsConfig["package"], $this->uid);
                            $i++;
                        }
                    }
                    $jsConfig["package"] = "prepay_id=" . $jsConfig["package"];
                    \app\columnapi\model\store\StoreOrder::where("id", $order["id"])->update(["pay_type" => "weixin"]);
                    return \service\JsonService::status("wechat_pay", ["jsConfig" => $jsConfig, "order_id" => $order["order_id"]]);
                } catch (\Exception $e) {
                    return \service\JsonService::fail($e->getMessage());
                }
                break;
            case "routine":
                $status = db("pay_set")->where("type", "weixin")->value("status");
                if ($status == 0) {
                    return \service\JsonService::fail("该支付未开启!");
                }
                try {
                    $jsConfig = \app\columnapi\model\store\StoreOrder::MiniProgramJsPay($order);
                    if (isset($jsConfig["package"]) && $jsConfig["package"]) {
                        $jsConfig["package"] = str_replace("prepay_id=", "", $jsConfig["package"]);
                        $i = 0;
                        while ($i < 3) {
                            \app\core\model\routine\RoutineFormId::SetFormId($jsConfig["package"], $this->uid);
                            $i++;
                        }
                    }
                    $jsConfig["package"] = "prepay_id=" . $jsConfig["package"];
                    \app\columnapi\model\store\StoreOrder::where("id", $order["id"])->update(["pay_type" => "routine"]);
                    return \service\JsonService::status("wechat_pay", ["jsConfig" => $jsConfig, "order_id" => $order["order_id"]]);
                } catch (\Exception $e) {
                    return \service\JsonService::fail($e->getMessage());
                }
                break;
            case "weixin_app":
                $status = db("pay_set")->where("type", "weixin")->value("status");
                if ($status == 0) {
                    return \service\JsonService::fail("该支付未开启!");
                }
                try {
                    $appConfig = \app\columnapi\model\store\StoreOrder::wechatAppPay($order);
                    $i = 0;
                    while ($i < 3) {
                        \app\core\model\routine\RoutineFormId::SetFormId($appConfig["prepayid"], $this->uid);
                        $i++;
                    }
                    \app\columnapi\model\store\StoreOrder::where("id", $order["id"])->update(["pay_type" => "weixin_app"]);
                    return \service\JsonService::status("wechat_app_pay", ["appConfig" => $appConfig, "order_id" => $order["order_id"]]);
                } catch (\Exception $e) {
                    return \service\JsonService::fail($e->getMessage());
                }
                break;
            case "yue":
                $status = db("pay_set")->where("type", "yue")->value("status");
                if ($status == 0) {
                    return \service\JsonService::fail("该支付未开启!");
                }
                if ($res = \app\columnapi\model\store\StoreOrder::yuePay($order["order_id"], $this->userInfo["uid"], "", $bill_type)) {
                    return \service\JsonService::successful("余额支付成功");
                }
                $error = \app\columnapi\model\store\StoreOrder::getErrorInfo();
                return \service\JsonService::fail(is_array($error) && isset($error["msg"]) ? $error["msg"] : $error);
                break;
        }
    }
    public function cancel_order()
    {
        $order_id = osx_input("order_id", "", "text");
        if (\app\columnapi\model\store\StoreOrder::cancelOrder($order_id)) {
            return \service\JsonService::successful("取消订单成功");
        }
        return \service\JsonService::fail(\app\columnapi\model\store\StoreOrder::getErrorInfo());
    }
}

?>