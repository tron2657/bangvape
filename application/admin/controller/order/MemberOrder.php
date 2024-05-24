<?php

namespace app\admin\controller\order;


use service\JsonService as Json;
 
use app\admin\model\order\StoreOrder as StoreOrderModel;
use service\UtilService;

/**
 * 订单管理控制器 同一个订单表放在一个控制器
 * Class StoreOrder
 * @package app\admin\controller\store
 */
class MemberOrder extends StoreOrder
{
      /**会员订单
     * @return mixed
     */
    public function index()
    {
        $this->assign([
            'year' => getMonth('y'),
            'real_name' => $this->request->get('real_name', ''),
            'orderCount' => StoreOrderModel::orderCount(1),
        ]);
        return $this->fetch();
    }

    
    /**
     * 获取订单列表
     * return json
     */
    public function order_list()
    {
        $where = UtilService::getMore([
            ['status', ''],
            ['real_name', $this->request->param('real_name', '')],
            ['is_del', 0],
            ['data', ''],
            ['type','11'],
            ['types',$this->request->param('types', 0)],
            ['order', ''],
            ['spread_type', ''],
            ['page', 1],
            ['limit', 20],
            ['excel', 0],
            ['is_zg',0]
        ]);
        return Json::successlayui(StoreOrderModel::OrderList($where));
    }
}
