<?php
namespace app\admin\controller\event;

use app\admin\controller\AuthController;
use app\admin\model\event\EventCategory as Cate;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use think\Url;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class EventCategory extends AuthController
{
    /**
     * 分类列表页
     * @return mixed
     */
    public function index(){
        $pid=osx_input('get.pid',0,'intval');
        $cate=Cate::get_cate_tree();
        $this->assign([
            'pid'=>$pid,
            'cate'=>$cate
        ]);
        return $this->fetch();
    }

    /**
     * 创建
     * @return mixed
     */
    public function create(){
        $id=osx_input('id',0,'intval');
        if($id){
            $data=Cate::get($id);
        }else{
            $data['name']=$data['pid']=$data['sort']='';
            $data['status']=1;
        }
        $cate=Cate::get_check_pid();
        $field = [
            Form::input('name','分类名称', $data['name'])->col(Form::col(24)),
            Form::select('pid','父级分类', (String)$data['pid'])->options($cate),
            Form::input('sort','排序', $data['sort'])->col(Form::col(24)),
            Form::radio('status','状态', $data['status'])->options([['label'=>'显示','value'=>1],['label'=>'隐藏','value'=>0]]),
            Form::hidden('id',$id)
        ];
        $form = Form::make_post_form('创建广告',$field, Url::build('save'),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     *更新
     */
    public function save(){
        $pam = Util::getMore([
            ['id',''],
            ['name',''],
            ['pid', 0],
            ['sort',0],
            ['status',1],
        ]);
        if(!$pam['name']){
            $this->apiError('请填写分类名称');
        }
        $res=Cate::editDate($pam);
        $name=$pam['id']?'编辑':'创建';
        if($res!==false){
            $this->apiSuccess($name.'分类成功');
        }else{
            $this->apiError($name.'分类错误');
        }
    }

    public function get_list(){
        $pam = Util::getMore([
            ['pid',0],
            ['real_name',''],
            ['status', ''],
            ['page',''],
            ['limit',10],
        ]);
        $where['status']=['egt',0];
        if($pam['pid']!==''){
            $where['pid']=$pam['pid'];
        }
        if($pam['real_name']!==''){
            $where['name']=['like','%'.$pam['real_name'].'%'];
        }
        if($pam['status']!==''){
            $where['status']=$pam['status'];
        }

        return JsonService::successlayui(Cate::get_list($where,$pam['page'],$pam['limit'],$order='id desc'));
    }

    public function delete(){
        $id=osx_input('id',0,'intval');
        $res=Cate::where(['id'=>$id])->setField('status',-1);
        $name='删除';
        if($res){
            $this->apiSuccess($name.'分类成功');
        }else{
            $this->apiError($name.'分类错误');
        }
    }
}