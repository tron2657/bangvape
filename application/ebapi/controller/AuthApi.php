<?php
namespace app\ebapi\controller;

use app\admin\model\user\MemberCouponPlan;
use app\core\model\routine\RoutineFormId;//待完善
use app\core\model\UserLevel;
use service\JsonService;
use app\core\util\SystemConfigService;
use service\UtilService;
use think\Request;
use app\core\behavior\GoodsBehavior;//待完善
use app\ebapi\model\store\StoreCouponUser;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreProductAttrValue;
use app\ebapi\model\store\StoreCart;
use app\ebapi\model\user\User;
use app\ebapi\model\store\StorePink;
use app\ebapi\model\store\StoreBargainUser;
use app\ebapi\model\store\StoreBargainUserHelp;
use app\admin\model\system\SystemConfig;
use service\WechatTemplateService;
use app\ebapi\model\user\WechatUser;
use app\core\util\WechatService;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;
use app\commonapi\model\Gong;
use app\ebapi\model\store\StoreCoupon;
use app\osapi\model\event\Event;
use Exception;

/**
 * 小程序 购物车,新增订单等 api接口
 * Class AuthApi
 * @package app\routine\controller
 *
 */
class AuthApi extends AuthController
{

    public static function whiteList()
    {
        return [
            'time_out_order',
            'user_message_order'
        ];
    }

    /**
     * 购物车
     * @return \think\response\Json
     */
    public function get_cart_list()
    {
        $result=StoreCart::getUserProductCartList($this->userInfo['uid'],'',0,'product',$this->isVip);
        $result['isVip']=$this->isVip;
        return JsonService::successful($result);
    }


    /*
     * 获取订单支付状态
     * @param string ordre_id 订单id
     * @return json
     * */
    public function get_order_pay_info()
    {
        $order_id=osx_input('order_id','','text');//订单id
        if ($order_id == '') return JsonService::fail('缺少参数');
        return JsonService::successful(StoreOrder::tidyOrder(StoreOrder::where('order_id', $order_id)->find()));
    }
    /**
     * 订单页面
     * @param Request $request
     * @return \think\response\Json
     */
    public function confirm_order(Request $request)
    {
        $data = UtilService::postMore(['cartId'], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'product',$this->isVip);
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];
        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        $other = [
            'offlinePostage' => SystemConfigService::get('offline_postage'),
            'integralRatio' => SystemConfigService::get('integral_ratio')
        ];
        $usableCoupon = StoreCouponUser::beUsableCoupon($this->userInfo['uid'], $priceGroup['totalPrice']);
        $cartIdA = explode(',', $cartId);
        if (count($cartIdA) > 1) $seckill_id = 0;
        else {
            $seckillinfo = StoreCart::where('id', $cartId)->find();
            if ((int)$seckillinfo['seckill_id'] > 0) $seckill_id = $seckillinfo['seckill_id'];
            else $seckill_id = 0;
        }
        $data['usableCoupon'] = $usableCoupon;
        $data['seckill_id'] = $seckill_id;
        $data['cartInfo'] = $cartInfo;
        $data['priceGroup'] = $priceGroup;
        $data['orderKey'] = StoreOrder::cacheOrderInfo($this->userInfo['uid'], $cartInfo, $priceGroup, $other);
        $data['offlinePostage'] = $other['offlinePostage'];
        $vipId=UserLevel::getUserLevel($this->uid);
        $this->userInfo['vip']=$vipId !==false ? true : false;
        if($this->userInfo['vip']){
            $this->userInfo['vip_id']=$vipId;
            $this->userInfo['discount']=UserLevel::getUserLevelInfo($vipId,'discount');
        }
        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        $data['isVip']=$this->isVip;
        $data['vip_discount']=SystemConfigService::get('membership_vip_discount');
       
        $data['user_score']= StoreOrder::get_user_order_score($this->uid,$this->isVip,$priceGroup);
        return JsonService::successful($data);
    }


    public function get_user_score(){
    

        $cartId=osx_input('cart_id','','text');
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        $couponId=osx_input('coupon_Id',0,'intval');
 
        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'product',$this->isVip);
        $cartInfo = $cartGroup['valid'];
     
        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
     
        $res=StoreOrder::get_user_order_score($this->uid,$this->isVip,$priceGroup,$couponId);
        return JsonService::successful($res);
    }

    /**
     * [cacheorder description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function cacheorder(Request $request)
    {
        $data = UtilService::postMore(['cartId'], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        $cartGroup = StoreCart::getUserZgCartList($this->userInfo['uid'], $cartId, 1);
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        // return JsonService::fail([]);
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];
        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        $other = [
            'offlinePostage' => SystemConfigService::get('offline_postage'),
            'integralRatio' => SystemConfigService::get('integral_ratio')
        ];
        $usableCoupon = StoreCouponUser::beUsableCoupon($this->userInfo['uid'], $priceGroup['totalPrice']);
        $cartIdA = explode(',', $cartId);
        if (count($cartIdA) > 1) $seckill_id = 0;
        else {
            $seckillinfo = StoreCart::where('id', $cartId)->find();
            if ((int)$seckillinfo['seckill_id'] > 0) $seckill_id = $seckillinfo['seckill_id'];
            else $seckill_id = 0;
        }
        $data['usableCoupon'] = $usableCoupon;
        $data['seckill_id'] = $seckill_id;
        $data['cartInfo'] = $cartInfo;
        $data['priceGroup'] = $priceGroup;
        $data['orderKey'] = StoreOrder::cacheOrderInfo($this->userInfo['uid'], $cartInfo, $priceGroup, $other);
        $data['offlinePostage'] = $other['offlinePostage'];
        $vipId=UserLevel::getUserLevel($this->uid);
        $this->userInfo['vip']=$vipId !==false ? true : false;
        if($this->userInfo['vip']){
            $this->userInfo['vip_id']=$vipId;
            $this->userInfo['discount']=UserLevel::getUserLevelInfo($vipId,'discount');
        }
        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        return JsonService::successful($data);
    }

    /*
     * 获取小程序订单列表统计数据
     *
     * */
    public function get_order_data()
    {
        return JsonService::successful(StoreOrder::getOrderData($this->uid));
    }
    /**
     * 过度查$uniqueId
     * @param string $productId
     * @param int $cartNum
     * @param string $uniqueId
     * @return \think\response\Json
     */
    public function unique()
    {
        $productId = $_GET['productId'];
        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        $uniqueId = StoreProductAttrValue::where('product_id', $productId)->value('unique');
        $data = $this->set_cart($productId, $cartNum = 1, $uniqueId);
        if ($data == true) {
            return JsonService::successful('ok');
        }
    }
    /**
     * 加入到购物车
     * @return \think\response\Json
     */
    public function set_cart()
    {
        $productId=osx_input('productId','','text');
        $cartNum=osx_input('cartNum',1,'text');
        $uniqueId=osx_input('uniqueId','','text');
        $type=osx_input('type','product','text');
        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        $cart_limit=SystemConfig::getValue('cart_limit');
        if($cart_limit<$cartNum) return JsonService::fail('加入购物车数量最高限制为'.$cart_limit.'件');
        $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum, $uniqueId, $type,'','','','','add_to_cart');
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else return JsonService::successful('ok', ['cartId' => $res->id]);
    }


    /**
     * 立即购买
     */
    public function now_buy()
    {
        $productId=osx_input('productId','','text');
        $cartNum=osx_input('cartNum',1,'intval');
        $uniqueId=osx_input('uniqueId','','text');
        $combinationId=osx_input('combinationId',0,'intval');
        $secKillId=osx_input('secKillId',0,'intval');
        $bargainId=osx_input('bargainId',0,'text');
        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        if ($bargainId && StoreBargainUserHelp::getSurplusPrice($bargainId, $this->userInfo['uid'])) return JsonService::fail('请先砍价');
        $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum, $uniqueId, 'product', 1, $combinationId, $secKillId, $bargainId,'now_buy');
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else
        {
            $cart=StoreCart::clearCartCache($this->uid,$res->id);
            return JsonService::successful('ok', ['cartId' => $res->id]);
        } 
    }

    /**
     * @api {post} /ebapi/auth_api/now_exchange 花间一壶酒活动-兑酒流程3.立即兑换
     * @apiName now_exchange
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} productId 商品ID
     * @apiParam {varchar} uniqueId 规格ID(73:235eb9b5)
     * @apiParam {varchar} couponId 优惠券id
     */
    public function now_exchange(){
        $productId=osx_input('productId','','text');
        $uniqueId=osx_input('uniqueId','','text');
        $couponId=osx_input('couponId',0,'intval');
        $cartNum=1;
        $bargainId=0;
        $combinationId=0;
        $secKillId=0;
        if (!$productId || !is_numeric($productId) || !$couponId ||!is_numeric($couponId)) return JsonService::fail('参数错误');
        $coupon=StoreCouponUser::getCouponById($couponId);
        if(!$coupon){
            return JsonService::fail('不存在优惠券，优惠券已使用或已过期');
        }
        $res = StoreCart::setCartExchange($this->userInfo['uid'], $productId, $cartNum, $uniqueId, 'product', 1, $combinationId, $secKillId, $bargainId,'now_buy',0,$couponId);
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else
        {
            $cart=StoreCart::clearCartCache($this->uid,$res->id);
            return JsonService::successful('ok', ['cartId' => $res->id]);
        }
    }

    /**
     * @api {post} /ebapi/auth_api/confirm_order_exchange 花间一壶酒活动-兑酒流程4.兑换确认
     * @apiName confirm_order_exchange
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} cartId 活动id
     */
    public function confirm_order_exchange(Request $request){
        $data = UtilService::postMore(['cartId'], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');        

        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'product',$this->isVip);
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];
        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        $other = [
            'offlinePostage' => SystemConfigService::get('offline_postage'),
            'integralRatio' => SystemConfigService::get('integral_ratio')
        ];
        // $usableCoupon = StoreCouponUser::beUsableCoupon($this->userInfo['uid'], $priceGroup['totalPrice']);
        $cartIdA = explode(',', $cartId);
        if (count($cartIdA) > 1) $seckill_id = 0;
        else {
            $seckillinfo = StoreCart::where('id', $cartId)->find();
            if ((int)$seckillinfo['seckill_id'] > 0) $seckill_id = $seckillinfo['seckill_id'];
            else $seckill_id = 0;
        }
        // $data['usableCoupon'] = $usableCoupon;
        $data['seckill_id'] = $seckill_id;
        $data['cartInfo'] = $cartInfo;

        //优惠券
        $couponId=$cartInfo[0]["coupon_id"];
        if(!$couponId) return JsonService::fail('无优惠券');
        $coupon=StoreCouponUser::getCouponById($couponId);
        if(!$coupon){
            return JsonService::fail('不存在优惠券，或已过期');
        }
        //判断是否是免费优惠券
        $couponPlan= MemberCouponPlan::get_coupon_attach($couponId);
        $is_free=join(",", $couponPlan->limit_product_ids)!='';
        if($is_free){
            $priceGroup['vipPrice']=0;
        }
        // $data['couponPlan']=$couponPlan;
        $event=Event::where('id',$couponPlan->event_id)->find();('province,city,district');
        $data['event']=['province'=>$event['province'], 'city'=>$event['city'],'district'=>$event['district']];
        $data['priceGroup'] = $priceGroup;
        $data['orderKey'] = StoreOrder::cacheOrderInfo($this->userInfo['uid'], $cartInfo, $priceGroup, $other);
        $data['offlinePostage'] = $other['offlinePostage'];
        $vipId=UserLevel::getUserLevel($this->uid);
        $this->userInfo['vip']=$vipId !==false ? true : false;
        if($this->userInfo['vip']){
            $this->userInfo['vip_id']=$vipId;
            $this->userInfo['discount']=UserLevel::getUserLevelInfo($vipId,'discount');
        }
        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        $data['isVip']=$this->isVip;
        $data['vip_discount']=SystemConfigService::get('membership_vip_discount');
       
        $data['user_score']= StoreOrder::get_user_order_score($this->uid,$this->isVip,$priceGroup);
      
        return JsonService::successful($data);
    }

    /**
     * @api {post} /ebapi/auth_api/create_order_exchange 花间一壶酒活动-兑酒流程5.立即兑换
     * @apiName create_order_exchange
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} midkey midkey
     */
    public function create_order_exchange(){
        try
        {
            list($midkey,$addressId) = UtilService::postMore([
                'midkey',
                'addressId',
    
           ], Request::instance(), true);
        //     list($midkey,$addressId, $couponId, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId,$is_zg,$score_num) = UtilService::postMore([
        //         'midkey','addressId', 'couponId', 'useIntegral', 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''], ['is_zg', '0'], ['score_num', '0']
        //    ], Request::instance(), true);
           if (!$midkey) return JsonService::fail('参数错误!');
           $score_num=0;//积分固定0
        //    $addressId=0;
        //    $couponId=0;
           $useIntegral=0;
           $mark='';
           $combinationId=0;
           $pinkId=0;
           $formId='';
           $bargainId='';
           $is_zg='0';
           $seckill_id=0;
    
           $iv = "1234567890123412";//16位 向量
           $key= '201707eggplant99';//16位 默认秘钥
           $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
    
           if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
               return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
           
           /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/
           if ($bargainId) StoreBargainUser::setBargainUserStatus($bargainId, $this->userInfo['uid']); //修改砍价状态
           /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/
    
    
           if ($pinkId) if (StorePink::getIsPinkUid($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
           if ($pinkId) if (StoreOrder::getIsOrderPink($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
    
           $order = StoreOrder::exchangeOrder($this->userInfo['uid'], $midkey, 0,$this->isVip,$addressId);
           $orderId = $order['order_id'];
           $info = compact('orderId', 'midkey');
           if ($orderId) {
               RoutineFormId::SetFormId($formId, $this->uid);
               Gong::actionadd('goumaishangpin','store_order','uid');//行为加分
               //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
               return JsonService::status('success', '兑换成功', $info);
           } else return JsonService::fail(StoreOrder::getErrorInfo('兑换失败!'));
        }
        catch(Exception $ex)
        {
            return $this->fail($ex->getMessage(),null);
        }
       
    }

     

    /**
     * 拼团 秒杀 砍价 加入到购物车
     * @return \think\response\Json
     */
    public function now_buy_zg()
    {
        $productId=osx_input('productId','','text');
        $cartNum=osx_input('cartNum',1,'intval');
        $uniqueId=osx_input('uniqueId','','text');
        $combinationId=osx_input('combinationId',0,'intval');
        $secKillId=osx_input('secKillId',0,'intval');
        $bargainId=osx_input('bargainId',0,'text');
        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        if ($bargainId && StoreBargainUserHelp::getSurplusPrice($bargainId, $this->userInfo['uid'])) return JsonService::fail('请先砍价');
        $uid = $this->userInfo['uid'];
        $data = db('store_cart')->where('uid', $uid)->where('product_id', $productId)->find();
        if($data){
            return JsonService::successful('ok', ['cartId' => $data['id']]);
        }else{
            $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum, $uniqueId, 'is_zg', 1, $combinationId, $secKillId, $bargainId);
            if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
            else  return JsonService::successful('ok', ['cartId' => $res->id]);
        }
    }
    /**
     * 获取购物车数量
     * @return \think\response\Json
     */
    public function get_cart_num()
    {
 
        if($this->uid>0)
        {
            return JsonService::successful('ok', StoreCart::getUserCartNum($this->userInfo['uid'], 'product'));
        }
        return  JsonService::successful('ok', 0);

    }
    
    /**
     * 修改购物车产品数量
     * @return \think\response\Json
     */
    public function change_cart_num()
    {

        $cartId=osx_input('cartId','','text');
        $cartNum=osx_input('cartNum','','text');
        if (!$cartId || !$cartNum || !is_numeric($cartId) || !is_numeric($cartNum)) return JsonService::fail('参数错误!');
        $cart_limit=SystemConfig::getValue('cart_limit');
        if($cart_limit<$cartNum) return JsonService::fail('加入购物车数量最高限制为'.$cart_limit.'件');
        $res = StoreCart::changeUserCartNum($cartId, $cartNum, $this->userInfo['uid']);
        if ($res)  return JsonService::successful();
        else return JsonService::fail(StoreCart::getErrorInfo('修改失败'));
    }

    /**
     * 删除购物车产品
     * @return \think\response\Json
     */
    public function remove_cart()
    {
        $ids=osx_input('ids','','text');
        if (!$ids) {
            return JsonService::fail('参数错误!');
        }
        $res=StoreCart::removeUserCart($this->userInfo['uid'], $ids);
        if($res){
            return JsonService::successful('删除成功');
        }
        return JsonService::fail('删除失败!');
    }
    /**
     * 创建订单
     * @return \think\response\Json
     */
    public function create_order()
    {
        $key=osx_input('key','','text');
        if (!$key) return JsonService::fail('参数错误!');
        if (StoreOrder::be(['order_id|unique' => $key, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
            return JsonService::status('extend_order', '订单已生成', ['orderId' => $key, 'key' => $key]);
        list($addressId, $couponId, $payType, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId) = UtilService::postMore([
            'addressId', 'couponId', 'payType', 'useIntegral', 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', '']
        ], Request::instance(), true);
        $payType = strtolower($payType);
        if ($bargainId) StoreBargainUser::setBargainUserStatus($bargainId, $this->userInfo['uid']); //修改砍价状态
        if ($pinkId) if (StorePink::getIsPinkUid($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
        if ($pinkId) if (StoreOrder::getIsOrderPink($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
        $order = StoreOrder::cacheKeyCreateOrder($this->userInfo['uid'], $key, $addressId, $payType, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId);
        $orderId = $order['order_id'];
        $info = compact('orderId', 'key');
        if ($orderId) {
            switch ($payType) {
                case "weixin":
                    $orderInfo = StoreOrder::where('order_id', $orderId)->find();
                    if (!$orderInfo || !isset($orderInfo['paid'])) exception('支付订单不存在!');
                    if ($orderInfo['paid']) exception('支付已支付!');
                    //如果支付金额为0
                    if (bcsub((float)$orderInfo['pay_price'], 0, 2) <= 0) {
                        //创建订单jspay支付
                        if (StoreOrder::jsPayPrice($orderId, $this->userInfo['uid'], $formId))
                            return JsonService::status('success', '微信支付成功', $info);
                        else
                            return JsonService::status('pay_error', StoreOrder::getErrorInfo());
                    } else {
                        RoutineFormId::SetFormId($formId, $this->uid);
                        try {
                            $jsConfig = StoreOrder::jsPay($orderId); //创建订单jspay
                            if(isset($jsConfig['package']) && $jsConfig['package']){
                                $package=str_replace('prepay_id=','',$jsConfig['package']);
                                for($i=0;$i<3;$i++){
                                    RoutineFormId::SetFormId($package, $this->uid);
                                }
                            }
                        } catch (\Exception $e) {
                            return JsonService::status('pay_error', $e->getMessage(), $info);
                        }
                        $info['jsConfig'] = $jsConfig;
                        return JsonService::status('wechat_pay', '订单创建成功', $info);
                    }
                    break;
                case 'yue':
                    if (StoreOrder::yuePay($orderId, $this->userInfo['uid'], $formId))
                        return JsonService::status('success', '余额支付成功', $info);
                    else {
                        $errorinfo = StoreOrder::getErrorInfo();
                        if (is_array($errorinfo))
                            return JsonService::status($errorinfo['status'], $errorinfo['msg'], $info);
                        else
                            return JsonService::status('pay_error', $errorinfo);
                    }
                    break;
                case 'offline':
                    RoutineFormId::SetFormId($formId, $this->uid);
                    //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
                    return JsonService::status('success', '订单创建成功', $info);
                    break;
            }
        } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }

    /**
     * 创建订单
     * @param string $key
     * @return \think\response\Json
     */
    public function create_order_new()
    {
        list($midkey,$addressId, $couponId, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId,$is_zg,$score_num) = UtilService::postMore([
             'midkey','addressId', 'couponId', 'useIntegral', 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''], ['is_zg', '0'], ['score_num', '0']
        ], Request::instance(), true);
        if (!$midkey) return JsonService::fail('参数错误!');
        // $score_num=0;//积分固定0

        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));

        if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
            return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
        
        /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/
        if ($bargainId) StoreBargainUser::setBargainUserStatus($bargainId, $this->userInfo['uid']); //修改砍价状态
        /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/


        if ($pinkId) if (StorePink::getIsPinkUid($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
        if ($pinkId) if (StoreOrder::getIsOrderPink($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
 
        $order = StoreOrder::cacheKeyCreateOrderNew($this->userInfo['uid'], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$is_zg,$score_num,0,0,$this->isVip);
        $orderId = $order['order_id'];
        $info = compact('orderId', 'midkey');
        if ($orderId) {
            RoutineFormId::SetFormId($formId, $this->uid);
            Gong::actionadd('goumaishangpin','store_order','uid');//行为加分
            //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
            return JsonService::status('success', '订单创建成功', $info);
        } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }

    /**
     * 创建智果订单
     * @param string $key
     * @return \think\response\Json
     */
    public function create_zg_order()
    {
        list($midkey,$addressId, $couponId, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId,$is_zg) = UtilService::postMore([
             'midkey','addressId', 'couponId', 'useIntegral', 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''], ['is_zg', '1']
        ], Request::instance(), true);
        if (!$midkey) return JsonService::fail('参数错误!');
        
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
        if (StoreOrder::be(['uid' => $this->userInfo['uid'],'paid' => 0, 'is_del' => 0, 'is_zg' => 1])){
            return JsonService::fail('您还有未支付的订单，请先取消或者支付订单以后再下单');
        }
        if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
            return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
        if ($bargainId) StoreBargainUser::setBargainUserStatus($bargainId, $this->userInfo['uid']); //修改砍价状态
        if ($pinkId) if (StorePink::getIsPinkUid($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
        if ($pinkId) if (StoreOrder::getIsOrderPink($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);

        $order = StoreOrder::ZgCreateOrderNew($this->userInfo['uid'], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$is_zg);

        // return JsonService::fail(StoreOrder::getErrorInfo('********!'));
        $orderId = $order['order_id'];
        $info = compact('orderId', 'midkey');
        if ($orderId) {
            RoutineFormId::SetFormId($formId, $this->uid);
            Gong::actionadd('goumaizhishishangpin','store_order','uid');//行为加分
            //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
            return JsonService::status('success', '订单创建成功', $info);
        } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }

    public function notify()
    {
        WechatService::handleNotify();
    }

    /**
     * 判断订单是否超时
     */
    public function time_out(){
        $uni=osx_input('uni','','text');
        if (!$uni) return JsonService::fail('参数错误!');
        $order = StoreOrder::getUserOrderDetail($this->userInfo['uid'], $uni);
        if (!$order) return JsonService::fail('订单不存在!');
        $time=time()-86400;
        if($order['paid']==0 && $order['add_time']<$time){
            StoreOrder::cancelOrder($order['order_id']);
            $data=0;
            JsonService::fail($data);
        }else{
            $data=1;
            return JsonService::successful($data);
        }
    }

    public function pay_order_new()
    {
        $uni=osx_input('uni','','text');
        $paytype=osx_input('paytype','weixin','text');
        $bill_type=osx_input('bill_type','pay_product','text');
        if (!$uni) return JsonService::fail('参数错误!');
        $order = StoreOrder::getUserOrderDetail($this->userInfo['uid'], $uni);
        if (!$order) return JsonService::fail('订单不存在!');
        if ($order['paid']) return JsonService::fail('该订单已支付!');
        if ($order['pink_id']) if (StorePink::isPinkStatus($order['pink_id'])) return JsonService::fail('该订单已失效!');
        if($order['pay_price']==0&&$order['is_zg']==1){
            $res = StoreOrder::yuePay($order['order_id'], $this->userInfo['uid'],'',$bill_type);
            if ($res){
                return JsonService::successful('购买成功');
            } else {
                return JsonService::fail('购买失败');
            }
        }else{
            $order['pay_type'] = $paytype; //重新支付选择支付方式
            switch ($order['pay_type']) {
                case 'weixin':
                    $status=db('pay_set')->where('type','weixin')->value('status');
                    if($status==0){
                        return JsonService::fail('该支付未开启!');
                    }
                    try {
                        $jsConfig = StoreOrder::jsPay($order); //订单列表发起支付
                        if(isset($jsConfig['package']) && $jsConfig['package']){
                            $jsConfig['package']=str_replace('prepay_id=','',$jsConfig['package']);
                            for($i=0;$i<3;$i++){
                                RoutineFormId::SetFormId($jsConfig['package'], $this->uid);
                            }
                        }
                        $jsConfig['package']='prepay_id='.$jsConfig['package'];
                        StoreOrder::where('id', $order['id'])->update(['pay_type'=>'weixin']);
                    } catch (\Exception $e) {
                        return JsonService::fail($e->getMessage());
                    }
                    return JsonService::status('wechat_pay', ['jsConfig' => $jsConfig, 'order_id' => $order['order_id']]);
                    break;
                case 'routine':
                    $status=db('pay_set')->where('type','weixin')->value('status');
                    if($status==0){
                        return JsonService::fail('该支付未开启!');
                    }
                    try {
                        //是否是礼品卡
                        if($order['type']==12)//礼品卡
                        {
                            $jsConfig = StoreOrder::MiniProgramJsPay($order,'order_id','Card'); //订单列表发起支付
                        }else{
                            $jsConfig = StoreOrder::MiniProgramJsPay($order); //订单列表发起支付
                        }
                       
                        if(isset($jsConfig['package']) && $jsConfig['package']){
                            $jsConfig['package']=str_replace('prepay_id=','',$jsConfig['package']);
                            for($i=0;$i<3;$i++){
                                RoutineFormId::SetFormId($jsConfig['package'], $this->uid);
                            }
                        }
                        $jsConfig['package']='prepay_id='.$jsConfig['package'];
                        StoreOrder::where('id', $order['id'])->update(['pay_type'=>'routine']);
                    } catch (\Exception $e) {
                        return JsonService::fail($e->getMessage());
                    }
                    return JsonService::status('wechat_pay', ['jsConfig' => $jsConfig, 'order_id' => $order['order_id']]);
                    break;
                case 'weixin_app':
                    $status=db('pay_set')->where('type','weixin')->value('status');
                    if($status==0){
                        return JsonService::fail('该支付未开启!');
                    }
                    try {
                        $appConfig = StoreOrder::wechatAppPay($order); //订单列表发起支付
                        for($i=0;$i<3;$i++){
                            RoutineFormId::SetFormId($appConfig['prepayid'], $this->uid);//多个地方用到表单令牌
                        }
                        StoreOrder::where('id', $order['id'])->update(['pay_type'=>'weixin_app']);
                    } catch (\Exception $e) {
                        return JsonService::fail($e->getMessage());
                    }
                    return JsonService::status('wechat_app_pay', ['appConfig' => $appConfig, 'order_id' => $order['order_id']]);
                    break;
                case 'yue':
                    $status=db('pay_set')->where('type','yue')->value('status');
                    if($status==0){
                        return JsonService::fail('该支付未开启!');
                    }
                    $password = osx_input('password', '', 'text');
                     /**解密 start**/
                    $iv = "1234567890123412";//16位 向量
                    $key= '201707eggplant99';//16位 默认秘钥
                    $password=trim(openssl_decrypt(base64_decode($password),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
                    $password=md5($password);
                    $wallet=db('user_wallet')->where(['uid'=>$this->uid,'password'=>$password])->find();
                    if($wallet){
                        if(bccomp($wallet['enable_money'],$order['pay_price'],2)==-1){
                            return $this->apiError(['status'=>0,'info'=>'余额不足']);
                        }
               
                        $res=\app\payapi\model\UserOrder::pay_order($order['order_id'],$this->uid,'yue');
                        if($res){
                            StoreOrder::paySuccess($order['order_id'],'yue');
                            return $this->apiSuccess(['status'=>1,'info'=>'支付成功']);
                        }else{
                            return $this->apiError(['status'=>0,'info'=>'支付失败']);
                        }
                
                    }else{
                        $this->apiError(['status'=>0,'info'=>'密码输入错误']);
                    }
                    break;
            }
        }


    }

    //TODO 支付订单
    /**
     * 支付订单
     * @return \think\response\Json
     */
    public function pay_order()
    {
        $uni=osx_input('uni','','text');
        $paytype=osx_input('paytype','weixin','text');
        if (!$uni) return JsonService::fail('参数错误!');
        $order = StoreOrder::getUserOrderDetail($this->userInfo['uid'], $uni);
        if (!$order) return JsonService::fail('订单不存在!');
        if ($order['paid']) return JsonService::fail('该订单已支付!');
        if ($order['pink_id']) if (StorePink::isPinkStatus($order['pink_id'])) return JsonService::fail('该订单已失效!');
        $order['pay_type'] = $paytype; //重新支付选择支付方式
        switch ($order['pay_type']) {
            case 'weixin':
                try {
                    $jsConfig = StoreOrder::jsPay($order); //订单列表发起支付
                    if(isset($jsConfig['package']) && $jsConfig['package']){
                        $jsConfig['package']=str_replace('prepay_id=','',$jsConfig['package']);
                        for($i=0;$i<3;$i++){
                            RoutineFormId::SetFormId($jsConfig['package'], $this->uid);
                        }
                    }
                } catch (\Exception $e) {
                    return JsonService::fail($e->getMessage());
                }
                return JsonService::status('wechat_pay', ['jsConfig' => $jsConfig, 'order_id' => $order['order_id']]);
                break;
            case 'yue':
                if ($res = StoreOrder::yuePay($order['order_id'], $this->userInfo['uid']))
                    return JsonService::successful('余额支付成功');
                else {
                    $error = StoreOrder::getErrorInfo();
                    return JsonService::fail(is_array($error) && isset($error['msg']) ? $error['msg'] : $error);
                }
                break;
            case 'offline':
                StoreOrder::createOrderTemplate($order);
                return JsonService::successful('订单创建成功');
                break;
        }
    }

    /*
     * 未支付的订单取消订单回退积分,回退优惠券,回退库存
     * @param string $order_id 订单id
     * */
    public function cancel_order()
    {
        $order_id=osx_input('order_id','','text');
        if (StoreOrder::cancelOrder($order_id))
            return JsonService::successful('取消订单成功');
        else
            return JsonService::fail(StoreOrder::getErrorInfo());
    }

    // public function time_out_order(){
    //     $out_time=SystemConfig::getValue('close_order_time');
    //     $time=time()-$out_time*3600;


    // }

    /**
     * 申请退款
     * @param string $uni
     * @param string $text
     * @return \think\response\Json
     */
    public function apply_order_refund(Request $request)
    {
        $data = UtilService::postMore([
            ['text', ''],
            ['refund_reason_wap_img', ''],
            ['refund_reason_wap_explain', ''],
            ['uni', '']
        ], $request);
        $uni = $data['uni'];
        unset($data['uni']);
        if ($data['refund_reason_wap_img']) $data['refund_reason_wap_img'] = explode(',', $data['refund_reason_wap_img']);
        if (!$uni || $data['text'] == '') return JsonService::fail('参数错误!');
        $res = StoreOrder::orderApplyRefund($uni, $this->userInfo['uid'], $data['text'], $data['refund_reason_wap_explain'], $data['refund_reason_wap_img']);
        if ($res){
            $order=StoreOrder::where('order_id',$uni)->find();
            $h5_url=SystemConfig::getValue('platform_h5_url');
            WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($order['uid']),WechatTemplateService::REFUND, [
                'first'=>'亲，您的订单已申请退款',
                'keyword1'=>$order['pay_price'],
                'keyword2'=>'3-7个工作日内',
                'remark'=>'可以去商城查看订单详情'
            ],$h5_url.'packageB/order/detail?id='.$order['order_id']);
            return JsonService::successful();
        }
        else{
            return JsonService::fail(StoreOrder::getErrorInfo());
        }

    }


    /**
     * 再来一单
     * @param string $uni
     */
    public function order_details()
    {
        $uni=osx_input('uni','','text');
        if (!$uni) return JsonService::fail('参数错误!');
        $order = StoreOrder::getUserOrderDetail($this->userInfo['uid'], $uni);
        if (!$order) return JsonService::fail('订单不存在!');
        $order = StoreOrder::tidyOrder($order, true);
        $res = array();
        foreach ($order['cartInfo'] as $v) {
            if ($v['combination_id']) return JsonService::fail('拼团产品不能再来一单，请在拼团产品内自行下单!');
            else  $res[] = StoreCart::setCart($this->userInfo['uid'], $v['product_id'], $v['cart_num'], isset($v['productInfo']['attrInfo']['unique']) ? $v['productInfo']['attrInfo']['unique'] : '', 'product', 0, 0);
        }
        $cateId = [];
        foreach ($res as $v) {
            if (!$v->result) return JsonService::fail('再来一单失败，请重新下单!');
            $cateId[] = $v['id'];
        }
        return JsonService::successful('ok', implode(',', $cateId));
    }
    /**
     * 购物车库存修改
     */
    public function set_buy_cart_num()
    {
        $cartId=osx_input('cartId',0,'intval');
        $cartNum=osx_input('cartNum',0,'intval');
        if (!$cartId) return JsonService::fail('参数错误');
        $res = StoreCart::edit(['cart_num' => $cartNum], $cartId);
        if ($res) return JsonService::successful();
        else return JsonService::fail('修改失败');
    }

    const ReqURL = "http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";


    public function getMessage(){
        $ShipperCode=osx_input('ShipperCode','');//快递公司编号
        $order_sn=osx_input('order_sn','');//运单号
        $phone=osx_input('phone','');//手机号
        $phone=mb_substr($phone,-4);
        $requestData= "{'OrderCode':'','ShipperCode':'".$ShipperCode."','LogisticCode':'".$order_sn."','CustomerName':'".$phone."'}";
        $config = SystemConfig::getMore('kdn_id,kdn_my,kdn_ff');
        if($config['kdn_ff']==1){
            $datas = array(
                'EBusinessID' => $config['kdn_id'],
                'RequestType' => '8001',//接口指令1002，固定
                'RequestData' => urlencode($requestData) ,
                'DataType' => '2', //数据返回格式 2 json
            );
        }else{
            $datas = array(
                'EBusinessID' => $config['kdn_id'],
                'RequestType' => '1002',//接口指令1002，固定
                'RequestData' => urlencode($requestData) ,
                'DataType' => '2', //数据返回格式 2 json
            );
        }
        //把$requestData进行加密处理
        $datas['DataSign'] = $this -> encrypt($requestData,$config['kdn_my']);
        $result = $this -> sendPost( self::ReqURL, $datas);
        if(!is_array($result)){
            $result=json_decode($result,true);
        }
        return JsonService::successful($result);
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    private function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /*
     * 进行加密
     */
    private function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}
