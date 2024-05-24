<?php
namespace app\payapi\controller;

use app\core\util\MiniProgramService;
use app\core\util\WechatService;
use think\Request;

/**
 * 支付回调
 * Class Notify
 * @package app\ebapi\controller
 */
//待完善
class Notify
{
    /**
     * 2020.9.21
     * 微信支付回调
     */
    public function notify(){
        $request=Request::instance();
        switch (strtolower($request->param('notify_type','weixin'))){
            case 'weixin':
                WechatService::handleNotifyNew();
                break;
            case 'routine': //小程序支付回调

                break;
            case 'alipay':
                break;
            default:
                echo 121;
                break;
        }
    }
}


