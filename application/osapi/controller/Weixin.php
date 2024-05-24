<?php
/**----------------------------------------------------------------------
 * OpenCenter V3
 * Copyright 2014-2018 http://www.ocenter.cn All rights reserved.
 * ----------------------------------------------------------------------
 * Author: lin(lt@ourstu.com)
 * Date: 2018/12/25
 * Time: 11:40
 * ----------------------------------------------------------------------
 */

namespace app\osapi\controller;

use app\commonapi\model\Gong;
use app\osapi\lib\ddhttp;
use app\osapi\model\com\HeadLogin;
use app\osapi\model\user\InviteCode;
use app\wechat\sdk\WechatAuth;
use app\osapi\model\user\UserModel;
use app\admin\model\system\SystemConfig;
use app\osapi\lib\FlyPigeno;
use app\osapi\model\user\UserVerify;
use app\osapi\lib\ChuanglanSmsApi;
use think\Db;
use app\wechat\sdk\JSSDK;
use think\Cache;
use app\admin\model\user\UserRecommend as RecommendModel;
use app\osapi\model\common\Support;
use Exception as GlobalException;
use think\Exception;

class weixin extends Base
{
    protected $appId;
    protected $appSecret;
    protected $User;
    protected $UserInfo;
    protected $Token;
    protected $sync;

    public function __construct(\think\Request $request = null)
    {
        parent::__construct($request);
    }


    /**
     * 微信登录url
     * @author:lin(lt@ourstu.com)
     */
    public function weChatLogin()
    {
        $redirect_uri = input('post.url', '', 'text');
        cache('redirect_uri', $redirect_uri);
        $appId = SystemConfig::getValue('wechat_appid');
        if (!empty($appId)) {
            $redirect = urlencode(url('osapi/Weixin/callback', '', true, true));
            $redirect = str_replace('%3A443','',$redirect);
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appId . '&redirect_uri=' . $redirect . '&response_type=code&scope=snsapi_userinfo&state=opensns#wechat_redirect';
            $this->apiSuccess($url);
        } else {
            $this->apiError('缺少微信公众平台的相关配置！');
        }
    }

    public function callback()
    {
        $code = input('get.code', '', 'text');
        $url = cache('redirect_uri') . '?code=' . $code;
        header('location:' . $url);
        exit;
    }

    /**
     * 微信授权登录
     */
    public function weChatOauth()
    {
        $code = input('post.code', '', 'text');
        $platform = input('post.platform', 8,'text');
        $invite_code = input('post.invite_code','','text');
        $appId = SystemConfig::getValue('wechat_appid');
        $appSecret = SystemConfig::getValue('wechat_appsecret');
        $wechat = new WechatAuth($appId, $appSecret);
        /* 获取请求信息 */
        $token = $wechat->getAccessToken('code', $code);
        if (isset($token['errcode'])) {
            $this->apiError('errcode:' . $token['errcode'] . ',errmsg:' . $token['errmsg']);
        }
        $userinfo = $wechat->getUserInfo($token);
        $userinfo['access_token']=$token['access_token'];
        if (isset($userinfo['errcode'])) {
            $this->apiError('errcode:' . $userinfo['errcode'] . ',errmsg:' . $userinfo['errmsg']);
        }
        //判断是否绑定了微信开放平台 xsh
        if(!empty($userinfo['unionid'])){
            $is_unionid = db('user_sync_login')->where('type_uid',$userinfo['unionid'])->find();//用unionid查找数据 qhy
            if (!$is_unionid){
                $is_openid = db('user_sync_login')->where('open_id',$userinfo['openid'])->find();//如果unionid没查到数据，则用openid查找 qhy
                if($is_openid){
                    if ($is_openid['type_uid']!=$userinfo['unionid']) {//如果unionid不同，则更新unionid qhy
                        $data['type_uid'] = $userinfo['unionid'];
                        $data['oauth_token_secret'] = $userinfo['unionid'];
                        $data['open_id'] = $userinfo['openid'];
                        db('user_sync_login')->where('open_id',$userinfo['openid'])->update($data);
                    }
                    if($is_unionid['is_update']==0){//老数据更新信息
                        $data['type_uid'] = $userinfo['unionid'];
                        $data['oauth_token_secret'] = $userinfo['unionid'];
                        $data['open_id'] = $userinfo['openid'];
                        $data['is_update'] = 1;
                        db('user_sync_login')->where('open_id',$userinfo['openid'])->update($data);
                    }
                    $uid=$is_openid['uid'];
                }else{
                    $uid=null;
                }
            }else{
                if(!$is_unionid['open_id']){
                    $data['open_id'] = $userinfo['openid'];
                    db('user_sync_login')->where('type_uid',$userinfo['unionid'])->update($data);
                }
                if($is_unionid['is_update']==0){//老数据更新信息
                    $data['type_uid'] = $userinfo['unionid'];
                    $data['oauth_token_secret'] = $userinfo['unionid'];
                    $data['open_id'] = $userinfo['openid'];
                    $data['is_update'] = 1;
                    db('user_sync_login')->where('type_uid',$userinfo['unionid'])->update($data);
                }
                $uid=$is_unionid['uid'];
            }
        }else{
            $uid = db('user_sync_login')->where('open_id',$userinfo['openid'])->value('uid');
        }
        $userinfo['unionid'] = !empty($userinfo['unionid']) ? $userinfo['unionid'] : '';
        if (!$uid) {
            $user_info = $this->wechat($userinfo);
            $uid = $this->addData($user_info,$userinfo);
            $tui_uids = RecommendModel::where('attention','1')->column('uid');
                foreach($tui_uids as $k => $v){
                    $datass = ['uid' => $uid, 'follow_uid' => $v,'create_time'=>time()];
                    db('user_follow')->insert($datass);
                }
                $ids_count = count($tui_uids);
                $datas = ['follow' => $ids_count];
                UserModel::where('uid',$uid)->update($datas);
            $fids=db('com_forum')->where('default_follow',1)->where('status',1)->column('id');
            foreach($fids as &$val){
                $data['uid']=$uid;
                $data['status']=1;
                $data['create_time']=time();
                $data['fid']=$val;
                db('com_forum_member')->insert($data);
            }
            unset($val);
            //新增微信授权层级 2019.10.25 zxh
            InviteCode::addInviteLog($invite_code,$uid);
            //注册加分
            Gong::actionadd('zhuce','com_post','uidflag',$uid);
        }
        $data = [
            'uid' => $uid,
            'platform'=>8,
            'reg_time' => time(),
        ];
        db('stat_reg_info')->insert($data);
        $res = UserModel::doQuickLogin($uid); //登陆
        if ($res) {
            $this->apiSuccess('微信登录成功', $res);
        } else {
            $this->apiError('微信登录失败');
        }

    }

    /**
     * 微信绑定
     */
    public function weChatOauthBind()
    {
        $uid=$this->_needLogin();
        $code = input('post.code', '', 'text');
        $appId = SystemConfig::getValue('wechat_appid');
        $appSecret = SystemConfig::getValue('wechat_appsecret');
        $wechat = new WechatAuth($appId, $appSecret);
        /* 获取请求信息 */
        $token = $wechat->getAccessToken('code', $code);
        if (isset($token['errcode'])) {
            $this->apiError('errcode:' . $token['errcode'] . ',errmsg:' . $token['errmsg']);
        }
        $userinfo = $wechat->getUserInfo($token);
        $userinfo['access_token']=$token['access_token'];
        if (isset($userinfo['errcode'])) {
            $this->apiError('errcode:' . $userinfo['errcode'] . ',errmsg:' . $userinfo['errmsg']);
        }
        //判断是否绑定了微信开放平台 xsh
        if(!empty($userinfo['unionid'])){
            $is_unionid = db('user_sync_login')->where('type_uid',$userinfo['unionid'])->find();//用unionid查找数据 qhy
            if (!$is_unionid){
                $is_openid = db('user_sync_login')->where('open_id',$userinfo['openid'])->find();//如果unionid没查到数据，则用openid查找 qhy
                if($is_openid){
                    if ($is_openid['type_uid']!=$userinfo['unionid']) {//如果unionid不同，则更新unionid qhy
                        $data['type_uid'] = $userinfo['unionid'];
                        $data['oauth_token_secret'] = $userinfo['unionid'];
                        $data['open_id'] = $userinfo['openid'];
                        db('user_sync_login')->where('open_id',$userinfo['openid'])->update($data);
                    }
                    $old_uid=$is_openid['uid'];
                }else{
                    $old_uid=null;
                }
            }else{
                if(!$is_unionid['open_id']){
                    $data['open_id'] = $userinfo['openid'];
                    db('user_sync_login')->where('type_uid',$userinfo['unionid'])->update($data);
                }
                $old_uid=$is_unionid['uid'];
            }
        }else{
            $old_uid = db('user_sync_login')->where('open_id',$userinfo['openid'])->value('uid');
        }
        $userinfo['unionid'] = !empty($userinfo['unionid']) ? $userinfo['unionid'] : '';
        if (!$old_uid) {
            $res = $this->bindData($uid,$userinfo);
            if ($res) {
                $this->apiSuccess('微信绑定成功');
            } else {
                $this->apiError('微信绑定失败');
            }
        }else{
            $this->apiError('该微信已绑定其他账号');
        }
    }

    /**
     * 解绑微信
     */
    public function weChatBindDel(){
        $uid=$this->_needLogin();
        $res=db('user_sync_login')->where('uid',$uid)->delete();
        if($res){
            Cache::rm('user_weixin_nickname'.$uid);
            $this->apiSuccess('微信解绑成功');
        }else{
            $this->apiError('微信解绑失败');
        }
    }

    protected $config = array(
        'url' => "https://api.weixin.qq.com/sns/jscode2session", //微信获取session_key接口url
        'appid' => '', // APPId
        'secret' => '', // 秘钥
        'grant_type' => 'authorization_code', // grant_type，一般情况下固定的
    );

    /**
     * 微信小程序登录
     * @author:qhy(qhy@ourstu.com)
     */
    public function MiniProgram()
    {
        
        try
        {
            $code = input('post.code', '', 'text');
            $nickname = input('post.nickname', '', 'text');
            $inviteCode = input('post.invite_code', '','text');
            $avatar = input('post.avatar', '', 'text');
            $sex = input('post.sex', '', 'text');
            $platform = input('post.platform', '4','text');
            $appId = SystemConfig::getValue('routine_appId');
            $appSecret = SystemConfig::getValue('routine_appsecret');
            if($code=='' || $nickname=='')
            {
                throw new Exception('当前参数不完整'.$nickname);
            }
            $params = array(
                'appid' => $appId,
                'secret' => $appSecret,
                'js_code' => $code,
                'grant_type' => $this->config['grant_type']
            );
            /* 获取请求信息 */
            $info = $this->checkLogin($params);
            $info=json_decode($info,true);
            //如果小程序openid存在open_id，调整回去
            $is_old_openid=db('user_sync_login')->where('open_id',$info['openid'])->find();
            if($is_old_openid){
                $map['mini_open_id']=$info['openid'];
                $map['open_id']='';
                
                db('user_sync_login')->where('open_id',$info['openid'])->update($map);
            }
            if(!empty($info['unionid'])){
                $is_unionid = db('user_sync_login')->where('type_uid',$info['unionid'])->find();//用unionid查找数据 qhy
                if (!$is_unionid){
                    $is_openid = db('user_sync_login')->where('mini_open_id',$info['openid'])->find();//如果unionid没查到数据，则用openid查找 qhy
                    if($is_openid){
                        if ($is_openid['type_uid']!=$info['unionid']) {//如果unionid不同，则更新unionid qhy
                            $data['type_uid'] = $info['unionid'];
                            $data['oauth_token_secret'] = $info['unionid'];
                            $data['mini_open_id'] = $info['openid'];
                            db('user_sync_login')->where('mini_open_id',$info['openid'])->update($data);
                        }
                        if($is_openid['is_update']==0){//老数据更新信息
                            $data['type_uid'] = $info['unionid'];
                            $data['oauth_token_secret'] = $info['unionid'];
                            $data['mini_open_id'] = $info['openid'];
                            $data['is_update'] = 1;
                            db('user_sync_login')->where('mini_open_id',$info['openid'])->update($data);
                        }
                        $uid=$is_openid['uid'];
                    }else{
                        $uid=null;
                    }
                }else{
                    if(!$is_unionid['mini_open_id']){
                        $data['mini_open_id'] = $info['openid'];
                        db('user_sync_login')->where('type_uid',$info['unionid'])->update($data);
                    }
                    if($is_unionid['is_update']==0){//老数据更新信息
                        $data['type_uid'] = $info['unionid'];
                        $data['oauth_token_secret'] = $info['unionid'];
                        $data['mini_open_id'] = $info['openid'];
                        $data['is_update'] = 1;
                        db('user_sync_login')->where('type_uid',$info['unionid'])->update($data);
                    }
                    $uid=$is_unionid['uid'];
                }
            }else{
                $uid = db('user_sync_login')->where('mini_open_id',$info['openid'])->value('uid');
            }
            $info['unionid'] = !empty($info['unionid']) ? $info['unionid'] : '';
            if (!$uid) {
                $user_info['name'] = $nickname;
                $user_info['nick'] = $nickname;
                $user_info['head'] = $avatar;
                $user_info['sex'] = $sex;
                $uid = $this->addDataProgram($user_info,$info);
             
    
                $tui_uids = RecommendModel::where('attention','1')->column('uid');
                    
                    foreach($tui_uids as $k => $v){
                        $datass = ['uid' => $uid, 'follow_uid' => $v,'create_time'=>time()];
                        db('user_follow')->insert($datass);
                    }
                    $ids_count = count($tui_uids);
                    $datas = ['follow' => $ids_count];
                    UserModel::where('uid',$uid)->update($datas);
                $fids=db('com_forum')->where('default_follow',1)->where('status',1)->column('id');
                foreach($fids as &$val){
                    $data['uid']=$uid;
                    $data['status']=1;
                    $data['create_time']=time();
                    $data['fid']=$val;
                    db('com_forum_member')->insert($data);
                }
                unset($val);
    
                if(isset($inviteCode)&&$inviteCode!='')
                {
                    //邀请记录添加
                    InviteCode::addInviteLog($inviteCode,$uid);
                }
                
                //注册加分
                Gong::actionadd('zhuce','com_post','uidflag',$uid);
                $data = [
                    'uid' => $uid,
                    'platform'=>$platform,
                    'reg_time' => time(),
                ];
                db('stat_reg_info')->insert($data);
            }
            else{
            //    $this->bindData($uid,$info);       
                // UserModel::update(['nickname'=>$nickname,'avatar'=>$avatar],['uid'=>$uid]);
                db('user_sync_login')->where('uid',$uid)->update(['oauth_token'=>$info['session_key']]);
            }
            $res = UserModel::doQuickLogin($uid); //登陆
            if ($res) {
                if($res['status']==0)
                {
                    $this->apiError("您已被禁止登陆");
                }
                // $cache_key = md5(time().$code);
                // Cache::set('eb_api_code_'.$cache_key, $info['session_key'], 86400);
                // $res['cache_key']=$cache_key;
                $this->apiSuccess('微信登录成功', $res);
            } else {
                $this->apiError('微信登录失败');
            }
        }catch(GlobalException $ex)
        {
            $this->apiError('微信登录失败',$ex->getMessage());
        }
        

    }

    /**
     * 获取openid 参数准备
     * @param $code
     * @return mixed
     */
    private function checkLogin($params) {
        /**
         * 这是一个 HTTP 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。其中 session_key 是对用户数据进行加密签名的密钥。
         * 为了自身应用安全，session_key 不应该在网络上传输。
         * 接口地址："https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code"
         */
        $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$params['appid'].'&secret='.$params['secret'].'&js_code='.$params['js_code'].'&grant_type=authorization_code';
        $res = $this->_requestPost($url, $params);
        return $res;
    }

    //post 提交
    private function _requestPost($url, $data, $ssl = true) {
        //curl完成
        $curl = curl_init();
        //设置curl选项
        curl_setopt($curl, CURLOPT_URL, $url);//URL
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

        //SSL相关
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
        }
        // 处理post相关选项
        //curl_setopt($curl, CURLOPT_POST, true);// 是否为POST请求
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);// 处理请求数据
        // 处理响应结果
        curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果

        // 发出请求
        $response = curl_exec($curl);
        if (false === $response) {
            echo '<br>', curl_error($curl), '<br>';
            return false;
        }
        curl_close($curl);
        return $response;
    }

    /**
     * 微信用户信息
     * @param $data
     * @return mixed
     * @author:lin(lt@ourstu.com)
     */
    private function wechat($data)
    {
        if (!isset($data['ret'])) {
            $userInfo['type'] = 'WEIXIN';
            $userInfo['name'] = $data['nickname'];
            $userInfo['nick'] = $data['nickname'];
            $userInfo['head'] = $data['headimgurl'];
            $userInfo['sex'] = $data['sex'] == '1' ? 0 : 1;
            return $userInfo;
        } else {
            $this->apiError("获取微信用户信息失败：{$data['errmsg']}");
        }
    }

    /**
     * 新增用户数据
     * @param $user_info
     * @return mixed
     * @author:lin(lt@ourstu.com)
     */
    private function addData($user_info,$userinfo)
    {
        $res = UserModel::addSyncData($user_info);
        // 记录数据到sync_login表中
        $this->addSyncLoginData($res['uid'],$userinfo);
        return $res['uid'];
    }


    /**
     * 绑定微信信息
     */
    private function bindData($uid,$userinfo)
    {
        // 记录数据到sync_login表中
        $res=$this->addSyncLoginData($uid,$userinfo);
        return $res;
    }

    private function addDataProgram($user_info,$info)
    {
        $res = UserModel::addSyncData($user_info);
        // 记录数据到sync_login表中
        $this->addSyncLoginDataProgram($res['uid'],$info);
        return $res['uid'];
    }

    /**
     * 记录sync_login表数据
     * @param $uid
     * @author:lin(lt@ourstu.com)
     */
    private static function addSyncLoginData($uid,$userinfo)
    {
        $data['uid'] = $uid;
        $data['type_uid'] = $userinfo['unionid'];
        $data['oauth_token'] = $userinfo['access_token'];
        $data['oauth_token_secret'] = $userinfo['unionid'];
        $data['open_id'] = isset($userinfo['openid'])?$userinfo['openid']:'';
        $data['app_open_id'] =  isset($userinfo['app_open_id'])?$userinfo['app_open_id']:'';
        $data['type'] = 'weixin';
        $data['is_sync'] = 1;
        $data['is_update'] = 1;
        $syncModel = db('user_sync_login');
        $map['uid'] = $uid;
        if (!$syncModel->where($map)->count()) {
            $res=$syncModel->insert($data);
            return $res;
        } else {
            $res=$syncModel->where($map)->update($data);
            return $res;
        }
    }

    private static function addSyncLoginDataProgram($uid,$info)
    {
        $data['uid'] = $uid;
        $data['type_uid'] = $info['unionid'];
        $data['oauth_token'] = $info['session_key'];
        $data['oauth_token_secret'] = $info['openid'];
        $data['mini_open_id'] = $info['openid'];
        $data['type'] = 'weixinProgram';
        $data['is_sync'] = 1;
        $data['is_update'] = 1;
        $syncModel = db('user_sync_login');
        $map['uid'] = $uid;
        if (!$syncModel->where($map)->count()) {
            $syncModel->insert($data);
        } else {
            $syncModel->where($map)->data($data)->update();
        }
    }

    /**
     * 微信分享
     */
    public function share(){
        $appId = SystemConfig::getValue('wechat_appid');
        $appSecret = SystemConfig::getValue('wechat_appsecret');
        $url=input('url','','');
        $jssdk = new JSSDK($appId,$appSecret,$url);
        $data['signPackage'] = $jssdk->GetSignPackage();
        $this->apiSuccess($data);
    }

    public function ceshi(){
        $a=Cache::get('ce_weixin');
        dump($a);
    }
    /**
     * 微信绑定手机号
     */
    public function phone(){
        $now_uid=$this->_needLogin();
        $aAccount = osx_input('post.phone', '');
        $inviteCode = osx_input('post.invite_code', '');
        $model = osx_input('post.model', 'mobile');
        $type = osx_input('post.type',1);
        /**解密 start**/
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $aAccount=trim(openssl_decrypt(base64_decode($aAccount),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
        /**解密 end**/
        if($model=='email'){
            $uid=db('user')->where('email',$aAccount)->find();
        }else{
            $uid=db('user')->where('phone',$aAccount)->find();
        }
        if(!$uid){
            if($model=='email'){
                $res=db('user')->where('uid',$now_uid)->update(['email' => $aAccount]);
            }else{
                $res=db('user')->where('uid',$now_uid)->update(['phone' => $aAccount]);
            }
            InviteCode::addInviteLog($inviteCode,$now_uid);
            if($res){
                //绑定手机号加分
                Gong::bindfirst('bangdingshouji',1) ;

                $this->apiSuccess('微信绑定手机号成功');
            }else {
                $this->apiError('微信绑定手机号失败');
            }
        } else {
            $is_bind=db('user_sync_login')->where('uid',$uid['uid'])->find();
            if($is_bind){
                if($type==1){
                    if($is_bind['open_id']){
                        if($model=='email'){
                            $this->apiError('该邮箱已经绑定过微信');
                        }else{
                            $this->apiError('该手机已经绑定过微信');
                        }
                    }
                    $now_openid=db('user_sync_login')->where('uid',$now_uid)->value('open_id');
                    $res=db('user_sync_login')->where('uid',$is_bind['uid'])->update(['open_id' => $now_openid]);
                }else{
                    if($is_bind['mini_open_id']){
                        $this->apiError('该手机已经绑定过小程序');
                    }
                    $now_openid=db('user_sync_login')->where('uid',$now_uid)->value('mini_open_id');
                    $res=db('user_sync_login')->where('uid',$is_bind['uid'])->update(['mini_open_id' => $now_openid]);
                }
            }else{
                $res=db('user_sync_login')->where('uid',$now_uid)->update(['uid' => $uid['uid']]);
            }
            if($res!==false){
                db('user')->where('uid',$now_uid)->delete();
                db('invite_level')->where('uid',$now_uid)->delete();
                $is_invite=db('invite_level')->where('uid',$now_uid)->find();
                if(!$is_invite){
                    InviteCode::addInviteLog($inviteCode,$uid['uid']);
                }
                $token = UserModel::doQuickLogin($uid['uid']);
                $token['message']='微信绑定手机号成功';
                //绑定手机号加分
                Gong::bindfirst('bangdingshouji',1) ;
                $this->apiSuccess($token);
            }else {
                $this->apiError('微信绑定手机号失败');
            }
        }

    }

    /**
     * 微信绑定生成手机验证码并发送
     */
    public function Verify()
    {
        if (is_post()) {
            $now_uid=$this->_needLogin();
            $account = input('post.phone', '', 'text');
            /**解密 start**/
            $iv = "1234567890123412";//16位 向量
            $key= '201707eggplant99';//16位 默认秘钥
            $account=trim(openssl_decrypt(base64_decode($account),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
            /**解密 end**/
            if (!isset($account)) {
                $this->apiError('请填写手机号');
            }
            $uid=UserModel::where('phone',$account)->value('uid');
            if($now_uid==$uid){
                $this->apiError('你已绑定该手机号！');
            }
            if($uid){
                $wx_uid=db('user_sync_login')->where('uid',$uid)->value('uid');
                if($wx_uid){
                    $this->apiError('该手机号已绑定微信账号！');
                }
            }
            $resend_time = modC('sms_resend_time', 60);
            if (time() <= Cache::get('verify_time_phone_ip'.get_client_ip()) + $resend_time) {
                $this->apiError('请勿重复获取验证码');
            }
            Cache::set('verify_time_phone_ip'.get_client_ip(), time());
            $aVerify = UserVerify::addData($account); //生成验证码
            if ($aVerify) {
                $sms_type = SystemConfig::getValue('sms_type');
                if($sms_type=='fg'){
                    $content = modC('sms_content');
                    $content = str_replace('{$verify}', $aVerify, $content); //根据短信模板添加验证码
                    $content = str_replace('{$account}', $account, $content); //根据短信模板添加手机号
                    $res = FlyPigeno::sendSMS($account, $content); //发送短信
                    if ($res===true) {
                        $this->apiSuccess('发送验证码成功');
                    } else {
                        Cache::rm('verify_time_phone_ip'.get_client_ip());
                        $this->apiError($res);
                    }
                }else{
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $content = str_replace('{s6}', $aVerify, $config['cl_sms_template']); //根据短信模板添加验证码
                    $content='【'.$config['cl_sms_sign'].'】'.$content;
                    // $this->apiSuccess($content);
                    $res = ChuanglanSmsApi::sendSMS($account,$content); //发送短信
                    $res=json_decode($res,true);
                    if ($res['code']==0) {
                        $this->apiSuccess('发送验证码成功');
                    } else {
                        Cache::rm('verify_time_phone_ip'.get_client_ip());
                        $this->apiError($res['errorMsg']);
                    }
                }
            } else {
                Cache::rm('verify_time_phone_ip'.get_client_ip());
                $this->apiError('生成验证码失败');
            }
        }
    }

    /**
     * 验证短信验证码
     */
    public function CheckVerify(){
        $aAccount = input('post.phone', '', 'text');
        $aRegVerify = input('post.quick_verify', '','text');
        /**解密 start**/
        $iv = "1234567890123412";//16位 向量
        $key= '201707eggplant99';//16位 默认秘钥
        $aAccount=trim(openssl_decrypt(base64_decode($aAccount),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
        /**解密 end**/
        //是否用户已经存在
        $is_exit=db('user')->where(['phone'=>$aAccount])->count();

        if (empty($aRegVerify)) {
            $this->apiError('请输入验证码');
        }
        $code=UserVerify::checkVerify($aAccount,'mobile', $aRegVerify);
        switch($code){
            case 1:
                $is_invite= db('system_config')->where(['menu_name'=>'invite_code'])->find();
                $need_invite= db('system_config')->where(['menu_name'=>'invite_code_need'])->find();
                $data['is_invite']=$is_exit?0:json_decode($is_invite['value']);
                $data['need_invite']=$is_exit?0:json_decode($need_invite['value']);
                $this->apiSuccess($data);
                break;
            case -1:
                $data['is_exit']=-1;
                $data['info']='短信验证码错误';
                $this->apiError($data);
                break;
            case -2:
                $data['is_exit']=-2;
                $data['info']='短信验证码已过期';
                $this->apiError($data);
                break;
        }
    }

    /**
     * 微信支付
     */
    public function wxPay(){
        require_once("../wxpay/WxPay.Api.php");
        $body=input('post.body', '', '通过微信在线支付');
        $order_sn=input('post.order_id', '', '');
        $order=db('store_order')->where('order_id',$order_sn)->find();
        $total_fee = $order['pay_price']*100;
        $notify_url = 'http://'.$_SERVER['HTTP_HOST'].'/osapi/Weixin/notify';
        $WxPayApi = new \WxPayApi;
        $input = new \WxPayUnifiedOrder;
        $input->SetBody($body);
        $input->SetOut_trade_no($order_sn);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 60*10));
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("APP");
        $data = $WxPayApi::unifiedOrder($input);
        $order_data = $WxPayApi->GetAppParameters($data);
        $this->apiSuccess($order_data);
    }

    public function notify()
    {
        require_once("../wxpay/WxPay.Data.php");
        $WxPay = new \WxPayResults();

        header('Content-type: text/xml');

        $returnResult = $GLOBALS['HTTP_RAW_POST_DATA'];

        //$res = $WxPay->FromXml($returnResult);
        $res = $WxPay::Init($returnResult);

        //支付成功
        if ($res['result_code'] == 'SUCCESS') {
            $data['paid']=1;
            $data['pay_type']='weixin';
            $data['pay_time']=time();
            $data['is_channel']=2;
            if(db('store_order')->where('order_id',$res['out_trade_no'])->count()){
                db('store_order')->where('order_id',$res['out_trade_no'])->update($data);
                if(db('sell_order')->where('order_id',$res['out_trade_no'])->where('order_status',4)->count()){
                    db('sell_order')->where('order_id',$res['out_trade_no'])->where('order_status',4)->update(['order_status'=>0]);//修改分销订单为已支付
                }
            }
            $success = array('return_code' => 'SUCCESS', 'return_msg' => 'OK');
            exit($this->ToXml($success));
        } else{
            // todo 返回错误信息记录表
        }
    }

    private function ToXml($data)
    {
        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    /*
     * 微信退款
     */
    private function refundWeixin($order){
        require_once("../wxpay/WxPay.Api.php");
        $pay = new \WxPayApi;
        $amount = $order['pay_price']*100;
        $desc = '退款';
        $number = time().create_rand(16,'num');
        $openid = Db::name('user_sync_login')->where(array('uid'=>$order['uid']))->field('open_id')->find();
        if(!$openid){
            return false;
        }
        $params = array(
            'partner_trade_no' => $number,
            'openid' => $openid['open_id'],
            'check_name' => 'NO_CHECK',
            'amount' => $amount,
            'desc' => $desc,
        );
        $toPay = $pay::payToUser($params);
        if($toPay["return_code"]=="SUCCESS"&&$toPay["result_code"]=="SUCCESS"){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 安卓登录
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public function app_weixin_login(){
        $invite_code = osx_input('invite_code','','text');
        $rt['access_token']=osx_input('access_token','','text');
        $rt['openid']=osx_input('openid','','text');
        //调用微信api
        $http = New ddhttp();
        // 拉取用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$rt['access_token']."&openid=".$rt['openid']."&lang=zh_CN ";

        $wechat_info = $http -> get($url);
        if(!$wechat_info)  $this->apiError('获取用户资料失败:CURL '.$http -> errmsg);

        $wechat_info = json_decode($wechat_info, 1);

        if(isset($wechat_info['errcode'])){
            $this->apiError("获取用户资料失败".$wechat_info['errmsg']);
        }

        $user_info = [
            "head"=>$wechat_info['headimgurl'],	//头像
            "name"=>$wechat_info['nickname'],	//昵称
            "nick"=>$wechat_info['nickname'],	//昵称
            "sex"=>$wechat_info['sex']== '1' ? 0 : 1,				//性别
            "openid"=>$wechat_info['openid'],		//app唯一
            "unionid"=>$wechat_info['unionid'],	//微信内部唯一，小程序， 公众号， web， 移动应用都是一致的
            'type'=>'WEIXIN',
            'access_token'=>$rt['access_token'],
        ];

        //判断是否绑定了微信开放平台 xsh
        if(!empty($user_info['unionid'])){
            $is_unionid = db('user_sync_login')->where('type_uid',$user_info['unionid'])->find();//用unionid查找数据 qhy
            if (!$is_unionid){
                $is_openid = db('user_sync_login')->where('app_open_id',$user_info['openid'])->find();//如果unionid没查到数据，则用openid查找 qhy
                if($is_openid){
                    if ($is_openid['type_uid']!=$user_info['unionid']) {//如果unionid不同，则更新unionid qhy
                        $data['type_uid'] = $user_info['unionid'];
                        $data['oauth_token_secret'] = $user_info['unionid'];
                        $data['app_open_id'] = $user_info['openid'];
                        db('user_sync_login')->where('open_id',$user_info['openid'])->update($data);
                    }
                    if($is_unionid['is_update']==0){//老数据更新信息
                        $data['type_uid'] = $user_info['unionid'];
                        $data['oauth_token_secret'] = $user_info['unionid'];
                        $data['app_open_id'] = $user_info['openid'];
                        $data['is_update'] = 1;
                        db('user_sync_login')->where('open_id',$user_info['openid'])->update($data);
                    }
                    $uid=$is_openid['uid'];
                }else{
                    $uid=null;
                }
            }else{
                if(!$is_unionid['open_id']){
                    $data['app_open_id'] = $user_info['openid'];
                    db('user_sync_login')->where('type_uid',$user_info['unionid'])->update($data);
                }
                if($is_unionid['is_update']==0){//老数据更新信息
                    $data['type_uid'] = $user_info['unionid'];
                    $data['oauth_token_secret'] = $user_info['unionid'];
                    $data['app_open_id'] = $user_info['openid'];
                    $data['is_update'] = 1;
                    db('user_sync_login')->where('type_uid',$user_info['unionid'])->update($data);
                }
                $uid=$is_unionid['uid'];
            }
        }else{
            $uid = db('user_sync_login')->where(['app_open_id|open_id'=>$user_info['openid']])->value('uid');
        }

        $user_info['unionid'] = !empty($user_info['unionid']) ? $user_info['unionid'] : '';

        if (!$uid) {
            $uid = $this->addData($user_info,$user_info);
            $tui_uids = RecommendModel::where('attention','1')->column('uid');
                
                foreach($tui_uids as $k => $v){
                    $datass = ['uid' => $uid, 'follow_uid' => $v,'create_time'=>time()];
                    db('user_follow')->insert($datass);
                }
                $ids_count = count($tui_uids);
                $datas = ['follow' => $ids_count];
                UserModel::where('uid',$uid)->update($datas);
            $fids=db('com_forum')->where('default_follow',1)->where('status',1)->column('id');
            foreach($fids as &$val){
                $data['uid']=$uid;
                $data['status']=1;
                $data['create_time']=time();
                $data['fid']=$val;
                db('com_forum_member')->insert($data);
            }
            unset($val);
            //新增微信授权层级 2019.10.25 zxh
            InviteCode::addInviteLog($invite_code,$uid);
        }
        $data = [
            'uid' => $uid,
            'platform'=>'android',
            'reg_time' => time(),
        ];
        db('stat_reg_info')->insert($data);
        $res = UserModel::doQuickLogin($uid); //登陆
        if ($res) {
            $this->apiSuccess('微信登录成功', $res);
        } else {
            $this->apiError('微信登录失败');
        }
    }

    public function head_login(){
        $invite_code = osx_input('invite_code','','text');
        $code = osx_input('code','','text');
        $anonymous_code = osx_input('anonymous_code','','text');
        $aNickname=osx_input('nickname','','text');
        $avatar=osx_input('avatar','','text');
        $sex=osx_input('sex',0,'intval');


        if(!$code&&!$anonymous_code){
            $this->apiError('code和anonymous_code');
        }

        if(!$aNickname){
            $this->apiError('请传入nickname');
        }
        if(!$avatar){
            $this->apiError('请传入头像');
        }
        $user=HeadLogin::head_login($code,$anonymous_code);
        if(array_key_exists('errcode',$user)){
            $this->apiError('登录错误:'.$user['errmsg']);
        }

        $uid=db('head_login')->where(['open_id'=>$user['openid']])->value('uid');
        if (!$uid) {
            $userInfo['type'] = 'Head';

            $userInfo['name'] = $aNickname;
            $userInfo['nick'] = $aNickname;

            if(!$avatar){
                $url=SystemConfig::getValue('default_avatar');
                $avatar=get_domain().$url;
            }

            $userInfo['head'] = $avatar;

            $userInfo['sex'] = $sex==1?1:0;
            $uid = UserModel::addSyncData($userInfo);
            $uid = $uid['uid'];
            db('head_login')->insert(['open_id'=>$user['openid'],'uid'=>$uid,'create_time'=>time()]);
            $fids=db('com_forum')->where('default_follow',1)->where('status',1)->column('id');
            foreach($fids as &$val){
                $data['uid']=$uid;
                $data['status']=1;
                $data['create_time']=time();
                $data['fid']=$val;
                db('com_forum_member')->insert($data);
            }
            unset($val);
            //新增微信授权层级 2019.10.25 zxh
            InviteCode::addInviteLog($invite_code,$uid);
        }
        $data = [
            'uid' => $uid,
            'platform'=>'android',
            'reg_time' => time(),
        ];
        db('stat_reg_info')->insert($data);
        $res = UserModel::doQuickLogin($uid); //登陆
        if ($res) {
            $this->apiSuccess('头条小程序登录成功', $res);
        } else {
            $this->apiError('头条小程序登录失败');
        }
    }

    /**
     * 获取信息流广告间隔
     */
    public function get_infomation_ad_config()
    {
        $open = SystemConfig::getValue('information_stream_open');
        if (intval($open) === 0) {
            $this->apiError("未开通小程序流量主广告功能！");
        }
        $data['min'] = (int)SystemConfig::getValue('information_stream_ad_min');
        $data['max'] = (int)SystemConfig::getValue('information_stream_ad_max');
        $this->apiSuccess($data);
    }

    /**
     * 观看激励视频广告加积分
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_reward_ad_jifen()
    {
        $open = SystemConfig::getValue('information_stream_open');
        if (intval($open) === 0) {
            $this->apiError("未开通小程序流量主广告功能！");
        }
        $id=osx_input('id',0,'intval');
        $uid = $this->_needLogin();
        $ad = $ads = db('routine_ad')->where('is_del', 0)->where('id', $id)->find();
        if (empty($ads)) {
            $this->apiError('该广告不存在');
        }
        if ($ad['ad_slot'] != 'SLOT_ID_WEAPP_REWARD_VIDEO' || (int)$ad['renwu_id'] <= 0) {
            $this->apiError('该广告不是激励视频广告');
        }
        $renwu = db('system_renwu')->where('id', $ad['renwu_id'])->find();
        if (empty($renwu)) {
            $this->apiError('添加积分失败');
        }
        // 判断是否达到了任务完成数量
        $count = Cache::get('reward_video_jifen_'.$uid);
        $count = $count ? ((int)$count+1) : 1;
        if ($count < (int)$renwu['require']) {
            Cache::rm('reward_video_jifen_'.$uid);
            Cache::set('reward_video_jifen_'.$uid, $count, (strtotime('tomorrow') - time()));
            $this->apiError('未达到任务完成数量！');
        } elseif ($count > (int)$renwu['require']) {
            $this->apiError('已完成今日任务！');
        } else {
            Cache::set('reward_video_jifen_'.$uid, $count, (strtotime('tomorrow') - time()));
        }
        $jifentype = db('system_rule')->where('status',1)->order('id asc')->select();
        $jifenArr = [];
        $log = [];
        foreach ($jifentype as $v) {
            if (isset($renwu[$v['flag']]) && (int)$renwu[$v['flag']] > 0) {
                $jifenArr[] = [
                    'name' => $v['name'],
                    'num' => (int)$renwu[$v['flag']]
                ];
                UserModel::where('uid', $uid)->setInc($v['flag'], (int)$renwu[$v['flag']]);
                $log[$v['flag']] = (int)$renwu[$v['flag']];
            }
        }
        unset($v);
        if (!empty($log)) {
            $log['zong'] = $count;
            Support::jiafenlog($uid, '观看激励视频广告', $log, 1, '任务');
        }
        $this->apiSuccess($jifenArr, '添加积分成功');
    }

    /**
     * 获取小程序流量主广告列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_routine_ad_list()
    {
        $open = SystemConfig::getValue('information_stream_open');
        if (intval($open) === 0) {
            $this->apiError("未开通小程序流量主广告功能！");
        }
        $list = [];
        $ads = db('routine_ad')->where('is_del', 0)->select();
        if (empty($ads)) {
            $this->apiSuccess($list);
        }
        foreach ($ads as $ad) {
            $data = [
                'id' => $ad['id'],
                'name' => $ad['name'],
                'ad_unit_id' => $ad['ad_unit_id'],
                'ad_slot' => $ad['ad_slot'],
                'status' => $ad['status'],
                'is_show' => $ad['is_show'],
                'remark' => $ad['remark']
            ];
            switch ($ad['ad_slot']) {
                case 'SLOT_ID_WEAPP_BANNER': //banner广告
                    $data['ad_type'] = db('routine_ad_position')->where('routine_ad_id', $ad['id'])->where('ad_type', '>', 0)->column('ad_type');
                    break;
                case 'SLOT_ID_WEAPP_REWARD_VIDEO': // 激励式广告
                    break;
                case 'SLOT_ID_WEAPP_INTERSTITIAL': // 插屏广告
                    $data['trigger_gap'] = $ad['trigger_gap'];
                    $data['trigger_scene'] = db('routine_ad_position')->where('routine_ad_id', $ad['id'])->where('trigger_scene', '>', 0)->column('trigger_scene');
                    break;
                case 'SLOT_ID_WEAPP_VIDEO_FEEDS': //视频广告
                    $data['ad_theme'] = $ad['ad_theme'] == 1 ? 'white' : 'black';
                    break;
                case 'SLOT_ID_WEAPP_VIDEO_BEGIN': // 视频贴片广告
                    break;
                case 'SLOT_ID_WEAPP_BOX': // 格子广告
                    $data['ad_type'] = db('routine_ad_position')->where('routine_ad_id', $ad['id'])->column('ad_type');
                    $data['ad_theme'] = $ad['ad_theme'] == 1 ? 'white' : 'black';
                    $data['grid_count'] = $ad['grid_count'];
                    break;
                case 'SLOT_ID_WEAPP_TEMPLATE': //原生模板广告
                    $data['position'] = $ad['position'];
                    break;
                default:
                    break;
            }
            $list[] = $data;
        }
        unset($ad);
        $this->apiSuccess($list);
    }
}