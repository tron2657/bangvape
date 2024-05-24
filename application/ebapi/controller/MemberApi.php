<?php

namespace app\ebapi\controller;
use service\GroupDataService;
use app\ebapi\model\member\MemberShip;
use app\ebapi\model\member\MemberCard;
use service\JsonService;
use service\UtilService;
use app\ebapi\model\store\StoreOrder;
use think\Request;
use app\ebapi\model\store\StoreCart;
use app\core\util\SystemConfigService;
use app\ebapi\model\store\StoreCouponUser;
use app\core\model\routine\RoutineFormId;//待完善
use app\commonapi\model\Gong;
use service\MemberShipService;

class MemberApi extends AuthApi
{
    public static function whiteList()
    {
        return [
            'time_out_trial'            
        ];
    }

    

    /**
     * 会员页数据
     */
    public function merberDatas(){
        // $interestsObj=GroupDataService::getDataDic('membership_interests',3)?:[];
        //SystemConfig::
        $config=MemberShipService::config();
        
        // $interests=GroupDataService::getData('membership_interests',3)?:[];
        // $description=GroupDataService::getData('member_description')?:[];
        // $interests_sort = array_column($interests,'sort');
        // array_multisort($interests_sort,SORT_ASC,$interests);
        // $description_sort = array_column($description,'sort');
        // array_multisort($description_sort,SORT_ASC,$description);
        // $data['interests']=$interests;
        // $data['description']=$description;
        $data['member']=MemberShip::memberMinOne();
        $data['memberData']=MemberShip::membershipList();
        $data['isVip']=$this->isVip;
        // $data['freeData']=MemberShip::memberFree($this->userInfo['uid']);
        // $data['interestsObj']=$interestsObj;
        $data['config']=$config;
        return JsonService::successful($data);
    }
        
    /**
     * 会员设置列表
     */
    public function membershipLists(){
        $meList=MemberShip::membershipList();
        return JsonService::successful($meList);
    }

    public function create_order(){
        list($special_id, $pay_type_num, $payType, $pinkId, $total_num, $link_pay_uid,$signUp) = UtilService::PostMore([
            ['special_id', 0],
            ['pay_type_num', -1],
            ['payType', 'weixin'],
            ['pinkId', 0],
            ['total_num', 1],
            ['link_pay_uid', 0],
            ['sign',''],
        ], $this->request, true);
        switch ($pay_type_num){
            case 10://会员支付
                $this->create_member_order($special_id,$payType);
                break;
            // case 20://报名支付
            //     $this->create_activity_order($special_id,$payType,$signUp);
            //     break;
            // case 30://虚拟币充值
            //     $auth_api = new AuthApi();
            //     $auth_api->user_wechat_recharge($special_id, $payType);
            // case 40: //商品购买
            //     $this->create_goods_order($special_id,$payType,$signUp);
            //     break;
             case 50: //订单再次支付
                $this->pay_order($special_id,$payType);
                break;
            default://专题支付
                $this->create_special_order($special_id, $pay_type_num, $payType, $pinkId, $total_num, $link_pay_uid);
        }
    }

      /**
     * 免费会员/是否领取
     */
    public function isRecord()
    {
        $data['freeData']=MemberShip::memberFree($this->userInfo['uid']);
        return JsonService::successful($data);
    }

        /**
     * 创建专题支付订单
     * @param int $special_id 专题id
     * @param int $pay_type 购买类型 1=礼物,2=普通购买,3=开团或者拼团
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create_special_order($special_id, $pay_type_num, $payType, $pinkId, $total_num, $link_pay_uid)
    {

        if (!$special_id) return JsonService::fail('缺少购买参数');
        if ($pay_type_num == -1) return JsonService::fail('选择购买方式');
        if ($pinkId) {
            $orderId = StoreOrder::getStoreIdPink($pinkId);
            if (StorePink::getIsPinkUid($pinkId)) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => $orderId]);
            if (StoreOrder::getIsOrderPink($pinkId)) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => $orderId]);
            if (StorePink::getPinkStatusIng($pinkId)) return JsonService::status('ORDER_EXIST', '拼团已完成或者已过期无法参团', ['orderId' => $orderId]);
            if (StorePink::be(['uid' => $this->uid, 'type' => 1, 'cid' => $special_id, 'status' => 1])) return JsonService::status('ORDER_EXIST', '您已参见本专题的拼团,请结束后再进行参团');
            if (SpecialBuy::be(['uid' => $this->uid, 'special_id' => $special_id, 'is_del' => 0])) return JsonService::status('ORDER_EXIST', '您已购买此专题,不能在进行参团!');
            //处理拼团完成
            try {
                if ($pink = StorePink::get($pinkId)) {
                    list($pinkAll, $pinkT, $count, $idAll, $uidAll) = StorePink::getPinkMemberAndPinkK($pink);
                    if ($pinkT['status'] == 1) {
                        if (!$count || $count < 0) {
                            StorePink::PinkComplete($uidAll, $idAll, $pinkT['uid'], $pinkT);
                            return JsonService::status('ORDER_EXIST', '当前拼团已完成，无法参团');
                        } else
                            StorePink::PinkFail($pinkT['uid'], $idAll, $pinkAll, $pinkT, $count, 0, $uidAll);
                    } else if ($pinkT['status'] == 2) {
                        return JsonService::status('ORDER_EXIST', '当前拼团已完成，无法参团');
                    } else if ($pinkT['status'] == 3) {
                        return JsonService::status('ORDER_EXIST', '拼团失败，无法参团');
                    }
                }
            } catch (\Exception $e) {

            }
        }
        $special = SpecialModel::PreWhere()->find($special_id);
        if (!$special) return JsonService::status('ORDER_ERROR', '购买的专题不存在');
        $order = StoreOrder::createSpecialOrder($special, $pinkId, $pay_type_num, $this->uid, $payType, $link_pay_uid, $total_num);
        $orderId = $order['order_id'];
        $info = compact('orderId');
        if ($orderId) {
            $orderInfo = StoreOrder::where('order_id', $orderId)->find();
            if (!$orderInfo || !isset($orderInfo['paid'])) return JsonService::status('pay_error', '支付订单不存在!');
            if ($orderInfo['paid']) return JsonService::status('pay_error', '支付已支付!');
            if (bcsub((float)$orderInfo['pay_price'], 0, 2) <= 0) {
                if (StoreOrder::jsPayPrice($orderId, $this->userInfo['uid']))
                    return JsonService::status('success', '微信支付成功', $info);
                else
                    return JsonService::status('pay_error', StoreOrder::getErrorInfo());
            }
            switch ($payType) {
                case 'weixin':
                    try {
                        $jsConfig = StoreOrder::jsSpecialPay($orderId);
                    } catch (\Exception $e) {
                        return JsonService::status('pay_error', $e->getMessage(), $info);
                    }
                    $info['jsConfig'] = $jsConfig;
                    return JsonService::status('wechat_pay', '订单创建成功', $info);
                    break;
                case 'yue':
                    if (StoreOrder::yuePay($orderId, $this->userInfo['uid']))
                        return JsonService::status('success', '余额支付成功', $info);
                    else
                        return JsonService::status('pay_error', StoreOrder::getErrorInfo());
                    break;
                case 'zhifubao':
                    $info['pay_price'] = $orderInfo['pay_price'];
                    $info['orderName'] = '专题购买';
                    return JsonService::status('zhifubao_pay','订单创建成功', base64_encode(json_encode($info)));
                    break;
            }
        } else {
            return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
        }
    }

    public function confirm_order(Request $request)
    {
        $data = UtilService::postMore([
            'cartId',
            'membership_id'
        ], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'membership',false);
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];

 
        
        //动态修改价格
        foreach($cartInfo as &$car)
        {
            $memberShip=db('member_ship')
            ->where('id',$data['membership_id'])
            ->where('product_id',$car['productInfo']['id'])
            ->find();
            if($memberShip==null) return JsonService::fail('商品参数错误!!');

            $car['price']=$memberShip['price'];
            $car['truePrice']=$memberShip['price'];
            $car['costPrice']=$memberShip['price'];;
            $car['productInfo']['ot_price']=$memberShip['original_price'];   
            $car['productInfo']['price']=$memberShip['price'];;
          
            //array_push($carArr,$car);
        }

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
    
        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        return JsonService::successful($data);
    }

        /**
     * 会员卡激活
     */
    public function confirm_activation(){
        $request = Request::instance();
        if (!$request->isPost()) return JsonService::fail('参数错误!');
        $data = UtilService::postMore([
            ['member_code', ''],
            ['member_pwd', ''],
        ], $request);
        $res=MemberCard::confirmActivation($data,$this->userInfo);
        if($res)
            return JsonService::successful('激活成功');
        else
            return JsonService::fail(MemberCard::getErrorInfo('激活失败!'));
    }

        /**会员订单创建
     * @param $id
     * @param $payType
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create_member_order($id,$payType)
    {
        if(!$id) return JsonService::fail('参数错误!');
        $order = StoreOrder::cacheMemberCreateOrder($this->userInfo['uid'],$id,$payType);
        $orderId = $order['order_id'];
        $info = compact('orderId');
        if ($orderId) {
            $orderInfo = StoreOrder::where('order_id', $orderId)->find();
            if (!$orderInfo || !isset($orderInfo['paid'])) exception('支付订单不存在!');
            if ($orderInfo['paid']) exception('支付已支付!');
            if (bcsub((float)$orderInfo['pay_price'], 0, 2) <= 0) {
                if (StoreOrder::jsPayMePrice($orderId, $this->userInfo['uid']))
                    return JsonService::status('success', '领取成功', $info);
                else
                    return JsonService::status('pay_error', StoreOrder::getErrorInfo());
            } else {
                switch ($payType) {
                    case 'weixin':
                        try {
                            $jsConfig = StoreOrder::jsPayMember($orderId);
                        } catch (\Exception $e) {
                            return JsonService::status('pay_error', $e->getMessage(), $info);
                        }
                        $info['jsConfig'] = $jsConfig;
                        return JsonService::status('wechat_pay', '订单创建成功', $info);
                        break;
                    case 'zhifubao':
                        $info['pay_price'] = $orderInfo['pay_price'];
                        $info['orderName'] = '会员购买';
                        return JsonService::status('zhifubao_pay','订单创建成功', base64_encode(json_encode($info)));
                        break;
                }
            }
        } else {
            return JsonService::fail(StoreOrder::getErrorInfo('领取失败!'));
        }
    }



    /**
     * 拼团 秒杀 砍价 加入到购物车
     * @return \think\response\Json
     */
    public function now_buy()
    {
        $productId=osx_input('productId','','text');
        $cartNum=osx_input('cartNum',1,'intval');
        $uniqueId=osx_input('uniqueId','','text');
        $combinationId=osx_input('combinationId',0,'intval');
        $secKillId=osx_input('secKillId',0,'intval');
        $bargainId=osx_input('bargainId',0,'text');
        $membershipid=osx_input('membership_id','','intval');

        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        $userInfo = $this->userInfo;
        if($userInfo['level'] && $userInfo['is_permanent']) return JsonService::fail('您是永久会员，无需续费!');
        $memberCount= \app\ebapi\model\member\MemberShip::where('id',$membershipid)
        ->where('is_publish',1)
        ->where('is_del',0)
        ->where('type',1)
        ->where('product_id',$productId)
        ->count();
        if($memberCount==0)
        {
            return JsonService::fail('不存在该记录!');
        }
        $res = StoreCart::setCart($this->userInfo['uid'], $productId, 1, 0, 'membership', 1, 0, 0, 0,'now_buy');
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else  return JsonService::successful('ok', ['cartId' => $res->id]);
    }


    /**
     * 创建订单
     * @param string $key
     * @return \think\response\Json
     */
    public function create_order_new()
    {
        list($midkey,$addressId, $couponId, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId,$is_zg,$score_num,$member_id) = UtilService::postMore([
             'midkey','addressId', 'couponId', 'useIntegral', 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''], ['is_zg', '0'], ['score_num', '0'],['member_id',0]
        ], Request::instance(), true);
        if (!$midkey) return JsonService::fail('参数错误!');

        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));

        if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
            return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
          
         
        $order = StoreOrder::cacheKeyCreateOrderNew($this->userInfo['uid'], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$is_zg,$score_num,11,$member_id);
        $orderId = $order['order_id'];
        $info = compact('orderId', 'midkey');
        if ($orderId) {
            RoutineFormId::SetFormId($formId, $this->uid);
            Gong::actionadd('goumaishangpin','store_order','uid');//行为加分
            //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
            return JsonService::status('success', '订单创建成功', $info);
        } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }


}
 