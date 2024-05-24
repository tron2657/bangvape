<?php


namespace app\columnapi\controller;

class AuthController extends Basic
{
    protected $userInfo = 0;
    protected function _initialize()
    {
        parent::_initialize();
        $this->userInfo = $this->checkTokenGetUserInfo();
        $this->uid = isset($this->userInfo["uid"]) ? $this->userInfo["uid"] : 0;
    }
}

?>