<?php


namespace app\admin\controller;

class SystemBasic extends \basic\ControllerBasic
{
    protected function _initialize()
    {
        $config = \app\admin\model\system\SystemConfig::getMore("ip_white,domain_white");
        $ip_list = explode("<br />\r\n", nl2br($config["ip_white"]));
        if (0 < count($ip_list) && trim($ip_list[0]) != "" && !in_array(get_client_ip(), $ip_list)) {
            $this->error("访问地址不存在！");
        }
        $domain_list = explode("<br />\r\n", nl2br($config["domain_white"]));
        if (0 < count($domain_list) && trim($domain_list[0]) != "" && !in_array(get_domain(), $domain_list)) {
            $this->error("访问地址不存在！");
        }
    }
    protected function failedNotice($msg = "操作失败", $backUrl = 0, $info = "", $duration = 3)
    {
        $type = "error";
        $this->assign(compact("msg", "backUrl", "info", "duration", "type"));
        return $this->fetch("public/notice");
    }
    protected function failedNoticeLast($msg = "操作失败", $backUrl = 0, $info = "")
    {
        return $this->failedNotice($msg, $backUrl, $info, 0);
    }
    protected function successfulNotice($msg = "操作成功", $backUrl = 0, $info = "", $duration = 3)
    {
        $type = "success";
        $this->assign(compact("msg", "backUrl", "info", "duration", "type"));
        return $this->fetch("public/notice");
    }
    protected function failNotice($msg = "操作成功", $backUrl = 0, $info = "", $duration = 3)
    {
        $type = "error";
        $this->assign(compact("msg", "backUrl", "info", "duration", "type"));
        return $this->fetch("public/notice");
    }
    protected function successfulNoticeLast($msg = "操作成功", $backUrl = 0, $info = "")
    {
        return $this->successfulNotice($msg, $backUrl, $info, 0);
    }
    protected function failed($msg = "哎呀…亲…您访问的页面出现错误", $url = 0)
    {
        if ($this->request->isAjax()) {
            exit(\service\JsonService::fail($msg, $url)->getContent());
        }
        $this->assign(compact("msg", "url"));
        exit($this->fetch("public/error"));
    }
    protected function successful($msg, $url = 0)
    {
        if ($this->request->isAjax()) {
            exit(\service\JsonService::successful($msg, $url)->getContent());
        }
        $this->assign(compact("msg", "url"));
        exit($this->fetch("public/success"));
    }
    protected function exception($msg = "无法打开页面")
    {
        $this->assign(compact("msg"));
        exit($this->fetch("public/exception"));
    }
    public function _empty($name)
    {
        exit($this->fetch("public/404"));
    }
    public function show_tip_page_list()
    {   $this->assign("is_free_ban", true);
        $this->assign("is_end_ban", true);
        $open_list = $this->_getClientOpenList();
        $this->assign("open_list", $open_list);
        return;
        $data = \service\UtilService::getMore([["is_submission", ""]]);
        if ($data["is_submission"]) {
            cache("is_submission", $data["is_submission"], 259200);
        }
        $list = $this->_getShowTipPageList();
        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        $is_free_ban = true;
        foreach ($list as $v) {
            if (strpos($url, $v) !== false) {
                $is_free_ban = false;
            }
        }
        unset($v);
        $this->assign("is_free_ban", $is_free_ban);
        $end_ban = $this->_getEndShowTipPageList();
        $is_end_ban = true;
        foreach ($end_ban as $v) {
         
            if (strpos($url, $v) !== false) {
                $is_end_ban = false;
            }
        }
        unset($v);
        $this->assign("is_end_ban", $is_end_ban);
        $open_list = $this->_getClientOpenList();
        $this->assign("open_list", $open_list);
        $is_register = $this->_getIsRegister();
        $is_submission = cache("is_submission");
        $siteCode = $this->_getCode(0);
        $this->assign("site_code", $siteCode);
        if (!$is_register && !$is_submission) {
            // $this->redirect("https://h5a.opensns.cn/auth/index/register_tips/code/" . $siteCode);
        }
        $site_order = $this->_getSitOrder();
        $this->assign("site_order", $site_order);
    }
}

?>