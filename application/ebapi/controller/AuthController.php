<?php
namespace app\ebapi\controller;
use app\ebapi\model\member\MemberShip as MemberShip;

class AuthController extends Basic
{
    protected $uid = 0;

    protected $userInfo = [];

    protected $isVip=false;

    protected function _initialize()
    {
        parent::_initialize();
        //验证TOken并获取user信息
        $this->userInfo=$this->checkTokenGetUserInfo();
        $this->uid=isset($this->userInfo['uid']) ? $this->userInfo['uid'] : 0;
        if($this->uid>0)
        {            
            $this->isVip=MemberShip::memberExpiration($this->uid)==false;
            //   $this->isVip=false;
        }
        
    }
}