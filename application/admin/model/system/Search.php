<?php


namespace app\admin\model\system;

use app\admin\model\user\User;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\com\ComForum as ForumModel;
use app\admin\model\system\SystemAdmin;
/**
 * Class ComThreadClass
 * @package app\admin\model\store
 */
class Search extends ModelBasic
{
    use ModelTrait;

    /*
     * 异步获取分类列表
     * @param $where
     * @return array
     */
    public static function SearchList($where){
        $model = self::getModelObject($where)->field(['*']);
        $model = $model->page((int)$where['page'], (int)$where['limit']);
        $data = ($data = $model->order('create_time desc')->select()) && count($data) ? $data->toArray() : [];
        foreach ($data as &$item){
            $item['nickname']=User::where('uid',$item['uid'])->value('nickname');
            $item['create_time']=time_format($item['create_time']);
        }
        $count=self::getModelObject($where)->count();
        return compact('count','data');
    }
    /**
     * @param $where
     * @return array
     */
    public static function getModelObject($where){
        $model = new self();
        if (!empty($where)) {
            if(isset($where['uid']) && $where['uid']!=''){
                $uids=db('user')->where('uid|nickname','like','%'.$where['uid'].'%')->column('uid');
                $model->where('uid','in',$uids);
            }
            if(isset($where['keyword']) && $where['keyword']!=''){
                $model->where('keyword',$where['keyword']);
            }
            $model->where(function($query) use($where){
                switch ($where['data']) {
                    case 'yesterday':
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                        $query->whereTime('create_time', $where['data']);
                        break;
                    case 'quarter':
                        $start = strtotime(Carbon::now()->startOfQuarter());
                        $end   = strtotime(Carbon::now()->endOfQuarter());
                        $query->whereTime('create_time', 'between', [$start, $end]);
                        break;
                    case '':
                        ;
                        break;
                    default:
                        $between = explode(' - ', $where['data']);
                        $query->whereTime('create_time', 'between', [$between[0], $between[1]]);
                        break;
                }
            });
        }
        return $model;
    }

}