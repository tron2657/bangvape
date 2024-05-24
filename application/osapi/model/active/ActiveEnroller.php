<?php

namespace app\osapi\model\active;

use app\admin\model\system\SystemConfig;
use app\osapi\model\BaseModel;
use app\osapi\model\common\Support;
use think\Cache;

use  app\osapi\model\active\Active as Event;
 
class ActiveEnroller extends BaseModel
{
    public static function add_enroll($uid,$event_id,$event=null){
        $data['uid']=$uid;
        $data['event_id']=$event_id;
        $data['status']=1;
        if(self::where($data)->count()){
            return true;
        }
        do{
            $code = create_rand(7);
        }while(self::where(['code'=>$code])->count());
        $data['code']=$code;
        $data['create_time']=time();
        $data['over_time']=0;
        $data['is_event_creater']=0;
        if($event!=null)
        {
            if($event['uid']==$uid)
            {
                $data['is_event_creater']=1;
            }
        }
        return self::insertGetId($data);
    }

  
    /**
     * 获取核销内容
     * @param $enroll
     * @return array
     */
    public static function get_enroll_info_by_code($enroll){

        // $info=self::get_enroll_info($enroll['event_id'],$enroll['uid']);
        $user=db('user')->where(['uid'=>$enroll['uid']])->field('nickname,avatar,uid')->find();
        $event=Event::where(['id'=>$enroll['event_id']])->field('store_branch_id,id,user_sex,user_name,user_phone,title,uid,start_time,end_time,address,detailed_address')->find();
        $event['start_time']=date('Y-m-d H:i:s',$event['start_time']);
        $event['end_time']=date('Y-m-d H:i:s',$event['end_time']);
        $event['store_branch']=db('active_store_branch')->where('id',$event['store_branch_id'])->find();
 
        
        $v['store_branch']=
        $info=[
            ['name'=>'昵称','content'=>db('user')->where('uid',$event['uid'])->value('nickname')],
            ['name'=>'真实姓名','content'=>$event['user_name']],
            ['name'=>'手机号','content'=>$event['user_phone']],
            ['name'=>'性别','content'=>$event['user_sex']],
            ['name'=>'活动参与人数','content'=>db('active_enroller')->where('event_id',$event['id'])->where('status',1)->count() ],
            ['name'=>'领取失效时间','content'=>date('Y-m-d H:i:s',$enroll['over_time'])],
        ];

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