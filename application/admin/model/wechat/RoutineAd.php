<?php
/**
 * 小程序流量主广告
 * @author xzj
 * @date 2020-9
 */

namespace app\admin\model\wechat;

use app\core\model\routine\RoutineServer;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;

class RoutineAd extends ModelBasic
{
    use ModelTrait;

    protected $autoWriteTimestamp = true;

    /**
     * 微信小程序广告类型
     * @return string[]
     * @author xzj
     * @date 2020/9/30
     */
    public static function getRoutineAdType()
    {
        return [
            'SLOT_ID_WEAPP_BANNER' => 'Banner广告',
            'SLOT_ID_WEAPP_REWARD_VIDEO' => '激励视频广告',
            'SLOT_ID_WEAPP_INTERSTITIAL' => '插屏广告',
            'SLOT_ID_WEAPP_VIDEO_FEEDS' => '视频广告',
            'SLOT_ID_WEAPP_VIDEO_BEGIN' => '视频前贴广告',
            'SLOT_ID_WEAPP_BOX' => '格子广告',
            'SLOT_ID_WEAPP_TEMPLATE' => '原生模板广告'
        ];
    }

    /**
     * 小程序广告列表
     * @param $where
     * @return array
     * @throws \think\Exception
     * @author xzj
     * @date 2020/9/30
     */
    public static function getRoutineAdList($where)
    {
        $map = ['is_del' => 0];
        if ($where['name'] != '') {
            $map['name'] = ['like', '%'.$where['name'].'%'];
        }
        if ($where['ad_unit_id'] != '') {
            $map['ad_unit_id'] = ['like', '%'.$where['ad_unit_id'].'%'];
        }
        if ($where['ad_slot'] != '') {
            $map['ad_slot'] = $where['ad_slot'];
        }
        $model = self::where($map)->page((int)$where['page'], (int)$where['limit']);
        $data = ($data = $model->select()) && count($data) ? $data->toArray() : [];
        foreach ($data as &$val) {
            $val['ad_slot_name'] = self::getRoutineAdType()[$val['ad_slot']];
        }
        $count = self::where($map)->count();
        return compact('count', 'data');
    }

    /**
     * 使用微信接口获取广告清单
     * @param $ad_unit_id
     * @return array
     * @author xzj
     * @date 2020/9/30
     */
    public static function getRoutineUnitAd($ad_unit_id)
    {
        $access_token = RoutineServer::get_access_token();
        if ($access_token == '') return [];
        $url = 'https://api.weixin.qq.com/publisher/stat?action=get_adunit_list&access_token='.$access_token.'&ad_unit_id='.$ad_unit_id;
        $req = RoutineServer::curlGet($url);
        $arr = json_decode($req, true);
        if (!isset($arr['total_num'])) {
            $access_token = RoutineServer::get_access_token(true);
            if ($access_token == '') return [];
            $url = 'https://api.weixin.qq.com/publisher/stat?action=get_adunit_list&access_token='.$access_token.'&ad_unit_id='.$ad_unit_id;
            $req = RoutineServer::curlGet($url);
            $arr = json_decode($req, true);
            if (!isset($arr['total_num'])) return [];
        }
        if ($arr['total_num']<1) return [];
        $count = $arr['total_num'];
        $data = $arr['ad_unit'];
        return compact('count', 'data');
    }

    /**
     * 广告固定位置列表
     * @param array $open_list
     * @param string $id
     * @return array
     * @author xzj
     * @date 2020/9/30
     */
    public static function getComTypeList($open_list = [], $id = '')
    {
        $list = [];
        if (in_array('osapi_base', $open_list)) {
            $list[] = [ 'id' => 2, 'label' => '社区首页广告位', 'checked' => false ];
            $list[] = [ 'id' => 4, 'label' => '帖子详情页广告位', 'checked' => false ];
        }
        if (in_array('ebapi_store', $open_list)) {
            $list[] = [ 'id' => 8, 'label' => '商城首页广告位', 'checked' => false ];
            $list[] = [ 'id' => 7, 'label' => '首页精品推荐广告位', 'checked' => false ];
        }
        if (in_array('knowledge', $open_list)) {
            $list[] = [ 'id' => 11, 'label' => '知识商城首页广告位', 'checked' => false ];
        }
        $list[] = [ 'id' => 9, 'label' => '推广中心广告位', 'checked' => false ];
        if (in_array('attestation', $open_list)) {
            $list[] = [ 'id' => 13, 'label' => '认证中心首页广告位', 'checked' => false ];
        }
        if (in_array('shop', $open_list)) {
            $list[] = [ 'id' => 10, 'label' => '积分商城首页广告位', 'checked' => false ];
        }
        $list[] = [ 'id' => 5, 'label' => '个人中心广告位', 'checked' => false ];
        $pos = self::alias('a')->join('routine_ad_position b', 'a.id=b.routine_ad_id')
            ->field('a.name,b.ad_type')->where('b.ad_type','>',0)->where('a.is_del', 0)->column('a.name','b.ad_type');
        $types = db('routine_ad_position')->where('routine_ad_id', $id)->where('ad_type', '>', 0)->column('ad_type');
        if (!empty($pos)) {
            foreach ($list as &$v) {
                if (isset($pos[$v['id']])) {
                    $v['checked'] = true;
                    if (empty($types) || !in_array($v['id'], $types)) {
                        $v['disabled'] = true;
                        $v['label'] = $v['label'].'（'.$pos[$v['id']].'）';
                    }
                }
            }
        }

        $tree = [
            [ 'id' => 'community', 'label' => '社区', 'spread' => true, 'children' => []],
            [ 'id' => 'shop', 'label' => '商城', 'spread' => true, 'children' => []],
            [ 'id' => 'knowledge', 'label' => '知识商城', 'spread' => true, 'children' => []],
            [ 'id' => 'extend', 'label' => '扩展', 'spread' => true, 'children' => []],
            [ 'id' => 'public', 'label' => '公共', 'spread' => true, 'children' => []]
        ];
        $num = [0, 0, 0, 0, 0];

        foreach ($list as $item) {
            if (in_array($item['id'], [2,4])) {
                if (isset($item['disabled'])) $num[0]++;
                $tree[0]['children'][] = $item;
            }
            if (in_array($item['id'], [7,8])) {
                if (isset($item['disabled'])) $num[1]++;
                $tree[1]['children'][] = $item;
            }
            if ($item['id'] == 11) {
                if (isset($item['disabled'])) $num[2]++;
                $tree[2]['children'][] = $item;
            }
            if (in_array($item['id'], [9,10,13])) {
                if (isset($item['disabled'])) $num[3]++;
                $tree[3]['children'][] = $item;
            }
            if ($item['id'] == 5) {
                if (isset($item['disabled'])) $num[4]++;
                $tree[4]['children'][] = $item;
            }
        }
        if ($num[0] == 2) $tree[0]['disabled'] = true;
        if ($num[1] == 2) $tree[1]['disabled'] = true;
        if ($num[2] == 1) $tree[2]['disabled'] = true;
        if ($num[3] == 3) $tree[3]['disabled'] = true;
        if ($num[4] == 1) $tree[4]['disabled'] = true;

        return $tree;
    }

    /**
     * 小程序广告触发场景列表
     * @param array $open_list
     * @return array
     * @author xzj
     * @date 2020/9/30
     */
    public static function getSceneList($open_list = [])
    {
        $list = [];
        if (in_array('ebapi_store', $open_list)) {
            $list[] = [ 'id' => 1, 'name' => '商城付款成功', 'checked' => false, 'ad_name' => '' ];
        }
        $list[] = [ 'id' => 2, 'name' => '底部Tab栏切换', 'checked' => false, 'ad_name' => '' ];
        if (in_array('osapi_video', $open_list)) {
            $list[] = [ 'id' => 3, 'name' => '视频播放暂停', 'checked' => false, 'ad_name' => '' ];
        }
        $scene = self::alias('a')->join('routine_ad_position b', 'a.id=b.routine_ad_id')
            ->field('a.name,b.trigger_scene')->where('b.trigger_scene','>',0)->column('a.name','b.trigger_scene');
        if (!empty($scene)) {
            foreach ($list as &$v) {
                if (isset($scene[$v['id']])) {
                    $v['checked'] = true;
                    $v['ad_name'] = $scene[$v['id']];
                }
            }
        }
        return $list;
    }

    /**
     * 添加视频激烈广告到任务列表
     * @param string $id
     * @param array $task
     * @return int|string
     * @author xzj
     * @date 2020/9/30
     */
    public static function addAdTask($id = '', $task = [])
    {
        $data['name'] = (isset($task['name']) && $task['name']) ? $task['name'] : '';
        $data['explain'] = (isset($task['explain']) && $task['explain']) ? $task['explain'] : '';
        $data['require'] = (isset($task['require']) && $task['require']) ? $task['require'] : '';
        $data['icon'] = (isset($task['icon']) && $task['icon']) ? $task['icon'] : '';
        $data['exp'] = (isset($task['exp']) && $task['exp']) ? $task['exp'] : 0;
        $data['fly'] = (isset($task['fly']) && $task['fly']) ? $task['fly'] : 0;
        $data['gong'] = (isset($task['gong']) && $task['gong']) ? $task['gong'] : 0;
        $data['buy'] = (isset($task['buy']) && $task['buy']) ? $task['buy'] : 0;
        $data['one'] = (isset($task['one']) && $task['one']) ? $task['one'] : 0;
        $data['leixing'] = 1;
        $data['jifenflag'] = 'REWARD_VIDEO_'.$id;
        $data['type'] = 2;
        $data['status'] = 1;
        $data['position'] = '';
        $data['url'] = '';
        $data['two'] = 0;
        $data['three'] = 0;
        $data['four'] = 0;
        $data['five'] = 0;
        $res = db('system_renwu')->insertGetId($data);
        if ($res) {
            self::edit(['renwu_id' => $res], $id);
        }
        return $res;
    }

    /**
     * 获取广告详情
     * @param $id
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author xzj
     * @date 2020/9/30
     */
    public static function getAdDetail($id)
    {
        $ad = self::get($id);
        if (!$ad) return [];
        $ad = $ad->toArray();
        if ($ad['renwu_id'] > 0) {
            $task = db('system_renwu')->where('id', $ad['renwu_id'])->field('name,explain,require,icon,exp,fly,gong,buy,one')->find();
            if ($task) $ad['task'] = $task;
        }
        $type = db('routine_ad_position')->where('routine_ad_id', $id)->where('ad_type', '>', 0)->column('ad_type');
        if (!empty($type)) $ad['ad_type'] = $type;
        $scene = db('routine_ad_position')->where('routine_ad_id', $id)->where('trigger_scene', '>', 0)->column('trigger_scene');
        if (!empty($scene)) $ad['trigger_scene'] = $scene;
        return $ad;
    }
}