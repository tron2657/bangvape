<?php
namespace app\admin\controller\com;

use app\admin\controller\AuthController;
use app\admin\model\com\Message;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Request;
use app\admin\model\com\Message as MessageModel;
use app\admin\model\com\MessageTemplate;
use think\Url;
use app\admin\model\system\SystemAttachment;
use app\admin\model\user\User as UserModel;
use app\admin\model\com\ComThread as ThreadModel;
use app\admin\model\com\ComThreadClass as ThreadClassModel;
use app\admin\model\com\ComForum as ForumModel;
use app\osapi\model\com\MessageRead;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ComMessage extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $map_common = ['status'=>1,'type_id'=>1];
        $map_band = ['status'=>0,'type_id'=>1];
        $map_recycle = ['status'=>-1,'type_id'=>1];


        $status=$this->request->param('status');

        //已发布
        $common = MessageModel::where($map_common)->where('type_now','>',0)->count();
        //未发布
        $band = MessageModel::where($map_band)->where('type_now','>',0)->count();
        //已关闭
        $recycle =  MessageModel::where($map_recycle)->where('type_now','>',0)->count();
        $count = array(
            'orderCount'=>1
        );
        $this->assign(compact('status','common','band','recycle','count'));
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
    public function message_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['status',1],
        ]);
        return JsonService::successlayui(MessageModel::MessageList($where));
    }

    public function message_reminder_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
        ]);
        return JsonService::successlayui(MessageTemplate::MessageReminderList($where));
    }

    public function set_status(){
        $status=osx_input('status','');
        $id=osx_input('id',0,'intval');
        ($status=='' || $id=='') && JsonService::fail('缺少参数');
        $res=MessageTemplate::where(['id'=>$id])->update(['status'=>(int)($status)]);
        if($res){
            return JsonService::successful($status==1 ? '开启成功':'关闭成功');
        }else{
            return JsonService::fail($status==1 ? '开启失败':'关闭失败');
        }
    }

    public function set_popup(){
        $popup=osx_input('popup','');
        $id=osx_input('id',0,'intval');
        ($popup=='' || $id=='') && JsonService::fail('缺少参数');
        $res=MessageTemplate::where(['id'=>$id])->update(['popup'=>(int)($popup)]);
        if($res){
            return JsonService::successful($popup==1 ? '开启成功':'关闭成功');
        }else{
            return JsonService::fail($popup==1 ? '开启失败':'关闭失败');
        }
    }

    public function edit_template()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $message = MessageTemplate::getOne($id);
        if(!$message) return Json::fail('数据不存在!');
        switch($message['type']){
            case 1:
                $message['type']='服务提醒';
                break;
            case 2:
                $message['type']='系统通知';
                break;
        }
        $field = [
            Form::input('title','名称',$message['title'])->readonly(1),
            Form::input('action','触发条件',$message['action'])->type('textarea')->readonly(1),
            Form::input('template','消息模版',$message['template'])->type('textarea')->readonly(1),
            Form::input('type','消息类型',$message['type'])->readonly(1),
            Form::radio('sms', '短信通知', $message['sms'])->options([
                ['value'=>'1','label'=>'开启'],
                ['value'=>'0','label'=>'关闭'],
            ]),
            Form::radio('popup', '弹窗提示', $message['popup'])->options([
                ['value'=>'1','label'=>'开启'],
                ['value'=>'0','label'=>'关闭'],
            ]),
            Form::radio('status', '状态', $message['status'])->options([
                ['value'=>'1','label'=>'开启'],
                ['value'=>'0','label'=>'禁用'],
            ]),
        ];
        $form = Form::make_post_form('编辑消息模版',$field,Url::build('update_template',array('id'=>$id)),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function update_template(Request $request)
    {
        $id=osx_input('id',0,'intval');
        $data = Util::postMore([
            'sms',
            'popup',
            'status',
        ],$request);
        MessageTemplate::edit($data,$id,'id');
        return Json::successful('成功');
    }

    public function create()
    {
        $field = [
            Form::input('title','标题')->col(Form::col(24)),
            Form::input('content','内容')->type('textarea'),
            Form::radio('type_now', '通知类型', 1)->options([
                ['value'=>'1','label'=>'系统通知'],
                ['value'=>'2','label'=>'用户通知'],
                ['value'=>'3','label'=>'活动通知'],
            ])->col(Form::col(12)),
            Form::radio('send_app', 'APP消息推送', 1)->options([
                ['value'=>'0','label'=>'不推送'],
                ['value'=>'1','label'=>'推送'],
            ])->col(Form::col(12)),
            //Form::dateTime('send_time','推送时间'),
        ];
        $form = Form::make_post_form('创建通知',$field,Url::build('save'),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = Util::postMore([
            'title',
            'content',
            'type_now',
            ['to_uid',0],
            ['to_type_uid',0],
            ['send_time',0],
            ['send_app',0],
            ['url',''],
        ],$request);
        $data['from_uid']=0;
        $data['type_id']=7;
        $data['from_type']=1;
        $data['create_time']=time();
        $data['status']=1;
        $message_id=MessageModel::insertGetId($data);
        $uid=$data['to_uid']?explode('，',$data['to_uid']):'';
        \app\osapi\model\com\Message::update_message_census($uid,'notice',$data);

        if($data['send_app']==1){
            //发送app消息
            app_send_message_all($data['title'],$data['content']);
        }
        $data2['is_read']=0;
        $data2['is_popup']=1;
        $data2['popup_time']=time();
        $data2['is_sms']=1;
        $data2['sms_time']=time();
        $data2['type']=7;
        $data2['create_time']=time();
        $data2['message_id']=$message_id;
        if($data['to_uid']!=0){
            $gids=explode(",",$data['to_uid']);
            if(in_array(8,$gids)){
                $uids=UserModel::where('status',1)->column('uid');
            }else{
                $uids=db('bind_group_uid')->where('g_id','in',$gids)->where('status',1)->where(function ($query) {
                    $query->where('end_time',0)->whereOr('end_time', '>',time());
                })->column('uid');
                $uids=array_unique($uids);
            }
        }else{
            $uids=UserModel::where('status',1)->column('uid');
        }
        $message_read=array();
        foreach($uids as &$item){
            $data2['uid']=$item;
            $message_read[]=$data2;
        }
        unset($item);
        MessageRead::insertAll($message_read);

        return Json::successful('成功');
    }

    public function create_message()
    {
        $this->assign('style','create');
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $id=osx_input('id',0,'intval');
        $data = Util::postMore([
            'title',
            'content',
            'type_now',
            ['to_uid',0],
            ['to_type_uid',0],
            ['send_time',0],
            ['send_app',0],
            ['url',''],
        ],$request);
        $res=MessageModel::edit($data,$id,'id');
        if($res!==false){
            MessageRead::beginTrans();
            MessageRead::where('message_id',$id)->where('type',5)->delete();
            $data2['is_read']=0;
            $data2['is_popup']=1;
            $data2['popup_time']=time();
            $data2['is_sms']=1;
            $data2['sms_time']=time();
            $data2['type']=7;
            $data2['create_time']=time();
            $data2['message_id']=$id;
            if($data['to_uid']!=0){
                $gids=explode(",",$data['to_uid']);
                if(in_array(8,$gids)){
                    $uids=UserModel::where('status',1)->column('uid');
                }else{
                    $uids=db('bind_group_uid')->where('g_id','in',$gids)->where('status',1)->where(function ($query) {
                        $query->where('end_time',0)->whereOr('end_time', '>',time());
                    })->column('uid');
                    $uids=array_unique($uids);
                }
            }else{
                $uids=UserModel::where('status',1)->column('uid');
            }
            $message_read=array();
            foreach($uids as &$item){
                $data2['uid']=$item;
                $message_read[]=$data2;
            }
            unset($item);
            MessageRead::insertAll($message_read);
            MessageRead::commitTrans();
            return Json::success('编辑成功');
        }else{
            return Json::fail('编辑失败');
        }
    }

    /**
     * 删除指定资源
     * @return \think\Response
     */
    public function delete()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $res=MessageModel::delete_ann($id);
        if($res==1){
            return Json::successful('删除成功');
        }else{
            JsonService::fail('删除失败');
        }
    }

    public function close()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $res=MessageModel::close($id);
        if($res==1){
            return Json::successful('关闭成功');
        }else{
            JsonService::fail('关闭失败');
        }
    }

    public function open()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $res=MessageModel::open($id);
        if($res==1){
            return Json::successful('推送成功');
        }else{
            JsonService::fail('推送失败');
        }
    }

    /**
     * 显示编辑资源表单页.
     * @return \think\Response
     */
    public function edit()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $message = MessageModel::getOne($id);
        if(!$message) return Json::fail('数据不存在!');
        $this->assign('message',$message);
        $this->assign('style','edit');
        return $this->fetch('create_message');
    }

    /**
     * 显示编辑资源表单页.
     * @return \think\Response
     */
    public function view()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $message = MessageModel::getOne($id);
        if(!$message) return Json::fail('数据不存在!');
        $this->assign('message',$message);
        $this->assign('style','view');
        return $this->fetch('create_message');
    }

}
