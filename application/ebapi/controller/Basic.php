<?php

namespace app\ebapi\controller;

class Basic extends \basic\ControllerBasic
{
	protected $ApimiddlewareGroups = [];
	protected $Debug = true;
	protected function _initialize()
	{
		parent::_initialize();
		$this->runApimiddlewareGroups();
	}
	protected function checkTokenGetUserInfo()
	{
		if (!\service\UtilService::isWechatBrowser() && $this->Debug === false) {
			return $this->fail("非法访问");
		}
		$check = $this->checkAuth();
		$token = $this->getRequestToken();
		if (!$token && $check === false) {
			$this->fail("请传入token验证您的身份信息");
		}
		$token_os = get_uid();
		if (!$token_os) {
			$Tokencheck = \app\core\util\TokenService::checkToken($token, $check);
			if ($Tokencheck === true) {
				return ["uid" => 0];
			} else {
				if (is_array($Tokencheck)) {
					list($uid) = $Tokencheck;
					$userInfo = \app\ebapi\model\user\User::getUserInfo($uid);
				} else {
					$this->fail("没有获取到用户信息,请传入token验证您的身份信息", [], 402);
				}
			}
		} else {
			$userInfo = \app\ebapi\model\user\User::getUserInfo($token_os);
		}
		if ((!$userInfo || !isset($userInfo)) && $check === false) {
			$this->fail("用户信息获取失败,没有这样的用户!");
		}
		if (isset($userInfo) && $userInfo) {
			if (!$userInfo->status) {
				$this->fail("您已被禁止登录", [], 401);
			}
			\service\HookService::listen("init", $userInfo, null, false, "app\\core\\behavior\\UserBehavior");
			return $userInfo->toArray();
		} else {
			return ["uid" => 0];
		}
	}
	protected function runApimiddlewareGroups()
	{
		$hash = $this->request->routeInfo();
		if (!\think\Config::get("url_route_on") || !isset($hash["rule"][1])) {
			foreach ((array) $this->ApimiddlewareGroups as $behavior) {
				$result = \think\Hook::exec($behavior);
				if (!is_null($result)) {
					return $this->fail($result);
				}
			}
		}
	}
	public function _empty($name)
	{
		$this->fail("您访问的页面不存在:" . $name);
	}
	protected function getRequestToken()
	{
		if ($this->Debug) {
			$TOKEN = $this->request->get("Access-Token", "");
			if ($TOKEN === "") {
				$TOKEN = $this->request->param("Access-Token", "");
			}
			if ($TOKEN === "") {
				$TOKEN = $this->request->header("Access-Token");
			}
		} else {
			$TOKEN = $this->request->header("Access-Token");
		}
		return $TOKEN;
	}
	protected function successful($msg = "ok", $data = [], $status = 200)
	{
		return \service\JsonService::successful($msg, $data, $status);
	}
	protected function fail($msg = "error", $data = [], $status = 400)
	{
		return \service\JsonService::fail($msg, $data, $status);
	}
	protected function getAuthName($action, $controller, $module)
	{
		return strtolower($module . "/" . $controller . "/" . $action);
	}
	protected function getCurrentController($controller, $module)
	{
		return "app\\" . $module . "\\controller\\" . str_replace(".", "\\", $controller);
	}
	protected function checkAuth($action = null, $controller = null, $module = null)
	{
		if ($module === null) {
			$module = $this->request->module();
		}
		if ($controller === null) {
			$controller = $this->request->controller();
		}
		if ($action === null) {
			$action = $this->request->action();
		}
		$className = $this->getCurrentController($controller, $module);
		if (method_exists($className, "whiteList")) {
			try {
				$white = $className::whiteList();
				if (!is_array($white)) {
					return false;
				}
				foreach ($white as $actionWhite) {
					if ($this->getAuthName($actionWhite, $controller, $module) == $this->getAuthName($action, $controller, $module)) {
						return true;
					}
				}
			} catch (\Exception $e) {
			}
		}
		return false;
	}
}