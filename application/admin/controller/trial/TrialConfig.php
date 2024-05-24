<?php
 
namespace app\admin\controller\trial;


use app\admin\controller\AuthController;
use app\admin\model\store\StoreProduct;
use app\admin\model\system\SystemConfig;
use app\shareapi\model\InviteShare;
use service\FormBuilder;
use service\JsonService;
use service\UtilService as Util;
use think\Request;
use think\Url;


class TrialConfig extends AuthController
{
   
     

   
    public function gui_ze()
    {
        $agent_xieyi_config=SystemConfig::getValue('agent_xieyi_config');
        $this->assign('agent_xieyi_config',$agent_xieyi_config);
        return $this->fetch();
    }

 
    public function saveGuiZe()
    {
        $request = Request::instance();
        if($request->isPost()){
            $agent_xieyi_config = osx_input('post.agent_xieyi_config','','html');
            SystemConfig::edit(['value' => json_encode($agent_xieyi_config)],'agent_xieyi_config','menu_name');
            db('all_agreement')->where('id',10)->update(['update_time'=>time()]);
            return JsonService::successful('修改成功');
        }
    }
  
}