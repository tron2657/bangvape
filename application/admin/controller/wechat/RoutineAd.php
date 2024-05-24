<?php
/**
 * 微信小程序广告
 * @author xzj
 * @date 2020-9
 */

namespace app\admin\controller\wechat;

use app\admin\controller\AuthController;
use app\admin\model\wechat\RoutineAd as AdModel;
use service\JsonService;
use service\UtilService as Util;
use think\Request;

class RoutineAd extends AuthController
{
    /**
     * 微信小程序流量主广告列表页
     * @return mixed
     * @author xzj
     * @date 2020/9/30
     */
    public function index()
    {
        $this->assign('ad_slot', AdModel::getRoutineAdType());
        return $this->fetch();
    }

    /**
     * 条件查询小程序广告列表
     * @throws \think\Exception
     * @author xzj
     * @date 2020/9/30
     */
    public function ad_list()
    {
        $where = Util::getMore([
            ['page', 1],
            ['limit', 20],
            ['name', ''],
            ['ad_unit_id', ''],
            ['ad_slot', '']
        ]);
        return JsonService::successlayui(AdModel::getRoutineAdList($where));
    }

    /**
     * 设置广告是否可见
     * @param string $is_show
     * @param string $id
     * @author xzj
     * @date 2020/9/30
     */
    public function set_show($is_show='', $id=''){
        ($is_show=='' || $id=='') && JsonService::fail('缺少参数');
        $res=AdModel::edit(['is_show'=>(int)$is_show], $id);
        if($res){
            return JsonService::successful($is_show==1 ? '显示成功':'隐藏成功');
        }else{
            return JsonService::fail($is_show==1 ? '显示失败':'隐藏失败');
        }
    }

    /**
     * 删除广告
     * @param $id
     * @author xzj
     * @date 2020/9/30
     */
    public function delete($id) {
        $map = [
            'is_del' => 1,
            'delete_time' => time()
        ];
        $res = AdModel::edit($map, $id);
        if($res){
            return JsonService::successful('删除成功');
        }else{
            return JsonService::fail('删除失败');
        }
    }

    /**
     * 新建广告页面
     * @return mixed
     * @author xzj
     * @date 2020/9/30
     */
    public function create_ad()
    {
        $type = osx_input('type','');
        $open_list = self::_getClientOpenList();
        $this->assign('type', $type);
        $this->assign('com_type', AdModel::getComTypeList($open_list));
        $this->assign('ad_scene', AdModel::getSceneList($open_list));
        $this->assign('ad_slot', AdModel::getRoutineAdType());
        $this->assign('ad_slot_obj', json_encode(AdModel::getRoutineAdType(), JSON_UNESCAPED_UNICODE) );
        return $this->fetch('create_ad');
    }

    /**
     * 广告ID获取广告详情
     * @param Request $request
     * @author xzj
     * @date 2020/9/30
     */
    public function get_ad_unit(Request $request)
    {
        $data = Util::getMore([
            ['ad_unit_id', '']
        ], $request);
        $data = AdModel::getRoutineUnitAd($data['ad_unit_id']);
        if (empty($data)) {
            return JsonService::fail('未读取到数据！');
        }
        return JsonService::successlayui($data);
    }

    /**
     * 新增广告
     * @param Request $request
     * @author xzj
     * @date 2020/9/30
     */
    public function add_ad(Request $request)
    {
        $data = Util::postMore([
            ['name', ''],
            ['ad_unit_id', ''],
            ['ad_slot', ''],
            ['ad_info', ''],
            ['status', 1],
            ['is_show', 1],
            ['remark', ''],
            ['ad_type', []],
            ['trigger_scene', []],
            ['trigger_gap', 0],
            ['task', []],
            ['ad_theme', 0],
            ['grid_count', 0],
            ['position', 0]
        ], $request);
        if($data['name'] == '') return JsonService::fail('请输入广告名称');
        if($data['ad_unit_id'] == '') return JsonService::fail('请输入广告ID');
        if($data['ad_slot'] == '') return JsonService::fail('请输入广告类型');
        $ad_type = $data['ad_type'];
        $trigger_scene = $data['trigger_scene'];
        $task = $data['task'];
        unset($data['ad_type']);
        unset($data['trigger_scene']);
        unset($data['task']);
        $res = AdModel::set($data, true);
        if (!$res || !$res->id) return JsonService::success('新建广告失败');
        $ad_id = $res->id;
        if (!empty($ad_type)) {
            foreach ($ad_type as $tv) {
                db('routine_ad_position')->insert(['routine_ad_id'=>$ad_id,'ad_type'=>$tv]);
            }
        }
        if (!empty($trigger_scene)) {
            foreach ($trigger_scene as $sv) {
                db('routine_ad_position')->insert(['routine_ad_id'=>$ad_id,'trigger_scene'=>$sv]);
            }
        }
        if ($data['ad_slot'] == 'SLOT_ID_WEAPP_REWARD_VIDEO' && !empty($task)) {
            AdModel::addAdTask($ad_id, $task);
        }
        return JsonService::success('新建广告成功');
    }

    /**
     * 编辑广告页面
     * @param $id
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author xzj
     * @date 2020/9/30
     */
    public function ad_edit($id)
    {
        $type = osx_input('type','');
        $this->assign('type', $type);
        $ad = AdModel::getAdDetail($id);
        $this->assign('id', $id);
        $this->assign('detail', $ad);
        $open_list = self::_getClientOpenList();
        $this->assign('com_type', AdModel::getComTypeList($open_list));
        $this->assign('ad_scene', AdModel::getSceneList($open_list));
        $this->assign('ad_slot', AdModel::getRoutineAdType());
        $this->assign('ad_slot_obj', json_encode(AdModel::getRoutineAdType(), JSON_UNESCAPED_UNICODE) );
        return $this->fetch('ad_edit');
    }

    /**
     * 获取广告详情
     * @param $id
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author xzj
     * @date 2020/10/9
     */
    public function get_ad_detail($id)
    {
        $ad = AdModel::getAdDetail($id);
        return JsonService::success('success', $ad);
    }

    /**
     * 编辑广告
     * @param Request $request
     * @param $id
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author xzj
     * @date 2020/9/30
     */
    public function update_ad(Request $request, $id)
    {
        $data = Util::postMore([
            ['name', ''],
            ['ad_unit_id', ''],
            ['ad_slot', ''],
            ['remark', ''],
            ['ad_type', []],
            ['trigger_scene', []],
            ['trigger_gap', 0],
            ['task', []],
            ['ad_theme', 0],
            ['grid_count', 0],
            ['position', 0]
        ], $request);
        if($data['name'] == '') return JsonService::fail('请输入广告名称');
        if($data['ad_unit_id'] == '') return JsonService::fail('请输入广告ID');
        if($data['ad_slot'] == '') return JsonService::fail('请输入广告类型');
        $ad_type = $data['ad_type'];
        $trigger_scene = $data['trigger_scene'];
        $task = $data['task'];
        unset($data['ad_type']);
        unset($data['trigger_scene']);
        unset($data['task']);
        AdModel::edit($data, $id);
        if (!empty($ad_type)) {
            db('routine_ad_position')->where('routine_ad_id', $id)->delete();
            foreach ($ad_type as $tv) {
                db('routine_ad_position')->insert(['routine_ad_id'=>$id,'ad_type'=>$tv]);
            }
        }
        if (!empty($trigger_scene)) {
            db('routine_ad_position')->where('routine_ad_id', $id)->delete();
            foreach ($trigger_scene as $sv) {
                db('routine_ad_position')->insert(['routine_ad_id'=>$id,'trigger_scene'=>$sv]);
            }
        }
        if ($data['ad_slot'] == 'SLOT_ID_WEAPP_REWARD_VIDEO' && !empty($task)) {
            $tid = AdModel::get($id)->renwu_id;
            if ($tid > 0) db('system_renwu')->where('id', $tid)->update($task);
        }
        return JsonService::success('编辑广告成功');
    }

    /**
     * 获取位置列表
     * @author xzj
     * @date 2020/10/9
     */
    public function get_type_tree()
    {
        $id = osx_input('id', '0');
        $tree = AdModel::getComTypeList(self::_getClientOpenList(), $id);
        return JsonService::success('success', $tree);
    }
}