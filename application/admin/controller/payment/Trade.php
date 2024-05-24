<?php

namespace app\admin\controller\payment;

use app\admin\controller\AuthController;
use app\admin\model\payment\UserOrder;
use service\UtilService as Util;
use service\JsonService as Json;
use app\admin\model\com\VisitAudit as ForumAudit;
/**
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class Trade extends AuthController
{
    public function index(){
        $uid=osx_input('uid',0,'intval');
        $this->assign([
            'year' => getMonth('y'),
            'order' => $this->request->get('order', ''),
            'info' => $this->request->get('info', ''),
            'orderType'=>UserOrder::$orderType,
            'uid'=>$uid,
        ]);
       return $this->fetch();
    }

    /**
     * 订单列表
     */
    public function get_trade_list(){
        $pam= Util::getMore([
            ['page',1],
            ['limit', 10],
            ['order',''],
            ['info',0],
            ['data',0],
            ['pay_type',0],
            ['status',''],
            ['order_type',''],
            ['uid',0]
        ]);
        $map['id']=['gt',0];
        if($pam['order']){
            $map['order_id']=['like','%'.$pam['order'].'%'];
        }
        if($pam['info']){
            $map['info']=['like','%'.$pam['info'].'%'];
        }
        if($pam['data']){
            $map['create_time']=ForumAudit::timeRange($pam['data']);
        }
        if($pam['pay_type']){
            if ($pam['pay_type'] == 'wechat') {
                $map['pay_type'] = ['in', ['wechat','weixin','weixin_app']];
            } else {
                $map['pay_type'] = $pam['pay_type'];
            }
        }
        if($pam['status']!==''){
            $map['status']=$pam['status'];
        }
        if(intval($pam['uid'])!==0){
            $map['uid']=$pam['uid'];
        }

        if($pam['order_type']!=='')
        {
            $map['order_type']=$pam['order_type'];
        }

        return Json::successlayui(UserOrder::get_user_order_list($map,$pam['page'],$pam['limit'],'create_time desc'));
    }
}