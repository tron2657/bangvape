<?php

namespace app\admin\model\column;

use app\osapi\model\user\UserModel;
use think\Cache;
use think\Model;
use basic\ModelBasic;

class ColumnUserBuy extends ModelBasic
{

    public static function FreeList($where){
        $model=self::getModelObject($where);
        $model=$model->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            $item['nickname'] = UserModel::where('uid',$item['uid'])->value('nickname');
            $item['create_time'] = time_format($item['create_time']);
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
            $model->where('pid',$where['id']);
        }
        return $model;
    }

}
