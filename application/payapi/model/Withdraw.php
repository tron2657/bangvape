<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/11/18
 * Time: 17:08
 */

namespace app\payapi\model;


use app\admin\model\payment\UserOrderLog;
use basic\ModelBasic;
use Guzzle\Service\Resource\Model;
use traits\ModelTrait;

class Withdraw extends ModelBasic
{
    use ModelTrait;
    protected $table='osx_withdraw_order';

    /**
     * 提现内容
     * @param $data
     * @return bool
     */
    public static function set_withdraw($data)
    {
        self::beginTrans();
        $res=self::insert($data);
        //支付系统订单
        $user_order['uid']=$data['uid'];
        $user_order['order_id']=$data['order_id'];
        $user_order['unique']=md5($data['order_id']);
        $user_order['create_time']=$data['create_time'];
        $user_order['info']='钱包提现';
        $user_order['amount']=$data['money'];
        $user_order['amount_type']=0;
        $user_order['status']=0;
        $user_order['bind_table']='withdraw_order';
        $user_order['order_type']=5;
        $user_order['pay_type']='yue';
        $res1=db('user_order')->insert($user_order);
        //系统收益订单
        if(bccomp($data['money'],$data['reality_money'],2)==1){
            $profit['order_id']=$data['order_id'];
            $profit['create_time']=$data['create_time'];
            $profit['info']='提现';
            $profit['status']=0;
            $profit['amount']=$data['money'];
            $profit['profit']=bcsub($data['money'],$data['reality_money']);
            $res2=db('payment_profit')->insert($profit);
        }else{
            $res2=true;
        }
        $res_en=db('user_wallet')->where(['uid'=>$data['uid']])->setDec('enable_money',$data['money']);
        $res_dis=db('user_wallet')->where(['uid'=>$data['uid']])->setInc('disable_money',$data['money']);
        //操作变更
        $orderLog['order_id']=$data['order_id'];
        $orderLog['uid_type']=0;
        $orderLog['uid']=$data['uid'];
        $orderLog['info']='发起申请';
   
        $res3=UserOrderLog::add_user_order_log($orderLog);
        if($res&&$res1&&$res2&&$res3&&$res_en&&$res_dis){
            self::commitTrans();
            return true;
        }else{
            self::rollbackTrans();
            return false;
        }
    }
}