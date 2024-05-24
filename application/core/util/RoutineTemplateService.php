<?php
namespace app\core\util;

use app\core\model\routine\RoutineServer;//待完善
use think\Db;
use app\core\implement\ProviderInterface;
use app\core\implement\TemplateInterface;
/**
 * 小程序模板消息
 * Class RoutineTemplate
 * @package app\routine\model\routine
 */
class RoutineTemplateService implements ProviderInterface,TemplateInterface
{
    //帖子审核
    const AUDIT_THREAD=8517;
    //帖子分享
    const THREAD_SHARE=4190;
    //帖子收藏
    const THREAD_COLLECT=3540;
    //帖子点赞
    const THREAD_SUPPORT=10173;
    //删除评论
    const POST_DEL=11671;
    //新评论提醒
    const NEW_POST=484;
    //评论回复通知
    const POST_REPLY=3206;
    //评论点赞
    const POST_SUPPORT=9007;
    //加入版块成功提醒
    const FORUM_ADD=1905;
    //加入版块拒绝提醒
    const FORUM_REFUSE=6897;
    //举报结果通知
    const REPORT_RESULT=7937;
    //被举报通知
    const HAVE_REPORT=5989;
    //认证审核通知
    const CERTIFICATION_ENTITY=3264;
    //邀请注册成功通知
    const INVITE_REGISTER=5710;
    //提现审核通知
    const CASH_OUT=1883;
    //分销审核通知
    const AGENT_MANAGE=3576;
    //发货通知
    const DELIVER_GOODS=1417;
    //未支付提醒
    const NO_PAY=1885;
    //退款提醒
    const REFUND_PAY=5592;
    //支付成功提醒
    const REFUND_SUCCESS=2027;
    //取消订单提醒
    const ORDER_CANCEL=7456;
    //拼团成功通知
    const PINK_SUCCESS=3574;
    //拼团失败通知
    const PINK_ERROR=3577;
    //优惠券到期
    const COUPON_EXPIRE=10540;
    //会员到期
    const MEMBER_SHIP_EXPIRE=8571;
    //活动通知
    //签到成功通知
    const CHECK_IN=569;

    public function register($config)
    {
        return ['routine',new self()];
    }

    /**
     * 根据模板编号获取模板ID
     * @param string $tempKey
     * @return mixed|string
     */
    public static function setTemplateId($tempKey = ''){
        if($tempKey == '')return '';
        return Db::name('RoutineTemplate')->where('tempkey',$tempKey)->where('status',1)->value('tempid');
    }
    /**服务进度通知
     * @param array $data
     * @param null $url
     * @param string $defaultColor
     * @return bool
     */
    public static function sendAdminNoticeTemplate(array $data,$url = null,$defaultColor = '')
    {
//        $adminIds = explode(',',trim(SystemConfigService::get('site_store_admin_uids')));
//        $kefuIds = ServiceModel::where('notify',1)->column('uid');
//        if(empty($adminIds[0])){
//            $adminList = array_unique($kefuIds);
//        }else{
//            $adminList = array_unique(array_merge($adminIds,$kefuIds));
//        }
//        if(!is_array($adminList) || empty($adminList)) return false;
//        foreach ($adminList as $uid){
//            try{
//                $openid = WechatUser::uidToOpenid($uid);
//            }catch (\Exception $e){
//                continue;
//            }
//            self::sendTemplate($openid,self::ADMIN_NOTICE,$data,$url,$defaultColor);
//        }
    }
    /**
     * 获取小程序模板库所有标题列表
     * @param string $accessToken
     * @param int $offset
     * @param int $count
     * @return mixed
     */
    public static function getTemplateListAll($offset = 0,$count = 20){
        $accessToken = RoutineServer::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list?access_token=".$accessToken;
        $data['access_token'] = $accessToken;
        $data['offset'] = $offset;
        $data['count'] = $count;
        return json_decode(RoutineServer::curlPost($url,json_encode($data)),true);
    }

    /**
     * 获取模板库某个模板标题下关键词库
     * @param string $templateId    模板ID 未添加之前的ID
     * @return mixed
     */
    public static function getTemplateKeyword($templateId = 'AT0005'){
        $accessToken = RoutineServer::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token=".$accessToken;
        $data['access_token'] = $accessToken;
        $data['id'] = $templateId;
        return json_decode(RoutineServer::curlPost($url,json_encode($data)),true);
    }

    /**
     * 获取小程序模板库申请的标题列表
     * @param int $offset
     * @param int $count
     * @return mixed
     */
    public static function getTemplateList($offset = 0,$count = 20){
        $accessToken = RoutineServer::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=".$accessToken;
        $data['access_token'] = $accessToken;
        $data['offset'] = $offset;
        $data['count'] = $count;
        return json_decode(RoutineServer::curlPost($url,json_encode($data)),true);
    }

    /**
     * 删除小程序中的某个模板消息
     * @param string $templateId
     * @return bool|mixed
     */
    public static function delTemplate($templateId = ''){
        if($templateId == '') return false;
        $accessToken = RoutineServer::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/del?access_token=".$accessToken;
        $data['access_token'] = $accessToken;
        $data['template_id'] = $templateId;
        return json_decode(RoutineServer::curlPost($url,json_encode($data)),true);
    }

    /**
     * 发送模板消息
     * @param string $openId   接收者（用户）的 openid
     * @param string $templateId 所需下发的模板消息的id
     * @param string $link 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
     * @param string $formId 表单提交场景下，为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id
     * @param array $dataKey 模板内容，不填则下发空模板
     * @param string $emphasisKeyword 模板需要放大的关键词，不填则默认无放大
     * @return bool|mixed
     */
    public static function sendTemplate($openId = '',$templateId = '',$dataKey = array(),$formId = '',$link = '',$emphasisKeyword = ''){
        if($openId == '' || $templateId == '') return false;
        $tempid=db('wechat_routine_template')->where('tempkey',$templateId)->where('status',1)->value('tempid');
        if(!$tempid) return false;
        $accessToken = RoutineServer::get_access_token();
        if (!$accessToken) return false;
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$accessToken;
        $data['touser'] =  $openId;//接收者（用户）的 openid
        $data['template_id'] =  $tempid; //所需下发的模板消息的id
        $data['page'] =  $link; //点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
        $data['data'] =  $dataKey;  //模板内容，不填则下发空模板
        $res=json_decode(RoutineServer::curlPost($url,json_encode($data)),true);
        return $res;
    }

    public static function getConstants($code='') {
        $oClass = new \ReflectionClass(__CLASS__);
        $stants=$oClass->getConstants();
        if($code) return isset($stants[$code]) ? $stants[$code] : '';
        else return $stants;
    }
}