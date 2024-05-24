<?php
namespace app\admin\controller\com;

use app\admin\controller\AuthController;
use app\admin\model\group\Power;
use app\admin\model\system\SystemConfig;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Cache;
use think\Request;
use app\admin\model\com\ComForumAdminScoreLog as ForumAdminScoreLogModel;
use app\admin\model\com\ComForum as ForumModel;
use think\Url;
use app\admin\model\user\User as UserModel;
use app\admin\model\system\SystemAdmin;
use app\admin\model\com\ComForumAdminApply;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ComForumAdminScoreLog extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign([
            'year' => getMonth('y'),
        ]);
        return $this->fetch();
    }

    /**
     * @return json
     */
    public function log_list(){
        $where = Util::getMore([
            ['uid',''],
            ['do_uid',''],
            ['model',''],
            ['page',1],
            ['limit',20],
            ['data',''],
        ]);
        return JsonService::successlayui(ForumAdminScoreLogModel::LogList($where));
    }

    public function set_admin()
    {
        return $this->fetch();
    }

}
