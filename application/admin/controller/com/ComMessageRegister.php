<?php
namespace app\admin\controller\com;

use app\admin\controller\AuthController;
use basic\ModelBasic;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Cache;
use think\Request;
use app\admin\model\com\MessageRegister as MessageRegisterModel;
use app\admin\model\com\MessageTemplate;
use think\Url;
use app\osapi\model\user\UserModel;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ComMessageRegister extends AuthController
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

    public function setting()
    {
        $open=db('message_register')->where(['status'=>1])->order('id desc')->field('open_time,is_open')->find();
        $this->assign([
            'open_time'=>$open['open_time'],
            'is_open'=>$open['is_open'],
        ]);
      return $this->fetch();
    }

    public function message_reminder()
    {
        return $this->fetch();
    }

    /**
     * 公告列表
     *
     * @return json
     */
    public function message_register_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
        ]);
        return JsonService::successlayui(MessageRegisterModel::MessageRegisterList($where));
    }

    /**
     * @return mixed|\think\response\Json|void
     */
    public function edit()
    {
        $id=osx_input('id',0,'intval');
        $info = MessageRegisterModel::get($id);
        $info['user'] = UserModel::where('uid', $info['author_uid'])->value('nickname');
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(1, 8))->select();
        $class = db('com_thread_class')->where('fid', $info['fid'])->where('status', 1)->select();
        $info['content'] = json_decode($info['content']);
        if ($info['image']) {
            $image = json_decode($info['image'], true);
            if (is_array($image)) {
                $info['image'] = $image;
            } else {
                $image = array();
                $image[] = $info['image'];
                $info['image'] = $image;
            }
            $info['is_auto_image'] = 0;
        } else {
            $info['is_auto_image'] = 1;
        }
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'edit');
        $this->assign('id', $id);
        $this->assign('status',$info['status']);
        return $this->fetch('create_message_register');
    }

    /**
     * 编辑帖子
     */
    public function edit_thread(Request $request)
    {
        $data = Util::postMore([
            'id',
            'title',
            'author_uid',
            'from',
            'send_time',
            ['cover', 0],
            ['summary', ''],
            ['type', ''],
            ['image', ''],
            ['is_auto_image', 1],
            ['false_view', 0],
            'fid',
            'class_id',
            ['is_weibo', 0],
            ['type_name',''],
            ['status',1],
        ], $request);
        $data['content']=osx_input('post.content','','html');
        if($data['send_time']>time()){
            JsonService::fail('发送时间不能大于当前时间');
        }
        $data['update_time'] = time();
        $thread_id=MessageRegisterModel::where(['id'=>$data['id']])->value('bind_thread');
        ModelBasic::beginTrans();
        $result = MessageRegisterModel::editThread($data);
        $message_id=$data['id'];
        unset($data['id']);
        $data['forum']='HouTai';
        $data['content']=json_encode($data['content']);
        if($thread_id){
            $res_thread=db('com_thread')->where(['id'=>$thread_id])->update($data);
        }else{
            $id=db('com_thread')->insertGetId($data);
            $res_thread=MessageRegisterModel::where(['id'=>$message_id])->update(['bind_thread'=>$id]);
        }
        if ($result !== false&&$res_thread) {
            ModelBasic::commitTrans();
            Cache::rm('message_register');
            $res['info'] = '编辑成功';
            $res['data'] =$message_id;
            Json::successful($res);
        } else {
            ModelBasic::rollbackTrans();
            JsonService::fail('编辑失败');
        }
    }

    public function site_edit(Request $request){
        $data = Util::postMore([
            'is_open',
            'open_time',
        ], $request);
        $result = MessageRegisterModel::where('id',1)->update($data);
        if ($result !== false) {
            Json::successful('编辑成功');
        } else {
            JsonService::fail('编辑失败');
        }
    }

}
