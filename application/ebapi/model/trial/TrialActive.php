<?php

namespace app\ebapi\model\trial;

use traits\ModelTrait;
use basic\ModelBasic;
use app\admin\model\system\SystemConfig;
class TrialActive extends ModelBasic
{
    use ModelTrait;

    /*
     * 获取申请人数
 
     * @return array
     * */
    public static function getEnrollerCount($event_id)
    {
        return self::valiWhere()->where(['event_id'=>$event_id])->count();
    }

     /*
     * 添加活动申请
     * @return array
     * */
    public static function addEnroller(){

    }

     /**
     * 获取列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @param boolean $my
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_event_list($map,$page,$limit,$order='create_time desc',$my=false){

        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        $time=time();
        $url='http://'.$_SERVER['SERVER_NAME'];
        $flag=SystemConfig::getValue('event_type_pay');
        $score_type=db('system_rule')->where(['flag'=>$flag])->value('name');
        foreach ($data as &$v){
            $v['score_type']=$score_type;
            $v['is_new']=($v['create_time']+3*24*3600>$time)?1:0;
            $v['is_end']=$v['end_time']<$time?1:0;
            $v['is_start']=$v['start_time']<$time&&$time<$v['end_time']?1:0;
            $v['is_enroll_start']=$v['enroll_start_time']<$time&&$time<$v['enroll_end_time']?1:0;
            //是否已截止报名
            $v['is_end_enroll']= ($v['enroll_end_time']<time()||($v['enroll_count']<=$v['enroll_reality_count']&&$v['enroll_count']!=0))?1:0;

            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            $v['start_time']=date('Y-m-d H:i:s',$v['start_time']);
            $v['end_time']=date('Y-m-d H:i:s',$v['end_time']);
            $v['enroll_start_time']=date('Y-m-d H:i:s',$v['enroll_start_time']);
            $v['enroll_end_time']=date('Y-m-d H:i:s',$v['enroll_end_time']);

            //图片全链接
            if(!preg_match('/^http(s)?:\\/\\/.+/',$v['cover'])){
                $v['cover']=$url.$v['cover'];
            }
            if($v['status']==0){
                $v['status_value']='活动已取消';
            }elseif($v['is_end']==1){
                $v['status_value']='活动已结束';
            }elseif ($v['is_end_enroll']==1){
                $v['status_value']='报名已截止';
            }elseif($v['is_enroll_start']==1){
                $v['status_value']='报名进行中';
            }else{
                $v['status_value']='报名未开始';
            }
        }
        unset($v);
        $count=self::where($map)->count();
        return ['data'=>$data,'count'=>$count];
    }

    /**
     * 获取活动详情
     * @param $id
     * @return null|static
     */
    public static function getEvent($id){
        $data=self::get($id);
        if(!$data){
            return [];
        }
        self::where(['id'=>$id])->setInc('view',1);
        $data['enroll_deadline']=$data['enroll_end_time']>time()?ceil(($data['enroll_end_time']-time())/(24*3600)):0;
        if($data['enroll_range']==0){
            $condition='全部用户';
        }elseif($data['enroll_range']==1){
            $condition='仅限版块粉丝';
        }else{
            $condition='指定用户组报名';
        }
        if($data['enroll_count']==0){
            $data['enroll_condition']=[
                $condition
            ];
        }else{
            $data['enroll_condition']=[
                '限制'.$data['enroll_count'].'人',
                $condition
            ];
        }
        // $data['start_time']=date('Y-m-d',$data['start_time']);
        $data['start_time']=$data['start_time'];
        $data['end_time']=$data['end_time'];

        $peopleQuery=db('trial_enroller')->where(['event_id'=>$id,'status'=>1]);
        $data['enroll_pepole_count']=$peopleQuery->count();
        // $people=$peopleQuery->column('uid');
        // $data['enroll_people']=db('user')->where(['uid'=>['in',$people]])->field('avatar,uid,nickname')->select();
        // $data['user']=db('user')->where(['uid'=>['in',$data['uid']]])->field('avatar,uid,nickname')->select();
        $uid=get_uid();
    
        //是否已经报名了        
        $data['enroll_status']=0;
        $data['is_enroll']=0;         
        $tairlEnroll=db('trial_enroller')->where(['uid'=>$uid,'event_id'=>$id])->where('status','>=',1)->find();
        if($tairlEnroll!=null)
        {   
            $data['is_enroll']=1;            
            $data['enroll_status']=$tairlEnroll['status'];
            $data['enroll_draw_status']=$tairlEnroll['draw_status'];
        }
    
        //是否已截止报名
        $data['is_end_enroll']= ($data['enroll_end_time']<time()||($data['enroll_count']<=$data['enroll_reality_count']&&$data['enroll_count']!=0))?1:0;

        

        // 是否禁止领取
        $data['is_stop_draw']=false;
        $data['is_stop_draw_reson']='';
        $data['draw_overdue_days']=0;
        if($data['draw_overdue_time']>0)
        { 
    
            $days=\app\admin\model\trial\TrialActive::calculation_days($data['draw_overdue_time']);
            if($days>0)
            {
                $data['draw_overdue_days']=$days;               
            }else
            {
                $data['is_stop_draw']=true;
                $data['is_stop_draw_reson']='已过领取结束时间('.date('Y-m-d H:i',$data['draw_overdue_time']).')';
            }

            $data['draw_overdue_time']=date('Y-m-d',$data['draw_overdue_time']);
        }

        //是否强制结束
        if($data['is_end']==1)
        {
            $data['is_end']=true;
        }else
        {
            $data['is_end']=$data['end_time']<time();
        }

        $data['is_overtime_end']=$data['end_time']<time();
        
   
        
        // $data['is_end']=1;
        //是否开始报名
        $data['is_start_enroll']= $data['enroll_start_time']<time()?1:0;
        //判断未报名的时候报名权限
        $data['enroll_power']=self::get_enroll_power($data,$uid);
        //图片全链接
        $url='http://'.$_SERVER['SERVER_NAME'];
        if(!preg_match('/^http(s)?:\\/\\/.+/',$data['cover'])){
            $data['cover']=$url.$data['cover'];
        }
        $flag=SystemConfig::getValue('event_type_pay');
        $data['score_type']=db('system_rule')->where(['flag'=>$flag])->value('name');

        return $data;
    }

       /**
     * 判断报名权限
     * @param $event
     * @param $uid
     * @return int
     */
    public static function get_enroll_power($event,$uid){
        $enroll_power=0;
        switch ($event['enroll_range']){
            case 0:
                $enroll_power=1;
                break;
            case 1:
                $is_fan=db('com_forum_member')->where(['status'=>1,'fid'=>$event['forum_id'],'uid'=>$uid])->count();
                $enroll_power=$is_fan>0?1:0;
                break;
            case 2:
                //指定用户组
                $group=db('event_bind_group')->where(['event_id'=>$event['id'],'status'=>1])->value('group');
                $group=explode(',',$group);
                $user_g_id=Group::get_group_by_uid($uid);
                if(array_intersect($group,$user_g_id)){
                    $enroll_power=1;
                }
                break;
        }
        return  $enroll_power;
    }

    
}
