<?php
/**
 * Created by PhpStorm.
 * User: zzl-yf
 * Date: 2020/2/14
 * Time: 15:47
 */

namespace app\commonapi\controller;


use app\admin\model\com\ComSite;
use app\commonapi\model\SystemCountLogToShow;
use app\osapi\model\com\ComThread;
use app\osapi\model\com\MessageUserPopup;
use app\osapi\model\user\UserModel;
use basic\ControllerBasic;
use service\UtilService;
use app\admin\model\system\SystemConfig;
use think\Cache;
use think\Request;
use app\commonapi\model\Gong;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;

class System extends ControllerBasic
{

    /**
     * 用户首次访问
     */
    public function firstUser()
    {
        $count_args['platform']=osx_input('post.platform',0);
        $count_args['user_type']=osx_input('post.user_type','');
        $platform=$count_args['platform'];
        if(!in_array($platform,SystemCountLogToShow::$platform_list)){
            $this->apiError('请传入使用平台');
        }
        if($count_args['user_type']!='new'&&$count_args['user_type']!='active'){
            $this->apiError('请传入用户类型');
        }
        $data=[
            'place'=>$platform,
            'type'=>$count_args['user_type'],
            'create_time'=>time()
        ];
        $res=db('system_count_log_user')->insertGetId($data);
        if(!$res){
            $this->apiError('插入记录失败');
        }
        $this->apiSuccess('记录成功');
    }

    /**
     * 用户访问次数
     */
    public function viewCount()
    {
        $count_args['platform']=osx_input('post.platform',0);
        $count_args['num']=osx_input('post.num',0);
        $platform=$count_args['platform'];
        if(!in_array($platform,SystemCountLogToShow::$platform_list)){
            $this->apiError('请传入使用平台');
        }
        if(intval($count_args['num'])<=0){
            $this->apiError('请传入访问次数');
        }
        $data=[
            'place'=>$platform,
            'num'=>intval($count_args['num']),
            'create_time'=>time()
        ];
        $res=db('system_count_log_view')->insertGetId($data);
        if(!$res){
            $this->apiError('插入记录失败');
        }
        $this->apiSuccess('记录成功');
    }

    /**
     * 用户分享次数
     */
    public function shareCount()
    {
        $platform=osx_input('post.platform',0);

        //接口返回进行阅读量统计
        $id=osx_input('post.thread_id',0);
        if($id){
            $list[]=['id'=>$id];
            ComSite::views($list);
        }

        if(!in_array($platform,SystemCountLogToShow::$platform_list)){
            $this->apiError('请传入使用平台');
        }
        $request = Request::instance();
        $tag='share_count_census_'. $request->ip();
        $is_count=Cache::get($tag);
        if(!$is_count){
            Cache::set($tag,'yes',5);
            $data=[
                'place'=>$platform,
                'create_time'=>time()
            ];
            $res=db('system_count_log_share')->insertGetId($data);
            if(!$res){
                $this->apiError('插入记录失败');
            }
        }
        Cache::clear($tag);
        $now_login=get_uid();
        website_connect_notify($now_login,0,0,'osapi_user_share');//通知第三方平台，任务回调
        $data = [
            'sharetype' => $platform,
            'uid'       => $now_login,
            'create_time'=>time()
        ] ;
        db('user_share')->insert($data);
        /*$uid=get_uid();
        $info='';
        if($uid>0){
            //增加任务积分
            Gong::finishtask('fenxiang','user_share','uid') ;
            //增加行为积分
            $info=Gong::actionadd('fenxiang','user_share','uid') ;
            //首次发帖
            Gong::firstaction('user_share','shoucifenxiang','uid');
            //发送小程序订阅消息
            if($id){
                $thread=ComThread::where('id',$id)->field('title,author_uid,content')->find();
                $thread['title']=$thread['title']?$thread['title']:mb_substr(json_decode($thread['content']),0,10).'...';
                $nickname=UserModel::where('uid',$thread['author_uid'])->value('nickname');
                RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($thread['author_uid']),RoutineTemplateService::THREAD_SHARE, [
                    'thing1'=>$nickname,
                    'date2'=>date('Y/m/d H:i',time()),
                    'thing4'=>$thread['title'],
                ],'');
            }
        }
        $this->apiSuccess('分享成功!'.$info);*/
        $this->apiSuccess('分享成功!');
    }


    /**
     * 获取协议列表
     * @author zxh  zxh@ourstu.com
     */
    public function get_agreement(){
        $data=db('user_agreement')->where(['status'=>1])->select();
        $this->apiSuccess($data);
    }

    /**
     * 获取证件相关内容
     * @author zxh  zxh@ourstu.com
     */
    public function get_company(){
        $company_name=SystemConfig::getValue('company_name');
        $related_information=SystemConfig::getValue('related_information');
        $this->apiSuccess(['company_name' => $company_name, 'related_information' =>$related_information]);
    }

    /**
     * 获取协议的详情内容
     * @author zxh  zxh@ourstu.com
     *时间：2020.3.10
     */
    public function get_agreement_one(){
        $pam_id=osx_input('post.id',0,'intval');;
        if($pam_id<=0){
            $this->apiError('请传入正确的id');
        }
        $data=db('user_agreement')->where(['id'=>$pam_id])->find();
        $this->apiSuccess($data);
    }

    /**
     * 获取微信登录设置
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.27
     */
    public function get_weixin_login_set(){
        $data['must_weixin_login']=SystemConfig::getValue('must_weixin_login');
        $this->apiSuccess($data);

    }

    /**
     * 获取邀请设置配置
     * @author zxh  zxh@ourstu.com
     *时间：2019.10.25
     */
    public function getInviteSet(){
        $is_invite= db('system_config')->where(['menu_name'=>'invite_code'])->find();
        $need_invite= db('system_config')->where(['menu_name'=>'invite_code_need'])->find();
        $this->apiSuccess(['is_invite'=>json_decode($is_invite['value']),'need_invite'=>json_decode($need_invite['value'])]);
    }

    /**
     * 获取配置集合
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.2
     */
    public function get_common_config(){
        $field='official_account_article_url,member_ship_xieyi,trail_explain,vip_nav_config_backgorup_img,vip_nav_config_name,vip_nav_config_desc,vip_nav_config_nav_title,vip_nav_config_icon,';
        $field.='website_connect_open,website_connect_login_page,share_title,share_content,share_picture,forum_num_limit,weibo_store_limit,weibo_content_limit,forum_content_limit,forum_product_limit,share_suffix,code_site,invite_show,shop_phone,service_code,website_name,website_introduce,website_logo,business_cooperation,feedback,forum_admin_two,';
        $field.='forum_admin_one,invite_code,invite_code_need,website_logo_show,website_url,tencent_video_is_open,tencent_video_app_id,video_size,video_product,video_title,video_content,video_title_down,video_content_down,must_weixin_login,xcx_video,index_goods_price,index_goods_sale,index_goods_name,video_default_cover,picture_max,video_cover_type';
        $field.=',im_open,im_url,im_workman_url,reward_points_rules,wallet_open,withdraw_to_wallet';
        $field.=',withdrawal_min_amount,withdrawal_max_amount,withdrawal_service_charge,withdrawal_day_max_amount,recharge_max_amount,recharge_day_max_amount,wallet_agreement_name,wallet_agreement_content';
        $field.=',picture_store_tencent_url,open_membership,active_config_rule,active_config_detail,active_enable,active_enroll_count';
 
        $field=explode(',',$field);
        $config=db('system_config')->where(['menu_name'=>['in',$field]])->field('menu_name,value')->select();
        $data=[];
        foreach ($config as $v){
            if($v['menu_name']=='trail_explain' || $v['menu_name']=='member_ship_xieyi')
            {
                $data[$v['menu_name']]=($v['value']);
            }else
            {
                $data[$v['menu_name']]=json_decode($v['value'],true);
            }
    
        }
        unset($v);
        $data['weixin']=get_root_path($data['service_code']);
        $data['share_picture']=get_root_path($data['share_picture']);
        $data['$website_logo']=get_root_path(SystemConfig::getValue('website_logo'));
        if(strpos($data['website_logo'],'http') === false){
            $url='http://'.$_SERVER['SERVER_NAME'];
            $data['website_logo']= $data['website_logo']? $url.$data['website_logo']:$data['website_logo'];
        }
        $data['forum_admin_one']=get_root_path($data['forum_admin_one']);
        $data['forum_admin_two']=get_root_path($data['forum_admin_two']);
        //社区配置
        $data['community']=db('com_site')->where('id',1)->find();
        //pc设置
        $data['pc_set']=db('pc_set')->where('id',1)->find();

        //禁言时间和理由
        $data['reason']=db('prohibit_reason')->where('status',1)->order('sort desc')->select();
        $data['time']=db('report_prohibit')->where('status',1)->order('sort desc')->select();
        
        //获取邀请设置配置
        $is_invite= db('system_config')->where(['menu_name'=>'invite_code'])->find();
        $need_invite= db('system_config')->where(['menu_name'=>'invite_code_need'])->find();
        $data['is_invite'] = intval(json_decode($is_invite['value']));
        $data['need_invite'] = intval(json_decode($need_invite['value']));

        //底部版权
        $open_list=$this->_getClientOpenList();
        if(in_array('right_copy',$open_list)){
            $website_logo=$data['website_logo'];
            $website_url=$data['website_url'];
        }else{
            $website_logo='';
            $website_url ='';
        }
        $url='http://'.$_SERVER['SERVER_NAME'];
        $is_logo=$website_logo? true:false;
        if(preg_match('/^http(s)?:\\/\\/.+/',$website_logo)){
            $data['logo']=$website_logo;
        }else{
            $data['logo']=$url.$website_logo;
        }
        $data['website_url']=$website_url;
        $data['is_logo']=$is_logo;

        //支付设置
        $data['pay_set']=db('pay_set')->select();

        //注册设置
        $data['login_set']=db('user_login_set')->select();

        //获取视频播放openid
        $tencent_video_app_id = $data['tencent_video_app_id'];
        $tencent_video_is_open = $data['tencent_video_is_open'];
        /**加密 start**/
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $data['tencent_video_app_id'] = urlencode(base64_encode(openssl_encrypt($tencent_video_app_id,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv)));
        $data['tencent_video_is_open'] = intval($tencent_video_is_open) == 1 ? true : false;
        
        //小程序视频是否开启(审核用)
        $data['xcx_video']=intval($data['xcx_video']);

        //小程序订阅消息
        $data['xcx_message']=db('wechat_routine_template')->select();

        //获取积分商城配置
        $data['shop']= SystemConfig::getValue('shop_on');
        //获取活动商城配置
        $data['event']= intval(SystemConfig::getValue('event_on'));
        //是否是核销员
        $uid=get_uid();
        $is_check=db('event_check')->where(['uid'=>$uid,'status'=>1])->count();
        $data['is_check']=$is_check>0?1:0;

        //自定义消息设置
        $data['message_name']= SystemConfig::getValue('message_name');
        $data['message_logo']= SystemConfig::getValue('message_logo');
 
        //获取用户弹窗设置
        $res = MessageUserPopup::getUserPopup($uid);
        $data['user_popup']=$res;
        //链接白名单
        $white=SystemConfig::getValue('url_link_white');
        $data['url_link_white']=explode('<br/>',preg_replace('/\s/u','',nl2br($white)));
        // 视频封面类型
        $data['video_cover_type'] = SystemConfig::getValue('video_cover_type');
        //是否开启社区图片评论
        $data['open_post'] = SystemConfig::getValue('comment_photo');

        if(!isset($data['open_membership'])){ 
            $data['open_membership']=false; 
        }else{
            $data['open_membership']=$data['open_membership']=='1';
        }

        $data['active_config_rule_pic']=$data['active_config_rule'];
        $data['active_config_rule']='<img src="'.$data['active_config_rule'].'">';

        $data['active_config_detail_pic']=$data['active_config_detail'];
        $data['active_config_detail']='<img src="'.$data['active_config_detail'].'">';
 
        $data['active_enable']=$data['active_enable']=='1';
        if(!isset($data['official_account_article_url']))
        {
            $data['official_account_article_url']='https://mp.weixin.qq.com/s/zljy95hJIrPN9lRjFbYZmg';
        }
        // $data['test']='xxxx';
        $this->apiSuccess($data);
    }

    /**
     * 检测是否上白名单链接
     * 2020.7.18
     */
    public function check_url_link_white(){
        $white=SystemConfig::getValue('url_link_white');
        $url=osx_input('url','','text');
        $white=explode('<br />
',nl2br($white));
        if(in_array($url,$white)){
            $this->apiSuccess(['is_white'=>1]);
        }else{
            $this->apiSuccess(['is_white'=>0]);
        }
    }
}