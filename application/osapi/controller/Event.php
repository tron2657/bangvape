<?php
namespace app\osapi\controller;
use app\admin\model\event\EventMessage;
use app\admin\model\system\SystemConfig;
use app\osapi\model\com\ComPost;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageRead;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\Report;
use app\osapi\model\common\Blacklist;
use app\osapi\model\event\EventCategory;
use app\osapi\model\event\Event as EventModel;
use app\osapi\model\event\EventEnroller;
use app\osapi\model\event\EventField;
use app\commonapi\controller\Sensitive;
use app\commonapi\model\share\ShareLink;
use app\osapi\model\user\UserModel;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;
use behavior\event\EventBehavior;
use think\Cache;
use service\HookService;
use think\Exception;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class Event extends Base
{
    /**
     * 获取活动所有分类
     */
    public function get_event_cate_list(){
        $cate=EventCategory::get_event_cate_list();
        $this->apiSuccess($cate);
    }

     /**
      * 活动列表
      */
    public function get_event_list(){
        $cate_id=osx_input('cate_id',0,'intval');
        $price=osx_input('price');
        $order_type=osx_input('order_type');
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',0,'intval');
        $search=osx_input('search');
        $map['status']=1;
        if($cate_id){
            $map['cate_id|cate_pid']=$cate_id;
        }
        if($price=='pay'){
            $map['price_type']=['gt',0];
        }elseif($price=='free'){
            $map['price_type']=0;
        }
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
     * @api {post} /osapi/event/get_event_list_huajian 花间一壶酒活动1.活动列表
     * @apiName get_event_list_huajian
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {string} district 地区
     */
    public function get_event_list_huajian(){
        $district=osx_input('district','');
        $map['district']=$district;
        $map['cate_id']=1;
        $map['status']=1;
        // $map['lat']=osx_input('lat','');
        // $map['lng']=osx_input('lng','');
        $page=1;
        $limit=20;
        $order='create_time desc';
        $postion=null;
        if(osx_input('lat','') !='' && osx_input('lng','')!='')
        {
           $postion= [
                'lat'=>osx_input('lat',''),
                'lng'=>osx_input('lng',''),
           ];
        }

        $data=EventModel::get_event_list($map,$page,$limit,$order,false,$postion);
        $this->apiSuccess($data);
    }


    /**
     * @api {post} /osapi/event/get_event 花间一壶酒活动2.获取活动详情页
     * @apiName get_event
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} id 活动id
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

    /**
     * @api {post} /osapi/event/enroll_event 花间一壶酒活动3.活动报名页
     * @apiName enroll_event
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} id 活动id
     */
    public function enroll_event(){
        $id=osx_input('id',0,'intval');
        $status=EventModel::where(['id'=>$id])->value('status');
        if($status==-1){
            $this->apiError(['info'=>'该活动已经被删除']);
        }
        if($status==0){
            $this->apiError(['info'=>'该活动已经被取消不能报名']);
        }
        $uid=$this->_needLogin();
        $res=EventEnroller::add_enroll($uid,$id);
        if($res){
            $datum=EventField::get_event_datum($id);
            $event=EventModel::where(['id'=>$id])->field('id,price,price_type,content')->find()->toArray();
            $data['datum']=$datum;
            $flag=SystemConfig::getValue('event_type_pay');
            $event['price_unit']=db('system_rule')->where('flag',$flag)->value('name');
            $data['event']=$event;
            $this->apiSuccess($data);
        }else{
            $this->apiError(['status'=>0,'info'=>'报名信息填写失败']);
        }
    }

     /**
     * @api {post} /osapi/event/enroll 花间一壶酒活动4.参加报名
     * @apiName enroll
     * @apiGroup 花间一壶酒活动
     * @apiHeader {string} Access-Token=4bb116d3-d820-47ce-a04b-b9860f8792a2
     * @apiHeader {string} content-type=application/x-www-form-urlencoded;charset=utf-8
     * @apiHeader {string} Platform-Token=pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=
     * @apiHeader {string} KF=cp
     * @apiParam {int} event_id 活动id
     * @apiParam {varchar} zsxm 真实姓名
     * @apiParam {varchar} wx 微信
     * @apiParam {varchar} sjh 手机号
     * @apiParam {varchar} sfzh 身份证号
     */
    public function enroll(){
        $id=osx_input('event_id',0,'intval');
        //初始判断上限
        $event=db('event')->where(['id'=>$id])->field('id,status,enroll_count,enroll_reality_count,forum_id,price,price_type,enroll_range,enroll_start_time,enroll_end_time')->cache('event_enroll_count_'.$id)->find();
        if($event['status']!=1){
            $this->apiError( ['status'=>0,'info'=>'该活动已经删除或者已经取消，无法报名']);
        }
        if($event['enroll_end_time']<time()){
            $this->apiError( ['status'=>0,'info'=>'活动报名已结束']);
        }
        if($event['enroll_start_time']>time()){
            $this->apiError( ['status'=>0,'info'=>'活动还未开始报名']);
        }
        $uid=$this->_needLogin();
        $event['enroll_reality_count']+=1;
        Cache::set('event_enroll_count_'.$id,$event);

        if($event['enroll_reality_count']>$event['enroll_count']&&$event['enroll_count']!=0){
            $this->apiError(['status'=>0,'info'=>'很遗憾,报名人数已满。']);
        }

        $datum=EventField::getEventField($id);

        //不能重复报名
        $enroll_count=EventEnroller::where(['uid'=>$uid])->where('status','in','1,2')->count();
        if($enroll_count>=1){
            $this->apiError(['status'=>0,'info'=>'您已报名过活动，不能再报名了']);
        }

        $enroll=EventEnroller::where(['uid'=>$uid,'event_id'=>$id])->find();
        if(!$enroll){
            $event['enroll_reality_count']-=1;
            Cache::set('event_enroll_count_'.$id,$event);
            $this->apiError(['status'=>0,'info'=>'未产生报名信息']);
        }
        if($enroll['status']==1){
            $event['enroll_reality_count']-=1;
            Cache::set('event_enroll_count_'.$id,$event);
            $this->apiError(['status'=>0,'info'=>'活动已经报名成功']);
        }

        $data=[];
        foreach ($datum as $v){
            $value['content']=osx_input($v);
            if(empty($value['content'])){
                $this->apiError(['info'=>'请将报名信息填写完整']);
            }
            $value['field']=$v;
            $value['event_id']=$id;
            $value['uid']=$uid;
            $value['status']=1;
            $value['create_time']=time();
            $data[]=$value;
            if($value['field']=='sfzh'){
                //判断身份证是否已被绑定过了
                if(!checkIdCard($value['content'])){
                    $this->apiError(['info'=>'身份证格式不正确']);
                }
                $sfzh_count= db('event_enroller_info')->where(['field'=>'sfzh','content'=>$value['content']])->count();
                if($sfzh_count>0){
                    $this->apiError(['info'=>'该身份证号已被绑定']);
                }
            }
        }
        $res=db('event_enroller_info')->insertAll($data);
        if($res){
            $result=EventEnroller::enroll_info($id,$uid);
            if($result['status']==1){
                EventMessage::send_message(56,$id,$uid);
                $this->apiSuccess($result);
            }else{
                $this->apiError($result);
            }
        }else{
            $event['enroll_reality_count']-=1;
            Cache::set('event_enroll_count_'.$id,$event);
            $this->apiError(['info'=>'报名信息存储错误']);
        }
    }

    

    /**
     * 回复主题帖或者楼中楼评论
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    public function postAdd()
    {
        $uid=$this->_needLogin();
        //判断是否是处于禁言
        if(Report::is_prohibit($uid)){
            $this->apiError(['info'=>'你正在处于禁言中']);
        }
        //发评论
        action_power('send_comment',$uid);

        $to_reply_uid = input('post.to_reply_uid/d', 0); //回复评论时的评论作者uid
        $thread_id = input('post.event_id/d', '0'); //活动id
        $thread=EventModel::get($thread_id);

        $image = input('post.image', '');//图片
        $audio_url = input('post.audio_url', '','text');//音频地址
        $audio_id = input('post.audio_id', '','text');//视频腾讯云上的音频id
        $audio_time = input('post.audio_time', 0,'intval');//音频时长

        $to_reply_id = input('post.to_reply_id/d', 0); //对应楼层帖子post_id
        $content = input('post.content', '','');
        $is_black=Blacklist::isBlack($to_reply_uid,$uid);
        if ($is_black) {
            $this->apiError(['info'=>'由于对方的权限设置，您无法进行该操作']);
        }
        if (!$content) {
            $this->apiError(['info'=>'内容文字不能为空！']);
        }
        $level = ($to_reply_id != 0) ? 2 : 1; //判断是楼中楼评论还是楼层，$to_reply_id有值，说明是楼中楼评论
        if($to_reply_id){
            $postInfo=ComPost::get($to_reply_id);
            if(!$postInfo||$postInfo['status']!=1){
                $this->apiError(['info'=>'楼层不存在或已删除！']);
            }
            if(!$thread_id){
                $thread_id=$postInfo['tid'];
            }
            if($postInfo['event_id']!=$thread_id){
                $this->apiError(['info'=>'非法操作！']);
            }
        }

        if(!$thread||$thread['status']!=1){
            $this->apiError(['info'=>'主题帖子不存在或已删除！']);
        }
        $content=Sensitive::sensitive($content,'社区评论');
        $data = [
            'author_uid' => $uid,
            'to_reply_uid' => $to_reply_uid,
            'fid' => $thread['forum_id'],
            'tid' => 0,
            'content' => emoji_encode($content),
            'to_reply_id' => $to_reply_id,//对应楼层帖子id
            'level' => $level,
            'is_thread'=>0,
            'status'=>1,
            'create_time'=>time(),
            'from'=>'phone',
            'image'=>$image,
            'audio_url'=>$audio_url,
            'audio_id'=>$audio_id,
            'audio_time'=>$audio_time,
            'event_id'=>$thread['id'],
            'type_id'=>2
        ];
        $result = ComPost::createPost($data); //添加评论操作

        if ($result) {
            $to_reply_thread_id=$thread_id;
            $nickname=UserModel::where('uid',$uid)->value('nickname');
            if($uid!=$to_reply_uid){
                $length=mb_strlen($content,'UTF-8');
                if($length>7){
                    $content=mb_substr($content,0,7,'UTF-8').'…';
                }
                if($level==1){
                    $to_reply_uid=$thread['uid'];
                }
                $set=MessageTemplate::getMessageSet(6);
                if($set['status']==1){
                    $template=str_replace('{用户昵称}', $nickname, $set['template']);
                    $message_id=Message::sendMessage($to_reply_uid,$uid,$template,2,$set['title'],2,'','reply_event',$to_reply_thread_id);
                    MessageRead::createMessageRead($to_reply_uid,$message_id,$set['popup'],2);
                }
                //todo 跳转链接
                RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($to_reply_uid),RoutineTemplateService::POST_REPLY, [
                    'thing3'=>['value'=>$nickname],
                    'time4'=>['value'=>date('Y/m/d H:i',time())],
                    'thing2'=>['value'=>$content],
                    'thing5'=>['value'=>'无'],
                ],'','/packageA/post-page/post-page?id='.$thread_id);
            }

            $res['post_id']=$result;
            $res['info']='发布成功';
            census('comment',1);
            $this->apiSuccess($res);
        } else {
            $error_info=ComPost::getErrorInfo();
            $this->apiError($error_info);
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
                $event_ids=db('event_enroller')->where(['status'=>1,'uid'=>$uid])->column('event_id');
                $map['id']=['in',$event_ids];
                break;
            case 'collect':
                $event_ids=db('event_collect')->where(['status'=>1,'uid'=>$uid])->column('eid');
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
     * 获取报名信息
     */
    public function get_enroll_info(){
        $event_id=osx_input('event_id',0,'intval');
        if(!$event_id){
            $this->apiError(['info'=>'请选择查看的活动']);
        }
        $uid=$this->_needLogin();
        $data=EventEnroller::get_enroll_info($event_id,$uid);
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
                $code_url =ShareLink::gen_share_link($uid,'/packageA/activity/verification?code='.$event_code,'event');
            // }
            $errorCorrectionLevel = 'H';    //容错级别
            $matrixPointSize = 7;           //生成二维码图片大小
    
            $thumb_dir_path=UPLOAD_PATH.'/event/'.$uid.'/';
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
     * 收藏/取消活动
     */
    public function collect(){
        $uid=$this->_needLogin();
        $event_id=osx_input('event_id',0,'intval');
        $count=db('event_collect')->where(['uid'=>$uid,'eid'=>$event_id])->count();
        if($count==1){
            $res=db('event_collect')->where(['uid'=>$uid,'eid'=>$event_id])->delete();
            $name='取消收藏';
        }else{
            $res=db('event_collect')->insert(['uid'=>$uid,'eid'=>$event_id,'create_time'=>time(),'status'=>1]);
            $name='收藏';
        }
        if($res){
            $this->apiSuccess(['status'=>1,'info'=>$name.'成功']);
        }else{
            $this->apiError(['status'=>0,'info'=>$name.'失败']);
        }
    }

    /**
     * 获取版块内的活动
     */
    public function get_forum_event(){
        $forum_id=osx_input('forum_id',0,'intval');
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',3,'intval');
        $map['forum_id']=$forum_id;
        $map['status']=1;
        $data=EventModel::get_event_list($map,$page,$limit,$order='create_time desc',false);
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
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$enroll['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能查看']);
        }
        
        list($info,$user,$event)=EventEnroller::get_enroll_info_by_code($enroll);
        $check_user=db('user')->where(['uid'=>$uid])->field('nickname,avatar,uid')->find();
        $user['add_time']=date('Y-m-d H:i:s',$enroll['create_time']);
        $this->apiSuccess(['event'=>$event,'check_user'=>$check_user,'user'=>$user,'datum'=>$info]);
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
        $this->apiError(['info'=>'暂不支持改方法,请更新小程序']);
        $code=osx_input('code');
        $uid=$this->_needLogin();
        $enroll=EventEnroller::where(['code'=>$code,'status'=>1])->find();
        // HookService::listen('event_sure_check',$enroll,$code,true,\app\core\behavior\EventBehavior::class);
 
        HookService::beforeListen('event_sure_check',$enroll,$code,true,\app\core\behavior\EventBehavior::class);
        
        if(!$enroll){
            $this->apiError(['info'=>'不存在该核销码']);
        }
        if($enroll['check_time']>0){
            $this->apiError(['info'=>'该核销码已经核销']);
        }
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$enroll['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能进行核销']);
        }
        $res=EventEnroller::where(['code'=>$code,'status'=>1])->update(['check_uid'=>$uid,'check_time'=>time()]);
        if($res){
            db('event')->where(['id'=>$enroll['event_id']])->setInc('check_count',1);
            HookService::afterListen('event_sure_check',$enroll,$code,false,\app\core\behavior\EventBehavior::class);
            $this->apiSuccess(['info'=>'核销成功']);
        }else{
            $this->apiError(['info'=>'核销失败']);
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
        $osx_event=osx_input('event_id',0,'intval');

        if($type=='my'){
            $map['check_uid']=$uid;
            $map['event_id']=$osx_event;
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
     * 活动楼中楼列表
     */
    public function threadReply()
    {
        $page=osx_input('page',1,'intval');
        $row=osx_input('limit',10,'intval');
        $event_id = osx_input('event_id', 0,'intval');
        $type=osx_input('type');
        if($type=='hot'){
            $sort='support_count desc';
        }else{
            $sort='create_time desc';
        }
//        $sort='create_time desc';
        $list = ComPost::getEventReplyList($event_id ,$page, $row, $sort); //获取帖子的评论列表
        $this->apiSuccess($list);
    }

    /**
     * 获取我的核销内容
     */
    public function my_check(){
        $uid=$this->_needLogin();
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',10,'intval');
        $type=osx_input('type','');
        $event_id=db('event_check')->where(['status'=>1,'uid'=>$uid])->column('event_id');
        $map['id']=['in',$event_id];
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
        $code=osx_input('code');
        $enroll=EventEnroller::where(['code'=>$code,'status'=>1])->find();
        if(!$enroll){
            $this->apiError(['info'=>'不存在该核销码','error_code'=>0]);
        }
        $uid=get_uid();
        $is_check_user=db('event_check')->where(['uid'=>$uid,'event_id'=>$enroll['event_id'],'status'=>1])->count();
        if(!$is_check_user){
            $this->apiError(['info'=>'您不是核销员,不能查看','error_code'=>1]);
        }
        if($enroll['check_time']>0){
            $this->apiError(['info'=>'该核销码已经核销']);
        }
        $this->apiSuccess(['info'=>'核销码有用']);
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