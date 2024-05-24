<?php

namespace app\ebapi\model\column;

use basic\ModelBasic;
use traits\ModelTrait;

class ColumnText extends ModelBasic
{
	use ModelTrait;
	/**
	* 目录
	*/
    public static function getCatalog($gid, $field='*', $orderBy, $page=1, $size=1000)
    {
		return self::where('pid',$gid)
				->where('is_show',1)
				->field($field)
				->order($orderBy)
				->limit(($page-1)*$size,$size)
				->select();
    }

    /**
    * 总条数
    */
    public static function getCatalogCount($gid)
    {
		return self::where('pid',$gid)
				->where('is_show',1)
				->count();
    }

    /**
    * 详情
    */
    public static function getContent($id)
    {
		return self::where('find_in_set(:id,pid)',['id'=>$id])
				->where('is_show',1)
				->find();
    }

    public static function checkImages($images)
    {
        if (is_string($images)) {
            return ( stripos($images,'http') !==0 && stripos($images,'/') === 0 ) ? get_domain() . $images : $images;
        }
        if (is_array($images)) {
            foreach ($images as &$image) {
                if (is_string($image) && stripos($image,'http') !==0 && stripos($image,'/') === 0) $image = get_domain() . $image;
            }
            unset($image);
            return $images;
        }
        return $images;
    }

    /**
     * 搜索知识商品
     * @param $keyword
     * @param int $page
     * @param int $row
     * @return array
     * @author xzj
     * @date 2020/10/26
     */
    public static function searchColumn($keyword,$page=1,$row=10)
    {
        $column=self::where('name','like','%'.$keyword.'%')->where('status',1)->where('is_show',1)->where('is_column=1 OR pid=\'0\'')->page($page, $row)->order('create_time desc')->select();
        if (empty($column)) {
            $column = array();
        } else {
            foreach ($column as &$value) {
                $value['image'] = self::checkImages($value['image']);
                $value['images'] = self::checkImages(json_decode($value['images'], true));
                $value['store_name'] = $value['name'];
                $value['seller_back']=get_seller_back_num($value['strip_num']);
                $value['show_seller']=$value['seller_back']==0?false:true;
            }
            unset($value);
        }
        return $column;
    }
}
