<?php

namespace app\admin\model\column;

use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

class ColumnClass extends ModelBasic
{
    use ModelTrait;

    public static function delAuthor($id){
        $res=self::where('id',$id)->update(['status'=>1]);
        return $res;
    }

    public static function ClassList($where){
        $model=self::getModelObject($where);
        $model=$model->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            switch($item['type']){
                case 1:
                    $item['type']='纵向单列';
                    break;
                case 2:
                    $item['type']='纵向双列';
                    break;
            }
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
            if(isset($where['name']) && $where['name']!=''){
                $model = $model->where('name|id','LIKE',"%$where[name]%");
            }
            if(isset($where['order']) && $where['order']!=''){
                $model = $model->order(self::setOrder($where['order']));
            }else{
                $model = $model->order('id desc');
            }
        }
        return $model;
    }

}
