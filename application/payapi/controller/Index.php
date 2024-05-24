<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/11/19
 * Time: 10:31
 */

namespace app\payapi\controller;


use app\admin\model\payment\UserOrderLog;
use app\admin\model\payment\WithdrawOrder;
use app\admin\model\system\SystemConfig;
use app\ebapi\model\store\StoreOrder;
use app\ebapi\model\store\StoreProduct;
use app\osapi\controller\User;
use app\osapi\model\user\InviteCode;
use app\core\util\WechatService;
use app\core\util\SystemConfigService;
use app\osapi\model\user\UserVerify;
use app\payapi\model\UserOrder;
use app\payapi\model\Withdraw;
use app\shareapi\model\Sell;
use app\shareapi\model\SellOrder;
use basic\ControllerBasic;
use app\core\model\routine\RoutineFormId;//待完善
use app\ebapi\model\user\WechatUser;
use basic\ModelBasic;
use Complex\Exception;
use service\UtilService;
use think\Url;


class Index extends ControllerBasic
{

    private function my_wallet()
    {
        $uid=$this->_needLogin();
   
        $wallet=db('user_wallet')->where(['uid'=>$uid])->find();
        return $wallet;
    }
    /**
     *获取我的钱包
     * 2020.9.16
     */
   public function get_my_wallet(){
       $uid=$this->_needLogin();
       $is_phone=db('user')->where(['uid'=>$uid])->value('phone');
       if(!$is_phone){
           return $this->apiError(['error_code'=>'no_phone','status'=>0,'info'=>'请绑定手机号码']);
       }
       $wallet=db('user_wallet')->where(['uid'=>$uid])->find();
       if(!$wallet['password']){
           return $this->apiError(['error_code'=>'no_password','status'=>0,'info'=>'请设置支付密码']);
       }else{
           unset($wallet['pay_password']);
           return $this->apiSuccess($wallet);
       }

   }

    /**
     * 设置密码
     * 2020.9.16
     */
   public function set_password(){
       $uid=$this->_needLogin();
       $password1 = input('password1', '', 'text');
       $password2=input('password2', '','text');
       if($password1!==$password2){
           return $this->apiError(['status'=>0,'info'=>'密码不同']);
       }
       /**解密 start**/
       $iv = "1234567890123412";//16位 向量
       $key= '201707eggplant99';//16位 默认秘钥
       $password=trim(openssl_decrypt(base64_decode($password1),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
       /**解密 end**/
       $password=md5($password);
       if(db('user_wallet')->where(['uid'=>$uid])->count()){
           $res=db('user_wallet')->where(['uid'=>$uid])->update(['password'=>$password]);
       }else{
           $res=db('user_wallet')->insert(['uid'=>$uid,'password'=>$password,'status'=>1]);
       }
       if($res){
           return $this->apiSuccess(['status'=>1,'info'=>'设置密码成功']);
       }else{
           return $this->apiError(['status'=>0,'info'=>'设置密码失败']);
       }
   }

    /**
     * 检测密码
     */
   public function check_password(){
       $uid=$this->_needLogin();
       $password = input('password', '', 'text');
       /**解密 start**/
       $iv = "1234567890123412";//16位 向量
       $key= '201707eggplant99';//16位 默认秘钥
       $password=trim(openssl_decrypt(base64_decode($password),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
       $password=md5($password);
       $count=db('user_wallet')->where(['uid'=>$uid,'password'=>$password])->count();
       if($count>0){
           return $this->apiSuccess(['status'=>1,'info'=>'密码正确']);
       }else{
           return $this->apiError(['status'=>0,'info'=>'密码错误']);
       }
   }
    /**
     * 忘记密码，重新设置
     * 2020.9.16
     */
    public function forget_password(){
        $uid=$this->_needLogin();
        $password = osx_input('password', '', 'text');
        $verify=osx_input('verify','','text');
        /**解密 start**/
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $password=trim(openssl_decrypt(base64_decode($password),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
        /**解密 end**/
        $phone=db('user')->where(['uid'=>$uid])->value('phone');
        $res=UserVerify::checkVerify($phone,'mobile',$verify);
        if($res!==1){
            return $this->apiError(['status'=>0,'info'=>'验证码错误']);
        }
        $password=md5($password);
        $res=db('user_wallet')->where(['uid'=>$uid])->update(['password'=>$password]);
        if($res){
            return $this->apiSuccess(['status'=>1,'info'=>'修改密码成功']);
        }else{
            return $this->apiError(['status'=>0,'info'=>'修改密码失败']);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function get_withdrawal_list(){
        $uid=$this->_needLogin();
        $time=osx_input('time','','text');
        $map['uid']=$uid;
        $map['status']=osx_input('status','','text');
        if( $map['status']==null || $map['status']=='') unset($map['status']);
        // $map['pay_type']='yue';
        // $map['order_type']=5;
        if($time){
            $time.='-01 00:00:00';
            // echo($time.'-');
            //当月开始
            $start=$time=strtotime($time);
//            $start=date('Y-m-d', strtotime(date('Y-m', $time) . '00:00:00')); //直接以strtotime生成
            //当月结束
            $end=strtotime(date('Y-m', $time) . '-' . date('t', $time) . ' 23:59:59'); //t为当月天数,28至31天
 
            $map['create_time']=['between',[$start,$end]];
 
        }
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',0,'intval');
        $res=\app\admin\model\payment\WithdrawOrder::get_user_withdraw_order_list($map,$page,$limit,'create_time desc');
        $data=&$res['data'];
        $typeStruct=[
            'wechat'=>'微信',
            'apliay'=>'支付宝'
        ];
        foreach($data as &$item)
        {
            $item['order_type']=5;
            // $item['info']=$typeStruct[$item['type']].' ';
            $item['info']=$item['type'];
            // $item['create_time']=date('Y-m-d H:i:s',$item['create_time']);
            $item['amount']=$item['money'];
        }
        return $this->apiSuccess($data);
    }

    /**
     * 获取订单列表
     * 2020.9.17
     */
    public function get_order_list(){
        $uid=$this->_needLogin();
        $time=osx_input('time','','text');
        $map['uid']=$uid;
        $map['status']=osx_input('status',1,'intval');
        $map['pay_type']=osx_input('pay_type',null);
        if($time){
            $time.='-01 00:00:00';
            // echo($time.'-');
            //当月开始
            $start=$time=strtotime($time);
//            $start=date('Y-m-d', strtotime(date('Y-m', $time) . '00:00:00')); //直接以strtotime生成
            //当月结束
            $end=strtotime(date('Y-m', $time) . '-' . date('t', $time) . ' 23:59:59'); //t为当月天数,28至31天
 
            $map['create_time']=['between',[$start,$end]];
 
        }

        $order_type=osx_input('order_type',0,'intval');
 
      
         if($order_type ){
            
            $map['order_type']=$order_type;
            $map['status']=1;
            // if($order_type!=5&&$map['status']==-2){
            //     $map['status']=1;
            // }elseif($order_type==5&&$map['status']=-2){
            //     unset($map['status']);
            // }else{
            //     $map['status']=0;
            // }
        } else
        {     
            $map['status']=1;
        }

        $map_or=[];
        // $order_type=osx_input('order_type',0,'intval');
        // if($order_type){
        //     $map['order_type']=$order_type;
        //     if($order_type!=5&&$map['status']==-2){
        //         $map['status']=1;
        //     }elseif($order_type==5&&$map['status']=-2){
        //         unset($map['status']);
        //     }else{
        //         $map['status']=0;
        //     }
        // }else{
        //     $map['status']=1;
        //     $map_or['order_type']=5;
        //     $map_or['uid']=$uid;
        // }


        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',0,'intval');
// echo(json_encode($map));
// die();
        $data=UserOrder::get_order_list($map,$page,$limit,'create_time desc',null);
        return $this->apiSuccess($data);
    }


    /**
     * 订单详情页
     * 2020.9.17
     */
    public function get_order_detail(){
        $order_id=osx_input('order_id',0,'text');
        $data=UserOrder::where(['unique'=>$order_id])->cache('user_order_'.$order_id)->find();
        $data['order_change']=db('user_order_log')->where(['order_id'=>$data['order_id']])->order(['create_time asc'])->select();
        $this->apiSuccess($data);
    }

    /**
     * 获取关联订单
     * 2020.9.18
     */
    public function get_relation_order(){
        $order_id=osx_input('post.order_id',0,'text');
        $data=UserOrder::where(['unique'=>$order_id])->cache('user_order_'.$order_id)->find();
        if(!$data['bind_table']){
            $this->apiError(['info'=>'不存在关联订单','status'=>0]);
        }
        $relation=db($data['bind_table'])->where(['order_id'=>$data['order_id']])->find();
        $data['relation']=$relation;
        $this->apiSuccess($data);
    }

    /**
     * 提现
     * 2020.9.21
     */
    public function withdraw(){
        $type=osx_input('type',0,'text');
        $account=osx_input('account','','text');
        $name=osx_input('name','','text');
        $code=osx_input('code','','text');
        $amount=osx_input('amount','','float');
        //基础判断
        if($amount<=0){
            return $this->apiError(['status'=>0,'info'=>'请输入正确的提现金额']);
        }
        if(!$account){
            return $this->apiError(['status'=>0,'info'=>'请填写提现账号']);
        }
        if(!$name){
            return $this->apiError(['status'=>0,'info'=>'请填写真实姓名']);
        }
        $uid=$this->_needLogin();
        $wallet=db('user_wallet')->where(['uid'=>$uid])->find();
        if($wallet['all_money']<=0)  return $this->apiError(['status'=>0,'info'=>'您的余额异常无法提现']);
        if($wallet['enable_money']<$amount)  return $this->apiError(['status'=>0,'info'=>'您的余额不足无法提现']);

        //后端配置判断start
        $withdrawal_min_amount=SystemConfig::getValue('withdrawal_min_amount');
        if(bccomp($withdrawal_min_amount,$amount,2)==1&&$withdrawal_min_amount!=0){
            return $this->apiError(['status'=>0,'info'=>'单次提现最低限额不能低于'.$withdrawal_min_amount.'元']);
        }
        $withdrawal_max_amount=SystemConfig::getValue('withdrawal_max_amount');
        if(bccomp($withdrawal_max_amount,$amount,2)==-1&&$withdrawal_max_amount!=0){
            return $this->apiError(['status'=>0,'info'=>'单次提现金额不能高于'.$withdrawal_max_amount.'元']);
        }
      

        // 每天累计金额 上限
        $withdrawal_day_max_amount=SystemConfig::getValue('withdrawal_day_max_amount');
        if($withdrawal_day_max_amount!=0){
            $today=strtotime(date('Y-m-d',time()).'00:00:00');
            $today_end=$today+24*3600;
            $max_amount=db('withdraw_order')->where(['uid'=>$uid,'status'=>['egt',0],'create_time'=>['between',[$today,$today_end]]])->sum('money');
            if(bccomp($withdrawal_day_max_amount,bcadd($max_amount,$amount),2)==-1){
                return $this->apiError(['status'=>0,'info'=>'每日提现金额最多不能高于'.$withdrawal_day_max_amount.'元']);
            }
        }
        //后端配置判断end

        $data['money']=$amount;
        $data['rate']=floatval(SystemConfig::getValue('withdrawal_service_charge'));
        $data['reality_money']=bcmul($amount,bcdiv(bcsub(100,$data['rate']),100,4));
        $order='tx'.date('Ymdhis',time()).$uid.create_rand(4,'num');
        $data['order_id']=$order;
        $data['type']=$type;
        $data['account']=$account;
        $data['info']='提现';
        $data['order_type']=5;
        $data['name']=$name;
        $data['code']=$code;
        $data['create_time']=time();
        $data['status']=0;
        $data['uid']=$uid;
        $res=Withdraw::set_withdraw($data);
        if($res){
            return $this->apiSuccess(['status'=>1,'info'=>'提现申请已经提交,请耐心等待']);
        }else{
            return $this->apiError(['status'=>0,'info'=>'提现申请失败']);
        }
    }

    /**
     * 充值订单
     * 2020.9.21
     */
    public function recharge(){
        $uid=$this->_needLogin();
        $amount=osx_input('amount',0,'float');
        if($amount<=0){
            $this->apiError(['status'=>0,'info'=>'请输入正确的数值']);
        }
        //后端配置判断start
        $recharge_max_amount=SystemConfig::getValue('recharge_max_amount');
        if(bccomp($recharge_max_amount,$amount,2)==-1&&$recharge_max_amount!=0){
            $this->apiError(['status'=>0,'info'=>'单次充值金额不能高于'.$recharge_max_amount.'元']);
        }
        // 每天累计金额 上限
        $recharge_day_max_amount=SystemConfig::getValue('recharge_day_max_amount');
        if($recharge_day_max_amount!=0){
            $today=strtotime(date('Y-m-d',time()).'00:00:00');
            $today_end=$today+24*3600;
            $max_amount=db('user_order')->where(['uid'=>$uid,'order_type'=>4,'create_time'=>['between',[$today,$today_end]]])->sum('amount');
            if(bccomp($recharge_day_max_amount,bcadd($max_amount,$amount),2)==-1){
                $this->apiError(['status'=>0,'info'=>'每日充值金额最多不能高于'.$recharge_day_max_amount.'元']);
            }
        }
        ModelBasic::beginTrans();
        //后端配置判断end
        $user_order['order_id']='cz'.date('Ymdhis',time()).$uid.create_rand(4,'num');
        $user_order['unique']=md5($user_order['order_id']);
        $user_order['create_time']=time();
        $user_order['info']='充值';
        $user_order['uid']=$uid;
        $user_order['amount']=$amount;
        $user_order['amount_type']=1;
        $user_order['status']=2;
        $user_order['create_time']=time();
        $user_order['bind_table']='';
        $user_order['order_type']=4;
        $res=db('user_order')->insert($user_order);

        //操作变更
        $orderLog['order_id']=$user_order['order_id'];
        $orderLog['uid_type']=0;
        $orderLog['uid']=$user_order['uid'];
        $orderLog['info']='发起申请';
        $res1=UserOrderLog::add_user_order_log($orderLog);
        if($res&&$res1){
            ModelBasic::commitTrans();
            $this->apiSuccess(['status'=>1,'info'=>'充值订单创建成功','order_id'=>$user_order['order_id'],'unique'=>$user_order['unique']]);
        }else{
            ModelBasic::rollbackTrans();
             $this->apiError(['status'=>0,'info'=>'充值订单创建失败']);
        }
    }

    /**
     * 收银台,获取订单详情页
     * 2020.9.21
     */
    public function payment(){
        $uni=osx_input('uni','','text');
        $uid=$this->_needLogin();
        $order=UserOrder::get_order($uid,$uni);
        if(!$order){
            $this->apiError(['status'=>0,'info'=>'订单不存在']);
        }
        $this->apiSuccess($order);
    }

    /**
     * 收银台,支付账单
     * 2020.9.21
     */

     public function pay_order(){
         $order_id=osx_input('order_id','','text');
         $paytype=osx_input('paytype','wechat','text');
         $uid=$this->_needLogin();
//         $bill_type=osx_input('bill_type','pay_product','text');
         if (!$order_id)  return $this->apiError(['status'=>0,'info'=>'参数错误']);
         $order=UserOrder::where(['order_id'=>$order_id])->find();
         $sum_money=UserOrder::where(['order_id'=>$order_id])->sum('amount');
         if (!$order) return $this->apiError(['status'=>0,'info'=>'订单不存在']);
         if ($order['status']==1) return  $this->apiError(['status'=>0,'info'=>'该订单已支付']);
         //todo 过期订单
         //todo 支付订单列表情况做判断
         StoreOrder::where('id', $order['id'])->update(['pay_type'=>$paytype]);
         switch ($paytype) {
             case 'wechat':
                 $status=db('pay_set')->where('type','weixin')->value('status');
                 if($status==0){
                     return $this->apiError(['status'=>0,'info'=>'该支付未开启!']);
                 }
                 try {
                     $openid_eb = WechatUser::getOpenId($uid);
                     if(!$openid_eb){
                         $openid_os=db('user_sync_login')->where('uid',$uid)->value('open_id');
                         if(!$openid_os){
                             $this->apiError(['status'=>0,'info'=>'该用户没有绑定微信!']);
                         }else{
                             $openid=$openid_os;
                         }
                     }else{
                         $openid=$openid_eb;
                     }
                     $jsConfig = WechatService::jsPay($openid,$order['order_id'],$sum_money,'productr',SystemConfigService::get('website_name'),$detail='', $trade_type='JSAPI');

                     if(isset($jsConfig['package']) && $jsConfig['package']){
                         $jsConfig['package']=str_replace('prepay_id=','',$jsConfig['package']);
                         for($i=0;$i<3;$i++){
                             RoutineFormId::SetFormId($jsConfig['package'], $uid);
                         }
                     }
                     $jsConfig['package']='prepay_id='.$jsConfig['package'];

                 } catch (\Exception $e) {
                     return $this->apiError(['status'=>0,'info'=>$e->getMessage()]);
                 }
                 return $this->apiSuccess(['status'=>1,'info'=>'支付判断成功','pay_type'=>'wechat_pay','data'=> ['jsConfig' => $jsConfig, 'order_id' => $order['order_id']]]);
                 break;

             case 'yue':
                 $status=db('pay_set')->where('type','yue')->value('status');
                 if($status==0){
                     return $this->apiError(['status'=>0,'info'=>'该支付未开启!']);
                 }
                 $password = osx_input('password', '', 'text');
                 /**解密 start**/
                 $iv = "1234567890123412";//16位 向量
                 $key= '201707eggplant99';//16位 默认秘钥
                 $password=trim(openssl_decrypt(base64_decode($password),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
                 $password=md5($password);
                 $wallet=db('user_wallet')->where(['uid'=>$uid,'password'=>$password])->find();
                 if($wallet){
                     if(bccomp($wallet['enable_money'],$sum_money,2)==-1){
                         return $this->apiError(['status'=>0,'info'=>'余额不足']);
                     }
                     $res=UserOrder::pay_order($order['order_id'],$uid);
                     if($res){
//                         //操作变更
//                         $orderLog['order_id']=$order['order_id'];
//                         $orderLog['uid_type']=0;
//                         $orderLog['uid']=get_uid();
//                         $orderLog['info']='支付成功';
//                         UserOrderLog::add_user_order_log($orderLog);
                         return $this->apiError(['status'=>1,'info'=>'支付成功']);
                     }else{
                         return $this->apiError(['status'=>0,'info'=>'支付失败']);
                     }
                 }else{
                     $this->apiError(['status'=>0,'info'=>'密码输入错误']);
                 }
             }
//         }
     }

    /**
     * 提现到钱包
     */
     public function withdraw_to_wallet(){
         $amount=osx_input('amount',0,'float');
         $uid=$this->_needLogin();
         $seller=db('sell')->where(['uid'=>$uid])->find();
         if($seller['status']!=1){
             $this->apiError(['status'=>0,'info'=>'您还未成为分销者']);
         }
         if(bccomp($amount,$seller['has_income'])==1){
             $this->apiError(['status'=>0,'info'=>'提现金额不足']);
         }
         $sell_info['has_income']=bcsub($seller['has_income'],$amount,2);
         $sell_info['out_income']=bcadd($seller['out_income'],$amount,2);
         $sell_info['out_num']= $seller['out_num']+1;
         ModelBasic::beginTrans();
         $res=db('sell')->where(['uid'=>$uid])->update($sell_info);
         $res2=db('user_wallet')->where(['uid'=>$uid])->setInc('all_money',$amount);
         $res3=db('user_wallet')->where(['uid'=>$uid])->setInc('enable_money',$amount);
         //提现记录
         $data['uid']=$uid;
         $data['type']='yue';
         $data['account']='钱包';
         $data['out_num']=$amount;
         $data['create_time']=time();
         $data['status']=3;
         $data['finish_time']=time();
         $data['remark']='钱包提现';
         $data['order_num']='qbtx'.date('ymd').$uid.create_rand(8);
         $res4=db('cash_out')->insert($data);
         if($res&$res2&&$res3&&$res4){
             ModelBasic::commitTrans();
             $this->apiSuccess(['status'=>1,'info'=>'提现成功']);
         }else{
             ModelBasic::rollbackTrans();
             $this->apiError(['status'=>0,'info'=>'提现失败']);
         }

     }
}