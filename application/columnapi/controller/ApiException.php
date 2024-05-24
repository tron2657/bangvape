<?php


namespace app\columnapi\controller;

class ApiException extends Basic
{
    public function render(\Exception $e)
    {
        if ($this->Debug) {
            return \think\exception\Handle::render($e);
        }
        if ($e instanceof \think\exception\ValidateException) {
            return \service\JsonService::fail($e->getError(), 422);
        }
        return \service\JsonService::fail("系统错误");
    }
}

?>