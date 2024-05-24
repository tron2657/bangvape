<?php

namespace app\admin\model\trial;

use app\admin\model\com\ComForum;
use service\PHPExcelService;
use think\Cache;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 活动 model
 * Class ComForum
 * @package app\admin\model\trial
 */
class TrialActive extends ModelBasic
{

    public static function getEvent($id){
        $data=self::get($id);
        if(empty($data)){
            $field=self::getTableFields();
            foreach ($field as $v){
                $data[$v]='';
            }
            unset($v);
        }
        return $data;
    }

    /**
     * 新增内容
     * @param $data
     * @return int|string
     */
    public static function addDate($data){

        $data['status']=1;
        $data['create_time']=time();
        $id=self::insertGetId($data);;
        //创建默认的字段
        $where['use_range'] = array('like','%4%');
 
        $field=db('certification_datum')->where($where)->field('field,name')->select();
        $event_field=[];
        $value['event_id']=$id;
        $value['create_time']=time();
        $value['status']=1;
        foreach ($field as $v){
            $value['field']=$v['field'];
            $value['field_name']=$v['name'];
            $value['is_need']=1;
            $event_field[]=$value;
        }
        unset($v);
        db('trial_field')->insertAll($event_field);
        return $id;
    }

    /**
     * 编辑内容
     * @param $data
     * @return $this|int|string
     */
    public static function editEvent($data){
        
        if($data['id']){
            Cache::rm('trail_active_enroll_count_'.$data['id']);
            return self::where(['id'=>$data['id']])->update($data);
        }else{
            return self::addDate($data);
        }
    }

    /**
     * 计算过期天数
     *
     * @return void
     */
    public static function calculation_days($et_time)
    {
        $st_time=time();
         
        $timespan=$et_time-$st_time;
         //计算天数
        $days=round($timespan/(86400),2);
 
        return $days;
    }

    /**
     * 获取列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_list($map,$page,$limit,$order='create_time desc'){
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
  
        $forum=ComForum::get_check_radio(['status'=>['gt',-1]]);
        $time=time();
        foreach ($data as &$v){
           
            $user=db('user')->where(['uid'=>$v['uid']])->value('nickname');
            $is_recommend=$v['is_recommend']==1?'<span style="color: #9D1E15">推荐</span>':'';
            $type_name=$v['type']==1?'线下活动':'线上活动';
            $v['event']=$v['title'].$is_recommend.'<br/>所属版块:'.$forum[$v['forum_id']]['label'];

            if($v['enroll_range']==0){
                $condition='全部用户';
            }elseif($v['enroll_range']==0){
                $condition='仅限版块粉丝';
            }else{
                $condition='指定用户组报名';
            }
            $v['condition']='限定人数:'.$v['enroll_count'].'<br/>'.$condition;

            // if($v['price_type']==0){
            //     $price_type='免费';
            // }elseif ($v['price_type']==1){
            //     $price_type='积分支付<br/>'.$v['price'].'积分';
            // }else{
            //     $price_type='现金支付<br/>'.$v['value'].'元';
            // }
            // $v['pattern']=$price_type;

            if($v['status']!=-1){
                if($time<$v['start_time']){
                    $event='未开始';
                }elseif($time>$v['start_time']&&$time<$v['end_time']){
                    $event='进行中';
                }else{
                    $event='已结束';
                }
                $v['event_time']=date('Y-m-d H:i',$v['start_time']).'至'.date('Y-m-d H:i',$v['end_time']).'<br/>'.$event;

                if($time<$v['enroll_start_time']){
                    $enroll='未开始';
                }elseif($time>$v['enroll_start_time']&&$time<$v['enroll_end_time']){
                    $enroll='报名中';
                }else{
                    $enroll='已截止';
                }
                $v['enroll_time']=date('Y-m-d H:i',$v['enroll_start_time']).'—'.date('Y-m-d h:i',$v['enroll_end_time']).'<br/>'.$enroll;
                $v['is_cancel']= ($v['start_time']>time()||$v['status']!=1)?1:0;
                $v['is_set_field']= $v['start_time']>time()?1:0;
                // 领取截止时间
                if($v['draw_overdue_time']>0)
                {
                    $days=self::calculation_days($v['draw_overdue_time']);
                    if($days>0)
                    {                      
                       $v['draw_overdue_time']=date('Y-m-d',$v['draw_overdue_time']).' 还剩'.$days.'天';
                    }else{
                        $v['draw_overdue_time']=date('Y-m-d',$v['draw_overdue_time']).' 已过期';
                    }                  
                }else{
                    $v['draw_overdue_time']='无限期';
                }
             
                //核销人数
                $v['check_user']=$v['is_need_check']==1?'开启':'不开启';
                $uid=db('event_check')->where(['event_id'=>$v['id'],'status'=>1])->column('uid');
                $nickname=db('user')->where(['uid'=>['in',$uid]])->column('nickname');
                $nickname=implode('<br/>',$nickname);
                $v['check_user'].='<br/>'.$nickname;

                $v['record']='浏览:'.$v['view'].'<br/>报名:'.$v['enroll_reality_count'];
            }else{
                $v['del_time']='创建时间:'.date('Y-m-d H:i:s',$v['create_time']).'<br/>删除时间:'.date('Y-m-d H:i:s',$v['delete_time']);
            }

        }
        unset($v);
        $count=self::where($map)->count();
        return compact('data','count');
    }

}