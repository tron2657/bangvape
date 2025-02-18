<?php

namespace app\admin\controller\ump;

use app\admin\controller\AuthController;
use app\admin\model\ump\ColumnCouponIssue;
use app\admin\model\wechat\WechatUser as UserModel;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UtilService;
use think\Request;
use app\admin\model\ump\ColumnCoupon as ColumnCouponModel;
use think\Url;

/**
 * 优惠券控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ColumnCoupon extends AuthController
{

    /**
     * @return mixed
     */
    public function index()
    {
        $where = Util::getMore([
            ['status',''],
            ['title',''],
        ],$this->request);
        $this->assign('where',$where);
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $f = array();
        $f[] = Form::input('title','优惠券名称');
        $f[] = Form::number('coupon_price','优惠券面值',0)->min(0);
        $f[] = Form::number('use_min_price','优惠券最低消费')->min(0);
        $f[] = Form::number('coupon_time','优惠券有效期限')->min(0);
        $f[] = Form::number('sort','排序');

        $form = Form::make_post_form('添加知识商城优惠券',$f,Url::build('save'));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request)
    {
        $data = Util::postMore([
            'title',
            'coupon_price',
            'use_min_price',
            'coupon_time',
            'sort',
        ],$request);
        $data['status']=1;
        if(!$data['title']) return Json::fail('请输入优惠券名称');
        if(!$data['coupon_price']) return Json::fail('请输入优惠券面值');
        if(!$data['coupon_time']) return Json::fail('请输入优惠券有效期限');
        ColumnCouponModel::set($data);
        return Json::successful('添加优惠券成功!');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $coupon = ColumnCouponModel::get($id);
        if(!$coupon) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('title','优惠券名称',$coupon->getData('title'));
        $f[] = Form::number('coupon_price','优惠券面值',$coupon->getData('coupon_price'))->min(0);
        $f[] = Form::number('use_min_price','优惠券最低消费',$coupon->getData('use_min_price'))->min(0);
        $f[] = Form::number('coupon_time','优惠券有效期限',$coupon->getData('coupon_time'))->min(0);
        $f[] = Form::number('sort','排序',$coupon->getData('sort'));

        $form = Form::make_post_form('编辑知识商城优惠券',$f,Url::build('update',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = Util::postMore([
            'title',
            'coupon_price',
            'use_min_price',
            'coupon_time',
            'sort',
        ],$request);
        $data['status']=1;
        if(!$data['title']) return Json::fail('请输入优惠券名称');
        if(!$data['coupon_price']) return Json::fail('请输入优惠券面值');
        if(!$data['coupon_time']) return Json::fail('请输入优惠券有效期限');
        ColumnCouponModel::edit($data,$id);
        return Json::successful('修改成功!');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return Json::fail('数据不存在!');
        $data['is_del'] = 1;
        if(!ColumnCouponModel::edit($data,$id))
            return Json::fail(ColumnCouponModel::getErrorInfo('删除失败,请稍候再试!'));
        else
            return Json::successful('删除成功!');
    }

    /**
     * 修改优惠券状态
     * @param $id
     * @return \think\response\Json
     */
    public function status($id)
    {
        if(!$id) return Json::fail('数据不存在!');
        if(!ColumnCouponModel::editIsDel($id))
            return Json::fail(ColumnCouponModel::getErrorInfo('修改失败,请稍候再试!'));
        else
            return Json::successful('修改成功!');
    }

    /**
     * @return mixed
     */
    public function grant_subscribe(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $this->assign('where',$where);
        $where['status']=1;
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function grant_all(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $where['status']=1;
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }

    /**
     * @param $id
     */
    public function grant($id){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $nickname = UserModel::where('uid','IN',$id)->column('uid,nickname');
        $this->assign('where',$where);
        $this->assign('uid',$id);
        $this->assign('nickname',implode(',',$nickname));
        $where['status']=1;
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }

    public function issue($id)
    {
        if(!ColumnCouponModel::be(['id'=>$id,'status'=>1,'is_del'=>0]))
            return $this->failed('发布的优惠劵已失效或不存在!');
        $f = array();
        $f[] = Form::input('id','优惠劵ID',$id)->disabled(1);
        $f[] = Form::dateTimeRange('range_date','领取时间')->placeholder('不填为永久有效');
        $f[] = Form::number('count','发布数量',0)->min(0)->placeholder('不填或填0,为不限量');
        $f[] = Form::radio('is_permanent','是否不限量',0)->options([['label'=>'限量','value'=>0],['label'=>'不限量','value'=>1]]);
        $f[] = Form::radio('status','状态',1)->options([['label'=>'开启','value'=>1],['label'=>'关闭','value'=>0]]);

        $form = Form::make_post_form('添加优惠券',$f,Url::build('update_issue',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function update_issue(Request $request,$id)
    {
        list($_id,$rangeTime,$count,$status) = UtilService::postMore([
            'id',['range_date',['','']],['count',0],['status',0]
        ],$request,true);
        if($_id != $id) return JsonService::fail('操作失败,信息不对称');
        if(!$count) $count = 0;
        if(!ColumnCouponModel::be(['id'=>$id,'status'=>1,'is_del'=>0])) return JsonService::fail('发布的优惠劵已失效或不存在!');
        if(count($rangeTime)!=2) return JsonService::fail('请选择正确的时间区间');

        list($startTime,$endTime) = $rangeTime;
//        echo $startTime;echo $endTime;var_dump($rangeTime);die;
        if(!$startTime) $startTime = 0;
        if(!$endTime) $endTime = 0;
        if(!$startTime && $endTime) return JsonService::fail('请选择正确的开始时间');
        if($startTime && !$endTime) return JsonService::fail('请选择正确的结束时间');
        if(ColumnCouponIssue::setIssue($id,$count,strtotime($startTime),strtotime($endTime),$count,$status))
            return JsonService::successful('发布优惠劵成功!');
        else
            return JsonService::fail('发布优惠劵失败!');
    }


    /**
     * 给分组用户发放优惠券
     */
    public function grant_group(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $group = UserModel::getUserGroup();
        $this->assign('where',$where);
        $this->assign('group',json_encode($group));
        $where['status']=1;
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }
    /**
     * 给标签用户发放优惠券
     */
    public function grant_tag(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $tag = UserModel::getUserTag();;//获取所有标签
        $this->assign('where',$where);
        $this->assign('tag',json_encode($tag));
        $where['status']=1;
        $this->assign(ColumnCouponModel::systemPage($where));
        return $this->fetch();
    }
}
