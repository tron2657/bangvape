<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/4
 * Time: 15:20
 */

namespace app\admin\controller\channel;



use app\admin\controller\AuthController;
use app\admin\model\channel\Channel;
use app\admin\model\channel\ChannelPost;
use app\admin\model\channel\ChannelPostPool;
use app\admin\model\com\ComThread;
use app\admin\model\user\User;
use app\osapi\lib\ChuanglanSmsApi;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageTemplate;
use service\JsonService;
use think\Cache;

class PostPool extends AuthController
{
    /**
     * 备选池页面
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function index()
    {
        return $this->fetch();
    }


    /**
     * 信息流列表获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function post_list()
    {
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['status']=1;

        $post_title=osx_input('post_title','','text');
        if($post_title!=''){
            $map['post_title']=['like','%'.$post_title.'%'];
        }

        $post_author=osx_input('post_author','','text');
        if($post_author!=''){
            $map['post_author']=['like','%'.$post_author.'%'];
        }
        return JsonService::successlayui(ChannelPostPool::getListPage($map,$page,$limit));
    }

    /**
 * -删除备选池信息流
 * @author zzl(zzl@dianyun.ren)
 * @date 2020-7
 */
    public function delete_post()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPostPool::where('id',$id)->setField('status',-1);
        if($res){
            return JsonService::successful('删除成功');
        }else{
            return JsonService::fail('删除失败');
        }
    }

    /**
     * -批量删除备选池信息流
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function delete_posts()
    {
        $id=osx_input('id','','text');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelPostPool::where('id','in',$id)->setField('status',-1);
        if($res){
            return JsonService::successful('批量删除成功');
        }else{
            return JsonService::fail('批量删除失败');
        }
    }


    /**
     * 立即推送页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function reset_channel()
    {
        $post_id=osx_input('post_id',0,'intval');
        $post_pool_id=osx_input('post_pool_id',0,'intval');
        $channel_list=Channel::getAllChannelList();
        $now_channel_ids=ChannelPost::where('post_id',$post_id)->where('status','in',[1,2])->column('channel_id');
        foreach ($channel_list as &$val){
            if(in_array($val['id'],$now_channel_ids)){
                $val['now_has']=1;
            }else{
                $val['now_has']=0;
            }
        }
        unset($val);
        $this->assign('channel_list',$channel_list);
        $this->assign('post_pool_id',$post_pool_id);
        $this->assign('post_id',$post_id);
        return $this->fetch();
    }

    /**
     * 立即推送操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_reset_channel()
    {
        $post_id=osx_input('post.post_id',0,'intval');
        $post_pool_id=osx_input('post.post_pool_id',0,'intval');
        $channel_ids=$_POST['channel_ids'];
        foreach ($channel_ids as $key=>&$val){
            $val=intval($val);
            if($val==0){
                unset($channel_ids[$key]);
            }
        }
        unset($val);
        if(!count($channel_ids)){
            return JsonService::fail('请至少选择一个推送位置');
        }

        $already_has_channel_ids=ChannelPost::where('post_id',$post_id)->where('status','in',[1,2])->column('channel_id');

        if($already_has_channel_ids){
            $new_channel_ids=array_diff($channel_ids,$already_has_channel_ids);
        }else{
            $new_channel_ids=$channel_ids;
        }
        if(count($new_channel_ids)){//新增
            if(!$post_pool_id){
                $post_pool_info=ChannelPostPool::where('status','gt',0)->where('post_id',$post_id)->find();
                if(!$post_pool_info){
                    $thread=ComThread::where('id',$post_id)->field('image,type')->find();
                    $image=$thread['image'];
                    $images=ChannelPost::getPostImages($image);
                    if(count($images)){
                        if(count($images)>3){
                            $image_show_type=3;
                        }else{
                            $image_show_type=count($images);
                        }
                    }else{
                        //咨询帖默认是单图
                        if($thread['type']==4){
                            $image_show_type=1;
                        }else{
                            $image_show_type=4;//无图
                        }

                    }
                    $post_pool_info=[
                        'post_long'=>1,
                        'sort_num'=>0,
                        'image_show_type'=>$image_show_type
                    ];
                }
            }else{
                $post_pool_info=ChannelPostPool::where('id',$post_pool_id)->find();
            }

            $thread_info=ComThread::find($post_id);
            $author_info=User::where('uid',$thread_info['author_uid'])->field('nickname,phone')->find();
            $deadline=Channel::dealPostLongToTime($post_pool_info['post_long']);
            $new_default_data=[
                'post_id'=>$post_id,
                'post_title'=>$thread_info['title'],
                'post_author'=>'【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')',
                'post_support_count'=>$thread_info['support_count'],
                'post_comment_count'=>$thread_info['reply_count'],
                'post_collect_count'=>$thread_info['collect_count'],
                'post_create_time'=>(isset($thread_info['send_time'])&&$thread_info['send_time']>0)?$thread_info['send_time']:$thread_info['create_time'],
                'post_comment_time'=>$thread_info['last_post_time'],
                'post_update_time'=>$thread_info['update_time'],
                'recommend_uid'=>$this->adminId,
                'status'=>1,
                'is_hide'=>0,
                'post_type'=>2,
                'type'=>$thread_info['type'],
                'post_long'=>$post_pool_info['post_long'],
                'deadline'=>$deadline,
                'sort_num'=>$post_pool_info['sort_num'],
                'image_show_type'=>$post_pool_info['image_show_type'],
                'is_top'=>$thread_info['is_top'],
                'create_time'=>time(),
                'update_time'=>time(),
            ];
            if($thread_info['is_weibo']==1){
                $new_default_data['post_title']=text($thread_info['content']);
            }
            foreach ($new_channel_ids as $val){
                $save_data=$new_default_data;
                $save_data['channel_id']=$val;
                ChannelPost::set($save_data);
                //清除信息流缓存
                Cache::clear('channel_list_change');
            }
            unset($val);
        }

        if($post_pool_id){
            //备选池页面操作
            $del_channel_ids=array_diff($already_has_channel_ids,$channel_ids);
            if(count($del_channel_ids)){//去除多余的
                //清除信息流缓存
                Cache::clear('channel_list_change');
                ChannelPost::where('post_id',$post_id)->where('channel_id','in',$del_channel_ids)->delete();
            }

            //推送后备选池删除
            ChannelPostPool::where('id',$post_pool_id)->setField('status',-1);
        }else{
            //帖子列表页操作
        }
        return JsonService::success('立即推送操作成功！');
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
        $channel_post_pool=ChannelPostPool::find($id);
        if(!$channel_post_pool){
            return JsonService::fail('非法操作!');
        }else{
            $post_id=$channel_post_pool['post_id'];
        }
        $this->assign('channel_post_pool_id',$id);
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
     * 执行推送编辑
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_edit_channel_post()
    {
        $id=osx_input('id',0,'intval');
        $post_id=osx_input('post_id',0,'intval');
        $channel_id=osx_input('channel_id',0,'intval');
        if($id==0){
            return JsonService::fail('非法操作');
        }
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

                    //清除信息流缓存
                    Cache::clear('channel_list_change');
                }
                ChannelPostPool::where('id',$id)->setField('status',-1);
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
                ChannelPostPool::where('id',$id)->setField('status',-1);
                return JsonService::success('推送编辑成功');
            }else{
                return JsonService::fail('推送编辑失败');
            }
        }
    }


    /**
     * -帖子加入备选操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function add_to_post_pool()
    {
        $post_id=osx_input('post_id',0,'intval');
        if($post_id==0){
            return JsonService::fail('非法操作');
        }

        $data['update_time']=time();
        $data['status']=1;
        $id=ChannelPostPool::where('status','gt',0)->where('post_id',$post_id)->value('id');
        if($id){
            $res=ChannelPostPool::edit($data,$id);
        }else{
            $thread_info=ComThread::find($post_id);
            if(!$thread_info||$thread_info['status']!=1){
                return JsonService::fail('帖子已被禁用或删除!');
            }
            $author_info=User::where('uid',$thread_info['author_uid'])->field('nickname,phone')->find();

            $data['post_long']=1;
            $data['sort_num']=0;

            $images=ChannelPost::getPostImages($thread_info['image']);
            if(count($images)){
                if(count($images)>3){
                    $image_show_type=3;
                }else{
                    $image_show_type=count($images);
                }
            }else{
                $image_show_type=4;//无图
            }
            $data['image_show_type']=$image_show_type;
            $data['post_id']=$post_id;
            $data['status']=1;
            $data['create_time']=time();
            $data['recommend_uid']=$this->adminId;
            if($thread_info['is_weibo']==1){
                $data['post_title']=text($thread_info['content']);
            }else{
                $data['post_title']=$thread_info['title'];
            }
            $data['post_author']='【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')';

            $res=ChannelPostPool::set($data);
        }
        if($res){
            return JsonService::success('加入备选成功');
        }else{
            return JsonService::fail('加入备选失败');
        }
    }
}