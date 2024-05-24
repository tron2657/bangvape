<?php

namespace app\columnapi\model\column;

use think\Cache;
use think\Model;
use basic\ModelBasic;
use app\columnapi\model\store\StoreCart;

class ColumnUserBuy extends ModelBasic
{

    /**
     * 我的已购
     * qhy
     */
    public static function getBuyList($is_column,$uid,$order){
        self::checkColumnBuy($uid);
        $list=self::where('uid',$uid)->where('is_column',$is_column)->where('status',1)->order($order)->select();
        foreach($list as $k=>&$value){
            $product=ColumnText::where('id',$value['pid'])->where('status',1)->where('is_show',1)->find();
            if($product){
                $product['author']=db('column_author')->where('id',$product['author_id'])->find();
                $product['image'] = ColumnText::checkImages($product['image']);
                $value['product']=$product;
            }else{
                array_splice($list,$k,1);
                continue;
            }
            $value['read_time']=time_format($value['read_time']);
        }
        unset($k);
        unset($value);
        return $list;
    }

    /**
     * 检查知识已购
     * @param $uid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author xzj
     * @date 2020/10/23
     */
    public static function checkColumnBuy($uid) {
        $list=self::where('uid',$uid)->order('id desc')->select();
        // 去重
        if (!empty($list)) {
            $pids = [];
            $deleted = false;
            foreach ($list as $bv) {
                if (in_array($bv['pid'],$pids)) {
                    self::where('id', $bv['id'])->delete();
                    $deleted = true;
                } else {
                    $pids[] = $bv['pid'];
                }
            }
            if ($deleted) $list=self::where('uid',$uid)->order('id desc')->select();
        }
        foreach ($list as $k => $v) {
            $product=ColumnText::where('id',$v['pid'])->where('status',1)->where('is_show',1)->find();
            if (!$product) {
                self::where('id', $v['id'])->update(['status'=>0]);
                continue;
            }
            if ($product['is_free'] == 1) {
                if ($v['is_free'] != 1 || $v['status'] != 1) {
                    self::where('id', $v['id'])->update(['is_free'=>1,'status'=>1]);
                }
            } else {
                if ($v['is_free'] != 0) self::where('id', $v['id'])->update(['is_free'=>0]);
                $order = db('store_order')->alias('a')->join('store_order_cart_info b','a.id=b.oid')
                    ->where('b.product_id',$v['pid'])->where('a.uid',$uid)->where('a.paid',1)->field('a.id,a.order_id')->find();
                if (empty($order) && $v['status'] == 1) {
                    self::where('id', $v['id'])->update(['status'=>0]);
                }
                if (!empty($order) && $v['status'] == 0) {
                    self::where('id', $v['id'])->update(['status'=>1]);
                }
            }
        }
    }

    /**
     * 检查知识商品购买记录
     * @param $uid
     * @param $pid
     * @return bool
     * @author xzj
     * @date 2020/10/27
     */
    public static function checkColumnBuyById($uid,$pid)
    {
        $count = self::where('uid',$uid)->where('pid',$pid)->count();
        if ($count < 1) return false;
        $buy = self::where('uid',$uid)->where('pid',$pid)->order('id desc')->find()->toArray();
        if ($count > 1) {
            self::where('id', '<', $buy['id'])->where('uid',$uid)->where('pid',$pid)->delete();
        }
        $product=ColumnText::where('id',$pid)->where('status',1)->where('is_show',1)->find();
        if (!$product) {
            self::where('id', $buy['id'])->update(['status'=>0]);
            return false;
        }
        if ($product['is_free'] == 1) {
            if ($buy['is_free'] != 1 || $buy['status'] != 1) {
                self::where('id', $buy['id'])->update(['is_free'=>1,'status'=>1]);
            }
        } else {
            if ($buy['is_free'] != 0) self::where('id', $buy['id'])->update(['is_free'=>0]);
            $order = db('store_order')->alias('a')->join('store_order_cart_info b','a.id=b.oid')
                ->where('b.product_id',$pid)->where('a.uid',$uid)->where('a.paid',1)->field('a.id,a.order_id')->find();
            if (empty($order) && $buy['status'] == 1) {
                self::where('id', $buy['id'])->update(['status'=>0]);
            }
            if (!empty($order) && $buy['status'] == 0) {
                self::where('id', $buy['id'])->update(['status'=>1]);
            }
        }
        return true;
    }

    /**
     * 购买免费商品
     * qhy
     */
    public static function buyFree($id,$uid){
        $column=db('column_text')->where('id',$id)->field('is_column,is_free,pid')->find();
        if($column['is_free']!=1){
            return false;
        }
        if($column['pid']!=0){
            return false;
        }
        $map['uid']=$uid;
        $map['pid']=$id;
        $map['is_free']=$column['is_free'];
        $map['is_column']=$column['is_column'];
        $map['status']=1;
        $map['is_new']=0;
        $map['create_time']=time();
        $map['read_time']=time();
        $res=db('column_user_buy')->insert($map);
        $cart = StoreCart::setCart($uid,$id,1,'','is_zg',1,0,0,0);
        if ($cart->id) StoreCart::edit(['is_pay' => 1], $cart->id);
        return $res;
    }

}
