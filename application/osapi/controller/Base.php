<?php


namespace app\osapi\controller;

class Base extends \basic\ControllerBasic
{
    protected function _initialize()
    {  
        parent::_initialize();
        $controller_name = $this->request->controller();
        if (!(strtolower($controller_name) == "weixin" || strtolower($controller_name) == "user" || strtolower($controller_name) == "base")) {
            action_power("visit", get_uid());
        }
    }
}

?>