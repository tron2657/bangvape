<?php

namespace app\admin\model\event;

use app\admin\model\com\ComForum;
use service\PHPExcelService;
use think\Cache;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;

/**
 * 广告位 model
 * Class ComForum
 * @package app\admin\model\com
 */
class Event extends ModelBasic
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
        $field=['zsxm','sfzh'];
        $field=db('certification_datum')->where(['field'=>['in',$field]])->field('field,name')->select();
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
        db('event_field')->insertAll($event_field);
        return $id;
    }

    /**
     * 编辑内容
     * @param $data
     * @return $this|int|string
     */
    public static function editEvent($data){
        if(isset($data['cate_id'])){
            $data['cate_pid']=EventCategory::where(['id'=>$data['cate_id']])->value('pid');
        }
        if($data['id']){
            Cache::rm('event_enroll_count_'.$data['id']);
            return self::where(['id'=>$data['id']])->update($data);
        }else{
            return self::addDate($data);
        }
    }

    /**
     * 获取列表
     * @param $map
     * @param $page
     * @param $limit
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_list($map,$page,$limit,$order='create_time desc',$is_excel=false){
        $data=self::where($map)->page($page,$limit)->order($order)->select()->toArray();
        $pid=EventCategory::check_value(['status'=>['egt',-1]]);
        $forum=ComForum::get_check_radio(['status'=>['gt',-1]]);
        $time=time();
        foreach ($data as &$v){
            $cate=$pid[$v['cate_id']]['label'];
            $user=db('user')->where(['uid'=>$v['uid']])->value('nickname');
            $is_recommend=$v['is_recommend']==1?'<span style="color: #9D1E15">推荐</span>':'';
            $type_name=$v['type']==1?'线下活动':'线上活动';

            $lable='未知板块';
            if(isset($forum[$v['forum_id']])) 
            {
                $lable=$forum[$v['forum_id']]['label'];
            }
            $v['event']=$v['title'].$is_recommend.'<br/>发起人:'.$user.'<br/>活动分类:'.$cate.'<br/>所属版块:'.$lable.'<br/>活动类型:'.$type_name;

           
            if($v['enroll_range']==0){
                $condition='全部用户';
            }elseif($v['enroll_range']==0){
                $condition='仅限版块粉丝';
            }else{
                $condition='指定用户组报名';
            }
            $v['condition']='限定人数:'.$v['enroll_count'].'<br/>'.$condition;

            if($v['price_type']==0){
                $price_type='免费';
            }elseif ($v['price_type']==1){
                $price_type='积分支付<br/>'.$v['price'].'积分';
            }else{
                $price_type='现金支付<br/>'.$v['value'].'元';
            }
            $v['pattern']=$price_type;

            if($v['status']!=-1){
                if($time<$v['start_time']){
                    $event='未开始';
                }elseif($time>$v['start_time']&&$time<$v['end_time']){
                    $event='进行中';
                }else{
                    $event='已结束';
                }
                $v['event_time']=date('Y-m-d H:i',$v['start_time']).'—<br/>'.date('Y-m-d H:i',$v['end_time']).'<br/>'.$event;

                if($time<$v['enroll_start_time']){
                    $enroll='未开始';
                }elseif($time>$v['enroll_start_time']&&$time<$v['enroll_end_time']){
                    $enroll='报名中';
                }else{
                    $enroll='已截止';
                }
                $v['enroll_time']=date('Y-m-d H:i',$v['enroll_start_time']).'—<br/>'.date('Y-m-d h:i',$v['enroll_end_time']).'<br/>'.$enroll;
                $v['is_cancel']= ($v['start_time']>time()||$v['status']!=1)?1:0;
                $v['is_set_field']= $v['start_time']>time()?1:0;
                //核销人数
                $v['check_user']=$v['is_need_check']==1?'开启':'不开启';
                $uid=db('event_check')->where(['event_id'=>$v['id'],'status'=>1])->column('uid');
                $nickname=db('user')->where(['uid'=>['in',$uid]])->column('nickname');
                $nickname=implode('<br/>',$nickname);
                $v['check_user'].='<br/>'.$nickname;

                if($is_excel)
                {
                    $v['check_user']= str_replace('<br/>',',',$nickname) ;
                }

                $v['record']='浏览:'.$v['view'].'<br/>报名:'.$v['enroll_reality_count'].'<br/>核销:'.$v['check_count'];
            }else{
                $v['del_time']='创建时间:'.date('Y-m-d H:i:s',$v['create_time']).'<br/>删除时间:'.date('Y-m-d H:i:s',$v['delete_time']);
            }

            if($is_excel)
            {                
                $v['event_time']=date('Y-m-d H:i',$v['start_time']).'—'.date('Y-m-d H:i',$v['end_time']);

            }
        }
        unset($v);
        $count=self::where($map)->count();
        return compact('data','count');
    }

}