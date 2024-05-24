<?php

namespace app\admin\model\com;

use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class ComAdv extends ModelBasic
{
	use ModelTrait;

	// 自动写入时间戳
	/*protected $autoWriteTimestamp = 'datetime';
	protected $dateFormat         = 'Y-m-d H:i:s';*/

	public static function AdvList($where){
		trace($where);
		$map                 = [];
		$map['type'] = $where['type'];

		if($where['status'] != ''){
			$map['status'] = $where['status'];
		}
		// if($where['name']){
		// 	$map['name'] = ['like', "%{$where['name']}%"];
		// }
        if ($where['platform'] != '') {
            if ($where['platform'] == '6') {
                $map2['platform'] = ['in', '1,2'];
            } elseif ($where['platform'] == '7') {
                $map2['platform'] = ['in', '3,4'];
            } else {
                $map2['platform'] = $where['platform'];
            }
            $ids = db('com_adv_platform')->where($map2)->column('adv_id');
            $ids = array_unique($ids);
            $map['id'] = ['in', $ids];
        }
		$model = self::where($map)->field(['*']);
		$model->page((int)$where['page'], (int)$where['limit']);
        $model->order($where['order']);
		$data = ($data = $model->select()) && count($data) ? $data->toArray() : [];
        foreach($data as &$val){
            $val['create_time']=time_format($val['create_time']);
            $val['update_time']=time_format($val['update_time']);
            $val['ad_type'] = 0;
            $val['ad_type_name'] = '自建广告';
            $val['platform'] = self::getAdvPlatform($val['id']);
        }
		$count = self::where($map)->count();
        if ($where['platform'] == '' || in_array($where['platform'], ['1', '2', '6'])) {
            $routine_ad = self::getRoutineAd($where);
            if (!empty($routine_ad)) {
                $data[] = $routine_ad;
                $count = $count + 1;
            }
        }
		return compact('count', 'data');
	}

    public static function getRoutineAd($where)
    {
        $ids = db('routine_ad_position')->where('ad_type', $where['type'])->column('routine_ad_id');
        if (empty($ids)) return [];
        $map['is_del'] = 0;
        $map['id'] = ['in', $ids];
        if ($where['status']) $map['status'] = $where['status'];
        $ad = db('routine_ad')->where($map)->field('id,name,ad_unit_id,ad_slot,status,is_show,create_time,update_time')->find();
        if (!$ad) return [];
        $data['id'] = $ad['id'];
        $data['name'] = $ad['name'].'<br>['.$ad['ad_unit_id'].']<br>'.($ad['status']==0?'<span class="text-muted">已关闭</span>':'<span class="text-info">已开启</span>');
        $data['pic'] = '';
        $data['url'] = '';
        $data['sort'] = '';
        $data['status'] = $ad['is_show'];
        $data['create_time'] = date('Y-m-d H:i', $ad['create_time']);
        $data['update_time'] = date('Y-m-d H:i', $ad['update_time']);
        $data['ad_type'] = 1;
        $data['ad_type_name'] = '流量主广告';
        $data['platform'] = '微信小程序（iOS）、微信小程序（Android）';
        return $data;
    }

    public static function getAdvPlatform($id) {
	    $arr = db('com_adv_platform')->where('adv_id', $id)->column('platform');
	    if (empty($arr)) return '';
	    $text = ['', '微信小程序（iOS）', '微信小程序（Android）', 'iOS App', 'Android App', 'H5'];
	    $p = [];
	    foreach ($arr as $v) {
	        if (isset($text[$v])) $p[] = $text[$v];
        }
	    return implode('、', $p);
    }
}