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


class UserWallet extends ModelBasic
{
    use ModelTrait;

    public static function get_user_wallet_list($map,$page,$limit,$order)
    {
        $field = 'uid,all_money,enable_money,disable_money,status,token_ios,token_other,update_time';
        $data=self::where($map)->field($field)->page($page,$limit)->order($order)->select()->toArray();
        $uid=array_column($data,'uid');
        $nickname=db('user')->where(['uid'=>['in',$uid]])->field('uid,nickname')->select();
        $nickname=array_column($nickname,'nickname','uid');
        foreach ($data as &$v){
            $v['nickname']=array_key_exists($v['uid'],$nickname)?$nickname[$v['uid']]:'';
            $v['amount']=$v['all_money'].'(冻结'.$v['disable_money'].')';
            $v['update_time']=$v['update_time']>0?date('Y-m-d H:i:s',$v['update_time']):'未变动';
        }
        unset($v);
        $count=self::where($map)->count();
        return compact('data','count');
    }
}