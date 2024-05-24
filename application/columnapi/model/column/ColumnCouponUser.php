<?php
/**
 * 知识商城优惠券发放记录表
 * 2020-10
 */

namespace app\columnapi\model\column;


use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponUser extends ModelBasic
{
    use ModelTrait;

    /**
     * 获取用户优惠券（全部）
     * @param $uid
     * @return mixed
     */
    public static function getUserAllCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('uid',$uid)->where('is_fail',0)->order('is_fail ASC,status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }

    /**
     * 获取用户优惠券（未使用）
     * @param $uid
     * @return mixed
     */
    public static function getUserValidCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('uid',$uid)->where('status',0)->where('is_fail',0)->order('is_fail ASC,status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }

    /**
     * 获取用户优惠券（已使用）
     * @param $uid
     * @return mixed
     */
    public static function getUserAlreadyUsedCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('uid',$uid)->where('status',1)->where('is_fail',0)->order('is_fail ASC,status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }

    /**
     * 获取用户优惠券（已过期）
     * @param $uid
     * @return mixed
     */
    public static function getUserBeOverdueCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('uid',$uid)->where('status',2)->where('is_fail',0)->order('is_fail ASC,status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }

    /**
     * 获取用户可以使用的优惠券
     * @param $uid
     * @param int $price
     * @return array
     */
    public static function beUsableCouponList($uid,$price=0){
        $list=self::where('uid',$uid)->where('is_fail',0)->where('status',0)->where('use_min_price','<=',$price)->select();
        $list=count($list) ? $list->toArray() : [];
        foreach ($list as &$item){
            $item['add_time']=date('Y/m/d',$item['add_time']);
            $item['end_time']=date('Y/m/d',$item['end_time']);
        }
        return $list;
    }

    public static function validAddressWhere($model=null,$prefix = '')
    {
        self::checkInvalidCoupon();
        if($prefix) $prefix .='.';
        $model = self::getSelfModel($model);
        return $model->where("{$prefix}is_fail",0)->where("{$prefix}status",0);
    }

    /**
     * 设置已过期优惠券
     */
    public static function checkInvalidCoupon()
    {
        self::where('end_time','<',time())->where('status',0)->update(['status'=>2]);
    }

    public static function tidyCouponList($couponList)
    {
        $time = time();
        foreach ($couponList as $k=>$coupon){
            $coupon['_add_time'] = date('Y/m/d',$coupon['add_time']);
            $coupon['_end_time'] = date('Y/m/d',$coupon['end_time']);
            $coupon['use_min_price'] = number_format($coupon['use_min_price'],2);
            $coupon['coupon_price'] = number_format($coupon['coupon_price'],2);
            if($coupon['is_fail']){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已失效';
            }else if ($coupon['status'] == 1){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已使用';
            }else if ($coupon['status'] == 2){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已过期';
            }else if($coupon['add_time'] > $time || $coupon['end_time'] < $time){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已过期';
            }else{
                if($coupon['add_time']+ 3600*24 > $time){
                    $coupon['_type'] = 2;
                    $coupon['_msg'] = '可使用';
                }else{
                    $coupon['_type'] = 1;
                    $coupon['_msg'] = '可使用';
                }
            }
            $couponList[$k] = $coupon;
        }
        return $couponList;
    }

    public static function useCoupon($id)
    {
        return self::where('id',$id)->update(['status'=>1,'use_time'=>time()]);
    }

    /**
     * 发放优惠券
     * @param $uid
     * @param $cid
     * @param string $type
     * @return bool|object
     */
    public static function addUserCoupon($uid,$cid,$type = 'get')
    {
        $couponInfo = ColumnCoupon::find($cid);
        if(!$couponInfo) return self::setErrorInfo('优惠劵不存在!');
        $data = [];
        $data['cid'] = $couponInfo['id'];
        $data['uid'] = $uid;
        $data['coupon_title'] = $couponInfo['title'];
        $data['coupon_price'] = $couponInfo['coupon_price'];
        $data['use_min_price'] = $couponInfo['use_min_price'];
        $data['add_time'] = time();
        $data['end_time'] = $data['add_time']+$couponInfo['coupon_time']*86400;
        $data['type'] = $type;
        return self::set($data);
    }
}