<?php
/**
 * Created by PhpStorm.
 * User: zxh
 * Date: 2020/3/31
 * Time: 9:20
 */
namespace app\commonapi\controller;


use app\admin\model\system\SystemConfig;
use app\commonapi\model\talk\TalkContent;
use app\osapi\controller\Base;
use app\commonapi\model\talk\Talk as TalkModel;
use service\JsonService;
class Talk extends Base
{
    /**
     * 获取会话id
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public function get_talk(){
        $uid=$this->_needLogin();
        $to_uid=osx_input('post.to_uid',0,'intval');
        if($to_uid<=0){
            return JsonService::fail('请选择聊天对象');
        }
        $id=TalkModel::check_talk($uid,$to_uid);
        return JsonService::success('获取成功',['id'=>$id]);
    }

    /**
     * 获取我的会话列表
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public function get_my_talk(){
        $uid=$this->_needLogin();
        $page=osx_input('post.page',0,'intval');
        $limit=osx_input('post.limit',10,'intval');
        $data=TalkModel::get_my_talk($uid,$page,$limit);
        return JsonService::success($data);
    }

    /**
     * 聊天内容增加
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public function add_talk_content(){
        $data['talk_id']=osx_input('post.talk_id',0,'intval');
        $data['uid']=$this->_needLogin();
        if($data['talk_id']<=0){
            return JsonService::fail('请选择聊天框');
        }
        //判断每天最多给｛n｝位用户发信息
        $talk_limit=SystemConfig::getValue('send_talk_limit');
        $today=strtotime(date('Y-m-d',time()).' 00:00:00');
        $talk_count=TalkContent::where(['uid'=>$data['uid'],'talk_id'=>['neq',$data['talk_id']],'create_time'=>['between',[$today,time()]]])->group('talk_id')->count();
        if($talk_count>=$talk_limit){
            return JsonService::fail('每天最多给'.$talk_limit.'位用户发信息');
        }
        $data['content']=osx_input('post.content','','text');
        $data['image']=osx_input('post.image',0,'text');
        if(!$data['content']&&!$data['image']){
            return JsonService::fail('请输入发送的内容');
        }
        $res=TalkContent::addData($data);
        if($res){
            return JsonService::success('发送成功');
        }else{
            return JsonService::fail('发送失败');
        }
    }

    /**
     * 获取会话内容
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public function get_talk_list(){
        $talk_id=osx_input('post.talk_id',0,'intval');
        $uid=$this->_needLogin();
        $page=osx_input('post.page',0,'intval');
        $limit=osx_input('post.limit',10,'intval');
        $data=TalkContent::get_talk_list($talk_id,$uid,$page,$limit);
        return JsonService::success($data);
    }

    /**
     *判断是否可以发送图片（只有相互关注的人才可以发送图片）
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public function is_send_image(){
        $talk_id=osx_input('post.talk_id',0,'intval');
        $this->_needLogin();
        $talk=TalkModel::get_talk($talk_id);
        $count1=db('user_follow')->where(['uid'=>$talk_id['uid'],'follow_uid'=>$talk['to_uid']])->count();
        $count2=db('user_follow')->where(['follow_uid'=>$talk_id['uid'],'uid'=>$talk['to_uid']])->count();
        if($count1&&$count2){
            $data['power']=1;
        }else{
            $data['power']=0;
        }
        return JsonService::success($data);
    }
}