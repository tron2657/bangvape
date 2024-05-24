<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/5/30
 * Time: 14:52
 */

namespace app\osapi\model\com;


use app\osapi\model\BaseModel;
use app\osapi\model\user\UserModel;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageUserPopup;
use app\admin\model\com\ComThread;
use think\Cache;


class Message extends BaseModel
{

    /**
     * 获取通知消息
     */
    public static function getNotice($uid,$page,$row){
        $map=[
            'uid'=>$uid,
            'type'=>7,
        ];
        $message_id=MessageRead::where($map)->order('create_time desc')->column('message_id');
        $notice=self::where('id','in',$message_id)->where('status',1)->where('send_time','<',time())->page($page,$row)->order('create_time desc')->select()->toArray();
        foreach($notice as &$value){
            $value['url']=link_select_url($value['url']);
            $value['create_time']=time_to_show($value['create_time']);
        }
        unset($value);
        db('message_read')->where($map)->update(['is_read'=>1]);
        return $notice;
    }

    public static function getUserNotice($uid,$page,$row){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>1,
            'from_type'=>1,
        ];
        $notice=self::where($map)->page($page,$row)->where('send_time','<',time())->where('status',1)->order('create_time desc')->select()->toArray();
        foreach($notice as &$value){
            if($value['route']=='thread'){
                $value['thread_id']=ComThread::where('post_id',$value['link_id'])->value('id');
            }
            $value['create_time']=time_to_show($value['create_time']);
        }
        unset($value);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',1)->where('uid',$uid)->where('is_read',0)->update($data);
        return $notice;
    }

    /**
     * 获取评论消息
     */
    public static function getCommentMessage($uid,$page,$row,$type){
        if($type==1){
            $map=[
                'to_uid'=>$uid,
                'type_id'=>2,
            ];
        }else{
            $map=[
                'post_uid'=>$uid,
                'type_id'=>2,
            ];
        }
        $notice=self::where($map)->page($page,$row)->order('create_time desc')->select()->toArray();
        foreach ($notice as &$val){
            $map=[
                'id'=>$val['link_id'],
            ];
            $val['post']=db('com_post')->field('id,fid,tid,author_uid,title,content,image,is_thread')->where($map)->find();
            $val['forum']=db('com_forum')->field('id,pid,name,type')->where('id',$val['post']['fid'])->find();
            $val['user']=UserModel::getUserInfo($val['from_uid']);
            //将图片字段转为数组
            $imgs = json_decode($val['post']['image'],true);
            if(is_array($imgs)){
                $val['post']['image']=$imgs;
                $val['post']['image_cj']=$imgs;
                $val['post']['image_cj_2']=$imgs;
                $width=count($val['post']['image_cj'])==1?912:456;
                foreach($val['post']['image_cj'] as &$value){
                    $value=thumb_path($value,$width,456);
                }
                unset($value);
                foreach($val['post']['image_cj_2'] as &$v){
                    $v=thumb_path($v,700,700);
                }
                unset($v);
                foreach($val['post']['image'] as &$value2){
                    $value2=get_root_path($value2);
                }
                unset($value2);
            }else{
                if($val['post']['image']!='null' && $val['post']['image']!='' && $val['post']['image']!='[]') {
                    $val['post']['image_cj'][0] = thumb_path($val['post']['image'], 1400);
                    $val['post']['image_cj_2'][0] = thumb_path($val['post']['image'], 700);
                    $val['post']['image']=get_root_path($val['post']['image']);
                    $arr=array();
                    $arr[]=$val['post']['image'];
                    $val['post']['image']=$arr;
                }
            }
            $val['post']['content']=text(html_message($val['post']['content']));
            $val['post']['user']=UserModel::getUserInfo($val['post']['author_uid']);
            if($val['post_id']>0){
                $val['post_content']=db('com_post')->field('id,fid,tid,author_uid,title,content')->where('id',$val['post_id'])->find();
                $val['post_content']['user']=UserModel::getUserInfo($val['post_content']['author_uid']);
            }else{
                $val['post_content']='';
            }
            if($val['own_post_id']>0){
                $val['own_post_content']=db('com_post')->field('id,fid,tid,author_uid,title,content')->where('id',$val['own_post_id'])->find();
                $val['own_post_content']['is_support']=db('support')->where('uid',$uid)->where('model','reply')->where('row',$val['own_post_id'])->where('status',1)->count();
                $val['own_post_content']['user']=UserModel::getUserInfo($val['own_post_content']['author_uid']);
            }else{
                $val['own_post_content']='';
            }
            $val['create_time']=time_to_show($val['create_time']);
        }
        unset($val);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',2)->where('uid',$uid)->where('is_read',0)->update($data);
        return $notice;
    }

    /**
     * 获取被赞消息
     */
    public static function getSupportMessage($uid,$page,$row){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>3,
        ];
        $notice=self::where($map)->page($page,$row)->order('create_time desc')->select()->toArray();
        foreach ($notice as &$val){
            $map=[
                'id'=>$val['link_id'],
            ];
            $val['post']=db('com_post')->field('id,fid,tid,author_uid,title,content,image')->where($map)->find();
            //将图片字段转为数组
            $imgs = json_decode($val['post']['image'],true);
            if(is_array($imgs)){
                $val['post']['image']=$imgs;
                $val['post']['image_cj']=$imgs;
                $val['post']['image_cj_2']=$imgs;
                $width=count($val['post']['image_cj'])==1?912:456;
                foreach($val['post']['image_cj'] as &$value){
                    $value=thumb_path($value,$width,456);
                }
                unset($value);
                foreach($val['post']['image_cj_2'] as &$v){
                    $v=thumb_path($v,700,700);
                }
                unset($v);
                foreach($val['post']['image'] as &$value2){
                    $value2=get_root_path($value2);
                }
                unset($value2);
            }else{
                if($val['post']['image']!='null' && $val['post']['image']!='' && $val['post']['image']!='[]') {
                    $val['post']['image_cj'][0] = thumb_path($val['post']['image'], 1400);
                    $val['post']['image_cj_2'][0] = thumb_path($val['post']['image'], 700);
                    $val['post']['image']=get_root_path($val['post']['image']);
                    $arr=array();
                    $arr[]=$val['post']['image'];
                    $val['post']['image']=$arr;
                }
            }
            $val['post']['content']=html_message($val['post']['content']);
            $val['post']['user']=UserModel::getUserInfo($val['post']['author_uid']);
            $val['forum']=db('com_forum')->field('id,pid,name,type')->where('id',$val['post']['fid'])->find();
            $val['user']=UserModel::getUserInfo($val['from_uid']);
            if($val['post_id']>0){
                $val['post_content']=db('com_post')->field('id,fid,tid,author_uid,title,content')->where('id',$val['post_id'])->find();
                $val['post_content']['user']=UserModel::getUserInfo($val['post_content']['author_uid']);
            }else{
                $val['post_content']='';
            }
            $val['create_time']=time_to_show($val['create_time']);
        }
        unset($val);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',3)->where('uid',$uid)->where('is_read',0)->update($data);
        return $notice;
    }

    /**
     * 获取互动消息
     */
    public static function getInteractionMessage($uid,$page,$row){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>4,
        ];
        $notice=self::where($map)->order('create_time desc')->page($page,$row)->select()->toArray();
        $follow_uid=db('user_follow')->where('uid',$uid)->where('status',1)->column('follow_uid');
        foreach ($notice as &$val){
            $val['user']=UserModel::getUserInfo($val['from_uid']);
            if(in_array($val['from_uid'],$follow_uid)){
                $val['user']['is_follow']=1;
            }else{
                $val['user']['is_follow']=0;
            }
            $val['create_time']=time_to_show($val['create_time']);
        }
        unset($val);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',4)->where('uid',$uid)->where('is_read',0)->update($data);
        return $notice;
    }

    /**
     * 获取新动态消息
     */
    public static function getNewSendMessage($uid,$page,$row){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>5,
        ];
        $notice=self::where($map)->order('create_time desc')->page($page,$row)->select()->toArray();
        foreach ($notice as &$val){
            $val['post']=db('com_thread')->where('post_id',$val['link_id'])->field('id,video_id,video_cover,video_url,fid,type,is_weibo,author_uid,title,content,image')->find();
            $img=$val['post']['image'];
            if($img){
                $val['post']['image']=json_decode($img,true);
//                $val['post']['image']=$img[0];
            }
            $val['post']['content']=json_decode($val['post']['content']);
            $val['forum']=db('com_forum')->field('id,pid,name,type')->where('id',$val['post']['fid'])->find();
            $val['user']=UserModel::getUserInfo($val['from_uid']);
            $val['create_time']=time_to_show($val['create_time']);
        }
        unset($val);
        $data['is_read']=1;
        $data['read_time']=time();
        MessageRead::where('type',5)->where('uid',$uid)->where('is_read',0)->update($data);
        return $notice;
    }

    /**
     * 获取用户最新的一条系统消息
     */
    public static function getUserMessage($uid){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>1,
        ];
        $message=self::where($map)->order('send_time desc')->find();
        if($message){
            if($message['route']=='reply'||$message['route']=='thread'){
                $message['thread_id']=ComThread::where('post_id',$message['link_id'])->value('id');
            }
            $message['create_time']=time_to_show($message['create_time']);
            $message['send_time']=time_to_show($message['send_time']);
            $message['is_read']=MessageRead::getMessageCount($uid);
        }else{
            $message='';
        }
        return $message;
    }


    /**
     * 获取用户最新的一条自定义消息
     */
    public static function getUserNewNotice($uid){
        $map=[
            'uid'=>$uid,
            'type'=>7,
        ];
        $message_id=MessageRead::where($map)->order('create_time desc')->find();
        $message=self::where('id',$message_id['message_id'])->where('status',1)->find();
        if($message){
            $message['send_time']=time_to_show($message['send_time']);
            $message['create_time']=time_to_show($message['create_time']);
            $message['url']=link_select_url($message['url']);
            $message['is_read']=MessageRead::getNoticeCount($uid);
        }else{
            $message='';
        }
        return $message;
    }

    /**
     * 获取用户最新的一条新动态消息
     */
    public static function getUserNewSend($uid){
        $map=[
            'to_uid'=>$uid,
            'type_id'=>5,
        ];
        $message=self::where($map)->order('send_time desc')->find();
        if($message){
            $message['create_time']=time_to_show($message['create_time']);
            $message['send_time']=time_to_show($message['send_time']);
            $message['is_read']=MessageRead::getNewSendCount($uid);
        }else{
            $message='';
        }
        return $message;
    }

    /**
     * 获取用户未读信息条数
     */
    public static function getMessageCount(){
        $uid=get_uid();
        $map=[
            'to_uid'=>$uid,
            'is_read'=>0,
        ];
        $count=self::where($map)->count();
        return $count;
    }

    /**
     * 发送消息
     */
    static public function sendMessage($to_uid,$uid = 0,$content,$type = 1,$title,$from_type = 1,$image = '',$route= '',$link_id = '',$post_id = 0,$own_post_id=0,$post_uid=0){
        $data['to_uid']=$to_uid;
        $data['from_uid']=$uid;
        $data['content']=$content;
        $data['type_id']=$type;
        $data['title']=$title;
        $data['from_type']=$from_type;
        $data['image']=$image;
        $data['route']=$route;
        $data['link_id']=$link_id;
        $data['post_id']=$post_id;
        $data['post_uid']=$post_uid;
        $data['own_post_id']=$own_post_id;
        $data['create_time']=time();
        $data['send_time']=time();
        $res=self::add($data);
        if($res){
            $is_read_type=[1=>'message',2=>'reply_count',3=>'support_count',4=>'follow_count',5=>'message_new_send',6=>'message_new',7=>'notice'];
            //最新消息内容更新
            if(array_key_exists($type,$is_read_type)){
                self::update_message_census($to_uid,$is_read_type[$type],$data);
            }
            //最新消息内容更新end
            //发送app消息内容
//            app_send_message_one($to_uid,$content,$route,$link_id,$title);
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 删除消息
     */
    static public function delMessage($to_uid,$uid = 0,$type = 1,$from_type = 1,$route= '',$link_id = ''){
        $data['to_uid']=$to_uid;
        $data['from_uid']=$uid;
        $data['type_id']=$type;
        $data['from_type']=$from_type;
        $data['route']=$route;
        $data['link_id']=$link_id;
        $res=self::where($data)->delete();
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取全部信息
     * @param $uid
     * @return mixed
     */
    public static function get_all_message($uid){
        $message_list_type=[1=>'message',5=>'message_new_send',7=>'notice'];
        //设置默认值
        $data['message']=$data['message_new_send']=$data['notice']=$data['message_new_send']=$data['message_new']=[];
        $map=[
            'to_uid'=>$uid,
            'type_id'=>['in',[1,5]],
        ];
        $giModel = db('message');
        $gi_table = $giModel->where($map)->order('create_time desc,send_time desc')->limit('999')->buildSql();//先排序
        $message = $giModel->table($gi_table .'as gi')
            ->field('gi.*')
            ->group('gi.type_id')
            ->select();
        //系统消息 type1  自定义消息 type7  新动态消息 type5
        foreach ($message as &$v){
            if($v['type_id']==1&&($v['route']=='reply'||$v['route']=='thread')){
                $v['thread_id']=ComThread::where('post_id',$v['link_id'])->value('id');
            }
            if($v['type_id']==7){
                $v['url']=link_select_url($v['url']);
            }
            $data[$message_list_type[$v['type_id']]]=$v;
        }
        unset($v);
        $data['message_new']=MessageNews::getUserMessageNew($uid);//营销消息type 6;
        $data['notice']=self::get_notice_new_message($uid);
        //已读消息内容
        $is_read_type_one=[1=>'message',5=>'message_new_send',6=>'message_new',7=>'notice'];
        $count_all=MessageRead::get_all_read_count($uid);

        foreach ($is_read_type_one as $v){
            if($data[$v]){
                $data[$v]['is_read']=isset($count_all[$v])?$count_all[$v]:0;
            }
        }
        unset($v);
        $is_read_type_two=[2=>'reply_count',3=>'support_count',4=>'follow_count'];
        foreach ($is_read_type_two as $v){
            $data[$v]=isset($count_all[$v])?$count_all[$v]:0;
        }
        unset($v);
        return $data;
    }


    /**
     * 获取消息列表
     * @param $uid
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     */
    public static function message_census($uid){
        $message=db('message_census')->where(['uid'=>$uid])->cache('message_census_new_uid_'.$uid,300)->find();
        $list_json=['message','message_new','message_new_send','notice'];
        if(!$message){
            $message=$message_data= self::get_all_message($uid);
            foreach ($list_json as $v){
                if(!empty($message[$v])){
                    $message_data[$v]=json_encode($message[$v]);
                    $message_data[$v.'_is_read']=$message[$v]['is_read'];
                    $message[$v]['send_time']=time_to_show( $message[$v]['send_time']);
                    $message[$v]['create_time']=time_to_show( $message[$v]['create_time']);
                }else{
                    unset($message_data[$v]);
                }
            }
            unset($list_json,$v);
            $message_data['uid']=$uid;
            db('message_census')->insert($message_data);
        }else{
            foreach ($list_json as $v){
                if(!empty($message[$v])){
                    $message[$v]=json_decode($message[$v],true);
                    //特殊情况处理
                    if($v=='message_new'&&$message[$v]['send_time']>time()){
                        $message['message_new']=MessageNews::getUserMessageNew($uid);
                    }
                    if($v=='notice'&&$message[$v]['send_time']>time()){
                        $message['notice']=self::get_notice_new_message($uid);
                    }
                    $message[$v]['send_time']=time_to_show($message[$v]['send_time']);
                    $message[$v]['create_time']=time_to_show( $message[$v]['create_time']);
                    $message[$v]['is_read']=$message[$v.'_is_read'];
                }
                unset($message[$v.'_is_read']);

                unset($v);

            }
            unset($list_json,$v);
        }
        return $message;
    }

    /**
     * 消息更新
     * @param $uid
     * @param $type
     * @param string $data
     * @return bool
     */
    public static function update_message_census($uid,$type,$data=''){
        $table=db('message_census');
        $now_uid=[];
        if(empty($uid)&&($type=='message_new'||$type=='notice')){
            $map['status']=1;
        }else{
            if(!is_array($uid)){
                $now_uid[]=$uid;
            } else{
                $now_uid=$uid;
            }
            $map['uid']=['in',$now_uid];
        }
        switch ($type){
            case 'follow_count':
                if($data!==''){
                    $table->where($map)->setInc('follow_count',1);
                }else{
                    $table->where($map)->update(['follow_count'=>0]);
                }
                break;
            case 'reply_count':
                if($data!==''){
                    $table->where($map)->setInc('reply_count',1);
                }else{
                    $table->where($map)->update(['reply_count'=>0]);
                }
                break;
            case 'support_count':
                if($data!==''){
                    $table->where($map)->setInc('support_count',1);
                }else{
                    $table->where($map)->update(['support_count'=>0]);
                }
                break;
            case 'message':
                if($data){
                    //系统消息 type1  自定义消息 type7  新动态消息 type5
                    if($data['type_id']==1&&($data['route']=='reply'||$data['route']=='thread')&&isset($data['link_id'])){
                        $data['thread_id']=ComThread::where('post_id',$data['link_id'])->value('id');
                    }
                    $table->where($map)->update(['message'=>json_encode($data),'message_is_read'=>1]);
                }else{
                    $table->where($map)->update(['message_is_read'=>0]);
                };
                break;
            case 'notice':
                if($data){
                    $data['url']=link_select_url($data['url']);
                    $data['send_time_copy']=$data['send_time'];
                    $table->where($map)->update(['notice'=>json_encode($data),'notice_is_read'=>1]);
                }else{
                    $table->where($map)->update(['notice_is_read'=>0]);
                };
                break;
            case 'message_new':
                if($data){
                    $data['send_time_copy']=$data['send_time'];
                    $table->where($map)->update(['message_new'=>json_encode($data),'message_new_is_read'=>1]);
                }else{
                    $table->where($map)->update(['message_new_is_read'=>0]);
                };
                break;
            case 'message_new_send':
                if($data){
                    $data['send_time_copy']=$data['send_time'];
                    $table->where($map)->update(['message_new_send'=>json_encode($data),'message_new_send_is_read'=>1]);
                }else{
                    $table->where($map)->update(['message_new_send_is_read'=>0]);
                };
                break;
            default:
                return false;
        }
        foreach ($now_uid as $v){
            Cache::rm('message_census_new_uid_'.$v);
        }
        unset($v);
        return true;
    }


    /**
     * 获取最新的一条数据
     * @param $uid
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function get_notice_new_message($uid){
        $notice= Cache::get('notice_message_uid_'.$uid);
        if(!$notice){
            $map=[
                'uid'=>$uid,
                'type'=>7,
            ];
            $message_id=MessageRead::where($map)->order('create_time desc')->column('message_id');
            $notice=self::where('id','in',$message_id)->where('status',1)->where('send_time','<',time())->order('create_time desc')->find();
            if($notice){
                $notice['url']=isset($notice['url'])?link_select_url($notice['url']):'';
                $notice['send_time']=$notice['create_time'];
                Cache::set('notice_message_uid_'.$uid,$notice,3600);
            }
        }
        return $notice;
    }
}