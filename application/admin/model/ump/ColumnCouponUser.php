<?php
/**
 * 知识商城优惠券发放记录
 * 2020-10
 */

namespace app\admin\model\ump;

use app\admin\model\wechat\WechatUser as UserModel;
use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponUser extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';

    public static function systemPage($where){
        $model = new self;
        if($where['status'] != '')  $model = $model->where('status',$where['status']);
        if($where['is_fail'] != '')  $model = $model->where('status',$where['is_fail']);
        if($where['coupon_title'] != '')  $model = $model->where('coupon_title','LIKE',"%$where[coupon_title]%");
        if($where['nickname'] != ''){
            $uid = UserModel::where('nickname','LIKE',"%$where[nickname]%")->column('uid');
            $model = $model->where('uid','IN',implode(',',$uid));
        }
        $model = $model->order('id desc');
        return self::page($model,function ($item){
            $item['nickname'] = UserModel::where('uid',$item['uid'])->value('nickname');
        },$where);
    }

    public static function setCoupon($coupon,$user){
        $data = array();
        foreach ($user as $k=>$v){
            $data[$k]['cid'] = $coupon['id'];
            $data[$k]['uid'] = $v;
            $data[$k]['coupon_title'] = $coupon['title'];
            $data[$k]['coupon_price'] = $coupon['coupon_price'];
            $data[$k]['use_min_price'] = $coupon['use_min_price'];
            $data[$k]['add_time'] = time();
            $data[$k]['end_time'] = $data[$k]['add_time']+$coupon['coupon_time']*86400;
        }
        $data_num = array_chunk($data,30);
        self::beginTrans();
        $res = true;
        foreach ($data_num as $k=>$v){
            $res = $res && self::insertAll($v);
        }
        self::checkTrans($res);
        return $res;
    }
}