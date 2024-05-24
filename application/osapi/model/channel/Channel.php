<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/1
 * Time: 13:48
 */

namespace app\osapi\model\channel;


use app\admin\model\channel\ChannelAdmin;
use app\admin\model\channel\ChannelCountContent;
use app\admin\model\channel\ChannelCountOpenRate;
use app\admin\model\channel\ChannelCountView;
use app\admin\model\channel\ChannelCountViewLog;
use app\admin\model\channel\ChannelPost;
use app\admin\model\channel\ChannelPostPool;
use app\admin\model\com\ForumPower;
use app\core\model\user\User;
use app\osapi\model\BaseModel;
use app\osapi\model\com\ComForum;
use app\osapi\model\com\ComPost;
use app\osapi\model\com\ComThread;
use think\Cache;
use traits\ModelTrait;

class Channel extends BaseModel
{
    use ModelTrait;

    /**
     * -频道引导页-获取推荐频道列表
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function getRecommend()
    {
//        $list=self::where('type',2)->where('status',1)->field('id,title,logo')->order('default_sort asc')->select();
        $list=self::where(['status'=>1,'default_open_status'=>0])->field('id,title,logo')->order('default_sort asc')->select();
        if(count($list)){
            $list=$list->toArray();
        }
        return $list;
    }


    /**
     * -频道首页-获取频道展示列表
     * @param int $uid
     * @return array|mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function getShowList($uid=0)
    {
        $default_channel=self::_getDefaultSetList();
        if($uid!=0){
            $user_channel_ids=ChannelUser::where('uid',$uid)->where('status',1)->order('sort_num asc')->column('channel_id');
            if(count($user_channel_ids)){
                $show_list=[];
                foreach ($user_channel_ids as $val){
                    if(isset($default_channel['all_channel_list'][$val])){
                        $show_list[]=$default_channel['all_channel_list'][$val];
                    }
                }
                unset($val);
            }
        }
        if(!isset($show_list)){
            $has_set=ChannelUser::where('uid',$uid)->count();
            if($has_set){
                $show_list=$default_channel['system_list'];
            }else{
                $show_list=$default_channel['show_list'];
            }
        }
        return $show_list;
    }

    /**
     * -频道编辑页-所有频道列表
     * @param int $uid
     * @return array|mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function allChannelList($uid=0)
    {
        if($uid==0){
            return [];
        }
        $default_channel=self::_getDefaultSetList();
        $has_set=ChannelUser::where('uid',$uid)->count();
        $user_channel_ids=ChannelUser::where('uid',$uid)->where('status',1)->order('sort_num asc')->column('channel_id');
        $all_channels=$default_channel['all_channel_list'];
        foreach ($all_channels as &$val){
            if($val['type']==2){//自定义频道
                if($has_set){
                    if(count($user_channel_ids)){
                        if(in_array($val['id'],$user_channel_ids)){
                            $val['my_channel']=1;//自定义频道是我的频道（我的频道列表展示）
                        }else{
                            $val['my_channel']=0;//自定义频道不是我的频道（更多频道列表展示）
                        }
                    }else{
                        $val['my_channel']=0;//自定义频道不是我的频道（更多频道列表展示）
                    }
                }else{
                    if($val['default_open_status']==1){
                        $val['my_channel']=1;//自定义频道是我的频道（我的频道列表展示）
                    }else{
                        $val['my_channel']=0;//自定义频道不是我的频道（更多频道列表展示）
                    }
                }
            }
        }
        unset($val);
        return $all_channels;
    }

    /**
     * -可推荐频道的频道列表
     * @param int $uid
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function canRecommendList($uid=0)
    {
        if($uid==0){
            return [];
        }

        $channel_list=Channel::where('status',1)->where('post_type','in',[2,3])->field('id,title,logo,intor,post_audit,post_intor')->order('type asc,default_sort asc')->select();
        if(count($channel_list)){
            $channel_list=$channel_list->toArray();
            $channel_ids=array_column($channel_list,'id');
            $cannel_admin_ids=ChannelAdmin::where('channel_id','in',$channel_ids)->where('status',1)->where('uid',$uid)->column('channel_id');
            foreach ($channel_list as &$val)
            {
                if(in_array($val['id'],$cannel_admin_ids)){
                    $val['is_admin']=1;//是频道管理员(实际先判断post_audit，是否需要审核，1为要审核)
                }else{
                    $val['is_admin']=0;//不是频道管理员
                }
            }
            unset($val);
        }
        return $channel_list;
    }

    /**
     * -判断是否可以投稿以及是否为管理员
     * @param $channel_id
     * @param int $uid
     * @return mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function checkCanRecommend($channel_id,$uid=0)
    {
        $audit_info=self::where('status',1)->where('post_type','in',[2,3])->where('id',$channel_id)->find();
        $return['is_admin']=ChannelAdmin::where('status',1)->where('uid',$uid)->where('channel_id',$channel_id)->count();
        $return['can_recommend']=$audit_info?1:0;
        $return['need_audit']=$audit_info['post_audit']?1:0;
        return $return;
    }

    /**
     * -默认频道配置-公用，有缓存
     * @return array|mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    private static function _getDefaultSetList()
    {
        $return_channel_list=Cache::get('default_set_channel_list');
        if(!$return_channel_list){
            $channel_list=self::where('status',1)->order('default_sort asc')->field('id,title,logo,default_open_status,fixed,type,is_index')->select()->toArray();
            $all_show_list=$system_channel_list=$other_show_channel_list=$other_un_show_channel_list=[];
            foreach ($channel_list as $val){
                if($val['default_open_status']==1){
                    $all_show_list[]=$val;
                }else{
                    $other_show_channel_list[]=$val;
                }
//                if($val['type']==1){
//                    $system_channel_list[]=$val;
//                }else{
//                    if($val['default_open_status']==1){
//
//                    }else{
//                        $other_un_show_channel_list[]=$val;
//                    }
//                }
            }
            $return_channel_list=[
                //全部开启的频道，以频道id为键
                'all_channel_list'=>array_combine(array_column($channel_list,'id'),$channel_list),
                //全部展示的频道，自带顺序，无需重新整理展示顺序
                'show_list'=>$all_show_list,
                //系统频道列表，带顺序
                'system_list'=>$system_channel_list,
                //自定义频道中默认展示的频道列表，带顺序
                'other_show_list'=>$other_show_channel_list,
                //自定义频道中默认不展示的频道列表，带顺序
                'other_un_show_list'=>$other_un_show_channel_list
            ];
            Cache::set('default_set_channel_list',$return_channel_list);
        }
        return $return_channel_list;
    }


    /**
     * -自动推荐列表更新
     * @param int $is_ok
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function autoRecommendPost($is_ok=0)
    {
        $channel_list=self::_getAutoRecommendChannel();
        foreach ($channel_list as $key=>$val){
            $res=self::_doOneChannelRecommend($val,$is_ok);

            if(!$res){
                $data['name']='更新频道【'.$val['id'].'-'.$val['title'].'】的自动推荐帖子时失败';
                $data['type']=2;
                $data['create_time']=time();
                db('script')->insert($data);
            }
        }
        unset($val);
        return true;
    }

    /**
     * -更新单个频道的自动推送列表
     * @param $channel_info
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    private static function _doOneChannelRecommend($channel_info,$is_ok=false)
    {
        //检查是否需要更新
        //+10*10 调整10分钟，防止出现卡点情况
//        $last_time=time()-intval($channel_info['list_update_interval'])*60*60+10*10;

        $num=$channel_info['list_update_interval_type']==0?1:60;

        $last_time=time()-intval($channel_info['list_update_interval'])*60*$num;
        $map['create_time']=array('egt',$last_time);
        $map['channel_id']=$channel_info['id'];

        if(db('channel_recommend_log')->where($map)->count()&&!$is_ok){
            return true;//没到刷新时间，不进行刷新
        }
        unset($map,$last_time);

        //设置自动推送帖子状态为 -2
        ChannelPost::where('post_type',1)->where('channel_id',$channel_info['id'])->setField('status',-2);

        //-------------循环执行新增自动推荐帖子 start----------------
        $page=1;
        $limit=2000;
        $fields='id,title,image,author_uid,support_count,reply_count,collect_count,create_time,last_post_time,update_time,type,is_weibo,content,send_time';
        //根据配置构造map查询条件
        $model=self::_build_map($channel_info);
        $one_data=[
            'channel_id'=>$channel_info['id'],
            'recommend_uid'=>0,
            'status'=>1,
            'is_hide'=>0,
            'post_type'=>1,
            'post_long'=>1,
            'deadline'=>'2145888000',//2038年1月1日的时间戳
            'sort_num'=>0,
            'is_top'=>0,
            'create_time'=>time(),
            'update_time'=>time()
        ];
        do{
            //可推荐帖子信息分页获取
            $post_list=$model->page($page,$limit)->field($fields)->order(self::_build_order($channel_info))->select();
            $total_num=count($post_list);
            if(count($post_list)){
                $post_list=$post_list->toArray();
                //去除已存在帖子
                $post_ids=array_column($post_list,'id');
                $already_exist=ChannelPost::where('post_id','in',$post_ids)
                    ->where('status','>=',-1)
                    ->where('channel_id',$channel_info['id'])
                    ->column('post_id');
                $old_auto_list=ChannelPost::where('post_id','in',$post_ids)
                    ->where('status',-2)
                    ->where('post_type',1)
                    ->where('channel_id',$channel_info['id'])
                    ->column('post_id');

                foreach ($post_list as $key=>$val) {
                    if (in_array($val['id'], $already_exist)) {
                        unset($post_list[$key]);
                        continue;
                    }
                    if (in_array($val['id'], $old_auto_list)) {
                        unset($post_list[$key]);
                        ChannelPost::where('status',-2)
                            ->where('post_type',1)
                            ->where('channel_id',$channel_info['id'])
                            ->where('post_id',$val['id'])
                            ->limit(1)
                            ->order('id asc')
                            ->setField('status',1);
                    }
                }
                unset($key,$val);
                //删除状态为-2的自动推送帖子
                ChannelPost::where('post_type',1)
                    ->where('status',-2)
                    ->where('channel_id',$channel_info['id'])
                    ->delete();
                //去除已存在帖子 end
                if(count($post_list)){
                    //新增自动推荐帖子到数据表
                    $add_list=[];
                    $uids=array_column($post_list,'author_uid');
                    $author_info_list=User::where('uid','in',$uids)->field('uid,nickname,phone')->select();
                    if(count($author_info_list)){
                        $author_info_list=$author_info_list->toArray();
                        $author_info_list=array_combine(array_column($author_info_list,'uid'),$author_info_list);
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }else{
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }
                    foreach ($post_list as $val){
                        $one_post=$one_data;
                        $one_post['post_id']=$val['id'];
                        if($val['is_weibo']==1){
                            $one_post['post_title']=text($val['content']);
                        }else{
                            $one_post['post_title']=$val['title'];
                        }
                        if(!isset($author_info_list[$val['author_uid']])){
                            $author_info_list[$val['author_uid']]=[
                                'nickname'=>'',
                                'phone'=>''
                            ];
                        }
                        $one_post['post_author']='【'.$val['author_uid'].'】'.$author_info_list[$val['author_uid']]['nickname'].'('.$author_info_list[$val['author_uid']]['phone'].')';
                        $one_post['post_support_count']=$val['support_count'];
                        $one_post['post_comment_count']=$val['reply_count'];
                        $one_post['post_collect_count']=$val['collect_count'];
                        $one_post['post_create_time']=$val['send_time']>0?$val['send_time']:$val['create_time'];
                        $one_post['post_comment_time']=$val['last_post_time'];
                        $one_post['post_update_time']=$val['update_time'];
                        $one_post['type']=$val['type'];
                        $val['images']=ChannelPost::getPostImages($val['image']);
                        if(count($val['images'])){
                            if(count($val['images'])>3){
                                $one_post['image_show_type']=3;
                            }else{
                                $one_post['image_show_type']=count($val['images']);
                            }
                        }else{
                            //资讯帖子默认为单图
                            if ($one_post['type']==4){
                                $one_post['image_show_type']=1;
                            }else{
                                $one_post['image_show_type']=4;//无图
                            }

                        }
                        $add_list[]=$one_post;
                    }
                    $res=ChannelPost::setAll($add_list);
                    //新增自动推荐帖子到数据表end
                }
            }
            $page++;
        }while($total_num==$limit);
        //-------------循环执行新增自动推荐帖子 end-------------------

        //清除信息流缓存
        Cache::clear('channel_list_change');

        //插入自动推荐执行记录
        db('channel_recommend_log')->insert(array(
            'channel_id'=>$channel_info['id'],
            'create_time'=>time()
        ));
        return true;
    }

    /**
     * -获取开启自动推荐的频道列表
     * @return array|false|\PDOStatement|string|\think\Collection
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    private static function _getAutoRecommendChannel()
    {
        $map['post_type']=array('in',[1,3]);
        $map['id']=array(array('eq',2),array('egt',5),'OR');
        $map['status']=1;
        $list=self::where($map)->select();
        if(count($list)){
            $list=$list->toArray();
        }
        return $list;
    }

    /**
     * -构造帖子自动推荐排序方式，用到的数据优先处理，防止任务时间过长被中断
     * @param $channel_info
     * @return ComThread
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    private static function _build_order($channel_info)
    {
        $order='id desc';
        switch ($channel_info['list_sort_type']){
            case 1://1. 按点赞数目倒序
                $order='support_count desc,'.$order;
                break;
            case 2://2. 按评论数目倒序
                $order='reply_count desc,'.$order;
                break;
            case 3://3. 按收藏数目倒序
                $order='collect_count desc,'.$order;
                break;
            case 4://4. 按发布时间倒序（默认）
                $order='create_time desc,'.$order;
                break;
            case 5://5. 按回复时间倒序
                $order='last_post_time desc,'.$order;
                break;
            case 6://6. 按修改时间倒序
                $order='update_time desc,'.$order;
                break;
            default:
                $order='create_time desc,'.$order;
        }
        return $order;
    }
    /**
     * -构造帖子自动推荐查询条件
     * @param $channel_info
     * @return ComThread
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    private static function _build_map($channel_info)
    {
        $model=new ComThread();
        $model->where('status',1);
        //来源类型
        switch ($channel_info['from_type']){
            case 0://来自版块
                $forum_ids=ComForum::where('id|pid','in',$channel_info['from_ids'])->where('status',1)->column('id');
                if(count($forum_ids)){
                    $private_forum_ids = ForumPower::get_private_id();
                    $forum_ids=array_diff($forum_ids,$private_forum_ids);
                    if(count($forum_ids)){
                        $model->whereIn('fid',$forum_ids);
                    }else{
                        $model->where('fid',-1);
                        //构造一个无法满足的条件，让查询结果为空
                    }
                }else{
                    $model->where('fid',-1);
                    //构造一个无法满足的条件，让查询结果为空
                }
                break;
            case 1://来自用户
                $model->whereIn('author_uid',$channel_info['from_ids']);
                $private_forum_ids = ForumPower::get_private_id();
                if(count($private_forum_ids)){
                    $model->where('fid','not in',$private_forum_ids);
                }
                break;
            case 2://来自全站
            default:
                $private_forum_ids = ForumPower::get_private_id();
                if(count($private_forum_ids)){
                    $model->where('fid','not in',$private_forum_ids);
                }
        }

        //热度条件
        if($channel_info['condition_post_hot_type']==1){//同时满足3项
            $model->where('reply_count','>=',$channel_info['condition_post_hot_comment']);
            $model->where('view_count','>=',$channel_info['condition_post_hot_read']);
            $model->where('support_count','>=',$channel_info['condition_post_hot_support']);
        }else{//满足任意一项
            $model->where(function($query) use($channel_info) {
                $query->whereOr('reply_count','>=',$channel_info['condition_post_hot_comment']);
                $query->whereOr('view_count','>=',$channel_info['condition_post_hot_read']);
                $query->whereOr('support_count','>=',$channel_info['condition_post_hot_support']);
            });
        }

        //帖子类型
        switch ($channel_info['condition_post_type']){
            case 0://全部
                break;
            case 1://只取精华
                $model->where('is_essence',1);
                break;
            case 2://只取置顶
                $model->where(function($query){
                    $now_time=time();
                    //置顶
                    $query->whereOrRaw('is_top=1 AND (top_end_time>'.$now_time.' OR top_end_time=0)');
                    //首页置顶
                    $query->whereOrRaw('index_top=1 AND (index_top_end_time>'.$now_time.' OR index_top_end_time=0)');
                    //详情页置顶
                    $query->whereOrRaw('detail_top=1 AND (detail_top_end_time>'.$now_time.' OR detail_top_end_time=0)');
                });
                break;
            default:
        }

        //帖子内容
        $condition_list=explode(',',$channel_info['condition_post_content']);
        $has_post=$has_weibo=$has_video=$has_news=0;
        $type_ids=[];
        if(in_array(1,$condition_list)){//帖子
            $has_post=1;
            $type_ids[]=1;//1.普通版面或动态
        }
        if(in_array(2,$condition_list)){//视频
            $has_video=1;
            $type_ids[]=6;//6.视频横版
        }
        if(in_array(3,$condition_list)){//资讯
            $has_news=1;
            $type_ids[]=4;//4.资讯
        }
        if(in_array(4,$condition_list)){//动态
            $has_weibo=1;
            $type_ids[]=1;//1.普通版面或动态
            $type_ids[]=2;
        }
        if($has_post&&$has_weibo){//帖子中（普通帖子和动态同时存在，不需要is_weibo判断）
            $model->where('type','in',$type_ids);
        }else if(!$has_post&&!$has_weibo){//帖子中（普通帖子和动态同时不存在，不需要is_weibo判断）
            $model->where('type','in',$type_ids);
        }else{//帖子中（普通帖子和动态存在一项，需要is_weibo判断）
            if($has_video||$has_news){//存在视频和资讯;同时普通帖子和动态二者有其中一项
                $model->where(function($query) use($has_weibo,$has_video,$has_news) {
                    if($has_weibo){//存在动态，不存在普通帖子
                        $query->whereOrRaw('type=1 AND is_weibo=1');
                    }else{//存在普通帖子，不存在动态
                        $query->whereOrRaw('type=1 AND is_weibo=0');
                    }
                    if($has_video){//有视频
                        $query->whereOr('type',6);
                    }
                    if($has_news){//有资讯
                        $query->whereOr('type',4);
                    }
                });
            }else{//不存在视频和资讯，只有普通帖子、动态其中一项
                $model->where('type',1);
                if($has_weibo){
                    $model->where('is_weibo',1);
                }else{
                    $model->where('is_weibo',0);
                }
            }
        }

        //发布时间范围
        switch ($channel_info['condition_post_send_time']){
            //1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天
            case 1:
                break;
            case 2:
                $model->where('create_time','gt',time()-24*60*60);
                break;
            case 3:
                $model->where('create_time','gt',time()-3*24*60*60);
                break;
            case 4:
                $model->where('create_time','gt',time()-7*24*60*60);
                break;
            case 5:
                $model->where('create_time','gt',time()-30*24*60*60);
                break;
            case 6:
                $model->where('create_time','gt',time()-180*24*60*60);
                break;
            default:
        }

        //最后回复时间范围
        switch ($channel_info['condition_post_comment_time']){
            //1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天
            case 1:
                break;
            case 2:
                $model->where('last_post_time','gt',time()-24*60*60);
                break;
            case 3:
                $model->where('last_post_time','gt',time()-3*24*60*60);
                break;
            case 4:
                $model->where('last_post_time','gt',time()-7*24*60*60);
                break;
            case 5:
                $model->where('last_post_time','gt',time()-30*24*60*60);
                break;
            case 6:
                $model->where('last_post_time','gt',time()-180*24*60*60);
                break;
            default:
        }

        //最后修改时间范围
        switch ($channel_info['condition_post_update_time']){
            //1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天
            case 1:
                break;
            case 2:
                $model->where('update_time','gt',time()-24*60*60);
                break;
            case 3:
                $model->where('update_time','gt',time()-3*24*60*60);
                break;
            case 4:
                $model->where('update_time','gt',time()-7*24*60*60);
                break;
            case 5:
                $model->where('update_time','gt',time()-30*24*60*60);
                break;
            case 6:
                $model->where('update_time','gt',time()-180*24*60*60);
                break;
            default:
        }

        //排除已屏蔽帖子
        $hide_ids=db('channel_post_hide')->where('channel_id',$channel_info['id'])->column('post_id');
        if(count($hide_ids)){
            $model->where('id','not in',$hide_ids);
        }

        return $model;
    }

    /**
     * -频道帖子标题、评论数、点赞数等信息同步 每小时执行一次
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function updateChannelPostInfo()
    {
        $page=1;
        $limit=2000;
        $fields='id,title,image,author_uid,support_count,reply_count,collect_count,create_time,last_post_time,update_time,is_weibo,content';
        do{
            //可更新帖子信息分页获取
            $channel_post_ids=ChannelPost::page($page,$limit)->order('post_id desc')->column('post_id');
            if(count($channel_post_ids)){
                $post_list=ComThread::where('id','in',$channel_post_ids)->field($fields)->select();
                if(count($post_list)){
                    $post_list=$post_list->toArray();
                    $uids=array_column($post_list,'author_uid');
                    $author_info_list=User::where('uid','in',$uids)->field('uid,nickname,phone')->select();
                    if(count($author_info_list)){
                        $author_info_list=$author_info_list->toArray();
                        $author_info_list=array_combine(array_column($author_info_list,'uid'),$author_info_list);
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }else{
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }
                    $sql_in_post_title=
                    $sql_in_post_author=
                    $sql_in_post_support_count=
                    $sql_in_post_comment_count=
                    $sql_in_post_collect_count=
                    $sql_in_post_comment_time=
                    $sql_in_post_update_time='';
                    $post_ids=implode(',',array_column($post_list,'id'));
                    foreach ($post_list as $val){
                        if(!isset($author_info_list[$val['author_uid']])){
                            $author_info_list[$val['author_uid']]=[
                                'nickname'=>'',
                                'phone'=>''
                            ];
                        }
                        $post_author='【'.$val['author_uid'].'】'.$author_info_list[$val['author_uid']]['nickname'].'('.$author_info_list[$val['author_uid']]['phone'].')';
                        if($val['is_weibo']==1){
                            $title=text(json_decode($val['content'],true));
                            $title=str_replace('\'','',$title);
                            $sql_in_post_title.=" WHEN {$val['id']} THEN '{$title}' ";
                        }else{
                            $title=str_replace('\'','',$val['title']);
                            $sql_in_post_title.=" WHEN {$val['id']} THEN '{$title}' ";
                        }
                        $sql_in_post_author.=" WHEN {$val['id']} THEN '{$post_author}' ";
                        $sql_in_post_support_count.=" WHEN {$val['id']} THEN {$val['support_count']} ";
                        $sql_in_post_comment_count.=" WHEN {$val['id']} THEN {$val['reply_count']} ";
                        $sql_in_post_collect_count.=" WHEN {$val['id']} THEN {$val['collect_count']} ";
                        $sql_in_post_comment_time.=" WHEN {$val['id']} THEN {$val['last_post_time']} ";
                        $sql_in_post_update_time.=" WHEN {$val['id']} THEN {$val['update_time']} ";

                    }
                    unset($val);
                    $sql=" UPDATE ".config('database.prefix')."channel_post 
                    SET post_title= CASE post_id 
                    {$sql_in_post_title}
                    END ,
                    post_author= CASE post_id 
                    {$sql_in_post_author}
                    END ,
                    post_support_count= CASE post_id 
                    {$sql_in_post_support_count}
                    END ,
                    post_comment_count= CASE post_id 
                    {$sql_in_post_comment_count}
                    END ,
                    post_collect_count= CASE post_id 
                    {$sql_in_post_collect_count}
                    END ,
                    post_comment_time= CASE post_id 
                    {$sql_in_post_comment_time}
                    END ,
                    post_update_time= CASE post_id 
                    {$sql_in_post_update_time}
                    END 
                    WHERE post_id in({$post_ids})";
                    $res=self::execute($sql);
                }
            }
            $page++;
        }while(count($channel_post_ids)==$limit);

        Cache::clear('channel_list_change');
        return true;
    }

    /**
     * -频道备选池帖子标题、评论数、点赞数等信息同步 每小时执行一次
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function updateChannelPostPoolInfo()
    {
        $page=1;
        $limit=2000;
        $fields='id,title,image,author_uid,support_count,reply_count,collect_count,create_time,last_post_time,update_time,is_weibo,content';
        do{
            //可更新帖子信息分页获取
            $channel_post_ids=ChannelPostPool::page($page,$limit)->order('post_id asc')->column('post_id');
            if(count($channel_post_ids)){
                $post_list=ComThread::where('id','in',$channel_post_ids)->field($fields)->select();
                if(count($post_list)){
                    $post_list=$post_list->toArray();
                    $uids=array_column($post_list,'author_uid');
                    $author_info_list=User::where('uid','in',$uids)->field('uid,nickname,phone')->select();
                    if(count($author_info_list)){
                        $author_info_list=$author_info_list->toArray();
                        $author_info_list=array_combine(array_column($author_info_list,'uid'),$author_info_list);
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }else{
                        $author_info_list[0]=['uid'=>0,'nickname'=>'','phone'=>''];
                    }
                    $sql_in_post_title=
                    $sql_in_post_author='';
                    $post_ids=implode(',',array_column($post_list,'id'));
                    foreach ($post_list as $val){
                        if(!isset($author_info_list[$val['author_uid']])){
                            $author_info_list[$val['author_uid']]=[
                                'nickname'=>'',
                                'phone'=>''
                            ];
                        }
                        $post_author='【'.$val['author_uid'].'】'.$author_info_list[$val['author_uid']]['nickname'].'('.$author_info_list[$val['author_uid']]['phone'].')';
                        if($val['is_weibo']==1){
                            $title=text(json_decode($val['content'],true));
                            $title=str_replace('\'','',$title);
                            $sql_in_post_title.=" WHEN {$val['id']} THEN '{$title}' ";
                        }else{
                            $title=str_replace('\'','',$val['title']);
                            $sql_in_post_title.=" WHEN {$val['id']} THEN '{$title}' ";
                        }
                        $sql_in_post_author.=" WHEN {$val['id']} THEN '{$post_author}' ";
                    }
                    unset($val);
                    $sql=" UPDATE ".config('database.prefix')."channel_post_pool 
                    SET post_title= CASE post_id 
                    {$sql_in_post_title}
                    END ,
                    post_author= CASE post_id 
                    {$sql_in_post_author}
                    END 
                    WHERE post_id in({$post_ids})";
                    $res=self::execute($sql);
                }
            }
            $page++;
        }while(count($channel_post_ids)==$limit);
        Cache::clear('channel_list_change');
        return true;
    }

    /**
     * -获取频道帖子
     * @param $channel_info
     * @param $page
     * @param $row
     * @param $access
     * @param $video_is_on
     * @return array
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function getPostList($channel_info,$page,$row,$access,$video_is_on)
    {
        //获取分页帖子id
        switch ($channel_info['list_sort_type']){
            case 1://1. 按点赞数目倒序；
                $order='post_support_count desc';
                break;
            case 2://2. 按评论数目倒序；
                $order='post_comment_count desc';
                break;
            case 3://3. 按收藏数目倒序；
                $order='post_collect_count desc';
                break;
            case 4://4. 按发布时间倒序（默认）；
                $order='post_create_time desc';
                break;
            case 5://5. 按回复时间倒序；
                $order='post_comment_time desc';
                break;
            case 6://6. 按修改时间倒序
                $order='post_update_time desc';
                break;
            default://4. 按发布时间倒序（默认）；
                $order='post_create_time desc';
        }
        $order='is_top desc,sort_num desc,'.$order;
        $map=[
            'status'=>1,
            'is_hide'=>0,
            'channel_id'=>$channel_info['id'],
            'deadline'=>array('gt',time()),
        ];
        if($access[1] == '微信小程序' && $video_is_on==0){
            $map['type']=['neq',6];
        }
        if($channel_info['id']==2){
            //推荐频道数据列表获取，排除掉首页置顶的数据
            // $map_channel=[
            //     'status'=>1,
            //     'index_top'=>1,
            //     'fid'=>['not in',ForumPower::get_private_id()],
            // ];
            // $index_top_ids=ComThread::where($map_channel)->where('index_top_end_time','>',time())->column('id');
            // if(count($index_top_ids)){
            //     $map['post_id']=['not in',$index_top_ids];
            // }
        }
        $thread_ids_post=ChannelPost::where($map)->page($page,$row)->order($order)->field('post_id,image_show_type')->select();

        $list=[];
        if(count($thread_ids_post)){
            $thread_ids_post=$thread_ids_post->toArray();
            $field = 'id,fid,type,false_view,is_announce,post_id,read_perm,author_uid,title,is_weibo,oid,detail_top,index_top,is_new,create_time,image,from,last_post_time,last_post_uid,update_time,view_count,reply_count,class_id,cover,status,sort,support_count,share_count,collect_count,high_light,is_essence,is_top,attachment_id,is_verify,stick_reply,summary,pos,position,product_id,is_massage,video_id,video_cover,video_url,audio_id,audio_url,audio_time,light_end_time,is_recommend,recommend_end_time,index_top_end_time,detail_top_end_time,top_end_time,send_time,column_id,content';
            $map_thread['id']=array('in',array_column($thread_ids_post,'post_id'));
            $map_thread['status']=1;
            $thread_list=ComThread::where($map_thread)->field($field)->select();
            if($thread_list){
                $thread_list=$thread_list->toArray();
                $thread_list=array_combine(array_column($thread_list,'id'),$thread_list);
                foreach ($thread_ids_post as $val){
                    if(!isset($thread_list[$val['post_id']])){
                        continue;
                    }
                    $one_data=$thread_list[$val['post_id']];
                    $one_data['content'] = json_decode($thread_list[$val['post_id']]['content']);
                    $one_data['image_show_type']=$val['image_show_type'];
                    $list[]=$one_data;
                }
                unset($val);
            }
        }
        if(count($list)){
            $list=ComThread::threadListHandle($list);
        }

        if(count($thread_ids_post)==$row){
            $has_more=1;
        }else{
            $has_more=0;
        }
        return array($list,$has_more);
    }


    /**
     * -增加浏览记录
     * @param $channel_id
     * @param $uid
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function views($channel_id,$uid)
    {
        if($channel_id!=2&&$channel_id<5){
            return false;
        }
        db('channel_count_view_log')->insert([
            'channel_id'=>$channel_id,
            'ip'=>get_client_ip(),
            'create_time'=>time(),
            'uid'=>$uid
        ]);
    }

    private static function _getAllChannelList()
    {
        $list=self::where('status',1)->where('type',2)->order('create_time asc')->field('id,title')->select();
        $recom_title=self::where('id',2)->value('title');
        $base_array=[['id'=>2,'title'=>$recom_title]];
        if($list){
            $list=$list->toArray();
            $list=array_merge($base_array,$list);
        }else{
            $list=$base_array;
        }
        return $list;
    }

    /**
     * -执行内容数统计
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function countContent()
    {
        //今日统计，记录为昨天凌晨的时间，标识统计的是昨天的数据
        $log_day_time=strtotime(date('Y-m-d',strtotime('-1 day')));
        $channel_list=self::_getAllChannelList();
        foreach ($channel_list as $val){
            $already_type=ChannelCountContent::where('channel_id',$val['id'])->where('create_time',$log_day_time)->column('post_type');
            if(!$already_type){
                $already_type=[];
            }
            if(!in_array(1,$already_type)){
                $count_1=ChannelPost::where('channel_id',$val['id'])
                    ->where('post_type',1)//自动推送
                    ->where('status',1)
                    ->where('is_hide',0)
                    ->where('deadline','>',time())//还在有效期内
                    ->count();
                ChannelCountContent::set([
                    'channel_id'=>$val['id'],
                    'post_num'=>$count_1,
                    'create_time'=>$log_day_time,
                    'post_type'=>1
                ]);
            }
            if(!in_array(2,$already_type)){
                $count_2=ChannelPost::where('channel_id',$val['id'])
                    ->where('post_type',2)//手动推送
                    ->where('status',1)
                    ->where('is_hide',0)
                    ->where('deadline','>',time())//还在有效期内
                    ->count();
                ChannelCountContent::set([
                    'channel_id'=>$val['id'],
                    'post_num'=>$count_2,
                    'create_time'=>$log_day_time,
                    'post_type'=>2
                ]);
            }
        }
        unset($val,$already_type,$channel_list);
        return true;
    }


    /**
     * -执行浏览量统计
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function countView()
    {
        $channel_list=self::_getAllChannelList();

        //日统计
        $count_day_start_time=strtotime(date('Y-m-d',strtotime('-1 day')));//今日统计，记录为昨天凌晨的时间，标识统计的是昨天的数据
        $count_day_end_time=strtotime(date('Y-m-d'))-1;
        $already_day_channel=ChannelCountView::where('create_time',$count_day_start_time)->where('type',1)->column('channel_id');
        //日统计 end

        //周统计
        $count_week_start_time = strtotime(date('Y-m-d',strtotime('-1 week Monday')));
        $count_week_end_time = $count_week_start_time+7*24*60*60-1;
        $already_week_channel=ChannelCountView::where('create_time',$count_week_start_time)->where('type',2)->column('channel_id');
        //周统计end

        //月统计
        $count_month_start_time = strtotime(date('Y-m-01',strtotime('-1 month')));
        $count_month_end_time = strtotime(date('Y-m-01'))-1;
        $already_month_channel=ChannelCountView::where('create_time',$count_month_start_time)->where('type',3)->column('channel_id');
        //月统计end

        //年统计
        $count_year_start_time = strtotime(date('Y-01-01',strtotime('-1 year')));
        $count_year_end_time = strtotime(date('Y-01-01'))-1;
        $already_year_channel=ChannelCountView::where('create_time',$count_year_start_time)->where('type',4)->column('channel_id');
        //年统计end
        foreach ($channel_list as $val){
            //日统计
            if(!in_array($val['id'],$already_day_channel)){
                $count_day=ChannelCountViewLog::where('channel_id',$val['id'])
                    ->where('create_time','between',[$count_day_start_time,$count_day_end_time])
                    //昨日开始到结束时间之间的记录
                    ->count();
                ChannelCountView::set([
                    'channel_id'=>$val['id'],
                    'view_num'=>$count_day,
                    'create_time'=>$count_day_start_time,
                    'type'=>1
                ]);
            }


            //周统计
            if(!in_array($val['id'],$already_week_channel)){
                $count_week=ChannelCountViewLog::where('channel_id',$val['id'])
                    ->where('create_time','between',[$count_week_start_time,$count_week_end_time])
                    //上周开始到结束时间之间的记录
                    ->count();
                ChannelCountView::set([
                    'channel_id'=>$val['id'],
                    'view_num'=>$count_week,
                    'create_time'=>$count_week_start_time,
                    'type'=>2
                ]);
            }

            //月统计
            if(!in_array($val['id'],$already_month_channel)){
                $count_month=ChannelCountViewLog::where('channel_id',$val['id'])
                    ->where('create_time','between',[$count_month_start_time,$count_month_end_time])
                    //上月开始到结束时间之间的记录
                    ->count();
                ChannelCountView::set([
                    'channel_id'=>$val['id'],
                    'view_num'=>$count_month,
                    'create_time'=>$count_month_start_time,
                    'type'=>3
                ]);
            }
            //年统计
            if(!in_array($val['id'],$already_year_channel)){
                $count_year=ChannelCountViewLog::where('channel_id',$val['id'])
                    ->where('create_time','between',[$count_year_start_time,$count_year_end_time])
                    //上月开始到结束时间之间的记录
                    ->count();
                ChannelCountView::set([
                    'channel_id'=>$val['id'],
                    'view_num'=>$count_year,
                    'create_time'=>$count_year_start_time,
                    'type'=>4
                ]);
            }

        }
        unset($val,$already_day_channel,$already_week_channel,$already_month_channel,$already_year_channel);
        return true;
    }

    /**
     * -执行开启率统计
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function countOpenRate()
    {
        $channel_list=self::_getAllChannelList();

        //日统计
        $log_time=strtotime(date('Y-m-d',strtotime('-1 day')));//今日统计，记录为昨天凌晨的时间，标识统计的是昨天的数据
        $already_channel=ChannelCountView::where('create_time',$log_time)->column('channel_id');
        //日统计 end

        $total_user=db('user')->where('status',1)->count();
        foreach ($channel_list as $val){
            if($val['id']==2){
                continue;
            }

            if(!in_array($val['id'],$already_channel)){
                $today_open_num=db('channel_user')->where('channel_id',$val['id'])->where('status',1)->count();
                $today_open_rate=$today_open_num*100/$total_user;
                ChannelCountView::set([
                    'channel_id'=>$val['id'],
                    'rate'=>$today_open_rate,
                    'create_time'=>$log_time,
                    'type'=>1
                ]);
            }
        }
        unset($val);
        return true;
    }

    public static function delPost($post_id)
    {
        $res=db('channel_post')->where('post_id',$post_id)->delete();
        db('channel_post_pool')->where('post_id',$post_id)->delete();
        if($res){
            Cache::clear('channel_list_change');
        }
    }

    
}