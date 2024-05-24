<?php
namespace app\admin\controller\system;

use app\admin\controller\AuthController;

use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Cache;
use think\Request;
use app\admin\model\system\Search as SearchModel;
use think\Url;
use app\admin\model\system\SystemMenus;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class Search extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return json
     */
    public function search_list(){
        $where = Util::getMore([
            ['uid',''],
            ['keyword',''],
            ['data',''],
            ['page',1],
            ['limit',20],
        ]);
        return JsonService::successlayui(SearchModel::SearchList($where));
    }

    /**
     * 搜索导航栏
     */
    /*public function search_nav(){
        $keyword=osx_input('keyword','');
        $data=db('system_menus')->where('menu_name','LIKE',"%{$keyword}%")->select();
        return JsonService::successlayui($data);
    }*/
    /**
     * 搜索导航栏
     */
    public function search_nav(){
        $keyword=osx_input('keyword','');
        $data=db('system_menus')->where('menu_name','LIKE',"%{$keyword}%")->select();
        foreach($data as &$value){
            $params = json_decode($value['params'],true);//获取参数
            $value['url'] =Url::build($value['module'].'/'.$value['controller'].'/'.$value['action'],$params);
            $have_menu=db('system_menus')->where('pid',$value['id'])->count();
            if($have_menu>0){
                $value['have_menu']=1;
            }else{
                $value['have_menu']=0;
            }
        }
        unset($value);
        return JsonService::successlayui($data);
    }

}
