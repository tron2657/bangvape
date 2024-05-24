<?php
namespace app\osapi\controller;

use app\admin\model\active\Active as ActiveActive;
use app\admin\model\active\ActiveEnroller;
use app\admin\model\active\ActiveMessage as EventMessage;
use app\admin\model\system\SystemConfig;
use app\osapi\model\com\ComPost;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\Report;
use app\osapi\model\common\Blacklist;
use app\osapi\model\active\ActiveCategory;
use app\osapi\model\active\Active as EventModel;
use app\osapi\model\active\ActiveEnroller  as EventEnroller;
use app\osapi\model\active\ActiveField;
use app\commonapi\controller\Sensitive;
use app\commonapi\model\share\ShareLink;
use app\osapi\model\user\UserModel;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;
use behavior\event\EventBehavior;
use think\Cache;
use service\HookService;
use think\Exception;
use service\UtilService as Util;
use think\Db;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class Active extends Base
{
   


    

    /**
     * 发布约酒活动
     *
     * @return void
     */
    public function publish(){

        try{
            $params = Util::postMore([
          
                ['user_name',''],//姓名
                ['user_phone',0],//手机号码
                ['user_sex',''],//性别
                ['store_branch_id'],//活动门店ID        
                ['enroll_start_time',0],//报名开始时间
                ['enroll_end_time',0],//报名结束时间    
                // ['start_time',0],//组局开始时间默认为，报名开始时间
                ['end_time',0],//报名结束时间    
                ['enroll_count',''],//报名人数
                ['enroll_range',0],                
                ['is_need_check',1],
                ['is_recommend',0],
               
            ],$this->request);
     
            $params['uid']=$this->_needLogin();

    
            $res=EventModel::publish($params);
            if($res!==false){               
                $this->apiSuccess($res,'发布约酒成功');
            }else{
                $this->apiFailed('发布约酒失败');
            }
        
            
            return $this->apiSuccess('发布成功！',$res);
        }catch(Exception $ex)
        {
            return $this->apiFailed(null,$ex->getMessage());
        }
        
    }


     /**
      * 活动列表
      */
    public function get_event_list(){
 
 
        $order_type=osx_input('order_type');
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',0,'intval');
        $search=osx_input('search');
        $city=osx_input('city');
        $district=osx_input('district');
        $map['status']=1;
        $map['city']=$city;
        $mpa['district']=$district;
        switch ($order_type){
            case 'time': $order='create_time desc';break;
            case 'view': $order='view desc';break;
            case 'enroll': $order='enroll_reality_count desc';break;
            default:$order='is_recommend desc,create_time desc';
        }
        if($search){
            $map['title|content']=['like','%'.$search.'%'];
        }

        $data=EventModel::get_event_list($map,$page,$limit,$order);
        $this->apiSuccess($data);
    }
     

   /**
    * 获取活动详情
    *
    * @return void
    */
    public function get_event(){
        $id=osx_input('id',0,'intval');
        if(!$id){
            $this->apiError(['info'=>'请选择查看的活动']);
        }
        $status=EventModel::where(['id'=>$id])->value('status');
        if($status==-1){
            $this->apiError(['info'=>'该活动已经被删除']);
        }
        $data=EventModel::getEvent($id);
        $this->apiSuccess($data);
    }

    public function get_branch_store_list()
    {
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',0,'intval');
        $search=osx_input('search');
        $city=osx_input('city');
        $district=osx_input('district');

        $map['city']=$city;
        $map['district']=$district;
        $map['is_close']=0;
        $data= db('active_store_branch')->where('stock','>',0)->where($map)->select();
        return $this->apiSuccess($data);
    }
   

    /**
     * 参加约酒活动
     *
     * @return void
     */
    public function enroll(){

        try
        { 
            $uid=$this->_needLogin();  
            $id=osx_input('event_id',0,'intval');
            $config=SystemConfig::getMore('active_cycle_days,active_enroller_over_time_seconds');//活动间隔周期
            if(!isset($config['active_cycle_days']))
            {
                $config['active_cycle_days']='30';
            }
            if(!isset($config['active_enroller_over_time_seconds']))
            {
                $config['active_enroller_over_time_seconds']='86400';
            }
            //初始判断上限
            $event=db('active')->where(['id'=>$id])->field('id,status,enroll_count,enroll_reality_count,forum_id,price,price_type,enroll_range,enroll_start_time,enroll_end_time')->cache('c_active_enroll_count_'.$id)->find();
            if($event['status']!=1){
                throw new Exception('该活动已经删除或者已经取消，无法报名');
            }
            if($event['enroll_end_time']<time()){          
                throw new Exception('活动报名已结束');
            }
            if($event['enroll_start_time']>time()){
                throw new Exception('活动还未开始报名');
            }
                       
 
            if($event['enroll_count']!=0)
            {
                if($event['enroll_reality_count']>$event['enroll_count']){           
                    throw new Exception('很遗憾,报名人数已满');
                }        
            }

            //不能重复报名
            $enroll_count=Db::view('active_enroller a')->view('active b','*','a.event_id=b.id')
            ->where('a.uid',$uid)
            ->where('b.uid','<>',$uid)
            ->where('b.status','in','1,2')->count();
           
            if($enroll_count>=1){
                throw new Exception('您已报名过活动，不能再报名了');
            }

            //判断自己是否有正在参与的活动
            $myRuningEventCount=Db::name('active')->where(['uid'=>$uid,'status'=>1,'is_finish'=>0])->count();
            if($myRuningEventCount>0)
            {
                throw new Exception('很抱歉，您当前有正在进行中的活动，无法参与当前活动');
            }
    
            $enroll=EventEnroller::where(['uid'=>$uid,'event_id'=>$id])->find();           
            if($enroll['status']==1){         
                throw new Exception('活动已经报名成功');
            }
     
            $result= EventEnroller::add_enroll($uid,$id);
            if($result==false){
                throw new Exception('参与失败');
            } 

            $event['enroll_reality_count']= EventEnroller::where('event_id',$id) ->count();
            db('active')->where('id',$id)->update(['enroll_reality_count'=> $event['enroll_reality_count']]);
            Cache::set('active_enroll_count_'.$id,$event);

            if($event['enroll_count']!=0)
            {
                if($event['enroll_reality_count']==$event['enroll_count'])
                {
                    $time=time();              
                    $active_cycle_days=(int)$config['active_cycle_days'];//默认30天后才能发起约酒
                    $next_start_time=bcadd(bcmul($active_cycle_days, 86400, 0), $time, 0);
                    //组局成功，修改当前活动状态
                    db('active')->where('id',$id)->update(['is_finish'=>1,'finish_time'=>$time,'next_start_time'=>$next_start_time]);
                    
                    $over_time=bcadd((int)$config['active_enroller_over_time_seconds'] , $time, 0);
                    db('active_enroller')->where('event_id',$id)->update(['over_time'=>$over_time]);

                    $enroll['over_time']=$over_time;
                    HookService::afterListen('active_finish',$enroll,$id,false,\app\core\behavior\ActiveBehavior::class);

                }
            }
            // EventMessage::send_message(56,$id,$uid);
            $this->apiSuccess(['status'=>1,'info'=>'参与成功']);

        }catch(Exception $ex)
        {
            $this->apiError(['status'=>0,'info'=>$ex->getMessage()]);
        }               
    }

 
    /**
     * 取消线下活动
     *
     * @return void
     */
    public function cancel_event()
    {
        try
        {
            $uid=$this->_needLogin();
            $id=osx_input('event_id',0,'intval');
            $result= \app\osapi\model\active\Active::cancel_event($id,$uid);
            return $this->apiSuccess(['status'=>1,'data'=>$result]);
        }
        catch(Exception $ex)
        {
            $this->apiFailed([],$ex->getMessage());
        }   
    }

    /**
     * 我的活动页面
     */
    public function my_event(){
        $uid=$this->_needLogin();
        $type=osx_input('type',0);
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',10,'intval');
        $map['status']=['in',[0,1]];
        $my=false;
        switch ($type){
            case 'enroll':
                $event_ids=Db::view('active_enroller','*')->
                view('active','uid','active_enroller.event_id=active.id')
                ->where(['active_enroller.status'=>1,'active_enroller.uid'=>$uid])
                ->where('active.uid','<>',$uid)
                // ->select();
                ->column('active_enroller.event_id');

                // echo($event_ids);
                // die();
                $map['id']=['in',$event_ids];
                break;
            case 'collect':
                $event_ids=db('active_collect')->where(['status'=>1,'uid'=>$uid])->column('eid');
                $map['id']=['in',$event_ids];
                break;
            case 'my_event':
                $map['uid']=$uid;
                $my=true;
                break;
            default:$this->apiError(['info'=>'参数错误']);
        }
        $data=EventModel::get_event_list($map,$page,$limit,$order='create_time desc',$my);
        $this->apiSuccess($data);
    }

 

    /**
     * @api {post} /osapi/event/get_check_code 花间一壶酒活动5.获取核销码
     * @apiName get_check_code
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} event_id 活动id
     */
    public function get_check_code(){
        $uid=$this->_needLogin();
        $event_id=osx_input('event_id',0,'intval');
        if(!$event_id){
            $this->apiError(['info'=>'请选择查看的活动']);
        }
 
        $data=EventEnroller::where(['uid'=>$uid,'event_id'=>$event_id,'status'=>1])->find();
        if($data){
            $data['create_time']=date('Y-m-d H:i:s',$data['create_time']);
            $data['is_check'] = $data['check_time']>0?1:0;
            $event_time=EventModel::where(['id'=>$event_id])->value(['end_time']);
            $data['is_invalid']=$event_time<time()?1:0;
            $data['user']=db('user')->where(['uid'=>$data['uid']])->field('nickname,uid,avatar')->find();
            $data['event']=EventModel::where(['id'=>$event_id])->value('title');

             //生成二维码 start
             $code_url=get_domain().'/event?code=';
 
            require_once ROOT.'/vendor/phpqrcode/phpqrcode.php';
            $qrcode=new \QRcode();
            $event_code=$data['code'];
            // if($invite_show==1){
                $code_url = $code_url.$event_code;//二维码内容
                $code_url =ShareLink::gen_share_link($uid,'/packageA/active/verification?code='.$event_code,'active_yj');
            // }
            $errorCorrectionLevel = 'H';    //容错级别
            $matrixPointSize = 7;           //生成二维码图片大小
    
            $thumb_dir_path=UPLOAD_PATH.'/active/'.$uid.'/';
            if (!is_dir($thumb_dir_path)){
                mkdir($thumb_dir_path,0777,true);
            }
    
            $qrcode_file_name=$thumb_dir_path.'thumb_qr_code'.$event_code.'.png';
            ob_start();
            $qrcode::png($code_url,$qrcode_file_name , $errorCorrectionLevel, $matrixPointSize, 2);
            ob_end_clean();//关闭缓冲区
            $qrcode_image = getThumbImage($qrcode_file_name,300,300);
           // $qCodeImg =imagecreatefromstring(file_get_contents($qrcode_image['src']));
            //unlink($qrcode_file_name);
           // if($qrcode_file_name!=$qrcode_image['src']){
               // unlink($qrcode_image['src']);
            //}
            //生成二维码 end
            $data['qrcode']=get_root_path($qrcode_image['src'],true);
        }

        $this->apiSuccess($data);
    }

 
    
    /**
     * @api {post} /osapi/event/check_code 花间一壶酒活动6.确认核销页面
     * @apiName check_code
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} code 核销码
     */
    public function check_code(){
        $code=osx_input('code');
        $uid=$this->_needLogin();
        
        $enroll=EventEnroller::where(['code'=>$code,'status'=>1])->find();
        if(!$enroll){
            $this->apiError(['info'=>'不存在该核销码']);
        }
        $is_check_user=db('active_check')->where(['uid'=>$uid,'event_id'=>$enroll['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能查看']);
        }
        
        list($info,$user,$event)=EventEnroller::get_enroll_info_by_code($enroll);
        $check_user=db('user')->where(['uid'=>$uid])->field('nickname,avatar,uid')->find();
        $user['add_time']=date('Y-m-d H:i:s',$enroll['create_time']);
        $this->apiSuccess(['event'=>$event,'check_user'=>$check_user,'user'=>$user,'datum'=>$info]);
    }

  
    private function valid_code()
    {
        $code=osx_input('code');
        $uid=$this->_needLogin();
        $enroll=EventEnroller::where(['code'=>$code])->find();

        try
        {             
            if(!$enroll){           
                throw new Exception('不存在该核销码');
            }
            if($enroll['check_time']>0){
                throw new Exception('该核销码已经核销');           
            }
            if( $enroll['status']==0)
            {
                throw new Exception('该核销码已经失效');    
            }
            
            if(time()> $enroll['over_time'])
            {
                throw new Exception('该核销码已经过期');    
            } 
           
            $is_check_user=db('active_check')->where(['uid'=>$uid,'event_id'=>$enroll['event_id'],'status'=>1])->count();
            if(!$is_check_user){ 
                throw new Exception('您不是核销员,不能进行核销');
            }
         
            return $enroll;
        }
        catch(Exception $ex)
        {
           throw $ex;
        }
    }

    /**
     * @api {post} /osapi/event/sure_check_code 花间一壶酒活动7.核销
     * @apiName sure_check_code
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {varchar} code 核销码
     */
    public function sure_check_code(){
        $code=osx_input('code');
        $uid=$this->_needLogin();  
 
        try
        {             
            $enroll=self::valid_code();
             
            $event=\app\osapi\model\active\Active::where('id',$enroll['event_id'])->find();
            if(!$event['is_finish'])
            {
                throw new Exception('当前约酒活动还未组局成功');  
            }      

            if($event['uid']!=$enroll['uid'])
            {
                throw new Exception('核销的订单不是活动发起人,无法领取');
            }
            
            $res=EventEnroller::where(['code'=>$code,'status'=>1])->update(['check_uid'=>$uid,'check_time'=>time()]);
            if(!$res){
                throw new Exception('核销失败');
            } 

            db('active')->where(['id'=>$enroll['event_id']])->setInc('check_count',1);    
            // HookService::afterListen('active_sure_check',$enroll,$code,false,\app\core\behavior\ActiveBehavior::class);
            $this->apiSuccess(['info'=>'核销成功']);
        }
        catch(Exception $ex)
        {
            $this->apiError(['info'=>$ex->getMessage()]);
        }
       
    }

    /**
     * 获取核销内容
     */
    public function get_check_list(){
        $uid=$this->_needLogin();
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',10,'intval');
        $type=osx_input('type','');
        $osx_event=osx_input('event_id','');

        if($type=='my'){
            $map['check_uid']=$uid;
            if($osx_event!='')
            {
                $map['event_id']=$osx_event;
            }         
        }elseif($type=='all'){
            $map['event_id']=$osx_event;
        }else{
            $this->apiError(['info'=>'不存在该分类']);
        }
        $map['check_time']=['gt',0];
        $data=EventEnroller::get_check_list($map,$page,$limit,$order='create_time desc');
        $this->apiSuccess($data);
    }

    /**
     * 判断活动状态
     */
    public function check_event_status(){
        $event_id=osx_input('event_id',0,'intval');
        $status=EventModel::where(['id'=>$event_id])->value('status');
        if($status==1){
            $this->apiSuccess(['info'=>'活动正常']);
        }else{
            $this->apiError(['info'=>'活动已被取消或者删除']);
        }
    }

 
    /**
     * 获取我的核销内容
     */
    public function my_check(){
        $uid=$this->_needLogin();
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',10,'intval');
        $type=osx_input('type','');
        $event_id=db('active_check')->where(['status'=>1,'uid'=>$uid])->column('event_id');
        $map['id']=['in',$event_id];
        $map['is_finish']=1;
        $order='id desc';
        if($type=='start'){
            $map['enroll_start_time']=['elt',time()];
            $map['end_time']=['egt',time()];
        }elseif($type=='end'){
            $map['end_time']=['lt',time()];
        }else{
            $this->apiError(['info'=>'参数错误']);
        }
        $data=EventModel::get_event_list($map,$page,$limit,$order,true);
        $this->apiSuccess($data);
    }

    /**
     * 检查核销码是否有用
     */
    public function inspect_code(){
        try
        {
            $enroll= self::valid_code();
            $this->apiSuccess(['info'=>'核销码有用']);
        }catch(Exception $ex)
        {
            $this->apiError(['info'=>$ex->getMessage()]);
        }
         
    }

    /**
     * 获取核销数量
     */
    public function get_check_num(){
        $uid=$this->_needLogin();
        $osx_event=osx_input('event_id',0,'intval');
        $map_start=['event_id'=>$osx_event,'check_uid'=>$uid,'check_time'=>['gt',0]];
        $map_end=['event_id'=>$osx_event,'check_time'=>['gt',0]];
        $count_now=EventEnroller::where($map_start)->count();
        $count_all=EventEnroller::where($map_end)->count();
        $this->apiSuccess(['now'=>$count_now,'all'=>$count_all]);
    }

    /**
     * 判断是否有我发起的活动
     */
    public function is_my_check(){
        $uid=$this->_needLogin();
        $count=db('event')->where(['uid'=>$uid,'status'=>['in',[0,1]]])->count();
        $data['is_show']=$count>0?1:0;
        $this->apiSuccess($data);
    }
}

 
