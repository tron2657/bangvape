<?php


namespace app\admin\controller\knowledge;

class OrderSummary extends \app\admin\controller\AuthController
{
    public function index()
    {
    }
    public function chart_order()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function get_echarts_order()
    {
        $where = \service\UtilService::getMore([["type", ""], ["status", ""], ["data", ""]]);
        $where["is_type"] = 1;
        $info = [];
        $info = \app\admin\model\column\StoreOrder::getEchartsOrderZg($where);
        if (!($value["name"] == "普通订单数量" || $value["name"] == "在线支付金额" || $value["name"] == "余额支付金额" || $value["name"] == "交易额" || $value["name"] == "订单商品数量")) {
            unset($info["badge"][$key]);
        }
        unset($info["legend"][3]);
        unset($info["seriesdata"][3]);
        return \service\JsonService::successful($info);
    }
    public function chart_product()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function get_echarts_product($type = "", $data = "", $is_column = 1)
    {
        if ($is_column === "0" || $is_column === "1") {
            $data = \app\admin\model\column\ColumnText::getColumnSummary($type, $data, $is_column);
        } else {
            $data = \app\admin\model\column\ColumnText::getColumnSummaryMerge($type, $data);
        }
        return \service\JsonService::successful($data);
    }
    public function get_echarts_maxlist($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\column\StoreProduct::getMaxList(compact("data"), 1));
    }
    public function get_echarts_profity($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\column\StoreProduct::ProfityTop10(compact("data"), 1));
    }
    public function getLackList()
    {
        $where = \service\UtilService::getMore([["page", 1], ["limit", 20]]);
        return \service\JsonService::successlayui(\app\admin\model\column\StoreProduct::getLackList($where));
    }
    public function editField($id = "")
    {
        $post = $this->request->post();
        \app\admin\model\column\StoreProduct::beginTrans();
        try {
            \app\admin\model\column\StoreProduct::edit($post, $id);
            \app\admin\model\column\StoreProduct::commitTrans();
            return \service\JsonService::successful("修改成功");
        } catch (\Exception $e) {
            \app\admin\model\column\StoreProduct::rollbackTrans();
            return \service\JsonService::fail($e->getMessage());
        }
    }
    public function getnegativelist()
    {
        $where = \service\UtilService::getMore([["page", 1], ["limit", 10]]);
        return \service\JsonService::successful(\app\admin\model\column\ColumnText::getnegativelist($where));
    }
    public function getTuiPriesList()
    {
        return \service\JsonService::successful(\app\admin\model\column\StoreProduct::TuiProductList());
    }
    public function chart_score()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getScoreBadgeList($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getScoreBadgeList(compact("data")));
    }
    public function getScoreCurve($data = "", $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getScoreCurve(compact("data", "limit")));
    }
    public function chart_coupon()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getCouponBadgeList($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\column\ColumnCouponUser::getCouponColumnList(compact("data")));
    }
    public function getConponCurve($data = "")
    {
        $info = \app\admin\model\column\ColumnCouponUser::getConponColumn(compact("data"));
        return \service\JsonService::successful($info);
    }
    public function chart_combination()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function chart_bargain()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function chart_seckill()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function chart_rebate()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getUserBillBrokerage($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getUserBillChart(compact("data")));
    }
    public function getRebateBadge($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getRebateBadge(compact("data")));
    }
    public function getFanList($page = 1, $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getFanList(compact("page", "limit")));
    }
    public function getFanCount()
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getFanCount());
    }
    public function chart_recharge()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getEchartsRecharge($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserBill::getEchartsRecharge(compact("data")));
    }
    public function chart_cash()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getExtractHead($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::getExtractHead(compact("data")));
    }
    public function user_chart()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getBadgeList($data = "", $is_promoter = "", $status = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getBadgeList(compact("data", "is_promoter", "status")));
    }
    public function getUserChartList($data = "", $is_promoter = "", $status = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getUserChartList(compact("data", "is_promoter", "status")));
    }
    public function getExtractData($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::getExtractList(compact("data")));
    }
    public function user_distribution_chart()
    {
        $limit = 10;
        $top10list = \app\admin\model\user\User::getUserDistributionTop10List($limit);
        $this->assign(["is_layui" => true, "limit" => $limit, "year" => getMonth("y"), "commissionList" => $top10list["commission"], "extractList" => $top10list["extract"]]);
        return $this->fetch();
    }
    public function getDistributionBadgeList($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getDistributionBadgeList(compact("data")));
    }
    public function getUserDistributionChart($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getUserDistributionChart(compact("data")));
    }
    public function user_business_chart()
    {
        $limit = 10;
        $top10list = \app\admin\model\user\User::getUserTop10List($limit);
        $this->assign(["is_layui" => true, "limit" => $limit, "year" => getMonth("y"), "integralList" => $top10list["integral"], "moneyList" => $top10list["now_money"], "shopcountList" => $top10list["shopcount"], "orderList" => $top10list["order"], "lastorderList" => $top10list["lastorder"]]);
        return $this->fetch();
    }
    public function getUserBusinessChart($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getUserBusinessChart(compact("data")));
    }
    public function getUserBusinesHeade($data)
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getUserBusinesHeade(compact("data")));
    }
    public function user_attr()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getEchartsData($data = "")
    {
        return \service\JsonService::successful(\app\admin\model\user\User::getEchartsData(compact("data")));
    }
    public function ranking_saleslists()
    {
        $is_free = osx_input("type", 1);
        $this->assign(["is_layui" => true]);
        $this->assign("is_free", $is_free);
        return $this->fetch();
    }
    public function getSaleslists()
    {
        $where = \service\UtilService::getMore([["start_time", ""], ["end_time", ""], ["title", ""], ["page", 1], ["limit", 20], ["is_column", ""]]);
        $is_free = osx_input("is_free", 1);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnText::getSaleslists($where, $is_free));
    }
    public function save_product_export()
    {
        $where = \service\UtilService::getMore([["start_time", ""], ["end_time", ""], ["title", ""], ["is_type", 1]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnText::SaveProductExport($where));
    }
    public function product_info($id = "")
    {
        $is_free = osx_input("is_free", "1");
        if ($id == "") {
            $this->failed("缺少商品id");
        }
        if (!\app\admin\model\column\ColumnText::be(["id" => $id])) {
            return $this->failed("商品不存在!");
        }
        $this->assign(["is_layui" => true, "year" => getMonth("y"), "id" => $id, "is_free" => $is_free]);
        return $this->fetch();
    }
    public function getProductBadgeList($id = "", $data = "")
    {
        return \service\JsonService::successful(\app\admin\model\column\ColumnText::getProductBadgeList($id, $data));
    }
    public function getProductCurve($id = "", $data = "", $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\column\ColumnText::getProductCurve(compact("id", "data", "limit")));
    }
    public function getProductCount($id, $data = "")
    {
        return \service\JsonService::successful(\app\admin\model\column\ColumnText::setWhere(compact("data"))->where("a.product_id", $id)->where("a.is_pay", 1)->count());
    }
    public function getSalelList($data = "", $id = 0, $page = 1, $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\column\ColumnText::getSalelList(compact("data", "id", "page", "limit")));
    }
    public function ranking_commission()
    {
        $this->assign(["is_layui" => true, "year" => getMonth("y")]);
        return $this->fetch();
    }
    public function getcommissionlist($page = 1, $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::where("status", 1)->field(["real_name", "extract_price", "balance"])->order("extract_price desc")->page($page, $limit)->select());
    }
    public function getmonthcommissionlist($page = 1, $limit = 20)
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::where("status", 1)->whereTime("add_time", "month")->field(["real_name", "extract_price", "balance"])->order("extract_price desc")->page($page, $limit)->select());
    }
    public function getCommissonCount()
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::where("status", 1)->count());
    }
    public function getMonthCommissonCount()
    {
        return \service\JsonService::successful(\app\admin\model\user\UserExtract::where("status", 1)->whereTime("add_time", "month")->count());
    }
    public function getMonthPountCount()
    {
        return \service\JsonService::successful(\app\admin\model\user\User::where("status", 1)->where("integral", "neq", 0)->whereTime("add_time", "month")->count());
    }
    public function ranking_lower()
    {
        echo " 复购率 复购增长率 活跃度 活跃率 分销总金额 增长率 消费会员 非消费会员 消费排行榜 积分排行榜 余额排行榜 分销总金额排行榜 分销人数排行榜 分销余额排行榜 购物金额排行榜 购物次数排行榜 提现排行榜 ";
    }
}

?>