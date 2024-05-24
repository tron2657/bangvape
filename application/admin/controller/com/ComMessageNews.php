<?php
namespace app\admin\controller\com;

use app\admin\controller\AuthController;
use app\admin\model\com\ComThread;
use app\admin\model\com\ComThreadUser;
use app\osapi\model\com\Message;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Cache;
use think\Request;
use app\admin\model\com\MessageNews as MessageNewsModel;
use think\Url;
use app\admin\model\system\SystemAttachment;
use app\admin\model\user\User as UserModel;
use app\admin\model\com\ComThread as ThreadModel;
use app\admin\model\com\ComThreadClass as ThreadClassModel;
use app\admin\model\com\ComForum as ForumModel;
use app\admin\model\system\SystemAdmin;
use app\osapi\model\com\MessageRead;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ComMessageNews extends AuthController
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
     * 营销消息列表
     *
     * @return json
     */
    public function message_news_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['status',''],
            ['title',''],
        ]);
        return JsonService::successlayui(MessageNewsModel::MessageNewsList($where));
    }

    public function create_message_news()
    {
        $select=db('com_forum')->where('status',1)->where('display',1)->where('pid','>',0)->where('type','in',array(1,8))->select();
        $this->assign('select',$select);
        $this->assign('style','create');
        return $this->fetch();
    }

    public function select_class(Request $request)
    {
        $data = Util::postMore([
            'id',
        ],$request);
        $select=db('com_thread_class')->where('status',1)->where('fid',$data['id'])->select();
        Json::successful($select);
    }

    /**
     * 新增营销消息
     */
    public function add_message_news(Request $request){
        $data = Util::postMore([
            'title',
            'logo',
            'fid',
            'class_id',
            'author_uid',
            ['summary',''],
            ['to_uid',0],
            ['to_type_uid',0],
            ['false_view',0],
            ['image',''],
            ['is_auto_image',1],
            ['is_weibo',0],
            'send_time',
            ['end_time',''],
            ['send_app','']
        ],$request);

        $data['content']=osx_input('post.content','','html');

        if($data['summary']==''){
            $data['summary']=mb_substr(text($data['content']),0,30);
        }
        $map1['title']=$data['title'];
        $map1['fid']=$data['fid'];
        $map1['class_id']=$data['class_id'];
        $map1['content']=$data['content'];
        $map1['create_time']=time();
        $map1['summary']=$data['summary'];
        $map1['false_view']=$data['false_view'];
        $map1['status']=1;
        $map1['fid']=1;
        $map1['class_id']=1;
        $map1['author_uid']=ComThreadUser::get_thread_user($this->adminId);
        $map1['from']='HouTai';
        $map1['is_massage']=1;
        $map1['type']=9;
        $map1['image']=$data['image'];
        $map1['is_auto_image']=$data['is_auto_image'];
        $map1['is_weibo']=$data['is_weibo'];
        $result = ThreadModel::createThread($map1); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        $map2['tid']=$result;
        $map2['fid']=$data['fid'];
        $map2['class_id']=$data['class_id'];
        $map2['false_view']=$data['false_view'];
        $map2['title']=$data['title'];
        $map2['content']=$data['content'];
        $map2['logo']=$data['logo'];
        $map2['to_uid']=$data['to_uid'];
        $map2['to_type_uid']=$data['to_type_uid'];
        $map2['from_uid']=ComThreadUser::get_thread_user($this->adminId);
        $map2['create_time']=time();
        $map2['summary']=$data['summary'];
        $map2['send_time']=$data['send_time'];
        if($data['end_time']==''){
            $map2['end_time']=64708014463;
        }else{
            $map2['end_time']=$data['end_time'];
        }
        $map2['status']=1;
        $map2['admin_uid']=SystemAdmin::activeAdminIdOrFail();
        $res=MessageNewsModel::createMessageNews($map2);
        $uid=$data['to_uid']?explode('，',$data['to_uid']):'';
        Message::update_message_census($uid,'message_new',$map2);
        if ($result && $res) {
            if($data['send_app']==1){
                app_send_message_all($data['title'],$data['content']);
            }
            $data2['is_read']=0;
            $data2['is_popup']=1;
            $data2['popup_time']=time();
            $data2['is_sms']=1;
            $data2['sms_time']=time();
            $data2['type']=6;
            $data2['create_time']=time();
            $data2['message_id']=$res;
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
            $res=array();
            $res['thread_id']=$result;
            $res['info']='发布成功';
            Json::successful($res);
        } else {
            JsonService::fail('创建营销消息失败');
        }
    }

    /**
     * 编辑营销消息
     */
    public function edit_message_news(Request $request){
        $data = Util::postMore([
            'id',
            'title',
            'fid',
            'class_id',
            'logo',
            ['false_view',0],
            ['summary',''],
            ['to_uid',0],
            ['to_type_uid',0],
            'send_time',
            ['end_time',''],
        ],$request);

        $data['content']=osx_input('post.content','','html');

        if($data['summary']==''){
            $data['summary']=mb_substr(text($data['content']),0,30);
        }
        $map1['title']=$data['title'];
        $map1['fid']=$data['fid'];
        $map1['class_id']=$data['class_id'];
        $map1['content']=json_encode($data['content']);
        $map1['summary']=$data['summary'];
        $map1['false_view']=$data['false_view'];
        $map1['author_uid']=ComThreadUser::get_thread_user($this->adminId);
        $tid=MessageNewsModel::where('id',$data['id'])->value('tid');
        $result = ThreadModel::where('id',$tid)->update($map1); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        $map2['from_uid']=ComThreadUser::get_thread_user($this->adminId);;
        $map2['title']=$data['title'];
        $map2['fid']=$data['fid'];
        $map2['class_id']=$data['class_id'];
        $map2['content']=$data['content'];
        $map2['logo']=$data['logo'];
        $map2['to_uid']=$data['to_uid'];
        $map2['to_type_uid']=$data['to_type_uid'];
        $map2['summary']=$data['summary'];
        $map2['false_view']=$data['false_view'];
        $map2['send_time']=$data['send_time'];
        if($data['end_time']==''){
            $map2['end_time']=64708014463;
        }else{
            $map2['end_time']=$data['end_time'];
        }
        $map2['status']=1;
        $map2['admin_uid']=SystemAdmin::activeAdminIdOrFail();
        $res=MessageNewsModel::where('id',$data['id'])->update($map2);
        if ($res!==false && $result!==false) {
            Cache::rm('thread_detail_'.$tid);
            MessageRead::beginTrans();
            MessageRead::where('message_id',$data['id'])->where('type',6)->delete();
            $data2['is_read']=0;
            $data2['is_popup']=1;
            $data2['popup_time']=time();
            $data2['is_sms']=1;
            $data2['sms_time']=time();
            $data2['type']=6;
            $data2['create_time']=time();
            $data2['message_id']=$data['id'];
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
            $res='编辑成功';
            Json::successful($res);
        } else {
            JsonService::fail('编辑失败');
        }
    }

    public function view(){
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $messageNews = MessageNewsModel::getOne($id);
        $messageNews['user']=UserModel::where('uid',$messageNews['from_uid'])->value('nickname');
        if(!$messageNews) return Json::fail('数据不存在!');
        $thread=ComThread::where('id',$messageNews['tid'])->find();
        $messageNews['false_view']=$thread['false_view'];
        $messageNews['fid']=$thread['fid'];
        $messageNews['class_id']=$thread['class_id'];
        $select=db('com_forum')->where('status',1)->where('display',1)->where('pid','>',0)->where('type','in',array(1,8))->select();
        $class=db('com_thread_class')->where('status',1)->where('fid',$thread['fid'])->select();
        $this->assign('select',$select);
        $this->assign('class',$class);
        $this->assign('messageNews',$messageNews);
        $this->assign('style','view');
        return $this->fetch('view');
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
            'start_time',
            'end_time',
            'fid',
        ],$request);

        $data['content']=osx_input('post.content','','html');

        $data['start_time']=strtotime($data['start_time']);
        $data['end_time']=strtotime($data['end_time']);
        MessageNewsModel::edit($data,$id);
        return Json::successful('成功');
    }

    /**
     *  qhy
     * 获取用户组
     */
    public function get_group(){
        $data['type1']=db('group')->where('status',1)->where('type',1)->select();
        $data['type2']=db('group')->where('status',1)->where('type',2)->select();
        $data['type3']=db('group')->where('status',1)->where('type',3)->select();
        $data['type4']=db('group')->where('status',1)->where('type',4)->select();
        $data['type5']=db('group')->where('status',1)->where('type',5)->select();
        $data['type6']=db('group')->where('status',1)->where('type',6)->select();
        return Json::successful($data);
    }

    /**
     * 删除指定资源
     * @return \think\Response
     */
    public function delete()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $res=MessageNewsModel::delete_ann($id);
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
        $res=MessageNewsModel::close($id);
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
        $res=MessageNewsModel::open($id);
        if($res==1){
            return Json::successful('推送成功');
        }else{
            JsonService::fail('推送失败');
        }
    }

    public function withdraw()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $res=MessageNewsModel::withdraw($id);
        if($res==1){
            return Json::successful('撤回成功');
        }else{
            JsonService::fail('撤回失败');
        }
    }

    public function find_users(){
        $nickname=osx_input('nickname','','text');
        $users=UserModel::where('nickname|uid|phone','like',"%$nickname%")->limit(10)->select()->toArray();
        $data=array();
        if($users){

            foreach ($users as $v){
                if($v){
                    $data[]=array('value'=>$v['uid'],'name'=>$v['nickname']);
                }
            }
        }

        return Json::successlayui(count($users),$data,'成功');

    }

    /**
     * 显示编辑资源表单页.
     * @return \think\Response
     */
    public function edit()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $messageNews = MessageNewsModel::getOne($id);
        $messageNews['user']=UserModel::where('uid',$messageNews['from_uid'])->value('nickname');
        if(!$messageNews) return Json::fail('数据不存在!');
        $thread=ComThread::where('id',$messageNews['tid'])->find();
        $messageNews['false_view']=$thread['false_view'];
        $messageNews['fid']=$thread['fid'];
        $messageNews['class_id']=$thread['class_id'];
        $select=db('com_forum')->where('status',1)->where('display',1)->where('pid','>',0)->where('type','in',array(1,8))->select();
        $class=db('com_thread_class')->where('fid',$thread['fid'])->where('status',1)->select();
        $this->assign('select',$select);
        $this->assign('class',$class);
        $this->assign('messageNews',$messageNews);
        $this->assign('style','edit');
        return $this->fetch('create_message_news');
    }

    public function bind_user_vim()
    {
        return $this->fetch();
    }

    /**
     * 判断用户
     */
    public function find_user(Request $request)
    {
        $data = Util::postMore([
            'phone',
        ],$request);
        $phone=db('user')->where('phone',$data['phone'])->where('status',1)->value('phone');
        if(!$phone){
            JsonService::fail('该用户不存在');
        }else{
            return Json::successful($phone);
        }
    }

    /**
     * 获取当前绑定用户
     */
    public function get_user()
    {
        $phone_uid=ComThreadUser::get_thread_user($this->adminId);;
        $user=db('user')->where('uid',$phone_uid)->field('uid,nickname')->find();
        return Json::successful($user);
    }

    /**
     * 绑定用户
     */
    public function bind_user(Request $request)
    {
        $data = Util::postMore([
            'uid',
        ],$request);
        $uid=db('user')->where('uid',$data['uid'])->where('status',1)->value('uid');
        if(!$uid){
            JsonService::fail('该用户不存在');
        }
        $res=ComThreadUser::set_thread_user($this->adminId,$data['uid']);
        if($res!==false){
            return Json::successful('绑定成功!');
        }else{
            JsonService::fail('绑定失败!');
        }
    }

    /**
     * 批量删除
     */
    public function remove(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要删除的数据');
        }else{
            $res=MessageNewsModel::where('id','in',$post['ids'])->update(['status'=>-1]);
            $tids=MessageNewsModel::where('id','in',$post['ids'])->column('tid');
            ComThread::where('id','in',$tids)->update(['status'=>-1]);
            MessageRead::where('message_id','in',$post['ids'])->where('type',6)->delete();
            if($res)
                return JsonService::successful('成功');
            else
                return JsonService::fail('失败');
        }
    }

}
