<?php
namespace app\admin\controller\event;

use app\admin\controller\AuthController;
use app\admin\model\certification\CertificationDatum;
use app\admin\model\com\ComForum;
use app\admin\model\event\EventCategory as Cate;
use app\admin\model\event\Event as EventModel;
use app\admin\model\event\EventCheck;
use app\admin\model\event\EventEnroller;
use app\admin\model\event\EventField;
use app\admin\model\event\EventMessage;
use app\admin\model\group\Group;
use app\admin\model\system\SystemConfig;
use app\osapi\controller\Common;
use service\FormBuilder as Form;
use service\JsonService;
use service\PHPExcelService;
use service\UtilService as Util;
use think\Url;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class Event extends AuthController
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
        $cate=Cate::get_check_id();
        unset($cate[0]);
        $forum=ComForum::get_check_radio(['status'=>1,'pid'=>['gt',0]]);
        $this->assign([
            'cate'=>$cate,
            'forum'=>$forum,
            'event'=>$event
        ]);
        return $this->fetch('add_event');
    }
    public function event_detail(){
        $id=osx_input('id',0,'intval');
        $event=EventModel::getEvent($id);
        $event['nickname']=db('user')->where(['uid'=>$event['uid']])->value('nickname');
        $event['start_time']= $event['start_time']?date('Y-m-d H:i:s',$event['start_time']):'';
        $event['end_time']=$event['end_time']?date('Y-m-d H:i:s',$event['end_time']):'';
        $event['enroll_start_time']=$event['enroll_start_time']?date('Y-m-d H:i:s',$event['enroll_start_time']):'';
        $event['enroll_end_time']=$event['enroll_end_time']?date('Y-m-d H:i:s',$event['enroll_end_time']):'';
        $cate=Cate::get_check_id();
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
            'cate'=>$cate,
            'forum'=>$forum,
            'event'=>$event
        ]);
        return $this->fetch();
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
            ['big_cover',''],
            ['forum_id',0],
            ['type',0],
            ['start_time',0],
            ['end_time',0],
            ['address',''],
            ['city',''],
             ['province',''],
             ['district',''],
             ['lat',0],
             ['lng',0],
             ['product_id',''],

            ['detailed_address',''],
            ['detailed_lat',''],
            ['detailed_lng',''],
            ['enroll_start_time',0],
            ['enroll_end_time',0],
            ['enroll_count',''],
            ['enroll_range',0],
            ['price_type',0],
            ['price',0],
            ['is_need_check',0],
            ['is_recommend',0],
            ['content','','html'],
            ['group','','html']
        ],$this->request);
        $name=$params['id']?'编辑':'创建';
        if(!$params['title']){
            $this->apiError('请输入标题');
        }
        if(!$params['cate_id']){
            $this->apiError('请选择分类');
        }
        $params['is_need_check']=1;
        if(!$params['uid']){
            $this->apiError('请选择发起人');
        }
        $params['start_time']=strtotime($params['start_time']);
        $params['end_time']=strtotime($params['end_time']);
        $params['enroll_start_time']=strtotime($params['enroll_start_time']);
        $params['enroll_end_time']=strtotime($params['enroll_end_time']);

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
                EventMessage::send_message(58,$params['id'],0);
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
        $check_uid=db('event_check')->where(['event_id'=>$eid,'status'=>1])->column('uid');
        $check_user=db('user')->where(['uid'=>['in',$check_uid]])->field('uid,nickname')->select();
        $this->assign([
            'check_user'=>$check_user,
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
            ['check_user'],
        ]);
        $where['event_id']=$data['id'];
        $where['status']=1;
        if($data['user']){
            $user_id=db('user')->where(['uid|nickname'=>['like','%'.$data['user'].'%']]);
            $where['uid']=['in',$user_id];
        }
        if($data['enroll']==1){
            $where['check_time']=['gt',0];
        }elseif($data['enroll']==2){
            $where['check_time']=0;
        }
        if($data['user']){
            $where['check_uid']=$data['check_user'];
        }
        return JsonService::successlayui(EventEnroller::get_check_list($where,$data['page'],$data['limit'],'create_time desc'));
    }



    public function save_event_excel(){
      
        $data=EventModel::get_list(null,1,10000,$order='id desc',true)['data'];
     
        $excel=[];
        $headerStruct=[
                '活动'=>'title',
                '省'=>'province',
                '市'=>'city',
                '区'=>'district',
                '活动时间'=>'event_time',
                '报名时间'=>'enroll_time',
                '核销员'=>'check_user',
                '浏览'=>'view',
                '报名'=>'enroll_reality_count',
                '核销'=>'check_count'
            ];
        $header=[];
        foreach($headerStruct as $key=>$head)
        {
            $header[]=$key;
        }
         
        foreach ($data as $item){            
    
            $excel_value=[];
            foreach($headerStruct as $key=>$field)
            {
                if(isset($item[$field]))
                {
                    $excel_value[]= str_replace('<br/>','',$item[$field]) ;
                }
                else{
                    $excel_value[]='';
                }
                
            }
      
            $excel[]=$excel_value;
        }
        PHPExcelService::setExcelHeader($header)
            ->setExcelTile('活动表导出',' ',' 生成时间：'.date('Y-m-d H:i:s',time()))
            ->setExcelContent($excel)
            ->ExcelSave();
    }

    public function save_excel(){
        $data = Util::getMore([
            ['event_id',0],
            ['user',''],
            ['enroll',0,''],
            ['check_user',''],
        ]);
        $list = EventEnroller::where(['event_id'=>$data['event_id'],'status'=>1])->select()->toArray();
        $datum=EventField::where(['event_id'=>$data['event_id'],'status'=>1])->field('field,field_name')->select()->toArray();
        $field_name=array_column($datum,'field_name');
        $field=array_column($datum,'field');
        $excel=[];
        $header=['序号','报名人','报名时间','核销时间','核销员'];
        $header=array_merge($header,$field_name);
        foreach ($list as $item){
            $datum_value=db('event_enroller_info')->where(['uid'=>$item['uid'],'event_id'=>$data['event_id']])->field('field,content')->select();
            $value=[];
            foreach ($datum_value as $v){
                $value[$v['field']]=$v['content'];
                if($v['field']='sfzh'){//身份证号过长 excel当成数字了
                    $value[$v['field']]=$v['content'].' ';
                }
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
            ->setExcelTile('活动报名表导出',' ',' 生成时间：'.date('Y-m-d H:i:s',time()))
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
        $datum_datas=db('event_field')->where(['event_id'=>$enroller['event_id'],'status'=>1])->select();
        $where=array_column($datum_datas,'field');
        $enroller_info=db('event_enroller_info')->where(['event_id'=>$enroller['event_id'],'uid'=>$enroller['uid'],'status'=>1])->select();
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