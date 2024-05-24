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
class StoreProductReply extends ModelBasic
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
    public static function systemPage($where,$is_type = 0)
    {
        $map['r.is_del'] = 0;
        $map['p.status'] = 1;
        $map['p.is_show'] = 1;
        if($where['comment'] != '') $map['r.comment'] = ['LIKE',"%$where[comment]%"];
        if($where['is_reply'] != '') $map['r.is_reply'] = $where['is_reply'] >= 0 ? $where['is_reply'] : ['GT', 0];
        if($where['product_id']) $map['r.product_id'] = $where['product_id'];
        $list = db('store_product_reply')->alias('r')->join('column_text p', 'p.id=r.product_id')
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

}