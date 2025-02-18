<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */
namespace app\admin\controller\order;

use app\admin\controller\shop\ShopOrder;
use app\admin\model\payment\UserOrder;
use app\admin\model\payment\UserOrderLog;
use app\admin\model\payment\UserPay;
use app\commonapi\model\Gong;
use Api\Express;
use app\admin\controller\AuthController;
use app\admin\model\system\SystemConfig;
use app\shareapi\model\Sell;
use app\shareapi\model\SellOrder;
use service\FormBuilder as Form;
use app\admin\model\order\StoreOrderStatus;
use app\admin\model\ump\StorePink;
use app\admin\model\user\User;
use app\admin\model\user\UserBill;
use basic\ModelBasic;
use behavior\admin\OrderBehavior;
use behavior\wechat\PaymentBehavior;
use EasyWeChat\Core\Exception;
use service\CacheService;
use service\HookService;
use service\JsonService;
use service\SystemConfigService;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Db;
use think\Request;
use think\Url;
use app\osapi\model\common\Support;
use app\wap\model\user\WechatUser;
use service\WechatTemplateService;
use app\admin\model\order\StoreOrder as StoreOrderModel;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;
use app\core\util\RoutineTemplateService;
use app\wap\model\store\StoreOrderCartInfo;

/**
 * 订单管理控制器 同一个订单表放在一个控制器
 * Class StoreOrder
 * @package app\admin\controller\store
 */
class StoreOrder extends AuthController
{
    /**
     * @return mixed
     */
    public function index()
    {
        $config = SystemConfigService::more(['pay_routine_appid','pay_routine_appsecret','pay_routine_mchid','pay_routine_key','pay_routine_client_cert','pay_routine_client_key']);
        $this->assign([
            'year'=>getMonth('y'),
            'real_name'=>$this->request->get('real_name',''),
            'orderCount'=>StoreOrderModel::orderCount(0),
        ]);
        return $this->fetch();
    }
    /**
     * 获取头部订单金额等信息
     * return json
     *
     */
    public function getBadge(){
        $where = Util::postMore([
            ['status',''],
            ['real_name',''],
            ['recieve_address',''],
            ['is_del',0],
            ['data',''],
            ['type',''],
            ['order',''],
            ['is_zg',0]
        ]);
        return JsonService::successful(StoreOrderModel::getBadge($where));
    }
    /**
     * 获取订单列表
     * return json
     */
    public function order_list(){
        $where = Util::getMore([
            ['status',''],
            ['real_name',$this->request->param('real_name','')],
            ['recieve_address',$this->request->param('recieve_address','')],
            ['user_address',$this->request->param('user_address','')],
            ['is_del',0],
            ['data',''],
            ['type',''],
            ['order',''],
            ['page',1],
            ['limit',20],
            ['excel',0],
            ['is_zg',0]
        ]);
        return JsonService::successlayui(StoreOrderModel::OrderList($where));
    }
    public function orderchart(){
        $where = Util::getMore([
            ['status',''],
            ['real_name',''],
            ['is_del',0],
            ['data',''],
            ['combination_id',''],
            ['export',0],
            ['order','id desc']
        ],$this->request);
        $limitTimeList = [
            'today'=>implode(' - ',[date('Y/m/d'),date('Y/m/d',strtotime('+1 day'))]),
            'week'=>implode(' - ',[
                date('Y/m/d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)),
                date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600))
            ]),
            'month'=>implode(' - ',[date('Y/m').'/01',date('Y/m').'/'.date('t')]),
            'quarter'=>implode(' - ',[
                date('Y').'/'.(ceil((date('n'))/3)*3-3+1).'/01',
                date('Y').'/'.(ceil((date('n'))/3)*3).'/'.date('t',mktime(0,0,0,(ceil((date('n'))/3)*3),1,date('Y')))
            ]),
            'year'=>implode(' - ',[
                date('Y').'/01/01',date('Y/m/d',strtotime(date('Y').'/01/01 + 1year -1 day'))
            ])
        ];
        if($where['data'] == '') $where['data'] = $limitTimeList['today'];
        $orderCount = [
            urlencode('未支付')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(0))->count(),
            urlencode('未发货')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(1))->count(),
            urlencode('待收货')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(2))->count(),
            urlencode('待评价')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(3))->count(),
            urlencode('交易完成')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(4))->count(),
            urlencode('退款中')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(-1))->count(),
            urlencode('已退款')=>StoreOrderModel::getOrderWhere($where,StoreOrderModel::statusByWhere(-2))->count()
        ];
        $model = StoreOrderModel::getOrderWhere($where,new StoreOrderModel())->field('sum(total_num) total_num,count(*) count,sum(total_price) total_price,sum(refund_price) refund_price,from_unixtime(add_time,\'%Y-%m-%d\') add_time')
            ->group('from_unixtime(add_time,\'%Y-%m-%d\')');
        $orderPrice = $model->select()->toArray();
        $orderDays = [];
        $orderCategory = [
            ['name'=>'商品数','type'=>'line','data'=>[]],
            ['name'=>'订单数','type'=>'line','data'=>[]],
            ['name'=>'订单金额','type'=>'line','data'=>[]],
            ['name'=>'退款金额','type'=>'line','data'=>[]]
        ];
        foreach ($orderPrice as $price){
            $orderDays[] = $price['add_time'];
            $orderCategory[0]['data'][] = $price['total_num'];
            $orderCategory[1]['data'][] = $price['count'];
            $orderCategory[2]['data'][] = $price['total_price'];
            $orderCategory[3]['data'][] = $price['refund_price'];
        }
        $this->assign(StoreOrderModel::systemPage($where,$this->adminId));
        $this->assign('price',StoreOrderModel::getOrderPrice($where));
        $this->assign(compact('limitTimeList','where','orderCount','orderPrice','orderDays','orderCategory'));
        return $this->fetch();
    }
    /**
     * 修改支付金额等
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function edit($id)
    {
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('order_id','订单编号',$product->getData('order_id'))->disabled(1);
        $f[] = Form::number('total_price','商品总价',$product->getData('total_price'))->min(0);
        $f[] = Form::number('total_postage','原始邮费',$product->getData('total_postage'))->min(0);
        $f[] = Form::number('pay_price','实际支付金额',$product->getData('pay_price'))->min(0);
        $f[] = Form::number('pay_postage','实际支付邮费',$product->getData('pay_postage'));
        $f[] = Form::number('gain_integral','赠送积分',$product->getData('gain_integral'));
//        $f[] = Form::radio('status','状态',$product->getData('status'))->options([['label'=>'开启','value'=>1],['label'=>'关闭','value'=>0]]);
        $form = Form::make_post_form('修改订单',$f,Url::build('update',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');

    }

    /** 修改订单提交更新
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $data = Util::postMore([
            'order_id',
            'total_price',
            'total_postage',
            'pay_price',
            'pay_postage',
            'gain_integral',
        ],$request);
        if($data['total_price'] <= 0) return Json::fail('请输入商品总价');
        if($data['pay_price'] <= 0) return Json::fail('请输入实际支付金额');
        $data['order_id'] = StoreOrderModel::changeOrderId($data['order_id']);
        StoreOrderModel::edit($data,$id);
        HookService::afterListen('store_product_order_edit',$data,$id,false,OrderBehavior::class);
        StoreOrderStatus::setStatus($id,'order_edit','修改商品总价为：'.$data['total_price'].' 实际支付金额'.$data['pay_price']);
        return Json::successful('修改成功!');
    }
    /**
     * 送货
     * @param $id
     *  send
     */
    public function delivery($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['paid'] == 1 && $product['status'] == 0) {
            $f = array();
            $f[] = Form::input('delivery_name','送货人姓名')->required('送货人姓名不能为空','required:true;');
            $f[] = Form::input('delivery_id','送货人电话')->required('请输入正确电话号码','telephone');
            $form = Form::make_post_form('修改订单',$f,Url::build('updateDelivery',array('id'=>$id)),5);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
        else $this->failedNotice('订单状态错误');
    }

    /**送货
     * @param Request $request
     * @param $id
     */
    public function updateDelivery(Request $request, $id){
        $data = Util::postMore([
            'delivery_name',
            'delivery_id',
        ],$request);
        $data['delivery_type'] = 'send';
        if(!$data['delivery_name']) return Json::fail('请输入送货人姓名');
        if(!(int)$data['delivery_id']) return Json::fail('请输入送货人电话号码');
        else if(!preg_match("/^1[3456789]{1}\d{9}$/",$data['delivery_id']))  return Json::fail('请输入正确的送货人电话号码');
        $data['status'] = 1;
        StoreOrderModel::edit($data,$id);
        HookService::afterListen('store_product_order_delivery',$data,$id,false,OrderBehavior::class);
        StoreOrderStatus::setStatus($id,'delivery','已配送 发货人：'.$data['delivery_name'].' 发货人电话：'.$data['delivery_id']);
        return Json::successful('修改成功!');
    }
    /**
     * 发货
     * @param $id
     *  express
     */
    public function deliver_goods($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['paid'] == 1 && $product['status'] == 0){
            $f = array();
            $f[] = Form::radio('no_delivery','无需物流',0)->options([['label'=>'否','value'=>0],['label'  => '是','value'=>1]]);
            $f[] = Form::select('delivery_name','快递公司')->setOptions(function(){
                        $list =  Db::name('express')->where('is_show',1)->order('sort DESC')->column('id,name');
                        $menus = [];
                        foreach ($list as $k=>$v){
                            $menus[] = ['value'=>$v,'label'=>$v];
                        }
                        return $menus;
                    })->filterable(1);
            $f[] = Form::input('delivery_id','快递单号');
            $form = Form::make_post_form('修改订单',$f,Url::build('updateDeliveryGoods',array('id'=>$id)),2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
        else return $this->failedNotice('订单状态错误');
    }

    /**发货保存
     * @param Request $request
     * @param $id
     */
    public function updateDeliveryGoods(Request $request, $id){
        $data = Util::postMore([
            'delivery_name',
            'delivery_id',
            'no_delivery',
        ],$request);
        $data['delivery_type'] = 'express';
        if($data['no_delivery']==0){
            if(!$data['delivery_name']) return Json::fail('请选择快递公司');
            if(!$data['delivery_id']) return Json::fail('请输入快递单号');
        }
        $data['status'] = 1;
        $data['delivery_time']=time();
        StoreOrderModel::edit($data,$id);
        $order = StoreOrderModel::where('id',$id)->find();
        SellOrder::edit(['order_status'=>1],$order['order_id'],'order_id');//修改分销订单状态为已发货待收货
        HookService::afterListen('store_product_order_delivery_goods',$data,$id,false,OrderBehavior::class);
        StoreOrderStatus::setStatus($id,'delivery_goods','已发货 快递公司：'.$data['delivery_name'].' 快递单号：'.$data['delivery_id']);
        $set=MessageTemplate::getMessageSet(14);
        $template=str_replace('{订单编号}', $order['order_id'], $set['template']);
        if($set['status']==1){
            $message_id=Message::sendMessage($order['uid'],0,$template,1,$set['title'],1,'','shop_detail',$order['order_id']);
            $read_id=MessageRead::createMessageRead($order['uid'],$message_id,$set['popup'],1);
        }
        if($set['sms']==1&&$set['status']==1){
            $account=User::where('uid',$order['uid'])->value('phone');
            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
            $template='【'.$config['cl_sms_sign'].'】'.$template;
            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
            $sms=json_decode($sms,true);
            if ($sms['code']==0) {
                $read_data['is_sms']=1;
                $read_data['sms_time']=time();
                MessageRead::where('id',$read_id)->update($read_data);
            }
        }
        $h5_url=SystemConfig::getValue('platform_h5_url');
        WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($order['uid']),WechatTemplateService::ORDER_POSTAGE_SUCCESS, [
            'first'=>'亲，您购买的商品已发货',
            'keyword1'=>$order['real_name'],
            'keyword2'=>$order['user_phone'],
            'keyword3'=>$order['delivery_name'],
            'keyword4'=>$order['delivery_id'],
            'keyword5'=>$order['order_id'],
            'remark'=>'可以去商城查看订单详情'
        ],$h5_url.'packageB/order/detail?id='.$order['order_id']);
        $goodsName = StoreOrderCartInfo::getProductNameList($order['id']);
        $goodsName=implode(',',$goodsName);
        $length=mb_strlen($goodsName,'UTF-8');
        if($length>15){
            $goodsName=mb_substr($goodsName,0,15,'UTF-8').'…';
        }
        RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($order['uid']),RoutineTemplateService::DELIVER_GOODS, [
            'number1'=>['value'=>$order['order_id']],
            'thing3'=>['value'=>$goodsName],
            'phrase5'=>['value'=>'已发货'],
            'thing11'=>['value'=>$order['delivery_name']],
            'character_string12'=>['value'=>$order['delivery_id']],
        ],'','/packageB/order/detail?id='.$order['order_id']);
        return Json::successful('修改成功!');
    }
    /**
     * 修改状态为已收货
     * @param $id
     * @return \think\response\Json|void
     */
    public function take_delivery($id){
        if(!$id) return $this->failed('数据不存在');
        $order = StoreOrderModel::get($id);
        if(!$order) return Json::fail('数据不存在!');
        if($order['status'] == 2) return Json::fail('不能重复收货!');
        if($order['paid'] == 1 && $order['status'] == 1) $data['status'] = 2;
        else if($order['pay_type'] == 'offline') $data['status'] = 2;
        else return Json::fail('请先发货或者送货!');
        if(!StoreOrderModel::edit($data,$id))
            return Json::fail(StoreOrderModel::getErrorInfo('收货失败,请稍候再试!'));
        else{
            try{
                $end_day=intval(SystemConfig::getValue('agent_tixian_config_day'));
                if($end_day<=0||$end_day>28){
                    $end_day=25;
                }
                $end_day_time=strtotime('+1 month',strtotime(time_format(time(),'Y-m-'.$end_day.' 00:00:00')));
                SellOrder::edit(['order_status'=>2,'give_back_time'=>$end_day_time],$order['order_id'],'order_id');//修改分销订单状态为已收货
                $res_seller=Sell::buyProductBeSeller($order);
                if(!$res_seller){
                    exception('成为分销商失败');
                }
                HookService::listen('store_product_order_take_delivery',$order,$id,false,OrderBehavior::class);
            }catch (\Exception $e){
                return Json::fail($e->getMessage());
            }
            StoreOrderStatus::setStatus($id,'take_delivery','已收货');
            return Json::successful('收货成功!');
        }
    }
    /**
     * 修改退款状态
     * @param $id
     * @return \think\response\Json|void
     */
    public function refund_y($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['paid'] == 1){
            $f = array();
            $f[] = Form::input('order_id','退款单号',$product->getData('order_id'))->disabled(1);
            $f[] = Form::number('refund_price','退款金额',$product->getData('pay_price'))->precision(2)->min(0.01);
            $f[] = Form::radio('type','状态',1)->options([['label'=>'直接退款','value'=>1]]);
            $form = Form::make_post_form('退款处理',$f,Url::build('updateRefundY',array('id'=>$id)),2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
        else return Json::fail('数据不存在!');
    }

    /**退款处理
     * @param Request $request
     * @param $id
     */
    public function updateRefundY(Request $request, $id){
        $data = Util::postMore([
            'refund_price',
            ['type',1],
        ],$request);
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['pay_type']!='card_exchange'){
            if($product['pay_price'] == $product['refund_price']) return Json::fail('已退完支付金额!不能再退款了');
            if(!$data['refund_price']) return Json::fail('请输入退款金额');
            $refund_price = $data['refund_price'];
            $data['refund_price'] = bcadd($data['refund_price'],$product['refund_price'],2);
            $bj = bccomp((float)$product['pay_price'],(float)$data['refund_price'],2);
            if($bj < 0) return Json::fail('退款金额大于支付金额，请修改退款金额');
        }else{
            $refund_price=0;
        }
       
        
        if($data['type'] == 1){
            $data['refund_status'] = 2;
        }else if($data['type'] == 2){
            $data['refund_status'] = 0;
        }
        $type =  $data['type'];
        unset($data['type']);
        $refund_data['pay_price'] = $product['pay_price'];
        $refund_data['refund_price'] = $refund_price;
        $refund_data['refund_id']=$product['order_id'];
        $refund_data['refund_operation_uid']=\app\admin\model\system\SystemAdmin::activeAdminIdOrFail();
        $refund_data['refund_operation_time']=time();
        if($product['pay_type'] == 'weixin'){
            try{
                if($product['score_num']>0){
                    $user_score=User::where('uid',$product['uid'])->value('buy');
                    $nums=$user_score+$product['score_num'];
                    $res3=User::where('uid',$product['uid'])->update(['buy'=>$nums]);
                    if($res3===false) return $this->failed('积分恢复失败!');
                    $log=[];
                    $log['buy']=$product['score_num'];
                    Support::jiafenlog($product['uid'],'购买商品积分抵现失败返还',$log,1,'行为');
                }
                $h5_url=SystemConfig::getValue('platform_h5_url');
                WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($product['uid']),WechatTemplateService::REFUND_SUCCESS, [
                    'first'=>'亲，您的订单退款已到账',
                    'keyword1'=>$product['order_id'],
                    'keyword2'=>$product['pay_price'],
                    'remark'=>'可以去商城查看订单详情'
                ],$h5_url.'packageB/order/detail?id='.$product['order_id']);
                HookService::listen('wechat_pay_order_refund',$id,$refund_data,true,PaymentBehavior::class);
            }catch(\Exception $e){
                return Json::fail($e->getMessage());
            }
//            if($product['is_channel']){//小程序
//                try{
//                    $user_score=User::where('uid',$product['uid'])->value('buy');
//                    $nums=$user_score+$product['score_num'];
//                    $res3=User::where('uid',$product['uid'])->update(['buy'=>$nums]);
//                    if($res3===false) return $this->failed('积分恢复失败!');
//                    HookService::listen('routine_pay_order_refund',$product['order_id'],$refund_data,true,PaymentBehavior::class);
//                }catch(\Exception $e){
//                    return Json::fail($e->getMessage());
//                }
//            }else{
//                try{
//                    $user_score=User::where('uid',$product['uid'])->value('buy');
//                    $nums=$user_score+$product['score_num'];
//                    $res3=User::where('uid',$product['uid'])->update(['buy'=>$nums]);
//                    if($res3===false) return $this->failed('积分恢复失败!');
//                    HookService::listen('wechat_pay_order_refund',$product['order_id'],$refund_data,true,PaymentBehavior::class);
//                }catch(\Exception $e){
//                    return Json::fail($e->getMessage());
//                }
        }else if($product['pay_type'] == 'yue'){
            ModelBasic::beginTrans();
            $usermoney = db('user_wallet')->where('uid',$product['uid'])->value('all_money');
            $res1 = db('user_wallet')->where(['uid'=>$product['uid']])->setInc('all_money',$refund_price);
            $res3 =  db('user_wallet')->where(['uid'=>$product['uid']])->setInc('enable_money',$refund_price);
            $res2 = UserBill::income('商品退款',$product['uid'],'all_money','pay_product_refund',$refund_price,$product['id'],bcadd($usermoney,$refund_price,2),'订单退款到余额'.floatval($refund_price).'元');
           if($product['score_num']>0){
               $user_score=User::where('uid',$product['uid'])->value('buy');
               $nums=$user_score+$product['score_num'];
               $res3=User::where('uid',$product['uid'])->update(['buy'=>$nums]);
               if($res3===false) return $this->failed('积分恢复失败!');
               $log=[];
               $log['buy']=$product['score_num'];
               Support::jiafenlog($product['uid'],'购买商品积分抵现失败返还',$log,1,'行为');
           }
            $h5_url=SystemConfig::getValue('platform_h5_url');
            WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($product['uid']),WechatTemplateService::REFUND_SUCCESS, [
                'first'=>'亲，您的订单退款已到账',
                'keyword1'=>$product['order_id'],
                'keyword2'=>$product['pay_price'],
                'remark'=>'可以去商城查看订单详情'
            ],$h5_url.'packageB/order/detail?id='.$product['order_id']);
            try{
                HookService::listen('store_order_yue_refund',$product,$refund_data,false,OrderBehavior::class);
            }catch (\Exception $e){
                ModelBasic::rollbackTrans();
                return Json::fail($e->getMessage());
            }
            $res = $res1 && $res2&&$res3;
            ModelBasic::checkTrans($res);
            if(!$res) return Json::fail('余额退款失败!');
        }else if($product['pay_type'] == 'routine'){
            try{
                if($product['score_num']>0){
                    $user_score=User::where('uid',$product['uid'])->value('buy');
                    $nums=$user_score+$product['score_num'];
                    $res3=User::where('uid',$product['uid'])->update(['buy'=>$nums]);
                    if($res3===false) return $this->failed('积分恢复失败!');
                    $log=[];
                    $log['buy']=$product['score_num'];
                    Support::jiafenlog($product['uid'],'购买商品积分抵现失败返还',$log,1,'行为');
                }
                $h5_url=SystemConfig::getValue('platform_h5_url');
                // WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($product['uid']),WechatTemplateService::REFUND_SUCCESS, [
                //     'first'=>'亲，您的订单退款已到账',
                //     'keyword1'=>$product['order_id'],
                //     'keyword2'=>$product['pay_price'],
                //     'remark'=>'可以去商城查看订单详情'
                // ],$h5_url.'packageB/order/detail?id='.$product['order_id']);
                WechatTemplateService::sendTemplate(WechatUser::uidToOpenid($product['uid']),WechatTemplateService::REFUND_SUCCESS, [
                    'first'=>'亲，您的订单退款已到账',
                    'keyword1'=>$product['order_id'],
                    'keyword2'=>$product['pay_price'],
                    'remark'=>'可以去商城查看订单详情'
                ]);
                HookService::listen('routine_pay_order_refund',$id,$refund_data,true,PaymentBehavior::class);
            }catch(\Exception $e){
                return Json::fail($e->getMessage());
            }
        }else if($product['pay_type']=='card_exchange'){
            try{
                ModelBasic::beginTrans();
                // $card=\app\admin\model\card\CardExchange::where(['exchange_product_id'])
                $exchangeLog= \app\admin\model\card\CardExchangeLog::where(['order_id'=>$product['order_id'],'status'=>0])->find();
                $exchangeLog_back=\app\admin\model\card\CardExchangeLog::where(['id'=>$exchangeLog['id']])->update(['status'=>-1]);
                $card_id=$exchangeLog['card_id'];
                $res3=\app\admin\model\card\Card::cardRevert($card_id);
                $res4= \app\admin\model\card\CardStatus::status($card_id,'exchange_back','兑换商品退款',time(),'+1','兑换商品退款次数+1');

               

                $resEdit = StoreOrderModel::edit($data,$id);
                if($resEdit){
                     //操作变更
                    $orderLog['order_id']=$product['order_id'];
                    $orderLog['uid_type']=1;
                    $orderLog['uid']=$this->adminId;
                    $orderLog['info']='订单退款';
                    $res5= UserOrderLog::add_user_order_log($orderLog);


                    //生成退款记录
                    UserOrder::create_refund_order([
                        'relation_order'=>$product['order_id'],
                        'amount'=>$refund_price,
                        'pay_type'=>$product['pay_type'],
                        'uid'=>$product['uid'],
                    ]);
                    $res = $res3 && $res4 && $exchangeLog_back && $res5;
                    ModelBasic::checkTrans($res);

                    if(!$res) return Json::fail('礼品卡兑换商品退款失败!');
                    return Json::successful('修改成功!');
                }else{
                    StoreOrderStatus::setStatus($id,'refund_price','退款给用户'.$refund_price.'元失败');
                    return Json::successful('修改失败!');
                }
               

              
            }catch(\Exception $e){
                return Json::fail($e->getMessage());
            }
        }
        $resEdit = StoreOrderModel::edit($data,$id);
        if($resEdit){
            if($product['is_zg']==1){
                Gong::delaction('goumaizhishishangpin',$product['uid'],'知识付费商品退款');//行为加分
            }else{
                Gong::delaction('goumaishangpin',$product['uid'],'商品退款');//行为加分
            }
            SellOrder::edit(['back_status'=>0,'order_status'=>-1,'give_back_time'=>0],$product['order_id'],'order_id');//修改分销订单状态为 已退款
            $data['type'] = $type;
            if($data['type'] == 1)  StorePink::setRefundPink($id);
            if($product['refund_status']==1){
                $set=MessageTemplate::getMessageSet(15);
                $template=str_replace('{订单编号}', $product['order_id'], $set['template']);
                if($set['status']==1){
                    $message_id=Message::sendMessage($product['uid'],0,$template,1,$set['title'],1,'','store_order',$product['order_id']);
                    $read_id=MessageRead::createMessageRead($product['uid'],$message_id,$set['popup'],1);
                }
                if($set['sms']==1&&$set['status']==1){
                    $account=User::where('uid',$product['uid'])->value('phone');
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $template='【'.$config['cl_sms_sign'].'】'.$template;
                    $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                    $sms=json_decode($sms,true);
                    if ($sms['code']==0) {
                        $read_data['is_sms']=1;
                        $read_data['sms_time']=time();
                        MessageRead::where('id',$read_id)->update($read_data);
                    }
                }
            }else{
                $set=MessageTemplate::getMessageSet(17);
                $template=str_replace('{订单编号}', $product['order_id'], $set['template']);
                if($set['status']==1){
                    $message_id=Message::sendMessage($product['uid'],0,$template,1,$set['title'],1,'','store_order',$product['order_id']);
                    $read_id=MessageRead::createMessageRead($product['uid'],$message_id,$set['popup'],1);
                }
                if($set['sms']==1&&$set['status']==1){
                    $account=User::where('uid',$product['uid'])->value('phone');
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $template='【'.$config['cl_sms_sign'].'】'.$template;
                    $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                    $sms=json_decode($sms,true);
                    if ($sms['code']==0) {
                        $read_data['is_sms']=1;
                        $read_data['sms_time']=time();
                        MessageRead::where('id',$read_id)->update($read_data);
                    }
                }
            }
            RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($product['uid']),RoutineTemplateService::REFUND_PAY, [
                'character_string1'=>['value'=>$product['order_id']],
                'amount3'=>['value'=>$product['pay_price']],
                'phrase4'=>['value'=>'已退款'],
            ],'','/packageB/order/detail?id='.$product['order_id']);
            HookService::afterListen('store_product_order_refund_y',$data,$id,false,OrderBehavior::class);
            StoreOrderStatus::setStatus($id,'refund_price','退款给用户'.$refund_price.'元');

            //操作变更
            $orderLog['order_id']=$product['order_id'];
            $orderLog['uid_type']=1;
            $orderLog['uid']=$this->adminId;
            $orderLog['info']='订单退款';
            UserOrderLog::add_user_order_log($orderLog);

            //生成退款记录
            UserOrder::create_refund_order([
                'relation_order'=>$product['order_id'],
                'amount'=>$refund_price,
                'pay_type'=>$product['pay_type'],
                'uid'=>$product['uid'],
            ]);
            return Json::successful('修改成功!');
        }else{
            StoreOrderStatus::setStatus($id,'refund_price','退款给用户'.$refund_price.'元失败');
            return Json::successful('修改失败!');
        }
    }
    public function order_info($oid = '')
    {
        if(!$oid || !($orderInfo = StoreOrderModel::get($oid)))
            return $this->failed('订单不存在!');
        $userInfo = User::getUserInfos($orderInfo['uid']);
        if($userInfo['spread_uid']){
            $spread = User::where('uid',$userInfo['spread_uid'])->value('nickname');
        }else{
            $spread ='';
        }
 
        $orderInfo['refund_reason_time_text']=date('Y-m-d H:i',$orderInfo['refund_reason_time']);
        $orderInfo['refund_reason_wap_img_data']=json_decode($orderInfo['refund_reason_wap_img']);
        $this->assign(compact('orderInfo','userInfo','spread'));
        return $this->fetch();
    }
    public function express($oid = '')
    {
        if(!$oid || !($order = StoreOrderModel::get($oid)))
            return $this->failed('订单不存在!');
        if($order['delivery_type'] != 'express' || !$order['delivery_id']) return $this->failed('该订单不存在快递单号!');

        //获取物流方法
        $result= \app\admin\model\shop\ShopOrder::get_delivery($order);

        $this->assign([
            'order'=>$order,
            'express'=>$result
        ]);
        return $this->fetch();
    }
    /**
     * 修改配送信息
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function distribution($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('order_id','物流订单',$product->getData('order_id'))->disabled(1);
        if($product['delivery_type'] == 'send'){
            $f[] = Form::input('delivery_name','送货人姓名',$product->getData('delivery_name'));
            $f[] = Form::input('delivery_id','送货人电话',$product->getData('delivery_id'));
        }else if($product['delivery_type'] == 'express'){
            $f[] = Form::select('delivery_name','快递公司',$product->getData('delivery_name'))->setOptions(function (){
                $list =  Db::name('express')->where('is_show',1)->column('id,name');
                $menus = [];
                foreach ($list as $k=>$v){
                    $menus[] = ['value'=>$v,'label'=>$v];
                }
                return $menus;
            });
            $f[] = Form::input('delivery_id','快递单号',$product->getData('delivery_id'));
        }
        $form = Form::make_post_form('配送信息',$f,Url::build('updateDistribution',array('id'=>$id)),5);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**修改配送信息
     * @param Request $request
     * @param $id
     */
    public function updateDistribution(Request $request, $id){
        $data = Util::postMore([
            'delivery_name',
            'delivery_id',
        ],$request);
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['delivery_type'] == 'send'){
            if(!$data['delivery_name']) return Json::fail('请输入送货人姓名');
            if(!(int)$data['delivery_id']) return Json::fail('请输入送货人电话号码');
            else if(!preg_match("/^1[3456789]{1}\d{9}$/",$data['delivery_id']))  return Json::fail('请输入正确的送货人电话号码');
        }else if($product['delivery_type'] == 'express'){
            if(!$data['delivery_name']) return Json::fail('请选择快递公司');
            if(!$data['delivery_id']) return Json::fail('请输入快递单号');
        }
        StoreOrderModel::edit($data,$id);
        HookService::afterListen('store_product_order_distribution',$data,$id,false,OrderBehavior::class);
        StoreOrderStatus::setStatus($id,'distribution','修改发货信息为'.$data['delivery_name'].'号'.$data['delivery_id']);
        return Json::successful('修改成功!');
    }
    /**
     * 修改退款状态
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function refund_n($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $f[] = Form::input('order_id','退款单号',$product->getData('order_id'))->disabled(1);
        $f[] = Form::input('refund_reason','拒绝退款原因')->type('textarea');
        $form = Form::make_post_form('退款',$f,Url::build('updateRefundN',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**拒绝拒绝退款原因
     * @param Request $request
     * @param $id
     */
    public function updateRefundN(Request $request, $id){
        $data = Util::postMore([
            'refund_reason',
        ],$request);
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if(!$data['refund_reason']) return Json::fail('请输入拒绝退款原因');
        $data['refund_status'] = 0;
        StoreOrderModel::edit($data,$id);
        HookService::afterListen('store_product_order_refund_n',$data['refund_reason'],$id,false,OrderBehavior::class);
        StoreOrderStatus::setStatus($id,'refund_n','拒绝拒绝退款原因:'.$data['refund_reason']);
        $set=MessageTemplate::getMessageSet(16);
        $template=str_replace('{订单编号}', $product['order_id'], $set['template']);
        if($set['status']==1){
            $message_id=Message::sendMessage($product['uid'],0,$template,1,$set['title'],1,'','store_order',$product['order_id']);
            $read_id=MessageRead::createMessageRead($product['uid'],$message_id,$set['popup'],1);
        }
        if($set['sms']==1&&$set['status']==1){
            $account=User::where('uid',$product['uid'])->value('phone');
            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
            $template='【'.$config['cl_sms_sign'].'】'.$template;
            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
            $sms=json_decode($sms,true);
            if ($sms['code']==0) {
                $read_data['is_sms']=1;
                $read_data['sms_time']=time();
                MessageRead::where('id',$read_id)->update($read_data);
            }
        }
        RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($product['uid']),RoutineTemplateService::REFUND_PAY, [
            'character_string1'=>['value'=>$product['order_id']],
            'amount3'=>['value'=>$product['pay_price']],
            'phrase4'=>['value'=>'拒绝退款'],
        ],'','/packageB/order/detail?id='.$product['order_id']);
        return Json::successful('修改成功!');
    }
    /**
     * 立即支付
     * @param $id
     */
    public function offline($id){
        $res = StoreOrderModel::updateOffline($id);
        if($res){
            try{
                HookService::listen('store_product_order_offline',$id,false,OrderBehavior::class);
            }catch (Exception $e){
                return Json::fail($e->getMessage());
            }
            StoreOrderStatus::setStatus($id,'offline','线下付款');
            return Json::successful('修改成功!');
        }else{
            return Json::fail('修改失败!');
        }
    }
    /**
     * 修改积分和金额
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function integral_back($id){
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($product['paid'] == 1){
            $f[] = Form::input('order_id','退款单号',$product->getData('order_id'))->disabled(1);
            $f[] = Form::number('use_integral','使用的积分',$product->getData('use_integral'))->min(0)->disabled(1);
            $f[] = Form::number('use_integrals','已退积分',$product->getData('back_integral'))->min(0)->disabled(1);
            $f[] = Form::number('back_integral','可退积分',bcsub($product->getData('use_integral'),$product->getData('use_integral')))->min(0);
            $form = Form::make_post_form('退积分',$f,Url::build('updateIntegralBack',array('id'=>$id)));
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }else{
            return Json::fail('参数错误!');
        }
        return $this->fetch('public/form-builder');
    }

    /** 退积分保存
     * @param Request $request
     * @param $id
     */
    public function updateIntegralBack(Request $request, $id){
        $data = Util::postMore([
            'back_integral',
        ],$request);
        if(!$id) return $this->failed('数据不存在');
        $product = StoreOrderModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        if($data['back_integral'] <= 0) return Json::fail('请输入积分');
        if($product['use_integral'] == $product['back_integral']) return Json::fail('已退完积分!不能再积分了');
        $back_integral = $data['back_integral'];
        $data['back_integral'] = bcadd($data['back_integral'],$product['back_integral'],2);
        $bj = bccomp((float)$product['use_integral'],(float)$data['back_integral'],2);
        if($bj < 0) return Json::fail('退积分大于支付积分，请修改退积分');
        ModelBasic::beginTrans();
        $integral = User::where('uid',$product['uid'])->value('integral');
        $res1 = User::bcInc($product['uid'],'integral',$back_integral,'uid');
        $res2 = UserBill::income('商品退积分',$product['uid'],'integral','pay_product_integral_back',$back_integral,$product['id'],bcadd($integral,$back_integral,2),'订单退积分'.floatval($back_integral).'积分到用户积分');
        try{
            HookService::listen('store_order_integral_back',$product,$back_integral,false,OrderBehavior::class);
        }catch (\Exception $e){
            ModelBasic::rollbackTrans();
            return Json::fail($e->getMessage());
        }
        $res = $res1 && $res2;
        ModelBasic::checkTrans($res);
        if(!$res) return Json::fail('退积分失败!');
        StoreOrderModel::edit($data,$id);
        StoreOrderStatus::setStatus($id,'integral_back','商品退积分：'.$data['back_integral']);
        return Json::successful('退积分成功!');
    }
    public function remark(Request $request){
        $data = Util::postMore(['id','remark'],$request);
        if(!$data['id']) return Json::fail('参数错误!');
        if($data['remark'] == '')  return Json::fail('请输入要备注的内容!');
        $id = $data['id'];
        unset($data['id']);
        StoreOrderModel::edit($data,$id);
        return Json::successful('备注成功!');
    }
    public function order_status($oid){
       if(!$oid) return $this->failed('数据不存在');
       $this->assign(StoreOrderStatus::systemPage($oid));
        $order_id=db('store_order')->where(['id'=>$oid])->value('order_id');
        $this->assign('order_id',$order_id);
       return $this->fetch();
    }
}
