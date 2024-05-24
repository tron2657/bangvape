<?php

namespace app\core\logic;

use app\core\util\MiniProgramService;
use app\core\util\WechatAppService;
use app\core\util\WechatService;
use app\core\util\WechatServiceCard;
use think\Request;

/**
 * Created by PhpStorm.
 * User: xurongyao <763569752@qq.com>
 * Date: 2019/4/8 5:48 PM
 */
class Pay
{
    public static function notify(){
        $request=Request::instance();
        $type=strtolower($request->param('notify_type','wenxin'));
        //微信没返回参数，是默认值
        switch ($type){
            case 'wenxin':
                WechatService::handleNotify();
                break;
            case 'routine.htm':
            case 'routine': //小程序支付回调
                MiniProgramService::handleNotify();
                break;
            case 'alipay':
                break;
            default:
                echo 121;
                break;
        }
    }

    public static function notifyApp(){
        $request=Request::instance();
        //微信没返回参数，是默认值
        switch (strtolower($request->param('notify_type','wxpay'))){
            case 'wxpay'://微信App支付
                WechatAppService::handleNotify();
                break;
            case 'alipay':
                break;
            default:
                echo 121;
                break;
        }
    }

    public static function notifyShop(){
        $request=Request::instance();
        switch (strtolower($request->param('notify_type','wenxin'))){
            case 'wenxin':
                WechatService::handleNotifyShop();
                break;
            case 'routine': //小程序支付回调
                MiniProgramService::handleNotifyShop();
                break;
            case 'alipay':
                break;
            default:
                echo 121;
                break;
        }
    }

    /**
     * 积分卡
     */
    public static function notifyCard(){
        $request=Request::instance();
        switch (strtolower($request->param('notify_type','wenxin'))){
            case 'wenxin':
                WechatServiceCard::handleNotifyCard();
                break;
            case 'routine': //小程序支付回调
                // MiniProgramService::handleNotifyShop();
                WechatServiceCard::handleNotifyCard();
                break;
            case 'alipay':
                break;
            default:
                echo 121;
                break;
        }
    }

}