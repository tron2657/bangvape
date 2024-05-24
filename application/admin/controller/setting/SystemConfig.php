<?php
namespace app\admin\controller\setting;
use app\core\util\TencentCosService;
use app\osapi\model\file\Picture;
use think\Config;
use think\Url;
use service\FormBuilder as Form;
use think\Request;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use app\admin\controller\AuthController;
use app\admin\model\system\SystemConfig as ConfigModel;
use service\JsonService;
use app\admin\model\user\UserAgreement as AgreementModel;
use app\admin\model\system\SystemGradeDesc;
use app\osapi\model\com\ComThread;


/**
 *  配置列表控制器
 * Class SystemConfig
 * @package app\admin\controller\system
 */
class SystemConfig extends AuthController
{
    /**
     * 基础配置
     * */
   public function index(){
       $type = input('type')!=0?input('type'):0;
       $tab_id = input('tab_id');
       if(!$tab_id) $tab_id = 1;
       $this->assign('tab_id',$tab_id);
       //如果未授权的情况下
       $open_list=$this->_getClientOpenList();
       if(!in_array('weixin_flow',$open_list)){
           ConfigModel::where(['menu_name'=>'information_stream_open'])->update(['value'=>"0"]);
       }
       $list = ConfigModel::getAll($tab_id);
       if($type==3){//其它分类
           $config_tab = null;
       }else{
           $config_tab = ConfigModel::getConfigTabAll($type);
           foreach ($config_tab as $kk=>$vv){
               $arr = ConfigModel::getAll($vv['value'])->toArray();
               if(empty($arr)){
                   unset($config_tab[$kk]);
               }
           }
       }


       foreach ($list as $k=>$v){
           if(!is_null(json_decode($v['value'])))
               $list[$k]['value'] = json_decode($v['value'],true);
           if($v['type'] == 'upload' && !empty($v['value'])){
               if($v['upload_type'] == 1 || $v['upload_type'] == 3) $list[$k]['value'] = explode(',',$v['value']);
           }
       }
       $this->assign('config_tab',$config_tab);
       $this->assign('list',$list);
       return $this->fetch();
   }
    /**
     * 基础配置  单个
     * @return mixed|void
     */
    public function index_alone(){
        $tab_id = input('tab_id');
        if(!$tab_id) return $this->failed('参数错误，请重新打开');
        $this->assign('tab_id',$tab_id);
        $list = ConfigModel::getAll($tab_id);
        foreach ($list as $k=>$v){
            if(!is_null(json_decode($v['value'])))
                $list[$k]['value'] = json_decode($v['value'],true);
            if($v['type'] == 'upload' && !empty($v['value'])){
                if($v['upload_type'] == 1 || $v['upload_type'] == 3) $list[$k]['value'] = explode(',',$v['value']);
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
   /**
    * 添加字段
    * */
   public function create(Request $request){
       $data = Util::getMore(['type',],$request);//接收参数
       $tab_id = !empty($request->param('tab_id'))?$request->param('tab_id'):1;
       $formbuider = array();
       switch ($data['type']){
           case 0://文本框
               $formbuider = ConfigModel::createInputRule($tab_id);
               break;
           case 1://多行文本框
               $formbuider = ConfigModel::createTextAreaRule($tab_id);
               break;
           case 2://单选框
               $formbuider = ConfigModel::createRadioRule($tab_id);
               break;
           case 3://文件上传
               $formbuider = ConfigModel::createUploadRule($tab_id);
               break;
           case 4://多选框
               $formbuider = ConfigModel::createCheckboxRule($tab_id);
               break;
       }
       $form = Form::make_post_form('添加字段',$formbuider,Url::build('save'));
       $this->assign(compact('form'));
       return $this->fetch('public/form-builder');
   }
   /**
    * 保存字段
    * */
   public function save(Request $request){
       $data = Util::postMore([
           'menu_name',
           'type',
           'config_tab_id',
           'parameter',
           'upload_type',
           'required',
           'width',
           'high',
           'value',
           'info',
           'desc',
           'sort',
           'status',],$request);
       if(!$data['info']) return Json::fail('请输入配置名称');
       if(!$data['menu_name']) return Json::fail('请输入字段名称');
       if($data['menu_name']){
           $oneConfig = ConfigModel::getOneConfig('menu_name',$data['menu_name']);
           if(!empty($oneConfig)) return Json::fail('请重新输入字段名称,之前的已经使用过了');
       }
       if(!$data['desc']) return Json::fail('请输入配置简介');
       if($data['sort'] < 0){
           $data['sort'] = 0;
       }
       if($data['type'] == 'text'){
           if(!ConfigModel::valiDateTextRole($data)) return Json::fail(ConfigModel::getErrorInfo());
       }
       if($data['type'] == 'textarea'){
           if(!ConfigModel::valiDateTextareaRole($data)) return Json::fail(ConfigModel::getErrorInfo());
       }
       if($data['type'] == 'radio' || $data['type'] == 'checkbox' ){
           if(!$data['parameter']) return Json::fail('请输入配置参数');
           if(!ConfigModel::valiDateRadioAndCheckbox($data)) return Json::fail(ConfigModel::getErrorInfo());
           $data['value'] = json_encode($data['value']);
       }
       ConfigModel::set($data);
       return Json::successful('添加菜单成功!');
   }
    /**
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function update_config(Request $request, $id)
    {
        $type = $request->post('type');
        if($type =='text' || $type =='textarea'|| $type == 'radio' || ($type == 'upload' && ($request->post('upload_type') == 1 || $request->post('upload_type') == 3))){
            $value = $request->post('value');
        }else{
            $value = $request->post('value/a');
        }
        $data = Util::postMore(['status','info','desc','sort','config_tab_id','required','parameter',['value',$value],'upload_type'],$request);
        $data['value'] = json_encode($data['value']);
        if(!ConfigModel::get($id)) return Json::fail('编辑的记录不存在!');
        ConfigModel::edit($data,$id);
        return Json::successful('修改成功!');
    }

    /**
     * 修改是否显示子子段
     * @param $id
     * @return mixed
     */
    public function edit_cinfig($id){
        $menu = ConfigModel::get($id)->getData();
        if(!$menu) return Json::fail('数据不存在!');
        $formbuider = array();
        $formbuider[] = Form::input('menu_name','字段变量',$menu['menu_name'])->disabled(1);
//        $formbuider[] = Form::input('type','字段类型',$menu['type'])->disabled(1);
        $formbuider[] = Form::hidden('type',$menu['type']);
        $formbuider[] = Form::select('config_tab_id','分类',(string)$menu['config_tab_id'])->setOptions(ConfigModel::getConfigTabAll(-1));
        $formbuider[] = Form::input('info','配置名称',$menu['info'])->autofocus(1);
        $formbuider[] = Form::input('desc','配置简介',$menu['desc']);
        switch ($menu['type']){
            case 'text':
                $menu['value'] = json_decode($menu['value'],true);
                //输入框验证规则
                $formbuider[] = Form::input('value','默认值',$menu['value']);
                if(!empty($menu['required'])){
                    $formbuider[] = Form::number('width','文本框宽(%)',$menu['width']);
                    $formbuider[] = Form::input('required','验证规则',$menu['required'])->placeholder('多个请用,隔开例如：required:true,url:true');
                }
                break;
            case 'textarea':
                $menu['value'] = json_decode($menu['value'],true);
                //多行文本
                if(!empty($menu['high'])){
                    $formbuider[] = Form::textarea('value','默认值',$menu['value'])->rows(5);
                    $formbuider[] = Form::number('width','文本框宽(%)',$menu['width']);
                    $formbuider[] = Form::number('high','多行文本框高(%)',$menu['high']);
                }else{
                    $formbuider[] = Form::input('value','默认值',$menu['value']);
                }
                break;
            case 'radio':
                $menu['value'] = json_decode($menu['value'],true);
                $parameter = explode("\n",$menu['parameter']);
                $options = [];
                if($parameter){
                    foreach ($parameter as $v){
                        $data = explode("=>",$v);
                        $options[] = ['label'=>$data[1],'value'=>$data[0]];
                    }
                    $formbuider[] = Form::radio('value','默认值',$menu['value'])->options($options);
                }
                //单选和多选参数配置
                if(!empty($menu['parameter'])){
                    $formbuider[] = Form::textarea('parameter','配置参数',$menu['parameter'])->placeholder("参数方式例如:\n1=白色\n2=红色\n3=黑色");
                }
                break;
            case 'checkbox':
                $menu['value'] = json_decode($menu['value'],true)?:[];
                $parameter = explode("\n",$menu['parameter']);
                $options = [];
                if($parameter) {
                    foreach ($parameter as $v) {
                        $data = explode("=>", $v);
                        $options[] = ['label' => $data[1], 'value' => $data[0]];
                    }
                    $formbuider[] = Form::checkbox('value', '默认值', $menu['value'])->options($options);
                }
                //单选和多选参数配置
                if(!empty($menu['parameter'])){
                    $formbuider[] = Form::textarea('parameter','配置参数',$menu['parameter'])->placeholder("参数方式例如:\n1=白色\n2=红色\n3=黑色");
                }
                break;
            case 'upload':
                if($menu['upload_type'] == 1 ){
                    $menu['value'] = json_decode($menu['value'],true);
                    $formbuider[] =  Form::frameImageOne('value','图片',Url::build('admin/widget.images/index',array('fodder'=>'value')),(string)$menu['value'])->icon('image')->width('100%')->height('550px');
                }elseif ($menu['upload_type'] == 2 ){
                    $menu['value'] = json_decode($menu['value'],true)?:[];
                    $formbuider[] =  Form::frameImages('value','多图片',Url::build('admin/widget.images/index',array('fodder'=>'value')),$menu['value'])->maxLength(5)->icon('images')->width('100%')->height('550px')->spin(0);
                }else{
                    $menu['value'] = json_decode($menu['value'],true);
                    $formbuider[] =  Form::uploadFileOne('value','文件',Url::build('file_upload'))->name('file');
                }
                //上传类型选择
                if(!empty($menu['upload_type'])){
                    $formbuider[] = Form::radio('upload_type','上传类型',$menu['upload_type'])->options([['value'=>1,'label'=>'单图'],['value'=>2,'label'=>'多图'],['value'=>3,'label'=>'文件']]);
                }
                break;

        }
        $formbuider[] = Form::number('sort','排序',$menu['sort']);
        $formbuider[] = Form::radio('status','状态',$menu['status'])->options([['value'=>1,'label'=>'显示'],['value'=>2,'label'=>'隐藏']]);

        $form = Form::make_post_form('编辑字段',$formbuider,Url::build('update_config',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }
    /**
     * 删除子字段
     * @return \think\response\Json
     */
    public function delete_cinfig(){
        $id = input('id');
        if(!ConfigModel::del($id))
            return Json::fail(ConfigModel::getErrorInfo('删除失败,请稍候再试!'));
        else
            return Json::successful('删除成功!');
    }

    /**
     * 保存数据    true
     * */
    public function save_basics(){
        $request = Request::instance();
        if($request->isPost()){
            $post = $request->post();
            $tab_id = $post['tab_id'];
            unset($post['tab_id']);

            //判断底部版权是否可以修改
            $open_list=$this->_getClientOpenList();
            if(!in_array('right_copy',$open_list)){
                if(array_key_exists('website_logo_show',$post)){
                    unset($post['website_logo_show']);
                }
                if(array_key_exists('website_url',$post)){
                    unset($post['website_url']);
                }
            }
            if(!in_array('weixin_flow',$open_list)){
                if(array_key_exists('weixin_flow',$post)){
                    $post['weixin_flow']="0";
                }
            }

            //如果是视频配置的 有些参数必须填写 zxh 2020.8.14
            if($tab_id==34){
                if($post['video_title']==0){
                    return $this->failNotice('请填写标题字数上限');
                }
                if($post['video_content']==0){
                    return $this->failNotice('请填写标题字数上限');
                }
            }
            if ((int)$tab_id == 110) {
                if ((int)$post['information_stream_ad_min'] > (int)$post['information_stream_ad_max']){
                    return $this->failNotice('最小间隔不能大于最大间隔');
                }
            }
            foreach ($post as $k=>$v){
                if(is_array($v)){
                    $res = ConfigModel::where('menu_name',$k)->column('type,upload_type');
                    foreach ($res as $kk=>$vv){
                        if($kk == 'upload'){
                            if($vv == 1 || $vv == 3){
                                $post[$k] = $v[0];
                            }
                        }
                    }
                }
            }
            foreach ($post as $k=>$v){
                ConfigModel::edit(['value' => json_encode($v)],$k,'menu_name');
            }
            return $this->successfulNotice('修改成功');
        }
    }
   /**
    * 模板表单提交
    * */
   public function view_upload(){
       if($_POST['type'] == 3){
           $res = Upload::file($_POST['file'],'config/file');
           if(!$res->status) return Json::fail($res->error);
           return Json::successful('上传成功!',['url'=>$res->dir]);
       }else{
           $file = request()->file($_POST['file']);
           $tmp_info=$file->getInfo();
           $isExist=Picture::checkExist($tmp_info['tmp_name']);
           if($isExist){
               return Json::successful('上传成功!', ['url' => $isExist['path']]);
           }else {
               $upload_type = ConfigModel::getValue('picture_store_place');
               switch ($upload_type) {
                   case 'Tencent_COS':
                       $picture_upload=Config::get('TENCENT_COS_PICTURE_UPLOAD');
                       if(!$file->check($picture_upload)){
                           $err=$file->getError();
                           return Json::fail('图片上传失败：'.$err);
                       }
                       //调用腾讯云上传
                       $result = TencentCosService::tencentCOSUpload($tmp_info);
                       if($result['result']==true){
                           Picture::uploadTencentCOS($result['info']);
                           return Json::successful('上传成功!', ['url' => $result['info']['path']]);
                       }else{
                           return Json::fail($result['info']);
                       }
                       break;
                   case 'local':
                   default:
                       $picture_upload=Config::get('PICTURE_UPLOAD');
                       $info = $file->validate($picture_upload)->rule($picture_upload['nameBuilder'])->move($picture_upload['rootPath']);
                       if ($info) {
                           // 成功上传后 获取上传信息
                           Picture::upload($info);
                           return Json::successful('上传成功!', ['url' => $picture_upload['db_rootPath'].'/'.$info->getSaveName()]);
                       }else{
                           return Json::fail($file->getError());
                       }
               }
           }
       }
   }
   /**
    * 文件上传
    * */
   public function file_upload(){
       $res = Upload::file($_POST['file'],'config/file');
       if(!$res->status) return Json::fail($res->error);
       return Json::successful('上传成功!',['url'=>$res->dir]);
   }


    /**
     * 获取文件名
     * */
    public function getImageName(){
        $request = Request::instance();
        $post = $request->post();
        $src = $post['src'];
        $data['name'] = basename($src);
        exit(json_encode($data));
    }
    /**
     * qxh
     * 后台协议集中管理
    */
    public function all_agreement(){
        $status=1;
        $this->assign('status',$status);
        return $this->fetch();
    }
    /**
     * 删除原来图片
     * @param $url
     */
    /*public function rmPublicResource($url)
    {
        $res = Util::rmPublicResource($url);
        if($res->status)
            return $this->successful('删除成功!');
        else
            return $this->failed($res->msg);
    }*/

    /**
     * 网站所有协议管理
     */
    public function agreement_list(){
        $where=Util::getMore([
            ['status',     1],
        ]);
        $data= db('all_agreement')->where($where)->select();
        foreach($data as $k=>$v){
            $data[$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
        }
        return JsonService::successlayui(count(db('all_agreement')->where($where)->select()),$data);
    }
    public function edit($id)
    {
        if(!$id) return $this->failed('数据不存在');
        //用户协议及隐私协议数据
        if($id == 1||$id == 2){
            $Agreement = AgreementModel::getOne_all($id);
        }else if($id == 3||$id == 4||$id==5||$id==9){
            //分销协议数据展示
            if($id == 3){
                $menus = 'agent_xieyi_config';
            }
            //分销收益修改展示
            if($id == 4){
                $menus = 'agent_income_config';
            }
            if($id == 5){
                $menus = 'agent_tixian_config_rules';
            }if($id == 9){
                $menus = 'reward_points_rules';
            }
            $Agreement = db('all_agreement')->where('id',$id)->find(); 
            $Agreement['content'] = ConfigModel::getValue($menus);
        }else if($id ==6 || $id == 7 || $id ==8  ){
            $Agreement = db('all_agreement')->where('id',$id)->find(); 
            $Agreement['content'] = SystemGradeDesc::where('all_agreement_id',$id)->value('description');
        }
 
        
        if(!$Agreement) return Json::fail('数据不存在!');
        $this->assign('Agreement',$Agreement);
        return $this->fetch('edit');
    }

    
    public function rich_text($code,$id)
    {
        $config= \app\admin\model\system\SystemConfig::where('menu_name',$code)->find();
 
        $this->assign('menu_name',$code);
        $this->assign('info',$config['info']);
        $this->assign('agreement_id',$id);
        $this->assign('content',$config['value'] );
        return $this->fetch();
    }

    public function save_rich_text()
    {
        $request = Request::instance();
        if($request->isPost()){
            $meun_name=osx_input('post.meun_name');
            $agreement_id=osx_input('post.agreement_id');
            $content = osx_input('post.content','','html');
            $result=\app\admin\model\system\SystemConfig::edit(['value' => $content],$meun_name,'menu_name');
            //db('all_agreement')->where('id',$agreement_id)->update(['update_time'=>time()]);
            return JsonService::successful('修改成功');
        }
    }

    public function edit_agreement(Request $request){
        $data = Util::postMore([
            'name',
            'id',
        ],$request);
        $data['content']=osx_input('post.content','','html');
        if(!$data['name']){
            JsonService::fail('协议名称不能为空');
        }
        if(mb_strlen($data['name'],'UTF-8')>8){
            JsonService::fail('协议名称最多只允许八个字。');
        }
        //用户协议及隐私协议修改
        if($data['id'] == 1|| $data['id'] == 2){
            $result = AgreementModel::where('all_agreement_id',$data['id'])->update($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
            $data['update_time'] = time();
            $result_all = db('all_agreement')->where('id',$data['id'])->update($data);
        }else if($data['id'] == 3||$data['id'] == 4||$data['id']==5||$data['id'] == 9){
            //总表分销协议修改
            if($data['id'] == 3){
                $menus = 'agent_xieyi_config';
            }
            //分销收益说明协议修改
            if($data['id'] == 4){
                $menus = 'agent_income_config';
            }
            //分销提现说明修改
            if($data['id'] == 5){
                $menus = 'agent_tixian_config_rules';
            }
            if($data['id'] == 9){
                $menus = 'reward_points_rules';
            }
            $result = ConfigModel::edit(['value' => json_encode($data['content'])],$menus,'menu_name');
            $data['update_time'] = time();
            $result_all = db('all_agreement')->where('id',$data['id'])->update($data);
        }else if($data['id'] ==6 || $data['id'] == 7 || $data['id'] == 8){
            $datas['description'] = $data['content'];
            $result = SystemGradeDesc::edit($datas,$data['id'],'all_agreement_id');
            $data['update_time'] = time();
            $result_all = db('all_agreement')->where('id',$data['id'])->update($data);
        }
        if ($result && $result_all) {
            $res['info']='编辑成功';
            Json::successful($res);
        } else {
            JsonService::fail('编辑失败');
        }
    }
    /**
     * qxh
     * 后端添加创作中心配置，可设置创作引导跳转的帖子链接
     */
    public function writing_center(){
        $some = ConfigModel::where('menu_name','writing_center')->column('value');
        $some = json_decode($some[0],true);
        $this->assign('some',$some);
        return $this->fetch();
    }
    /**
     * qxh
     * 后端添加创作中心配置,搜索帖子的接口
    */
    public function writing_sou(){
        $content=osx_input('title');
        $id=osx_input('id');
        //查询帖子列表
        if(isset($content)){
            $some_datas = ComThread::writing_post($content);
        }else{
            //查询单个帖子的数据
            $some_data = ComThread::where('id',$id)->find();
            if($some_data){
                $some_datas = mb_substr($some_data, 1, 8); 
                $some_datas = '帖子页面-[' . $id . ']' . $some_data['title'] .'...||/#/post?postPid='. $id  .'&forumFid=' . $some_data['fid'] ;
                ConfigModel::edit(['value' => json_encode($some_datas)],'writing_center','menu_name');
            }else{
                $this->apiError('选定失败');
            }    
        }
        $this->apiSuccess($some_datas,'选定成功');
    }
}
