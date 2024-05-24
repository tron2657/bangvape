<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/6/25
 * Time: 13:07
 */

namespace app\admin\controller\channel;


use app\admin\controller\AuthController;
use app\admin\model\channel\Channel;
use app\admin\model\channel\ChannelAdmin;
use app\admin\model\channel\ChannelPost;
use app\admin\model\channel\ChannelPostHide;
use app\admin\model\com\ComThread;
use app\admin\model\system\SystemConfig;
use app\admin\model\user\User;
use app\osapi\lib\ChuanglanSmsApi;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageTemplate;
use service\FormBuilder;
use service\JsonService;
use think\Cache;
use think\Request;
use think\Url;

class Post extends AuthController
{
    /**
     * 信息流页面
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function index()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $status=1;
        if($channel_id==0){
            JsonService::fail('非法操作！');
        }
        $this->assign('channel_id',$channel_id);
        $channel_title=Channel::where('id',$channel_id)->value('title');
        $this->assign('channel_title',$channel_title);
        $channel_admin_list=ChannelAdmin::getChannelAdminList($channel_id);
        $this->assign('channel_admin_list',$channel_admin_list);

        $this->assign([
            'year'   => getMonth('y'),
            'status' => $status
        ]);

        return $this->fetch();
    }


    /**
     * 信息流列表获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function post_list()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $status=1;
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['channel_id']=$channel_id;
        $map['status']=$status;

        $post_title=osx_input('post_title','','text');
        if($post_title!=''){
            $map['post_title']=['like','%'.$post_title.'%'];
        }

        $post_author=osx_input('post_author','','text');
        if($post_author!=''){
            $map['post_author']=['like','%'.$post_author.'%'];
        }

        $post_type=osx_input('post_type','','text');
        if($post_type!=''){
            $map['post_type']=$post_type;
        }

        $post_long=osx_input('post_long','','text');
        if($post_long!=''){
            $map['post_long']=$post_long;
        }

        $recommend_uid=osx_input('recommend_uid','','text');
        if($recommend_uid!=''){
            $map['recommend_uid']=$recommend_uid;
        }

        $is_hide=osx_input('is_hide','','text');
        if($is_hide!=''){
            switch ($is_hide){
                case 1:
                    $map['is_hide']=0;
                    $map['deadline']=['gt',time()];
                    break;
                case 2:
                    $map['is_hide']=1;
                    break;
                case 3:
                    $map['is_hide']=0;
                    $map['deadline']=['elt',time()];
                    break;
            }
        }
        $time_data=osx_input('data','','text');
        return JsonService::successlayui(ChannelPost::getPostListPage($map,$page,$limit,$time_data));
    }


    /**
     * 审核池页面
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function audit()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $status=2;
        if($channel_id==0){
            JsonService::fail('非法操作！');
        }
        $this->assign('channel_id',$channel_id);
        $channel_title=Channel::where('id',$channel_id)->value('title');
        $this->assign('channel_title',$channel_title);
        $channel_admin_list=ChannelAdmin::getChannelAdminList($channel_id);
        $this->assign('channel_admin_list',$channel_admin_list);

        $this->assign([
            'year'   => getMonth('y'),
            'status' => $status
        ]);

        return $this->fetch();
    }


    /**
     * 审核池列表获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function audit_list()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $status=2;
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['channel_id']=$channel_id;
        $map['status']=$status;

        $post_title=osx_input('post_title','','text');
        if($post_title!=''){
            $map['post_title']=['like','%'.$post_title.'%'];
        }

        $post_author=osx_input('post_author','','text');
        if($post_author!=''){
            $map['post_author']=['like','%'.$post_author.'%'];
        }

        $post_long=osx_input('post_long','','text');
        if($post_long!=''){
            $map['post_long']=$post_long;
        }

        $recommend_uid=osx_input('recommend_uid','','text');
        if($recommend_uid!=''){
            $map['recommend_uid']=$recommend_uid;
        }

        $time_data=osx_input('data','','text');
        return JsonService::successlayui(ChannelPost::getPostListPage($map,$page,$limit,$time_data));
    }

    /**
     * -审核通过单个
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function audit_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $post_info=ChannelPost::find($id);
        $thread_info=ComThread::find($post_info['post_id']);
        $deadline=Channel::dealPostLongToTime($post_info['post_long']);
        $res=ChannelPost::where(['id'=>$id])->update([
            'status'=>1,
            'deadline'=>$deadline,
            'update_time'=>time(),
            'post_support_count'=>$thread_info['support_count'],
            'post_comment_count'=>$thread_info['reply_count'],
            'post_collect_count'=>$thread_info['collect_count'],
            'post_create_time'=>(isset($thread_info['send_time'])&&$thread_info['send_time']>0)?$thread_info['send_time']:$thread_info['create_time'],
            'post_comment_time'=>$thread_info['last_post_time'],
            'post_update_time'=>$thread_info['update_time']
        ]);
        if($res){
            //审核通过发送消息
            $set=MessageTemplate::getMessageSet(60);
            if($set['status']==1){
                $template=str_replace('{推送时间}', time_format($post_info['create_time']), $set['template']);
                $channel_title=Channel::where('id',$post_info['channel_id'])->value('title');
                $template=str_replace('{XX频道}', $channel_title, $template);
                if($thread_info['title']==''){
                    $title=text(json_decode($thread_info['content'],true));
                }else{
                    $title=$thread_info['title'];
                }
                if(mb_strlen($title)>7){
                    $title=mb_substr($title,0,7,'utf-8').'……';
                }
                $template=str_replace('{帖子标题}', $title, $template);
                $message_id=Message::sendMessage($post_info['recommend_uid'],0,$template,1,$set['title'],1,'','channel',$post_info['channel_id']);
                $read_id=MessageRead::createMessageRead($post_info['recommend_uid'],$message_id,$set['popup'],1);

                if($set['sms']==1){
                    $account=User::where('uid',$post_info['recommend_uid'])->value('phone');
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $template='【'.$config['cl_sms_sign'].'】'.$template;
                    $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                    $sms=json_decode($sms,true);
                    if ($sms['code']==0) {
                        $read_data['is_sms']=1;
                        $read_data['sms_time']=time();
                        MessageRead::where('id',$read_id)->update($read_data);
                    }
                }
            }

            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('操作成功');
        }else{
            return JsonService::fail('操作失败');
        }
    }

    /**
     * -审核通过多个
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function audit_posts()
    {
        $id=osx_input('id','','text');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>['in',$id]])->update(['status'=>1]);
        if($res){
            $id=explode(',',$id);
            $set=MessageTemplate::getMessageSet(60);
            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
            foreach ($id as $val){
                $post_info=ChannelPost::find($val);
                $thread_info=ComThread::find($post_info['post_id']);
                $deadline=Channel::dealPostLongToTime($post_info['post_long']);
                ChannelPost::where(['id'=>$val])->update([
                    'deadline'=>$deadline,
                    'update_time'=>time(),
                    'post_support_count'=>$thread_info['support_count'],
                    'post_comment_count'=>$thread_info['reply_count'],
                    'post_collect_count'=>$thread_info['collect_count'],
                    'post_create_time'=>(isset($thread_info['send_time'])&&$thread_info['send_time']>0)?$thread_info['send_time']:$thread_info['create_time'],
                    'post_comment_time'=>$thread_info['last_post_time'],
                    'post_update_time'=>$thread_info['update_time']
                ]);

                if($set['status']==1){
                    //审核通过发送消息
                    $template=str_replace('{推送时间}',  time_format($post_info['create_time']), $set['template']);
                    $channel_title=Channel::where('id',$post_info['channel_id'])->value('title');
                    $template=str_replace('{XX频道}', $channel_title, $template);
                    if($thread_info['title']==''){
                        $title=text(json_decode($thread_info['content'],true));
                    }else{
                        $title=$thread_info['title'];
                    }
                    if(mb_strlen($title)>7){
                        $title=mb_substr($title,0,7,'utf-8').'……';
                    }
                    $template=str_replace('{帖子标题}', $title, $template);
                    $message_id=Message::sendMessage($post_info['recommend_uid'],0,$template,1,$set['title'],1,'','channel',$post_info['channel_id']);
                    $read_id=MessageRead::createMessageRead($post_info['recommend_uid'],$message_id,$set['popup'],1);

                    if($set['sms']==1){
                        $account=User::where('uid',$post_info['recommend_uid'])->value('phone');
                        $template='【'.$config['cl_sms_sign'].'】'.$template;
                        $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                        $sms=json_decode($sms,true);
                        if ($sms['code']==0) {
                            $read_data['is_sms']=1;
                            $read_data['sms_time']=time();
                            MessageRead::where('id',$read_id)->update($read_data);
                        }
                    }
                }
            }
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('批量操作成功');
        }else{
            return JsonService::fail('批量操作失败');
        }
    }

    public function audit_fail()
    {
        $ids=osx_input('ids','','text');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data['ids']) {
                return JsonService::fail('非法操作');
            }
            $audit_fail_reason=text($data['audit_fail_reason']);
            if (!$audit_fail_reason) {
                return JsonService::fail('请填写反馈说明');
            }

            if (ChannelPost::where('id', 'in', $ids)->update(['audit_fail_reason' => $audit_fail_reason, 'status' => 0,'update_time'=>time()])){
                $ids=explode(',',$ids);
                $set=MessageTemplate::getMessageSet(61);
                if($set['status']==1){
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    foreach ($ids as $val){
                        $post_info=ChannelPost::find($val);
                        $thread_info=ComThread::find($post_info['post_id']);

                        //审核驳回发送消息
                        $template=str_replace('{推送时间}', time_format($post_info['create_time']), $set['template']);
                        $channel_title=Channel::where('id',$post_info['channel_id'])->value('title');
                        $template=str_replace('{XX频道}', $channel_title, $template);
                        if($thread_info['title']==''){
                            $title=text(json_decode($thread_info['content'],true));
                        }else{
                            $title=$thread_info['title'];
                        }
                        if(mb_strlen($title)>7){
                            $title=mb_substr($title,0,7,'utf-8').'……';
                        }
                        $template=str_replace('{帖子标题}', $title, $template);
                        $template=str_replace('{XXX}', $audit_fail_reason, $template);
                        $message_id=Message::sendMessage($post_info['recommend_uid'],0,$template,1,$set['title'],1,'','',0);
                        $read_id=MessageRead::createMessageRead($post_info['recommend_uid'],$message_id,$set['popup'],1);
                        if($set['sms']==1){
                            $account=User::where('uid',$post_info['recommend_uid'])->value('phone');
                            $template='【'.$config['cl_sms_sign'].'】'.$template;
                            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                            $sms=json_decode($sms,true);
                            if ($sms['code']==0) {
                                $read_data['is_sms']=1;
                                $read_data['sms_time']=time();
                                MessageRead::where('id',$read_id)->update($read_data);
                            }
                        }
                    }
                }
                return JsonService::successful('操作成功');
            }else{
                return JsonService::fail('操作失败');
            }
        }
        $field = [
            FormBuilder::textarea('audit_fail_reason', '反馈说明')->maxlength(140)->placeholder('请输入内容')->required("内容不能为空")->rows(5),
            FormBuilder::hidden('ids', $ids),
        ];
        $form = FormBuilder::make_post_form('审核反馈', $field, Url::build('audit_fail'), 2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 屏蔽列表页面
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function hide()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        if($channel_id==0){
            JsonService::fail('非法操作！');
        }
        $this->assign('channel_id',$channel_id);
        $channel_title=Channel::where('id',$channel_id)->value('title');
        $this->assign('channel_title',$channel_title);

        $this->assign([
            'status' => 10
        ]);
        return $this->fetch();
    }


    /**
     * 屏蔽列表获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function hide_list()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['channel_id']=$channel_id;

        return JsonService::successlayui(ChannelPostHide::getPostListPage($map,$page,$limit));
    }


    public function delete_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>$id])->update(['status'=>-1]);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('删除成功');
        }else{
            return JsonService::fail('删除失败');
        }
    }

    public function delete_posts()
    {
        $id=osx_input('id','','text');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>['in',$id]])->update(['status'=>-1]);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('批量删除成功');
        }else{
            return JsonService::fail('批量删除失败');
        }
    }

    /**
     * 屏蔽操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function hide_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>$id])->update(['is_hide'=>1]);
        if($res){
            $channel_post=ChannelPost::find($id);
            if(!ChannelPostHide::where('channel_id',$channel_post['channel_id'])->where('post_id',$channel_post['post_id'])->find()){
                ChannelPostHide::set([
                    'channel_id'=>$channel_post['channel_id'],
                    'post_id'=>$channel_post['post_id'],
                    'uid'=>$this->adminId,
                    'create_time'=>time()
                ]);
            }
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('屏蔽成功');
        }else{
            return JsonService::fail('屏蔽失败');
        }
    }

    /**
     * 批量屏蔽操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function hide_posts()
    {
        $id=osx_input('id','','text');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>['in',$id]])->update(['is_hide'=>1]);
        if($res){
            $ids=explode(',',$id);
            foreach ($ids as $val){
                $channel_post=ChannelPost::find($id);
                if(!ChannelPostHide::where('channel_id',$channel_post['channel_id'])->where('post_id',$channel_post['post_id'])->find()) {
                    ChannelPostHide::set([
                        'channel_id' => $channel_post['channel_id'],
                        'post_id' => $channel_post['post_id'],
                        'uid' => $this->adminId,
                        'create_time' => time()
                    ]);
                }
            }
            unset($val);
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('批量屏蔽成功');
        }else{
            return JsonService::fail('批量屏蔽失败');
        }
    }

    /**
     * 取消屏蔽
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function un_hide_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>$id])->update(['is_hide'=>0]);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            $channel_post=ChannelPost::find($id);
            ChannelPostHide::where('channel_id',$channel_post['channel_id'])->where('post_id',$channel_post['post_id'])->delete();
            return JsonService::successful('取消屏蔽成功');
        }else{
            return JsonService::fail('取消屏蔽失败');
        }
    }

    /**
     * -屏蔽列表页面-取消屏蔽
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function cancel_hide_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $channel_post_hide=ChannelPostHide::find($id);
        $res=ChannelPostHide::where(['id'=>$id])->delete();
        if($res){
            $res2=ChannelPost::where('channel_id',$channel_post_hide['channel_id'])->where('post_id',$channel_post_hide['post_id'])->setField('is_hide',0);
            if($res2){
                //清除信息流缓存
                Cache::clear('channel_list_change');
            }
            return JsonService::successful('取消屏蔽成功');
        }else{
            return JsonService::fail('取消屏蔽失败');
        }
    }

    /**
     * 置顶
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function top_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>$id])->update(['is_top'=>1]);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('置顶成功');
        }else{
            return JsonService::fail('置顶失败');
        }
    }

    /**
     * 取消置顶
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function un_top_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPost::where(['id'=>$id])->update(['is_top'=>0]);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('取消置顶成功');
        }else{
            return JsonService::fail('取消置顶失败');
        }
    }

    /**
     * 移动页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function reset_channel()
    {
        $post_id=osx_input('post_id',0,'text');
        $post_ids=explode(',',$post_id);
        $now_channel=osx_input('now_channel',0,'intval');
        //is_type 用于判断是否是批量处理0非1是
        $is_type=osx_input('is_type',0,'intval');
        $channel_list=Channel::getAllChannelList();
        $now_channel_ids=ChannelPost::where('post_id','in',$post_ids)->where('status','in',[1,2])->column('channel_id');
        foreach ($channel_list as &$val){
            if(in_array($val['id'],$now_channel_ids)){
                $val['now_has']=1;
            }else{
                $val['now_has']=0;
            }
        }
        unset($val);
        $this->assign('channel_list',$channel_list);
        $this->assign('now_channel',$now_channel);
        $this->assign('post_id',$post_id);
        $this->assign('is_type',$is_type);
        return $this->fetch();
    }

    /**
     * 执行移动操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_reset_channel()
    {
        $post_id=osx_input('post.post_id',0,'text');
        $now_channel=osx_input('post.now_channel',0,'intval');
        $is_type=osx_input('is_type',0,'intval');
        $channel_ids=$_POST['channel_ids'];
        foreach ($channel_ids as $key=>&$val){
            $val=intval($val);
            if($val==0){
                unset($channel_ids[$key]);
            }
        }
        unset($val);
        if(!count($channel_ids)){
            return JsonService::fail('请至少选择一个移动位置');
        }
        $post_ids=explode(',',$post_id);
        if($is_type==1){
            $post_ids=ChannelPost::where('id','in',$post_ids)->column('post_id');
        }
        foreach ($post_ids as $post_id){
            $already_has_channel_ids=ChannelPost::where('post_id',$post_id)->where('status','in',[1,2])->column('channel_id');

            $new_channel_ids=array_diff($channel_ids,$already_has_channel_ids);
            if(count($new_channel_ids)){//新增
                //清除信息流缓存
                Cache::clear('channel_list_change');
                $post_info=ChannelPost::where('channel_id',$now_channel)->where('post_id',$post_id)->find();
                $new_default_data=[
                    'post_id'=>$post_id,
                    'post_title'=>$post_info['post_title'],
                    'post_author'=>$post_info['post_author'],
                    'post_support_count'=>$post_info['post_support_count'],
                    'post_comment_count'=>$post_info['post_comment_count'],
                    'post_collect_count'=>$post_info['post_collect_count'],
                    'post_create_time'=>$post_info['post_create_time'],
                    'post_comment_time'=>$post_info['post_comment_time'],
                    'post_update_time'=>$post_info['post_update_time'],
                    'recommend_uid'=>$this->adminId,
                    'status'=>1,
                    'is_hide'=>0,
                    'post_type'=>2,
                    'type'=>$post_info['type'],
                    'post_long'=>$post_info['post_long'],
                    'deadline'=>$post_info['deadline'],
                    'sort_num'=>$post_info['sort_num'],
                    'image_show_type'=>$post_info['image_show_type'],
                    'is_top'=>$post_info['is_top'],
                    'create_time'=>time(),
                    'update_time'=>time(),
                ];
                foreach ($new_channel_ids as $val){
                    $save_data=$new_default_data;
                    $save_data['channel_id']=$val;
                    ChannelPost::set($save_data);
                }
                unset($val);
            }
            $del_channel_ids=array_diff($already_has_channel_ids,$channel_ids);
            if(count($del_channel_ids)){//去除多余的
                //清除信息流缓存
                Cache::clear('channel_list_change');
                ChannelPost::where('post_id',$post_id)->where('channel_id','in',$del_channel_ids)->delete();
            }
        }
        return JsonService::success('移动操作成功！');
    }


    /**
     * 推送编辑-供帖子列表等地方调用
     * @return mixed|void
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function edit_channel_post_for_other()
    {
        $post_id=osx_input('post_id',0,'intval');
        $channel_post=ChannelPost::where('post_id',$post_id)->where('status',1)->order('deadline desc,id asc')->find();
        if(!$channel_post){
            $channel_post=[
                'image_show_type'=>0,
                'post_long'=>1,
                'channel_id'=>'',
                'sort_num'=>0
            ];
        }
        $already_in_ids=ChannelPost::where('post_id',$post_id)->where('status',1)->column('channel_id');
        $already_in_audit_ids=ChannelPost::where('post_id',$post_id)->where('status',2)->column('channel_id');

        $channel_list=Channel::getAllChannelList();
        $post_detail=ComThread::find($post_id);
        if(!$post_detail||$post_detail['status']!=1){
            return JsonService::fail('帖子已被禁用或删除!');
        }

        $un_show_image_select=0;
        if($post_detail['is_weibo']==1||in_array($post_detail['type'],[6,7])){
            $un_show_image_select=1;
        }else{
            $post_detail['images']=ChannelPost::getPostImages($post_detail['image']);

            if($channel_post['image_show_type']==0){//未做相关配置，则获取图片数量为默认值
                if(count($post_detail['images'])){
                    if(count($post_detail['images'])>3){
                        $channel_post['image_show_type']=3;
                    }else{
                        $channel_post['image_show_type']=count($post_detail['images']);
                    }
                }else{
                    $channel_post['image_show_type']=4;//无图
                }
            }
        }

        $this->assign([
            'channel_post'=>$channel_post,
            'channel_list'=>$channel_list,
            'post_detail'=>$post_detail,
            'already_in_ids'=>json_encode($already_in_ids),
            'already_in_audit_ids'=>json_encode($already_in_audit_ids),
            'un_show_image_select'=>$un_show_image_select
        ]);
        return $this->fetch();
    }


    /**
     * 执行推送编辑-检查已存在
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_edit_channel_post_with_check()
    {
        $post_id=osx_input('post_id',0,'intval');
        $channel_id=osx_input('channel_id',0,'intval');
        if(!$post_id){
            return JsonService::fail('非法操作');
        }
        if(!$channel_id){
            return JsonService::fail('请选择频道');
        }
        $post_long=osx_input('post_long',1,'intval');
        $sort_num=osx_input('sort_num',0,'intval');
        $image_show_type=osx_input('image_show_type',1,'intval');
        if(!in_array($post_long,[1,2,3,4,5,6])){
            return JsonService::fail('请选择推送时长');
        }
        if($sort_num>100||$sort_num<0){
            return JsonService::fail('请输入正确的排序权重');
        }
        if(!in_array($image_show_type,[1,2,3,4])){
            return JsonService::fail('请选择图片形式');
        }
        $post_info=ChannelPost::where('post_id',$post_id)
            ->where('channel_id',$channel_id)
            ->where('status','in',[1,2])->find();
        if($post_info){
            $data['deadline']=Channel::dealPostLongToTime($post_long);
            $data['post_long']=$post_long;
            $data['sort_num']=$sort_num;
            $data['image_show_type']=$image_show_type;
            $data['update_time']=time();
            $data['post_type']=2;//编辑后自动变成手动推送
            $res=ChannelPost::edit($data,$post_info['id']);
            if($res){
                if($post_info['status']==2){
                    //审核通过发送消息
                    $set=MessageTemplate::getMessageSet(60);
                    if($set['status']==1){
                        $template=str_replace('{推送时间}', time_format($post_info['create_time']), $set['template']);
                        $channel_title=Channel::where('id',$post_info['channel_id'])->value('title');
                        $template=str_replace('{XX频道}', $channel_title, $template);
                        $thread_info=ComThread::find($post_info['post_id']);
                        if($thread_info['title']==''){
                            $title=text(json_decode($thread_info['content'],true));
                        }else{
                            $title=$thread_info['title'];
                        }
                        if(mb_strlen($title)>7){
                            $title=mb_substr($title,0,7,'utf-8').'……';
                        }
                        $template=str_replace('{帖子标题}', $title, $template);
                        $message_id=Message::sendMessage($post_info['recommend_uid'],0,$template,1,$set['title'],1,'','channel',$post_info['channel_id']);
                        $read_id=MessageRead::createMessageRead($post_info['recommend_uid'],$message_id,$set['popup'],1);

                        if($set['sms']==1){
                            $account=User::where('uid',$post_info['recommend_uid'])->value('phone');
                            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                            $template='【'.$config['cl_sms_sign'].'】'.$template;
                            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                            $sms=json_decode($sms,true);
                            if ($sms['code']==0) {
                                $read_data['is_sms']=1;
                                $read_data['sms_time']=time();
                                MessageRead::where('id',$read_id)->update($read_data);
                            }
                        }
                    }
                }
                //清除信息流缓存
                Cache::clear('channel_list_change');
                return JsonService::success('推送编辑成功');
            }else{
                return JsonService::fail('推送编辑失败');
            }
        }else{
            $thread_info=ComThread::find($post_id);
            $author_info=User::where('uid',$thread_info['author_uid'])->field('nickname,phone')->find();
            $deadline=Channel::dealPostLongToTime($post_long);
            $new_default_data=[
                'post_id'=>$post_id,
                'post_title'=>$thread_info['title'],
                'post_author'=>'【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')',
                'post_support_count'=>$thread_info['support_count'],
                'post_comment_count'=>$thread_info['reply_count'],
                'post_collect_count'=>$thread_info['collect_count'],
                'post_create_time'=>$thread_info['create_time'],
                'post_comment_time'=>$thread_info['last_post_time'],
                'post_update_time'=>$thread_info['update_time'],
                'recommend_uid'=>$this->adminId,
                'status'=>1,
                'is_hide'=>0,
                'post_type'=>2,
                'type'=>$thread_info['type'],
                'post_long'=>$post_long,
                'deadline'=>$deadline,
                'sort_num'=>$sort_num,
                'image_show_type'=>$image_show_type,
                'is_top'=>0,
                'create_time'=>time(),
                'update_time'=>time(),
                'channel_id'=>$channel_id
            ];
            if($thread_info['is_weibo']==1){
                $new_default_data['post_title']=text($thread_info['content']);
            }
            $res=ChannelPost::set($new_default_data);
            if($res){
                return JsonService::success('推送编辑成功');
            }else{
                return JsonService::fail('推送编辑失败');
            }
        }
    }

    /**
     * 推送编辑
     * @return mixed|void
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function edit_channel_post()
    {
        $id=osx_input('id',0,'intval');
        $channel_post=ChannelPost::find($id);
        if(!$channel_post){
            return JsonService::fail('非法操作!');
        }
        $channel_post['channel_title']=Channel::where('id',$channel_post['channel_id'])->value('title');
        $post_detail=ComThread::find($channel_post['post_id']);
        if(!$post_detail||$post_detail['status']!=1){
            return JsonService::fail('帖子已被禁用或删除!');
        }

        $un_show_image_select=0;
        if($post_detail['is_weibo']==1||in_array($post_detail['type'],[6,7])){
            $un_show_image_select=1;
        }else{
            $post_detail['images']=ChannelPost::getPostImages($post_detail['image']);

            if($channel_post['image_show_type']==0){//未做相关配置，则获取图片数量为默认值
                if(count($post_detail['images'])){
                    if(count($post_detail['images'])>3){
                        $channel_post['image_show_type']=3;
                    }else{
                        $channel_post['image_show_type']=count($post_detail['images']);
                    }
                }else{
                    //咨询帖默认是单图
                    if($post_detail['type']==4){
                        $channel_post['image_show_type']=1;
                    }else{
                        $channel_post['image_show_type']=4;//无图
                    }

                }
            }
        }

        $this->assign([
            'channel_post'=>$channel_post,
            'post_detail'=>$post_detail,
            'un_show_image_select'=>$un_show_image_select
        ]);
        return $this->fetch();
    }

    /**
     * -界面样式预览数据
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function getPostInfo()
    {
        $post_id=osx_input('post_id',0,'intval');
        $post_detail=ComThread::find($post_id);
        $post_detail['images']=ChannelPost::getPostImages($post_detail['image']);

        $post_detail['user']=db('user')->where('uid',$post_detail['author_uid'])->field('nickname,avatar,account')->find();
        $post_detail['post_time']=time_format($post_detail['create_time']);
        $post_detail['content']=json_decode($post_detail['content'],true);
        $is_post=0;
        $is_weibo=0;
        $is_news=0;
        $is_video=0;
        if($post_detail['is_weibo']==1){
            $image_num=count($post_detail['images']);
            $is_weibo=1;
        }else{
            $image_num=0;
            switch ($post_detail['type']){
                case 4:
                    if(count($post_detail['images'])>3){
                        $post_detail['images']=array_slice($post_detail['images'],0,3);
                    }
                    $is_news=1;
                    break;
                case 6:
                case 7:
                    $is_video=1;
                    break;
                case 1:
                case 2:
                case 3:
                case 5:
                default:
                    if(count($post_detail['images'])>3){
                        $post_detail['images']=array_slice($post_detail['images'],0,3);
                    }
                    $is_post=1;
            }
        }

        $image_400_200=$image_456_456=[];
        foreach ($post_detail['images'] as $val){
            $thumb_image=getThumbImage($val,400,200);
            $image_400_200[]=get_root_path($thumb_image['src']);
            $thumb_image=getThumbImage($val,456,456);
            $image_456_456[]=get_root_path($thumb_image['src']);
        }
        unset($val);
        $post_detail['images']=[
            'image_400_200'=>$image_400_200,
            'image_456_456'=>$image_456_456
        ];

        return JsonService::success('ok',[
            'post_detail'=>$post_detail,
            'is_post'=>$is_post,
            'is_weibo'=>$is_weibo,
            'is_news'=>$is_news,
            'is_video'=>$is_video,
            'weibo_image_num'=>$image_num
        ]);
    }

    /**
     * 执行推送编辑
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_edit_channel_post()
    {
        $id=osx_input('id',0,'intval');
        $post_long=osx_input('post_long',1,'intval');
        $sort_num=osx_input('sort_num',0,'intval');
        $image_show_type=osx_input('image_show_type',1,'intval');
        if(!in_array($post_long,[1,2,3,4,5,6])){
            return JsonService::fail('请选择推送时长');
        }
        if($sort_num>100||$sort_num<0){
            return JsonService::fail('请输入正确的排序权重');
        }
        if(!in_array($image_show_type,[1,2,3,4])){
            return JsonService::fail('请选择图片形式');
        }
        $data['deadline']=Channel::dealPostLongToTime($post_long);
        $data['post_long']=$post_long;
        $data['sort_num']=$sort_num;
        $data['image_show_type']=$image_show_type;
        $data['update_time']=time();
        $data['post_type']=2;//编辑后自动变成手动推送
        $res=ChannelPost::edit($data,$id);
        if($res){
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::success('推送编辑成功');
        }else{
            return JsonService::fail('推送编辑失败');
        }
    }

    /**
     * 立即更新
     * 2020.9.11
     */
    public function update_post(){
        $res=\app\osapi\model\channel\Channel::autoRecommendPost(1);
        if($res===false){
            return JsonService::fail('更新失败');
        }else{
            return JsonService::success('更新成功');
        }
    }

    /**
     *获取立即更新进度
     * 2020.9.11
     */
    public function get_update_process(){
        $all=Cache::get('_channel_count_1');
        $now=Cache::get('_channel_now_count_1');
//        if($all==$now){
//            Cache::rm('_channel_count_1');
//            Cache::rm('_channel_now_count_1');
//        }
        return JsonService::success('获取成功',['all'=>$all,'now'=>$now]);
    }
}