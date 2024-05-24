<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\store;

use app\admin\model\user\User;
use Carbon\Carbon;
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
    public static function systemPage($where,$is_type = 0){
        $model = new self;
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
        $model = $model->join('__STORE_PRODUCT__ p','p.id=r.product_id');
        $model = $model->where('r.is_del',0);
        $model = $model->where('p.is_type',$is_type);
        $model = $model->field('r.*,u.nickname,u.avatar,p.store_name');
        $model = $model->order('r.add_time desc,r.is_reply asc');
        return self::page($model,function($itme){

        },$where);
    }

    public static function ReplyList($where){
        $model=self::getModelObject($where);
        $model=$model->order('id desc')->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            $item['nickname'] = User::where('uid',$item['uid'])->value('nickname');
            $item['avatar'] = User::where('uid',$item['uid'])->value('avatar');
            $item['store_name'] = StoreProduct::where('id',$item['product_id'])->value('store_name');
            $item['add_time'] = time_format($item['add_time']);
            $item['merchant_reply_time'] = time_format($item['merchant_reply_time']);
            $item['del_time'] = time_format($item['del_time']);
        }
        $count=self::getModelObject($where)->count();
        return compact('count','data');
    }

    /**
     * 获取连表MOdel
     * @param $model
     * @return object
     */
    public static function getModelObject($where=[]){
        $model=new self();
        if(!empty($where)){
            // data 日期
            $model->where(function($query) use($where){
                switch ($where['data']) {
                    case 'yesterday':
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                        $query->whereTime('add_time', $where['data']);
                        break;
                    case 'quarter':
                        $start = strtotime(Carbon::now()->startOfQuarter());
                        $end   = strtotime(Carbon::now()->endOfQuarter());
                        $query->whereTime('add_time', 'between', [$start, $end]);
                        break;
                    case '':
                        ;
                        break;
                    default:
                        $between = explode(' - ', $where['data']);
                        $query->whereTime('add_time', 'between', [$between[0], $between[1]]);
                        break;
                }
            });
            if(isset($where['is_reply']) && $where['is_reply']!=''){
                $model = $model->where('is_reply',$where['is_reply']);
            }
            if(isset($where['comment']) && $where['comment']!=''){
                $model = $model->where('comment','LIKE',"%$where[comment]%");
            }
            if(isset($where['status']) && $where['status']!=''){
                $model = $model->where('is_del',$where['status']);
            }
        }
        return $model;
    }

}