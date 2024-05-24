<?php
namespace app\admin\controller\user;

use app\admin\controller\AuthController;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use app\osapi\model\user\UserModel;
use app\admin\model\user\UserRecommend as RecommendModel;
use think\Url;
use app\admin\model\system\SystemConfig as ConfigModel;

/**
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class UserRecommend extends AuthController
{
	public function index(){
        $pz = $sms_type = ConfigModel::getValue('recommend_at');
        $this->assign('pz',$pz);
		return $this->fetch();
	}

	public function user_list(){
		$where = Util::getMore([
            ['page',1],
            ['limit',20],
        ]);
        return JsonService::successlayui(RecommendModel::UserList($where));
	}

    public function find_users($nickname){
        $users=UserModel::where('nickname|uid|phone','like',"%$nickname%")->limit(10)->select()->toArray();
        $data=array();
        if($users){

            foreach ($users as $v){
                if($v){
                    $data[]=array('value'=>$v['uid'],'name'=>$v['nickname']);
                }
            }
        }
        return Json::successlayui(count($users),$data,'成功');
    }

    /**
     * 推荐用户
     */
    public function recommend_user(Request $request)
    {
        $data = Util::postMore([
            'uid',
            ['reason',''],
        ],$request);
        $uid=db('user')->where('uid',$data['uid'])->where('status',1)->value('uid');
        if(!$uid){
            return JsonService::fail('该用户不存在');
        }
        $count=RecommendModel::where('uid',$uid)->find();
        if($count){
            $map['reason']=$data['reason'];
            $map['status']=1;
            $map['create_time']=time();
            $res=RecommendModel::where('uid',$uid)->update($map);
        }else{
            $map['uid']=$uid;
            $map['reason']=$data['reason'];
            $map['status']=1;
            $map['create_time']=time();
            $map['sort']=0;
            $res=RecommendModel::where('id',1)->insert($map);
        }
        if($res){
            return Json::successful('推荐成功!');
        }else{
            return JsonService::fail('推荐失败!');
        }
    }

    /**
     * 推荐多个用户
     */
    public function recommend_user_all(Request $request)
    {
        $data = Util::postMore([
            'uid',
            ['reason',''],
        ],$request);
        $uids=explode(",",$data['uid']);
        if(!$uids){
            return JsonService::fail('请输入用户');
        }
        foreach($uids as &$val){
            $count=RecommendModel::where('uid',$val)->find();
            if($count){
                $map['reason']=$data['reason'];
                $map['status']=1;
                $map['create_time']=time();
                RecommendModel::where('uid',$val)->update($map);
            }else{
                $map['uid']=$val;
                $map['reason']=$data['reason'];
                $map['status']=1;
                $map['create_time']=time();
                $map['sort']=0;
                RecommendModel::where('id',1)->insert($map);
            }
        }
        unset($val);
        return Json::successful('推荐成功!');

    }

    public function set_recommend()
    {
        return $this->fetch();
    }

    public function cancel_recommend(){
        $post=Util::postMore([
            ['uid']
        ]);
        if(empty($post['uid'])){
            return JsonService::fail('请选择需要取消推荐的用户');
        }else{
            $res=RecommendModel::where('uid',$post['uid'])->update(['status'=>0]);
            if($res)
                return JsonService::successful('成功');
            else
                return JsonService::fail('失败');
        }
    }

    /**
     * 批量取消推荐
     */
    public function del_recommend(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要取消推荐的用户');
        }else{
            $res=RecommendModel::where('id','in',$post['ids'])->update(['status'=>0]);
            if($res)
                return JsonService::successful('成功');
            else
                return JsonService::fail('失败');
        }
    }

    /**
     * 快速编辑
     *
     * @return json
     */
    public function quick_edit($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && JsonService::fail('缺少参数');
        if(RecommendModel::where(['id'=>$id])->update([$field=>$value]))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }

    /**
     * 默认关注
     */
     public function attention_edit(){
        $id=osx_input('id',0,'intval');
        $attention=osx_input('attention','','text');
        if($id == '' || $attention == ''){
            return  JsonService::fail('缺少参数');
        }
        // var_dump($attention);die;
        if($attention == 'true'){
            // echo 1;die;
            if(RecommendModel::where(['id'=>$id])->update(['attention' => 1])){
                return JsonService::successful('开启成功');
            }else{
                return JsonService::fail('开启失败');
            }
        }else{
            // echo 2;die;
            if(RecommendModel::where(['id'=>$id])->update(['attention' => 0])){
                return JsonService::successful('关闭成功');
            }else{
                return JsonService::fail('关闭失败');
            }
        }  
        //  return JsonService::successful('保存成功');
     }
     public function recommend_config_edit(){
        $name=osx_input('config_edit','','intval');
        $data = array(
            'recommend_at' => $name
        );
        $res = ConfigModel::updateSms($data);
            if($res == 1){
                return JsonService::successful('修改配置成功');
            }else{
                return JsonService::fail('修改配置失败');
            }
     }
}