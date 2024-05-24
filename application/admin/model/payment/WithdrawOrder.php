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


class WithdrawOrder extends ModelBasic
{
    use ModelTrait;

    public static function get_withdraw_order_list($map,$page,$limit,$order)
    {
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        $uids=array_column($data,'uid');
        $users=db('user')->where(['uid'=>['in',$uids]])->field('uid,nickname,phone')->select();
        $users=array_column($users,null,'uid');
        foreach ($data as &$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            switch ($v['status']){
                case 0:$v['status_name']='<span style="color:#F0C98E ">待审核</span>';break;
                case 1:$v['status_name']='<span style="color:#7FBEFF ">已审核</span>';break;
                case 2:$v['status_name']='<span style="color:#E3E3E3 ">已打款审核</span>';break;
                case -1:$v['status_name']='<span style="color:#F56C6C ">已驳回</span>';break;
                default:$v['status_name']='状态错误';
            }
            $v['user_message']=isset($users[$v['uid']])?'<span>'.$users[$v['uid']]['nickname'].'</span></br><span>'.$users[$v['uid']]['phone'].'</span>':'';
            if($v['type']=='wechat'){
                $v['type']='<span style="color:#67C23A">微信</span></br>';
            }elseif($v['type']=='alipay'){
                $v['type']='<span style="color:#5AABFF">支付宝</span></br>';
            }
            $v['type'].='账号:'.$v['account'].'</br>姓名:'.$v['name'];
        }
        $count=self::where($map)->count();
        return compact('count', 'data');
    }

    public static function get_user_withdraw_order_list($map,$page,$limit,$order)
    {
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        $uids=array_column($data,'uid');
        $users=db('user')->where(['uid'=>['in',$uids]])->field('uid,nickname,phone')->select();
        $users=array_column($users,null,'uid');
        foreach ($data as &$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            switch ($v['status']){
                case 0:$v['status_name']='待审核';break;
                case 1:$v['status_name']='已审核';break;
                case 2:$v['status_name']='已打款审核';break;
                case -1:$v['status_name']='已驳回';break;
                default:$v['status_name']='状态错误';
            }
            $v['user_message']=isset($users[$v['uid']])?'<span>'.$users[$v['uid']]['nickname'].'</span></br><span>'.$users[$v['uid']]['phone'].'</span>':'';
            if($v['type']=='wechat'){
                $v['type']='[微信]';
            }elseif($v['type']=='alipay'){
                $v['type']='[支付宝]';
            }
            $v['type'].=' 姓名:'.$v['name'].' '.$v['status_name'];
            $v['unique']=md5($v['order_id']);
        }
        $count=self::where($map)->count();
        return compact('count', 'data');
    }

}