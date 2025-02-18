<?php
/**
 * Created by PhpStorm.
 * User: xurongyao <763569752@qq.com>
 * Date: 2019/4/8 5:41 PM
 */

namespace app\core\behavior;


use app\core\model\UserBill;
use app\core\model\UserLevel;
use service\HookService;
use app\core\model\User;
use think\Request;

class UserBehavior
{
    /** 用户访问记录
     * @param $userinfo
     */
    public static function init($userinfo)
    {
        $request=Request::instance();
        User::edit(['last_time'=>time(),'last_ip'=>$request->ip()],$userinfo->uid,'uid');
    }
    /**
     * 管理员后台给用户添加金额
     * @param $user
     * $user 用户信息
     * @param $money
     * $money 添加的金额
     */
    public static function adminAddMoneyAfter($user,$money){

    }

    /**
     * 管理员后台给用户减少金额
     * @param $user
     * $user 用户信息
     * @param $money
     * $money 减少的金额
     */
    public static function adminSubMoneyAfter($user,$money){

    }

    /**
     * 管理员后台给用户增加的积分
     * @param $user
     * $user 用户信息
     * @param $integral
     * $integral 增加的积分
     */
    public static function adminAddIntegralAfter($user,$integral){

    }

    /**
     * 管理员后台给用户减少的积分
     * @param $user
     * $user 用户信息
     * @param $integral
     * $integral 减少的积分
     */
    public static function adminSubIntegralAfter($user,$integral){

    }

    /*
     * 用是否可成为Vip
     * @param object $user 用户信息
     * */
    public static function userLevelAfter($user,$number)
    {
        return UserLevel::setLevelComplete($user['uid'],$number);
    }

}