<?php

namespace app\ebapi\controller;
use app\core\model\routine\RoutineFormId;//待完善
use app\commonapi\model\Gong;
use app\admin\controller\trial\TrialActive;
use app\admin\controller\widget\StroeProduct;
use app\core\util\SystemConfigService;
use app\core\model\UserLevel;
use app\ebapi\model\store\StoreProduct;
use app\ebapi\model\store\StoreProductAttr;
use app\ebapi\model\trial\TrialField;
use app\ebapi\model\trial\TrialActive as EventModel;
use app\ebapi\model\trial\TrialEnroller;
use app\admin\model\trial\TrialMessage;
use think\Request;
use app\admin\model\system\SystemConfig;
use app\admin\model\trial\TrialActive as TrialTrialActive;
use app\ebapi\model\trial\TrialActive as ModelTrialTrialActive;
use service\JsonService;
use think\Cache;
use service\UtilService;

use app\ebapi\model\store\StoreCouponUser;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreProductAttrValue;
use app\ebapi\model\store\StoreCart;
use app\ebapi\model\store\StorePink;
use app\ebapi\model\store\StoreBargainUser;
use app\ebapi\model\store\StoreBargainUserHelp;
use app\wap\model\store\StoreOrder as StoreStoreOrder;
use think\Db;
class TrialApi extends AuthApi
{
    public static function whiteList()
    {
        return [
            'time_out_trial',
            'test'
        ];
    }

    //活动过期，或者活动结束
    public function time_out_trial()
    {
        //查询所有需要完成的订单
        $time = time();

        $map['end_time'] = array('<=', $time);
        $map['finish_status'] = 0;
        // $activeData=db('trial_active')->where($map);
        $enrollData = Db::view('trial_active', 'start_time,end_time')
            ->view('trial_enroller', 'id,event_id,finish_time,status', 'trial_active.id=trial_enroller.event_id')
            ->where($map)
            ->select();
        foreach ($enrollData as $item) {
            //
            //发送模板消息

            //默认设置完成时间
            $time=time();
            $updateData = [
                'finish_time' => $time,
                'finish_status' => 1
            ];
            if ($item['status'] != 2) {
                $updateData['status'] = -1;
            }

            $where['id'] = $item['id'];
            $where['finish_status'] = 0;

            db('trial_enroller')->where($where)->update(
                $updateData
            );

        }

        echo(count($enrollData));
    }

    /**
     * 获取试用产品领取详情
     */
    private function get_product_detail($id)
    {
        if (!$id || !($storeInfo = StoreProduct::getValidProduct($id))) return JsonService::fail('商品不存在或已下架');
        if ($storeInfo == '该商品已下架！') return JsonService::fail('商品不存在或已下架');
        // $storeInfo['userCollect'] = StoreProductRelation::isProductRelation($id,$this->userInfo['uid'],'collect');
        list($productAttr, $productValue) = StoreProductAttr::getProductAttrDetail($id);
        setView($this->userInfo['uid'], $id, $storeInfo['cate_id'], 'viwe');
        $data['storeInfo'] = StoreProduct::setLevelPrice($storeInfo, $this->uid, true);
        $data['storeInfo']["price"] = 0;
        //$data['similarity'] = StoreProduct::cateIdBySimilarityProduct($storeInfo['cate_id'],'id,store_name,image,price,sales,ficti',4);
        $data['productAttr'] = $productAttr;
        $data['productValue'] = $productValue;
        $data['priceName'] = StoreProduct::getPacketPrice($storeInfo, $productValue);
        //  $data['trialActive']=
        $data['mer_id'] = StoreProduct::where('id', $storeInfo['id'])->value('mer_id');
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            StoreProduct::setInc_bow($id);
        }
        return $data;
    }
 

    /**
     * 获取活动列表
     */
    // public function get_event_list(){

        
    //     $cate_id=osx_input('cate_id',0,'intval');
    //     $price=osx_input('price');
    //     $order_type=osx_input('order_type');
    //     $page=osx_input('page',0,'intval');
    //     $limit=osx_input('limit',0,'intval');
    //     $search=osx_input('search');
    //     $map['status']=1;
    //     if($cate_id){
    //         $map['cate_id|cate_pid']=$cate_id;
    //     }
    //     if($price=='pay'){
    //         $map['price_type']=['gt',0];
    //     }elseif($price=='free'){
    //         $map['price_type']=0;
    //     }
    //     switch ($order_type){
    //         case 'time': $order='create_time desc';break;
    //         case 'view': $order='view desc';break;
    //         case 'enroll': $order='enroll_reality_count desc';break;
    //         default:$order='is_recommend desc,create_time desc';
    //     }
    //     if($search){
    //         $map['title|content']=['like','%'.$search.'%'];
    //     }

    //     $data=EventModel::get_event_list($map,$page,$limit,$order);
    //     $this->apiSuccess($data);
    // }
 
    /**
     * 领取0元试用商品
     */
     public  function now_buy(){
         try{
            $this->checkBuyAuth();      
         }
         catch(\Exception $e)
         {
            return JsonService::fail($e->getMessage());
         }

         $productId=osx_input('productId','','text');
         $id=osx_input('id',0,'intval');
         $cartNum=1;
 
         if (!$productId || !is_numeric($productId)) return JsonService::fail('参数错误');

               // $res=TrialEnroller::update([],['event_id'=>$id,'draw_status'=>0,'uid'=>$this->uid])->result;        
            // if($res==0)
            // {
            //     return JsonService::fail('请不要重复领取提交!'); 
            // }
                
            $map=['event_id'=>$id,'uid'=>$this->uid];
            $trial_enroller=TrialEnroller::where($map)->find();
 
            if($trial_enroller!=null) 
            {
                if($trial_enroller['draw_status']==1)
                {
                    return JsonService::fail('请不要重复领取提交!'); 
                }

                $event=EventModel::getEvent($id);
                if($event['is_stop_draw'])
                {
                    $msg=$event['is_stop_draw_reson'];
                    return JsonService::fail($msg); 
                }
              
            }
        
         $res = StoreCart::setCart($this->userInfo['uid'], $productId, $cartNum,'', 'trial_product', 1, 0, 0,0,'now_buy',$id);
         if (!$res->result) return JsonService::fail(StoreCart::getErrorInfo());
         else  return JsonService::successful('ok', ['cartId' => $res->id]);

 
    }
 

    //判断购买权限
    private function checkBuyAuth()
    {        
        //获取购买码,判断购买码是否是自己的
        $id=osx_input('id',0,'intval');
        $enrollData=TrialEnroller::get(['event_id'=>$id,'uid'=>$this->uid]);
     
        if ($enrollData==null) 
        {    
            throw new \Exception('请提交您参与的活动申请');            
        }
 
    }

     /**
     * 获取我的试用
     * @param string $type
     * @param int $first
     * @param int $limit
     * @param string $search
     * @return \think\response\Json
     */
    public function get_user_order_list()
    {
        list($type,$page,$limit,$search)=UtilService::getMore([
            ['type',''],
            ['page',''],
            ['limit',''],
            ['search',''],
        ],$this->request,true);
        $res= TrialEnroller::get_order_list(['status'=>$type,'uid'=>$this->uid],$page,$limit,$search);

     
        return JsonService::successful($res);
    }


    //确认订单
    public function confirm_order(Request $request)
    {
        $data = UtilService::postMore(['cartId'], $request);
        $cartId = $data['cartId'];
        if (!is_string($cartId) || !$cartId) return JsonService::fail('请提交购买的商品!');
        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['uid'], $cartId, 1,'trial_product');
        if (count($cartGroup['invalid'])) return JsonService::fail($cartGroup['invalid'][0]['productInfo']['store_name'] . '已失效!');
        if (!$cartGroup['valid']) return JsonService::fail('请提交购买的商品!!');
        $cartInfo = $cartGroup['valid'];
        $trial_id='';
        $carArr=[];
        //动态修改价格
        foreach($cartInfo as $car)
        {
            if($this->isVip)
            {
                $car['truePrice']=0;
                $car['costPrice']=0;
            }

            $trial_id=$car['trial_id'];
            array_push($carArr,$car);
        }

        $priceGroup = StoreOrder::getOrderPriceGroup($carArr);
        $other = [
            'offlinePostage' => SystemConfigService::get('offline_postage'),
            'integralRatio' => SystemConfigService::get('integral_ratio')
        ];

        $trial=EventModel::get($trial_id);
        
 
        if($trial['is_vip_postage'])//如果活动是包邮的活动
        {
            if($this->isVip)//如果是会员用户
            {
                $priceGroup['storeFreePostage']=0;
                $priceGroup['storePostage']=0.00;
                
            }
        }
     
 
        $priceGroup['totalPrice']=0.00;
        $data['usableCoupon'] = null;
        $data['seckill_id'] = 0;
        $data['cartInfo'] = $cartInfo;
        $data['priceGroup'] = $priceGroup;
        $data['orderKey'] = StoreOrder::cacheOrderInfo($this->userInfo['uid'], $cartInfo, $priceGroup, $other);
        $data['offlinePostage'] = $other['offlinePostage'];
        $data['isVip']=$this->isVip;
        

        $data['userInfo']=$this->userInfo;
        $data['integralRatio'] = $other['integralRatio'];
        return JsonService::successful($data);
    }

    /**
     * 创建订单
     * @param string $key
     * @return \think\response\Json
     */
    public function create_order_new()
    { 
        try {
            $this->checkBuyAuth();  
        }
        catch(\Exception $e)
        {
            return JsonService::fail($e->getMessage()); 
        } 


        list($midkey,$addressId) = UtilService::postMore([
             'midkey','addressId'  
        ], Request::instance(), true);
        if (!$midkey) return JsonService::fail('参数错误!');

        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $midkey=trim(openssl_decrypt(base64_decode($midkey),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));

        if (StoreOrder::be(['order_id|unique' => $midkey, 'uid' => $this->userInfo['uid'], 'is_del' => 0]))
            return JsonService::status('extend_order', '该订单已生成', ['orderId' => $midkey, 'key' => $midkey]);
 
      
        $order = StoreOrder::cacheKeyCreateOrderNew($this->userInfo['uid'], $midkey, $addressId, 0, 0, '试用订单', 0, 0, 0, '',0,0,10);
        $orderId = $order['order_id'];
        $isVip=$this->isVip;
        $info = compact('orderId', 'midkey','isVip');
        if ($orderId) {
        
            //更新领取状态
            $id=osx_input('id',0,'intval');
            // $res=TrialEnroller::update([],['event_id'=>$id,'draw_status'=>0,'uid'=>$this->uid])->result;        
            // if($res==0)
            // {
            //     return JsonService::fail('请不要重复领取提交!'); 
            // }
                
            $order= StoreOrder::get(['order_id'=>$orderId]);
            if($order['pay_price']==0)//如果支付价格=0 那么这个订单就是会员免运费的订单，直接设置为支付成功状态；
            {
                \app\ebapi\model\store\StoreOrder::payVipTrialSuccess($orderId);
            }

            $enrollData=TrialEnroller::get(['event_id'=>$id,'uid'=>$this->uid]);
            //关联领取后的订单信息
            TrialEnroller::update(['order_id'=>$orderId,'draw_status'=>1,'draw_time'=>time()],['id'=>$enrollData['id']]);

            return JsonService::status('success', '订单创建成功', $info);
        } else return JsonService::fail(StoreOrder::getErrorInfo('订单生成失败!'));
    }


    /**
     * 获取领取页面活动详情 
     * @param string $id
     * @return \think\response\Json
     */
    private function get_event_detail($id)
    {
        $eventData=EventModel::getEvent($id);       
        $productData=$this->get_product_detail($eventData['product_id']);

        $storeData=$productData['storeInfo'];

        $res['isVip']=$this->isVip;
        if($res['isVip'])
        {
            if($eventData['is_vip_postage'])
            {
                $storeData['postage']=0;
            }
            
        }

        $res['product']=[
            'store_name'=>$storeData['store_name'],
            'id'=>$storeData['id'],
            'slider_image'=>$storeData['slider_image'],
            'image'=>$storeData['id'],
            'image_150'=>$storeData['image_150'],
            'image_350'=>$storeData['image_350'],
            'image_750'=>$storeData['image_750'],
            'store_info'=>$storeData['store_info'],
            'price'=>$storeData['price'],
            'ot_price'=>0,
            'unit_name'=>$storeData['unit_name'],            
            'postage'=>$storeData['postage'],
            'buy_num'=>$storeData['buy_num'],//限购数,      
            'description'=>$storeData['description']     ,
        ];
        $res['event']=$eventData;
        return $res;
    }

    /**
     * 获取活动详情页
     */
    public function get_event(){
        $id=osx_input('id',0,'intval');
        if(!$id){
            $this->apiError(['info'=>'请选择查看的活动']);
        }
        $status=EventModel::where(['id'=>$id])->value('status');
        if($status==-1){
            $this->apiError(['info'=>'该活动已经被删除']);
        }

        
        $res=$this->get_event_detail($id);
        
        $this->apiSuccess($res);
    }

      /**
     * 提交报名，并返回 报名活动需要填写的内容
     */
    public function enroll_event(){
        $id=osx_input('id',0,'intval');
        $status=EventModel::where(['id'=>$id])->value('status');
        if($status==-1){
            $this->apiError(['info'=>'该活动已经被删除']);
        }
        if($status==0){
            $this->apiError(['info'=>'该活动已经被取消不能报名']);
        }
        $uid=$this->_needLogin();
        $res=TrialEnroller::add_enroll($uid,$id);
        if($res){
            $datum=TrialField::get_event_datum($id);
          
            $data['datum']=$datum;
            $flag=SystemConfig::getValue('event_type_pay');
            $event=$this->get_event_detail($id);
            $data['event']=$event['event'];
            $data['product']=$event['product'];
      
            $this->apiSuccess($data);
        }else{
            $this->apiError(['status'=>0,'info'=>'报名信息填写失败']);
        }
    }



    /**
     * 提交报名信息
     */
    public function enroll(){
        $id=osx_input('event_id',0,'intval');
        //初始判断上限
        $event=db('trial_active')->where(['id'=>$id])->field('id,status,enroll_count,enroll_reality_count,forum_id,price,price_type,enroll_range,enroll_start_time,enroll_end_time')->cache('event_enroll_count_'.$id)->find();
        if($event['status']!=1){
            $this->apiError( ['status'=>0,'info'=>'该活动已经删除或者已经取消，无法报名']);
        }
        if($event['enroll_end_time']<time()){
            $this->apiError( ['status'=>0,'info'=>'活动报名已结束']);
        }
        if($event['enroll_start_time']>time()){
            $this->apiError( ['status'=>0,'info'=>'活动还未开始报名']);
        }
        $uid=$this->_needLogin();
        $event['enroll_reality_count']+=1;
        Cache::set('trial_enroll_count_'.$id,$event);

        if($event['enroll_reality_count']>$event['enroll_count']&&$event['enroll_count']!=0){
            $this->apiError(['status'=>0,'info'=>'很遗憾,报名人数已满。']);
        }

        $datum=TrialField::getEventField($id);

        $enroll=TrialEnroller::where(['uid'=>$uid,'event_id'=>$id])->find();
        if(!$enroll){
            $event['enroll_reality_count']-=1;
            Cache::set('trial_enroll_count_'.$id,$event);
            $this->apiError(['status'=>0,'info'=>'未产生报名信息']);
        }
        if($enroll['status']>=1){
            $event['enroll_reality_count']-=1;
            Cache::set('event_enroll_count_'.$id,$event);
            $this->apiError(['status'=>0,'info'=>'活动已经报名成功，请勿重复提交']);
        }

        $data=[];
        foreach ($datum as $v){
            $value['content']=osx_input($v);
            if(empty($value['content'])){
                $this->apiError(['info'=>'请将报名信息填写完整']);
            }
            $value['field']=$v;
            $value['event_id']=$id;
            $value['uid']=$uid;
            $value['status']=1;
            $value['create_time']=time();
            $data[]=$value;
        }
        $res=db('trial_enroller_info')->insertAll($data);
        if($res){
            $result=TrialEnroller::enroll_info($id,$uid);
            if($result['status']==1){
                TrialMessage::send_message(56,$id,$uid);
                $this->apiSuccess($result);
            }else{
                $this->apiError($result);
            }
        }else{
            $event['enroll_reality_count']-=1;
            Cache::set('trial_enroll_count_'.$id,$event);
            $this->apiError(['info'=>'报名信息存储错误']);
        }
    }

}