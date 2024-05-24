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

use function GuzzleHttp\Psr7\str;

class UserOrder extends ModelBasic
{
    use ModelTrait;

    public static $orderType=['1'=>'社区消费','2'=>'社区收入','3'=>'商城消费','4'=>'充值','5'=>'提现','6'=>'退款'];

    public static function get_user_order_list($map,$page,$limit,$order)
    {
        $data = self::where($map)->page($page, $limit)->order($order)->select()->toArray();
        $uid = array_column($data, 'uid');
        $nickname = db('user')->where(['uid' => ['in', $uid]])->field('uid,nickname')->select();
        $nickname = array_column($nickname, 'nickname', 'uid');
        foreach ($data as &$v) {
            $v['nickname'] = array_key_exists($v['uid'], $nickname) ? $nickname[$v['uid']] : '';
//            状态2创建交易中1交易成功0交易关闭 -1交易失败
            switch ($v['status']) {
                case -1:
                    $v['status_name'] = '交易失败';
                    break;
                case 0:
                    $v['status_name'] = '交易关闭';
                    break;
                case 1:
                    $v['status_name'] = '交易成功';
                    break;
                case 2:
                    $v['status_name'] = '交易中';
                    break;
                default:
                    $v['status_name'] = '订单状态异常';
                    break;
            }
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            
            $v['pay_type']=$v['pay_type']==''?'': \app\ebapi\model\store\StoreOrder::$payType[$v['pay_type']];           
            $v['order_type_show']=self::$orderType[strval($v['order_type']) ];
            // case 'routine':$v['pay_type']='小程序支付';break;
            // case 'weixin':$v['pay_type']='微信';break;
            // case 'alipay':$v['pay_type']='支付宝';break;
            // case 'weixin_app':$v['pay_type']='微信';break;
            // default:$v['pay_type']='余额';;break;

            
        }
        unset($v);
        $count = self::where($map)->count();
        return compact('data', 'count');
    }

    /**
     * 创建退款订单
     * @param $data
     */
    public static function create_refund_order($data){
        $uid=get_uid();
        $data['order_id']='tk'.date('Ymdhis',time()).$uid.create_rand(4,'num');
        $data['unique']=md5($data['order_id']);
        $data['create_time']=$data['pay_time']=time();
        $data['status']=1;
        $data['order_type']=6;
        $data['info']='商品退款';
        $data['bind_table']='user_order';
        $data['amount_type']= 1;
        self::insertGetId($data);
    }
}