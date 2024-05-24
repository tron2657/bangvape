<?php


namespace app\ebapi\controller;

class ColumnApi extends AuthController
{
    public static function whiteList()
    {
        return ["home", "get_content", "is_sets", "index", "get_type_column", "get_column_catalog", "get_book_count", "get_column_content", "getRootCommit", "product_reply_list"];
    }
    public function index()
    {
        $orderBy = "";
        $sid = input("post.sid") ? input("post.sid") : 0;
        $order = input("post.order") ? input("post.order") : "DESC";
        $select = input("post.select") ? input("post.select") : "";
        $sorts = input("post.sorts") ? input("post.sorts") : "";
        if (!empty($sorts)) {
            $orderBy = $sorts . " " . $order;
        }
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 10;
        $get_commission_level_one = \app\admin\model\system\SystemConfig::getValue("agent_yongjin_config");
        $info = \app\ebapi\model\store\StoreProduct::getColumnProduct("", $size, $page, $orderBy, $select, $sid);
        if ($select != "") {
            $access = $this->access;
            \app\osapi\model\common\Search::addSearch($select, $this->uid, "知识商城", $access[1]);
        }
        $info[$key]["num_q"] = \app\ebapi\model\column\ColumnText::getCatalogCount($value["id"]);
        $r = db("text_user")->where("aid", $value["mer_id"])->find();
        $info[$key]["test_nickname"] = $r["nickname"];
        $info[$key]["test_avatar"] = $r["avatar"];
        $info[$key]["test_level"] = $r["level"];
        $info[$key]["test_signature"] = $r["signature"];
        $info[$key]["income"] = $value["strip_num"] * $get_commission_level_one / 100;
        $infoCount = \app\ebapi\model\store\StoreProduct::getColumnCountProduct($select, $sid);
        return \service\JsonService::successlayui($infoCount, $info, "success", 200);
    }
    public function recommendProductList()
    {
        $orderBy = "";
        $order = input("post.order") ? input("post.order") : "DESC";
        $select = input("post.select") ? input("post.select") : "";
        $sorts = input("post.sorts") ? input("post.sorts") : "";
        if (!empty($sorts)) {
            $orderBy = $sorts . " " . $order;
        }
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 10;
        $get_commission_level_one = \app\admin\model\system\SystemConfig::getValue("agent_yongjin_config");
        $info = \app\ebapi\model\store\StoreProduct::getReColumnProduct("", $size, $page, $orderBy, $select);
        $info[$key]["num_q"] = \app\ebapi\model\column\ColumnText::getCatalogCount($value["id"]);
        $r = db("text_user")->where("aid", $value["mer_id"])->find();
        $info[$key]["test_nickname"] = $r["nickname"];
        $info[$key]["test_avatar"] = $r["avatar"];
        $info[$key]["test_level"] = $r["level"];
        $info[$key]["test_signature"] = $r["signature"];
        $info[$key]["income"] = $value["strip_num"] * $get_commission_level_one / 100;
        $infoCount = \app\ebapi\model\store\StoreProduct::getReColumnCountProduct($select);
        return \service\JsonService::successlayui($infoCount, $info, "success", 200);
    }
    public function home()
    {
        $select = "";
        $orderBy = "pid DESC";
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 4;
        $info = \app\ebapi\model\store\StoreProduct::getColumnProduct("", $size, $page, $orderBy, $select);
        return \service\JsonService::successlayui(0, $info, "success", 200);
    }
    public function get_type_column()
    {
        return \service\JsonService::successful(\app\ebapi\model\store\StoreCategoryColumn::getProductCategory());
    }
    public function is_sets()
    {
        $uid = 0;
        $info["is_set"] = 0;
        $token = $this->request->header("access-token");
        $gid = input("post.gid") ? input("post.gid") : 0;
        if (!$gid) {
            return \service\JsonService::fail("参数错误!!");
        }
        if (!empty($token)) {
            $token_os = db("os_token")->where("token", $token)->value("uid");
            $uid = $token_os;
        }
        $rester = Db("store_cart")->where("uid", $uid)->where("type", "is_zg")->where("product_id", $gid)->select();
        foreach ($rester as $k => $v) {
            $rs = \app\ebapi\model\store\StoreOrder::is_gm($uid, $v["id"]);
            if (0 < count($rs)) {
                $info["is_set"] = 1;
            }
        }
        return \service\JsonService::successful("success", $info, 200);
    }
    public function get_column_content()
    {
        $uid = 0;
        $token = $this->request->header("access-token");
        if (!empty($token)) {
            $token_os = db("os_token")->where("token", $token)->value("uid");
            $uid = $token_os;
        }
        $gid = input("post.gid") ? input("post.gid") : 0;
        if (!$gid) {
            return \service\JsonService::fail("参数错误!!");
        }
        $info = \app\ebapi\model\store\StoreProduct::getContentProduct($gid, "id,image,slider_image,store_name,store_info,price,ot_price,vip_price,postage,stock,description,add_time,browse,IFNULL(sales,0) + IFNULL(ficti,0) as sales,unit_name,is_del");
        $info["is_set"] = 0;
        $info["is_collect"] = false;
        $info["is_book_shelf"] = false;
        $info["read_position"] = NULL;
        $info["book_shelf_num"] = 0;
        \app\ebapi\model\store\StoreProduct::edit(["browse" => $info["browse"] + 1], $gid);
        if (!empty($uid)) {
            $rester = Db("store_cart")->where("uid", $uid)->where("type", "is_zg")->where("product_id", $gid)->select();
            foreach ($rester as $k => $v) {
                $rs = \app\ebapi\model\store\StoreOrder::is_gm($uid, $v["id"]);
                if ($rs) {
                    $info["is_set"] = 1;
                }
            }
            $collect = \app\ebapi\model\read\Collect::getUserCollect($uid, $gid);
            if (!empty($collect)) {
                $info["is_collect"] = true;
            }
            $is_book_shelf = \app\ebapi\model\store\StoreCart::getUserOrder($uid, $gid, $where = ["is_del" => 0]);
            if (!empty($is_book_shelf)) {
                $info["is_book_shelf"] = true;
            }
            $read = \app\ebapi\model\read\UserRead::getUserRead($uid, $gid);
            if (!empty($read)) {
                $info["read_position"] = $read["rid"];
            }
            $info["book_shelf_num"] = \app\ebapi\model\store\StoreCart::getCartCountSum($uid, $where = ["is_del" => 0, "is_pay" => 0]);
        }
        return \service\JsonService::successful("success", $info, 200);
    }
    public function get_column_catalog()
    {
        $orderBy = "";
        $gid = input("post.gid") ? input("post.gid") : 0;
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 1000;
        $order = input("post.order");
        $sort = input("post.sort");
        $orderStyle = \app\ebapi\model\store\StoreProduct::getCategoryContentSortStyle($gid);
        if (!$gid) {
            return \service\JsonService::fail("参数错误!!");
        }
        if (empty($order)) {
            $orderBy = "sort " . $sort . ", " . "id " . $orderStyle;
        } else {
            $orderBy = "id " . $order;
        }
        $info = \app\ebapi\model\column\ColumnText::getCatalog($gid, "id,img,name,type,is_read,sort,create_time,read_num as browse_num", $orderBy, $page, $size)->toArray();
        $value["create_time"] = time_format($value["create_time"]);
        $value["img"] = get_root_path($value["img"]);
        $infoCount = \app\ebapi\model\column\ColumnText::getCatalogCount($gid);
        return \service\JsonService::successlayui($infoCount, $info, "successs", 200);
    }
    public function get_content()
    {
        $id = input("post.id") ? input("post.id") : 0;
        if (!$id) {
            return \service\JsonService::fail("参数错误!!");
        }
        $info = \app\ebapi\model\column\ColumnText::getContent($id);
        if (!$info) {
            return \service\JsonService::fail("数据不存在!!");
        }
        if ($info["m_type"] == 1 && strpos($info["info"], "http") === 0) {
            $getYunConfig = \app\commonapi\model\TencentFile::ifYunUpload();
            $getYunMediaKey = $getYunConfig["pkey"];
            $info["media_url"] = \app\commonapi\model\TencentFile::yunKeyMediaUrl($info["info"], $getYunMediaKey);
        }
        if ($info["image"] && strpos($info["image"], "/") === 0) {
            $info["image"] = "https://" . \think\Request::instance()->server("SERVER_NAME") . $info["image"];
        }
        if ($info["info"] && strpos($info["info"], "/") === 0) {
            $info["media_url"] = "https://" . \think\Request::instance()->server("SERVER_NAME") . $info["info"];
        }
        $create_time = strtotime($info["create_time"]);
        $info["create_times"] = $create_time;
        $token = $this->request->header("access-token");
        if (!empty($token)) {
            $uid = $this->userInfo["uid"];
            $read = \app\ebapi\model\read\UserRead::getUserRead($uid, $info["pid"]);
            if (!empty($read)) {
                $data["rid"] = $id;
                $data["create_time"] = time();
                \app\ebapi\model\read\UserRead::updates($data, $read["id"]);
            } else {
                $data = ["uid" => $uid, "pid" => $info["pid"], "rid" => $id, "create_time" => time()];
                \app\ebapi\model\read\UserRead::set($data);
            }
        } else {
            if ($info["is_read"] != 1) {
                return \service\JsonService::fail("不是免费试读数据!!");
            }
        }
        \app\ebapi\model\column\ColumnText::edit(["read_num" => $info["read_num"] + 1], $id);
        $info["browse_num"] = $info["read_num"];
        unset($info["read_num"]);
        return \service\JsonService::successful($info);
    }
    public function get_cart_list()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 10;
        $is_pay = input("post.is_pay") ? input("post.is_pay") : 0;
        $info = \app\ebapi\model\store\StoreCart::CartList($uid, $is_pay, $page, $size);
        $infoCount = \app\ebapi\model\store\StoreCart::CartListCount($uid, $is_pay);
        $info[$key]["num_q"] = \app\ebapi\model\column\ColumnText::getCatalogCount($value["id"]);
        $info[$key]["image"] = \app\ebapi\model\column\ColumnText::checkImages($value["image"]);
        $r = db("text_user")->where("aid", 1)->find();
        if (!empty($r)) {
            $info[$key]["test_nickname"] = $r["nickname"];
            $info[$key]["test_avatar"] = $r["avatar"];
            $info[$key]["test_level"] = $r["level"];
            $info[$key]["test_signature"] = $r["signature"];
        }
        return \service\JsonService::successlayui($infoCount, $info, "success", 200);
    }
    public function get_column_payed()
    {
        $columnID = input("post.columnID");
        $uid = input("post.uid");
        $type = input("post.type");
        if (empty($columnID) || empty($uid)) {
            return \service\JsonService::fail("获取购物车参数错误");
        }
        if (empty($type)) {
            $type = "is_zg";
        }
        $getCartData = Db("store_cart")->where("uid", $uid)->where("product_id", $columnID)->where("type", $type)->field("id,uid,product_id,type,is_pay")->find();
        if (!$getCartData) {
            $info["result"] = 0;
        } else {
            if ($getCartData["is_pay"] == 0) {
                $info["result"] = 0;
            } else {
                $info["result"] = 1;
            }
        }
        return \service\JsonService::successful($info);
    }
    public function zg_remove_one_cart()
    {
        $uid = osx_input("uid", 0, "intval");
        $productId = osx_input("productId", 0, "intval");
        if (!$uid) {
            return \service\JsonService::fail("请先登录再操作!");
        }
        if (!$productId) {
            return \service\JsonService::fail("书架传参错误!");
        }
        $res = \app\ebapi\model\store\StoreCart::removeZgUserCartByClick($uid, $productId);
        if ($res) {
            return \service\JsonService::successful("删除成功");
        }
        return \service\JsonService::fail("删除失败!");
    }
    public function zg_remove_cart()
    {
        $uid = osx_input("uid", 0, "intval");
        $ids = osx_input("ids", "", "text");
        $productIds = osx_input("productIds", "", "text");
        if (!$uid) {
            return \service\JsonService::fail("请先登录再操作!");
        }
        if (!$ids || !$productIds) {
            return \service\JsonService::fail("书架传参错误!");
        }
        $res = \app\ebapi\model\store\StoreCart::removeZgUserCart($uid, $ids, $productIds);
        if ($res) {
            return \service\JsonService::successful("删除成功");
        }
        return \service\JsonService::fail("删除失败!");
    }
    public function pre_my_income()
    {
        $cid = osx_input("cid", 0, "intval");
        if (!$cid) {
            return \service\JsonService::fail("传参错误!");
        }
        $get_strip = Db("store_product")->where("id", $cid)->value("strip_num");
        $get_commission_level_one = \app\admin\model\system\SystemConfig::getValue("agent_yongjin_config");
        $pre_my_income = $get_strip * $get_commission_level_one / 100;
        return \service\JsonService::successful($pre_my_income);
    }
    public function is_delete()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $gid = input("post.gid") ? input("post.gid") : 0;
        if (!$gid) {
            return \service\JsonService::fail("参数错误!!");
        }
        $data = db("store_cart")->where("uid", $uid)->where("product_id", $gid)->select();
        return \service\JsonService::successful();
    }
    public function get_book_count()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $infoCount = \app\ebapi\model\store\StoreCart::CartListCount($uid, $is_pay = 5);
        return \service\JsonService::successful($infoCount);
    }
    public function collection()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $product_id = input("post.product_id") ? input("post.product_id") : 0;
        if (!$product_id) {
            return \service\JsonService::fail("参数错误!!");
        }
        $res = \app\ebapi\model\store\StoreProductRelation::where(["uid" => $uid, "product_id" => $product_id, "type" => "collect"])->find();
        if ($res) {
            return \service\JsonService::successful(intval(1));
        }
        return \service\JsonService::successful(intval(0));
    }
    public function user_comment_product(\think\Request $request)
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $group = \service\UtilService::postMore([["uid", $uid], ["pid", 0], ["tid", 0], ["product_id", 0], ["comment", ""], ["pics", ""], ["add_time", time()], ["score", 5]], \think\Request::instance());
        if (!$group["tid"]) {
            return \service\JsonService::fail("缺少参数！");
        }
        if (!$group["product_id"]) {
            return \service\JsonService::fail("缺少参数！！");
        }
        \app\ebapi\model\column\StoreProductTextReply::set($group);
        \app\commonapi\model\Gong::actionadd("fazhishishangpinpingjia", "store_product_text_reply", "uid");
        return \service\JsonService::successful([]);
    }
    public function getRootCommit()
    {
        $gid = input("post.gid") ? input("post.gid") : 0;
        if (!$gid) {
            return \service\JsonService::fail("参数错误!!");
        }
        $info = \app\ebapi\model\store\StoreProduct::getContentProduct($gid, "mer_id");
        $root1 = ["aid" => 1, "nickname" => "", "avatar" => "", "level" => 0, "signature" => ""];
        if (!empty($info)) {
            $r = db("text_user")->where("aid", $info["mer_id"])->find();
            if (!empty($r)) {
                $root1 = $r;
            }
        }
        if (!empty($gid)) {
            $root2 = \app\ebapi\model\store\StoreProductReply::getShopId($gid);
        }
        return \service\JsonService::successful(["composer" => $root1, "reply" => $root2]);
    }
    public function product_reply_count()
    {
        $productId = "";
        if (!$productId) {
            return \service\JsonService::fail("缺少参数");
        }
        return \service\JsonService::successful(\app\ebapi\model\column\StoreProductTextReply::productReplyCount($productId));
    }
    public function product_reply_list()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $tid = input("post.tid") ? input("post.tid") : 0;
        $page = input("post.page") ? input("post.page") : 1;
        $size = input("post.size") ? input("post.size") : 10000;
        if (!$tid) {
            return \service\JsonService::fail("缺少参数");
        }
        $list = \app\ebapi\model\column\StoreProductTextReply::getProductReplyTextList($tid, $page, $size);
        $list[$key]["zan"] = db("store_zan")->where("uid", $uid)->where("pid", $value["id"])->count();
        return \service\JsonService::successlayui(count($list), $list, "success", 200);
    }
    public function zan()
    {
        $token = $this->request->header("access-token");
        $uid = $this->userInfo["uid"];
        $pid = input("post.pid") ? input("post.pid") : 0;
        if (!$pid) {
            return \service\JsonService::fail("缺少参数");
        }
        $res = db("store_zan")->where("uid", $uid)->where("pid", $pid)->find();
        if ($res) {
            db("store_zan")->where("id", $res["id"])->delete();
            db("store_product_text_reply")->where("id", $pid)->setDec("is_zan");
        } else {
            $data = ["uid" => $uid, "pid" => $pid, "time" => time()];
            \app\ebapi\model\column\StoreZan::set($data);
            db("store_product_text_reply")->where("id", $pid)->setInc("is_zan");
        }
        return \service\JsonService::successful();
    }
    public function fahuo()
    {
        $id = input("post.id") ? input("post.id") : 0;
        if (!$id) {
            return \service\JsonService::fail("缺少参数");
        }
        $res = Db("store_order")->where("order_id", (string) $id)->update(["status" => 2]);
        if ($res) {
            $rester = Db("store_order")->where("order_id", (string) $id)->find();
            $list = substr($rester["cart_id"], 1, -1);
            $list_array = explode(",", $list);
            foreach ($list_array as $key => $value) {
                Db("store_cart")->where("id", $value)->update(["is_pay" => 1]);
                $cid = db("store_cart")->where("id", $value)->where("type", "is_zg")->value("product_id");
                if ($rester["is_zg"] == 1 && $cid) {
                    $column = db("column_text")->where("id", $cid)->where("status", 1)->where("is_show", 1)->field("is_column,is_free,sales")->find();
                    if ($column) {
                        $sales = (array) $column["sales"] + 1;
                        db("column_text")->where("id", $cid)->update(["sales" => $sales]);
                        db("column_user_buy")->where("uid", $rester["uid"])->where("pid", $cid)->delete();
                        $map["uid"] = $rester["uid"];
                        $map["pid"] = $cid;
                        $map["is_free"] = $column["is_free"];
                        $map["is_column"] = $column["is_column"];
                        $map["status"] = 1;
                        $map["is_new"] = 0;
                        $map["create_time"] = time();
                        $map["read_time"] = time();
                        db("column_user_buy")->insert($map);
                    }
                }
            }
            return \service\JsonService::successful();
        } else {
            return \service\JsonService::fail("修改失败");
        }
    }
    public function user_token()
    {
        $token = osx_input("token");
        $uid = 0;
        if (!empty($token)) {
            $token_os = db("os_token")->where("token", $token)->value("uid");
            return $uid = $token_os;
        }
        return \service\JsonService::fail("缺少参数");
    }
    public function send_thread_knowledge()
    {
        $uid = $this->userInfo["uid"];
        $type = osx_input("type", 1, "intval");
        if ($uid == 0) {
            return \service\JsonService::fail("缺少用户数据!");
        }
        if (!isset($type)) {
            return \service\JsonService::fail("缺少关键参数!");
        }
        if ($type == 1) {
            $buy_pids = db("column_user_buy")->where("uid", $uid)->where("status", 1)->field("pid")->select();
            $buy_pids = array_column($buy_pids, "pid");
            $data = db("column_text")->where("id", "in", $buy_pids)->where("status", 1)->select();
        } else {
            $collect_pids = db("column_collect")->where("uid", $uid)->where("status", 1)->field("pid")->select();
            $collect_pids = array_column($collect_pids, "pid");
            $data = db("column_text")->where("id", "in", $collect_pids)->where("status", 1)->select();
        }
        return \service\JsonService::successful($data);
    }
}

?>