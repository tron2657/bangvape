<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/21
 */

namespace app\ebapi\model\user;

use app\core\model\UserBill;
use app\ebapi\model\store\StoreOrder;
use basic\ModelBasic;
use app\core\util\SystemConfigService;
use think\Request;
use think\Session;
use traits\ModelTrait;

/**
 * 用户
 * Class User
 * @package app\routine\model\user
 */
class UserWallet extends ModelBasic
{
    use ModelTrait;
}
