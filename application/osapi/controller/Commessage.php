<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/5/24
 * Time: 17:11
 */

namespace app\osapi\controller;


use app\imapi\model\im\ImModel;
use app\osapi\model\com\MessageNews;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageUserPopup;
use app\admin\model\com\ComThread;

class ComMessage extends Base
{

    /**
     * 运营消息
     */
    public function messageNew(){
        $page = input('page',1);
        $row = input('row', 10);
        $message_new=MessageNews::getMessageNews($page,$row);
        //消息已读更新
        Message::update_message_census(get_uid(),'message_new');
        //已读消息更新 end
        $this->apiSuccess($message_new);
    }

    /**
     * 自定义消息
     */
    public function messageNotice(){
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $uid=get_uid();
        $notice = Message::getNotice($uid,$page,$row);
        //消息已读更新
        Message::update_message_census($uid,'notice');
        //已读消息更新 end
        $this->apiSuccess($notice);
    }

    /**
     * 系统通知
     */
    public function messageUserNotice(){
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $uid=get_uid();
        $notice = Message::getUserNotice($uid,$page,$row);
        //消息已读更新
        Message::update_message_census($uid,'message');
        //已读消息更新 end
        $this->apiSuccess($notice);
    }

    /**
     * 评论消息
     */
    public function messageComment(){
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $type=osx_input('type',1);
        $uid=get_uid();
        $notice = Message::getCommentMessage($uid,$page,$row,$type);

        //消息已读更新
        Message::update_message_census($uid,'reply_count');
        //已读消息更新end

        $this->apiSuccess($notice);
    }

    /**
     * 被赞消息
     */
    public function messageSupport(){
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $uid=get_uid();
        $notice = Message::getSupportMessage($uid,$page,$row);
        //消息已读更新
        Message::update_message_census($uid,'support_count');
        //已读消息更新 end
        $this->apiSuccess($notice);
    }

    /**
     * 新增关注
     */
    public function messageInteraction(){
        $uid=get_uid();
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $notice = Message::getInteractionMessage($uid,$page,$row);
        //消息已读更新
        Message::update_message_census($uid,'follow_count');
        //已读消息更新 end
        $this->apiSuccess($notice);
    }

    /**
     * 新动态消息
     */
    public function messageNewSend(){
        $uid=get_uid();
        $page=input('post.page/d',1);
        $row=input('post.row/d',10);
        $notice = Message::getNewSendMessage($uid,$page,$row);
        //消息已读更新
        Message::update_message_census($uid,'message_new_send');
        //已读消息更新 end
        $this->apiSuccess($notice);
    }

    /**
     * 获取新消息
     */
    public function newMessage(){
        $count = Message::getMessageCount();
        $this->apiSuccess($count);
    }

    /**
     * 用户设置弹窗
     */
    public function user_set_popup(){
        $status=input('status','','intval');
        $uid=get_uid();
        $res = MessageUserPopup::setUserPopup($uid,$status);
        if($res===false){
            $this->apiError('设置失败');
        }else{
            $this->apiSuccess('设置成功');
        }
    }

    /**
     * 获取用户弹窗设置
     */
    public function get_user_popup(){
        $uid=get_uid();
        $res = MessageUserPopup::getUserPopup($uid);
        $this->apiSuccess($res);
    }

    /**
     * 消息首页
     */
    public function message_index(){
        $uid=$this->_needLogin();
//        $res['message_new'] = MessageNews::getUserMessageNew($uid);//营销消息 1
//        $res['message'] = Message::getUserMessage($uid);//系统消息
//        $res['notice'] = Message::getUserNewNotice($uid);//自定义消息
//        $res['message_new_send'] = Message::getUserNewSend($uid);//新动态消息
//        $res['reply_count']=MessageRead::getReplyCount($uid);//未读回复消息数
//        $res['support_count']=MessageRead::getSupportCount($uid);//未读点赞消息数
//        $res['follow_count']=MessageRead::getFollowCount($uid);//未读新关注消息数
        $res=Message::message_census($uid);
        //已处理
//        $open_list=$this->_getClientOpenList();
//        if(in_array('im',$open_list)){
//            $code=$this->_getCode();
//            $res['im_count']=ImModel::get_read_count($uid,$code);//未读私信数
//        }else{
//            $res['im_count']=0;
//        }
        $res['im_count']=0;
        $create_time=db('user')->where(['uid'=>$uid])->cache('user_add_time_'.$uid)->value('add_time');
        $register=db('message_register')->where(['status'=>1])->cache('message_register')->order('id desc')->find();
        if($create_time+$register['open_time']*3600*24>time()){
            $register['user']=db('user')->where(['uid'=>$register['author_uid']])->field('uid,nickname,avatar')->find();
            $register['content']=json_decode($register['content'],true);
            $register['send_time']=time_to_show($create_time);
            $register['image']=json_decode($register['image'],true);
            $res['message_register']=$register;
        }
        $this->apiSuccess($res);
    }

    /**
     * 单条消息设置为已读
     */
    public function setReadOne(){
        $message_id=input('message_id','','intval');
        $uid=get_uid();
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('message_id',$message_id)->where('uid',$uid)->update($data);
        $this->apiSuccess('设置成功');
    }

    /**
     * 弹窗列表
     */
    public function popupList(){
        $uid=get_uid();
        $message_ids=MessageRead::where('is_popup',0)->where('uid',$uid)->order('create_time desc')->limit(10)->column('message_id');
        if($message_ids){
            $message=Message::where('id','in',$message_ids)->select()->toArray();
            foreach($message as &$value){
                if($value['route']=='reply'||$value['route']=='thread'){
                    $value['thread_id']=ComThread::where('post_id',$value['link_id'])->value('id');
                }
            }
            $data['is_popup']=1;
            $data['popup_time']=time();
            MessageRead::where('is_popup',0)->where('uid',$uid)->update($data);
            $this->apiSuccess($message);
        }else{
            $message='';
            $this->apiSuccess($message);
        }
    }
    /**
     * 关闭所有的弹出的框
     * 2020.7.20
     */
    public function close_popup(){
        $uid=get_uid();
        $res=MessageRead::where('is_popup',0)->where('uid',$uid)->update(['is_popup'=>1]);
        if($res!==false){
            $this->apiSuccess('关闭成功');
        }else{
            $this->apiError('错误成功');
        }
    }



}