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
use app\ebapi\model\store\StoreOrder;
use app\shopapi\model\shop\ShopOrder;
use basic\ModelBasic;
use traits\ModelTrait;
use app\wap\model\store\StoreOrder as StoreOrderWapModel;

class UserOrder extends ModelBasic
{
    use ModelTrait;

    /**
     * 获取订单列表
     * @param $map
     * @param $page
     * @param $limit
     * @param $order
     * @param $map_or
     * @return mixed
     *
     */
    public static function get_order_list($map,$page,$limit,$order,$map_or)
    {
        if($map_or!=null && count($map_or)){
            $data = self::whereOr(function($query) use($map) {
                $query->where($map);
            })->whereOr(function($query) use($map_or){
                $query->where($map_or);
            })->page($page,$limit)->order($order)->select()->toArray();
        }else{
            $data = self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        }
        foreach ($data as &$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            $v['pay_time']=date('Y-m-d H:i:s',$v['pay_time']);
        }
        unset($v);
        return $data;
    }

    /**
     * 获取订单内容
     * @param $uid
     * @param $key
     * @return array
     */
    public static function get_order($uid,$key){
        $uid=$uid?$uid:get_uid();
        $data=self::where(['unique'=>$key,'uid'=>$uid])->find();
        return $data;
    }

    /**
     * 订单支付后的内容
     * @param $order_id 订单号
     * @param $pay_type 支付类型
     * @param $notify
     * @return bool
     */
    public static function beforeOrder($order_id,$pay_type,$notify=''){
        self::beginTrans();
        //回调内容记录
        if($notify){
            $notify_data=[
                'create_time'=>time(),
                'status'=>1,
                'order_id'=>$order_id,
                'content'=>json_encode($notify),
                'pay_type'=>$pay_type
            ];
            $res1=db('user_order_callback_log')->insert($notify_data);
        }else{
            $res1=true;
        }

        $order=self::where(['order_id'=>$order_id])->find();
        $res3=$res4=true;
//        订单类型1社区消费2社区收入3商城消费4充值5提现6退款
        switch ($order['order_type']){
            case 1:
                //todo 功能暂无
                $res2=true;
                break;
            case 3:
                if($order['bind_table']=='shop_order'){
                    $res2=ShopOrder::paySuccess($order_id,'yue');
                }elseif($order['bind_table']=='zg_goods_order'){
                    $res2=StoreOrder::paySuccess($order_id,'yue');
                    db('store_order')->where(['order_id'=>$order_id])->update(['status'=>3]);
                }else{
                    $res2=StoreOrderWapModel::paySuccess($order_id);
                }
                break;
            case 4:
                $res_all=db('user_wallet')->where(['uid'=>$order['uid']])->setInc('all_money',$order['amount']);
                $res_enable=db('user_wallet')->where(['uid'=>$order['uid']])->setInc('enable_money',$order['amount']);
                if($res_all&&$res_enable){
                    $res2=true;
                }else{
                    $res2=false;
                }
                break;
            default:$res2=false;
        }
        if($order['order_type']!==3){
            //操作变更
            $orderLog['order_id']=$order_id;
            $orderLog['uid_type']=0;
            $orderLog['uid']=get_uid();
            $orderLog['info']='支付成功';
            $res3=UserOrderLog::add_user_order_log($orderLog);
            $res4=self::where(['order_id'=>$order_id])->update(['pay_type'=>'yue','status'=>1,'pay_time'=>time()]);
        }
        if($res2&&$res1&&$res3&&$res4){
            self::commitTrans();
            return true;
        }else{
            self::rollbackTrans();
            return false;
        }
    }

    /**
     * 余额支付
     * @param $order_id 支付类型
     * @param $uid
     * @return bool
     * 2020.9.22
     */
    public static function pay_order($order_id,$uid){
        self::beginTrans();
        $sum_money=self::where(['order_id'=>$order_id])->sum('amount');
        $bind_table=self::where(['order_id'=>$order_id])->value('bind_table');
        //特殊情况
        if($bind_table=='zg_goods_order'){
            $bind_table='store_order';
        }
        $res3=db($bind_table)->where(['order_id'=>$order_id])->update(['pay_type'=>'yue']);
        $res1=db('user_wallet')->where(['uid'=>$uid])->setDec('all_money',$sum_money);
        $res2=db('user_wallet')->where(['uid'=>$uid])->setDec('enable_money',$sum_money);
        if(!($res1&&$res2&&$res3)){
            self::rollbackTrans();
            return false;
        }
        $res=self::beforeOrder($order_id,'yue','');
        self::where(['order_id'=>$order_id])->update(['status'=>1]);
        if($res){
            self::commitTrans();
            return true;
        }else{
            self::rollbackTrans();
            return false;
        }
    }

    /**
     * 创建退款订单
     * @param $data
     */
    public static function create_refund_order($data){
        $uid=get_uid();
        $data['order_id']='tk'.date('Ymdhis',time()).$uid.create_rand(4,'num');
        $data['unique']=md5($data['order_id']);
        $data['create_time']=$data['pay_time']=time();
        $data['status']=1;
        $data['order_type']=6;
        $data['info']='商品退款';
        $data['bind_table']='user_order';
        $data['amount_type']= 1;
        self::insertGetId($data);
    }
}