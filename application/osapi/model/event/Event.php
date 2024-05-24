<?php

namespace app\osapi\model\event;

use app\admin\model\com\ComForum;
use app\admin\model\group\Group;
use app\admin\model\system\SystemConfig;
use app\admin\model\user\MemberCouponPlan;
use app\core\behavior\EventBehavior;
use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\MemberShipService;
use service\UtilService;
use think\Exception;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class Event extends ModelBasic
{


    /**
     * 完成自动报名跟核销
     */
    public static function autoFinishEnrollAndCheck($uid,$event_id)
    {
    
        //自动完成报名的程序
        $id=$event_id;
        //初始判断上限
        $event=db('event')->where(['id'=>$id])->field('id,status,enroll_count,enroll_reality_count,forum_id,price,price_type,enroll_range,enroll_start_time,enroll_end_time')->cache('event_enroll_count_'.$id)->find();
        if($event['status']!=1){
            throw new Exception('该活动已经删除或者已经取消，无法报名');
        }
        if($event['enroll_end_time']<time()){
            throw new Exception('活动报名已结束');
        }
        if($event['enroll_start_time']>time()){
            throw new Exception('活动还未开始报名');
        }
 
        $event['enroll_reality_count']+=1;
        
        if($event['enroll_reality_count']>$event['enroll_count']&&$event['enroll_count']!=0){
            throw new Exception('很遗憾,报名人数已满。');
        } 

        //不能重复报名
        $enroll_count=EventEnroller::where(['uid'=>$uid])->where('status','in','1,2')->count();
        if($enroll_count>=1){
             throw new Exception('您已报名过活动，不能再报名了');
        }

        // $enroll=EventEnroller::where(['uid'=>$uid,'event_id'=>$id])->find();
        // if(!$enroll){
        //     $event['enroll_reality_count']-=1; 
        //     throw new Exception('未产生报名信息');
        // }
        // if($enroll['status']==1){
        //     $event['enroll_reality_count']-=1;
        //     // Cache::set('event_enroll_count_'.$id,$event);
        //     // $this->apiError(['status'=>0,'info'=>'活动已经报名成功']);
        // }


        $enroll= EventEnroller::where(['uid'=>$uid,'event_id'=>$event_id])->find();   
        if(!$enroll)
        {            
            $enroller_id=0;
            $code=EventEnroller::rand_code();
            $enroller_id=EventEnroller::insertGetId([
                'uid'=>$uid,
                'event_id'=>$event_id,
                'status'=>1,
                'check_uid'=>$uid,
                'check_time'=>time(),
                'create_time'=>time(),
                'code'=>$code
            ]);

            //赠送一张免费的优惠券  
            // MemberShipService::get_grant_frist_coupon($uid,['st_time'=>time(),'cyle_days'=>90])
            if($enroller_id>0)
            {
                $enroll=EventEnroller::get($enroller_id);
                EventBehavior::eventSureCheckAfter($enroll,$code);
            }
        }              
        // MemberCouponPlan::grant_first_coupon();
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
        $data['start_time']=date('Y-m-d',$data['start_time']);
        $data['end_time']=date('Y-m-d',$data['end_time']);
        $people=db('event_enroller')->where(['event_id'=>$id,'status'=>1])->column('uid');
        $data['enroll_people']=db('user')->where(['uid'=>['in',$people]])->field('avatar,uid,nickname')->select();
        $data['user']=db('user')->where(['uid'=>['in',$data['uid']]])->field('avatar,uid,nickname')->select();
        $uid=get_uid();
        $data['is_collect']=db('event_collect')->where(['uid'=>$uid,'eid'=>$id,'status'=>1])->count();
        //是否已经报名了
        $data['is_enroll']=db('event_enroller')->where(['uid'=>$uid,'event_id'=>$id,'status'=>1])->count();
        //是否已截止报名
        $data['is_end_enroll']= ($data['enroll_end_time']<time()||($data['enroll_count']<=$data['enroll_reality_count']&&$data['enroll_count']!=0))?1:0;
        //是否开始报名
        $data['is_start_enroll']= $data['enroll_start_time']<time()?1:0;
        //判断未报名的时候报名权限

        $data['enroll_power']=self::get_enroll_power($data,$uid);
        //图片全链接
        $url='http://'.$_SERVER['SERVER_NAME'];
        if(!preg_match('/^http(s)?:\\/\\/.+/',$data['cover'])){
            $data['cover']=$url.$data['cover'];
        }
//         $arr=\app\core\util\Position::bd09ToGcj02($item['store_branch']['lat'],$item['store_branch']['lng']);
        $convertFileds=[['detailed_lat','detailed_lng'],['lat','lng']];
        foreach($convertFileds as $keys)
        {
            $arr=\app\core\util\Position::bd09ToGcj02($data[$keys[0]],$data[$keys[1]]);
            $data[$keys[0]]=$arr[0];
            $data[$keys[1]]=$arr[1];
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

    /**
     * 获取列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @param boolean $my
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_event_list($map,$page,$limit,$order='create_time desc',$my=false,$postion=null){

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

            if($postion!=null)
            {
               $arr= \app\core\util\Position::bd09ToGcj02($v['lat'],$v['lng']);
               $lat=$arr[0];
               $lng=$arr[1];
               $v['distance']=getDistance($postion['lng'],$postion['lat'],$lng,$lat);
            }
    
        }
        unset($v);
        $count=self::where($map)->count();
        return ['data'=>$data,'count'=>$count];
    }


}