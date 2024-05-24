<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/5
 * Time: 8:42
 */

namespace app\admin\controller\channel;


use app\admin\controller\AuthController;
use app\admin\model\channel\Channel;
use app\admin\model\channel\ChannelAdmin;
use app\admin\model\user\User;
use service\JsonService;

class Admin extends AuthController
{
    /**
     * 频道管理员页面
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function index()
    {
        $channel_list=Channel::getAllChannelList();
        $this->assign('channel_list',$channel_list);
        return $this->fetch();
    }


    /**
     * 频道管理员列表获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function admin_list()
    {
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['status']=['egt',0];

        $nickname=osx_input('nickname','','text');
        if($nickname!=''){
            $uid=User::where('uid|nickname|phone','like','%'.$nickname.'%')->where('status',1)->limit(2000)->column('uid');
            if(count($uid)){
                $map['uid']=['in',$uid];
            }else{
                $map['uid']=-1;
            }
        }

        $channel_id=osx_input('channel_id',0,'intval');
        if($channel_id!=0){
            $map['channel_id']=$channel_id;
        }
        return JsonService::successlayui(ChannelAdmin::getListPage($map,$page,$limit));
    }

    /**
     * 设置开启关闭某个管理员
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function set_status(){
        $status=osx_input('status',1,'intval');
        $id=osx_input('id','','intval');
        ($status=='' || $id=='') && JsonService::fail('缺少参数');
        $res=ChannelAdmin::where(['id'=>$id])->update(['status'=>(int)($status)]);
        if($res){
            return JsonService::successful($status==1 ? '开启成功':'关闭成功');
        }else{
            return JsonService::fail($status==1 ? '开启失败':'关闭失败');
        }
    }

    /**
     * -删除频道管理员
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function delete_admin()
    {
        $id=osx_input('id','','intval');
        $id=='' && JsonService::fail('缺少参数');
        $res=ChannelAdmin::where(['id'=>$id])->update(['status'=>-1]);
        if($res){
            return JsonService::successful('删除成功');
        }else{
            return JsonService::fail('删除失败');
        }
    }


    /**
     * 设为频道管理员页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function set_admin_channel()
    {
        $uid=osx_input('uid',0,'intval');
        $channel_list=Channel::getAllChannelList();
        $now_channel_ids=ChannelAdmin::where('uid',$uid)->where('status','in',[0,1])->column('channel_id');
        foreach ($channel_list as &$val){
            if(in_array($val['id'],$now_channel_ids)){
                $val['now_has']=1;
            }else{
                $val['now_has']=0;
            }
        }
        unset($val);
        $this->assign('channel_list',$channel_list);
        $this->assign('uid',$uid);
        return $this->fetch();
    }

    /**
     * 设为频道管理员操作（新增有效，去除需到频道管理员列表操作）
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function do_set_admin_channel()
    {
        $uid=osx_input('post.uid',0,'intval');
        $channel_ids=$_POST['channel_ids'];
        foreach ($channel_ids as $key=>&$val){
            $val=intval($val);
            if($val==0){
                unset($channel_ids[$key]);
            }
        }
        unset($val);
        if(!count($channel_ids)){
            return JsonService::fail('请至少选择一个频道');
        }

        $already_has_channel_ids=ChannelAdmin::where('uid',$uid)->where('status','in',[0,1])->column('channel_id');

        if($already_has_channel_ids){
            $new_channel_ids=array_diff($channel_ids,$already_has_channel_ids);
        }else{
            $new_channel_ids=$channel_ids;
        }
        if(count($new_channel_ids)){//新增
            $new_default_data=[
                'uid'=>$uid,
                'do_uid'=>$this->adminId,
                'create_time'=>time(),
                'status'=>1
            ];
            foreach ($new_channel_ids as $val){
                $save_data=$new_default_data;
                $save_data['channel_id']=$val;
                ChannelAdmin::set($save_data);
            }
            unset($val);
        }

        return JsonService::success('设为频道管理员操作成功！');
    }
}