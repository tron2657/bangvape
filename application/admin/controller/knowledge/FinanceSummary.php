<?php


namespace app\admin\controller\knowledge;

class FinanceSummary extends \app\admin\controller\AuthController
{
    public function index()
    {
        $where = \service\UtilService::getMore([["date", ""], ["export", ""], ["data", ""]], $this->request);
        $where["date"] = $this->request->param("date");
        $where["data"] = $this->request->param("data");
        $where["export"] = $this->request->param("export");
        $where["is_type"] = 1;
        $trans = \app\admin\model\record\StoreStatistics::trans($is_type = 1);
        $ordinary = \app\admin\model\record\StoreStatistics::getOrdinary($where);
        $extension = \app\admin\model\record\StoreStatistics::getExtension($where);
        $orderCount = [urlencode("微信支付") => \app\admin\model\record\StoreStatistics::getTimeWhere($where, \app\admin\model\record\StoreStatistics::statusByWhere("weixin"))->count(), urlencode("余额支付") => \app\admin\model\record\StoreStatistics::getTimeWhere($where, \app\admin\model\record\StoreStatistics::statusByWhere("yue"))->count(), urlencode("线下支付") => \app\admin\model\record\StoreStatistics::getTimeWhere($where, \app\admin\model\record\StoreStatistics::statusByWhere("offline"))->count()];
        $Statistic = [["name" => "营业额", "type" => "line", "data" => []], ["name" => "支出", "type" => "line", "data" => []], ["name" => "盈利", "type" => "line", "data" => []]];
        $orderinfos = \app\admin\model\record\StoreStatistics::getOrderInfo($where);
        $orderinfo = $orderinfos["orderinfo"];
        $orderDays = [];
        if (empty($orderinfo)) {
            $orderDays[] = date("Y-m-d", time());
            $Statistic[0]["data"][] = 0;
            $Statistic[1]["data"][] = 0;
            $Statistic[2]["data"][] = 0;
        }
        foreach ($orderinfo as $info) {
            $orderDays[] = $info["pay_time"];
            $Statistic[0]["data"][] = $info["total_price"] + $info["pay_postage"];
            $Statistic[1]["data"][] = $info["coupon_price"] + $info["deduction_price"] + $info["cost"];
            $Statistic[2]["data"][] = $info["total_price"] + $info["pay_postage"] - ($info["coupon_price"] + $info["deduction_price"] + $info["cost"]);
        }
        $price = $orderinfos["price"] + $orderinfos["postage"];
        $cost = $orderinfos["deduction"] + $orderinfos["coupon"] + $orderinfos["cost"];
        $Consumption = \app\admin\model\record\StoreStatistics::getConsumption($where)["number"];
        $header = [["name" => "总营业额", "class" => "fa-line-chart", "value" => "￥" . $price, "color" => "red"], ["name" => "总支出", "class" => "fa-area-chart", "value" => "￥" . ($cost + $extension), "color" => "lazur"], ["name" => "总盈利", "class" => "fa-bar-chart", "value" => "￥" . bcsub($price, $cost, 0), "color" => "navy"], ["name" => "新增消费", "class" => "fa-pie-chart", "value" => "￥" . ($Consumption == 0 ? 0 : $Consumption), "color" => "yellow"]];
        $data = [["value" => $orderinfos["cost"], "name" => "商品成本"], ["value" => $orderinfos["coupon"], "name" => "优惠券抵扣"]];
        $this->assign(\app\admin\model\record\StoreStatistics::systemTable($where));
        $this->assign(compact("where", "trans", "orderCount", "orderPrice", "orderDays", "header", "Statistic", "ordinary", "data"));
        $this->assign("price", \app\admin\model\record\StoreStatistics::getOrderPrice($where));
        return $this->fetch();
    }
}

?>