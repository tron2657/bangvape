<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/26
 */

namespace behavior\wechat;


use app\admin\model\wechat\WechatMessage;
use app\ebapi\model\store\StoreOrder as StoreOrderRoutineModel;
use app\wap\model\store\StoreOrder as StoreOrderWapModel;
use app\shopapi\model\shop\ShopOrder as ShopOrderModel;
use app\wap\model\user\UserRecharge;
use service\HookService;
use service\WechatService;
use service\MiniProgramService;

class PaymentBehavior
{

    /**
     * 公众号下单成功之后
     * @param $order
     * @param $prepay_id
     */
    public static function wechatPaymentPrepare($order, $prepay_id)
    {

    }
    /**
     * 小程序下单成功之后
     * @param $order
     * @param $prepay_id
     */
    public static function wechatPaymentPrepareProgram($order, $prepay_id)
    {

    }

    /**
     * 支付成功后
     * @param $notify
     * @return bool|mixed
     */
    public static function wechatPaySuccess($notify)
    {
        if(isset($notify->attach) && $notify->attach){
            return HookService::listen('wechat_pay_success_'.strtolower($notify->attach),$notify->out_trade_no,$notify,true,self::class);
        }
        return false;
    }

    /**
     * 商品订单支付成功后  微信公众号
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function wechatPaySuccessProduct($orderId, $notify)
    {
        try{
            if(StoreOrderWapModel::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return StoreOrderWapModel::paySuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 商品订单支付成功后  APP
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function wechatPaySuccessProductApp($orderId, $notify)
    {
        try{
            if(StoreOrderWapModel::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return StoreOrderWapModel::paySuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    public static function wechatPaySuccessProductShop($orderId, $notify)
    {
        try{
            if(ShopOrderModel::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return ShopOrderModel::paySuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 商品订单支付成功后  小程序
     *
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function wechatPaySuccessProductr($orderId, $notify)
    {
        try{
            if(StoreOrderRoutineModel::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return StoreOrderRoutineModel::paySuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 用户充值成功后
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function wechatPaySuccessUserRecharge($orderId, $notify)
    {
        try{
            if(UserRecharge::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return UserRecharge::rechargeSuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 使用余额支付订单时
     * @param $userInfo
     * @param $orderInfo
     */
    public static function yuePayProduct($userInfo, $orderInfo)
    {


    }

    /**
     * 会员订单支付成功后  微信公众号 支付宝
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function wechatPaySuccessMember($orderId, $notify)
    {
        try{
            if(StoreOrderWapModel::be(['order_id'=>$orderId,'paid'=>1])) return true;
            return StoreOrderWapModel::payMeSuccess($orderId);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 微信支付订单退款
     * @param $orderNo
     * @param array $opt
     */
    public static function wechatPayOrderRefund($orderNo, array $opt)
    {
        WechatService::payOrderRefund($orderNo,$opt,'weixin');
    }

    /**
     * 小程序支付订单退款
     * @param $orderNo
     * @param array $opt
     */
    public static function routinePayOrderRefund($orderNo, array $opt)
    {
        $refundDesc = isset($opt['desc']) ? $opt['desc'] : '';
        $res = MiniProgramService::payOrderRefund($orderNo,$opt,'routine');//2.5.36
//        $res = RoutineRefund::doRefund($opt['pay_price'],$opt['refund_price'],$orderNo,'',$orderNo,$refundDesc);
    }

    /**
     * 微信支付充值退款
     * @param $orderNo
     * @param array $opt
     */

    public static function userRechargeRefund($orderNo, array $opt)
    {
        WechatService::payOrderRefund($orderNo,$opt);
    }
}