<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/17
 */

namespace app\admin\controller\ump;


use app\admin\controller\AuthController;
use app\admin\model\ump\ColumnCouponIssueUser;
use service\FormBuilder as Form;
use app\admin\model\ump\ColumnCouponIssue as ColumnCouponIssueModel;
use service\JsonService;
use think\Url;
use traits\CurdControllerTrait;
use service\UtilService as Util;

class ColumnCouponIssue extends AuthController
{
    use CurdControllerTrait;

    protected $bindModel = ColumnCouponIssueModel::class;

    public function index()
    {
        $where=Util::getMore([
            ['status',''],
            ['coupon_title','']
        ]);
        $this->assign(ColumnCouponIssueModel::stsypage($where));
        $this->assign('where',$where);
        return $this->fetch();
    }

    public function delete($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        if(ColumnCouponIssueModel::edit(['is_del'=>1],$id,'id'))
            return JsonService::successful('删除成功!');
        else
            return JsonService::fail('删除失败!');
    }

    public function edit($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        $issueInfo = ColumnCouponIssueModel::get($id);
        if(-1 == $issueInfo['status'] || 1 == $issueInfo['is_del']) return $this->failed('状态错误,无法修改');
        $f = [Form::radio('status','是否开启',$issueInfo['status'])->options([['label'=>'开启','value'=>1],['label'=>'关闭','value'=>0]])];
        $form = Form::make_post_form('状态修改',$f,Url::build('change_field',array('id'=>$id,'field'=>'status')));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function issue_log($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        $this->assign(ColumnCouponIssueUser::systemCouponIssuePage($id));
        return $this->fetch();
    }
}