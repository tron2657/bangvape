<?php

namespace app\admin\controller\payment;

use app\admin\controller\AuthController;
use app\admin\model\payment\PaymentProfit;
use app\admin\model\payment\UserOrderLog;
use app\admin\model\payment\WithdrawOrder;
use app\admin\model\system\SystemConfig as ConfigModel;
use app\admin\model\payment\UserPay;
use basic\ModelBasic;
use service\FormBuilder as Form;
use service\JsonService as Json;
use service\UtilService as Util;
use app\admin\model\com\VisitAudit as ForumAudit;
use think\Request;
use think\Url;

/**
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class index extends AuthController
{
    public function index(){

    }

    public function set_config(){
        return $this->fetch();
    }

    /**
     * 获取列表的
     * 2020.9.4
     */
    public function get_pay_method(){
        return Json::successlayui(UserPay::get_pay_method());
    }

    /**
     * 配置编辑
     */
    public function edit(){
        $id=osx_input('id',0,'intval');
        $type =1;
        $tab_id =db('user_pay')->where(['id'=>$id])->value('tab_id');
        if(!$tab_id) $tab_id = 1;
        $this->assign('tab_id',$tab_id);
        $list = ConfigModel::getAll($tab_id);
        if($type==3){//其它分类
            $config_tab = null;
        }else{
            $config_tab = ConfigModel::getConfigTabAll($type);
            foreach ($config_tab as $kk=>$vv){
                $arr = ConfigModel::getAll($vv['value'])->toArray();
                if(empty($arr)){
                    unset($config_tab[$kk]);
                }
            }
        }
        foreach ($list as $k=>&$v){
            if(!is_null(json_decode($v['value'])))
                $list[$k]['value'] = json_decode($v['value'],true);
            if($v['type'] == 'upload' && !empty($v['value'])){
                if($v['upload_type'] == 1 || $v['upload_type'] == 3) $list[$k]['value'] = explode(',',$v['value']);
            }
        }
        $this->assign('config_tab',$config_tab);
        $this->assign('list',$list);
        return $this->fetch('set');
    }

    /**
     * 保存数据    true
     * */
    public function save_base(){
        $request = Request::instance();
        if($request->isPost()){
            $post = $request->post();
            $tab_id = $post['tab_id'];
            unset($post['tab_id']);
            $post['wallet_agreement_content']=osx_input('wallet_agreement_content','','html');
            foreach ($post as $k=>$v){
                if(is_array($v)){
                    $res = ConfigModel::where('menu_name',$k)->column('type,upload_type');
                    foreach ($res as $kk=>$vv){
                        if($kk == 'upload'){
                            if($vv == 1 || $vv == 3){
                                $post[$k] = $v[0];
                            }
                        }
                    }
                }
            }
            foreach ($post as $k=>$v){
                ConfigModel::edit(['value' => json_encode($v)],$k,'menu_name');
            }
            return Json::successful('修改成功!');
        }
    }
    /**
     * 配置编辑
     */
    public function detail(){
        $id=osx_input('id',0,'intval');
        $type =1;
        $tab_id =db('user_pay')->where(['id'=>$id])->value('tab_id');
        if(!$tab_id) $tab_id = 1;
        $this->assign('tab_id',$tab_id);
        $list = ConfigModel::getAll($tab_id);
        if($type==3){//其它分类
            $config_tab = null;
        }else{
            $config_tab = ConfigModel::getConfigTabAll($type);
            foreach ($config_tab as $kk=>$vv){
                $arr = ConfigModel::getAll($vv['value'])->toArray();
                if(empty($arr)){
                    unset($config_tab[$kk]);
                }
            }
        }
        foreach ($list as $k=>$v){
            if(!is_null(json_decode($v['value'])))
                $list[$k]['value'] = json_decode($v['value'],true);
            if($v['type'] == 'upload' && !empty($v['value'])){
                if($v['upload_type'] == 1 || $v['upload_type'] == 3) $list[$k]['value'] = explode(',',$v['value']);
            }
        }
        $this->assign('config_tab',$config_tab);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 2020.9.23
     * @return mixed
     */
    public function set_limit(){
        $type =1;
        $tab_id=101;
        $list = ConfigModel::getAll($tab_id);
        if($type==3){//其它分类
            $config_tab = null;
        }else{
            $config_tab = ConfigModel::getConfigTabAll($type);
            foreach ($config_tab as $kk=>$vv){
                $arr = ConfigModel::getAll($vv['value'])->toArray();
                if(empty($arr)){
                    unset($config_tab[$kk]);
                }
            }
        }
        foreach ($list as $k=>$v){
            if(!is_null(json_decode($v['value'])))
                $list[$k]['value'] = json_decode($v['value'],true);
            if($v['type'] == 'upload' && !empty($v['value'])){
                if($v['upload_type'] == 1 || $v['upload_type'] == 3) $list[$k]['value'] = explode(',',$v['value']);
            }
            if($v['type']=='textarea'){
                $v['type']='myeditor';
            }
        }
        $this->assign('config_tab',$config_tab);
        $this->assign('tab_id',$tab_id);
        $this->assign('list',$list);
        return $this->fetch('set');
    }

    /***
     * 提现页面
     * @return mixed
     */
    public function withdraw_list(){
        $status=osx_input('status','','intval');
        $real_name=osx_input('real_name','','text');
        //整体内容
        $sum=db('withdraw_order')->where(['status'=>['in',[1,2]]])->sum('reality_money');
        $content[]=['name'=>'已提现金额','amount'=>$sum,'field'=>'￥'];
        $sum=db('withdraw_order')->where(['status'=>0])->sum('reality_money');
        $content[]=['name'=>'待审核提现金额','amount'=>$sum,'field'=>'急'];
        $sum=db('user_wallet')->where(['status'=>1])->sum('enable_money');
        $content[]=['name'=>'未提现金额','amount'=>$sum,'field'=>'待'];
        $this->assign([
            'status'=>$status,
            'real_name' =>$real_name,
            'year' => getMonth('y'),
            'content'=>$content,
        ]);
        return $this->fetch();
    }

    /**
     * 获取提现列表
     */
    public function get_withdraw_list(){
        $pam= Util::getMore([
            ['page',1],
            ['limit', 10],
            ['data',''],
            ['real_name',0],
            ['cid',0],
            ['status',-2],
        ]);
        $map['id']=['gt',0];
        if($pam['data']){
            $map['create_time']=ForumAudit::timeRange($pam['data']);
        }
        if($pam['real_name']){
            $map['order_id|account|name']=$pam['real_name'];
        }
        if($pam['cid']){
            $map['type']=$pam['cid'];
        }
        if($pam['status']!=-2){
            $map['status']=$pam['status'];
        }
        return Json::successlayui(WithdrawOrder::get_withdraw_order_list($map,$pam['page'],$pam['limit'],'create_time desc'));
    }

    /**
     * 审核内容
     */
    public function set_withdraw_status(){
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        $reason=osx_input('reason','','text');
        $withdraw=db('withdraw_order')->find($id);
        ModelBasic::beginTrans();
        $res=db('withdraw_order')->where(['id'=>$id])->update(['status'=>$status,'reason'=>$reason]);
        //变更记录
        $orderLog['order_id']=$withdraw['order_id'];
        $orderLog['uid_type']=1;
        $orderLog['uid']=$this->adminId;
        $res4=$res5=$res6=$res2=true;
        if($status==1){
            $orderLog['info']='审核通过';
            $res2=db('user_order')->where(['order_id'=>$withdraw['order_id']])->update(['status'=>2]);
        }else if($status==-1){
            $res2=db('user_order')->where(['order_id'=>$withdraw['order_id']])->update(['status'=>-1]);
            $res5=db('user_wallet')->where(['uid'=>$withdraw['uid']])->setInc('enable_money',$withdraw['money']);
            $res6=db('user_wallet')->where(['uid'=>$withdraw['uid']])->setDec('disable_money',$withdraw['money']);
            $res4=db('payment_profit')->where(['order_id'=>$withdraw['order_id']])->update(['status'=>-1]);
            $orderLog['info']='审核驳回';
        }else if($status==2){
            $orderLog['info']='打款成功';
            //用户冻结资金减少  总金额减少
            $res2=db('user_order')->where(['order_id'=>$withdraw['order_id']])->update(['status'=>1]);
            $res5=db('user_wallet')->where(['uid'=>$withdraw['uid']])->setDec('all_money',$withdraw['money']);
            $res6=db('user_wallet')->where(['uid'=>$withdraw['uid']])->setDec('disable_money',$withdraw['money']);
            $res4=db('payment_profit')->where(['order_id'=>$withdraw['order_id']])->update(['status'=>1]);
        }
        $res3=UserOrderLog::add_user_order_log($orderLog);
        if($res&&$res2&&$res3&&$res5&&$res4&&$res6){
            ModelBasic::commitTrans();
            return Json::success('审核成功');
        }else{
            ModelBasic::rollbackTrans();
            return Json::fail('审核失败');
        }
    }

    /**
     * 驳回
     * @return mixed
     */
    public function set_withdraw_fail(){
        $id=osx_input('id',0,'intval');
        $f = array();
        $f[] = Form::text('reason','驳回理由','');
        $f[] = Form::hidden('id',$id);
        $f[] = Form::hidden('status',-1);
        $form = Form::make_post_form('驳回',$f,Url::build('set_withdraw_status'));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }
    /***
     * 提现页面
     * @return mixed
     */
    public function payment_profit(){
        $status=osx_input('status',0,'intval');
        $real_name=osx_input('real_name','','text');
        //整体内容
        $sum=db('withdraw_order')->where(['status'=>2])->sum('money');
        $content[]=['name'=>'用户总提现金额','amount'=>$sum,'field'=>'元'];
        $sum=db('user_wallet')->where(['status'=>1])->sum('all_money');
        $content[]=['name'=>'用户钱包总额','amount'=>$sum,'field'=>'元'];
        $sum=db('payment_profit')->where(['create_time'=>['gt',0],'status'=>1])->sum('profit');
        $content[]=['name'=>'平台收益','amount'=>$sum,'field'=>'元'];
        $this->assign([
            'status'=>$status,
            'real_name' =>$real_name,
            'year' => getMonth('y'),
            'content'=>$content,
        ]);
        return $this->fetch();
    }

    /**
     * 获取收益列表
     * 2020.9.28
     */
    public function get_payment_profit_list(){
        $pam= Util::getMore([
            ['page',1],
            ['limit', 10],
        ]);
        $map['status']=1;
        return Json::successlayui(PaymentProfit::get_payment_profit_list($map,$pam['page'],$pam['limit'],'create_time desc'));
    }

    /**
     * 展示变更记录
     * 2020.9.30
     */
    public function show_order_log(){
        $id=osx_input('id',0,'intval');
        //type 0代表提现 1代表全部订单
        $type=osx_input('type',0,'intval');
        switch ($type){
            case 0:
                $order_id=db('withdraw_order')->where(['id'=>$id])->value('order_id');
                break;
            case 1:
                $order_id=db('user_order')->where(['id'=>$id])->value('order_id');
                break;
            default:return Json::fail('参数错误');
        }

        $list=UserOrderLog::where(['order_id'=>$order_id])->order('create_time desc')->select()->toArray();
        $uid=array_column($list,'uid');
        $user=db('user')->where(['uid'=>['in',$uid]])->field('uid,nickname')->select();
        $user=array_column($user,'nickname','uid');
        $admin_user=db('system_admin')->where(['id'=>['in',$uid]])->field('id,real_name')->select();
        $admin_user=array_column($admin_user,'real_name','id');
        foreach ($list as &$value){
            $value['create_time']=date('Y-m-d H:i:s',$value['create_time']);
            if($value['uid']==0){
                $value['name']='系统';
            }else{
                $value['name']=$value['uid_type']==0?'<span style="color:green">'.$user[$value['uid']].'</span>':'<span style="color:blue">'.$admin_user[$value['uid']].'</span>';
            }

        }
        $this->assign('list',$list);
        $this->assign('order_id',$order_id);
        return $this->fetch();
    }

    /**
     * 订单详情页
     * @return mixed
     */
    public function order_detail(){
        $id=osx_input('id',0,'intval');
        $order=db('user_order')->where(['id'=>$id])->find();
        $order['user']=db('user')->where(['uid'=>$order['uid']])->field('uid,nickname')->find();
        if($order['bind_table']=='withdraw_order'){
            $bind_data=db($order['bind_table'])->where(['order_id'=>$order['order_id']])->field('rate,money')->find();
            $order['bind_table_data']=bcdiv(bcmul($bind_data['money'],$bind_data['rate']),100,2);
        }else{
            $order['bind_table_data']= 0;
        }
        $order['pay_time']=$order['pay_time']?date('Y-m-d h:i:s',$order['pay_time']):'';
        $order['create_time']=date('Y-m-d h:i:s',$order['create_time']);
        $this->assign([
            'order'=>$order,
        ]);
        return $this->fetch();
    }
}