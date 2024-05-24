<?php


namespace app\ebapi\controller;

use app\admin\model\card\CardExchange;
use app\core\model\routine\RoutineFormId;//待完善
use app\core\model\UserLevel;
use app\ebapi\model\store\StoreProduct;
use app\ebapi\model\store\StoreProductAttr;
use app\ebapi\model\store\StoreCart;
use app\ebapi\model\store\StoreCouponUser;
use service\JsonService;
use app\core\util\SystemConfigService;
use service\UtilService;
use think\Request;
use app\osapi\model\common\Support;
use app\core\behavior\GoodsBehavior;//待完善
use app\commonapi\model\Gong;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreOrderStatus;
use app\ebapi\model\user\User;
use app\admin\model\system\SystemConfig;
use service\WechatTemplateService;
use app\ebapi\model\user\WechatUser;
use app\core\util\WechatServiceCard;
use think\Cache;
use app\admin\model\payment\UserOrderLog;
use app\ebapi\model\card\Card;
use app\ebapi\model\card\CardStatus;
use app\ebapi\model\card\CardSend;
use app\ebapi\model\card\CardOrder;
use app\core\model\routine\RoutineCode;//待完善

class CardApi extends AuthController
{
    public static function whiteList()
    {
        return [
            'card_list'
        ];
    }

     /**
     * @api {post} /ebapi/card_api/get_page_code 获取小程序二维码
     * @apiName get_page_code
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {Number} url=/packageA/activity/detail?id=4  url.
     */
    public function get_page_code($url){
        $uid=$this->userInfo['uid'];
        $path = makePathToUrl('routine/code');
        if($path == '')
            return JsonService::fail('生成上传目录失败,请检查权限!');
        $picname = $path.'/'.$this->userInfo['uid'].'.jpg';
        $domain = SystemConfigService::get('site_url').'/';
        $domainTop = substr($domain,0,5);
        if($domainTop != 'https') $domain = 'https:'.substr($domain,5,strlen($domain));
    //   $res=  RoutineCode::getCode(0,'',$color = array(),$url,'card');
    $res= RoutineCode::getCode_activity(4,$picname);
      if($res) file_put_contents($picname,$res);
       return JsonService::successful($domain.$picname);
    }
     /**
     * @api {post} /ebapi/card_api/now_buy 购买流程1.点击购买
     * @apiName now_buy
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {Number} productId  productId.
     * @apiParam {Number} cartNum  cartNum.
     */
    public function now_buy(){
        $productId=osx_input('productId','','text');
        $cartNum=osx_input('cartNum',1,'intval');
        //$uniqueId=osx_input('uniqueId','','text');//默认第一个规格
        // $attr=StoreProductAttr::storeProductAttrValueDb()->where('product_id',$productId)->find();
        // $uniqueId=$attr['unique'];
        $uniqueId=0;
        $combinationId=0;
        $secKillId=0;
        $bargainId=0;
        // $combinationId=osx_input('combinationId',0,'intval');
        // $secKillId=osx_input('secKillId',0,'intval');
        // $bargainId=osx_input('bargainId',0,'text');
        if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');
        if ($bargainId && StoreBargainUserHelp::getSurplusPrice($bargainId, $this->userInfo['uid'])) return JsonService::fail('请先砍价');
        $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum,$uniqueId, 'product', 1, $combinationId, $secKillId, $bargainId,'now_buy');
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else
        {
            $cart=StoreCart::clearCartCache($this->uid,$res->id);
            return JsonService::successful('ok', ['cartId' => $res->id]);
        }  
    }

    /**
     * @api {post} /ebapi/card_api/confirm_order 购买流程2.确认订单
     * @apiName confirm_order
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {Number} cartId  cartId
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
       // $usableCoupon = StoreCouponUser::beUsableCoupon($this->userInfo['uid'], $priceGroup['totalPrice']);
        $cartIdA = explode(',', $cartId);
        if (count($cartIdA) > 1) $seckill_id = 0;
        else {
            $seckillinfo = StoreCart::where('id', $cartId)->find();
            if ((int)$seckillinfo['seckill_id'] > 0) $seckill_id = $seckillinfo['seckill_id'];
            else $seckill_id = 0;
        }
        //$data['usableCoupon'] = $usableCoupon;
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
        $data['isVip']=$this->isVip;
        $data['vip_discount']=SystemConfigService::get('membership_vip_discount');
        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        $data['user_score']= StoreOrder::get_user_order_score($this->uid,$this->isVip,$priceGroup);
        return JsonService::successful($data);
    }

     /**
     * @api {post} /ebapi/card_api/get_midkey 购买流程3.获取key(正式环境无需)
     * @apiName get_midkey
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {string} key=OSA2SFsRCH2w+s1TvIUSnNkTCFxfhucbVPFvOhLb962Gu2AT0Wa7WVwPk6wgaKDS  key.
     */
    public function get_midkey(){
        $inputkey=osx_input('key','','text');
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $midkey=openssl_encrypt($inputkey,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        $data['key']=base64_encode($midkey);
        return JsonService::successful($data);
    }
    /**
     * @api {post} /ebapi/card_api/create_order_new 购买流程4.创建订单
     * @apiName create_order_new
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {string} midkey=OSA2SFsRCH2w+s1TvIUSnNkTCFxfhucbVPFvOhLb962Gu2AT0Wa7WVwPk6wgaKDS  midkey.
     * @apiParam {Number} mark  mark
     */
    public function create_order_new()
    {
        list($midkey, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId,$is_zg,$score_num) = UtilService::postMore([
            'midkey','mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''], ['is_zg', '0'], ['score_num', '0']
       ], Request::instance(), true);
       if (!$midkey) return JsonService::fail('参数错误!');
       $score_num=0;//积分为0
       $iv = "1234567890123412";//16位 向量
       $key= '201707eggplant99';//16位 默认秘钥
       $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));

       if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
           return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
       
       /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/
       //if ($bargainId) StoreBargainUser::setBargainUserStatus($bargainId, $this->userInfo['uid']); //修改砍价状态
       /**当前屏蔽砍价功能，所以这里用不到，所以不做事务考虑**/


       //if ($pinkId) if (StorePink::getIsPinkUid($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
       //if ($pinkId) if (StoreOrder::getIsOrderPink($pinkId, $this->userInfo['uid'])) return JsonService::status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $this->userInfo['uid'])]);
       $order = StoreOrder::cacheKeyCreateOrderCard($this->userInfo['uid'], $midkey,false,0, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$score_num,$this->isVip);
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
     * @api {post} /ebapi/card_api/pay_order_new 购买流程5.支付订单
     * @apiName pay_order_new
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {string} uni  uni.
     * @apiParam {string} paytype="routine"  paytype.
     * @apiParam {string} bill_type="pay_product"   bill_type.
     */
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

        $out_time=SystemConfig::getValue('close_order_time');
        $time=time()-$out_time*3600;
        if($order['add_time']<$time){
            return JsonService::fail('订单已超时，不能支付');
        }

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
                        $jsConfig = StoreOrder::MiniProgramJsPay($order,'order_id','Card'); //订单列表发起支付
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

     /**
     * @api {post} /ebapi/card_api/notify 支付回调
     * @apiName notify
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {string} uni  uni.
     * @apiParam {string} paytype=routine  paytype.
     * @apiParam {string} bill_type=pay_product   bill_type.
     */
    public function notify()
    {
        WechatServiceCard::handleNotify();
    }

    /**
     * @api {post} /ebapi/card_api/payment_success_test 购买流程6.支付成功测试（正式环境无需）
     * @apiName payment_success_test
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {string} orderId
     * @apiParam {string} paytype="routine"
     * @apiParam {string} formId="pay_product"
     */
    public function payment_success_test(){
        // $orderId,$paytype='weixin',$formId = ''
        $orderId=osx_input('orderId', '');
        $paytype=osx_input('paytype', 'routine');
        $formId=osx_input('formId', 'pay_product');

       $res= StoreOrder::paySuccessCard($orderId,$paytype,$formId);
       return JsonService::successful($res);
    }

    /**
     * @api {get} /ebapi/card_api/card_list  礼品卡列表
     * @apiName card_list
     * @apiGroup Card
     *
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {Number} page 1 Page.
     * @apiParam {Number} limit 20 Limit.
     * @apiSuccess {String} cash_price 价格.
     * @apiSuccess {String} vip_price 会员价格.
     * @apiSuccess {String} thrift 预计节省.
     */
    public function card_list(){
        list($page,$limit)=UtilService::getMore([
            ['page',''],
            ['limit',''],
        ],$this->request,true);
        $uid=get_uid();
        $list=StoreProduct::where(['is_show'=>1,'is_type'=>2,'is_del'=>0])->page((int)$page,(int)$limit)->order('sort asc')->field('id,image,store_name,price,vip_price')->select();
        foreach($list as &$item){
            $item['thrift']=bcsub($item['price'],$item['vip_price']);
            $item['vip_price']=(float)StoreProduct::getVipPrice($item['price']);
        }
        return JsonService::successful($list);
    }

     /**
     * @api {get} /ebapi/card_api/details 礼品卡详情
     * @apiName details
     * @apiGroup Card
     *
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {Number} id  Id.
     * @apiParam {Number} number  Number.
     * @apiSuccess {String} number 数量.
     * @apiSuccess {String} amount 金额.
     */
    public function details(){
        $id=osx_input('id',0,'intval');
        $number=osx_input('number',0,'intval');
        if($number<=0) return JsonService::fail('数量不能小于0');
        $field='id,image,store_name,price,vip_price,is_type';
        if(!$id || !($cardProduct = StoreProduct::getCardProduct($id,$field))) return JsonService::fail('商品不存在或已下架');
        if($cardProduct=='该商品已下架！') return JsonService::fail('该商品已下架！');
        if($cardProduct['is_type']!=2) return JsonService::fail('只能购买礼品卡');
        $cardProduct['number']=$number;
        $cardProduct['amount']=bcmul($cardProduct['price'],$number);
        return JsonService::successful($cardProduct);
    }

     /**
     * @api {get} /ebapi/card_api/my_card_list 我的卡
     * @apiName my_card_list
     * @apiGroup Card
     *
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     */
    public function my_card_list(){
        $uid=get_uid();
        $list= Card::where('o.uid',$uid)->alias("o")->join('store_product p','p.id=o.product_id','LEFT')->field(['o.id','o.status','o.product_id','count(*) count','p.store_name','p.image'])->group('o.product_id')->select();
        
        foreach ($list as $item) {
            $item['left_count']= Card::where(['product_id'=>$item['product_id'],'uid'=>$uid])->where('status',0)->count();
            $item['end_time']='永久有效';
        }
        return JsonService::successful($list);
    }

     /**
     * @api {get} /ebapi/card_api/my_card_list_more 我的卡-查看更多
     * @apiName my_card_list_more
     * @apiGroup Card
     *
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} product_id 商品ID
     */
    public function my_card_list_more(){
        $uid=get_uid();
        $productId=osx_input('product_id',0);
        $list= Card::where(['o.product_id'=>$productId,'uid'=>$uid])->alias("o")
                ->join('store_product p','p.id=o.product_id','LEFT')
                ->join('card_exchange e','e.product_id=o.product_id','LEFT')->field(['o.id','e.exchange_product_id as product_id','p.store_name','p.image','o.status','o.pay_price'])->select();
        foreach ($list as $item) {
            # code...            
            $item['count']=$item['status']== 0 ? 1 : 0;
            $item['end_time']='永久有效';
        }
        return JsonService::successful($list);
    }

    /**
     * @api {get} /ebapi/card_api/card_status_list 我的卡-兑换记录
     * @apiName card_status_list
     * @apiGroup Card
     *
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} card_id 商品ID
     */
    public function card_status_list(){
        $uid=get_uid();
        $cardId=osx_input('card_id',0);
        $list=CardStatus::where(['card_id'=>$cardId])->field('card_id,change_message,change_time,remark')->select();
        foreach($list as $item){
            $item['change_time']=date('Y/m/d H:i',$item['change_time']);
        }
        $card=Card::where(['id'=>$cardId])->find();
        $time='长期有效';
        if($card['end_time']!=null&&$card['end_time']!=0){
            $time==date('Y/m/d H:i',$card['end_time']);
        }
        $data['end_time']=$time;
        $data['send_times_left']=$card['send_times_left'];
        $data['list']=$list;
        return JsonService::successful($data);
    }

     /**
     * @api {get} /ebapi/card_api/use_card_tobalance 我的卡-使用1-转入余额
     * @apiName use_card_tobalance
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} id 礼品卡id
     */
    public function use_card_tobalance(){
        $id=osx_input('id',0);
        $res=Card::cardToBalance($id);
        if(!$res){
            return JsonService::fail("转入失败");
        }
        return JsonService::success("转入成功");       
    }

    /**
     * @api {post} /ebapi/card_api/use_send_card 我的卡-使用2-赠送
     * @apiName use_send_card
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} id 礼品卡id
     * @apiParam {strng} message 祝福语
     */
    public function use_send_card(){
        $card_id=osx_input('id',0);
        $message=osx_input('message',0);
        $uid=get_uid();
        $card= CardSend::send_to($card_id,$uid,$message);
        if($card){
            return JsonService::successful($card);
        }
        return JsonService::fail(CardSend::getErrorInfo());
    }

     /**
     * @api {post} /ebapi/card_api/use_send_cancel 我的卡-撤销赠送
     * @apiName use_send_cancel
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} id 礼品卡id
     */
    public function use_send_cancel(){
        $card_id=osx_input('id',0);
        $uid=get_uid();
        $card= CardSend::send_cancel($card_id,$uid);
        if(!$card){
            return JsonService::fail("撤销失败");
        }
        return JsonService::success("撤销成功");
    }

    /**
     * @api {post} /ebapi/card_api/recieve_card 接收礼品卡1.接收礼品卡
     * @apiName recieve_card
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} recieve_code 礼品卡code
     */
    public function recieve_card(){
        $recieve_code=osx_input('recieve_code',0);
        if($recieve_code=='undefined'){
            return JsonService::fail("礼品卡不存在");
        }
        $send=CardSend::recieve_card($recieve_code);
        if(!$send){
            return JsonService::fail("礼品卡不存在");
        }
        return JsonService::successful($send);
    }


     /**
     * @api {get} /ebapi/card_api/recieve_card_confirm 接收礼品卡2.确认接收赠送
     * @apiName recieve_card_confirm
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {strng} recieve_code 礼品卡code
     */
    public function recieve_card_confirm(){
        $recieve_code=osx_input('recieve_code',0);
        $res=CardSend::recieve_card_confirm($recieve_code);
        if($res){
            return JsonService::success("接收成功");
        }
        return JsonService::fail(CardSend::getErrorInfo());
    }


     /**
     * @api {post} /ebapi/card_api/exchange_now 兑换流程1.立即兑换
     * @apiName exchange_now
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {int} card_id card_id
     * @apiParam {int} uniqueId="c657ae41" 规格id
     */
    public function exchange_now(){
        $uniqueId=osx_input('uniqueId','','text');//规格id
        $card_id=osx_input('card_id',0);
        $uid=get_uid();
        $card= Card::where(['id'=>$card_id,'status'=>0,'uid'=>$uid])->find();        
        if(!$card) return JsonService::fail('礼品卡不存在');
        $exchange_card=CardExchange::where(['product_id'=>$card['product_id']])->find();
        $productId=$exchange_card['exchange_product_id'];//兑换商品id
        $cartNum=1;
        $combinationId=0;
        $secKillId=0;
        $bargainId=0;
        $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum, $uniqueId, 'card_exchange', 1, $combinationId, $secKillId, $bargainId,'now_buy');
        $res3 = Card::where(['cart_id'=>$res->id])->update(['cart_id'=>0]);
        $res1 = Card::where(['id'=>$card_id])->update(['cart_id'=>$res->id]);
        $res2=$res&&$res1&&$res3;
        if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
        else  return JsonService::successful('ok', ['cartId' => $res->id]);
    }


     /**
     * @api {post} /ebapi/card_api/exchange_confirm_order 兑换流程2.确认订单
     * @apiName exchange_confirm_order
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {number} cartId cartId
     */
    public function exchange_confirm_order(Request $request){
        $data = UtilService::postMore(['cartId'], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        //判断礼品卡是否由会员赠送,如果是会员赠送，则价格按会员价转换
        // $isVIp=$this->isVip;
        // $send_card=Card::where(['cart_id'=>$cartId])->find();
        // if($send_card['from_uid']){
        //     $isVip= \app\ebapi\model\member\MemberShip::memberExpiration($send_card['from_uid'])==false;
        // }

        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'card_exchange',$this->isVip);
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];
        // $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        $priceGroup = [
            'storePostage'=>0,
            'storeFreePostage'=>0,
            'totalPrice'=>0,
            'costPrice'=>0,
            'vipPrice'=>0,
            'orderPrice'=>0
        ];
        
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
        //价格设置0
        $data['priceGroup']['vipPrice']=$priceGroup['orderPrice'];//优惠价格设置为成本价格一样
        $data['priceGroup']['totalPrice']=0;
        return JsonService::successful($data);
    }

     /**
     * @api {post} /ebapi/card_api/exchange_create_order_new 兑换流程3.创建订单
     * @apiName exchange_create_order_new
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {number} midkey midkey
     * @apiParam {number} addressId=1 addressId
     * @apiParam {number} mark mark
     */
    public function exchange_create_order_new(){
        list($midkey,$addressId, $mark) = UtilService::postMore([
            'midkey','addressId', 'mark'
       ], Request::instance(), true);
       $couponId=null;
       $useIntegral=null;
       $combinationId=0;
       $pinkId=0;
       $seckill_id=0;
       $formId='';
       $bargainId='';
       $is_zg='';
       $score_num=0;

       if (!$midkey) return JsonService::fail('参数错误!');

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
    //    $order = StoreOrder::cacheKeyCreateOrderNew($this->userInfo['uid'], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$is_zg,$score_num);       
       $order = StoreOrder::cacheKeyCreateOrderExchange($this->userInfo['uid'], $midkey, $addressId, $useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId,$is_zg,$score_num);
       $orderId = $order['order_id'];
       $info = compact('orderId', 'midkey');
       if ($orderId) {
           RoutineFormId::SetFormId($formId, $this->uid);
        //    Gong::actionadd('goumaishangpin','store_order','uid');//行为加分
           //                RoutineTemplate::sendOrderSuccess($formId,$orderId);//发送模板消息
           //操作card
           return JsonService::status('success', '订单创建成功', $info);
       } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }

    /**
     * @api {get} /ebapi/card_api/send_history 收送记录
     * @apiName send_history
     * @apiGroup Card
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     * @apiParam {number} status 状态（send recieve）
     */
    public function send_history($status){
        $uid=get_uid();
        $res= CardSend::send_history($status,$uid);
        if($res){
            foreach($res as &$item){
                $add_time= $item['add_time'];
                if($add_time==null){
                    $item['add_time']='无';
                }else{
                    $item['add_time']=date('Y年m月d日',$item['add_time']);
                }
                if($item['nickname']==null){
                    $item['nickname']='无';
                }

                $history_list=CardSend::card_send_history($item['card_id']);
                foreach($history_list as &$item_history){
                    if($item_history['add_time']==null){
                        $item_history['add_time'] = '无';
                    }else{
                        $item_history['add_time'] = date('Y年m月d日',$item_history['add_time']);
                    }
                    $status_name='无';
                    switch($item_history['status']){
                        case 0:
                            $status_name='赠送中';
                            break;
                        case 1:
                            $status_name='已领取';
                            break;
                        case -1:
                            $status_name='已撤销';
                            break;
                    }
                    $item_history['status_name']=$status_name;
                }
                $item['card_send_history']=$history_list;
            }
        }
        
        return JsonService::successful($res);
    }





}  