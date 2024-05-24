<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\column;

use traits\ModelTrait;
use basic\ModelBasic;

/**
 * 评论管理 model
 * Class StoreProductReply
 * @package app\admin\model\store
 */
class ColumnProductReply extends ModelBasic
{
    use ModelTrait;

    protected function getPicsAttr($value)
    {
        return json_decode($value,true);
    }
    /**
     * @param $where
     * @return array
     */
    public static function systemPage($where,$is_type = 0){
        /*$model = new self;
        if($where['comment'] != '')  $model = $model->where('r.comment','LIKE',"%$where[comment]%");
        if($where['is_reply'] != ''){
            if($where['is_reply'] >= 0){
                $model = $model->where('r.is_reply',$where['is_reply']);
            }else{
                $model = $model->where('r.is_reply','GT',0);
            }
        }
        if($where['product_id'])  $model = $model->where('r.product_id',$where['product_id']);
        $model = $model->alias('r')->join('__USER__ u','u.uid=r.uid');
        $model = $model->join('__COLUMN_TEXT__ p','p.id=r.product_id');
        $model = $model->where('r.is_del',0);
        $model = $model->where('p.status',1)->where('p.is_show',1);
        $model = $model->field('r.*,u.nickname,u.avatar,p.name as store_name');
        $model = $model->order('r.add_time desc,r.is_reply asc');
        return self::page($model,function($itme){

        },$where);*/

        $map['r.is_del'] = 0;
        $map['p.status'] = 1;
        $map['p.is_show'] = 1;
        if($where['comment'] != '') $map['r.comment'] = ['LIKE',"%$where[comment]%"];
        if($where['is_reply'] != '') $map['r.is_reply'] = $where['is_reply'] >= 0 ? $where['is_reply'] : ['GT', 0];
        if($where['product_id']) $map['r.product_id'] = $where['product_id'];
        $list = db('column_product_reply')->alias('r')->join('column_text p', 'p.id=r.product_id')
            ->where($map)->field('r.*,p.name as store_name')->order('r.add_time desc,r.is_reply asc')->select();
        foreach ($list as &$v) {
            $user = db('user')->where('uid',$v['uid'])->field('nickname,avatar')->find();
            $v['nickname'] = $user ? $user['nickname'] : '';
            $v['avatar'] = $user ? $user['avatar'] : '';
        }
        $page = 1;
        $total = empty($list) ? 0 : count($list);
        return compact('list','page','total');
    }

    public static function order_list($map,$page,$limit,$order)
    {
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        $uids=array_column($data,'uid');
        $users=db('user')->where(['uid'=>['in',$uids]])->field('uid,nickname,phone')->select();
        $users=array_column($users,null,'uid');

        $product_id=array_column($data,'product_id');
        $product = db('column_text')->where(['id'=>['in',$product_id]])->field('id,name')->select();
        $product =array_column($product,'name','id');


        foreach ($data as &$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['add_time']);
            $v['user_message']=isset($users[$v['uid']])?'<span>'.$users[$v['uid']]['nickname'].'</span></br><span>'.$users[$v['uid']]['phone'].'</span>':'';
            $v['product']=isset($product[$v['product_id']])?$product[$v['product_id']]:'';
            $v['score']='<span>商品分数:'.$v['product_score'].'</span><br/><span>服务分数:'.$v['service_score'].'</span>';
        }
        $count=self::where($map)->count();
        return compact('count', 'data');
    }

}