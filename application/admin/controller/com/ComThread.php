<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */
namespace app\admin\controller\com;

use Api\Express;
use app\admin\controller\AuthController;
use app\admin\model\com\ComForumAdminScoreLog;
use app\admin\model\channel\Channel;
use app\osapi\model\channel\Channel as ChannelOsApi;
use app\admin\model\column\ColumnText;
use app\admin\model\com\ComThreadDraft;
use app\admin\model\com\ComThreadUser;
use app\admin\model\store\StoreProduct;
use app\commonapi\model\Gong;
use app\commonapi\model\TencentFile;
use service\FormBuilder as Form;
use service\JsonService;
use think\Cache;
use app\osapi\model\user\UserFollow;
use service\UtilService as Util;
use service\JsonService as Json;
use think\Request;
use think\Url;
use app\admin\model\order\StoreOrder as StoreOrderModel;
use app\admin\model\com\ComThread as ThreadModel;
use app\admin\model\com\ComThreadClass as ThreadClassModel;
use app\admin\model\com\ComForum as ForumModel;
use app\osapi\model\user\UserModel;
use app\osapi\model\common\Support;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;
use app\admin\model\system\SystemConfig;
use app\admin\model\system\SystemAdmin;
use app\commonapi\controller\Index as CommonIndex;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;
use app\admin\model\com\ComTopic;
/**
 * 订单管理控制器 同一个订单表放在一个控制器
 * Class StoreOrder
 * @package app\admin\controller\store
 */
class ComThread extends AuthController
{
    /**
     * @return mixed
     */
    public function index()
    {
        $id=osx_input('id','','intval');
        $status=osx_input('status','','text');
        $type=osx_input('type',1,'intval');
        $is_weibo=osx_input('is_weibo',0,'intval');
        $oid=osx_input('oid','','intval');
        $hot_count=SystemConfig::getValue('forum_hot_count');
        $hot_count=explode(',',$hot_count);
        $this->assign([
            'year' => getMonth('y'),
            'real_name' => $this->request->get('real_name', ''),
            'orderCount' => StoreOrderModel::orderCount(),
            'status' => $status,
            'type' => $type,
            'id' => $id,
            'is_weibo' => $is_weibo,
            'oid' => $oid,
            'forum_list' => ForumModel::getSelectList(),
            'hot_count'=>$hot_count
        ]);
        $this->assign('cate', ForumModel::getCatTierList());
        return $this->fetch();
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $type=osx_input('type',0,'intval');

        $field = [
            Form::input('title', '标题'),
            Form::hidden('author_uid', $this->adminId),
            Form::hidden('type', $type),
            Form::select('fid', '版块分类')->setOptions(function () {
                $list = ForumModel::getCatTierList('', 1);
                $menus = [['value' => 0, 'label' => '顶级分区']];;
                foreach ($list as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['html'] . $menu['name']];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1),
            Form::select('class_id', '分类')->setOptions(function () {
                $list = ThreadClassModel::getCatTierList();
                $menus = [['value' => 0, 'label' => '顶级分类']];;
                foreach ($list as $menu) {
                    $menus[] = ['value' => $menu['value'], 'label' => $menu['label']];//,'disabled'=>$menu['pid']== 0];
                }
                return $menus;
            })->filterable(1),
            Form::frameImageOne('cover', '图文封面大图片', Url::build('admin/widget.images/index', array('fodder' => 'cover')))->icon('image')->width('100%')->height('500px'),
            Form::textarea('summary', '文章简介'),
            Form::textarea('content', '文章内容'),
        ];
        $form = Form::make_post_form('添加文章', $field, Url::build('save'), 2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function add_goods()
    {
        return $this->fetch();
    }

    public function create_thread()
    {
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(1, 8))->order('pid')->select();
        $select=self:: pack_select($select);

        $this->assign('select', $select);
        $this->assign('style', 'create');
        $this->assign('id',0);
        $this->assign('status','');
        return $this->fetch();
    }

    public function create_weibo()
    {
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(2, 8))->select();
        $select=self:: pack_select($select);

        
        $this->assign('select', $select);
        $this->assign('style', 'create');
        return $this->fetch();
    }

    public function create_news()
    {
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(4, 8))->select();
        $select=self:: pack_select($select);
        $this->assign('select', $select);
        $this->assign('id',0);
        $this->assign('style', 'create_news');
        $this->assign('status','');
        return $this->fetch();
    }

    public function create_video()
    {
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(6, 8))->select();
        $select=self:: pack_select($select);
        $video_cover_type=SystemConfig::getValue('video_cover_type');
        $this->assign('select', $select);
        $this->assign('style', 'create_video');
        $this->assign('video_cover_type', $video_cover_type);
        return $this->fetch();
    }

    public function select_class(Request $request)
    {
        $post = $request->post();
        $data = Util::postMore([
            'id',
        ], $request);
        $select = db('com_thread_class')->where('status', 1)->where('fid', $data['id'])->select();
        Json::successful($select);
    }

    /**
     * 新增帖子
     */
    public function add_thread(Request $request)
    {
        $data = Util::postMore([
            'title',
            'fid',
            'class_id',
            'author_uid',
            ['image', ''],
            ['send_time', ''],
            ['is_auto_image', 1],
            ['false_view', 0],
            ['is_weibo', 0],
            ['status',1],
            ['type_name',''],
            ['product_id',''],
            ['recommend_to_channel_ids',''],
        ], $request);
        $data['content']=osx_input('post.content','','html');

        $data['create_time'] = time();
        $data['update_time'] = time();
//        $data['status'] = 1;
        if($data['send_time']==''||$data['send_time']==0){
            $data['send_time']=$data['create_time'];
        }else{
            if($data['send_time']>$data['create_time']){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
        }
        $data['type'] = 1;
        $data['from'] = 'HouTai';
        if($data['type_name']=='draft'){
            $result = ComThreadDraft::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        }else{
            $result = ThreadModel::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        }
        if ($result&&$data['type_name']!='draft') {
            db('user')->where('uid', $data['author_uid'])->setInc('post_count');
            db('com_forum')->where('id', $data['fid'])->setInc('post_count');
            Cache::clear('thread_list_cache');
            $set1=MessageTemplate::getMessageSet(7);
            $nickname=UserModel::where('uid',$data['author_uid'])->value('nickname');
            $set1['template']=str_replace('{用户昵称}', $nickname, $set1['template']);
            $fans_list=UserFollow::where('follow_uid',$data['author_uid'])->where('status',1)->column('uid');
            $link_id=ThreadModel::where('id',$result)->value('post_id');
            if($set1['status']==1){
                $message1=array();
                $data2['from_uid']=$data['author_uid'];
                $data2['content']=$set1['template'];
                $data2['type_id']=5;
                $data2['title']=$set1['title'];
                $data2['from_type']=5;
                $data2['route']='thread';
                $data2['link_id']=$link_id;
                $data2['create_time']=time();
                $data2['send_time']=time();
                $map1=$data2;
                foreach($fans_list as &$value){
                    $data2['to_uid']=$value;
                    $message1[]=$data2;
                }
                unset($value);
                Message::insertAll($message1);

                //最新消息内容更新
                unset($data2['to_uid']);
                Message::update_message_census($fans_list,'message_new_send',$data2);
                //最新消息内容更新end

                $message_list1=Message::where($map1)->select()->toArray();
                $data3['is_read']=0;
                if($set1['popup']==1){
                    $data3['is_popup']=0;
                }else{
                    $data3['is_popup']=1;
                    $data3['popup_time']=time();
                }
                $data3['is_sms']=1;
                $data3['sms_time']=time();
                $data3['type']=5;
                $data3['create_time']=time();
                $message_read1=array();
                foreach($message_list1 as &$item){
                    $data3['uid']=$item['to_uid'];
                    $data3['message_id']=$item['id'];
                    $message_read1[]=$data3;
                }
                MessageRead::insertAll($message_read1);
            }
            $res['thread_id'] = $result;
            $res['info'] = '发布成功';
            if($data['recommend_to_channel_ids']!=''){
                //执行同步到频道
                Channel::recommendToChannel($res['thread_id'],$data['recommend_to_channel_ids'],$this->adminId);
            }
            Json::successful($res);
        } else {
            if($result){
                $res['thread_id'] = $result;
                $res['info'] = '发布成功';
                Cache::rm('channel_list_minutes');//清除频道计划任务列表
                Json::successful($res);
            }else{
                JsonService::fail('创建帖子失败');
            }

        }
    }

    /**
     * 新增动态
     */
    public function add_weibo(Request $request)
    {
        $data = Util::postMore([
            'fid',
            'class_id',
            'author_uid',
            ['send_time', ''],
            ['image', ''],
            ['is_auto_image', 0],
            ['false_view', 0],
            ['recommend_to_channel_ids',''],
            ['topic', ''],
        ], $request);

        $data['content']=osx_input('post.content','','html');
        $data['type'] = 1;
        $data['title'] = '';
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['status'] = 1;
        $data['from'] = 'h5';
        $data['is_weibo'] = 1;
        if($data['send_time']==''||$data['send_time']==0){
            $data['send_time']=$data['create_time'];
        }else{
            if($data['send_time']>$data['create_time']){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
        }
        if ($data['topic'] != '') {
            $topics=explode(',', $data['topic']);
            $oid=array();
            foreach ($topics as $v) {
                $topic = ComTopic::where('title', $v)->find();
                if ($topic) {
                    if ((int)$topic['status'] < 0) {
                        ComTopic::where('id', $topic['id'])->update(['status' => 2, 'update_time' => time()]);
                    }
                    $oid[] = $topic['id'];
                } else {
                    $topic_data['uid']=$data['author_uid'];
                    $topic_data['title']=$v;
                    $topic_data['status']=2;
                    $topic_data['create_time']=time();
                    $oid[]=ComTopic::add($topic_data);
                }
            }
            unset($v);
            $data['oid']=implode(',',$oid);
        }
        $result = ThreadModel::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        if ($result) {
            db('user')->where('uid', $data['author_uid'])->setInc('post_count');
            db('com_forum')->where('id', $data['fid'])->setInc('post_count');
            $res['thread_id'] = $result;
            $res['info'] = '发布成功';
            census('forum', 1);
            if($data['recommend_to_channel_ids']!=''){
                //执行同步到频道
                Channel::recommendToChannel($res['thread_id'],$data['recommend_to_channel_ids'],$this->adminId);
            }
            Cache::rm('channel_list_minutes');//清除频道计划任务列表
            Json::successful($res);
        } else {
            JsonService::fail('创建帖子失败');
        }
    }

    /**
     * 编辑帖子
     */
    public function edit_thread(Request $request)
    {
        $data = Util::postMore([
            'id',
            'title',
            'author_uid',
            'from',
            'send_time',
            ['cover', 0],
            ['summary', ''],
            ['type', ''],
            ['image', ''],
            ['is_auto_image', 1],
            ['false_view', 0],
            'fid',
            'class_id',
            ['is_weibo', 0],
            ['type_name',''],
            ['status',1],
            ['video_cover', ''],
            ['product_id',''],
        ], $request);
        $data['content']=osx_input('post.content','','html');
        $data['update_time'] = time();
        if($data['type_name']=='draft'&&$data['status']!=3){
            $id=$data['id'];
            unset($data['id']);
            if($data['type']==4){
                $data['status'] = 1;
                $data['from'] = 'HouTai';
                $data['type'] = 4;
            }
            $data['create_time'] = time();
            $data['update_time'] = time();
            if($data['send_time']>$data['create_time']){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
            $result = ThreadModel::createThread($data);
            $data['status']=-1;
            $data['id']=$id;
            ComThreadDraft::editThread($data);
        }elseif($data['status']==3){
            $result = ComThreadDraft::editThread($data);
        }else{
            $create_time=ThreadModel::where('id',$data['id'])->value('create_time');
            if($data['send_time']>$create_time){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
            if($data['type']==6){
                $data['from'] = 'HouTai';
            }
            $result = ThreadModel::editThread($data);
        }

        if ($result !== false) {
            if ($data['id']) {
                Cache::rm('thread_detail_'.$data['id']);//编辑帖子时清除帖子缓存
                Cache::clear('thread_list_cache');
                Cache::clear('channel_list_change');
            }
            $res['info'] = '编辑成功';
            $res['data'] =$data['id'];
            Cache::rm('channel_list_minutes');//清除频道计划任务列表
            Json::successful($res);
        } else {
            JsonService::fail('编辑失败');
        }
    }


    /**
     * 新增资讯
     */
    public function add_news(Request $request)
    {
        $data = Util::postMore([
            'title',
            'cover',
            'author_uid',
            ['summary', ''],
            ['send_time', ''],
            ['false_view', 0],
            ['image', ''],
            ['is_auto_image', 1],
            'fid',
            'class_id',
            ['is_weibo', 0],
            ['type_name',''],
            ['recommend_to_channel_ids',''],
        ], $request);

        $data['content']=osx_input('post.content','','html');

        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['status'] = 1;
        $data['from'] = 'HouTai';
        $data['type'] = 4;
        if($data['send_time']==''||$data['send_time']==0){
            $data['send_time']=$data['create_time'];
        }else{
            if($data['send_time']>$data['create_time']){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
        }
        if($data['type_name']=='draft'){
            $data['status']=3;
            $result = ComThreadDraft::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        }else{
            $result = ThreadModel::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        }
//        $result = ThreadModel::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        if ($result&&$data['type_name']!='draft') {
            db('user')->where('uid', $data['author_uid'])->setInc('post_count');
            db('com_forum')->where('id', $data['fid'])->setInc('post_count');
            $res['thread_id'] = $result;
            $res['info'] = '发布成功';
            if($data['recommend_to_channel_ids']!=''){
                //执行同步到频道
                Channel::recommendToChannel($res['thread_id'],$data['recommend_to_channel_ids'],$this->adminId);
            }
            Json::successful($res);
        } else {
            if($result){
                $res['thread_id'] = $result;
                $res['info'] = '发布成功';
                Cache::rm('channel_list_minutes');//清除频道计划任务列表
                Json::successful($res);
            }else{
                JsonService::fail('创建帖子失败');
            }
        }
    }

    public function get_topic($topic,$author_uid)
    {
        if ($topic != '') {
            $topics=explode(',', $topic);
            $oid=array();
            foreach ($topics as $v) {
                $topic = ComTopic::where('title', $v)->find();
                if ($topic) {
                    if ((int)$topic['status'] < 0) {
                        ComTopic::where('id', $topic['id'])->update(['status' => 2, 'update_time' => time()]);
                    }
                    $oid[] = $topic['id'];
                } else {
                    $topic_data['uid']=$author_uid;
                    $topic_data['title']=$v;
                    $topic_data['status']=2;
                    $topic_data['create_time']=time();
                    $oid[]=ComTopic::add($topic_data);
                }
            }
            unset($v);
            return implode(',',$oid);
        }
        return null;
    }
    /**
     * 新增视频
     */
    public function add_video(Request $request)
    {
        $data = Util::postMore([
            'title',
            ['summary', ''],
            'video_id',
            'video_url',
            'author_uid',
            ['video_cover', ''],
            ['send_time', ''],
            ['false_view', 0],
            ['image', ''],
            ['is_auto_image', 1],
            'fid',
            'class_id',
            ['is_weibo', 0],
            ['recommend_to_channel_ids',''],
            ['topic','']
        ], $request);

        $data['content']=osx_input('post.content','','html');

        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['status'] = 1;
        $data['from'] = 'HouTai';
        $data['type'] = 6;
        $cover_type = SystemConfig::getValue('video_cover_type');
        if (is_array($cover_type) && in_array('1', $cover_type) && $data['video_cover'] == '' || $cover_type == '1') {
            $animatedGraphic = ThreadModel::getAnimatedGraphic($data['video_id']);
            if ($animatedGraphic != '') {
                $data['video_cover'] = $animatedGraphic;
            }
        }
        if($data['send_time']==''||$data['send_time']==0){
            $data['send_time']=$data['create_time'];
        }else{
            if($data['send_time']>$data['create_time']){
                JsonService::fail('虚拟发帖时间不能大于真实发帖时间');
            }
        }
        if ($data['topic'] != '') {
            $topics=explode(',', $data['topic']);
            $oid=array();
            foreach ($topics as $v) {
                $topic = ComTopic::where('title', $v)->find();
                if ($topic) {
                    if ((int)$topic['status'] < 0) {
                        ComTopic::where('id', $topic['id'])->update(['status' => 2, 'update_time' => time()]);
                    }
                    $oid[] = $topic['id'];
                } else {
                    $topic_data['uid']=$data['author_uid'];
                    $topic_data['title']=$v;
                    $topic_data['status']=2;
                    $topic_data['create_time']=time();
                    $oid[]=ComTopic::add($topic_data);
                }
            }
            unset($v);
            $data['oid']=implode(',',$oid);
        }
        $data['image']=$data['video_cover'];

        $result = ThreadModel::createThread($data); //新增帖子内容到数据库，事务写法，过程中涉及很多数据库操作
        if ($result) {
            TencentFile::uploadTencentVOD(['file_id'=>$data['video_id'],'media_url'=>$data['video_url'],'cover_url'=>$data['video_cover'],'type'=>'video']);
            db('user')->where('uid', $data['author_uid'])->setInc('post_count');
            db('com_forum')->where('id', $data['fid'])->setInc('post_count');
            $res['thread_id'] = $result;
            $res['info'] = '发布成功';
            if($data['recommend_to_channel_ids']!=''){
                //执行同步到频道
                Channel::recommendToChannel($res['thread_id'],$data['recommend_to_channel_ids'],$this->adminId);
            }
            Cache::rm('channel_list_minutes');//清除频道计划任务列表
            Json::successful($res);
        } else {
            JsonService::fail('创建帖子失败');
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = Util::postMore([
            'title',
            'author_uid',
            'type',
            'fid',
            'class_id',
            'cover',
            'summary',
        ], $request);

        $data['content']=osx_input('post.content','','html');

        if (!$data['title']) return Json::fail('请输入标题');
        $data['image'] = '';
        ThreadModel::set($data);
        return Json::successful('添加成功!');
    }

    /**
     * 获取头部订单金额等信息
     * return json
     *
     */
    public function getBadge()
    {
        $where = Util::postMore([
            ['status', ''],
            ['real_name', ''],
            ['is_del', 0],
            ['data', ''],
            ['type', ''],
            ['order', '']
        ]);
        return JsonService::successful(StoreOrderModel::getBadge($where));
    }

    /**
     * 获取帖子主题列表
     * return json
     */
    public function thread_list()
    {
        $status=osx_input('status','','text');
        $type=osx_input('type',1,'intval');
        $is_weibo=osx_input('is_weibo',0,'intval');
        $real_name=osx_input('real_name','','text');
        $id=osx_input('id','','intval');

        $where = Util::getMore([
            ['status', $status],
            ['tid', 0],
            ['fid', ''],
            ['id', $id],
            ['cid', ''],
            ['is_top', ''],
            ['is_essence', ''],
            ['is_recommend', ''],
            ['type', $type],
            ['title', $real_name],
            ['uid', ''],
            ['data', ''],
            ['order', ''],
            ['oid', ''],
            ['page', 1],
            ['limit', 20],
            ['excel', 0],
            ['comment_num', ''],
            ['is_weibo', $is_weibo],
            ['hot_count','']
        ]);
        trace($where);
        if($where['status']==3){
            $list=ComThreadDraft::ThreadList($where);
        }else{
            $list=ThreadModel::ThreadList($where);
        }
        return JsonService::successlayui($list);
    }

    /**
     * @return mixed|\think\response\Json|void
     */
    public function edit()
    {
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        if($status==3){
            $info = ComThreadDraft::get($id);
        }else{
            $info = ThreadModel::get($id);
        }

        $info['user'] = UserModel::where('uid', $info['author_uid'])->value('nickname');
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(1, 8))->select();
        $select=self:: pack_select($select);
        $class = db('com_thread_class')->where('fid', $info['fid'])->where('status', 1)->select();
        if ($info['from'] != 'HouTai') {
            $info['content'] = json_decode($info['content']);
        }
        if ($info['image']) {
            $image = json_decode($info['image'], true);
            if (is_array($image)) {
                $info['image'] = $image;
            } else {
                $image = array();
                $image[] = $info['image'];
                $info['image'] = $image;
            }
            $info['is_auto_image'] = 0;
        } else {
            $info['is_auto_image'] = 1;
        }
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'edit');
        $this->assign('id', $id);
        $this->assign('status',$info['status']);
        $this->assign('page',1);
        return $this->fetch('create_thread');
    }

    public function pack_select($select)
    {
        $parentData=db('com_forum')->where('pid', 0)->select();
        $pData=[];
        foreach($parentData as &$item)
        {
            $pData['L'.$item['id']]=$item['name'];            
        }

        foreach($select as &$item)
        {
            $parentName=$pData['L'.$item['pid']];
            $item['name']=$parentName.'>'.$item['name'];
        }
        return $select;
    }

    public function edit_news()
    {
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        if($status==3){
            $info = ComThreadDraft::get($id);
        }else{
            $info = ThreadModel::get($id);
        }
        $info['user'] = UserModel::where('uid', $info['author_uid'])->value('nickname');
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(4, 8))->select();
        $select=self:: pack_select($select);
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'edit_news');
        $this->assign('status',$info['status']);
        $this->assign('id',$id);
        return $this->fetch('create_news');

    }

    public function edit_weibo()
    {
        $id=osx_input('id',0,'intval');
        $info = ThreadModel::get($id);
        $info['user'] = UserModel::where('uid', $info['author_uid'])->value('nickname');
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(2, 8))->select();
        $select=self:: pack_select($select);
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        $info['content']=json_decode($info['content']);
        if ($info['image']) {
            $image = json_decode($info['image'], true);
            if (is_array($image)) {
                $info['image'] = $image;
            } else {
                $image = array();
                $image[] = $info['image'];
                $info['image'] = $image;
            }

        }
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'edit_weibo');
        return $this->fetch('create_weibo');

    }

    public function view_news()
    {
        $id=osx_input('id',0,'intval');
        $info = ThreadModel::get($id);
        $info['user'] = UserModel::where('uid', $info['author_uid'])->field('nickname,avatar')->find()->toArray();
        $info['create_time']=time_format($info['create_time']);
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(4, 8))->select();
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        $info=$info->toArray();
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'view_news');
        return $this->fetch('view_news');
    }

    public function edit_video()
    {
        $id=osx_input('id',0,'intval');
        $info = ThreadModel::get($id);
        $info['user'] = UserModel::where('uid', $info['author_uid'])->value('nickname');
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(6, 8))->select();
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        if($info['video_url']){
            $getYunConfig = TencentFile::ifYunUpload();
            $info['video_url']=TencentFile::yunKeyMediaUrl($info['video_url'],$getYunConfig['pkey']);
        }
        if($info['audio_url']){
            $getYunConfig = TencentFile::ifYunUpload();
            $info['audio_url']=TencentFile::yunKeyMediaUrl($info['audio_url'],$getYunConfig['pkey']);
        }
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'edit_video');
        return $this->fetch('create_video');

    }

    public function view_video()
    {
        $id=osx_input('id',0,'intval');
        $type=osx_input('type','','text');
        $info = ThreadModel::get($id);
        $user = UserModel::where('uid', $info['author_uid'])->field('nickname,avatar')->find();
        if (is_null($user)) {
            $info['user'] = ['nickname' => '', 'avatar' => ''];
        } else {
            $info['user'] = $user->toArray();
        }
        $info['create_time']=time_format($info['create_time']);
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(6, 8))->select();
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        $info=$info->toArray();
        if($info['video_url']){
            $getYunConfig = TencentFile::ifYunUpload();
            $info['video_url']=TencentFile::yunKeyMediaUrl($info['video_url'],$getYunConfig['pkey']);
        }
        if($info['audio_url']){
            $getYunConfig = TencentFile::ifYunUpload();
            $info['audio_url']=TencentFile::yunKeyMediaUrl($info['audio_url'],$getYunConfig['pkey']);
        }
        if ($info['video_id']) {
            $info['psign'] = ThreadModel::_videoKey($info['video_id']);
            $info['app_id'] = db('system_config')->where('menu_name', 'tencent_video_app_id')->value('value');
        } else {
            $info['psign'] = '';
            $info['app_id'] = '""';
        }
        $this->assign('type', $type);
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'view_video');
        return $this->fetch('view_video');
    }

    public function view_weibo()
    {
        $id=osx_input('id',0,'intval');
        $info = ThreadModel::get($id);
        $info['user'] = UserModel::where('uid', $info['author_uid'])->field('nickname,avatar')->find()->toArray();
        $info['create_time']=time_format($info['create_time']);;
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(2, 8))->select();
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        $info['content']=json_decode($info['content']);
        if ($info['image']) {
            $image = json_decode($info['image'], true);
            if (is_array($image)) {
                $info['image'] = $image;
            } else {
                $image = array();
                $image[] = $info['image'];
                $info['image'] = $image;
            }

        }
        $info=$info->toArray();
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'view_weibo');
        return $this->fetch('view_weibo');
    }

    public function view_thread()
    {
        $id=osx_input('id',0,'intval');
        $status=osx_input('status',0,'intval');
        if($status==3){
            $info = ComThreadDraft::get($id);
        }else{
            $info = ThreadModel::get($id);
        }

        $info['user'] = UserModel::where('uid', $info['author_uid'])->field('nickname,avatar')->find()->toArray();
        $info['create_time']=time_format($info['create_time']);
        $select = db('com_forum')->where('status', 1)->where('display', 1)->where('pid', '>', 0)->where('type', 'in', array(1, 8))->select();
        $class = db('com_thread_class')->where('status', 1)->where('fid', $info['fid'])->select();
        if ($info['from'] != 'HouTai') {
            $info['content'] = json_decode($info['content']);
        }
        $image = json_decode($info['image'], true);
        if (is_array($image)) {
            $info['image'] = $image;
        } else {
            $image = array();
            $image[] = $info['image'];
            $info['image'] = $image;
        }
        $info=$info->toArray();
        $this->assign('info', $info);
        $this->assign('select', $select);
        $this->assign('class', $class);
        $this->assign('style', 'view');
        return $this->fetch('view_thread');
    }

    /** 修改订单提交更新
     * @param Request $request
     */
    public function update(Request $request)
    {
        $id=osx_input('post.id',0,'intval');
        $data = Util::postMore([
            'title',
            'author_uid',
            // 'type',
            'fid',
            'class_id',
            'cover',
            'summary',
        ], $request);

        $data['content']=osx_input('post.content','','html');

        if (!$data['title']) return Json::fail('请输入标题');
        $data['image'] = '';
        ThreadModel::edit($data, $id);
        return Json::successful('修改成功!');
    }

    public function bind_user_vim()
    {
        return $this->fetch();
    }

    /**
     * 判断用户
     */
    public function find_user(Request $request)
    {
        $data = Util::postMore([
            'phone',
        ], $request);
        $phone = db('user')->where('phone', $data['phone'])->where('status', 1)->value('phone');
        if (!$phone) {
            JsonService::fail('该用户不存在');
        } else {
            return Json::successful($phone);
        }
    }

    /**
     * 获取当前绑定用户
     */
    public function get_user(Request $request)
    {
        $data = Util::getMore([
            'uid', 0
        ], $request);
        if ($data['uid']) {
            $phone_uid = $data['uid'];
        } else {
            $phone_uid = ComThreadUser::get_thread_user($this->adminId);
        }
        $user = db('user')->where('uid', $phone_uid)->field('uid,nickname')->find();
        return Json::successful($user);
    }

    public function find_users()
    {
        $nickname=osx_input('nickname','','text');
        $users = UserModel::where('nickname|uid|phone', 'like', "%$nickname%")->limit(10)->select()->toArray();
        $data = array();
        if ($users) {
            foreach ($users as $v) {
                if ($v) {
                    $data[] = array('value' => $v['uid'], 'name' => $v['nickname']);
                }
            }
        }
        return Json::successlayui(count($users), $data, '成功');
    }

    /* 商品快速查找
     * */
    public function find_product($nickname){
        $nickname=osx_input('nickname','','text');
        $product=StoreProduct::where('id|store_name','like',"%$nickname%")
														 ->where('is_del',0)
														 ->where('is_type',0)
														 ->where('is_column',0)
														 ->select()
														 ->toArray();
        $data=array();

        if($product){
            foreach ($product as $v){
                if($v){
                    $data[]=array(
                    	'value'=>urlencode(json_encode(array('id'=>$v['id'],'price'=>$v['price'],'img'=>$v['image'],'name'=>$v['store_name']))),
											'name'=>$v['store_name']
										);
                }
            }
        }

        return Json::successlayui(count($product),$data,'成功');

    }

	/* 专栏商品快速查找
	 * */
	public function find_column_products($nickname){
        $nickname=osx_input('nickname','','text');
        if ($nickname === '') return Json::successlayui(0,[],'成功');
		/*$product=StoreProduct::where('id|store_name','like',"%$nickname%")
												 ->where('is_del',0)
												 ->where('is_type',1)
												 ->where('is_column',1)
												 ->select()
												 ->toArray();*/
		$product = ColumnText::where('id|name','like',"%$nickname%")->where('is_show',1)->where('status',1)->select()->toArray();
		$data=array();

		if($product){
			foreach ($product as $v){
				if($v){
					$data[]=array(
						'value'=>urlencode(json_encode(array('id'=>$v['id'],'price'=>$v['price'],'img'=>$v['image'],'name'=>$v['name']))
						),
						'name'=>$v['name']
					);
				}
			}
		}

		return Json::successlayui(count($product),$data,'成功');

	}

    /**
     * 绑定用户
     */
    public function bind_user(Request $request)
    {
        $data = Util::postMore([
            'uid',
        ], $request);
        $uid = db('user')->where('uid', $data['uid'])->where('status', 1)->value('uid');
        if (!$uid) {
            JsonService::fail('该用户不存在');
        }
        $res = ComThreadUser::set_thread_user($this->adminId,$data['uid']);
        $is_vest = db('user')->where('uid', $data['uid'])->where('status', 1)->value('is_vest');
        db('bind_user_log')->insert(['uid' => $uid, 'status' => 1, 'create_time' => time(), 'is_vest' => $is_vest]);
        if ($res !== false) {
            return Json::successful('绑定成功!');
        } else {
            JsonService::fail('绑定失败!');
        }
    }

    /**
     * 获取绑定的列表
     */
    public function get_bind_log(Request $request)
    {
        $data = Util::postMore([
            'is_vest',
        ], $request);
        $user = db('bind_user_log')->where(['is_vest' => $data['is_vest'], 'create_time' => ['gt', time() - 30 * 24 * 3600]])->group('uid')->order('create_time desc')->select();
        foreach ($user as &$vo) {
            $vo['nickname'] = db('user')->where('uid', $vo['uid'])->value('nickname');
        }
        unset($vo);
        $this->assign(['user' => $user]);

        $data['html'] = $this->fetch('bind_log');
        return Json::successful('获取成功!', $data);
    }

    /**
     * 批量审核版块
     *
     * @return json
     */
    public function verify()
    {
        $post = Util::postMore([
            ['ids', []]
        ]);
        if (empty($post['ids'])) {
            return JsonService::fail('请选择需要审核的版块');
        } else {
            $data['operation_uid']=SystemAdmin::activeAdminIdOrFail();
            $data['operation_identity']=1;
            $data['status']=1;
            $res = ThreadModel::where('id', 'in', $post['ids'])->update($data);
            $now_uid=get_uid();
            if ($res){
                foreach ($post['ids'] as $v){
                    $thread=db('com_thread')->where(['id'=>$v])->find();
                    $forum_name=db('com_forum')->where(['id'=>$thread['fid']])->value('name');
                    //出现新的标签
                    $time=time()-86400;
                    $newThread=db('com_thread')->where('status',1)->where('fid',$thread['fid'])->where('create_time','>',$time)->limit(5)->order('create_time desc')->column('id');
                    db('com_thread')->where('fid',$thread['fid'])->update(['is_new'=>0]);
                    db('com_thread')->where('id','in',$newThread)->update(['is_new'=>1]);

                    $thread['title']=$thread['title']?$thread['title']:mb_substr(json_decode($thread['content']),0,10).'...';
                    //发送消息
                    $set=MessageTemplate::getMessageSet(44);
                    $template=str_replace('{年月日时分}', time_format(time()), $set['template']);
                    $template=str_replace('{版块名称}', $forum_name, $template);
                    $template=str_replace('{帖子标题}', $thread['title'], $template);
                    if($set['status']==1){
                        $message_id=Message::sendMessage($thread['author_uid'],$now_uid,$template,1,$set['title'],1,'','thread',$thread['post_id']);
                        $read_id=MessageRead::createMessageRead($thread['author_uid'],$message_id,$set['popup'],1);
                    }
                    if($set['sms']==1&&$set['status']==1){
                        $account=UserModel::where('uid',$thread['author_uid'])->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template='【'.$config['cl_sms_sign'].'】'.$template;
                        $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                        $sms=json_decode($sms,true);
                        if ($sms['code']==0) {
                            $read_data['is_sms']=1;
                            $read_data['sms_time']=time();
                            MessageRead::where('id',$read_id)->update($read_data);
                        }
                    }
                    RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($thread['author_uid']),RoutineTemplateService::AUDIT_THREAD, [
                        'thing1'=>['value'=>$thread['title']],
                        'thing2'=>['value'=>'审核通过'],
                        'time4'=>['value'=>date('Y/m/d H:i',time())],
                        'thing5'=>['value'=>'请前往社区查看您的帖子'],
                     ],'','/packageA/post-page/post-page?id='.$v);
                }
                unset($v);
                Cache::clear('thread_list_cache');
                return JsonService::successful('审核成功');
            }
            else{
                return JsonService::fail('审核失败');
            }

        }
    }

    /**
     * 批量还原版块
     *
     * @return json
     */
    public function restore()
    {
        $post = Util::postMore([
            ['ids', []]
        ]);
        if (empty($post['ids'])) {
            return JsonService::fail('请选择需要还原的版块');
        } else {
            $res = ThreadModel::where('id', 'in', $post['ids'])->update(['status' => 1]);
//            $fid = ThreadModel::where('id', 'in', $post['ids'])->value('fid');
            $list=ThreadModel::where('id', 'in', $post['ids'])->field('author_uid,fid')->select();
            foreach ($list as $v){
                db('user')->where(['uid'=>$v['author_uid']])->setInc('post_count',1);
                db('com_forum')->where(['id'=>$v['fid']])->setInc('post_count',1);
                $time=time()-86400;
                $newThread=db('com_thread')->where('status',1)->where('fid',$v['fid'])->where('create_time','>',$time)->limit(5)->order('create_time desc')->column('id');
                db('com_thread')->where('fid',$v['fid'])->update(['is_new'=>0]);
                db('com_thread')->where('id','in',$newThread)->update(['is_new'=>1]);
            }
            unset($v);

            if ($res){
                Cache::clear('thread_list_cache');
                Cache::rm('channel_list_minutes');//清除频道计划任务列表
                return JsonService::successful('还原成功');
            }else{
                return JsonService::fail('还原失败');
            }
        }
    }

    /**
     * 批量删除帖子
     *
     * @return json
     */
    public function delete()
    {
        $post = Util::postMore([
            ['ids', []]
        ]);
        if (empty($post['ids'])) {
            return JsonService::fail('请选择需要删除的版块');
        } else {
            $data['operation_uid']=SystemAdmin::activeAdminIdOrFail();
            $data['operation_identity']=1;
            $data['status']=-1;
            $res = ThreadModel::where('id', 'in', $post['ids'])->update($data);
            if ($res) {
                foreach ($post['ids'] as $thread_id) {
                    Cache::clear('thread_detail_tag_' . $thread_id);
                }
                unset($thread_id);
                Cache::clear('thread_list_cache');
                Cache::clear('channel_list_change');
                return JsonService::successful('删除成功');
            } else
                return JsonService::fail('删除失败');
        }
    }

    /**
     * 清理指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function remove()
    {
        $post = Util::postMore([
            ['ids', []]
        ]);
        if (empty($post['ids'])) {
            return JsonService::fail('请选择需要清理的版块');
        } else {
            $res = ThreadModel::destroy($post['ids']);
            if ($res) {
                return JsonService::successful('清理成功');
            } else {
                return JsonService::fail('清理失败');
            }
        }
    }


    /**
     * 快速编辑
     *
     * @return json
     */
    public function quick_edit()
    {
        $field=osx_input('field','','text');
        $value=osx_input('value','','text');
        $id=osx_input('id',0,'intval');
        $field == '' || $id == '' || $value == '' && JsonService::fail('缺少参数');
        $fid = ThreadModel::where(['id' => $id])->value('fid');
        $uid = ThreadModel::where(['id' => $id])->value('author_uid');
        $title = ThreadModel::where('id', $id)->value('title');
        $forum_name = ForumModel::where('id', $fid)->value('name');
        $link_id = ThreadModel::where('id', $id)->value('post_id');
        $time = time_format(time());
        $length_title = mb_strlen($title, 'UTF-8');
        $length_name = mb_strlen($forum_name, 'UTF-8');
        if ($length_title > 7) {
            $title = mb_substr($title, 0, 7, 'UTF-8') . '…';
        }
        if ($length_name > 4) {
            $forum_name = mb_substr($forum_name, 0, 4, 'UTF-8') . '…';
        }
        if ($field == 'is_top') {
            $is_detail_top = ThreadModel::where('id', $id)->where('detail_top', 1)->count();
            if ($is_detail_top == 1) {
                return JsonService::fail('该帖子已经详情置顶，请先取消详情置顶');
            }
        }
        if (ThreadModel::where(['id' => $id])->update([$field => $value])) {
            if($field=='is_top'){
                db('channel_post')->where(['post_id'=>$id])->update(['is_top'=>$value]);//推送页的置顶一起改
                Cache::clear('channel_list_change');
            }
            Cache::clear('thread_list_cache');
            if ($field == 'is_top') {
                Cache::rm('forum_other_info_fid_' . $fid);
            }
            if ($value == -1 && $field == 'status') {
                Cache::clear('thread_detail_tag_' . $id);
                Cache::clear('channel_list_change');
                db('com_forum')->where('id', $fid)->setDec('post_count');
                db('user')->where('uid', $uid)->setDec('post_count');
                $set = MessageTemplate::getMessageSet(33);
                $template = str_replace('{年月日时分}', $time, $set['template']);
                $template = str_replace('{版块名称}', $forum_name, $template);
                $template = str_replace('{帖子标题}', $title, $template);
                if ($set['status'] == 1) {
                    $message_id = Message::sendMessage($uid, 0, $template, 1, $set['title'], 1,'','thread',$id);
                    $read_id = MessageRead::createMessageRead($uid, $message_id, $set['popup'], 1);
                }
                if ($set['sms'] == 1 && $set['status'] == 1) {
                    $account = UserModel::where('uid', $uid)->value('phone');
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $template = '【' . $config['cl_sms_sign'] . '】' . $template;
                    $sms = ChuanglanSmsApi::sendSMS($account, $template); //发送短信
                    $sms = json_decode($sms, true);
                    if ($sms['code'] == 0) {
                        $read_data['is_sms'] = 1;
                        $read_data['sms_time'] = time();
                        MessageRead::where('id', $read_id)->update($read_data);
                    }
                }
            }
            if ($value == 1 && $field == 'status'){
                $thread=db('com_thread')->where(['id'=>$id])->find();
                $forum_name=db('com_forum')->where(['id'=>$thread['fid']])->value('name');
                //出现新的标签
                $time=time()-86400;
                $newThread=db('com_thread')->where('status',1)->where('fid',$thread['fid'])->where('create_time','>',$time)->limit(5)->order('create_time desc')->column('id');
                db('com_thread')->where('fid',$thread['fid'])->update(['is_new'=>0]);
                db('com_thread')->where('id','in',$newThread)->update(['is_new'=>1]);

                $thread['title']=$thread['title']?$thread['title']:mb_substr(json_decode($thread['content']),0,10).'...';
                //奖励积分发送消息
                $jifen=0;
                if($thread['is_weibo']==1){
                   $jifen= Gong::actionadd('fadongtai','com_thread','author_uid',$thread['author_uid']);
                }elseif($thread['type']==6){
                   $jifen= Gong::actionadd('fashipin','com_thread','author_uid',$thread['author_uid']) ;
                }else{
                   $jifen= Gong::actionadd('fatie','com_thread','author_uid',$thread['author_uid']);
                }


                $set=MessageTemplate::getMessageSet(44);
                $template=str_replace('{年月日时分}', time_format($thread['create_time']), $set['template']);
                $template=str_replace('{版块名称}', $forum_name, $template);
                $template=str_replace('{帖子标题}', $thread['title'], $template);
                $template=str_replace('{奖励积分}', $jifen, $template);
                if($set['status']==1){
                    $message_id=Message::sendMessage($thread['author_uid'],get_uid(),$template,1,$set['title'],1,'','thread',$thread['post_id']);
                    $read_id=MessageRead::createMessageRead($thread['author_uid'],$message_id,$set['popup'],1);
                }
                if($set['sms']==1&&$set['status']==1){
                    $account=UserModel::where('uid',$thread['author_uid'])->value('phone');
                    $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                    $template='【'.$config['cl_sms_sign'].'】'.$template;
                    $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                    $sms=json_decode($sms,true);
                    if ($sms['code']==0) {
                        $read_data['is_sms']=1;
                        $read_data['sms_time']=time();
                        MessageRead::where('id',$read_id)->update($read_data);
                    }
                }
                RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($thread['author_uid']),RoutineTemplateService::AUDIT_THREAD, [
                    'thing1'=>['value'=>$thread['title']],
                    'thing2'=>['value'=>'审核通过'],
                    'time4'=>['value'=>date('Y/m/d H:i',time())],
                    'thing5'=>['value'=>'请前往社区查看您的帖子'],
                ],'','/packageA/post-page/post-page?id='.$id);

                

            }
            if ($value) {
                //置顶、加精 加分
                $actionflag = '';
                if ($field == 'is_top') {
                    $set = MessageTemplate::getMessageSet(37);
                    $template = str_replace('{年月日时分}', $time, $set['template']);
                    $template = str_replace('{版块名称}', $forum_name, $template);
                    $template = str_replace('{帖子标题}', $title, $template);
                    if ($set['status'] == 1) {
                        $message_id = Message::sendMessage($uid, 0, $template, 1, $set['title'], 1, '', 'thread', $link_id);
                        $read_id = MessageRead::createMessageRead($uid, $message_id, $set['popup'], 1);
                    }
                    if ($set['sms'] == 1 && $set['status'] == 1) {
                        $account = UserModel::where('uid', $uid)->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template = '【' . $config['cl_sms_sign'] . '】' . $template;
                        $sms = ChuanglanSmsApi::sendSMS($account, $template); //发送短信
                        $sms = json_decode($sms, true);
                        if ($sms['code'] == 0) {
                            $read_data['is_sms'] = 1;
                            $read_data['sms_time'] = time();
                            MessageRead::where('id', $read_id)->update($read_data);
                        }
                    }
                    $guanzhu = db('system_rule_action')->where('actionflag', 'beizhiding')->find();
                    //Support::addjifen($guanzhu, 1, $uid);
                    website_connect_notify($uid,$id,$uid,'admin_com_management_top');//通知第三方平台，任务回调
                } elseif ($field == 'is_essence') {
                    $set = MessageTemplate::getMessageSet(36);
                    $template = str_replace('{年月日时分}', $time, $set['template']);
                    $template = str_replace('{版块名称}', $forum_name, $template);
                    $template = str_replace('{帖子标题}', $title, $template);
                    if ($set['status'] == 1) {
                        $message_id = Message::sendMessage($uid, 0, $template, 1, $set['title'], 1, '', 'index', $link_id);
                        $read_id = MessageRead::createMessageRead($uid, $message_id, $set['popup'], 1);
                    }
                    if ($set['sms'] == 1 && $set['status'] == 1) {
                        $account = UserModel::where('uid', $uid)->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template = '【' . $config['cl_sms_sign'] . '】' . $template;
                        $sms = ChuanglanSmsApi::sendSMS($account, $template); //发送短信
                        $sms = json_decode($sms, true);
                        if ($sms['code'] == 0) {
                            $read_data['is_sms'] = 1;
                            $read_data['sms_time'] = time();
                            MessageRead::where('id', $read_id)->update($read_data);
                        }
                    }
                    $guanzhu = db('system_rule_action')->where('actionflag', 'beijiajing')->find();
                    //Support::addjifen($guanzhu, 1, $uid);
                    website_connect_notify($uid,$id,$uid,'admin_com_management_essence');//通知第三方平台，任务回调
                } elseif ($field == 'status') {
                    if($value!=1){
                         //帖子被删除扣除积分
                        Gong::delaction('fatie', $uid,'帖子被删除');
                        website_connect_notify($uid,$id,$uid,'admin_com_management_status');//通知第三方平台，任务回调
                    }
                   
                }

            } else {
                if ($field == 'is_top') {
                    website_connect_notify($uid,$id,$uid,'admin_com_management_un_top');//通知第三方平台，任务回调
                } elseif ($field == 'is_essence') {
                    website_connect_notify($uid,$id,$uid,'admin_com_management_un_essence');//通知第三方平台，任务回调
                }
            }
            Cache::rm('channel_list_minutes');//清除频道计划任务列表
            return JsonService::successful('保存成功');
        } else {

            return JsonService::fail('保存失败');
        }

    }

    //加分
    private function actionaddfen()
    {
        $actionflag=osx_input('actionflag','','text');
        $user_id=osx_input('user_id',0,'intval');
        $guanzhu = db('system_rule_action')->where('actionflag', $actionflag)->find();
        UserModel::where('uid', $user_id)->setDec('exp', $guanzhu['expone']);
        UserModel::where('uid', $user_id)->setDec('fly', $guanzhu['flyone']);
        UserModel::where('uid', $user_id)->setDec('gong', $guanzhu['gongone']);
        UserModel::where('uid', $user_id)->setDec('buy', $guanzhu['buyone']);
        UserModel::where('uid', $user_id)->setDec('one', $guanzhu['firstone']);

    }

    //减分
    private function actionsubfen()
    {
        $actionflag=osx_input('actionflag','','text');
        $user_id=osx_input('user_id',0,'intval');
        $guanzhu = db('system_rule_action')->where('actionflag', $actionflag)->find();
        UserModel::where('uid', $user_id)->setInc('exp', $guanzhu['expone']);
        UserModel::where('uid', $user_id)->setInc('fly', $guanzhu['flyone']);
        UserModel::where('uid', $user_id)->setInc('gong', $guanzhu['gongone']);
        UserModel::where('uid', $user_id)->setInc('buy', $guanzhu['buyone']);
        UserModel::where('uid', $user_id)->setInc('one', $guanzhu['firstone']);

    }

    public function thread_reward_log(){
        $id=osx_input('id',0,'intval');
        $where=[
            'tid'=>$id,
            'page'=>1,
            'limit'=>100,
            'data'=>''
        ];
        $data= ComForumAdminScoreLog::LogList($where);
        $list=$data['data'];
        $count=$data['count'];
        // $list = db('renwu_jiafen_log')->alias('r')->join('user u','r.uid = u.uid ','LEFT')->where('r.')->field('u.nickname,r.exp,r.fly,r.buy,r.gong,r.one,r.two,r.three,r.four,r.five,r.create_time,r.explain,r.model,r.type')->where($map)->order('r.id desc')->select();
        // $this->assign($list,$count);
        $this->assign('list',$list);
        $this->assign('total',$count);
        $this->assign('page',1);
        return $this->fetch();
    }


    public function quick_edit_recommend()
    {
        $field=osx_input('field','','text');
        $id=osx_input('id',0,'intval');
        $value=osx_input('value','','text');

        $field == '' || $id == '' || $value == '' && JsonService::fail('缺少参数');
        if (db('com_thread')->where(['id' => $id])->update([$field => $value])) {
            $uid = db('com_thread')->where('id', $id)->value('author_uid');
            if ($value) {
                $guanzhu = db('system_rule_action')->where('actionflag', 'beituijian')->find();
                //Support::addjifen($guanzhu, 1, $uid);
            } else {
                //$guanzhu = db('system_rule_action')->where('actionflag','beiquxiaotuijian')->find();
                //Support::addjifen($guanzhu,1,$uid) ;
            }
            if ($field == 'is_recommend') {
                if ($id) {
                    Cache::clear('thread_detail_tag_' . $id);//编辑帖子时清除帖子缓存
                }
            }

            if($field=='is_recommend'&&$value==0){
                website_connect_notify($uid, $id, $uid, 'admin_com_management_un_recommend');//通知第三方平台，任务回调
            }
            return JsonService::successful('保存成功');
        } else
            return JsonService::fail('保存失败');
    }

    public function quick_edit_detail_top()
    {
        $field=osx_input('field','','text');
        $id=osx_input('id',0,'intval');
        $value=osx_input('value','','text');

        $field == '' || $id == '' || $value == '' && JsonService::fail('缺少参数');
        $fid = ThreadModel::where('id', $id)->value('fid');
        $res1 = ThreadModel::where('detail_top', 1)->where('fid', $fid)->update([$field => 0]);
        if ($value == 1) {
            $is_top = ThreadModel::where('id', $id)->where('is_top', 1)->count();
            if ($is_top == 1) {
                return JsonService::fail('该帖子已经普通置顶，请先取消普通置顶');
            }
            $res2 = ThreadModel::where('id', $id)->update([$field => 1]);
        }
        if ($res1 === false && $res2 === false) {
            return JsonService::fail('保存失败');
        } else {
            Cache::clear('thread_list_cache');
            Cache::rm('com_detail_top');
            $tag='forum_other_info_fid_'.$fid;
            Cache::rm($tag);
            if($field=='detail_top'&&$value==0){
                $uid = db('com_thread')->where('id', $id)->value('author_uid');
                website_connect_notify($uid, $id, $uid, 'admin_com_management_un_detail_top');//通知第三方平台，任务回调
            }
            return JsonService::successful('保存成功');
        }
    }

    public function quick_edit_index_top()
    {
        $field=osx_input('field','','text');
        $id=osx_input('id',0,'intval');
        $value=osx_input('value','','text');

        $field == '' || $id == '' || $value == '' && JsonService::fail('缺少参数');
        $res1 = ThreadModel::where('index_top', 1)->update([$field => 0]);
        if ($value == 1) {
            $res2 = ThreadModel::where('id', $id)->update([$field => 1]);
        }
        if ($res1 === false && $res2 === false) {
            return JsonService::fail('保存失败');
        } else {
            Cache::clear('thread_list_cache');
            Cache::rm('com_index_top');
            if($field=='index_top'&&$value==0){
                $uid = db('com_thread')->where('id', $id)->value('author_uid');
                website_connect_notify($uid, $id, $uid, 'admin_com_management_un_index_top');//通知第三方平台，任务回调
            }
            return JsonService::successful('保存成功');
        }
    }

    public function move()
    {
        $ids=osx_input('ids','','text');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            // halt($data);
            if (!$data['ids']) {
                return JsonService::fail('请选择迁移后的版块');
            }
            $fid = $data['fid_class_id'][0];
            $class_id = $data['fid_class_id'][1];

            if (ThreadModel::where('id', 'in', $ids)->update(['fid' => $fid, 'class_id' => $class_id])){
                db('com_post')->where('tid', 'in', $ids)->update(['fid' => $fid]);
                Cache::clear('thread_list_cache');
                Cache::clear('thread_detail_tag');
                return JsonService::successful('成功');
            }else{
                return JsonService::fail('失败');
            }
        }
        $cascader_classes = ForumModel::cascader_class();
        // trace($cascader_classes);
        $field = [
            Form::cascader('fid_class_id', '所选帖子迁移到版块【-分类】')->data($cascader_classes),
            // Form::input('name','所选帖子迁移到')->readonly(true),
            // Form::select('fid','迁移帖子到其他版块')->setOptions(function(){
            //     $list  = ForumModel::getCatTierList();
            //     $menus = [];
            //     foreach ($list as $menu){
            //         $menus[] = ['value'=>$menu['id'],'label'=>$menu['html'].$menu['name']];//,'disabled'=>$menu['pid']== 0];
            //     }
            //     return $menus;
            // })->filterable(1),
            Form::hidden('ids', $ids),
        ];
        $form = Form::make_post_form('迁移帖子到其他版块', $field, Url::build('move'), 2);
        // $form->hiddenSubmitBtn(true);
        // $form->hiddenResetBtn(true);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    // 获取分类
    public function getThreadClassByForum()
    {
        $fid=osx_input('fid',0,'intval');
        $list = ThreadClassModel::where('fid', $fid)->field(['id' => 'value', 'name' => 'label'])->select();
        return Json::returnData(200, '', $list);
    }

    public function add_comment(Request $request)
    {
        $post = Util::postMore([
            ['ids', ''],
            ['time', 0],
            ['num', ''],
            ['temp', ''],
            ['temp_content', '','html'],
            ['is_post', 0],
        ], $request);
        if (empty($post['ids'])) {
            $id = Util::getMore([
                ['ids', ''],
            ], $request);
            $post['ids'] = $id['ids'];
        }
        if ($post['is_post'] == 1) {
            if ($post['time'] <= 0) {
                JsonService::fail('请选择正确的时间');
            }
            $time[0] = time() - $post['time'] * 3600;
            $time[1] = time();
            $ids = explode(',', $post['ids']);
            $res = ThreadModel::add_vest_comment($ids, $time, $post['num'], $post['temp'], $post['temp_content']);
            if ($res) {
                $resData['info'] = '马甲评论成功';
                Json::successful($resData);
            } else {
                JsonService::fail('马甲评论失败');
            }
        } else {
            $num = count(explode(',', $post['ids']));
            $time = date('Y:m:d H:i:s', time() - 12 * 3600) . ' ~ ' . date('Y-m-d H:i:s', time());
            $this->assign(
                [
                    'ids' => $post['ids'],
                    'num' => $num,
                    'time' => $time
                ]
            );
            return $this->fetch('add_comment');
        }
    }

    /**
     * 审核驳回
     * @return mixed|void
     */
    public function audit(){
        $params = Util::getMore([
            ['id',''],
            ['reason',''],
            ['is_post',0],
        ],$this->request);
        if($params['is_post']==1){
            $ids=explode(',',$params['id']);
            $data['operation_uid']=SystemAdmin::activeAdminIdOrFail();
            $data['operation_identity']=1;
            $data['reject_reason']=$params['reason'];
            $data['status']=0;
            $res=db('com_thread')->where(['id'=>['in',$ids]])->update($data);
            if($res){
                $now_uid=get_uid();
                foreach ($ids as $v){
                    $thread=db('com_thread')->where(['id'=>$v])->find();
                    $forum_name=db('com_forum')->where(['id'=>$thread['fid']])->value('name');
                    $thread['title']=$thread['title']?$thread['title']:mb_substr($thread['summary'],0,10).'...';
                    //发送消息
                    $set=MessageTemplate::getMessageSet(43);
                    $template=str_replace('{年月日时分}', time_format($thread['create_time']), $set['template']);
                    $template=str_replace('{版块名称}', $forum_name, $template);
                    $template=str_replace('{帖子标题}', $thread['title'], $template);
                    $template=str_replace('{驳回原因}', $params['reason'], $template);
                    if($set['status']==1){
                        $message_id=Message::sendMessage($thread['author_uid'],$now_uid,$template,1,$set['title'],1,'','thread',$thread['post_id']);
                        $read_id=MessageRead::createMessageRead($thread['author_uid'],$message_id,$set['popup'],1);
                    }
                    if($set['sms']==1&&$set['status']==1){
                        $account=UserModel::where('uid',$thread['author_uid'])->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template='【'.$config['cl_sms_sign'].'】'.$template;
                        $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                        $sms=json_decode($sms,true);
                        if ($sms['code']==0) {
                            $read_data['is_sms']=1;
                            $read_data['sms_time']=time();
                            MessageRead::where('id',$read_id)->update($read_data);
                        }
                    }
                    // if($thread['is_weibo']==1){
                    //     Gong::delaction('fadongtai',$thread['author_uid'],'动态被驳回');
                    // }elseif($thread['type']==6){
                    //     Gong::delaction('fashipin',$thread['author_uid'],'视频被驳回');
                    // }else{
                    //     Gong::delaction('fatie', $thread['author_uid'],'帖子被驳回');
                    // }
                    RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($thread['author_uid']),RoutineTemplateService::AUDIT_THREAD, [
                        'thing1'=>['value'=>$thread['title']],
                        'thing2'=>['value'=>'审核驳回'],
                        'time4'=>['value'=>date('Y/m/d H:i',time())],
                        'thing5'=>['value'=>$params['reason']],
                    ],'','/pages/user/index');
                }
                unset($v);
                Cache::clear('thread_list_cache');
                return Json::successful('审核成功!');
            }else{
                return Json::fail('审核失败!');
            }
        }else {
            $field = [
                Form::textarea('reason', '理由', ''),
                Form::hidden('id', $params['id']),
                Form::hidden('is_post', 1),
            ];
            $form = Form::make_post_form('填写驳回理由', $field, Url::build('audit'), 2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
    }

    /**
     * 删除帖子
     * @return mixed|void
     */
    public function delete_forum(){
        $params = Util::getMore([
            ['id',''],
            ['reason',''],
            ['is_post',0],
            ['status',0]
        ],$this->request);
        if($params['is_post']==1){
            $ids=explode(',',$params['id']);
            $data['operation_uid']=SystemAdmin::activeAdminIdOrFail();
            $data['operation_identity']=1;
            $data['delete_reason']=$params['reason'];
            $data['status']=-1;
            if($params['status']==3){
                $res=db('com_thread_draft')->where(['id'=>['in',$ids]])->update($data);
            }else{
                $res=db('com_thread')->where(['id'=>['in',$ids]])->update($data);
            }
            if($res&&$params['status']!=3){
                $now_uid=get_uid();
                foreach ($ids as $v){
                    $thread=db('com_thread')->where(['id'=>$v])->find();
                    db('com_forum')->where('id', $thread['fid'])->setDec('post_count');
//                    db('user')->where('uid', $thread['author_uid'])->setDec('post_count');
                    $forum_name=db('com_forum')->where(['id'=>$thread['fid']])->value('name');
                    $thread['title']=$thread['title']?$thread['title']:mb_substr($thread['summary'],0,10).'...';
                    //发送消息
                    $set=MessageTemplate::getMessageSet(42);
                    $template=str_replace('{年月日时分}', time_format($thread['create_time']), $set['template']);
                    $template=str_replace('{版块名称}', $forum_name, $template);
                    $template=str_replace('{帖子标题}', $thread['title'], $template);
                    $template=str_replace('{删除原因}', $params['reason'], $template);
                    if($set['status']==1){
                        $message_id=Message::sendMessage($thread['author_uid'],$now_uid,$template,1,$set['title'],1,'','index',$thread['post_id']);
                        $read_id=MessageRead::createMessageRead($thread['author_uid'],$message_id,$set['popup'],1);
                    }
                    if($set['sms']==1&&$set['status']==1){
                        $account=UserModel::where('uid',$thread['author_uid'])->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template='【'.$config['cl_sms_sign'].'】'.$template;
                        $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                        $sms=json_decode($sms,true);
                        if ($sms['code']==0) {
                            $read_data['is_sms']=1;
                            $read_data['sms_time']=time();
                            MessageRead::where('id',$read_id)->update($read_data);
                        }
                    }
                    website_connect_notify($thread['author_uid'],$v,$thread['author_uid'],'admin_com_thread_delete');//通知第三方平台，任务回调
                    if($thread['is_weibo']==1){
                        Gong::delaction('fadongtai',$thread['author_uid'],'动态被删除');
                    }elseif($thread['type']==6){
                        Gong::delaction('fashipin',$thread['author_uid'],'视频被删除');
                    }else{
                        Gong::delaction('fatie', $thread['author_uid'],'帖子被删除');
                    }
                    ChannelOsApi::delPost($v);
                }
                unset($v);
                Cache::clear('thread_list_cache');
                Cache::clear('channel_list_change');
                return Json::successful('删除成功!');
            }else{
                if($res){
                    return Json::successful('删除成功!');
                }else{
                    return Json::fail('删除失败!');
                }
            }
        }else {
            $field = [
                Form::textarea('reason', '理由', ''),
                Form::hidden('id', $params['id']),
                Form::hidden('status', $params['status']),
                Form::hidden('is_post', 1),
            ];
            $form = Form::make_post_form('填写删除理由', $field, Url::build('delete_forum'), 2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
    }

    /**
     * 版主管理设置
     * @return mixed|void
     */
    public function set_management(){
        $params = Util::getMore([
            ['id',''],
            ['type',''],
            ['end_time',0],
            ['is_post',0],
            ['time_type',1],
        ],$this->request);
        if($params['is_post']==1){
            $is_type=$params['type'];
            $reward=$_POST['reward'];
            $reward_explain=input('reward_explain','');
            if($params['type']=='reward'){
                $uid = ThreadModel::where(['id' => $params['id']])->value('author_uid');
                $fid = ThreadModel::where(['id'=>$params['id']])->value('fid');
                $score_log=array();
                if(!$reward){
                    return Json::fail('请输入积分!');
                }
                foreach($reward as &$value){
                    $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                    if($score_res!==false){
                        $score_log[$value['flag']]=$value['num'];
                    }
                }
                unset($value);
                Support::jiafenlog($uid,'后台推荐积分奖励',$score_log,1,'行为') ;
                $score_log['uid']=$uid;
                $score_log['do_uid']=$this->adminId;
                $score_log['from']='推荐';
                $score_log['fid']=$fid;
                $score_log['tid']=$params['id'];
                $score_log['explain']=$reward_explain;
                $score_log['type']=1;
                $score_log['model']=2;
                $score_log['create_time']=time();
                $res=ComForumAdminScoreLog::create($score_log);
            }else{
                //判断是否置顶
                // $is_exit=ThreadModel::where('id',$params['id'])->where('is_'.$params['type'], 1)->where($params['type'].'_end_time','gt',time())->count();
                // if($is_exit>0){
                //     return JsonService::fail('该帖已经置顶');
                // }
                if($params['time_type']==1){
                    $end_time=$params['end_time']*86400;
                    $end_time=time()+$end_time;
                }else{
                    $end_time=strtotime($params['end_time']);
                }
                $fid = ThreadModel::where(['id'=>$params['id']])->value('fid');
                if($params['type']=='detail_top'){
                    $is_top = ThreadModel::where('id',$params['id'])->where('is_top', 1)->count();
                    if ($is_top == 1) {
                        return JsonService::fail('该帖子已经普通置顶，请先取消普通置顶');
                    }
                    ThreadModel::where('detail_top', 1)->where('fid', $fid)->update(['detail_top' => 0]);
                    $is_type=$params['type'];
                }elseif($params['type']=='index_top'){
                    $index_top_count=ThreadModel::where('index_top', 1)->count();
                    if($index_top_count>4){
                        $first_id=ThreadModel::where('index_top', 1)->order('index_top_time asc')->value('id');
                        ThreadModel::where('id',$first_id)->update(['index_top' => 0]);
                    }
                    $is_type=$params['type'];
                }else{
                    $is_type='is_'.$params['type'];
                }
                $type_uid=$params['type'].'_uid';
                $time_type=$params['type'].'_time';
                $end_time_type=$params['type'].'_end_time';
                $res=ThreadModel::where('id',$params['id'])->update([$is_type=>1,$end_time_type=>$end_time,$time_type=>time(),$type_uid=>0]);
                
            }
            if($res){
                $uid = ThreadModel::where(['id' => $params['id']])->value('author_uid');
                $title = ThreadModel::where('id', $params['id'])->value('title');
                $forum_name = ForumModel::where('id', $fid)->value('name');
                $link_id = ThreadModel::where('id', $params['id'])->value('post_id');
                $time = time_format(time());
                $length_title = mb_strlen($title, 'UTF-8');
                $length_name = mb_strlen($forum_name, 'UTF-8');
                if ($length_title > 7) {
                    $title = mb_substr($title, 0, 7, 'UTF-8') . '…';
                }
                if ($length_name > 4) {
                    $forum_name = mb_substr($forum_name, 0, 4, 'UTF-8') . '…';
                }
                if($params['type']=='recommend'){
                    $score_log=array();
                    if($reward){
                        foreach($reward as &$value){
                            $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                            if($score_res!==false){
                                $score_log[$value['flag']]=$value['num'];
                            }
                        }
                        unset($value);
                        Support::jiafenlog($uid,'后台推荐积分奖励',$score_log,1,'行为') ;
                        $score_log['uid']=$uid;
                        $score_log['do_uid']=1;
                        $score_log['from']='推荐';
                        $score_log['fid']=$fid;
                        $score_log['tid']=$params['id'];
                        $score_log['explain']=$reward_explain;
                        $score_log['type']=1;
                        $score_log['model']=2;
                        $score_log['create_time']=time();
                        ComForumAdminScoreLog::create($score_log);
                    }
                }
                if($params['type']=='detail_top'){
                    $score_log=array();
                    if($reward){
                        foreach($reward as &$value){
                            $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                            if($score_res!==false){
                                $score_log[$value['flag']]=$value['num'];
                            }
                        }
                        unset($value);
                        Support::jiafenlog($uid,'后台详情置顶积分奖励',$score_log,1,'行为') ;
                        $score_log['uid']=$uid;
                        $score_log['do_uid']=1;
                        $score_log['from']='详情置顶';
                        $score_log['fid']=$fid;
                        $score_log['tid']=$params['id'];
                        $score_log['explain']=$reward_explain;
                        $score_log['type']=1;
                        $score_log['model']=2;
                        $score_log['create_time']=time();
                        ComForumAdminScoreLog::create($score_log);
                    }
                    Cache::rm('com_detail_top');
                }
                if($params['type']=='index_top'){
                    $score_log=array();
                    if($reward){
                        foreach($reward as &$value){
                            $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                            if($score_res!==false){
                                $score_log[$value['flag']]=$value['num'];
                            }
                        }
                        unset($value);
                        Support::jiafenlog($uid,'后台首页置顶积分奖励',$score_log,1,'行为') ;
                        $score_log['uid']=$uid;
                        $score_log['do_uid']=1;
                        $score_log['from']='首页置顶';
                        $score_log['fid']=$fid;
                        $score_log['tid']=$params['id'];
                        $score_log['explain']=$reward_explain;
                        $score_log['type']=1;
                        $score_log['model']=2;
                        $score_log['create_time']=time();
                        ComForumAdminScoreLog::create($score_log);
                    }
                    Cache::clear('thread_list_cache');
                    Cache::clear('com_index_top');
                }
                if($params['type']=='essence'){
                    $score_log=array();
                    if($reward){
                        foreach($reward as &$value){
                            $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                            if($score_res!==false){
                                $score_log[$value['flag']]=$value['num'];
                            }
                        }
                        unset($value);
                        Support::jiafenlog($uid,'后台加精积分奖励',$score_log,1,'行为') ;
                        $score_log['uid']=$uid;
                        $score_log['do_uid']=1;
                        $score_log['from']='加精';
                        $score_log['fid']=$fid;
                        $score_log['tid']=$params['id'];
                        $score_log['explain']=$reward_explain;
                        $score_log['type']=1;
                        $score_log['model']=2;
                        $score_log['create_time']=time();
                        ComForumAdminScoreLog::create($score_log);
                    }
                    $set = MessageTemplate::getMessageSet(36);
                    $template = str_replace('{年月日时分}', $time, $set['template']);
                    $template = str_replace('{版块名称}', $forum_name, $template);
                    $template = str_replace('{帖子标题}', $title, $template);
                    if ($set['status'] == 1) {
                        $message_id = Message::sendMessage($uid, 0, $template, 1, $set['title'], 1, '', 'index', $link_id);
                        $read_id = MessageRead::createMessageRead($uid, $message_id, $set['popup'], 1);
                    }
                    if ($set['sms'] == 1 && $set['status'] == 1) {
                        $account = UserModel::where('uid', $uid)->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template = '【' . $config['cl_sms_sign'] . '】' . $template;
                        $sms = ChuanglanSmsApi::sendSMS($account, $template); //发送短信
                        $sms = json_decode($sms, true);
                        if ($sms['code'] == 0) {
                            $read_data['is_sms'] = 1;
                            $read_data['sms_time'] = time();
                            MessageRead::where('id', $read_id)->update($read_data);
                        }
                    }
                    $guanzhu = db('system_rule_action')->where('actionflag', 'beijiajing')->find();
                    //Support::addjifen($guanzhu, 1, $uid);
                }
                if ($is_type == 'is_top') {
                    db('channel_post')->where('post_id',$params['id'])->update(['is_top'=>1]);//推送设置为置顶
                    Cache::clear('channel_list_change');
                    $score_log=array();
                    if($reward){
                        foreach($reward as &$value){
                            $score_res=UserModel::where('uid',$uid)->setInc($value['flag'],$value['num']);
                            if($score_res!==false){
                                $score_log[$value['flag']]=$value['num'];
                            }
                        }
                        unset($value);
                        Support::jiafenlog($uid,'后台置顶积分奖励',$score_log,1,'行为') ;
                        $score_log['uid']=$uid;
                        $score_log['do_uid']=1;
                        $score_log['from']='置顶';
                        $score_log['fid']=$fid;
                        $score_log['tid']=$params['id'];
                        $score_log['explain']=$reward_explain;
                        $score_log['type']=1;
                        $score_log['model']=2;
                        $score_log['create_time']=time();
                        ComForumAdminScoreLog::create($score_log);
                    }
                    Cache::rm('forum_other_info_fid_' . $fid);
                    $set = MessageTemplate::getMessageSet(37);
                    $template = str_replace('{年月日时分}', $time, $set['template']);
                    $template = str_replace('{版块名称}', $forum_name, $template);
                    $template = str_replace('{帖子标题}', $title, $template);
                    if ($set['status'] == 1) {
                        $message_id = Message::sendMessage($uid, 0, $template, 1, $set['title'], 1, '', 'thread', $link_id);
                        $read_id = MessageRead::createMessageRead($uid, $message_id, $set['popup'], 1);
                    }
                    if ($set['sms'] == 1 && $set['status'] == 1) {
                        $account = UserModel::where('uid', $uid)->value('phone');
                        $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                        $template = '【' . $config['cl_sms_sign'] . '】' . $template;
                        $sms = ChuanglanSmsApi::sendSMS($account, $template); //发送短信
                        $sms = json_decode($sms, true);
                        if ($sms['code'] == 0) {
                            $read_data['is_sms'] = 1;
                            $read_data['sms_time'] = time();
                            MessageRead::where('id', $read_id)->update($read_data);
                        }
                    }
                    $guanzhu = db('system_rule_action')->where('actionflag', 'beizhiding')->find();
                    Support::addjifen($guanzhu, 1, $uid);
                }
                Cache::clear('thread_list_cache');

                switch ($params['type']){
                    case 'detail_top':
                        website_connect_notify($uid,$params['id'],$uid,'admin_com_management_detail_top');//通知第三方平台，任务回调
                        break;
                    case 'index_top':
                        website_connect_notify($uid,$params['id'],$uid,'admin_com_management_index_top');//通知第三方平台，任务回调
                        break;
                    case 'top':
                        website_connect_notify($uid,$params['id'],$uid,'admin_com_management_top');//通知第三方平台，任务回调
                        break;
                    case 'recommend':
                        website_connect_notify($uid,$params['id'],$uid,'admin_com_management_recommend');//通知第三方平台，任务回调
                        break;
                    case 'essence':
                        website_connect_notify($uid,$params['id'],$uid,'admin_com_management_essence');//通知第三方平台，任务回调
                        break;
                }

                return Json::successful('设置成功!');
            }else{
                return Json::fail('设置失败!');
            }
        }else {
            $top=ThreadModel::where('id',$params['id'])->field('id,is_top,top_end_time,detail_top,detail_top_end_time,index_top,index_top_end_time')->find();
            $top['top_end_time']=time_format($top['top_end_time']);
            $top['detail_top_end_time']=time_format($top['detail_top_end_time']);
            $top['index_top_end_time']=time_format($top['index_top_end_time']);
            $score=db('system_rule')->where('status',1)->where('is_del',0)->select();
            $this->assign('top',$top);
            $this->assign('score',$score);
            $this->assign('type',$params['type']);
            $this->assign('id',$params['id']);
            return $this->fetch();
        }
    }

    /**
     * 展示二维码草稿
     * @return mixed
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public function show_code(){
        $id=osx_input('get.id',0,'intval');
        $h5_url=SystemConfig::getValue('platform_h5_url');
        $url=$h5_url.'packageA/post-page/post-page?id='.$id.'&noBlackList=false';
        //生成二维码 start
        require_once ROOT.'/vendor/phpqrcode/phpqrcode.php';
        $qrcode=new \QRcode();

        $errorCorrectionLevel = 'H';    //容错级别
        $matrixPointSize = 7;           //生成二维码图片大小

        $thumb_dir_path=UPLOAD_PATH.'/caogao/';
        if (!is_dir($thumb_dir_path)){
            mkdir($thumb_dir_path,0777,true);
        }
        $code_width=$code_height=200;
        $qrcode_file_name=$thumb_dir_path.'thumb_qr_code'.$id.'.png';
        ob_start();
        $qrcode::png($url,$qrcode_file_name , $errorCorrectionLevel, $matrixPointSize, 2);
        ob_end_clean();//关闭缓冲区
        $qrcode_image = getThumbImage($qrcode_file_name,$code_width,$code_height);
        $this->assign(['image'=>'/'.$qrcode_image['src']]);
        $this->assign(['url'=>$url]);
        return $this->fetch();
    }

    /**
     * 获取草稿地址
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public function get_draft_url(){
        $id=osx_input('id',0,'intval');
        $h5_url=SystemConfig::getValue('platform_h5_url');
        $url=$h5_url.'packageA/post-page/post-preview?id='.$id.'&noBlackList=false';
        $data['url']=$url;
        return JsonService::successful('获取成功',$data);
    }

    /**
     * 获取h5地址
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public function get_thread_url(){
        $id=osx_input('id',0,'intval');
        $h5_url=SystemConfig::getValue('platform_h5_url');
        if(!$h5_url){
            return JsonService::fail('H5地址未设置');
        }
        $url=$h5_url.'packageA/post-page/post-page?id='.$id.'&noBlackList=false';
        $data['url']=$url;
        return JsonService::successful('获取成功',$data);
    }

}
