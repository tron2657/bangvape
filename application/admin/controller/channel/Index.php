<?php
namespace app\admin\controller\channel;

use app\admin\controller\AuthController;
use app\admin\model\channel\Channel;
use app\admin\model\channel\ChannelAdmin;
use app\admin\model\com\ComForum;
use app\admin\model\com\ForumPower;
use app\admin\model\system\SystemConfig;
use app\osapi\model\user\UserModel;
use service\FormBuilder;
use service\JsonService;
use service\UtilService;
use think\Cache;
use think\Model;
use think\Request;
use think\Url;


/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */

class Index extends AuthController
{

    /**
     * 配置页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function config()
    {
        $channel_config=SystemConfig::getMore('channel_first_page_open,channel_first_page_can_jump,channel_edit_page_open');
        $this->assign('channel_config',$channel_config);
        return $this->fetch();
    }

    /**
     * 频道设置
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function saveChannelConfig()
    {
        $request = Request::instance();
        if($request->isPost()){
            $channel_first_page_open = osx_input('post.channel_first_page_open','1','intval');
            $channel_first_page_can_jump = osx_input('post.channel_first_page_can_jump','1','intval');
            $channel_edit_page_open = osx_input('post.channel_edit_page_open','1','intval');
            $channel_first_page_open=$channel_edit_page_open==0?0:$channel_first_page_open;
            SystemConfig::edit(['value' => json_encode($channel_first_page_open)],'channel_first_page_open','menu_name');
            SystemConfig::edit(['value' => json_encode($channel_first_page_can_jump)],'channel_first_page_can_jump','menu_name');
            SystemConfig::edit(['value' => json_encode($channel_edit_page_open)],'channel_edit_page_open','menu_name');
            return JsonService::successful('修改成功');
        }
    }

    /**
     * 默认导航设置
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function navSet()
    {
        $systemChannelList=Channel::where('status',1)->field('id,title,default_sort,type,default_open_status,fixed,is_index')->order('fixed desc,default_sort asc')->select()->toArray();
//        $otherChannelList=Channel::where('type',2)->where('status',1)->field('id,title,default_sort,default_open_status')->order('default_sort asc')->select();
        $open_ids='';
        $fixed_id='';
        if(count($systemChannelList)){
            foreach ($systemChannelList as $val){
                if($val['default_open_status']==1){
                    $open_ids.=','.$val['id'];
                }
                if($val['fixed']==1){
                    $fixed_id.=','.$val['id'];
                }
            }
            $open_ids=substr($open_ids,1);
            $fixed_id=substr($fixed_id,1);
            $other_sort=implode(',',array_column($systemChannelList,'id'));
        }else{
            $other_sort='';
        }

        $this->assign([
            'system_list'=>$systemChannelList,
            'system_sort'=>implode(',',array_column($systemChannelList,'id')),
            'other_list'=>[],
            'other_sort'=>$other_sort,
            'open_ids'=>$open_ids,
            'fixed_id'=>$fixed_id,
        ]);
        return $this->fetch();
    }

    public function get_system_list(){
        $systemChannelList=Channel::where('status','egt',0)->field('id,title,default_sort,type,status,default_open_status,fixed,is_index')->order('fixed desc,default_sort asc')->select()->toArray();
        return JsonService::success('获取成功',$systemChannelList);
    }
    /**
     * 执行导航设置
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function do_nav_set()
    {
        $system_sort=osx_input('system_sort','','text');
        $other_sort=osx_input('other_sort','','text');
        $open_ids=osx_input('open_ids','','text');
        $fixed_id=osx_input('fixed_id','','text');
        $status_id=osx_input('status_id','','text');
        $system_sort=explode(',',$system_sort);
        $index_id=osx_input('index_id',''.'text');
        $sort_num=1;
        foreach ($system_sort as $val){
            Channel::edit(['default_sort'=>$sort_num],$val);
            $sort_num++;
        }
        unset($val);
        Channel::where(['status'=>['egt',0]])->update(['default_open_status'=>0,'fixed'=>0,'status'=>0,'is_index'=>0]);
        if($open_ids!=''){
            $open_ids=explode(',',$open_ids);
            Channel::where('id','in',$open_ids)->setField('default_open_status',1);
        }
        if($fixed_id!=''){
            $fixed_id=explode(',',$fixed_id);
            Channel::where('id','in',$fixed_id)->setField('fixed',1);
        }
        if($status_id!=''){
            $status_id=explode(',',$status_id);
            Channel::where('id','in',$status_id)->setField('status',1);
        }
        if($index_id!=''){
            $index_id=explode(',',$index_id);
            Channel::where('id','in',$index_id)->setField('is_index',1);
        }
        Cache::rm('default_set_channel_list');
        
        return JsonService::success('设置成功！');
    }

    /**
     * 系统频道首页
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 自定义频道首页
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function other()
    {
        $is_add=self::check_add_channel();
        $this->assign('is_add',$is_add);
        return $this->fetch();
    }

    /**
     * 判断是否有权限添加数据
     * @return int
     */
    public function check_add_channel(){
        return 1;
        //判断是否自定已添加上限
        $is_add=1;
        $open_list=$this->_getClientOpenList();
        if(!in_array('channel',$open_list)){
            $map['type']=2;
            $map['status']=1;
            $count=db('channel')->where($map)->count();
            if($count>=3){
                $is_add=0;
            }
        }
        return $is_add;
    }

    /**
     * 频道列表展示获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function channel_list()
    {
        $status=osx_input('status','','text');
        $type=osx_input('type',1,'intval');
        $page=osx_input('page',1,'intval');
        $limit=osx_input('limit',20,'intval');
        $map['type']=$type;
        $channel_title=osx_input('channel_name','','text');
        if($status!=''){
            $map['status']=intval($status);
        }else{
            $map['status']=['egt',0];
        }
        if($channel_title!=''){
            $map['title']=['like','%'.$channel_title.'%'];
        }
        return JsonService::successlayui(Channel::getChannelListPage($map,$page,$limit));
    }

    /**
     * 设置开启关闭某个频道
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function set_status(){
        $status=osx_input('status',1,'intval');
        $id=osx_input('id','','intval');
        ($status=='' || $id=='') && JsonService::fail('缺少参数');
        $res=Channel::where(['id'=>$id])->update(['status'=>(int)($status)]);
        if($res){
            Cache::rm('default_set_channel_list');
            return JsonService::successful($status==1 ? '开启成功':'关闭成功');
        }else{
            return JsonService::fail($status==1 ? '开启失败':'关闭失败');
        }
    }

    /**
     * 系统频道编辑页面
     * @return mixed|void
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function edit()
    {
        $id=osx_input('id',0,'intval');
        if(!$id) return $this->failed('数据不存在');
        $channel = Channel::get($id);
        if(!$channel) return JsonService::fail('数据不存在!');

        $field = [
            FormBuilder::input('title','频道名称',$channel->getData('title'))->placeholder('填写字段')->maxlength(4)->required('必填'),
            FormBuilder::input('intor','频道说明',$channel->getData('intor'))->placeholder('请输入说明信息')->required('必填')->type('textarea')->maxlength(140)->rows(5),
            FormBuilder::frameImageOne('logo','频道封面(300*300px)',Url::build('admin/widget.images/index',array('fodder'=>'logo')),$channel->getData('logo'))->required('必填')->icon('image')->width('100%')->height('300px'),
            //FormBuilder::Switches('moderators','是否仅管理员可用',$class->getData('moderators'))->openStr('是')->closeStr('否')->size('default'),
            /*FormBuilder::radio('status','状态',$class->getData('status'))->options([['label'=>'正式','value'=>1],['label'=>'禁用','value'=>0]]),
            FormBuilder::number('sort','排序',$class->getData('sort'))->col(8),*/
        ];
        $form = FormBuilder::make_post_form('频道编辑',$field,Url::build('update',array('id'=>$id)),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * 系统频道编辑保存
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function update()
    {
        $id=osx_input('id',0,'intval');
        $data['title']=osx_input('title','','text');
        $data['intor']=osx_input('intor','','text');
        $data['logo']=osx_input('logo','','text');

        if(!$id) return JsonService::fail('数据不存在');
        if($data['title']==''||iconv_strlen($data['title'])>4) return JsonService::fail('频道名称不能为空，且不能超过4个字');
        if($data['intor']==''||iconv_strlen($data['intor'])>140) return JsonService::fail('频道说明不能为空，且不能超过140个字');
        if($data['logo']=='') return JsonService::fail('频道封面不能为空');

        Channel::edit($data,$id);
        Cache::rm('default_set_channel_list');
        return JsonService::successful('修改成功!');
    }

    /**
     * 自定义频道编辑页面（推荐也调用）
     * @return mixed|void
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function edit_other()
    {
        $id=osx_input('id',0,'intval');
        if($id!=0){
            //编辑频道
            $this->assign('top_title','编辑频道');
            $channel=Channel::find($id);
            if($channel&&($channel['id']==2||$channel['type']==2)){
                $channel['post_type1']=0;
                $channel['post_type2']=0;
                switch ($channel['post_type']){
                    case 0:
                        break;
                    case 1:
                        $channel['post_type1']=1;
                        break;
                    case 2:
                        $channel['post_type2']=1;
                        break;
                    case 3:
                        $channel['post_type1']=1;
                        $channel['post_type2']=1;
                        break;
                    default:
                }
                $condition_post_content=explode(',',$channel['condition_post_content']);
                $channel['condition_post_content1']=in_array('1',$condition_post_content);
                $channel['condition_post_content2']=in_array('2',$condition_post_content);
                $channel['condition_post_content3']=in_array('3',$condition_post_content);
                $channel['condition_post_content4']=in_array('4',$condition_post_content);
                $admin_user_list=ChannelAdmin::getChannelAdminList($id);
                if(count($admin_user_list)){
                    $channel['admin_user']=implode(',',array_column($admin_user_list,'uid'));
                    $channel['admin_user_list']=array_column($admin_user_list,'nickname');
                }else{
                    $channel['admin_user']='';
                    $channel['admin_user_list']=[];
                }


                if(!in_array($channel['list_page_limit'],[5,10])){
                    $channel['list_page_limit_input']=$channel['list_page_limit'];
                    $channel['list_page_limit']='自定义';
                }else{
                    $channel['list_page_limit_input']='';
                }

                if(!in_array($channel['list_update_interval'],[5,10])){
                    $channel['list_update_interval_input']=$channel['list_update_interval'];
                    $channel['list_update_interval']='自定义';
                }else{
                    $channel['list_update_interval_input']='';
                }

                if($channel['from_type']==1&&$channel['from_ids']!=''){
                    $channel['from_user_ids']=$channel['from_ids'];
                    $channel['from_user_list']=UserModel::where(['uid'=>['in',$channel['from_user_ids']],'status'=>1])->column('nickname');
                }else{
                    $channel['from_user_ids']='';
                    $channel['from_user_list']=[];
                }

                if($channel['from_type']==0&&$channel['from_ids']!=''){
                    $channel['from_forum_ids']=$channel['from_ids'];
                    $channel['from_forum_list']=ComForum::where(['id'=>['in',$channel['from_forum_ids']],'status'=>1])->column('name');
                }else{
                    $channel['from_forum_ids']='';
                    $channel['from_forum_list']=[];
                }
            }else{
                return $this->error('频道不存在或不可编辑');
            }
        }else{
            //新建频道
            $this->assign('top_title','新建频道');
            $channel=[
                'id'=>0,
                'title'=>'',
                'logo'=>'',
                'intor'=>'',
                'admin_user'=>'',
                'admin_user_list'=>[],
                'post_type1'=>1,//默认选中自动推送
                'post_type2'=>1,//默认选中手动推送
                'post_audit'=>1,//审核：默认开启审核
                'post_intor'=>'',
                'from_type'=>2,//来源：默认来自全站
                'from_forum_ids'=>'',
                'from_forum_list'=>[],
                'from_user_ids'=>'',
                'from_user_list'=>[],
                'condition_post_hot_type'=>1,//条件：默认同时满足三项
                'condition_post_hot_comment'=>'',
                'condition_post_hot_read'=>'',
                'condition_post_hot_support'=>'',
                'condition_post_type'=>0,//条件：默认全部帖子类型
                'condition_post_content1'=>1,//条件：默认选择帖子，多选
                'condition_post_content2'=>0,//条件：默认不选择视频，多选
                'condition_post_content3'=>0,//条件：默认不选择资讯，多选
                'condition_post_content4'=>0,//条件：默认不选择动态，多选
                'condition_post_send_time'=>'',
                'condition_post_comment_time'=>'',
                'condition_post_update_time'=>'',
                'list_sort_type'=>'',
                'list_page_limit'=>'',
                'list_page_limit_input'=>'',
                'list_update_interval'=>'',
                'list_update_interval_input'=>'',
                'list_page_limit_input_type'=>'',
                'list_update_interval_type'=>''
            ];
        }
        $system_img=SystemConfig::getValue('website_logo');
        $this->assign('system_img',get_root_path($system_img));
        $this->assign('channel',$channel);
        return $this->fetch();
    }

    /**
     * 自定义频道编辑保存（推荐也调用）
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function saveChannelData()
    {
        $is_add=self::check_add_channel();
        if(!$is_add){
            JsonService::fail('创建数量已达上限，如需添加更多频道请联系客服升级');
        }
        $id=osx_input('post.id',0,'intval');
        if(in_array($id,[1,3,4])){
            JsonService::fail('非法操作');
        }
        if($id==2){
            $data['type']=1;
        }else{
            $data['type']=2;
        }
        $data['title']=osx_input('post.title','','text');
        $data['intor']=osx_input('post.intor','','text');
        $data['logo']=osx_input('post.logo','','text');
        if(iconv_strlen($data['title'])>4||$data['title']==''){
            return JsonService::fail('频道名称长度不合法');
        }
        if(iconv_strlen($data['intor'])>140||$data['intor']==''){
            return JsonService::fail('频道说明长度不合法');
        }
        $admin_user=osx_input('post.admin_user','','text');
        if($admin_user==''){
            return JsonService::fail('请选择频道管理员');
        }
        $post_type=$_POST['post_type'];
        if(in_array('1',$post_type)&&in_array('2',$post_type)){
            $data['post_type']=3;//自动推荐+手动推荐
        }else{
            if(in_array('1',$post_type)){
                $data['post_type']=1;//自动推荐
            }elseif (in_array('2',$post_type)){
                $data['post_type']=2;//手动推荐
            }else{
                $data['post_type']=0;//不推荐
                return JsonService::fail('请至少选择一种推送方式');
            }
        }
        if($data['post_type']==2||$data['post_type']==3){
            //手动推荐设置
            $data['post_audit']=osx_input('post.post_audit',1,'intval');
            $data['post_intor']=osx_input('post.post_intor','','text');
            if($data['post_audit']==1){
                if(iconv_strlen($data['post_intor'])>140||$data['post_intor']==''){
                    return JsonService::fail('频道投稿说明长度不合法');
                }
            }
        }
        if($data['post_type']==1||$data['post_type']==3) {
            //自动推荐设置
            $data['from_type'] = osx_input('post.from_type', 2, 'intval');
            switch ($data['from_type']) {
                case 0:
                    $from_forum_ids = osx_input('post.from_forum_ids', '', 'text');
                    $data['from_ids'] = $from_forum_ids;
                    if($data['from_ids']==''){
                        return JsonService::fail('请选择来自版块');
                    }
                    break;
                case 1:
                    $from_user_ids = osx_input('post.from_user_ids', '', 'text');
                    $data['from_ids'] = $from_user_ids;
                    if($data['from_ids']==''){
                        return JsonService::fail('请选择来自用户');
                    }
                    break;
                default:
                    $data['from_ids'] = '';
            }
            $data['condition_post_hot_type'] = osx_input('post.condition_post_hot_type', 1, 'intval');
            $data['condition_post_hot_comment'] = osx_input('post.condition_post_hot_comment', 0, 'intval');
            $data['condition_post_hot_read'] = osx_input('post.condition_post_hot_read', 0, 'intval');
            $data['condition_post_hot_support'] = osx_input('post.condition_post_hot_support', 0, 'intval');
            if($data['condition_post_hot_comment']>=1000000||$data['condition_post_hot_read']>=1000000||$data['condition_post_hot_support']>=1000000){
                return JsonService::fail('条件设置->帖子热度 条件的数字不能大于6位');
            }
            $data['condition_post_type'] = osx_input('post.condition_post_type', 0, 'intval');
            $condition_post_content=$_POST['condition_post_content'];
            $data['condition_post_content'] = implode(',',$condition_post_content);
            $data['condition_post_send_time'] = osx_input('post.condition_post_send_time', 1, 'intval');
            if(!in_array($data['condition_post_send_time'],[1,2,3,4,5,6])){
                return JsonService::fail('条件设置->发布时间范围设置不正确');
            }
            $data['condition_post_comment_time'] = osx_input('post.condition_post_comment_time', 1, 'intval');
            if(!in_array($data['condition_post_comment_time'],[1,2,3,4,5,6])){
                return JsonService::fail('条件设置->回复时间范围设置不正确');
            }
            $data['condition_post_update_time'] = osx_input('post.condition_post_update_time', 1, 'intval');
            if(!in_array($data['condition_post_update_time'],[1,2,3,4,5,6])){
                return JsonService::fail('条件设置->修改时间范围设置不正确');
            }
            $data['list_sort_type'] = osx_input('post.list_sort_type', 4, 'intval');
            if(!in_array($data['list_sort_type'],[1,2,3,4,5,6])){
                return JsonService::fail('请选择排序方式');
            }
            $data['list_page_limit'] = osx_input('post.list_page_limit', '10', 'text');
            $data['list_update_interval'] = osx_input('post.list_update_interval', '10', 'text');
            $data['list_update_interval_type'] = osx_input('post.list_update_interval_type', 0, 'intval');
            if ($data['list_page_limit'] == '自定义') {
                $list_page_limit_input = osx_input('post.list_page_limit_input', 10, 'intval');
                if ($list_page_limit_input <= 0 || $list_page_limit_input >= 100) {
                    $list_page_limit_input = 10;
                }
                $data['list_page_limit'] = $list_page_limit_input;
            }
            if ($data['list_update_interval'] == '自定义') {
                $list_update_interval_input = osx_input('post.list_update_interval_input', 10, 'intval');
                if ($list_update_interval_input <= 0 || $list_update_interval_input >= 24) {
                    $list_update_interval_input = 10;
                }
                $data['list_update_interval'] = $list_update_interval_input;
            }
            if($data['list_page_limit']<= 0||$data['list_page_limit'] >= 100){
                return JsonService::fail('性能设置->单页数量设置不合规（1~99之间）');
            }
            if($data['list_update_interval']<= 0||$data['list_update_interval'] >= 24){
                return JsonService::fail('性能设置->数据刷新率设置不合规（1~23之间）');
            }
        }

        if($id){
            //编辑
            $res1=Channel::edit($data,$id);
            $res2=ChannelAdmin::resetChannelAdmin($id,$admin_user,$this->adminId,$is_add=0);
            if($res1||$res2){
                Cache::rm('default_set_channel_list');
                //清除信息流缓存
                Cache::clear('channel_list_change');
                if($id==2){
                    return JsonService::success('编辑成功',['url'=>Url('channel.index/index')]);
                }else{
                    return JsonService::success('编辑成功',['url'=>Url('channel.index/other')]);
                }
            }else{
                return JsonService::fail('编辑失败');
            }
        }else{
            //新增
            $data['status']=1;
            $res1=Channel::set($data,true);
            if($res1->result){
                Cache::rm('default_set_channel_list');
                ChannelAdmin::resetChannelAdmin($res1->id,$admin_user,$this->adminId,$is_add=1);
                //清除信息流缓存
                Cache::clear('channel_list_change');
                return JsonService::success('新增成功',['url'=>Url('channel.index/other'),'id'=>$res1->id]);
            }else{
                return JsonService::fail('新增失败');
            }
        }
    }

    /**
     * 绑定频道管理员页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function bind_user_vim()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $this->assign('channel_id',$channel_id);
        return $this->fetch();
    }

    /**
     * 获取当前频道管理员-绑定频道管理员调用
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function get_already_user()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        if (!$channel_id) {
            return JsonService::successful([]);
        } else {
            $admin_users = ChannelAdmin::getChannelAdminList($channel_id);
            if(count($admin_users)){
                return JsonService::successful(['user'=>$admin_users,'count'=>count($admin_users)]);
            }else{
                return JsonService::successful([]);
            }
        }
    }

    /**
     * 搜索用户-绑定频道管理员及自动推荐来自用户时调用
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function find_users()
    {
        $nickname=osx_input('nickname','','text');
        $users = UserModel::where('nickname|uid|phone', 'like', "%$nickname%")->where('status',1)->limit(10)->select()->toArray();
        $data = array();
        if ($users) {
            foreach ($users as $v) {
                if ($v) {
                    $data[] = array('value' => $v['uid'], 'name' => $v['nickname']);
                }
            }
        }
        return JsonService::successlayui(count($users), $data, '成功');
    }

    /**
     * 绑定帖子来源用户页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function bind_from_user_vim()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $this->assign('channel_id',$channel_id);
        return $this->fetch();
    }

    /**
     * 获取当前频道已绑定帖子用户-绑定帖子来源用户调用
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function get_already_from_user()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        if (!$channel_id) {
            return JsonService::successful([]);
        } else {
            $channel=Channel::field('from_type,from_ids')->find($channel_id);
            if($channel['from_type']==1&&$channel['from_ids']!=''){
                $uids=explode(',',$channel['from_ids']);
                $user = db('user')->where(['uid'=>['in', $uids],'status'=>1])->field('uid,nickname')->select();
                return JsonService::successful(['user'=>$user,'count'=>count($user)]);
            }else{
                return JsonService::successful([]);
            }
        }
    }

    /**
     * 搜索版块-自动推荐帖子来源版块调用
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function find_forums()
    {
        $keywords=osx_input('keywords','','text');
        $forums = ComForum::where('name|id', 'like', "%$keywords%")->where('status',1)->limit(10)->select()->toArray();
        $data = array();
        if ($forums) {
            $private_forum=ForumPower::get_private_id();
            foreach ($forums as $v) {
                if ($v&&!in_array($v['id'],$private_forum)) {
                    $data[] = array('value' => $v['id'], 'name' => $v['name']);
                }
            }
        }
        return JsonService::successlayui(count($forums), $data, '成功');
    }

    /**
     * 绑定帖子来源版块页面
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function bind_from_forum_vim()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        $this->assign('channel_id',$channel_id);
        return $this->fetch();
    }

    /**
     * 获取当前频道已绑定帖子版块-绑定帖子来源版块调用
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public function get_already_from_forum()
    {
        $channel_id=osx_input('channel_id',0,'intval');
        if (!$channel_id) {
            return JsonService::successful([]);
        } else {
            $channel=Channel::field('from_type,from_ids')->find($channel_id);
            if($channel['from_type']==0&&$channel['from_ids']!='') {
                $forum_ids = explode(',', $channel['from_ids']);
                $forum = ComForum::where(['id' => ['in', $forum_ids], 'status' => 1])->field('id,name')->select();
                $private_forum = ForumPower::get_private_id();
                foreach ($forum as $key => $val) {
                    if (in_array($val['id'], $private_forum)) {
                        unset($forum[$key]);
                    }
                }
                unset($val);
            }
            if(isset($forum)&&count($forum)){
                return JsonService::successful(['forum'=>$forum,'count'=>count($forum)]);
            }else{
                return JsonService::successful([]);
            }
        }
    }

    /**
     * 发布帖子页面同步到频道选择
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function recommend_to_channel()
    {
        $channel_list=Channel::getAllChannelList();
        $this->assign('channel_list',$channel_list);
        return $this->fetch();
    }

    /**
     * 同步单个问题
     */
    public function follow_channel_one(){
        $id=osx_input('id',0,'intval');
        if(!$id){
            return JsonService::fail('请选择同步的id');
        }
        $res=Channel::follow_channel_one($id);
        if($res){
            Cache::rm('default_set_channel_list');
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('同步成功');
        }else{
            return JsonService::successful('同步失败');
        }
    }

    /**
     * 同步全部问题
     */
    public function follow_channel_all(){
        $res=db('channel_user')->where(['status'=>1])->delete();
        if($res!==false){
            Cache::rm('default_set_channel_list');
            //清除信息流缓存
            Cache::clear('channel_list_change');
            return JsonService::successful('全部同步成功');
        }else{
            return JsonService::successful('全部同步失败');
        }
    }

    /**
     * 设置首页
     */
    public function set_index(){
        $id=osx_input('id',0,'intval');
        //将之前的设置变为非首页
        db('channel')->where(['is_index'=>1])->update(['is_index'=>0]);
        $res=db('channel')->where(['id'=>$id])->update(['is_index'=>1]);
        if($res!==false){
            Cache::rm('default_set_channel_list');
            return JsonService::successful('设置成功');
        }else{
            return JsonService::successful('设置失败');
        }
    }
}