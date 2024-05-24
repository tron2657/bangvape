<?php

namespace app\admin\controller\ump;

use app\admin\controller\AuthController;
use app\admin\model\wechat\WechatUser;
use service\UtilService as Util;
use service\JsonService as Json;
use app\admin\model\ump\ColumnCoupon as ColumnCouponModel;
use app\admin\model\ump\ColumnCouponUser as ColumnCouponUserModel;
use app\admin\model\wechat\WechatUser as UserModel;
use think\Request;

/**
 * 优惠券发放记录控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ColumnCouponUser extends AuthController
{

    /**
     * @return mixed
     */
    public function index()
    {
        $where = Util::getMore([
            ['status',''],
            ['is_fail',''],
            ['coupon_title',''],
            ['nickname',''],
        ],$this->request);
        $this->assign('where',$where);
        $this->assign(ColumnCouponUserModel::systemPage($where));
        return $this->fetch();
    }

    /**
     * 给已关注的用户发放优惠券
     * @param $id
     */
    public function grant_subscribe($id){
        if(!$id) return Json::fail('数据不存在!');
        $coupon = ColumnCouponModel::get($id)->toArray();
        if(!$coupon) return Json::fail('数据不存在!');
        $user = UserModel::getSubscribe('uid');
        if(!ColumnCouponUserModel::setCoupon($coupon,$user))
            return Json::fail(ColumnCouponUserModel::getErrorInfo('发放失败,请稍候再试!'));
        else
            return Json::successful('发放成功!');
    }

    /**
     * 给所有人发放优惠券
     * @param $id
     */
    public function grant_all($id){
        if(!$id) return Json::fail('数据不存在!');
        $coupon = ColumnCouponModel::get($id)->toArray();
        if(!$coupon) return Json::fail('数据不存在!');
        $user = UserModel::getUserAll('uid');
        if(!ColumnCouponUserModel::setCoupon($coupon,$user))
            return Json::fail(ColumnCouponUserModel::getErrorInfo('发放失败,请稍候再试!'));
        else
            return Json::successful('发放成功!');
    }

    /**
     * 发放优惠券到指定个人
     * @param $id
     * @param $uid
     * @return \think\response\Json
     */
    public function grant($id,$uid){
        if(!$id) return Json::fail('数据不存在!');
        $coupon = ColumnCouponModel::get($id)->toArray();
        if(!$coupon) return Json::fail('数据不存在!');
        $user = explode(',',$uid);
        if(!ColumnCouponUserModel::setCoupon($coupon,$user))
            return Json::fail(ColumnCouponUserModel::getErrorInfo('发放失败,请稍候再试!'));
        else
            return Json::successful('发放成功!');

    }

    public function grant_group($id,Request $request){
        $data = Util::postMore([
            ['group',0]
        ],$request);
        if(!$id) return Json::fail('数据不存在!');
        $coupon = ColumnCouponModel::get($id)->toArray();
        if(!$coupon) return Json::fail('数据不存在!');
        $user = WechatUser::where('groupid',$data['group'])->column('uid','uid');
        if(!ColumnCouponUserModel::setCoupon($coupon,$user))
            return Json::fail(ColumnCouponUserModel::getErrorInfo('发放失败,请稍候再试!'));
        else
            return Json::successful('发放成功!');
    }

    public function grant_tag($id,Request $request){
        $data = Util::postMore([
            ['tag',0]
        ],$request);
        if(!$id) return Json::fail('数据不存在!');
        $coupon = ColumnCouponModel::get($id)->toArray();
        if(!$coupon) return Json::fail('数据不存在!');
        $user = WechatUser::where("tagid_list","LIKE","%$data[tag]%")->column('uid','uid');
        if(!ColumnCouponUserModel::setCoupon($coupon,$user))
            return Json::fail(ColumnCouponUserModel::getErrorInfo('发放失败,请稍候再试!'));
        else
            return Json::successful('发放成功!');
    }

}
