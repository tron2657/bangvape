<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/06
 */

namespace service;

use app\wap\model\user\WechatUser;
use app\admin\model\wechat\WechatTemplate as WechatTemplateModel;
use app\admin\model\wechat\StoreService as ServiceModel;

class WechatTemplateService
{
    /**
     * 主营行业：IT科技 互联网|电子商务
     * 副营行业：IT科技 IT软件与服务
     */

    //用户登录提醒
    const USER_LOGIN = 'TM00181';

    //下单成功通知
    const ORDER_PAY_SUCCESS = 'OPENTM412865802';

    //订单发货通知
    const ORDER_POSTAGE_SUCCESS = 'OPENTM410578602';

    //订单取消通知
    const ORDER_CANCEL = 'OPENTM201490123';

    //退款通知
    const REFUND = 'OPENTM411665300';

    //签到成功通知
    const CHICK_IN = 'OPENTM412181252';

    //退款成功通知
    const REFUND_SUCCESS = 'OPENTM414889350';

    //客服通知提醒
    //const SERVICE_NOTICE = 'OPENTM204431262';

    //服务进度提醒
    //const ADMIN_NOTICE = 'OPENTM408237350';

    //拼团成功通知
    //const ORDER_USER_GROUPS_SUCCESS = 'OPENTM407456411';

    //拼团失败通知
    //const ORDER_USER_GROUPS_LOSE   = 'OPENTM401113750';

    public static function sendTemplate($openid,$templateId,array $data,$url = null,$defaultColor = '')
    {
        $tempid = WechatTemplateModel::where('tempkey',$templateId)->where('status',1)->value('tempid');
        if(!$tempid) return false;
        try{
            return WechatService::sendTemplate($openid,$tempid,$data,$url,$defaultColor);
        }catch (\Exception $e){
            return false;
        }
    }

    /**服务进度通知
     * @param array $data
     * @param null $url
     * @param string $defaultColor
     * @return bool
     */
    public static function sendAdminNoticeTemplate(array $data,$url = 'pages/index/index',$defaultColor = '')
    {
        $adminIds = explode(',',trim(SystemConfigService::get('site_store_admin_uids')));
        $kefuIds = ServiceModel::where('notify',1)->column('uid');
        if(empty($adminIds[0])){
            $adminList = array_unique($kefuIds);
        }else{
            $adminList = array_unique(array_merge($adminIds,$kefuIds));
        }
        if(!is_array($adminList) || empty($adminList)) return false;
        foreach ($adminList as $uid){
            try{
                $openid = WechatUser::uidToOpenid($uid);
            }catch (\Exception $e){
                continue;
            }
            self::sendTemplate($openid,self::ADMIN_NOTICE,$data,$url,$defaultColor);
        }
    }

    /**
     * 返回所有支持的行业列表
     * @return \EasyWeChat\Support\Collection
     */
    public static function getIndustry()
    {
        return WechatService::noticeService()->getIndustry();
    }

    /**
     * 修改账号所属行业
     * 主行业	副行业	代码
     * IT科技	互联网/电子商务	1
     * IT科技	IT软件与服务	2
     * IT科技	IT硬件与设备	3
     * IT科技	电子技术	4
     * IT科技	通信与运营商	5
     * IT科技	网络游戏	6
     * 金融业	银行	7
     * 金融业	基金|理财|信托	8
     * 金融业	保险	9
     * 餐饮	餐饮	10
     * 酒店旅游	酒店	11
     * 酒店旅游	旅游	12
     * 运输与仓储	快递	13
     * 运输与仓储	物流	14
     * 运输与仓储	仓储	15
     * 教育	培训	16
     * 教育	院校	17
     * 政府与公共事业	学术科研	18
     * 政府与公共事业	交警	19
     * 政府与公共事业	博物馆	20
     * 政府与公共事业	公共事业|非盈利机构	21
     * 医药护理	医药医疗	22
     * 医药护理	护理美容	23
     * 医药护理	保健与卫生	24
     * 交通工具	汽车相关	25
     * 交通工具	摩托车相关	26
     * 交通工具	火车相关	27
     * 交通工具	飞机相关	28
     * 房地产	建筑	29
     * 房地产	物业	30
     * 消费品	消费品	31
     * 商业服务	法律	32
     * 商业服务	会展	33
     * 商业服务	中介服务	34
     * 商业服务	认证	35
     * 商业服务	审计	36
     * 文体娱乐	传媒	37
     * 文体娱乐	体育	38
     * 文体娱乐	娱乐休闲	39
     * 印刷	印刷	40
     * 其它	其它	41
     * @param $industryId1
     * @param $industryId2
     * @return \EasyWeChat\Support\Collection
     */
    public static function setIndustry($industryId1, $industryId2)
    {
        return WechatService::noticeService()->setIndustry($industryId1, $industryId2);
    }

    /**
     * 获取所有模板列表
     * @return \EasyWeChat\Support\Collection
     */
    public static function getPrivateTemplates()
    {
        return WechatService::noticeService()->getPrivateTemplates();
    }

    /**
     * 删除指定ID的模板
     * @param $templateId
     * @return \EasyWeChat\Support\Collection
     */
    public static function deletePrivateTemplate($templateId)
    {
        return WechatService::noticeService()->deletePrivateTemplate($templateId);
    }


    /**
     * 添加模板并获取模板ID
     * @param $shortId
     * @return \EasyWeChat\Support\Collection
     */
    public static function addTemplate($shortId)
    {
        return WechatService::noticeService()->addTemplate($shortId);
    }
}