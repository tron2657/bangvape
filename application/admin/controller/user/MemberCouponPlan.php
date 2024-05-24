<?php

 

namespace app\admin\controller\user;

use app\admin\controller\AuthController;
use service\UtilService as Util;
use service\JsonService as Json;
use service\FormBuilder as Form;
use think\Url;
use app\admin\model\user\MemberCouponPlan as MemberCouponPlanModel;
use service\JsonService;
use think\Exception;

class MemberCouponPlan extends AuthController
{
    public function index()
    {
       
        // $this->assign(MemberCouponPlanModel::systemPage($where));
        // $this->assign(['where'=>$where] );
 
        return $this->fetch();
    }
    

    public function set_fail()
    {
        $pam = Util::getMore([
            ['id',''],
            ['value', ''],
        ]);
        $res=MemberCouponPlanModel::where(['id'=>$pam['id']])->setField('is_fail',$pam['value']);
        if($res!==false){
            $this->apiSuccess('设置成功');
        }else{
            $this->apiError('设置失败');
        }
    }
 
    public function grant_coupon()
    {
        try{
            MemberCouponPlanModel::grant_coupon();
            return JsonService::successful('执行成功');
        }catch(Exception $e)
        {
            return JsonService::fail($e->getMessage());
        }

    }

    public function list(){
        $where = Util::getMore([
            ['page', 1],
            ['limit', 20],
            ['status', ''],
            ['title', ''],
            ['is_fail', ''],
            ['nickname','']
        ]);
        $result= MemberCouponPlanModel::systemPage($where);

        return json::successlayui($result);
    }

}