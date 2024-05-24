<?php

namespace app\columnapi\model\column;

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
		return self::where('id',$id)
				->where('is_show',1)
				->find();
    }

    /**
     * 本地图片加上域名
     * @param $images
     * @return array|string
     * @author xzj
     * @date 2020/10/30
     */
    public static function checkImages($images)
    {
        if (is_string($images)) {
            return ( stripos($images,'http') !==0 && stripos($images,'/') === 0 ) ? get_domain() . $images : $images;
        }
        if (is_array($images)) {
            foreach ($images as &$image) {
                if (stripos($image,'http') !==0 && stripos($image,'/') === 0) $image = get_domain() . $image;
            }
            unset($image);
            return $images;
        }
        return $images;
    }

	/**
	 * 获取专栏信息
	 * qhy
	 */
	public static function getColumnInfo($id){
        $uid=get_uid();
        ColumnUserBuy::checkColumnBuyById($uid,$id);
		$list=self::where('id',$id)->where('status',1)->where('is_show',1)->where('is_column',1)->find();
		if (!$list) return false;
		$list['image'] = self::checkImages($list['image']);
		$list['images']=json_decode($list['images'],true);
        $list['images'] = self::checkImages($list['images']);
		$list['create_time']=time_format($list['create_time']);
        $sales_count = db('column_user_buy')->where('pid',$id)->where('status',1)->count();
        if ($sales_count > $list['sales']) {
            self::where('id',$id)->update(['sales'=>$sales_count]);
            $list['sales'] = $sales_count;
        }
		return $list;
	}

	/**
	 * 获取单品信息
	 * qhy
	 */
	public static function getProduct($id){
		$uid=get_uid();
		ColumnUserBuy::checkColumnBuyById($uid,$id);
		$list=self::where('id',$id)->where('status',1)->where('is_show',1)->where('is_column',0)->field('id,is_free,author_id,name,image,images,info,keyword,introduction,type,m_type,price,cost_price,ot_price,score,is_trial,read_count,sort,sales,ficti_sales,strip_num,recommend_sell,create_time')->find();
		if($list){
			$list['author']=db('column_author')->where('id',$list['author_id'])->find();
			$list['create_time']=time_format($list['create_time']);
			$list['is_collect']=ColumnCollect::isCollect($id);
			$sales_count = db('column_user_buy')->where('pid',$id)->where('status',1)->count();
			if ($sales_count > $list['sales']) {
			    self::where('id',$id)->update(['sales'=>$sales_count]);
			    $list['sales'] = $sales_count;
            }
			$list['sales']=$list['sales']+$list['ficti_sales'];
			$list['image'] = self::checkImages($list['image']);
			$list['images'] = json_decode($list['images'],true);
			$list['images'] = self::checkImages($list['images']);
			$list['is_set']=ColumnUserBuy::where('uid',$uid)->where('pid',$id)->where('status',1)->count();
		}
		return $list;
	}

	/**
	 * 获取单品详情
	 * qhy
	 */
	public static function ProductInfo($id,$uid,$pid='0'){
		$list=self::where('id',$id)->where('status',1)->where('is_show',1)->where('is_column',0)->find();
		if($list){
			$list['is_buy']=0;
			if($list['pid']=='0'){
				if($list['is_free']==0){
					$user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$id)->where('is_free',0)->where('status',1)->count();
					if($user_buy>0){
						$list['is_buy']=1;
                        ColumnUserBuy::where('uid',$uid)->where('pid',$id)->where('is_free',0)->where('status',1)->update(['read_time'=>time()]);
					}
				}else{
					$user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$id)->where('status',1)->count();
					if($user_buy>0){
						$list['is_buy']=1;
                        ColumnUserBuy::where('uid',$uid)->where('pid',$id)->where('status',1)->update(['read_time'=>time()]);
					}
				}
			}else{
			    if ($pid == '0') return false;
				$pids = explode(',',$list['pid']);
				if (!in_array($pid, $pids)) return false;
                $column=self::where('id',$pid)->find();
                if ($column['is_free']==0) {
                    $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$pid)->where('is_free',0)->where('status',1)->count();
                    if ($user_buy > 0) {
                        $list['is_buy']=1;
                        ColumnUserBuy::where('uid',$uid)->where('pid',$pid)->where('is_free',0)->where('status',1)->update(['read_time'=>time()]);
                    }
                } else {
                    $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$pid)->where('status',1)->count();
                    if ($user_buy > 0) {
                        $list['is_buy']=1;
                        ColumnUserBuy::where('uid',$uid)->where('pid',$pid)->where('status',1)->update(['read_time'=>time()]);
                    }
                }
			}
			$list['author']=db('column_author')->where('id',$list['author_id'])->find();
			$list['is_collect']=ColumnCollect::isCollect($id);
		}
		return $list;
	}

	/**
	 * 获取专栏商品列表
	 * qhy
	 */
	public static function getColumnList($id,$page=1,$row=10,$order='create_time desc'){
		$list=self::where('find_in_set(:id,pid)',['id'=>$id])->where('status',1)->where('is_show',1)->where('is_column',0)->order('create_time asc')->select();
		if (empty($list)) {
		    $list = array();
        } else {
		    $list = $list->toArray();
        }
		foreach($list as $k => &$value){
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			$value['image'] = self::checkImages($value['image']);
			$value['images']=json_decode($value['images'],true);
            $value['images'] = self::checkImages($value['images']);
			$value['create_time']=time_format($value['create_time']);
			$value['order'] = $k+1;
		}
		unset($value);
        //if (stripos($order,'desc') > 0) $list = array_reverse($list);
		return $list;
	}

	/**
	 * 搜索全部
	 * qhy
	 */
	public static function searchALL($keyword,$order,$category_id){
	    $model = self::where('status',1)->where('is_show',1)->where('is_column=1 OR pid=\'0\'');
	    if ($keyword != '') $model=$model->where('name|keyword','LIKE',"%$keyword%");
	    if (!empty($category_id)) $model=$model->where('category_id',$category_id);
		$list=$model->limit(20)->order($order)->select();
		foreach($list as &$value){
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			$value['image'] = self::checkImages($value['image']);
			if($value['is_column']==1){
				$value['product_count']=self::where('find_in_set(:id,pid)',['id'=>$value['id']])->where('status',1)->where('is_show',1)->count();
			}else{
				if($value['pid']){
					$pid=explode(',',$value['pid']);
					foreach($pid as &$val){
						$val=self::where('id',$val)->value('name');
					}
					unset($val);
					$value['pid_name']=$pid;
				}else{
					$value['pid_name']='';
				}
			}
		}
		unset($value);
		return $list;
	}

	/**
	 * 搜索专栏
	 * qhy
	 */
	public static function searchColumn($keyword,$order,$category_id){
        $model = self::where('status',1)->where('is_show',1)->where('is_column',1);
        if ($keyword != '') $model=$model->where('name|keyword','LIKE',"%$keyword%");
        if (!empty($category_id)) $model=$model->where('category_id',$category_id);
		$list=$model->limit(20)->order($order)->select();
		foreach($list as &$value){
            $value['image'] = self::checkImages($value['image']);
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			$value['product_count']=self::where('find_in_set(:id,pid)',['id'=>$value['id']])->where('status',1)->where('is_show',1)->count();
		}
		unset($value);
		return $list;
	}

	/**
	 * 搜索图文
	 * qhy
	 */
	public static function searchText($keyword,$order,$category_id){
        $model = self::where('status',1)->where('is_show',1)->where('is_column',0)->where('pid','0')->where('type',1);
        if ($keyword != '') $model=$model->where('name|keyword','LIKE',"%$keyword%");
        if (!empty($category_id)) $model=$model->where('category_id',$category_id);
		$list=$model->limit(20)->order($order)->select();
		foreach($list as &$value){
            $value['image'] = self::checkImages($value['image']);
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			if($value['pid']){
				$pid=explode(',',$value['pid']);
				foreach($pid as &$val){
					$val=self::where('id',$val)->value('name');
				}
				unset($val);
				$value['pid_name']=$pid;
			}else{
				$value['pid_name']='';
			}
		}
		unset($value);
		return $list;
	}

	/**
	 * 搜索音频
	 * qhy
	 */
	public static function searchAudio($keyword,$order,$category_id){
        $model = self::where('status',1)->where('is_show',1)->where('is_column',0)->where('pid','0')->where('type',2);
        if ($keyword != '') $model=$model->where('name|keyword','LIKE',"%$keyword%");
        if (!empty($category_id)) $model=$model->where('category_id',$category_id);
		$list=$model->limit(20)->order($order)->select();
		foreach($list as &$value){
            $value['image'] = self::checkImages($value['image']);
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			if($value['pid']){
				$pid=explode(',',$value['pid']);
				foreach($pid as &$val){
					$val=self::where('id',$val)->value('name');
				}
				unset($val);
				$value['pid_name']=$pid;
			}else{
				$value['pid_name']='';
			}
		}
		unset($value);
		return $list;
	}

	/**
	 * 搜索视频
	 * qhy
	 */
	public static function searchVideo($keyword,$order,$category_id){
        $model = self::where('status',1)->where('is_show',1)->where('is_column',0)->where('pid','0')->where('type',3);
        if ($keyword != '') $model=$model->where('name|keyword','LIKE',"%$keyword%");
        if (!empty($category_id)) $model=$model->where('category_id',$category_id);
		$list=$model->limit(20)->order($order)->select();
		foreach($list as &$value){
            $value['image'] = self::checkImages($value['image']);
			$value['author']=db('column_author')->where('id',$value['author_id'])->find();
			if($value['pid']){
				$pid=explode(',',$value['pid']);
				foreach($pid as &$val){
					$val=self::where('id',$val)->value('name');
				}
				unset($val);
				$value['pid_name']=$pid;
			}else{
				$value['pid_name']='';
			}
		}
		unset($value);
		return $list;
	}

	public static function isValidProduct($productId)
	{
		return self::be(['id'=>$productId,'status'=>1,'is_show'=>1]) > 0;
	}

}
