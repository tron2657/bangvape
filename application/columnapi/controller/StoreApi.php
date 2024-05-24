<?php


namespace app\columnapi\controller;

class StoreApi extends AuthController
{
    public static function whiteList()
    {
        return ["goods_search", "get_routine_hot_search", "get_pid_cate", "get_product_category", "get_product_list", "details", "product_reply_list", "product_reply_count", "product_reply_list_zg"];
    }
    public function goods_search()
    {
        list($keyword) = \service\UtilService::getMore([["keyword", 0]], NULL, true);
        return \service\JsonService::successful(\app\ebapi\model\store\StoreProduct::getSearchStorePage($keyword));
    }
    public function store1(Request $request)
    {
        $data = \service\UtilService::postMore([["keyword", ""], ["cid", ""], ["sid", ""]], $request);
        $keyword = addslashes($data["keyword"]);
        $cid = intval($data["cid"]);
        $sid = intval($data["sid"]);
        $category = NULL;
        if ($sid) {
            $category = \app\ebapi\model\store\StoreCategory::get($sid);
        }
        if ($cid && !$category) {
            $category = \app\ebapi\model\store\StoreCategory::get($cid);
        }
        $data["keyword"] = $keyword;
        $data["cid"] = $cid;
        $data["sid"] = $sid;
        return \service\JsonService::successful($data);
    }
    public function get_pid_cate()
    {
        $data = \app\ebapi\model\store\StoreCategory::pidByCategory(0, "id,cate_name");
        return \service\JsonService::successful($data);
    }
    public function get_id_cate(Request $request)
    {
        $data = \service\UtilService::postMore([["id", 0]], $request);
        $dataCateA = [];
        $dataCateA[0]["id"] = $data["id"];
        $dataCateA[0]["cate_name"] = "全部商品";
        $dataCateA[0]["pid"] = 0;
        $dataCateE = \app\ebapi\model\store\StoreCategory::pidBySidList($data["id"]);
        if ($dataCateE) {
            $dataCateE = $dataCateE->toArray();
        }
        $dataCate = [];
        $dataCate = array_merge_recursive($dataCateA, $dataCateE);
        return \service\JsonService::successful($dataCate);
    }
    public function get_product_list()
    {
        $data = \service\UtilService::getMore([["sid", 0], ["cid", 0], ["keyword", ""], ["priceOrder", ""], ["salesOrder", ""], ["news", 0], ["first", 0], ["limit", 0], ["recommend_sell", 0]], $this->request);
        $access = $this->access;
        return \service\JsonService::successful(\app\ebapi\model\store\StoreProduct::getProductList($data, $this->uid, $access));
    }
    public function details()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id || !($storeInfo = \app\ebapi\model\store\StoreProduct::getValidProduct($id))) {
            return \service\JsonService::fail("商品不存在或已下架");
        }
        if ($storeInfo == "该商品已下架！") {
            return \service\JsonService::fail("商品不存在或已下架");
        }
        $storeInfo["userCollect"] = \app\ebapi\model\store\StoreProductRelation::isProductRelation($id, $this->userInfo["uid"], "collect");
        list($productAttr, $productValue) = \app\ebapi\model\store\StoreProductAttr::getProductAttrDetail($id);
        setView($this->userInfo["uid"], $id, $storeInfo["cate_id"], "viwe");
        $data["storeInfo"] = \app\ebapi\model\store\StoreProduct::setLevelPrice($storeInfo, $this->uid, true);
        $data["similarity"] = \app\ebapi\model\store\StoreProduct::cateIdBySimilarityProduct($storeInfo["cate_id"], "id,store_name,image,price,sales,ficti", 4);
        $data["productAttr"] = $productAttr;
        $data["productValue"] = $productValue;
        $data["priceName"] = \app\ebapi\model\store\StoreProduct::getPacketPrice($storeInfo, $productValue);
        $data["reply"] = \app\ebapi\model\store\StoreProductReply::getRecProductReply($storeInfo["id"]);
        $data["replyCount"] = \app\ebapi\model\store\StoreProductReply::productValidWhere()->where("product_id", $storeInfo["id"])->count();
        if ($data["replyCount"]) {
            $goodReply = \app\ebapi\model\store\StoreProductReply::productValidWhere()->where("product_id", $storeInfo["id"])->where("product_score", 5)->count();
            $data["replyChance"] = bcdiv($goodReply, $data["replyCount"], 2);
            $data["replyChance"] = bcmul($data["replyChance"], 100, 3);
        } else {
            $data["replyChance"] = 0;
        }
        $data["mer_id"] = \app\ebapi\model\store\StoreProduct::where("id", $storeInfo["id"])->value("mer_id");
        if ($_SERVER["REQUEST_METHOD"] != "OPTIONS") {
            \app\ebapi\model\store\StoreProduct::setInc_bow($id);
        }
        return \service\JsonService::successful($data);
    }
    public function get_product_collect()
    {
        $product_id = osx_input("product_id", 0, "intval");
        return \service\JsonService::successful(["userCollect" => \app\ebapi\model\store\StoreProductRelation::isProductRelation($product_id, $this->userInfo["uid"], "collect")]);
    }
    public function get_product_reply()
    {
        $productId = osx_input("productId", 0, "intval");
        if (!$productId) {
            return \service\JsonService::fail("参数错误");
        }
        $replyCount = \app\columnapi\model\column\ColumnProductReply::productValidWhere()->where("product_id", $productId)->count();
        $reply = \app\columnapi\model\column\ColumnProductReply::getRecProductReply($productId);
        return \service\JsonService::successful(["replyCount" => $replyCount, "reply" => $reply]);
    }
    public function like_product()
    {
        $productId = osx_input("productId", 0, "intval");
        $category = osx_input("category", "product", "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\ebapi\model\store\StoreProductRelation::productRelation($productId, $this->userInfo["uid"], "like", $category);
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful();
    }
    public function unlike_product()
    {
        $productId = osx_input("productId", 0, "intval");
        $category = osx_input("category", "product", "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\ebapi\model\store\StoreProductRelation::unProductRelation($productId, $this->userInfo["uid"], "like", $category);
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful();
    }
    public function collect_product()
    {
        $productId = osx_input("productId", 0, "intval");
        $category = osx_input("category", "product", "text");
        $is_zg = osx_input("is_zg", 0, "intval");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\ebapi\model\store\StoreProductRelation::productRelation($productId, $this->userInfo["uid"], "collect", $category, $is_zg);
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful();
    }
    public function collect_product_all()
    {
        $productId = osx_input("productId", 0, "intval");
        $category = osx_input("category", "product", "text");
        $is_zg = osx_input("is_zg", 0, "intval");
        if ($productId == "") {
            return \service\JsonService::fail("参数错误");
        }
        $productIdS = explode(",", $productId);
        $res = \app\ebapi\model\store\StoreProductRelation::productRelationAll($productIdS, $this->userInfo["uid"], "collect", $category, $is_zg);
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful("收藏成功");
    }
    public function uncollect_product()
    {
        $productId = osx_input("productId", 0, "intval");
        $category = osx_input("category", "product", "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\ebapi\model\store\StoreProductRelation::unProductRelation($productId, $this->userInfo["uid"], "collect", $category);
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful();
    }
    public function get_user_collect_product()
    {
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $list = \app\ebapi\model\store\StoreProductRelation::getUserCollectProduct($this->uid, $page, $limit);
        $list = array_values($list);
        return \service\JsonService::successful($list);
    }
    public function get_user_collect_product_del()
    {
        $pid = osx_input("pid", 0, "intval");
        if ($pid) {
            $list = \app\ebapi\model\store\StoreProductRelation::where("uid", $this->userInfo["uid"])->where("product_id", $pid)->delete();
            return \service\JsonService::successful($list);
        }
        return \service\JsonService::fail("缺少参数");
    }
    public function get_order_product()
    {
        $unique = osx_input("unique", "", "text");
        if (!$unique || !\app\ebapi\model\store\StoreOrderCartInfo::be(["unique" => $unique]) || !($cartInfo = \app\ebapi\model\store\StoreOrderCartInfo::where("unique", $unique)->find())) {
            return \service\JsonService::fail("评价产品不存在!");
        }
        return \service\JsonService::successful($cartInfo);
    }
    public function get_product_category()
    {
        return \service\JsonService::successful(\app\ebapi\model\store\StoreCategory::getProductCategory());
    }
    public function product_reply_list()
    {
        $productId = osx_input("productId", "", "text");
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $type = osx_input("type", 0, "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误!");
        }
        $list = \app\columnapi\model\column\ColumnProductReply::getProductReplyList($productId, (array) $type, $page, $limit);
        return \service\JsonService::successful($list);
    }
    public function product_reply_list_zg()
    {
        $productId = osx_input("productId", "", "text");
        $page = osx_input("page", 0, "intval");
        $limit = osx_input("limit", 8, "intval");
        $type = osx_input("type", 0, "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误!");
        }
        $list = \app\columnapi\model\column\ColumnProductReply::getProductReplyLists($productId, (array) $type, $page, $limit);
        return \service\JsonService::successful($list);
    }
    public function product_reply_count()
    {
        $productId = osx_input("productId", "", "text");
        if (!$productId) {
            return \service\JsonService::fail("缺少参数");
        }
        return \service\JsonService::successful(\app\columnapi\model\column\ColumnProductReply::productReplyCount($productId));
    }
    public function product_reply_count_zg()
    {
        $productId = osx_input("productId", "", "text");
        if (!$productId) {
            return \service\JsonService::fail("缺少参数");
        }
        return \service\JsonService::successful(\app\columnapi\model\column\ColumnProductReply::productReplyCounts($productId));
    }
    public function product_attr_detail()
    {
        $productId = osx_input("productId", "", "text");
        if (!$productId || !is_numeric($productId)) {
            return \service\JsonService::fail("参数错误!");
        }
        list($productAttr, $productValue) = \app\ebapi\model\store\StoreProductAttr::getProductAttrDetail($productId);
        return \service\JsonService::successful(compact("productAttr", "productValue"));
    }
    public function poster($id = 0)
    {
    }
    public function product_promotion_code()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return \service\JsonService::fail("参数错误ID不存在");
        }
        $count = \app\ebapi\model\store\StoreProduct::validWhere()->count();
        if (!$count) {
            return \service\JsonService::fail("参数错误");
        }
        $path = makePathToUrl("routine/codepath/product/", 4);
        if ($path == "") {
            return \service\JsonService::fail("生成上传目录失败,请检查权限!");
        }
        $codePath = $path . $id . "_" . $this->userInfo["uid"] . "_product.jpg";
        $domain = \app\core\util\SystemConfigService::get("site_url") . "/";
        if (!file_exists($codePath)) {
            if (!is_dir($path)) {
                mkdir($path, 511, true);
            }
            $res = \app\core\model\routine\RoutineCode::getPageCode("pages/goods_details/index", "id=" . $id . ($this->userInfo["is_promoter"] ? "&pid=" . $this->uid : ""), 280);
            if ($res) {
                file_put_contents($codePath, $res);
            } else {
                return \service\JsonService::fail("二维码生成失败");
            }
        }
        return \service\JsonService::successful($domain . $codePath);
    }
    public function get_routine_hot_search()
    {
        $routineHotSearch = \app\core\util\GroupDataService::getData("routine_hot_search") ?: [];
        return \service\JsonService::successful($routineHotSearch);
    }
    public function getViewList()
    {
        $page = osx_input("page", 1, "intval");
        $view = getViewList($this->userInfo["uid"], $page);
        $vo["store_data"] = \app\ebapi\model\store\StoreProduct::getValidProduct($vo["product_id"]);
        if ($vo["store_data"] == "该商品已下架！") {
            unset($view["data"][$key]);
            $view["count"]--;
        }
        $vo["userCollect"] = \app\ebapi\model\store\StoreProductRelation::isProductRelation($vo["product_id"], $this->userInfo["uid"], "collect");
        unset($key);
        unset($vo);
        $view["data"] = array_values($view["data"]);
        if ($view) {
            \service\JsonService::successful($view);
        } else {
            return \service\JsonService::fail("未查询到数据");
        }
    }
    public function ViewDelete()
    {
        $views = osx_input("views", "", "text");
        if ($views == "") {
            return \service\JsonService::fail("参数错误");
        }
        $viewIds = explode(",", $views);
        $res = deleteView($viewIds);
        if (!$res) {
            return \service\JsonService::fail("删除失败");
        }
        return \service\JsonService::successful("删除成功");
    }
    public function collectDelete($productId = "")
    {
        $productId = osx_input("productId", "", "text");
        if ($productId == "") {
            return \service\JsonService::fail("参数错误");
        }
        $productIdS = explode(",", $productId);
        $res = false;
        foreach ($productIdS as $vo) {
            $res = \app\ebapi\model\store\StoreProductRelation::unProductRelation($vo, $this->userInfo["uid"], "collect", "product");
        }
        if (!$res) {
            return \service\JsonService::fail(\app\ebapi\model\store\StoreProductRelation::getErrorInfo());
        }
        return \service\JsonService::successful("取消收藏成功");
    }
}

?>