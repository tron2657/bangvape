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


class PaymentProfit extends ModelBasic
{
    use ModelTrait;

    public static function get_payment_profit_list($map,$page,$limit,$order)
    {
        $data=self::where($map)->page($page,$limit)->order($order)->select();
        foreach ($data as &$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            switch ($v['status']){
                case 0:$v['status_name']='待审核';break;
                case 1:$v['status_name']='已审核';break;
                case 2:$v['status_name']='已打款审核';break;
                case -1:$v['status_name']='已驳回';break;
                default:$v['status_name']='状态错误';
            }
        }
        $count=self::where($map)->count();
        return compact('data','count');
    }

}