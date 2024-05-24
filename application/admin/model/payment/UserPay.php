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


class UserPay extends ModelBasic
{
    use ModelTrait;

    public static function get_pay_method()
    {
        $data=self::where(['status'=>1])->select();
        foreach ($data as $v){
            $v['method']= SystemConfig::getValue( $v['method']);
            $v['name']= SystemConfig::getValue( $v['name']);
            $v['business']= SystemConfig::getValue( $v['bus']);
        }
        unset($v);
        $count=self::where(['status'=>1])->count();
        return compact('data','count');
    }

}