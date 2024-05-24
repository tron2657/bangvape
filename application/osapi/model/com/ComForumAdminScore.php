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
use app\osapi\model\com\ComForumAdminScoreLog;
use app\osapi\model\user\UserModel;
use think\Cache;

class ComForumAdminScore extends BaseModel
{

    /**
     * 获取版主剩余积分奖励额度
     * @author qhy
     */
    public static function getHaveScore($fid,$uid){
        $is_admin=ComForum::_ForumAdmin($fid,$uid);
        if($is_admin['admin_three']==1){
            $limit=ComForumAdminScore::getSetLimit(3);
            foreach($limit as &$value){
                $use_score=ComForumAdminScoreLog::where('do_uid',$uid)->whereTime('create_time', 'month')->where('fid',$fid)->sum($value['flag']);
                $value['have_num']=$value['num']-$use_score;
            }
            unset($value);
            return $limit;
        }elseif($is_admin['admin_two']==1){
            $limit=self::getSetLimit(2);
            foreach($limit as &$value){
                $use_score=ComForumAdminScoreLog::where('do_uid',$uid)->whereTime('create_time', 'month')->where('fid',$fid)->sum($value['flag']);
                $value['have_num']=$value['num']-$use_score;
            }
            unset($value);
            return $limit;
        }elseif($is_admin['admin_one']==1){
            $limit=self::getSetLimit(1);
            foreach($limit as &$value){
                $use_score=ComForumAdminScoreLog::where('do_uid',$uid)->whereTime('create_time', 'month')->where('fid',$fid)->sum($value['flag']);
                $value['have_num']=$value['num']-$use_score;
            }
            unset($value);
            return $limit;
        }else{
            return false;
        }
    }

    /**
     * 获取版主奖励额度
     * @author qhy
     */
    public static function getSetLimit($type)
    {
        $info=self::where('status',1)->where('type',$type)->where('is_del',0)->value('info');
        if($info){
            $info=json_decode($info,true);
        }else{
            $info=[];
        }
        return $info;
    }

}