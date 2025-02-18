<?php
namespace app\ebapi\controller;

use Api\Express;
use app\admin\controller\user\MemberCouponPlan;
use app\admin\model\order\StoreOrder as OrderStoreOrder;
use app\admin\model\system\SystemConfig;
use app\core\model\UserLevel;
use app\core\model\UserSign;
use app\core\model\routine\RoutineCode;//待完善
use app\core\model\routine\RoutineFormId;//待完善
use app\ebapi\model\store\StoreBargain;
use app\ebapi\model\store\StoreCombination;
use app\ebapi\model\store\StoreCouponUser;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreOrderCartInfo;
use app\ebapi\model\store\StoreProductRelation;
use app\ebapi\model\store\StoreProductReply;
use app\ebapi\model\store\StoreSeckill;
use app\ebapi\model\user\User;
use app\ebapi\model\user\UserAddress;
use app\core\model\UserBill;//待完善
use app\ebapi\model\user\UserExtract;
use app\ebapi\model\user\UserNotice;
use app\ebapi\model\user\UserRecharge;
use service\CacheService;
use app\core\util\GroupDataService;
use service\JsonService;
use app\core\util\SystemConfigService;
use service\UtilService;
use think\Request;
use think\Cache;
use app\commonapi\controller\Sensitive;
use app\commonapi\model\Gong;
use app\admin\model\order\StoreOrderStatus;
use app\admin\model\user\MemberCouponPlan as UserMemberCouponPlan;
use app\commonapi\model\share\ShareLink;
use app\osapi\model\event\Event as EventModel;

/**
 * 小程序个人中心api接口
 * Class UserApi
 * @package app\routine\controller
 *
 */
class UserApi extends AuthController
{

    public static function whiteList()
    {
        return [
            'userCard',
        ];
    }

    /*
     * 获取签到按月份查找
     * @return json
     * */
    public function get_sign_month_list()
    {
        $page=osx_input('page',1,'intval');//页码
        $limit=osx_input('limit',10,'intval');//显示条数
        return JsonService::successful(UserSign::getSignMonthList($this->uid,$page,$limit));
    }
    /*
     * 获取用户签到记录列表
     *
     * */
    public function get_sign_list()
    {
        $page=osx_input('page',1,'intval');//页码
        $limit=osx_input('limit',10,'intval');//显示条数
        return JsonService::successful(UserSign::getSignList($this->uid,$page,$limit));
    }
    /*
     * 获取当前登录的用户信息
     * */
    public function get_my_user_info()
    {
        list($isSgin,$isIntegral,$isall)=UtilService::getMore([
            ['isSgin',0],
            ['isIntegral',0],
            ['isall',0],
        ],$this->request,true);
        //是否统计签到
        if($isSgin || $isall){
            $this->userInfo['sum_sgin_day']=UserSign::getSignSumDay($this->uid);
            $this->userInfo['is_day_sgin']=UserSign::getToDayIsSign($this->uid);
            $this->userInfo['is_YesterDay_sgin']=UserSign::getYesterDayIsSign($this->uid);
            if(!$this->userInfo['is_day_sgin'] && !$this->userInfo['is_YesterDay_sgin']){
                $this->userInfo['sign_num']=0;
            }
        }
        //是否统计积分使用情况
        if($isIntegral || $isall){
            $this->userInfo['sum_integral']=(int)UserBill::getRecordCount($this->uid,'integral','sign,system_add,gain');
            $this->userInfo['deduction_integral']=(int)UserBill::getRecordCount($this->uid,'integral','deduction') ? : 0;
            $this->userInfo['today_integral']=(int)UserBill::getRecordCount($this->uid,'integral','sign,system_add,gain','today');
        }
        unset($this->userInfo['pwd']);
        $this->userInfo['integral']=(int)$this->userInfo['integral'];
        return JsonService::successful($this->userInfo);
    }
    /**
     * 获取用户信息
     * @param int $userId 用户uid
     * @return \think\response\Json
     */
    public function get_user_info_uid(){
        $userId=osx_input('userId',0,'intval');//用户uid
        if(!$userId) return JsonService::fail('参数错误');
        $res = User::getUserInfo($userId);
        if($res) return JsonService::successful($res);
        else return JsonService::fail(User::getErrorInfo());
    }
    
    public function my(){
        $this->userInfo['couponCount'] = StoreCouponUser::getUserValidCouponCount($this->userInfo['uid']);
        $this->userInfo['like'] = StoreProductRelation::getUserIdCollect($this->userInfo['uid']);;
        $this->userInfo['orderStatusNum'] = StoreOrder::getOrderStatusNum($this->userInfo['uid']);
        $this->userInfo['notice'] = UserNotice::getNotice($this->userInfo['uid']);
        $this->userInfo['brokerage'] = UserBill::getBrokerage($this->uid);//获取总佣金
        $this->userInfo['recharge'] = UserBill::getRecharge($this->uid);//累计充值
        $this->userInfo['orderStatusSum'] = StoreOrder::getOrderStatusSum($this->uid);//累计消费
        $this->userInfo['extractTotalPrice'] = UserExtract::userExtractTotalPrice($this->uid);//累计提现
        $this->userInfo['extractPrice'] = (float)bcsub($this->userInfo['brokerage'],$this->userInfo['extractTotalPrice'],2) > 0 ? : 0;//可提现
        $this->userInfo['statu'] = (int)SystemConfigService::get('store_brokerage_statu');
        $vipId=UserLevel::getUserLevel($this->uid);
        $this->userInfo['vip']=$vipId !==false ? true : false;
        if($this->userInfo['vip']){
            $this->userInfo['vip_id']=$vipId;
            $this->userInfo['vip_icon']=UserLevel::getUserLevelInfo($vipId,'icon');
            $this->userInfo['vip_name']=UserLevel::getUserLevelInfo($vipId,'name');
        }
        unset($this->userInfo['pwd']);
        return JsonService::successful($this->userInfo);
    }
    /**
     * @api {post} /ebapi/user_api/getOrderStatusNum 订单状态数据
     * @apiName getOrderStatusNum
     * @apiGroup UserApi
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==
     * @apiHeader {string} KF=cp
     */
    public function getOrderStatusNum(){
        $res=StoreOrder::getOrderStatusNum($this->userInfo['uid']);
        return JsonService::successful($res);
    }

    /**
     * 用户签到
     * @return \think\response\Json
     */
    public function user_sign()
    {
        $signed = UserSign::getToDayIsSign($this->userInfo['uid']);
        if($signed) return JsonService::fail('已签到');
        if(false !== $integral = UserSign::sign($this->uid))
            return JsonService::successful('签到获得'.floatval($integral).'积分',['integral'=>$integral]);
        else
            return JsonService::fail(UserSign::getErrorInfo('签到失败'));
    }

    /**
     * 获取一条用户地址
     * @return \think\response\Json
     */
    public function get_user_address(){
        $addressId=osx_input('addressId','','text');//地址id
        $addressInfo = [];
        if($addressId && is_numeric($addressId) && UserAddress::be(['is_del'=>0,'id'=>$addressId,'uid'=>$this->userInfo['uid']])){
            $addressInfo = UserAddress::find($addressId);
        }
        return JsonService::successful($addressInfo);
    }

    /**
     * 获取默认地址
     * @return \think\response\Json
     */
    public function user_default_address()
    {
        $defaultAddress = UserAddress::getUserDefaultAddress($this->userInfo['uid'],'id,real_name,phone,province,city,district,detail,is_default',
        osx_input('limit_province',null,'text'),
        osx_input('limit_city',null,'text'),
        osx_input('limit_area',null,'text')
        // osx_input('addressId','','text')
    
    );
        if($defaultAddress) return JsonService::successful('ok',$defaultAddress);
        else return JsonService::successful('empty',[]);
    }

    /**
     * 删除地址
     * @return \think\response\Json
     */
    public function remove_user_address()
    {
        $addressId=osx_input('addressId','','text');//地址id
        if(!$addressId || !is_numeric($addressId)) return JsonService::fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$addressId,'uid'=>$this->userInfo['uid']]))
            return JsonService::fail('地址不存在!');
        if(UserAddress::edit(['is_del'=>'1'],$addressId,'id'))
            return JsonService::successful();
        else
            return JsonService::fail('删除地址失败!');
    }

    /**
     * 个人中心 获取订单列表
     * @param string $type
     * @param int $first
     * @param int $limit
     * @param string $search
     * @return \think\response\Json
     */
    /**
     * @api {post} /ebapi/user_api/get_recieve_order_list 花间一壶酒活动-领酒流程1.我的领取列表
     * @apiName get_recieve_order_list
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} status 状态 1待领取 2已领取 -1已过期
     * @apiParam {int} page 页码
     * @apiParam {int} limit 条数
     */
    public function get_recieve_order_list(){        
        list($status,$page,$limit,$search)=UtilService::getMore([
            ['status',''],
            ['page',''],
            ['limit',''],
            ['search',''],
        ],$this->request,true);
        return JsonService::successful(StoreOrder::getRecieveOrderList($this->uid,$status,$page,$limit,$search));
    }

    /**
     * @api {post} /ebapi/user_api/recieve_order_detail 花间一壶酒活动-领酒流程2.待领取详情
     * @apiName recieve_order_detail
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} uni 订单id
     */
    public function recieve_order_detail(){
        $uni=osx_input('uni','','text');
        if($uni == '') return JsonService::fail('参数错误');
        $uid=$this->_needLogin();
        $order = StoreOrder::getUserOrderDetail($uid,$uni);
        if(!$order) return JsonService::fail('订单不存在');
        if($order!=null){
            $order = $order->toArray();
        }
        // if($order['status']!=1)return JsonService::fail('订单不能使用');
        $uid=get_uid();
        
        $data=StoreOrder::tidyRecieveOrder($order,true);

        //活动地址
        $event=EventModel::getEvent($order['event_id']);
        $data['recieve_address']=$event['detailed_address'];
        
        //二维码开始
        $code_url=get_domain().'/event_recieve?code=';
        require_once ROOT.'/vendor/phpqrcode/phpqrcode.php';
        $qrcode=new \QRcode();
        $event_code=$data['recieve_code'];
        // if($invite_show==1){
            $code_url = $code_url.$event_code;//二维码内容
            $code_url =ShareLink::gen_share_link($uid,'/packageA/activity/verification?type=order&code='.$event_code,'event_recieve');
        // }
        $errorCorrectionLevel = 'H';    //容错级别
        $matrixPointSize = 7;           //生成二维码图片大小

        $thumb_dir_path=UPLOAD_PATH.'/event_recieve/'.$uid.'/';
        if (!is_dir($thumb_dir_path)){
            mkdir($thumb_dir_path,0777,true);
        }

        $qrcode_file_name=$thumb_dir_path.'thumb_qr_code'.$event_code.'.png';
        ob_start();
        $qrcode::png($code_url,$qrcode_file_name , $errorCorrectionLevel, $matrixPointSize, 2);
        ob_end_clean();//关闭缓冲区
        $qrcode_image = getThumbImage($qrcode_file_name,300,300);
       // $qCodeImg =imagecreatefromstring(file_get_contents($qrcode_image['src']));
        //unlink($qrcode_file_name);
       // if($qrcode_file_name!=$qrcode_image['src']){
           // unlink($qrcode_image['src']);
        //}
        //生成二维码 end
        $data['qrcode']=get_root_path($qrcode_image['src'].'?'.time(),true);
        $data['user_phone']=$order['user_phone'];
        $data['user_address']=$order['user_address'];
        $data['real_name']=$order['real_name'];
        $goods=OrderStoreOrder::get_goods_name_by_oid($order['id']);
        $data['goods_name'] =$goods['goods_name'];
        $data['truePrice'] =$goods['true_price'];
        //二维码结束
        // $event= EventModel::where('id',$order['event_id'])->find();
        // $data['address']=$event['detailed_address'];//详细地址

        return JsonService::successful($data);
    }


      /**
     * @api {post} /ebapi/user_api/inspect_code 花间一壶酒活动-领酒流程3.检查核销码是否有用
     * @apiName 检查核销码是否有用
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} code 核销码
     */
    public function inspect_code(){
        $code=osx_input('code');
        $order=StoreOrder::where(['recieve_code'=>$code,'status'=>1])->find();
        $uid=get_uid();
        if(!$order){
            $this->apiError(['info'=>'不存在该核销码','error_code'=>0]);
        }
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$order['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能查看','error_code'=>1]);
        }

        if($order['recieve_time']>0){
            $this->apiError(['info'=>'该核销码已经核销']);
        }
        $this->apiSuccess(['info'=>'核销码有用']);
    }

      /**
     * @api {post} /ebapi/user_api/get_check_list 花间一壶酒活动-领酒流程3.核销记录
     * @apiName 核销记录
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} page 页码
     * @apiParam {varchar} limit 每页条数
     */
    public function get_check_list(){
        list($page,$limit)=UtilService::getMore([
            ['page',''],
            ['limit',''],
        ],$this->request,true);
        $uid=get_uid();
        $res=StoreOrder::getUserCheckList($uid,$page,$limit);
        return JsonService::successful($res);
    }

      /**
     * @api {post} /ebapi/user_api/check_code 花间一壶酒活动-领酒流程3.审核人员确认核销页面
     * @apiName 审核人员确认核销页面
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} code 核销码
     */
    public function check_code(){
        $code=osx_input('code');
        $uid=$this->_needLogin();
        $order=StoreOrder::where(['recieve_code'=>$code,'status'=>1])->find();
       
        if(!$order){
            $this->apiError(['info'=>'不存在该核销码']);
        }
        $event=EventModel::getEvent($order['event_id']);
        if(!$event) $this->apiError(['info'=>'活动不存在']);
        $order['recieve_address']=$event['detailed_address'];
        StoreOrder::update(['recieve_address'=>$event['detailed_address']],['recieve_code'=>$code,'status'=>1]);//修改领取地址
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$order['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能查看']);
        }
        $check_user=db('user')->where(['uid'=>$uid])->field('nickname,avatar,uid')->find();

        $data=StoreOrder::tidyRecieveOrder($order,true);
        //身份证号
        // $data['sfzh']=db('event_enroller_info')->where(['uid'=>$order['uid'],'event_id'=>$event['id'],'field'=>'sfzh'])->value('content');
        $data['check_user']=$check_user;//审核人员
        $data['order_user']=db('user')->where(['uid'=>$order['uid']])->field('nickname,avatar,uid')->find();

  
        $this->apiSuccess($data);
    }

     /**
     * @api {post} /ebapi/user_api/sure_check_code 花间一壶酒活动-领酒流程4.领酒核销
     * @apiName 领酒核销
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} code 核销码
     */
    public function sure_check_code(){
        $code=osx_input('code');
        $uid=$this->_needLogin();
        $order=StoreOrder::where(['recieve_code'=>$code,'status'=>1])->find();
        if(!$order){
            $this->apiError(['info'=>'不存在该核销码']);
        }
        if($order['recieve_time']>0){
            $this->apiError(['info'=>'该核销码已经核销']);
        }
        if($order['status']==-1){
            $this->apiError(['info'=>'该订单已过期']);
        }
        $order_uid=$order['uid'];
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$order['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能进行核销']);
        }
        $res=StoreOrder::where(['recieve_code'=>$code,'status'=>1])->update(['check_uid'=>$uid,'recieve_time'=>time(),'status'=>3]); //status3 已完成
        if($res){
            db('event')->where(['id'=>$order['event_id']])->setInc('check_count',1);
            $order_status=StoreOrderStatus::setStatus($order['id'],'recieve_sure_check_sucess','领酒核销成功');
            \app\core\util\HookService::afterListen('user_order_sure_check',$order,$order['order_id'],false,\app\core\behavior\OrderBehavior::class);
            UserMemberCouponPlan::set_fail_state($order_uid);
            UserMemberCouponPlan::grant_coupon($order_uid);
            $this->apiSuccess(['info'=>'核销成功']);
        }else{
            $this->apiError(['info'=>'核销失败']);
        }
    }

    public function get_user_order_list()
    {
        list($type,$page,$limit,$search)=UtilService::getMore([
            ['type',''],
            ['page',''],
            ['limit',''],
            ['search',''],
        ],$this->request,true);
        return JsonService::successful(StoreOrder::getUserOrderSearchList($this->uid,$type,$page,$limit,$search));
    }

    public function get_user_order_list_zg()
    {
        list($type,$page,$limit,$search)=UtilService::getMore([
            ['type',''],
            ['page',''],
            ['limit',''],
            ['search',''],
        ],$this->request,true);
        return JsonService::successful(StoreOrder::getUserOrderSearchListZg($this->uid,$type,$page,$limit,$search));
    }

    /**
     * 个人中心 订单详情页
     * @return \think\response\Json
     */
    public function get_order(){
        $uni=osx_input('uni','','text');
        if($uni == '') return JsonService::fail('参数错误');
        $order = StoreOrder::getUserOrderDetail($this->userInfo['uid'],$uni);
        if($order!=null){
            $order = $order->toArray();
        }
        $out_time=SystemConfig::getValue('close_order_time');
       

        $receiving_time=SystemConfig::getValue('receiving_goods_time');
        $time=time()-$out_time*3600;
    
        if($order['paid']==0 && $order['add_time']<$time){
            StoreOrder::cancelOrder($order['order_id']);
            JsonService::fail('订单已超时，已自动取消订单');
        }


        $score_cash=abs(SystemConfig::getValue('score_cash'));
        $order['score_num_pay']=$order['score_num']*$score_cash;
        $order['add_time_y'] = date('Y-m-d',$order['add_time']);
        $order['add_time_h'] = date('H:i:s',$order['add_time']);
        $people = db('store_pink')->where('id',$order['pink_id'])->value('people');
        $people_all = db('store_pink')->where('k_id',$order['pink_id'])->count();
        $people_all=$people_all+1;
        $all=$people-$people_all;
        if($all==0){
            $order['is_pink_success']=1;
        }else{
            $order['is_pink_success']=0;
        }
        //判断是否是秒杀商品
        if($order['seckill_id']){
            $seckill=StoreSeckill::getValidProduct($order['seckill_id']);
            $order['seckill_id']=$seckill['id'];
            $order['stop_time']=$seckill['stop_time'];
            $order['product_id']=$seckill['product_id'];
        }

        if($order['status']==0){
            $order['out_time']=$out_time*3600+$order['add_time']-time();
            $order['out_time']=$order['out_time']>0?$order['out_time']:0;
        }
        if($order['status']==2){
            $order['receiving_time']=$receiving_time*24*3600+$order['delivery_time']-time();
            $order['receiving_time']=$order['receiving_time']>0?$order['receiving_time']:0;
        }

        if(!$order) return JsonService::fail('订单不存在');
        return JsonService::successful(StoreOrder::tidyOrder($order,true,true));
    }

    /**
     * 个人中心 删除订单
     * @return \think\response\Json
     */
    public function user_remove_order()
    {
        $uni=osx_input('uni','','text');
        if(!$uni) return JsonService::fail('参数错误!');
        $res = StoreOrder::removeOrder($uni,$this->userInfo['uid']);
        if($res)
            return JsonService::successful();
        else
            return JsonService::fail(StoreOrder::getErrorInfo());
    }

    /**
     * 获取用户手机号码
     * @param Request $request
     * @return \think\response\Json
     */
    /**
     * @api {post} /ebapi/user_api/bind_mobile 花间一壶酒活动-绑定手机
     * @apiName bind_mobile
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} iv iv
     * @apiParam {varchar} encryptedData encryptedData
     */
    public function bind_mobile(Request $request){
        list($iv,$encryptedData) = UtilService::postMore([
            ['iv',''],
            ['encryptedData',''],
        ],$request,true);
        // $iv  = urldecode(urlencode($iv));
        try{
            // if(!Cache::has('eb_api_code_'.$cache_key)) return JsonService::fail('获取手机号失败');
            // $session_key=Cache::get('eb_api_code_'.$cache_key);
            $session_key=db('user_sync_login')->where('uid',$this->userInfo['uid'])->value('oauth_token');
            // $session_key='oGtD32Jvwkobe9Jgp2eG0Q==';
            $userInfo = \service\MiniProgramService::encryptor($session_key,$iv,$encryptedData);
            if(!empty($userInfo['purePhoneNumber'])){
                if(User::edit(['phone'=>$userInfo['purePhoneNumber']],$this->userInfo['uid']))
                    return JsonService::successful('绑定成功',['phone'=>$userInfo['purePhoneNumber']]);
                else
                    return JsonService::fail('绑定失败');
            }else
                return JsonService::fail('获取手机号失败');
        }catch (\Exception $e){
            return JsonService::fail('error',$e->getMessage());
        }
    }
    /**
     * 个人中心 用户确认收货
     * @return \think\response\Json
     */
    public function user_take_order()
    {
        $uni=osx_input('uni','','text');
        if(!$uni) return JsonService::fail('参数错误!');

        $res = StoreOrder::takeOrder($uni,$this->userInfo['uid']);
        if($res)
            return JsonService::successful();
        else
            return JsonService::fail(StoreOrder::getErrorInfo());
    }

    /**
     *  个人中心 充值
     * @return \think\response\Json
     */
    public function user_wechat_recharge()
    {
        $price=osx_input('price',0,'intval');
        if(!$price || $price <=0) return JsonService::fail('参数错误');
        $storeMinRecharge = SystemConfigService::get('store_user_min_recharge');
        if($price < $storeMinRecharge) return JsonService::fail('充值金额不能低于'.$storeMinRecharge);
        $rechargeOrder = UserRecharge::addRecharge($this->userInfo['uid'],$price);
        if(!$rechargeOrder->result) return JsonService::fail('充值订单生成失败!');
        try{
            return JsonService::successful(UserRecharge::jsPay($rechargeOrder));
        }catch (\Exception $e){
            return JsonService::fail($e->getMessage());
        }
    }

    /**
     * 个人中心 余额使用记录
     * @return \think\response\Json
     */
    public function user_balance_list()
    {
        $first=osx_input('first',0,'intval');
        $limit=osx_input('limit',8,'intval');
        return JsonService::successful(UserBill::userBillList($this->uid,$first,$limit,'now_money'));
    }

    /**
     * 个人中心 积分使用记录
     * @return \think\response\Json
     */
    public function user_integral_list()
    {
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',8,'intval');
        return JsonService::successful(UserBill::userBillList($this->uid,$page,$limit));

    }

    /**
     * 个人中心 获取一级推荐人
     * @return \think\response\Json
     */
    public function get_spread_list()
    {
        $first=osx_input('first',0,'intval');
        $limit=osx_input('limit',20,'intval');
        return JsonService::successful(User::getSpreadList($this->uid,$first,$limit));
    }

    /**
     * 个人中心 获取二级推荐人
     * @return \think\response\Json
     */
    public function get_spread_list_two()
    {
        $two_uid=osx_input('two_uid',0,'intval');
        $limit=osx_input('limit',20,'intval');
        $first=osx_input('first',0,'intval');
        return JsonService::successful(User::getSpreadList($two_uid,$first,$limit));
    }

    /**
     * 获取用户所有地址
     * @return \think\response\Json
     */
    public function user_address_list()
    {
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',8,'intval');
        $limit_province= osx_input('limit_province',null,'text');
        $limit_city=osx_input('limit_city',null,'text');
        $limit_area=osx_input('limit_area',null,'text');

        $list = UserAddress::getUserValidAddressList($this->userInfo['uid'],$page,$limit,'id,real_name,phone,province,city,district,detail,is_default',$limit_province,$limit_city,$limit_area);
        return JsonService::successful($list);
    }

    /**
     * 修改用户通知为已查看
     * @return \think\response\Json
     */
    public function see_notice()
    {
        $nid=osx_input('nid',1,'intval');
        UserNotice::seeNotice($this->userInfo['uid'],$nid);
        return JsonService::successful();
    }
    /*
     * 用户提现申请
     * @param array
     * @return \think\response\Json
     * */
    public function user_extract()
    {
        list($lists)=UtilService::postMore([['lists',[]]],$this->request,true);
        if(UserExtract::userExtract($this->userInfo,$lists))
            return JsonService::successful('申请提现成功!');
        else
            return JsonService::fail(UserExtract::getErrorInfo('提现失败'));
    }
    /**
     * 用户下级的订单
     * @return json
     */
    public function subordinateOrderlist()
    {
        $first=osx_input('post.first',0,'intval');
        $limit=osx_input('post.limit',8,'intval');
        $xUid=osx_input('post.xUid',0,'intval');
        $status=osx_input('post.status',0,'intval');
        switch ($status){
            case 0:
                $type='';
                break;
            case 1:
                $type=4;
                break;
            case 2:
                $type=3;
                break;
            default:
                return JsonService::fail();
        }
        return JsonService::successful(StoreOrder::getSubordinateOrderlist($xUid,$this->uid,$type,$first,$limit));
    }

    /**
     * 个人中心 用户下级的订单
     * @return json
     */
    public function subordinateOrderlistmoney()
    {
        $status = osx_input('status',0,'intval');
        $type = '';
        if($status == 1) $type = 4;
        elseif($status == 2) $type = 3;
        $arr = User::where('spread_uid',$this->userInfo['uid'])->column('uid');
        $list = StoreOrder::getUserOrderCount(implode(',',$arr),$type);
        $price = [];
//        if(!empty($list)) foreach ($list as $k=>$v) $price[]=$v['pay_price'];
        if(!empty($list)) foreach ($list as $k=>$v) $price[]=$v;
        $cont = count($list);
        $sum = array_sum($price);
        return JsonService::successful(['cont'=>$cont,'sum'=>$sum]);
    }

    /*
     * 用户提现记录列表
     * @return json
     */
    public function extract()
    {
        $first=osx_input('post.first',0,'intval');//截取行数
        $limit=osx_input('post.limit',8,'intval');//展示条数
        return JsonService::successful(UserExtract::extractList($this->uid,$first,$limit));
    }

    /**
     * 个人中心 订单 评价订单
     * @param string $unique
     * @return \think\response\Json
     */
    public function user_comment_product(Request $request)
    {
        $data = UtilService::postMore(['unique'], $request);
        if(!$data['unique']) return JsonService::fail('参数错误!');
        $cartInfo = StoreOrderCartInfo::where('unique',$data['unique'])->find();
        $uid = $this->userInfo['uid'];
        if(!$cartInfo || $uid != $cartInfo['cart_info']['uid']) return JsonService::fail('评价产品不存在!');
        if(StoreProductReply::be(['oid'=>$cartInfo['oid'],'unique'=>$data['unique']]))
            return JsonService::fail('该产品已评价!');
        $group = UtilService::postMore([
            ['comment',''],['pics',''],['product_score',5],['service_score',5]
        ],Request::instance());
        $group['comment'] = htmlspecialchars(trim($group['comment']));
        $group['comment']=Sensitive::sensitive($group['comment'],'商城评论');
        if($group['product_score'] < 1) return JsonService::fail('请为产品评分');
        else if($group['service_score'] < 1) return JsonService::fail('请为商家服务评分');
        if($cartInfo['cart_info']['combination_id']) $productId = $cartInfo['cart_info']['product_id'];
        else if($cartInfo['cart_info']['seckill_id']) $productId = $cartInfo['cart_info']['product_id'];
        else if($cartInfo['cart_info']['bargain_id']) $productId = $cartInfo['cart_info']['product_id'];
        else $productId = $cartInfo['product_id'];
        $group = array_merge($group,[
            'uid'=>$uid,
            'oid'=>$cartInfo['oid'],
            'unique'=>$data['unique'],
            'product_id'=>$productId,
            'reply_type'=>'product'
        ]);
        StoreProductReply::beginTrans();
        $res = StoreProductReply::reply($group,'product');
        Gong::actionadd('fashangpinpingjia','store_product_reply','uid');//行为加分
        if(!$res) {
            StoreProductReply::rollbackTrans();
            return JsonService::fail('评价失败!');
        }
        try{
//            HookService::listen('store_product_order_reply',$group,$cartInfo,false,StoreProductBehavior::class);
            StoreOrder::checkOrderOver($cartInfo['oid']);
        }catch (\Exception $e){
            StoreProductReply::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }
        StoreProductReply::commitTrans();
        return JsonService::successful();
    }

    /*
     * 个人中心 查物流
     * @param int $uid 用户id
     * @param string $uni 订单id或者订单唯一键
     * @return json
     */
    public function express()
    {
        $uni=osx_input('uni','');
        if(!$uni || !($order = StoreOrder::getUserOrderDetail($this->uid,$uni))) return JsonService::fail('查询订单不存在!');
        if($order['delivery_type'] != 'express' || !$order['delivery_id']) return JsonService::fail('该订单不存在快递单号!');
        $cacheName = $uni.$order['delivery_id'];
        CacheService::rm($cacheName);
        $result = CacheService::get($cacheName,null);
        if($result === NULL){
            $result = Express::query($order['delivery_id']);
            if(is_array($result) &&
                isset($result['result']) &&
                isset($result['result']['deliverystatus']) &&
                $result['result']['deliverystatus'] >= 3)
                $cacheTime = 0;
            else
                $cacheTime = 1800;
            CacheService::set($cacheName,$result,$cacheTime);
        }
        return JsonService::successful([ 'order'=>StoreOrder::tidyOrder($order,true), 'express'=>$result ? $result : []]);
    }

    /**
     * 修改收货地址
     * @return \think\response\Json
     */
    public function edit_user_address()
    {
        $request = Request::instance();
        if(!$request->isPost()) return JsonService::fail('参数错误!');
        $addressInfo = UtilService::postMore([
            ['city',''],
            ['district',''],
            ['province',''],
            ['is_default',false],
            ['real_name',''],
            ['post_code',''],
            ['phone',''],
            ['detail',''],
            ['id',0]
        ],$request);
        $addressInfo['is_default'] = $addressInfo['is_default'] == true ? 1 : 0;
        $addressInfo['uid'] = $this->userInfo['uid'];

        if($addressInfo['id'] && UserAddress::be(['id'=>$addressInfo['id'],'uid'=>$this->userInfo['uid'],'is_del'=>0])){
            $id = $addressInfo['id'];
            unset($addressInfo['id']);
            if(UserAddress::edit($addressInfo,$id,'id')){
                if($addressInfo['is_default'])
                    UserAddress::setDefaultAddress($id,$this->userInfo['uid']);
                return JsonService::successful();
            }else
                return JsonService::fail('编辑收货地址失败!');
        }else{
            if($address = UserAddress::set($addressInfo,true)){
                if($addressInfo['is_default'])
                    UserAddress::setDefaultAddress($address->id,$this->userInfo['uid']);
                return JsonService::successful(['id'=>$address->id]);
            }else
                return JsonService::fail('添加收货地址失败!');
        }
    }

    /**
     * 用户通知
     * @return \think\response\Json
     */
    public function get_notice_list()
    {
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',8,'intval');
        $list = UserNotice::getNoticeList($this->userInfo['uid'],$page,$limit);
        return JsonService::successful($list);
    }

    /*
    * 昨日推广佣金
     * @return json
    */
    public function yesterday_commission()
    {
        return JsonService::successful(UserBill::yesterdayCommissionSum($this->uid));
    }

    /*
     * 累计已提金额
     * @return json
     */
    public function extractsum()
    {
        return JsonService::successful(UserExtract::extractSum($this->uid));
    }

    /**
     * 绑定推荐人
     * @return \think\response\Json
     */
    public function spread_uid(){
        $spread_uid=osx_input('post.spread_uid',0,'intval');
        if($spread_uid){
            if(!$this->userInfo['spread_uid']){
                $res = User::edit(['spread_uid'=>$spread_uid],$this->userInfo['uid']);
                if($res) return JsonService::successful('绑定成功');
                else return JsonService::successful('绑定失败');
            }else return JsonService::fail('已存在被推荐人');
        }else return JsonService::fail('没有推荐人');
    }

    /**
     * 设置为默认地址
     * @return \think\response\Json
     */
    public function set_user_default_address()
    {
        $addressId=osx_input('addressId',0,'intval');
        if(!$addressId || !is_numeric($addressId)) return JsonService::fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$addressId,'uid'=>$this->userInfo['uid']]))
            return JsonService::fail('地址不存在!');
        $res = UserAddress::setDefaultAddress($addressId,$this->userInfo['uid']);
        if(!$res)
            return JsonService::fail('地址不存在!');
        else
            return JsonService::successful();
    }

    /**
     * 获取分销二维码
     * @return \think\response\Json
     */
    public  function get_code(){
        header('content-type:image/jpg');
        if(!$this->userInfo['uid']) return JsonService::fail('授权失败，请重新授权');
        $path = makePathToUrl('routine/code');
        if($path == '')
            return JsonService::fail('生成上传目录失败,请检查权限!');
        $picname = $path.'/'.$this->userInfo['uid'].'.jpg';
        $domain = SystemConfigService::get('site_url').'/';
        $domainTop = substr($domain,0,5);
        if($domainTop != 'https') $domain = 'https:'.substr($domain,5,strlen($domain));
        if(file_exists($picname)) return JsonService::successful($domain.$picname);
        else{
            $res = RoutineCode::getCode($this->userInfo['uid'],$picname);
            if($res) file_put_contents($picname,$res);
            else return JsonService::fail('二维码生成失败');
        }
        return JsonService::successful($domain.$picname);
    }

    /*
     * 修改用户信息
     * */
    public function edit_user(){
        $formid=osx_input('formid',0,'intval');
        list($avatar,$nickname)=UtilService::postMore([
            ['avatar',''],
            ['nickname',''],
        ],$this->request,true);
        RoutineFormId::SetFormId($formid,$this->uid);
        if(User::editUser($avatar,$nickname,$this->uid))
            return JsonService::successful('修改成功');
        else
            return JsonService::fail('');
    }

    /*
     * 查找用户消费充值记录
     *
     * */
    public function get_user_bill_list()
    {
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',8,'intval');
        $type=osx_input('type',0,'intval');
        return JsonService::successful(UserBill::getUserBillList($this->uid,$page,$limit,$type));
    }

    /*
     * 获取活动是否存在
     * */
    public function get_activity()
    {
        $data['is_bargin']=StoreBargain::validBargain() ? true : false;
        $data['is_pink']=StoreCombination::getPinkIsOpen() ? true : false;
        $data['is_seckill']=StoreSeckill::getSeckillCount() ? true : false;
        return JsonService::successful($data);
    }

    /**
     * TODO 获取记录总和
     */
    public function get_record_list_count()
    {
        $type=osx_input('type',3,'intval');
        $count = 0;
        if($type == 3) $count = UserBill::getRecordCount($this->uid, 'now_money', 'brokerage');
        else if($type == 4) $count = UserExtract::userExtractTotalPrice($this->uid);//累计提现
        $count = $count ? $count : 0;
        JsonService::successful('',$count);
    }

    /**
     * TODO 获取订单返佣记录
     */
    public function get_record_order_list(){
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',8,'intval');
        $category=osx_input('category','now_money','text');
        $type=osx_input('type','brokerage','text');
        $data['list'] = [];
        $data['count'] = 0;
        $data['list'] = UserBill::getRecordList($this->uid,$page,$limit,$category,$type);
        $count = UserBill::getRecordOrderCount($this->uid, $category, $type);
        $data['count'] = $count ? $count : 0;
        if(!count($data['list'])) return JsonService::successful([]);
        foreach ($data['list'] as $key=>&$value){
            $value['child'] = UserBill::getRecordOrderListDraw($this->uid, $value['time'],$category, $type);
            $value['count'] = count($value['child']);
        }
        return JsonService::successful($data);
    }

    /**
     * TODO 获取推广人列表
     */
    public function user_spread_new_list(){
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',8,'intval');
        $grade=osx_input('grade',0,'intval');
        $keyword=osx_input('keyword','','text');
        $sort=osx_input('sort','','text');
        if(!$keyword) $keyword = '';
        $data['list'] = User::getUserSpreadGrade($this->userInfo['uid'],bcadd($grade,1,0),$sort,$keyword,$page,$limit);
        $data['total'] = User::getSpreadCount($this->uid);
        $data['totalLevel'] = User::getSpreadLevelCount($this->uid);
        return JsonService::successful($data);
    }

    /**
     * 分销二维码海报生成
     */
    public function user_spread_banner_list(){
        header('content-type:image/jpg');
        try{
            $routineSpreadBanner = GroupDataService::getData('routine_spread_banner');
            if(!count($routineSpreadBanner)) return JsonService::fail('暂无海报');
            $pathCode = makePathToUrl('routine/code',3);
            if($pathCode == '') return JsonService::fail('生成上传目录失败,请检查权限!');
            $picName = $pathCode.DS.$this->userInfo['uid'].'.jpg';
            $picName = trim(str_replace(DS, '/',$picName,$loop));
            $res = RoutineCode::getShareCode($this->uid, 'spread', '', $picName);
            if($res) file_put_contents($picName,$res);
            else return JsonService::fail('二维码生成失败');
            $res = true;
            $url = SystemConfigService::get('site_url').'/';
            $domainTop = substr($url,0,5);
            if($domainTop != 'https') $url = 'https:'.substr($url,5,strlen($url));
            $pathCode = makePathToUrl('routine/poster',3);
            foreach ($routineSpreadBanner as $key=>&$item){
                $config = array(
                    'image'=>array(
                        array(
                            'url'=>ROOT_PATH.$picName,     //二维码资源
                            'stream'=>0,
                            'left'=>114,
                            'top'=>790,
                            'right'=>0,
                            'bottom'=>0,
                            'width'=>120,
                            'height'=>120,
                            'opacity'=>100
                        )
                    ),
                    'text'=>array(
                        array(
                            'text'=>$this->userInfo['nickname'],
                            'left'=>250,
                            'top'=>840,
                            'fontPath'=>ROOT_PATH.'public/static/font/SourceHanSansCN-Bold.otf',     //字体文件
                            'fontSize'=>16,             //字号
                            'fontColor'=>'40,40,40',       //字体颜色
                            'angle'=>0,
                        ),
                        array(
                            'text'=>'邀请您加入'.SystemConfigService::get('website_name'),
                            'left'=>250,
                            'top'=>880,
                            'fontPath'=>ROOT_PATH.'public/static/font/SourceHanSansCN-Normal.otf',     //字体文件
                            'fontSize'=>16,             //字号
                            'fontColor'=>'40,40,40',       //字体颜色
                            'angle'=>0,
                        )
                    ),
                    'background'=>$item['pic']
                );
                $filename = ROOT_PATH.$pathCode.'/'.$item['id'].'_'.$this->uid.'.png';
                $res = $res && UtilService::setSharePoster($config,$filename);
                if($res) $item['poster'] = $url.$pathCode.'/'.$item['id'].'_'.$this->uid.'.png';
            }
            if($res) return JsonService::successful($routineSpreadBanner);
            else return JsonService::fail('生成图片失败');
        }catch (\Exception $e){
            return JsonService::fail('生成图片时，系统错误',['line'=>$e->getLine(),'message'=>$e->getMessage()]);
        }
    }

}