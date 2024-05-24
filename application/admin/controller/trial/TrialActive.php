<?php
namespace app\admin\controller\trial;

use app\admin\controller\AuthController;
use app\admin\model\certification\CertificationDatum;
use app\admin\model\com\ComForum;
// use app\admin\model\event\EventCategory as Cate;
use app\admin\model\trial\TrialActive as EventModel;
 use app\admin\model\trial\TrialEnroller as  EventEnroller;
 use app\admin\model\trial\TrialEnroller  ;
// use app\admin\model\event\EventEnroller;
 use app\admin\model\trial\TrialField;
 use app\admin\model\trial\TrialField as EventField;
 use app\admin\model\trial\TrialMessage;
use app\admin\model\group\Group;
use app\admin\model\system\SystemConfig;
use app\osapi\controller\Common;
use service\FormBuilder as Form;
use service\JsonService;
use service\PHPExcelService;
use service\UtilService as Util;
use think\Url;
use think\Cache;
/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class TrialActive extends AuthController
{
    /**
     * 分类列表页
     * @return mixed
     */
    public function index(){
        $pid=osx_input('pid',0,'intval');
        $status=osx_input('status',0,'intval');
        $this->assign([
            'pid'=>$pid,
            'year' => getMonth('y'),
            'status'=>$status,
            'real_name'=>'',
            'time'=>time()
        ]);
        return $this->fetch();
    }

    
    /**
     * 创建
     * @return mixed
     */
    public function create(){
        $id=osx_input('id',0,'intval');
        $event=EventModel::getEvent($id);
        $event['nickname']=db('user')->where(['uid'=>$event['uid']])->value('nickname');
        $event['start_time']= $event['start_time']?date('Y-m-d H:i:s',$event['start_time']):'';
        $event['end_time']=$event['end_time']?date('Y-m-d H:i:s',$event['end_time']):'';
        $event['enroll_start_time']=$event['enroll_start_time']?date('Y-m-d H:i:s',$event['enroll_start_time']):'';
        $event['enroll_end_time']=$event['enroll_end_time']?date('Y-m-d H:i:s',$event['enroll_end_time']):'';
        $event['draw_overdue_time']=$event['draw_overdue_time']?date('Y-m-d',$event['draw_overdue_time']):'';
        $event['publish_time']=$event['publish_time']?date('Y-m-d',$event['publish_time']):'';
        if($id){
            $group=db('event_bind_group')->where(['event_id'=>$id,'status'=>1])->value('group');
            if($group){
                $event['g_id']=$group;
                $g_id=explode(',',$group);
                $g_name=Group::where(['id'=>['in',$g_id]])->field('name')->select()->toArray();
                $g_name=array_column($g_name,'name');
                $event['g_name']=implode(',',$g_name);
            }else{
                $event['g_id']=$event['g_name']='';
            }
        }else{
            $event['g_id']= $event['g_name']='';
        }
    
     
        $forum=ComForum::get_check_radio(['status'=>1,'pid'=>['gt',0]]);
        $this->assign([
        
            'forum'=>$forum,
            'event'=>$event
        ]);
        return $this->fetch('add_active');
    }
    public function event_detail(){
        $id=osx_input('id',0,'intval');
        $event=EventModel::getEvent($id);
        $event['nickname']=db('user')->where(['uid'=>$event['uid']])->value('nickname');
        $event['start_time']= $event['start_time']?date('Y-m-d H:i:s',$event['start_time']):'';
        $event['end_time']=$event['end_time']?date('Y-m-d H:i:s',$event['end_time']):'';
        $event['enroll_start_time']=$event['enroll_start_time']?date('Y-m-d H:i:s',$event['enroll_start_time']):'';
        $event['enroll_end_time']=$event['enroll_end_time']?date('Y-m-d H:i:s',$event['enroll_end_time']):'';
        // $cate=Cate::get_check_id();
        $group=db('event_bind_group')->where(['event_id'=>$id,'status'=>1])->value('group');
        if($group){
            $event['g_id']=$group;
            $g_id=explode(',',$group);
            $g_name=Group::where(['id'=>['in',$g_id]])->field('name')->select()->toArray();
            $g_name=array_column($g_name,'name');
            $event['g_name']=implode(',',$g_name);
        }else{
            $event['g_id']=$event['g_name']='';
        }
        unset($cate[0]);
        $forum=ComForum::get_check_radio(['status'=>1,'pid'=>['gt',0]]);
        $this->assign([
            // 'cate'=>$cate,
            'forum'=>$forum,
            'event'=>$event
        ]);
        return $this->fetch();
    }
 

    public function get_list(){
        $pam = Util::getMore([
            ['status', ''],
            ['page',''],
            ['limit',10],
            ['data',''],
            ['enroll',''],
            ['user_name',''],
            ['title',''],
            ['type',''],
            ['price_type',''],
        ]);
        $where['status']=['egt',0];
        if($pam['title']!==''){
            $where['title']=['like','%'.$pam['title'].'%'];
        }
        if($pam['status']==-1){
            $where['status']=$pam['status'];
        }
        $time=time();
        if($pam['enroll']==1){
            $where['enroll_start_time']=['lt',$time];
            $where['enroll_end_time']=['gt',$time];
        }elseif($pam['enroll']==2){
            $where['enroll_end_time']=['lt',$time];
        }
        if($pam['user_name']!==''){
            $uids=db('user')->where(['nickname'=>['like','%'.$pam['user_name'].'%']])->column('uid');
            $where['uid']=['in',$uids];
        }
        if($pam['type']!==''){
            $where['type']=['in',$pam['type']];
        }
        if($pam['price_type']!==''){
            $where['price_type']=['in',$pam['price_type']];
        }
        if($pam['data']!==''){
            $data['create_time']=Common::timeRange($pam['data']);
        }
        return JsonService::successlayui(EventModel::get_list($where,$pam['page'],$pam['limit'],$order='id desc'));
    }

   

    //通过申请
    public function set_enroller_state(){
 
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        $res=TrialEnroller::where(['id'=>$id,'status'=>$status])->update([
            'status'=>2,
            'check_uid'=>$this->adminId,
            'check_time'=>time()
        ]);
        $name='申请';
        if($res){
            $this->apiSuccess($name.'成功');
        }else{
            $this->apiError($name.'错误');
        }
    }

    /**
     * 设置活动状态
     */
    public function set_event_status(){
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        $data['status']=$status;
        if($status==-1){
            $name='删除';
            $data['delete_time']=time();
        }elseif($status==0){
            $name='取消';
        }else{
            $name='开启';
        }
        $res=EventModel::where(['id'=>$id])->update($data);
        if($res){
            $this->apiSuccess('操作成功');
        }else{
            $this->apiError($name.'错误');
        }
    }

    /**
     * 创建/编辑活动
     */
    public function edit_event(){
        $params = Util::postMore([
            ['id',0],
            ['title',''],
            ['cate_id',0],
            ['uid',0],
            ['cover',''],
            ['product_cover',''],
            ['forum_id',0],
            ['product_id',0],
            ['type',0],
            ['start_time',0],
            ['end_time',0],
            ['address',''],
            ['detailed_address',''],
            ['enroll_start_time',0],
            ['enroll_end_time',0],
            ['enroll_count',''],
            ['enroll_range',0],
            ['price_type',0],
            ['price',0],
            ['is_need_check',0],
            ['is_recommend',0],
            ['draw_overdue_time',0],
            ['publish_time',0],
            ['is_vip_postage',0],
            ['content','','html'],
            ['group','','html']
        ],$this->request);
        $name=$params['id']?'编辑':'创建';
        if(!$params['title']){
            $this->apiError('请输入标题');
        }
 
        $params['is_need_check']=1;
        if(!$params['uid']){
            $this->apiError('请选择发起人');
        }
        $params['start_time']=strtotime($params['start_time']);
        $params['end_time']=strtotime($params['end_time']);
        $params['enroll_start_time']=strtotime($params['enroll_start_time']);
        $params['enroll_end_time']=strtotime($params['enroll_end_time']);
        $params['draw_overdue_time']=strtotime($params['draw_overdue_time']);
        $params['publish_time']=strtotime($params['publish_time']);
        // dump(json_encode($params).'123fffadf');
        // return;
        $res=EventModel::editEvent($params);
        if($res!==false){
            if($params['enroll_range']==2){
                if($params['id']){
                    db('event_bind_group')->insert(['event_id'=>$params['id'],'group'=>$params['group'],'create_time'=>time(),'status'=>1]);
                }else{
                    db('event_bind_group')->insert(['event_id'=>$res,'group'=>$params['group'],'create_time'=>time(),'status'=>1]);
                }
            }
            $this->apiSuccess($name.'活动成功');
        }else{
            $this->apiError($name.'活动失败');
        }
    }

    /**
     * 2020.7.29 活动取消
     */
    public function cancel(){
        $params = Util::getMore([
            ['id',0],
            ['cancel_reason',''],
            ['is_post',0],
        ],$this->request);
        if($params['is_post']==1){
            $params['status']=0;
            $res=EventModel::editEvent($params);
            if($res){
                TrialMessage::send_message(58,$params['id'],0);
                $this->apiSuccess('取消成功');
            }else{
                $this->apiError('取消失败');
            }
        }else {
            $field = [
                Form::textarea('cancel_reason', '取消理由',''),
                Form::hidden('id', $params['id']),
                Form::hidden('is_post', 1),
            ];
            $form = Form::make_post_form('取消', $field, Url::build('cancel'), 2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
    }
    /**
     * 设置内容
     * @return mixed
     */
    public function bind_field(){
        $id=osx_input('id',0,'intval');
        if(!$id){
            $this->apiError('请选择需要设置的活动');
        }
        $where['use_range'] = array('like','%2%');
        $where['status']=1;
        $datums=CertificationDatum::getList($where);
        $bind=EventField::getEventField($id);
        $field=array_column($bind,'field');
        $this->assign([
            'datum'=>$datums,
            'bind'=>$bind,
            'field'=>$field,
            'id'=>$id,
            'url'=>get_domain(),
        ]);
        return $this->fetch();
    }

    /**
     *绑定活动需要报名的字段
     */
    public function set_bind_field(){
        $pam = Util::getMore([
            ['id',''],
            ['name',[]],
            ['need', []],
        ]);

        $name=$pam['name'];
        $need=$pam['need'];
        $data=[];
        $time=time();
        foreach ($name as $key=>$v){
            $data[$key]['field']=$v;
            $data[$key]['field_name']=CertificationDatum::where(['field'=>$v])->value('name');
            $data[$key]['is_need']=$need[$key]=='true'?1:0;
            $data[$key]['status']=1;
            $data[$key]['create_time']=$time;
            $data[$key]['event_id']=$pam['id'];
        }
        unset($v);
        $res=EventField::set_bind_field($pam['id'],$data);
        if($res){
            $this->apiSuccess('设置成功');
        }else{
            $this->apiError('设置失败');
        }
    }

    /**
     * 2020.7.28 设置推荐
     */
    public function set_recommend(){
        $pam = Util::getMore([
            ['id',''],
            ['field',''],
            ['value', ''],
        ]);
        $res=EventModel::where(['id'=>$pam['id']])->setField($pam['field'],$pam['value']);
        if($res!==false){
            $this->apiSuccess('推荐成功');
        }else{
            $this->apiError('推荐失败');
        }
    }

    public function set_end(){
        $pam = Util::getMore([
            ['id',''],
            ['value', ''],
        ]);
        $res=EventModel::where(['id'=>$pam['id']])->setField('is_end',$pam['value']);
        if($res!==false){
            $this->apiSuccess('推荐成功');
        }else{
            $this->apiError('推荐失败');
        }
    }

    /**
     * 绑定列表页面
     */
    public function check_view(){
        $id=osx_input('id',0,'intval');
        if(!$id){
            $this->apiError('请选择需要设置的活动');
        }
        $this->assign([
            'id'=>$id
        ]);
       return $this->fetch('set_check');
    }

    /**
     * 设置核销员
     */
    public function bind_check(){
        $pam = Util::getMore([
            ['uid',''],
            ['id',''],
        ]);
        if(!$pam['id']){
            $this->apiError('请选择需要设置的活动');
        }

        if(!$pam['uid']){
            $this->apiError('请选择设置为核销员的用户');
        }

        if(db('event_check')->where(['uid'=>$pam['uid'],'event_id'=>$pam['id'],'status'=>1])->count()){
            $this->apiError('该用户已经是该活动的核销员了');
        }
        $data=['uid'=>$pam['uid'],'event_id'=>$pam['id'],'status'=>1];
        $res=EventCheck::addCheck($data);
        if($res){
            $this->apiSuccess('设置成功');
        }else{
            $this->apiError('设置失败');
        }
    }
    /**
     *获取核销人员列表
     */
    public function get_check_list(){
        $pam = Util::getMore([
            ['page',''],
            ['limit',10],
            ['event_id',0],
        ]);
        $where['status']=1;
        $where['event_id']=$pam['event_id'];
        return JsonService::successlayui(EventCheck::get_list($where,$pam['page'],$pam['limit'],$order='create_time desc'));
    }

    /**
     * 设置核销人员状态
     */
    public function set_check_status(){
        $pam = Util::getMore([
            ['id',''],
            ['field',''],
            ['value', ''],
        ]);
        $res=EventCheck::where(['id'=>$pam['id']])->setField($pam['field'],$pam['value']);
        if($res!==false){
            $this->apiSuccess('删除成功');
        }else{
            $this->apiError('删除失败');
        }
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function set()
    {
        $list=db('system_rule')->where('is_del',0)->where('status',1)->where('flag','neq','exp')->select();
        $is_on=SystemConfig::getValue('event_on');
        $type=SystemConfig::getValue('event_type_pay');
        $this->assign('is_on', $is_on);
        $this->assign('type', $type);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 保存设置类型
     */
    public function save_score_type()
    {
        $data = Util::postMore([
            'is_on',
            'flag',
        ]);
        if(!$data['flag'])  $this->apiSuccess('积分类型不能为空');
        $map1['value']=json_encode($data['flag']);
        $map2['value']=json_encode($data['is_on']);
        $res1=db('system_config')->where('menu_name','event_type_pay')->update($map1);
        $res2=db('system_config')->where('menu_name','event_on')->update($map2);
        if($res1!==false&&$res2!==false){
            $this->apiSuccess('修改成功');
        }else{
            $this->apiSuccess('修改失败');
        }
    }

    /**
     * 活动报名列表页
     * @return mixed
     */
    public function show_check(){
        $data = Util::getMore(['id']);
        $eid=$data['id'];
        
        // $check_user=db('user')->where(['uid'=>['in',$check_uid]])->field('uid,nickname')->select();
        $this->assign([
     
            'id'=>$eid
        ]);
        return $this->fetch();
    }

    /**
     * 活动报名列表
     */
    public function get_show_check_list(){
        $data = Util::getMore([
            ['id',0],
            ['page',0],
            ['limit',0],
            ['user',''],
            ['enroll',0],
            // ['check_user'],
        ]);
        $where['event_id']=$data['id'];
        $where['status']=['>',0];
        if($data['user']){
            $user_id=db('user')->where('uid|nickname','like','%'.$data['user'].'%')->column('uid');
            $where['uid']=['in',$user_id];
        }
         
        if($data['enroll']){
            $where['status']=$data['enroll'];
        } 
 
        return JsonService::successlayui(TrialEnroller::get_check_list($where,$data['page'],$data['limit'],'create_time desc'));
    }

    public function save_excel(){
        $data = Util::getMore([
            ['event_id',0],
            ['user',''],
            ['enroll',0,''],
            ['check_user',''],
        ]);
        $list = TrialEnroller::where(['event_id'=>$data['event_id'],'status'=>['>',0]])->select()->toArray();
        $datum=TrialField::where(['event_id'=>$data['event_id'],'status'=>['>',0]])->field('field,field_name')->select()->toArray();
        $field_name=array_column($datum,'field_name');
        $field=array_column($datum,'field');
        $excel=[];
        $header=['序号','申请人','申请时间','审核时间','审核员'];
        $header=array_merge($header,$field_name);
        foreach ($list as $item){
            $datum_value=db('trial_enroller_info')->where(['uid'=>$item['uid'],'event_id'=>$data['event_id']])->field('field,content')->select();
            $value=[];
            foreach ($datum_value as $v){
                $value[$v['field']]=$v['content'];
            }
            unset($v);
            $excel_value=[];
            foreach ($field as $v){
                $excel_value[]=array_key_exists($v,$value)?$value[$v]:'';
            }
            unset($v);
            $item['user']=db('user')->where(['uid'=>$item['uid']])->value('nickname');
            $item['create_time']=date('Y-m-d H:i:s',$item['create_time']);
            $item['check_time']=$item['check_time']?date('Y-m-d H:i:s',$item['check_time']):'';
            $item['check_user']=$item['check_uid']?db('user')->where(['uid'=>$item['check_uid']])->value('nickname'):'';
            $data_value=[
                $item['id'],
                $item['user'],
                $item['create_time'],
                $item['check_time'],
                $item['check_user'],
            ];
            $excel[]=array_merge($data_value,$excel_value);
        }
        PHPExcelService::setExcelHeader($header)
            ->setExcelTile('试用申请表导出',' ',' 生成时间：'.date('Y-m-d H:i:s',time()))
            ->setExcelContent($excel)
            ->ExcelSave();
    }

    /**
     * 用户显示
     * @return mixed
     */
    public function show_user(){
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $enroller = EventEnroller::get($id);
        if(!$enroller) return JsonService::fail('数据不存在!');
        $datum_datas=db('trial_field')->where(['event_id'=>$enroller['event_id'],'status'=>1])->select();
        $where=array_column($datum_datas,'field');
        $enroller_info=db('trial_enroller_info')->where(['event_id'=>$enroller['event_id'],'uid'=>$enroller['uid'],'status'=>1])->select();
        $enroller_info=array_column($enroller_info,'content','field');
        $datums=CertificationDatum::where('field','in',$where)->field('field,form_type,name')->select()->toArray();
        $field=[];
        foreach ($datums as $key => $value) {
            $enroller_info[$value['field']]=!isset($enroller_info[$value['field']])?'':$enroller_info[$value['field']];
            switch ($value['form_type']) {
                case 'text':
                    $field[] = Form::input($value['field'],$value['name'],$enroller_info[$value['field']])->col(Form::col(24))->readonly(true);
                    break;
                case 'file':
                    $field[] = Form::frameImageOne($value['field'],$value['name'],Url::build('admin/widget.images/index',array('fodder'=>'')), $enroller_info[$value['field']])->icon('image')->width('100%')->height('500px')->allowRemove(false);
                    break;
                default:
                    $field[] = Form::input($value['field'],$value['name'],$enroller_info[$value['field']])->col(Form::col(24))->readonly(true);
                    break;
            }
        }
        $form = Form::make_post_form('详情',$field, Url::build('view'),2);
        $form->hiddenSubmitBtn(true);
        $form->hiddenResetBtn(true);
        $this->assign(compact('form'));
        return $this->fetch('certification/entity/form-builder');
    }
}