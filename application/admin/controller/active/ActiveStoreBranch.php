<?php
namespace app\admin\controller\active;

use app\admin\controller\AuthController;
use app\admin\model\certification\CertificationDatum;
use app\admin\model\com\ComForum;
use app\admin\model\active\ActiveCategory as Cate;
use app\admin\model\active\ActiveStoreBranch as ActiveStoreBranchModel;
use app\admin\model\group\Group;
use app\admin\model\system\SystemConfig;
use app\osapi\controller\Common;
use service\FormBuilder as Form;
use service\JsonService;
use service\PHPExcelService;
use service\UtilService as Util;
use think\Url;
use think\Request;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ActiveStoreBranch extends AuthController
{
    /**
     * 分类列表页
     * @return mixed
     */
    public function index(){
        $provinc_list=ActiveStoreBranchModel::field('province')->distinct(true)->select()->toArray();
        $city_list=ActiveStoreBranchModel::field('city')->distinct(true)->select()->toArray();
        $district_list=ActiveStoreBranchModel::field('district')->distinct(true)->select()->toArray();
        $this->assign([
            'provinc_list'=>$provinc_list,
            'city_list'=>$city_list,
            'district_list'=>$district_list,
        ]);
        return $this->fetch();
    }

    /**
     * 创建
     * @return mixed
     */
    public function create(){
        $id=osx_input('id',0,'intval');
        $event=ActiveStoreBranchModel::getEvent($id);        
        $event['nickname']=db('user')->where(['uid'=>$event['uid']])->value('nickname');
        $this->assign([
            'event'=>$event
        ]);
        return $this->fetch('create');
    }    

      /**
     * 创建/编辑活动
     */
    public function edit_event(Request $request){
        $params = Util::postMore([
            ['id',''],
            ['uid',''],
            ['name',''],
            ['city',''],
            ['province',''],
            ['district',''],
            ['lat',''],
            ['lng',''],
            ['address',''],
            ['cover',''],
            ['stock',0],
            
        ],$request);
        $name=$params['id']?'编辑':'创建';
        if(!$params['name']){
            $this->apiError('请输入名称');
        }
        if(!$params['uid']){
            $this->apiError('请选择门店负责人');
        }

        $res=ActiveStoreBranchModel::editData($params);
        if($res!==false){           
            $this->apiSuccess($name.'成功');
        }else{
            $this->apiError($name.'失败');
        }
    }


    public function get_list(){
        $pam = Util::getMore([
            ['status', ''],
            ['page',''],
            ['limit',10],
            ['name',''],
            ['province',''],
            ['city',''],
            ['district',''],
        ]);
        $where=[];
        if($pam['name']!==''){
            $where['name']=['like','%'.name.'%'];           
        }
        if($pam['province']!==''){
            $where['province']=$pam['province'];
        }
        if($pam['city']!==''){
            $where['city']=$pam['city'];
        }
        if($pam['district']!==''){
            $where['district']=$pam['district'];
        }
        return JsonService::successlayui(ActiveStoreBranchModel::get_list($where,$pam['page'],$pam['limit'],$order='id desc'));
    }

    /**
     * 删除分类
     */
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
    /**
     * 设置活动状态
     */
    public function set_is_close(){
        $id=osx_input('id',0,'intval');
        $is_close=osx_input('is_close',0,'intval');
        $data['is_close']=$is_close;
        if($is_close==0){
            $name='开启';
        }else{
            $name='关闭';
        }
        $res=ActiveStoreBranchModel::where(['id'=>$id])->update($data);
        if($res){
            $this->apiSuccess('操作成功');
        }else{
            $this->apiError($name.'错误');
        }
    }

  

   
}