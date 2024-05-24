<?php

namespace app\admin\controller\payment;

use app\admin\controller\AuthController;
use app\admin\model\payment\UserWallet;
use service\JsonService as Json;
use service\UtilService as Util;


/**
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class User extends AuthController
{
    public function index(){

        $pam=Util::getMore([
            ['real_name',''],
        ]);
        $this->assign([
            'real_name'=>$pam['real_name'],
        ]);
        return $this->fetch();
    }

    /**
     * 获取列表
     */
    public function get_user_wallet_list(){
        $pam=Util::getMore([
            ['real_name',''],
            ['page',0],
            ['limit',10],
        ]);
        $map['status']=1;
        if($pam['real_name']){
            $user=db('user')->where(['nickname'=>['like','%'.$pam['real_name'].'%']])->column('uid');
            $map['uid']=['in',$user];
        }
        return Json::successlayui(UserWallet::get_user_wallet_list($map,$pam['page'],$pam['limit'],'uid asc'));
    }
}