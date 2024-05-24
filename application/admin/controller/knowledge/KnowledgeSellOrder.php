<?php


namespace app\admin\controller\knowledge;

class KnowledgeSellOrder extends \app\admin\controller\AuthController
{
    public function index()
    {
        $this->assign(["year" => getMonth("y")]);
        return $this->fetch();
    }
    public function order_list()
    {
        $where = \service\UtilService::getMore([["page", 1], ["limit", 20], ["select_date", ""], ["order_status", ""], ["back_status", ""], ["keywords_type", "order_id"], ["keywords", ""], ["excel", 0]]);
        return \service\JsonService::successlayui(\app\shareapi\model\SellOrder::getKnowledgeOrderSellListPageAdmin($where));
    }
}

?>