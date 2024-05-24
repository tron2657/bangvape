<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\payment;

use app\admin\model\system\SystemConfig;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Db;


class UserOrderLog extends ModelBasic
{
    use ModelTrait;

    public static function add_user_order_log($data)
    {
        $data['create_time']=time();
        return self::insert($data);
    }

}