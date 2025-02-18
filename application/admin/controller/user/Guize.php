<?php
namespace app\admin\controller\user;

use app\admin\controller\AuthController;
use app\admin\model\system\SystemRuleAction;
use app\admin\model\system\SystemGradeDesc;
use app\admin\model\system\SystemUserTask;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService;
use think\Url;
use traits\CurdControllerTrait;
use think\Request;

/**
 * 会员设置
 * Class UserLevel
 * @package app\admin\controller\user
 */
class Guize extends AuthController
{
    use CurdControllerTrait;

    /*
     * 等级展示
     * */
    public function index()
    {
        $jifenleixing = db('system_rule')->where('status',1)->order('id','asc')->select();
        foreach ($jifenleixing as $item) {
            $secnod[] = $this->deal($item) ;
        }
        $secnod = json_encode($secnod) ;
        $this->assign('secnod',$secnod);
        return $this->fetch();
    }

    public function deal($item)
    {
        $father = [] ;
        if(strpos($item['flag'],'exp')!==false){
            $father['field'] = 'expone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'expone' ;
        }
        if(strpos($item['flag'],'fly')!==false){
            $father['field'] = 'flyone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'flyone' ;
        }
        if(strpos($item['flag'],'gong')!==false){
            $father['field'] = 'gongone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'gongone' ;
        }
        if(strpos($item['flag'],'buy')!==false){
            $father['field'] = 'buyone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'buyone' ;
        }
        if(strpos($item['flag'],'one')!==false){
            $father['field'] = 'firstone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'firstone' ;
        }
        if(strpos($item['flag'],'two')!==false){
            $father['field'] = 'twoone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'twoone' ;
        }
        if(strpos($item['flag'],'three')!==false){
            $father['field'] = 'threeone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'threeone' ;
        }
        if(strpos($item['flag'],'four')!==false){
            $father['field'] = 'fourone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'fourone' ;
        }
        if(strpos($item['flag'],'five')!==false){
            $father['field'] = 'fiveone' ;
            $father['title'] = $item['name'] ;
            $father['edit'] = 'fieone' ;
        }
        return $father;
    }


    /*
     * 创建form表单
     * */
    public function create($id=0 )
    {

        if($id) $vipinfo=SystemRuleAction::get($id);
        $field[]= Form::input('name','名称',isset($vipinfo) ? $vipinfo->name : '')->col(Form::col(24));
        $field[]= Form::select('leixing','类型',empty($vipinfo->leixing) ? 0 : $vipinfo->leixing )->options([['label'=>'系统积分','value'=>'1'],['label'=>'自定义积分','value'=>'2']  ])->col(24);
        $field[]= Form::input('danwei','单位',isset($vipinfo) ? $vipinfo->danwei : '')->col(Form::col(24));
        $field[]= Form::textarea('explain','积分说明',isset($vipinfo) ? $vipinfo->explain : '');
        $title = empty($id) ? '添加' : '编辑' ;
        $form = Form::make_post_form($title,$field,Url::build('save',['id'=>$id]),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /*
     * 会员等级添加或者修改
     * @param $id 修改的等级id
     * @return json
     * */
    public function save($id=0)
    {
        $data=UtilService::postMore([
            ['name',''],
            ['leixing',0],
            ['danwei',''],
            ['explain',''],
        ]);

        if(!$data['name']) return JsonService::fail('请输入名称');
//        if(!$data['leixing']) return JsonService::fail('请输入经验值上限');
        if(!$data['danwei']) return JsonService::fail('请填写积分单位');
        if(!$data['explain']) return JsonService::fail('请填写积分说明');
        SystemRuleAction::beginTrans();
        try{
            //修改
            if($id){
                if(SystemRuleAction::edit($data,$id)){
                    SystemRuleAction::commitTrans();
                    return JsonService::successful('修改成功');
                }else{
                    SystemRuleAction::rollbackTrans();
                    return JsonService::fail('修改失败');
                }
            }else{
                //新增
                $data['add_time']=time();
                if(SystemRuleAction::set($data)){
                    SystemRuleAction::commitTrans();
                    return JsonService::successful('添加成功');
                }else{
                    SystemRuleAction::rollbackTrans();
                    return JsonService::fail('添加失败');
                }
            }
        }catch (\Exception $e){
            SystemRuleAction::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }
    }
    /*
     * 获取系统设置的vip列表
     * @param int page
     * @param int limit
     * */
    public function get_system_vip_list()
    {
        $where=UtilService::getMore([
            ['page',0],
            ['limit',20],

        ]);
        return JsonService::successlayui(SystemRuleAction::getSytemList($where));
    }

    /*
     * 删除会员等级
     * @param int $id
     * */
    public function delete($id=0)
    {
        if(SystemRuleAction::edit(['is_del'=>1],$id))

            return JsonService::successful('删除成功');
        else
            return JsonService::fail('删除失败');
    }

    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_show($is_show='',$id=''){
        ($is_show=='' || $id=='') && Json::fail('缺少参数');
        $res=SystemRuleAction::where(['id'=>$id])->update(['is_show'=>(int)$is_show]);
        if($res){
            return JsonService::successful($is_show==1 ? '显示成功':'隐藏成功');
        }else{
            return JsonService::fail($is_show==1 ? '显示失败':'隐藏失败');
        }
    }

    /**
     * 快速编辑
     *
     * @return json
     */
    public function set_value($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && Json::fail('缺少参数');
        $info=SystemRuleAction::where('id',$id)->find()->toArray();
        $value=intval($value);
        if($field=='num'){
            $info['expmax']=$value*$info['expone'];
            $info['flymax']=$value*$info['flyone'];
            $info['gongmax']=$value*$info['gongone'];
            $info['buymax']=$value*$info['buyone'];
            $info['firstmax']=$value*$info['firstone'];
            $info['twomax']=$value*$info['twoone'];
            $info['threemax']=$value*$info['threeone'];
            $info['fourmax']=$value*$info['fourone'];
            $info['fivemax']=$value*$info['fiveone'];
            $info['num']=$value;
        }else{
            if($info['actionflag']=='beijinyan'){
                if($value>0){
                    return JsonService::fail('该操作不能设置正值');
                }
            }else{
                if($value<0){
                    return JsonService::fail('该操作不能设置负值');
                }
            }
            $field_max=str_replace('one','',$field).'max';
            $info[$field]=$value;
            $info[$field_max]=$info['num']*$info[$field];
        }
        if(SystemRuleAction::where(['id'=>$id])->update($info))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }


    /*
     * 等级任务列表
     * @param int $vip_id 等级id
     * @return json
     * */
    public function tash($level_id=0)
    {
        $this->assign('level_id',$level_id);
        return $this->fetch();
    }

    /**
     * 快速编辑
     *
     * @return json
     */
    public function set_tash_value($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && Json::fail('缺少参数');
        if(SystemUserTask::where(['id'=>$id])->update([$field=>$value]))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }

    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_tash_show($is_show='',$id=''){
        ($is_show=='' || $id=='') && Json::fail('缺少参数');
        $res=SystemUserTask::where(['id'=>$id])->update(['is_show'=>(int)$is_show]);
        if($res){
            return JsonService::successful($is_show==1 ? '显示成功':'隐藏成功');
        }else{
            return JsonService::fail($is_show==1 ? '显示失败':'隐藏失败');
        }
    }

    /**
     * 设置单个产品上架|下架
     *
     * @return json
     */
    public function set_tash_must($is_must='',$id=''){
        ($is_must=='' || $id=='') && Json::fail('缺少参数');
        $res=SystemUserTask::where(['id'=>$id])->update(['is_must'=>(int)$is_must]);
        if($res){
            return JsonService::successful('设置成功');
        }else{
            return JsonService::fail('设置失败');
        }
    }

    /*
     * 生成任务表单
     * @param int $id 任务id
     * @param int $vip_id 会员id
     * @return html
     * */
    public function create_tash($id=0,$level_id=0)
    {
        if($id) $tash=SystemUserTask::get($id);
        $field[]= Form::select('task_type','任务类型',isset($tash) ? $tash->task_type : '')->setOptions(function(){
            $list = SystemUserTask::getTaskTypeAll();
            $menus=[];
            foreach ($list as $menu){
                $menus[] = ['value'=>$menu['type'],'label'=>$menu['name'].'----单位['.$menu['unit'].']'];
            }
            return $menus;
        })->filterable(1);
        $field[]= Form::number('number','限定数量',isset($tash) ? $tash->number : 0)->min(0)->col(24);
        $field[]= Form::number('sort','排序',isset($tash) ? $tash->sort : 0)->min(0)->col(24);
        $field[]= Form::radio('is_show','是否显示',isset($tash) ? $tash->is_show : 1)->options([['label'=>'显示','value'=>1],['label'=>'隐藏','value'=>0]])->col(24);
        $field[]= Form::radio('is_must','是否务必达成',isset($tash) ? $tash->is_must : 1)->options([['label'=>'务必达成','value'=>1],['label'=>'完成其一','value'=>0]])->col(24);
        $field[]= Form::textarea('illustrate','任务说明',isset($tash) ? $tash->illustrate : '');
        $form = Form::make_post_form('添加任务',$field,Url::build('save_tash',['id'=>$id,'level_id'=>$level_id]),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /*
     * 保存或者修改任务
     * @param int $id 任务id
     * @param int $vip_id 会员id
     * */
    public function save_tash($id=0,$level_id=0)
    {
        if(!$level_id) return JsonService::fail('缺少参数');
        $data=UtilService::postMore([
            ['task_type',''],
            ['number',0],
            ['is_show',0],
            ['sort',0],
            ['is_must',0],
            ['illustrate',''],
        ]);
        if(!$data['task_type']) return JsonService::fail('请选择任务类型');
        if($data['number'] < 0) return JsonService::fail('请输入限定数量');
        $tash=SystemUserTask::getTaskType($data['task_type']);
        if($tash['max_number']!=0 && $data['number'] > $tash['max_number']) return JsonService::fail('您设置的限定数量超出上限限制,上限限制为:'.$tash['max_number']);
        $data['name']=SystemUserTask::setTaskName($data['task_type'],$data['number']);
        try{
            if($id){
                SystemUserTask::edit($data,$id);
                return JsonService::successful('修改成功');
            }else{
                $data['level_id']=$level_id;
                $data['add_time']=time();
                $data['real_name']=$tash['real_name'];
                if(SystemUserTask::set($data))
                    return JsonService::successful('添加成功');
                else
                    return JsonService::fail('添加失败');
            }
        }catch (\Exception $e){
            return JsonService::fail($e->getMessage());
        }
    }

    /*
     * 异步获取等级任务列表
     * @param int $vip_id 会员id
     * @param int $page 分页
     * @param int $limit 显示条数
     * @return json
     * */
    public function get_tash_list($level_id=0)
    {
        list($page,$limit)=UtilService::getMore([
            ['page',1],
            ['limit',10],
        ],$this->request,true);
        return JsonService::successlayui(SystemUserTask::getTashList($level_id,(int)$page,(int)$limit));
    }

    /*
     * 删除任务
     * @param int 任务id
     * */
    public function delete_tash($id=0)
    {
        if(!$id) return JsonService::fail('缺少参数');
        if(SystemUserTask::del($id))
            return JsonService::successful('删除成功');
        else
            return JsonService::fail('删除失败');
    }

    public function edit_content(Request $request){
        $id = 1 ;
        $type = $request->param()['type'] ;
        $content = empty($id) ? '' : SystemGradeDesc::where('type',$type)->find() ;

        $this->assign([
            'content'=> empty($content['description']) ? '': $content['description'],
            'field'=>'description',
            'action'=>Url::build('change_field',['id'=>empty($content['id']) ? 0 : $content['id'] ,'field'=>'description','type'=>$type])
        ]);
        return $this->fetch('public/edit_content');
    }

    public function change_field(Request $request,$id,$field){

        $data['description'] = $request->param()['description'];
        $data['type'] = $request->param()['type'];
        $id = $request->param()['id'];

        if(empty($id)){
            $res = SystemGradeDesc::set($data);
        }else{
            $res = SystemGradeDesc::edit($data,$id);
        }

        if($res)
            return JsonService::successful('添加成功');
        else
            return JsonService::fail('添加失败');
    }

}