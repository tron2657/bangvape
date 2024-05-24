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
use think\Cache;

class ComForumAdminScoreLog extends BaseModel
{
    /**
     * qhy
     * 全部日志列表
     */
    public static function LogListAll($fids,$page,$row){
        $list=self::where('fid','in',$fids)->page($page,$row)->order('create_time desc')->select()->toArray();
        $rule=db('system_rule')->where('status',1)->field('name,flag')->select();
        $rule=array_column($rule,'name','flag');

        foreach($list as &$value){
            $value['thread']=ComThread::where('id',$value['tid'])->find()->toArray();
            $value['forum']=ComForum::where('id',$value['fid'])->find()->toArray();
            $value['nickname']=UserModel::where('uid',$value['uid'])->value('nickname');
            $value['do_nickname']=UserModel::where('uid',$value['do_uid'])->value('nickname');
            $value['create_time']=time_format($value['create_time']);

            //奖励人等级
            $level=db('com_forum_admin')->where('fid',$value['fid'])->where('uid',$value['uid'])->where('status',1)->order('level desc')->value('level');
            if($level==2){
                $admin_name='超级版主';
            }elseif($level==1){
                $admin_name='版主';
            }elseif(db('bind_group_uid')->where(['uid'=>$value['uid'],'g_id'=>2,'status'=>1,'end_time'=>[['eq',0],['gt',time()],'or']])->count()){
                $admin_name='管理员';
            }else{
                $admin_name='版主';
            }
            $value['admin_name']=$admin_name;

            //奖励积分
            $remark='';
            foreach ($rule as $key=>$v){
                if(isset($value[$key])&&$value[$key]>0){
                    $remark.=$v.$value[$key].'、';
                }
            }
            $remark= mb_substr($remark,0,mb_strlen($remark)-1,'UTF-8');
            $value['reword']=$remark;
        }
        unset($value);
        return $list;
    }

    /**
     * qhy
     * 版块日志列表
     */
    public static function LogList($fid,$page,$row){
        $list=self::where('fid',$fid)->page($page,$row)->order('create_time desc')->select()->toArray();
        $rule=db('system_rule')->where('status',1)->field('name,flag')->select();
        $rule=array_column($rule,'name','flag');
        foreach($list as &$value){
            $value['thread']=ComThread::where('id',$value['tid'])->find();
            $value['forum']=ComForum::where('id',$value['fid'])->find();
            $value['nickname']=UserModel::where('uid',$value['uid'])->value('nickname');
            $value['do_nickname']=UserModel::where('uid',$value['do_uid'])->value('nickname');
            $value['create_time']=time_format($value['create_time']);

            //奖励人等级
            $level=db('com_forum_admin')->where('fid',$value['fid'])->where('uid',$value['uid'])->where('status',1)->order('level desc')->value('level');
            if($level==2){
                $admin_name='超级版主';
            }elseif($level==1){
                $admin_name='版主';
            }elseif(db('bind_group_uid')->where(['uid'=>$value['uid'],'g_id'=>2,'status'=>1,'end_time'=>[['eq',0],['gt',time()],'or']])->count()){
                $admin_name='管理员';
            }else{
                $admin_name='版主';
            }
            $value['admin_name']=$admin_name;
            //奖励积分
            $remark='';
            foreach ($rule as $key=>$v){
                if(isset($value[$key])&&$value[$key]>0){
                    $remark.=$v.$value[$key].'、';
                }
            }
            $remark= mb_substr($remark,0,mb_strlen($remark)-1,'UTF-8');
            $value['reword']=$remark;
        }
        unset($value,$key,$v);
        return $list;
    }

}