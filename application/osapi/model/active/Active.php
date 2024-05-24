<?php

namespace app\osapi\model\active;

use app\admin\model\active\Active as ActiveActive;
use app\admin\model\active\ActiveCheck;
use app\admin\model\com\ComForum;
use app\admin\model\group\Group;
use app\admin\model\system\SystemConfig;
use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use Exception;
use service\UtilService;
use think\Exception as ThinkException;

class Active extends ModelBasic
{

    /**
     * 获取最后一个发布并组局成功的系统
     *
     * @return void
     */
    public static function getLastSuccessData($uid)
    {
        $model=self::where('uid',$uid)->where('status',1)->where('is_finish',1)->order('end_time','desc')->find();
        return $model;
    }


    /**
     * 是否有正在进行中的活动
     *
     * @param [type] $uid
     * @return boolean
     */
    public static function hasRuningActive($uid)
    {
        $res=self::where('uid',$uid)->where('status',1)->where('is_finish',0)->count()>0;
        return $res;
    }

    public static function cancel_event($id,$uid)
    {
        // 启动事务
        Db::startTrans();
        try{

            $event=self::where('id',$id)->find();
            if($event['is_finish']==1)
            {
                throw new \think\Exception('当前活动已组局成功，无法取消!');
            }
            Db::name('active')->where(['id'=>$id,'is_finish'=>0,'uid'=>$uid])->update(['status'=>0]);

            if($event['status']==1)
            {
                Db::name('active_store_branch')->where(['id'=>$event['store_branch_id']])->setInc('stock'); //更新库存信息
            }     
            // 提交事务
            return   Db::commit();    
        } catch (\think\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw $e;
        }     
    }

    public static function get_publish_end_time($time,$config=null)
    {
        if($config==null)        
        {
            $config=SystemConfig::getMore('active_end_time_seconds');//截止时间默认增加秒数
            if(!isset($config['active_end_time_seconds']))
            {
                $config['active_end_time_seconds']='86400';
            }           
                    
        }

        return bcadd((int)$config['active_end_time_seconds'], $time, 0);
    }

    public static function publish($input)
    {      
        $config=SystemConfig::getMore('active_end_time_seconds,active_enroll_count,active_enroll_enable_limit');//截止时间默认增加秒数
        if(!isset($config['active_end_time_seconds']))
        {
            $config['active_end_time_seconds']='86400';
        }
        if(!isset($config['active_enroll_count']))//默认约酒参与人数
        {
            $config['active_enroll_count']='4';
        }
        if(!isset($config['active_enroll_enable_limit']))//默认约酒参与人数
        {
            $config['active_enroll_enable_limit']='0';
        }
        //active_enroll_enable_limit
    
        $uid=$input['uid'];
        $user=db('user')->where('uid',$uid)->find();
        $store_branch=db('active_store_branch')->where('is_close',0)->where('id',$input['store_branch_id'])->find();
        if($store_branch==null) throw new ThinkException('当前门店不存在');
        if($store_branch['stock']<=0)
        {
            throw new ThinkException('当前门店库存不够，无法发起约酒活动');
        }


        if($config['active_enroll_enable_limit']=='1')
        {
            if(Active::hasRuningActive($uid))
            {
                $err_msg='根据活动规则,不能同时发布多次活动!';
                throw new ThinkException($err_msg);
            }

            //找出上次活动已经结束，并且是成功组局的数据
            $last_data=Active::getLastSuccessData($uid);
            if($last_data!=null)
            {
                //计算现在距离上次的时间的天数
                if(time()<=$last_data['next_start_time'])
                {
                    $date_show=date('Y-m-d H:i:s',$last_data['next_start_time']);
                    $err_msg='根据活动规则,'.$date_show.'后才能继续发起约酒活动!';
                    throw new ThinkException($err_msg);
                }
            }
        }
       

        $time=time();
        $input['enroll_start_time']=$time;
        $input['enroll_end_time']=bcadd((int)$config['active_end_time_seconds'], $time, 0); //活动截止时间默认24小时内
        $active=new Active([
            'user_name'=>$input['user_name'],
            'user_phone'=>$input['user_phone'],
            'user_sex'=>$input['user_sex'],
            'store_branch_id'=>$input['store_branch_id'],
            'is_need_check'=>1,
            'uid'=>$user['uid'],
            'cate_id'=>0,
            'cate_pid'=>0,
            'enroll_count'=>$config['active_enroll_count'],
            'title'=>$store_branch['name'],
            'address'=>$store_branch['name'],
            'detailed_address'=>$store_branch['address'],
            'city'=>$store_branch['city'],
            'province'=>$store_branch['province'],
            'district'=>$store_branch['district'],
            'lat'=>$store_branch['province'],
            'create_time'=>time(),
            'lng'=>$store_branch['district'],
            'start_time'=>$input['enroll_start_time'],
            'end_time'=>$input['enroll_end_time'],
            'enroll_start_time'=>$input['enroll_start_time'],
            'enroll_end_time'=>$input['enroll_end_time'],
            'invalid_time'=>$input['enroll_end_time']
        ]);

        $active->save();
     
        if($active->id>0)
        { 
            
            db('active_store_branch')->where(['id'=>$active['store_branch_id']])->where('stock','>',0)->setInc('stock',-1); 
            ActiveEnroller::add_enroll($user['uid'],$active->id,$active);
            //添加核销人员(默认是选择的门店的人作为核销人员)            
            ActiveCheck::addCheck([
                'event_id'=>$active->id,
                'uid'=>$store_branch['uid']
            ]);         
        }
       
       
        return $active->id;
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
        $item=[];
        self::where(['id'=>$id])->setInc('view',1);
        $item['enroll_deadline']=$data['enroll_end_time']>time()?ceil(($data['enroll_end_time']-time())/(24*3600)):0;
        $item['id']=$data['id'];
        $item['enroll_end_time']=date('Y-m-d',$data['enroll_end_time']);
        $item['enroll_start_time']=date('Y-m-d',$data['enroll_start_time']);
        $item['end_time']=date('Y-m-d',$data['end_time']);
        $people=db('active_enroller')->where(['event_id'=>$id,'status'=>1])->column('uid');
        $item['enroll_people']=db('user')->where(['uid'=>['in',$people]])->field('avatar,uid,nickname')->select();
        $item['user']=db('user')->where(['uid'=>['in',$data['uid']]])->field('avatar,uid,nickname')->select();
        $uid=get_uid();
        $item['is_collect']=db('event_collect')->where(['uid'=>$uid,'eid'=>$id,'status'=>1])->count();
        //是否已经报名了
        $item['is_enroll']=db('event_enroller')->where(['uid'=>$uid,'event_id'=>$id,'status'=>1])->count();
        //是否已截止报名
        $item['is_end_enroll']= ($data['enroll_end_time']<time()||($data['enroll_count']<=$data['enroll_reality_count']&&$data['enroll_count']!=0))?1:0;
        //是否开始报名
        $item['is_start_enroll']= $data['enroll_start_time']<time()?1:0;
        $item['store_branch']=db('active_store_branch')->where('id',$data['store_branch_id'])->find();
     
        if($item['store_branch'])
        {
            $arr=\app\core\util\Position::bd09ToGcj02($item['store_branch']['lat'],$item['store_branch']['lng']);
            $item['store_branch']['lat']=$arr[0];
            $item['store_branch']['lng']=$arr[1];
        }
        $item['status']=$data['status'];
        $item['status_value']=self::get_status_text($data);
        $item['enroll_count']=$data['enroll_count'];
        $item['content']='';//活动详情
        $item['is_finish']=$data['is_finish'];
        $item['create_time']=date('Y-m-d',$data['create_time']);
        $item['allow_apply']=$data['is_finish']==0 && $data['status']==1;
 
        return $item;
    }

 

    public static function get_status_text($v)
    {
        $status_value='';
        if($v['status']==0){
            $status_value='邀约已取消';
        }else if($v['status']==1 )
        {
            if($v['is_finish']==1)
            {   
                $status_value='邀约已成功';
            }else
            {
                $status_value='邀约进行中';
            }             
        }
        return $status_value;  
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
 
        $result=[];
        foreach ($data as $v){
            
            $item=[];

            $item['id']=$v['id'];
            $item['store_branch_stock']=0;
            $item['store_branch_name']=0;
            $item['store_branch_img']='';
            if($v['store_branch_id']>0)
            {
        
                $store_branch=db::name('active_store_branch')->where('id',$v['store_branch_id'])->find();
                $item['store_branch_stock']=$store_branch['stock'];
                $item['store_branch_name']=$store_branch['name'];
                $item['store_branch_cover']=$store_branch['cover'];
                // if($item['store_branch_stock']>0)
                // {
                //     //获取门店已经举行过的活动次数。
                //     $branch_active_count=db('active_store_branch_id')->where('event_id',$item['id'])->count();
                //     $item['store_branch_stock']=$item['store_branch_stock']-$branch_active_count;
                // }
                https://img95.699pic.com/photo/50062/1576.jpg_wh300.jpg
            }
            
            $item['is_new']=($v['create_time']+3*24*3600>$time)?1:0;
            $item['is_end']=$v['end_time']<$time?1:0;
            $item['is_start']=$v['start_time']<$time&&$time<$v['end_time']?1:0;
            $item['is_enroll_start']=$v['enroll_start_time']<$time&&$time<$v['enroll_end_time']?1:0;
            //是否已截止报名
            $item['is_end_enroll']= ($v['enroll_end_time']<time()||($v['enroll_count']<=$v['enroll_reality_count']&&$v['enroll_count']!=0))?1:0;
            $item['is_finish']=$v['is_finish'];
            $item['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            $item['start_time']=date('Y-m-d H:i:s',$v['start_time']);
            $item['end_time']=date('Y.m.d~H:i:s',$v['end_time']);
            $item['enroll_start_time']=date('Y.m.d',$v['enroll_start_time']);
            $item['enroll_end_time']=date('Y.m.d',$v['enroll_end_time']);
            $item['enroll_count']=$v['enroll_count'];
            $item['detailed_address']=$v['province'].$v['city'].$v['district'].$v['detailed_address'];//报名地址
            $item['province']=$v['province'];//省
            $item['city']=$v['city'];//市
            $item['district']=$v['district'];//区
            $item['nickname']=db('user')->where('uid',$v['uid'])->value('nickname');

            $item['draw_over_time']=0;
            if($item['is_finish']==1)
            {
                $item['draw_over_time']=db('active_enroller')->where('event_id',$item['id'])->where('uid',$v['uid'])->value('over_time');
                $item['draw_over_time']=date('Y.m.d~H:i:s',$item['draw_over_time']);
            }
            //图片全链接
            if(!preg_match('/^http(s)?:\\/\\/.+/',$v['cover'])){
                $item['cover']=$url.$v['cover'];
            }
            

            $item['status_value']=self::get_status_text($v);
            $item['status']=$v['status'];
            $result[]=$item;
        }
        unset($v);
        $count=self::where($map)->count();
        return ['data'=>$result,'count'=>$count];
    }


}