<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\card;


use app\admin\model\system\SystemConfig;
use app\admin\model\ump\StoreCouponUser;
use app\admin\model\wechat\WechatUser;
use app\admin\model\ump\StorePink;
use app\admin\model\order\StoreOrderCartInfo;
use app\admin\model\order\StoreOrder;
use app\admin\model\store\StoreProduct;
use app\admin\model\routine\RoutineFormId;
use app\core\model\routine\RoutineTemplate;
use service\ProgramTemplateService;
use service\PHPExcelService;
use traits\ModelTrait;
use basic\ModelBasic;
use service\WechatTemplateService;
use think\Url;
use think\Db;
use app\admin\model\user\User;
use app\admin\model\user\UserBill;
/**
 * 订单管理Model
 * Class StoreOrder
 * @package app\admin\model\store
 */
class CardExchange extends ModelBasic
{
    use ModelTrait;

}