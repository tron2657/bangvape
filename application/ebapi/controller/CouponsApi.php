<?php
namespace app\ebapi\controller;

use app\admin\model\user\MemberCouponPlan;
use app\admin\model\user\User;
use app\ebapi\model\store\StoreCouponIssue;
use app\ebapi\model\store\StoreCouponUser;
use service\JsonService;
use app\core\util\SystemConfigService;
/**
 * 小程序优惠券api接口
 * Class CouponsApi
 * @package app\routine\controller
 *
 */
class CouponsApi extends AuthController
{

   

    /**
     * 获取用户优惠券
     * @return \think\response\Json
     */
     /**
     * @api {post} /ebapi/coupons_api/get_use_coupons 花间一壶酒活动-兑酒流程.优惠券列表
     * @apiName get_use_coupons
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} types 类型0所有 1未使用 2已使用 3已过期
     */
    public function get_use_coupons()
    {
        $types=osx_input('types','','text');
        $coupon_ids=null;
        $user=User::where('uid',$this->userInfo['uid'])->find();
        if($user['is_bus']==1)
        {
            $query=MemberCouponPlan::where('uid',$this->userInfo['uid']);
            $coupon_ids= $query->where('seq',1)->column('cuid');
            // $count=$query->where('seq','>',1)->where('is_fail',0)->update(['is_fail'=>'1']);
        }
        switch ($types){
            case 0:case '':
                $list= StoreCouponUser::getUserAllCoupon($this->userInfo['uid'],$coupon_ids);
                break;
            case 1:
                $list=StoreCouponUser::getUserValidCoupon($this->userInfo['uid']);
                break;
            case 2:
                $list=StoreCouponUser::getUserAlreadyUsedCoupon($this->userInfo['uid']);
                break;
            default:
                $list=StoreCouponUser::getUserBeOverdueCoupon($this->userInfo['uid']);
                break;
        }
        foreach ($list as &$v){
            $v['add_time'] = date('Y/m/d',$v['add_time']);
            $v['end_time'] = date('Y/m/d',$v['end_time']);
        }
        return JsonService::successful($list);
    }
    /**
     * 获取用户优惠券
     * @return \think\response\Json
     */
    public function get_use_coupon(){

        return JsonService::successful('',StoreCouponUser::getUserAllCoupon($this->userInfo['uid']));
    }

    /**
     * 获取可以使用的优惠券
     * @return \think\response\Json
     */
    public function get_use_coupon_order()
    {
        $totalPrice=osx_input('totalPrice',0,'intval');
        if($this->isVip)
        {        
            $vip_discount=SystemConfigService::get('membership_vip_discount');
            $totalPrice=$totalPrice*$vip_discount;
        }
     
        return JsonService::successful(StoreCouponUser::beUsableCouponList($this->userInfo['uid'],$totalPrice));
    }


    /**
     * 领取优惠券
     * @return \think\response\Json
     */
    public function user_get_coupon()
    {
        $couponId=osx_input('couponId',0,'intval');
        if(!$couponId || !is_numeric($couponId)) return JsonService::fail('参数错误!');
        if(StoreCouponIssue::issueUserCoupon($couponId,$this->userInfo['uid'])){
            return JsonService::successful('领取成功');
        }else{
            return JsonService::fail(StoreCouponIssue::getErrorInfo('领取失败!'));
        }
    }

    /**
     * 获取一条优惠券
     * @return \think\response\Json
     */
    public function get_coupon_rope(){
        $couponId=osx_input('couponId',0,'intval');
        if(!$couponId) return JsonService::fail('参数错误');
        $couponUser = StoreCouponUser::validAddressWhere()->where('id',$couponId)->where('uid',$this->userInfo['uid'])->find();
        return JsonService::successful($couponUser);
    }
    /**
     * 获取  可以领取的优惠券
     * @return \think\response\Json
     */
    public function get_issue_coupon_list()
    {
        $limit=osx_input('limit',2,'intval');
        return JsonService::successful(StoreCouponIssue::getIssueCouponList($this->uid,$limit));
    }
}