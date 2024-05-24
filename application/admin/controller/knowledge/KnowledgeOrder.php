<?php


namespace app\admin\controller\knowledge;

class KnowledgeOrder extends \app\admin\controller\AuthController
{
    public function index()
    {
        $config = \service\SystemConfigService::more(["pay_routine_appid", "pay_routine_appsecret", "pay_routine_mchid", "pay_routine_key", "pay_routine_client_cert", "pay_routine_client_key"]);
        $this->assign(["year" => getMonth("y"), "real_name" => $this->request->get("real_name", ""), "orderCount" => \app\admin\model\column\StoreOrder::orderCount()]);
        return $this->fetch();
    }
    public function getBadge()
    {
        $where = \service\UtilService::postMore([["status", ""], ["real_name", ""], ["is_del", 0], ["data", ""], ["type", ""], ["order", ""], ["is_zg", 1]]);
        $info = [];
        $data = \app\admin\model\column\StoreOrder::getBadge($where);
        foreach ($data as $key => $value) {
            if ($value["name"] != "退款金额") {
                $info[] = $value;
            }
        }
        return \service\JsonService::successful($info);
    }
    public function order_list()
    {
        $where = \service\UtilService::getMore([["status", ""], ["real_name", $this->request->param("real_name", "")], ["is_del", 0], ["data", ""], ["type", ""], ["order", ""], ["page", 1], ["limit", 20], ["excel", 0], ["is_zg", 1]]);
        return \service\JsonService::successlayui(\app\admin\model\column\StoreOrder::OrderList($where));
    }
    public function orderchart()
    {
        $where = \service\UtilService::getMore([["status", ""], ["real_name", ""], ["is_del", 0], ["data", ""], ["combination_id", ""], ["export", 0], ["order", "id desc"]], $this->request);
        $limitTimeList = ["today" => implode(" - ", [date("Y/m/d"), date("Y/m/d", strtotime("+1 day"))]), "week" => implode(" - ", [date("Y/m/d", time() - ((date("w") == 0 ? 7 : date("w")) - 1) * 24 * 3600), date("Y-m-d", time() + (7 - (date("w") == 0 ? 7 : date("w"))) * 24 * 3600)]), "month" => implode(" - ", [date("Y/m") . "/01", date("Y/m") . "/" . date("t")]), "quarter" => implode(" - ", [date("Y") . "/" . (ceil(date("n") / 3) * 3 - 3 + 1) . "/01", date("Y") . "/" . ceil(date("n") / 3) * 3 . "/" . date("t", mktime(0, 0, 0, ceil(date("n") / 3) * 3, 1, date("Y")))]), "year" => implode(" - ", [date("Y") . "/01/01", date("Y/m/d", strtotime(date("Y") . "/01/01 + 1year -1 day"))])];
        if ($where["data"] == "") {
            $where["data"] = $limitTimeList["today"];
        }
        $orderCount = [urlencode("未支付") => \app\admin\model\column\StoreOrder::getOrderWhere($where, \app\admin\model\column\StoreOrder::statusByWhere(0))->count(), urlencode("待评价") => \app\admin\model\column\StoreOrder::getOrderWhere($where, \app\admin\model\column\StoreOrder::statusByWhere(3))->count(), urlencode("交易完成") => \app\admin\model\column\StoreOrder::getOrderWhere($where, \app\admin\model\column\StoreOrder::statusByWhere(4))->count()];
        $model = \app\admin\model\column\StoreOrder::getOrderWhere($where, new \app\admin\model\column\StoreOrder())->field("sum(total_num) total_num,count(*) count,sum(total_price) total_price,sum(refund_price) refund_price,from_unixtime(add_time,'%Y-%m-%d') add_time")->group("from_unixtime(add_time,'%Y-%m-%d')");
        $orderPrice = $model->select()->toArray();
        $orderDays = [];
        $orderCategory = [["name" => "商品数", "type" => "line", "data" => []], ["name" => "订单数", "type" => "line", "data" => []], ["name" => "订单金额", "type" => "line", "data" => []], ["name" => "退款金额", "type" => "line", "data" => []]];
        foreach ($orderPrice as $price) {
            $orderDays[] = $price["add_time"];
            $orderCategory[0]["data"][] = $price["total_num"];
            $orderCategory[1]["data"][] = $price["count"];
            $orderCategory[2]["data"][] = $price["total_price"];
            $orderCategory[3]["data"][] = $price["refund_price"];
        }
        $this->assign(\app\admin\model\column\StoreOrder::systemPage($where, $this->adminId));
        $this->assign("price", \app\admin\model\column\StoreOrder::getOrderPrice($where));
        $this->assign(compact("limitTimeList", "where", "orderCount", "orderPrice", "orderDays", "orderCategory"));
        return $this->fetch();
    }
    public function edit($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $f = [];
        $f[] = \service\FormBuilder::input("order_id", "订单编号", $product->getData("order_id"))->disabled(1);
        $f[] = \service\FormBuilder::number("total_price", "商品总价", $product->getData("total_price"))->min(0);
        $f[] = \service\FormBuilder::number("total_postage", "原始邮费", $product->getData("total_postage"))->min(0);
        $f[] = \service\FormBuilder::number("pay_price", "实际支付金额", $product->getData("pay_price"))->min(0);
        $f[] = \service\FormBuilder::number("pay_postage", "实际支付邮费", $product->getData("pay_postage"));
        $f[] = \service\FormBuilder::number("gain_integral", "赠送积分", $product->getData("gain_integral"));
        $form = \service\FormBuilder::make_post_form("修改订单", $f, \think\Url::build("update", ["id" => $id]));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["order_id", "total_price", "total_postage", "pay_price", "pay_postage", "gain_integral"], $request);
        if ($data["total_price"] <= 0) {
            return \service\JsonService::fail("请输入商品总价");
        }
        if ($data["pay_price"] <= 0) {
            return \service\JsonService::fail("请输入实际支付金额");
        }
        $data["order_id"] = \app\admin\model\column\StoreOrder::changeOrderId($data["order_id"]);
        \app\admin\model\column\StoreOrder::edit($data, $id);
        \service\HookService::afterListen("store_product_order_edit", $data, $id, false, "behavior\\admin\\OrderBehavior");
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "order_edit", "修改商品总价为：" . $data["total_price"] . " 实际支付金额" . $data["pay_price"]);
        return \service\JsonService::successful("修改成功!");
    }
    public function delivery($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["paid"] == 1 && $product["status"] == 0) {
            $f = [];
            $f[] = \service\FormBuilder::input("delivery_name", "送货人姓名")->required("送货人姓名不能为空", "required:true;");
            $f[] = \service\FormBuilder::input("delivery_id", "送货人电话")->required("请输入正确电话号码", "telephone");
            $form = \service\FormBuilder::make_post_form("修改订单", $f, \think\Url::build("updateDelivery", ["id" => $id]), 5);
            $this->assign(compact("form"));
            return $this->fetch("public/form-builder");
        }
        $this->failedNotice("订单状态错误");
    }
    public function updateDelivery(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["delivery_name", "delivery_id"], $request);
        $data["delivery_type"] = "send";
        if (!$data["delivery_name"]) {
            return \service\JsonService::fail("请输入送货人姓名");
        }
        if (!(array) $data["delivery_id"]) {
            return \service\JsonService::fail("请输入送货人电话号码");
        }
        if (!preg_match("/^1[3456789]{1}\\d{9}\$/", $data["delivery_id"])) {
            return \service\JsonService::fail("请输入正确的送货人电话号码");
        }
        $data["status"] = 1;
        \app\admin\model\column\StoreOrder::edit($data, $id);
        \service\HookService::afterListen("store_product_order_delivery", $data, $id, false, "behavior\\admin\\OrderBehavior");
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "delivery", "已配送 发货人：" . $data["delivery_name"] . " 发货人电话：" . $data["delivery_id"]);
        return \service\JsonService::successful("修改成功!");
    }
    public function deliver_goods($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["paid"] == 1 && $product["status"] == 0) {
            $f = [];
            $f[] = \service\FormBuilder::select("delivery_name", "快递公司")->setOptions(function () {
                $list = \think\Db::name("express")->where("is_show", 1)->order("sort DESC")->column("id,name");
                $menus = [];
                foreach ($list as $k => $v) {
                    $menus[] = ["value" => $v, "label" => $v];
                }
                return $menus;
            })->filterable(1);
            $f[] = \service\FormBuilder::input("delivery_id", "快递单号");
            $form = \service\FormBuilder::make_post_form("修改订单", $f, \think\Url::build("updateDeliveryGoods", ["id" => $id]), 5);
            $this->assign(compact("form"));
            return $this->fetch("public/form-builder");
        }
        return $this->failedNotice("订单状态错误");
    }
    public function updateDeliveryGoods(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["delivery_name", "delivery_id"], $request);
        $data["delivery_type"] = "express";
        if (!$data["delivery_name"]) {
            return \service\JsonService::fail("请选择快递公司");
        }
        if (!$data["delivery_id"]) {
            return \service\JsonService::fail("请输入快递单号");
        }
        $data["status"] = 1;
        \app\admin\model\column\StoreOrder::edit($data, $id);
        \service\HookService::afterListen("store_product_order_delivery_goods", $data, $id, false, "behavior\\admin\\OrderBehavior");
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "delivery_goods", "已发货 快递公司：" . $data["delivery_name"] . " 快递单号：" . $data["delivery_id"]);
        return \service\JsonService::successful("修改成功!");
    }
    public function take_delivery($id)
    {
        while (!$id) {
            $order = \app\admin\model\column\StoreOrder::get($id);
            if (!$order) {
                return \service\JsonService::fail("数据不存在!");
            }
            if ($order["status"] == 2) {
                return \service\JsonService::fail("不能重复收货!");
            }
            if ($order["paid"] == 1 && $order["status"] == 1) {
                $data["status"] = 2;
            } else {
                if ($order["pay_type"] == "offline") {
                    $data["status"] = 2;
                } else {
                    return \service\JsonService::fail("请先发货或者送货!");
                }
            }
            if (!\app\admin\model\column\StoreOrder::edit($data, $id)) {
                return \service\JsonService::fail(\app\admin\model\column\StoreOrder::getErrorInfo("收货失败,请稍候再试!"));
            }
            try {
                \service\HookService::listen("store_product_order_take_delivery", $order, $id, false, "behavior\\admin\\OrderBehavior");
                \app\admin\model\column\StoreOrderStatus::setStatus($id, "take_delivery", "已收货");
                return \service\JsonService::successful("收货成功!");
            } catch (\Exception $e) {
                return \service\JsonService::fail($e->getMessage());
            }
        }
        return $this->failed("数据不存在");
    }
    public function refund_y($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["paid"] == 1) {
            $f = [];
            $f[] = \service\FormBuilder::input("order_id", "退款单号", $product->getData("order_id"))->disabled(1);
            $f[] = \service\FormBuilder::number("refund_price", "退款金额", $product->getData("pay_price"))->precision(2)->min(0);
            $f[] = \service\FormBuilder::radio("type", "状态", 1)->options([["label" => "直接退款", "value" => 1], ["label" => "退款后,返回原状态", "value" => 2]]);
            $form = \service\FormBuilder::make_post_form("退款处理", $f, \think\Url::build("updateRefundY", ["id" => $id]), 5);
            $this->assign(compact("form"));
            return $this->fetch("public/form-builder");
        }
        return \service\JsonService::fail("数据不存在!");
    }
    public function updateRefundY(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["refund_price", ["type", 1]], $request);
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["pay_price"] == $product["refund_price"]) {
            return \service\JsonService::fail("已退完支付金额!不能再退款了");
        }
        if (!$data["refund_price"]) {
            return \service\JsonService::fail("请输入退款金额");
        }
        $refund_price = $data["refund_price"];
        $data["refund_price"] = bcadd($data["refund_price"], $product["refund_price"], 2);
        $bj = bccomp((object) $product["pay_price"], (object) $data["refund_price"], 2);
        if ($bj < 0) {
            return \service\JsonService::fail("退款金额大于支付金额，请修改退款金额");
        }
        if ($data["type"] == 1) {
            $data["refund_status"] = 2;
        } else {
            if ($data["type"] == 2) {
                $data["refund_status"] = 0;
            }
        }
        $type = $data["type"];
        unset($data["type"]);
        $refund_data["pay_price"] = $product["pay_price"];
        $refund_data["refund_price"] = $refund_price;
        if ($product["pay_type"] == "weixin") {
            if ($product["is_channel"]) {
                try {
                    \service\HookService::listen("routine_pay_order_refund", $product["order_id"], $refund_data, true, "behavior\\wechat\\PaymentBehavior");
                } catch (\Exception $e) {
                    return \service\JsonService::fail($e->getMessage());
                }
            }
            try {
                \service\HookService::listen("wechat_pay_order_refund", $product["order_id"], $refund_data, true, "behavior\\wechat\\PaymentBehavior");
            } catch (\Exception $e) {
                return \service\JsonService::fail($e->getMessage());
            }
        } else {
            if ($product["pay_type"] == "yue") {
                \basic\ModelBasic::beginTrans();
                $usermoney = db("user_wallet")->where("uid", $product["uid"])->value("all_money");
                $res1 = db("user_wallet")->bcInc($product["uid"], "all_money", $refund_price, "uid");
                $res3 = db("user_wallet")->bcInc($product["uid"], "enable_money", $refund_price, "uid");
                $res2 = \app\admin\model\user\UserBill::income("商品退款", $product["uid"], "all_money", "pay_product_refund", $refund_price, $product["id"], bcadd($usermoney, $refund_price, 2), "订单退款到余额" . floatval($refund_price) . "元");
                try {
                    \service\HookService::listen("store_order_yue_refund", $product, $refund_data, false, "behavior\\admin\\OrderBehavior");
                    $res = $res1 && $res2 && $res3;
                    \basic\ModelBasic::checkTrans($res);
                    if (!$res) {
                        return \service\JsonService::fail("余额退款失败!");
                    }
                } catch (\Exception $e) {
                    \basic\ModelBasic::rollbackTrans();
                    return \service\JsonService::fail($e->getMessage());
                }
            }
        }
        $resEdit = \app\admin\model\column\StoreOrder::edit($data, $id);
        if ($resEdit) {
            $data["type"] = $type;
            \service\HookService::afterListen("store_product_order_refund_y", $data, $id, false, "behavior\\admin\\OrderBehavior");
            \app\admin\model\column\StoreOrderStatus::setStatus($id, "refund_price", "退款给用户" . $refund_price . "元");
            $orderLog["order_id"] = $product["order_id"];
            $orderLog["uid_type"] = 1;
            $orderLog["uid"] = $this->adminId;
            $orderLog["info"] = "订单退款";
            \app\admin\model\payment\UserOrderLog::add_user_order_log($orderLog);
            \app\payapi\model\UserOrder::create_refund_order(["relation_order" => $product["order_id"], "amount" => $refund_price, "pay_type" => $product["pay_type"], "uid" => $product["uid"]]);
            return \service\JsonService::successful("修改成功!");
        }
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "refund_price", "退款给用户" . $refund_price . "元失败");
        return \service\JsonService::successful("修改失败!");
    }
    public function order_info($oid = "")
    {
        if (!$oid || !($orderInfo = \app\admin\model\column\StoreOrder::get($oid))) {
            return $this->failed("订单不存在!");
        }
        $userInfo = \app\admin\model\user\User::getUserInfos($orderInfo["uid"]);
        if ($userInfo["spread_uid"]) {
            $spread = \app\admin\model\user\User::where("uid", $userInfo["spread_uid"])->value("nickname");
        } else {
            $spread = "";
        }
        $this->assign(compact("orderInfo", "userInfo", "spread"));
        return $this->fetch();
    }
    public function express($oid = "")
    {
        if (!$oid || !($order = \app\admin\model\column\StoreOrder::get($oid))) {
            return $this->failed("订单不存在!");
        }
        if ($order["delivery_type"] != "express" || !$order["delivery_id"]) {
            return $this->failed("该订单不存在快递单号!");
        }
        $cacheName = $order["order_id"] . $order["delivery_id"];
        $result = \service\CacheService::get($cacheName, NULL);
        if ($result === NULL || true) {
            $result = \Api\Express::query($order["delivery_id"]);
            if (is_array($result) && isset($result["result"]) && isset($result["result"]["deliverystatus"]) && 3 <= $result["result"]["deliverystatus"]) {
                $cacheTime = 0;
            } else {
                $cacheTime = 1800;
            }
            \service\CacheService::set($cacheName, $result, $cacheTime);
        }
        $this->assign(["order" => $order, "express" => $result]);
        return $this->fetch();
    }
    public function distribution($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $f = [];
        $f[] = \service\FormBuilder::input("order_id", "退款单号", $product->getData("order_id"))->disabled(1);
        if ($product["delivery_type"] == "send") {
            $f[] = \service\FormBuilder::input("delivery_name", "送货人姓名", $product->getData("delivery_name"));
            $f[] = \service\FormBuilder::input("delivery_id", "送货人电话", $product->getData("delivery_id"));
        } else {
            if ($product["delivery_type"] == "express") {
                $f[] = \service\FormBuilder::select("delivery_name", "快递公司", $product->getData("delivery_name"))->setOptions(function () {
                    $list = \think\Db::name("express")->where("is_show", 1)->column("id,name");
                    $menus = [];
                    foreach ($list as $k => $v) {
                        $menus[] = ["value" => $v, "label" => $v];
                    }
                    return $menus;
                });
                $f[] = \service\FormBuilder::input("delivery_id", "快递单号", $product->getData("delivery_id"));
            }
        }
        $form = \service\FormBuilder::make_post_form("配送信息", $f, \think\Url::build("updateDistribution", ["id" => $id]), 5);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function updateDistribution(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["delivery_name", "delivery_id"], $request);
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["delivery_type"] == "send") {
            if (!$data["delivery_name"]) {
                return \service\JsonService::fail("请输入送货人姓名");
            }
            if (!(array) $data["delivery_id"]) {
                return \service\JsonService::fail("请输入送货人电话号码");
            }
            if (!preg_match("/^1[3456789]{1}\\d{9}\$/", $data["delivery_id"])) {
                return \service\JsonService::fail("请输入正确的送货人电话号码");
            }
        } else {
            if ($product["delivery_type"] == "express") {
                if (!$data["delivery_name"]) {
                    return \service\JsonService::fail("请选择快递公司");
                }
                if (!$data["delivery_id"]) {
                    return \service\JsonService::fail("请输入快递单号");
                }
            }
        }
        \app\admin\model\column\StoreOrder::edit($data, $id);
        \service\HookService::afterListen("store_product_order_distribution", $data, $id, false, "behavior\\admin\\OrderBehavior");
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "distribution", "修改发货信息为" . $data["delivery_name"] . "号" . $data["delivery_id"]);
        return \service\JsonService::successful("修改成功!");
    }
    public function refund_n($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $f[] = \service\FormBuilder::input("order_id", "退款单号", $product->getData("order_id"))->disabled(1);
        $f[] = \service\FormBuilder::input("refund_reason", "拒绝退款原因")->type("textarea");
        $form = \service\FormBuilder::make_post_form("退款", $f, \think\Url::build("updateRefundN", ["id" => $id]));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function updateRefundN(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["refund_reason"], $request);
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if (!$data["refund_reason"]) {
            return \service\JsonService::fail("请输入拒绝退款原因");
        }
        $data["refund_status"] = 0;
        \app\admin\model\column\StoreOrder::edit($data, $id);
        \service\HookService::afterListen("store_product_order_refund_n", $data["refund_reason"], $id, false, "behavior\\admin\\OrderBehavior");
        \app\admin\model\column\StoreOrderStatus::setStatus($id, "refund_n", "拒绝拒绝退款原因:" . $data["refund_reason"]);
        return \service\JsonService::successful("修改成功!");
    }
    public function offline($id)
    {
        $res = \app\admin\model\column\StoreOrder::updateOffline($id);
        if ($res) {
            try {
                \service\HookService::listen("store_product_order_offline", $id, false, "behavior\\admin\\OrderBehavior");
                \app\admin\model\column\StoreOrderStatus::setStatus($id, "offline", "线下付款");
                return \service\JsonService::successful("修改成功!");
            } catch (\EasyWeChat\Core\Exception $e) {
                return \service\JsonService::fail($e->getMessage());
            }
        }
        return \service\JsonService::fail("修改失败!");
    }
    public function integral_back($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($product["paid"] == 1) {
            $f[] = \service\FormBuilder::input("order_id", "退款单号", $product->getData("order_id"))->disabled(1);
            $f[] = \service\FormBuilder::number("use_integral", "使用的积分", $product->getData("use_integral"))->min(0)->disabled(1);
            $f[] = \service\FormBuilder::number("use_integrals", "已退积分", $product->getData("back_integral"))->min(0)->disabled(1);
            $f[] = \service\FormBuilder::number("back_integral", "可退积分", bcsub($product->getData("use_integral"), $product->getData("use_integral")))->min(0);
            $form = \service\FormBuilder::make_post_form("退积分", $f, \think\Url::build("updateIntegralBack", ["id" => $id]));
            $this->assign(compact("form"));
            return $this->fetch("public/form-builder");
        }
        return \service\JsonService::fail("参数错误!");
    }
    public function updateIntegralBack(\think\Request $request, $id)
    {
        $data = \service\UtilService::postMore(["back_integral"], $request);
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\StoreOrder::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        if ($data["back_integral"] <= 0) {
            return \service\JsonService::fail("请输入积分");
        }
        if ($product["use_integral"] == $product["back_integral"]) {
            return \service\JsonService::fail("已退完积分!不能再积分了");
        }
        $back_integral = $data["back_integral"];
        $data["back_integral"] = bcadd($data["back_integral"], $product["back_integral"], 2);
        $bj = bccomp((object) $product["use_integral"], (object) $data["back_integral"], 2);
        if ($bj < 0) {
            return \service\JsonService::fail("退积分大于支付积分，请修改退积分");
        }
        \basic\ModelBasic::beginTrans();
        $integral = \app\admin\model\user\User::where("uid", $product["uid"])->value("integral");
        $res1 = \app\admin\model\user\User::bcInc($product["uid"], "integral", $back_integral, "uid");
        $res2 = \app\admin\model\user\UserBill::income("商品退积分", $product["uid"], "integral", "pay_product_integral_back", $back_integral, $product["id"], bcadd($integral, $back_integral, 2), "订单退积分" . floatval($back_integral) . "积分到用户积分");
        try {
            \service\HookService::listen("store_order_integral_back", $product, $back_integral, false, "behavior\\admin\\OrderBehavior");
            $res = $res1 && $res2;
            \basic\ModelBasic::checkTrans($res);
            if (!$res) {
                return \service\JsonService::fail("退积分失败!");
            }
            \app\admin\model\column\StoreOrder::edit($data, $id);
            \app\admin\model\column\StoreOrderStatus::setStatus($id, "integral_back", "商品退积分：" . $data["back_integral"]);
            return \service\JsonService::successful("退积分成功!");
        } catch (\Exception $e) {
            \basic\ModelBasic::rollbackTrans();
            return \service\JsonService::fail($e->getMessage());
        }
    }
    public function remark(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["id", "remark"], $request);
        if (!$data["id"]) {
            return \service\JsonService::fail("参数错误!");
        }
        if ($data["remark"] == "") {
            return \service\JsonService::fail("请输入要备注的内容!");
        }
        $id = $data["id"];
        unset($data["id"]);
        \app\admin\model\column\StoreOrder::edit($data, $id);
        return \service\JsonService::successful("备注成功!");
    }
    public function order_status($oid)
    {
        if (!$oid) {
            return $this->failed("数据不存在");
        }
        $this->assign(\app\admin\model\column\StoreOrderStatus::systemPage($oid));
        return $this->fetch();
    }
}

?>