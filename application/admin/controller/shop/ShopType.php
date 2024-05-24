<?php

namespace app\admin\controller\shop;

use app\admin\controller\AuthController;
use app\admin\model\system\SystemConfig;
use service\FormBuilder as Form;
use service\JsonService;
use service\JsonService as Json;
use think\Request;
use think\Url;
use service\UtilService;


/**
 * 商品管理
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class ShopType extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $type=db('shop_score_type')->where('id',1)->value('flag');
        $list=db('system_rule')->where('is_del',0)->where('status',1)->where('flag','neq','exp')->select();
        $is_on=SystemConfig::getValue('shop_on');
        $this->assign('is_on', $is_on);
        $this->assign('type', $type);
        $this->assign('list', $list);
        return $this->fetch();
    }


    public function save_score_type(Request $request)
    {
        $data = UtilService::postMore([
            'is_on',
            'flag',
        ],$request);
        if(!$data['flag']) return Json::fail('积分商城类型不能为空');
        $map1['flag']=$data['flag'];
        $map2['value']=$data['is_on'];
        $res1=db('shop_score_type')->where('id',1)->update($map1);
        $res2=db('system_config')->where('menu_name','shop_on')->update($map2);
        if($res1!==false&&$res2!==false){
            return JsonService::successful('修改成功');
        }else{
            return JsonService::fail('修改失败');
        }
    }

}
