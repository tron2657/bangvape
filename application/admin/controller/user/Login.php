<?php
namespace app\admin\controller\user;

use app\admin\controller\AuthController;
use app\admin\model\system\SystemConfig;
use app\admin\model\system\SystemRenwu;
use app\admin\model\system\SystemGradeDesc;
use app\admin\model\system\SystemUserTask;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService;
use think\Url;
use traits\CurdControllerTrait;
use think\Request;
use think\Db;

/**
 * 会员设置
 * Class UserLevel
 * @package app\admin\controller\user
 */
class Login extends AuthController
{
    use CurdControllerTrait;

    /*
     *
     * */
    public function index()
    {
        $data=db('user_login_set')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function edit(){
        $type=osx_input('type',0);
        $status=osx_input('status',0);
        if($status==0){
            $other=db('user_login_set')->where('status',1)->where('type','neq',$type)->count();
            if($other==0){
                return JsonService::fail('至少开启一种注册登录方式');
            }
        }
        $res=db('user_login_set')->where('type',$type)->update(['status'=>$status]);
        if($res!==false){
            return JsonService::successful('修改成功');
        }else{
            return JsonService::fail('修改失败');
        }
    }

    public function edit_set(){
        $type=osx_input('type','');
        $status=osx_input('status',0);
        $res=SystemConfig::edit(['value' => json_encode($status)],$type,'menu_name');
        if($res!==false){
            return JsonService::successful('修改成功');
        }else{
            return JsonService::fail('修改失败');
        }
    }

    public function index_set(){
        $data=SystemConfig::getMore('invite_code,invite_code_need,must_weixin_login,is_force_login,support_third_login,other_login_must');
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function weixin_set(){
        $data=SystemConfig::getMore('invite_code,invite_code_need,must_weixin_login,is_force_login,other_login,other_login_must');
        $this->assign('data',$data);
        return $this->fetch();
    }

}