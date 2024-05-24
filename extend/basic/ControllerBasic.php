<?php


namespace basic;

use app\admin\controller\setting\SystemConfig;
use app\admin\model\system\SystemConfig as SystemSystemConfig;
use service\SystemConfigService;

class ControllerBasic extends \think\Controller implements ControllerInterface
{
    protected $official_auth_list = NULL;
    public function __construct(\think\Request $request = NULL)
    {
        
        parent::__construct($request);
        $this->_checkOfficialAuth();
        $this->check_platform();
        $this->_check_extend_auth();
    }
    protected function _initialize()
    {
        if (request()->method() == "OPTIONS") {
            exit;
        }
        parent::_initialize();
        $token = request()->server("HTTP_ACCESS_TOKEN");
        if ($token == "" || $token == NULL) {
            $token = request()->post("token");
        }
        $this->user_token = $token;
    }

 

    protected function _needLogin()
    {
        $uid = get_uid();
        if (!$uid) {
            $return["need_login"] = 1;
            $return["info"] = "请先登录";
            $this->apiError($return);
        }
        return $uid;
    }
    protected function apiFailed($data = [], $msg = "fail")
    {
        return \service\JsonService::fail($msg, $data);
    }
    protected function apiError($data = [], $msg = "error")
    {
        if (!is_string($msg)) {
            $data["info"] = $msg;
            $msg = "error";
        }
        return \service\JsonService::success($msg, $data);
    }
    protected function apiSuccess($data = [], $msg = "ok")
    {
        return \service\JsonService::success($msg, $data);
    }
    protected function oauth()
    {
        $openid = \think\Session::get("loginOpenid", "wap");
        if ($openid) {
            return $openid;
        }
        if (!\service\UtilService::isWechatBrowser()) {
            exit($this->failed("请在微信客户端打开链接"));
        }
        if ($this->request->isAjax()) {
            exit($this->failed("请登陆!"));
        }
        $errorNum = (array) \think\Cookie::get("_oen");
        if ($errorNum && 3 < $errorNum) {
            exit($this->failed("微信用户信息获取失败!!"));
        }
        try {
            $wechatInfo = \service\WechatService::oauthService()->user()->getOriginal();
            if (!isset($wechatInfo["nickname"])) {
                $wechatInfo = \service\WechatService::getUserInfo($wechatInfo["openid"]);
                if (!$wechatInfo["subscribe"] && !isset($wechatInfo["nickname"])) {
                    exit(\service\WechatService::oauthService()->scopes(["snsapi_userinfo"])->redirect($this->request->url(true))->send());
                }
                if (isset($wechatInfo["tagid_list"])) {
                    $wechatInfo["tagid_list"] = implode(",", $wechatInfo["tagid_list"]);
                }
            } else {
                if (isset($wechatInfo["privilege"])) {
                    unset($wechatInfo["privilege"]);
                }
                $wechatInfo["subscribe"] = 0;
            }
            \think\Cookie::delete("_oen");
            $openid = $wechatInfo["openid"];
            \service\HookService::afterListen("wechat_oauth", $openid, $wechatInfo, false, "behavior\\wechat\\UserBehavior");
            \think\Session::set("loginOpenid", $openid, "wap");
            \think\Cookie::set("is_login", 1);
            return $openid;
        } catch (\Exception $e) {
            \think\Cookie::set("_oen", ++$errorNum, 900);
            exit(\service\WechatService::oauthService()->scopes(["snsapi_base"])->redirect($this->request->url(true))->send());
        }
    }
    private function _checkOfficialAuth()
    {
      
        $module_name = $this->request->module();
        $controller_name = $module_name . "/" . $this->request->controller();
        $action_name = $controller_name . "/" . $this->request->action();
        if ($module_name == "auth") {
            return true;
        }
        if ($action_name == "osapi/Base/giveauth") {
            return true;
        }
        $tip_content = "本系统由独角鲸软件提供，为确保您能正常使用，请先联系   联系电话： 18163791243 ";
   
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            if (is_file($auth_file_path)) {
                $content = file_get_contents($auth_file_path);
                $auth_all_list = $this->_dealAuthContent($content);
                \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
            } else {
                return \service\JsonService::fail($tip_content);
            }
        }
        $auth_all_list = json_decode($auth_all_list, true);
        // if ($auth_all_list["valid_end"] < time()) {
        //     $content = $this->giveAuth(1);
        //     if ($content) {
        //         $auth_all_list = $this->_dealAuthContent($content);
        //         $auth_all_list = json_decode($auth_all_list, true);
        //         if ($auth_all_list["valid_end"] < time()) {
        //             return \service\JsonService::fail($tip_content);
        //         }
        //     } else {
        //         return \service\JsonService::fail($tip_content);
        //     }
        // }
        if (time() < $auth_all_list["valid_end"] && $auth_all_list["valid_end"] < time() - 3600) {
            $tag = "OFFICIAL_AUTH_CHECK_SEND";
            if (!\think\Cache::get($tag)) {
                $this->giveAuth(1);
                \think\Cache::set($tag, 1, 600);
            }
        }
        if (in_array($module_name, $auth_all_list["forbidden_list"]) || in_array($controller_name, $auth_all_list["forbidden_list"]) || in_array($action_name, $auth_all_list["forbidden_list"])) {
            return \service\JsonService::fail($tip_content);
        }
        $auth_list = $auth_all_list["open_list"];
        $official_module = $this::get_official_module();
        if (!in_array($module_name, $official_module)) {
            return true;
        }
        if (isset($auth_list[$module_name]) && $auth_list[$module_name]["auth"] && ($auth_list[$module_name]["end_time"] == "forever" || time() < $auth_list[$module_name]["end_time"])) {
            return true;
        }
        if (isset($auth_list[$controller_name]) && $auth_list[$controller_name]["auth"] && ($auth_list[$controller_name]["end_time"] == "forever" || time() < $auth_list[$controller_name]["end_time"])) {
            return true;
        }
        if (isset($auth_list[$action_name]) && $auth_list[$action_name]["auth"] && ($auth_list[$action_name]["end_time"] == "forever" || time() < $auth_list[$action_name]["end_time"])) {
            return true;
        }
        return true;
        return \service\JsonService::fail($tip_content);
    }
    public function giveAuth($no_out = 0)
    {
        // if (function_exists("curl_init")) {
        //     $data = \service\UtilService::getMore([["auth_get_code", ""]]);
        //     $code = $data["auth_get_code"];
        //     if ($code == "") {
        //         $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
        //         if (!is_file($auth_file_path)) {
        //             if ($no_out) {
        //                 return false;
        //             }
        //             header("content-type:text/html;charset=utf-8");
        //             exit("权限校验失败-file not exit");
        //         }
        //         $content = file_get_contents($auth_file_path);
        //         $content = base64_decode($content);
        //         $string_content = substr($content, 16, strlen($content) - 32);
        //         $iv_r = substr($content, 0, 16);
        //         $key_r = substr($content, strlen($content) - 16, 16);
        //         $auth_all_list = json_decode(openssl_decrypt(base64_decode($string_content), "AES-128-CBC", $key_r, OPENSSL_RAW_DATA, $iv_r), true);
        //         if (!isset($auth_all_list["code"])) {
        //             if ($no_out) {
        //                 return false;
        //             }
        //             header("content-type:text/html;charset=utf-8");
        //             exit("权限校验失败- code false");
        //         }
        //         $code = $auth_all_list["code"];
        //     }
        //     if ($code == "") {
        //         if ($no_out) {
        //             return false;
        //         }
        //         header("content-type:text/html;charset=utf-8");
        //         exit("权限校验失败- no code");
        //     }
        //     $url = "https://h5a.opensns.cn/auth/Index/getAuthCode";
        //     $iv = "" . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999);
        //     $strs = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
        //     $key = substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 17), 16);
        //     $code_str = openssl_encrypt($code . time(), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
        //     $code_str = base64_encode($iv . $code_str . $key);
        //     $a = db("user")->where(["status" => 1])->count();
        //     $version_file = ROOT . "/version.php";
        //     if (!is_file($version_file)) {
        //         $version = "no-found";
        //     } else {
        //         $version = file_get_contents($version_file);
        //     }
        //     $params = http_build_query(["code" => $code_str, "a" => $a, "b" => base64_encode(get_domain()), "v" => base64_encode($version)]);
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //     curl_setopt($ch, CURLOPT_HEADER, 0);
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        //     $result = curl_exec($ch);
        //     $result = json_decode($result, true);
        //     curl_close($ch);
        //     if ($result["code"] == 200) {
        //         $content = $result["data"]["auth_code"];
        //         $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
        //         $res = file_put_contents($auth_file_path, $content);
        //         if ($res) {
        //             \think\Cache::rm($this->official_auth_list);
        //             \app\admin\model\system\SystemConfig::setValue("client_local_storage_version", time_format(time(), "Y-m-d-H-i"));
        //         }
        //         if ($no_out) {
        //             return $content;
        //         }
        //         header("content-type:text/html;charset=utf-8");
        //         exit("已获得来自 想天软件 的权限校验");
        //     }
        //     if ($no_out) {
        //         return false;
        //     }
        //     header("content-type:text/html;charset=utf-8");
        //     exit("权限校验失败-end  " . $result["msg"]);
        // }
        // if ($no_out) {
        //     return false;
        // }
        // header("content-type:text/html;charset=utf-8");
        // echo "汗！貌似您的服务器尚未开启curl扩展，无法连接想天软件进行授权校验，请联系您的主机商开启，本地调试请无视";
        // exit;
    }
    protected function _getHideAdminMenu()
    {
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["hide_menu"]) ? $auth_all_list["hide_menu"] : [];
    }
    protected function _getShowTipPageList()
    {
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["show_tip_page_list"]) ? $auth_all_list["show_tip_page_list"] : [];
    }
    protected function _getEndShowTipPageList()
    { 
        global $v;
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $end_show_tip_page_list = [];
        $auth_all_list = json_decode($auth_all_list, true);
        if (array_key_exists("end_show_tip_page_list", $auth_all_list)) {
            if ($v["end_time"] < time()) {
                $end_show_tip_page_list[] = $v["menus"];
            }
            unset($v);
        }
        return $end_show_tip_page_list;
    }
    protected function _getIsRegister()
    {
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["is_register"]) ? $auth_all_list["is_register"] : [];
    }
    protected function _getSitOrder()
    {
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["site_order"]) ? $auth_all_list["site_order"] : "";
    }
    protected function _getPlatformOrder()
    {
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["platform"]) ? explode(",", $auth_all_list["platform"]) : [];
    }
    protected function _getClientOpenList()
    {
        $client_open_list = [];
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        if (array_key_exists("client_open_list", $auth_all_list)) {
            foreach ($auth_all_list["client_open_list"] as $key => $v) {
                if (is_array($v)  ) {
                    $client_open_list[] = $key;
                }
            }
        }
        unset($v);
        array_push($client_open_list,"event");
        return $client_open_list;
    }
    protected function _getSelectTabOpenList()
    {
        $select_tab_open_list = ["os", "eb", "zg", "my_page", "message", "renzheng", "renwu", "tuiguang", "shop", "defined", "other", "wechats"];
        $auth_all_list = \think\Cache::get($this->official_auth_list);
        if (!$auth_all_list) {
            $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
            $content = file_get_contents($auth_file_path);
            $auth_all_list = $this->_dealAuthContent($content);
            \think\Cache::set($this->official_auth_list, $auth_all_list, 600);
        }
        $auth_all_list = json_decode($auth_all_list, true);
        return isset($auth_all_list["select_tab_open_list"]) ? $auth_all_list["select_tab_open_list"] : $select_tab_open_list;
    }
    private function _dealAuthContent($content)
    {
        $content = base64_decode($content);
        $string_content = substr($content, 16, strlen($content) - 32);
        $iv_r = substr($content, 0, 16);
        $key_r = substr($content, strlen($content) - 16, 16);
        $auth_all_list = openssl_decrypt(base64_decode($string_content), "AES-128-CBC", $key_r, OPENSSL_RAW_DATA, $iv_r);
        return $auth_all_list;
    }
    protected function _getCode($no_out = 0)
    {
        $auth_file_path = UPLOAD_PATH . "/" . md5("give_auth") . ".md5";
        if (!is_file($auth_file_path)) {
            if ($no_out) {
                return false;
            }
            header("content-type:text/html;charset=utf-8");
            exit("权限校验失败-file not exit");
        }
        $content = file_get_contents($auth_file_path);
        $content = base64_decode($content);
        $string_content = substr($content, 16, strlen($content) - 32);
        $iv_r = substr($content, 0, 16);
        $key_r = substr($content, strlen($content) - 16, 16);
        $auth_all_list = json_decode(openssl_decrypt(base64_decode($string_content), "AES-128-CBC", $key_r, OPENSSL_RAW_DATA, $iv_r), true);
        if (!isset($auth_all_list["code"])) {
            if ($no_out) {
                return false;
            }
            header("content-type:text/html;charset=utf-8");
            exit("权限校验失败- code false");
        }
        $code = $auth_all_list["code"];
        return $code;
    }
    public function get_access_token()
    {
        $url = get_domain();
        $plate = $this->_getPlatformOrder();
        $data = [];
        $iv = "1234567890123412";
        $key = "201707eggplant99";
        foreach ($plate as $v) {
            $code = $url . "|" . $v;
            $data[$v] = trim(base64_encode(openssl_encrypt(base64_encode($code), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv)));
        }
        unset($v);
        return $data;
    }
    public function decrypt_access_token($token = "qxgt+WgUsARnawZqp83JX6LFuwDmDLKG2ciSCaLePayiBbBbIfCVl4usV+xJz6QV1r5EJgki/dLzgS03adgvpcY8ocIzpZC/LkfvtMix/LE=")
    {
        $plate = $this->_getPlatformOrder();
        $iv = "1234567890123412";
        $key = "201707eggplant99";
        if (empty($token)) {
            return false;
        }
        $access_token = trim(base64_decode(openssl_decrypt(base64_decode($token), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv)));
        $access = explode("|", $access_token);
        $this->access = $access;
        if ($access[0] == get_domain() && in_array($access[1], $plate)) {
            return true;
        }
        return false;
    }
    public function check_platform()
    {
        $module_name = $this->request->module();
        $controller_name = $module_name . "/" . $this->request->controller();
        $action_name = $controller_name . "/" . $this->request->action();
        $all_special = ["admin", "auth", "imapi", "osapi/weixin", "commonapi/user", "osapi/base/giveauth", "ebapi/auth_api/time_out_order", "ebapi/authapi/time_out_order", "shareapi/order/giveorderback", "ebapi/auth_api/user_message_order", "ebapi/authapi/user_message_order", "shareapi/order/sendmessage", "commonapi/index/toendimg", "commonapi/system/countdata", "commonapi/script/new_thread", "commonapi/rank/threadrank", "commonapi/rank/topicrank", "commonapi/rank/userrank", "commonapi/script/set_hot_topic", "commonapi/index/createsignature", "frameweb/index/frameweb", "commonapi/user/renotify", "ebapi/auth_api/notify", "ebapi/authapi/notify", "commonapi/script","ebapi/trialapi/time_out_trial"];
        if (in_array(strtolower($module_name), $all_special) || in_array(strtolower($controller_name), $all_special) || in_array(strtolower($action_name), $all_special)) {
            return true;
        }
        $token = $this::getAllHeaders();
        if (array_key_exists("Platform-Token", $token)) {
            if (!$this::decrypt_access_token($token["Platform-Token"])) {
                $this->error("Platform-Token错误！");
            }
        } else { 
            $this->error("Platform-Token错误！");
        }
        return true;
    }
    private function _check_extend_auth()
    {
        return true;
        $module_name = $this->request->module();
        $controller_name = $module_name . "/" . $this->request->controller();
        if (strtolower($controller_name) == "commomapi/user") {
            $clientOpenList = $this->_getClientOpenList();
            if (!in_array("website_connect", $clientOpenList)) {
                $this->error("该功能为商业付费扩展，请联系官方客户经理开通！");
            }
        }
        return true;
    }
    public function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == "HTTP_") {
                $headers[str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
    public function get_official_module()
    {
        $model = ["admin", "osapi", "commonapi", "ebapi", "shopapi", "shareapi"];
        return $model;
    }
}

?>