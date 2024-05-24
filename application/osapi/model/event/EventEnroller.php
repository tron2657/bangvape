<?php

namespace app\osapi\model\event;

use app\admin\model\system\SystemConfig;
use app\osapi\model\BaseModel;
use app\osapi\model\common\Support;
use think\Cache;


/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class EventEnroller extends BaseModel
{
    public static function add_enroll($uid,$event_id){
        $data['uid']=$uid;
        $data['event_id']=$event_id;
        $data['status']=0;
        if(self::where($data)->count()){
            return true;
        }
        do{
            $code = create_rand(7);
        }while(self::where(['code'=>$code])->count());
        $data['code']=$code;
        $data['create_time']=time();
        return self::insertGetId($data);
    }

    public static function rand_code()
    {
        do{
            $code = create_rand(7);
        }while(self::where(['code'=>$code])->count());
        return $code;
    }

    /**
     * 报名
     * @param $id
     * @param $uid
     * @return array
     */
    public static function enroll_info($id,$uid){
        $enroll=self::where(['uid'=>$uid,'event_id'=>$id])->find();
        if(!$enroll){
            return ['status'=>0,'info'=>'未产生报名信息'];
        }
        if($enroll['status']==1){
            return ['status'=>0,'info'=>'活动已经报名成功'];
        }
        $event=db('event')->where(['id'=>$id])->field('id,status,enroll_count,enroll_reality_count,forum_id,price,price_type,enroll_range')->cache('event_enroll_count_'.$id)->find();

        $enroll_power=Event::get_enroll_power($event,$uid);
        if(!$enroll_power){
            return ['status'=>0,'info'=>'没有权限报名'];
        }
        $result= ['status'=>0,'info'=>'报名失败'];
        $count=self::where(['event_id'=>$id,'status'=>1])->count();
        switch ($event['price_type']){
            case 0:
                $res=self::where(['id'=>$enroll['id']])->update(['status'=>1]);
                if($res){
                    db('event')->where(['id'=>$id])->update(['enroll_reality_count'=>$count+1]);
                    $result= ['status'=>1,'info'=>'报名成功'];
                }
                break;
            case 1:
                // 积分设置
                $flag=SystemConfig::getValue('event_type_pay');
                $score=db('user')->where(['uid'=>$uid])->value($flag);
                if($score>$event['price']){
                    self::startTrans();
                    $res=self::where(['id'=>$enroll['id']])->update(['status'=>1]);
                    $res2=db('user')->where(['uid'=>$uid])->setDec($flag,$event['price']);
                    if($res&&$res2){
                        db('event')->where(['id'=>$id])->update(['enroll_reality_count'=>$count+1]);
                        Support::jiafenlog($uid,'报名活动',[$flag=>-$event['price']],-1,'活动');
                        $result= ['status'=>1,'info'=>'报名成功'];
                        self::commitTrans();
                    }else{
                        self::rollbackTrans();
                    }
                }else{
                    $result= ['status'=>0,'info'=>'积分不足'];
                }

                break;
            case 2:
                //todo 余额付款
                $result= ['status'=>2,'info'=>'需要现金支付'];
                ;break;
            default:  $result= ['status'=>0,'info'=>'不存在改付费信息'];
        }
        //如果为否，则删除一位报名信息
        if($result['status']==0){
            $event['enroll_reality_count']-=1;
            Cache::set('event_enroll_count_'.$id,$event);
        }
        return $result;
    }

    /**
     * 获取提交字段
     * @param $event_id
     * @param $uid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_enroll_info($event_id,$uid){

        $info=db('event_enroller_info')->where(['event_id'=>$event_id,'status'=>1,'uid'=>$uid])->select();
        $enroll=[];
        foreach ($info as $key=>$v){
            $enroll[$v['field']]['content']=$v['content'];
            $enroll[$v['field']]['create_time']=date('Y-m-d H:i:s',$v['create_time']);
        }
        unset($key,$v);
        $field=array_column($info,'field');
        $datum=db('certification_datum')->where(['field'=>['in',$field]])->field('id,field,name,form_type,input_tips,setting')->select();
        foreach ($datum as $k=>&$vo){
            $vo['content']=$enroll[$vo['field']]['content'];
            $vo['create_time']=$enroll[$vo['field']]['create_time'];
        }
        unset($k,$vo);
        return $datum;
    }

    /**
     * 获取核销内容
     * @param $enroll
     * @return array
     */
    public static function get_enroll_info_by_code($enroll){
        $info=self::get_enroll_info($enroll['event_id'],$enroll['uid']);
        $user=db('user')->where(['uid'=>$enroll['uid']])->field('nickname,avatar,uid')->find();
        $event=Event::where(['id'=>$enroll['event_id']])->field('id,title,start_time,end_time,address,detailed_address')->find();
        $event['start_time']=date('Y-m-d H:i:s',$event['start_time']);
        $event['end_time']=date('Y-m-d H:i:s',$event['end_time']);
        return [$info,$user,$event];
    }

    /**
     * 获取核销列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @return array
     */
    public static function get_check_list($map,$page,$limit,$order='check_time desc'){
        $list=self::where($map)->page($page,$limit)->order($order)->select();
        foreach ($list as &$v){
            $v['user']=db('user')->where(['uid'=>$v['uid']])->value('nickname');
            $v['check_time']=date('Y-m-d H:i:s',$v['check_time']);
            $v['check_user']=db('user')->where(['uid'=>$v['check_uid']])->value('nickname');
        }
        $count=self::where($map)->count();
        return ['data'=>$list,'count'=>$count];
    }
}