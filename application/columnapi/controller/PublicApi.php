<?php


namespace app\columnapi\controller;

class PublicApi extends AuthController
{
    public static function whiteList()
    {
        return ["get_store_set"];
    }
    public function get_store_set()
    {
        $data = db("store_set")->where("status", 1)->select();
        return \service\JsonService::successful($data);
    }
}

?>