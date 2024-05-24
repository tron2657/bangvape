<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/1
 * Time: 13:45
 */

namespace app\osapi\controller;


use app\admin\model\channel\ChannelPost;
use app\admin\model\channel\ChannelPostHide;
use app\admin\model\com\ComSite;
use app\admin\model\system\SystemConfig;
use app\admin\model\user\User;
use app\osapi\model\channel\ChannelUser;
use app\osapi\model\channel\Channel as ChannelModel;
use app\osapi\model\com\ComForum;
use app\osapi\model\com\ComThread;
use service\JsonService;
use think\Cache;

class Channel extends Base
{

    /**
     * -频道配置获取
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function config()
    {
        $config=SystemConfig::getMore('channel_first_page_open,channel_first_page_can_jump,channel_edit_page_open,recommend_at');
        return JsonService::success($config);
    }

    /**
     * -选择你喜欢的频道页面-频道列表获取接口
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function recommend()
    {
        $channel_list=ChannelModel::getRecommend();
        return JsonService::success($channel_list);
    }

    /**
     * -设置我喜欢的频道（频道引导页和频道编辑页调用）
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function setMyChannel()
    {
        $uid=$this->_needLogin();

        //喜欢的频道id列表，支持顺序，如“6,3,4,5,1,2”
        $channel_ids=osx_input('post.channel_ids','','text');
        $type=osx_input('post.type','','text');
        //判断设置为首页的channel是不能删除的
        $ids=explode(',',$channel_ids);
        $index_id=db('channel')->where(['is_index'=>1])->field('id,title')->find();
        if(!in_array($index_id['id'],$ids)&&$type==''){
            return JsonService::fail('error','不能取关'.$index_id['title'].'频道');
        }
        $res=ChannelUser::resetMyChannel($uid,$channel_ids,$type);
        if($res){
            return JsonService::success('设置成功');
        }else{
            return JsonService::fail('操作失败');
        }
    }

    /**
     * -频道首页-展示频道列表接口
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function showChannelList()
    {
        $uid = get_uid();
        $channel_list=ChannelModel::getShowList($uid);
        return JsonService::success($channel_list);
    }

    /**
     * -频道编辑页-所有频道列表
     * @return array|mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function allChannelList()
    {
        $uid=$this->_needLogin();
        $channel_list=ChannelModel::allChannelList($uid);
        return JsonService::success($channel_list);
    }

    /**
     * -可推荐的频道列表
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function canRecommendList()
    {
        $uid=$this->_needLogin();
        $channel_list=ChannelModel::canRecommendList($uid);
        return JsonService::success($channel_list);
    }

    /**
     * -执行推送操作
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function doRecommend()
    {
        $uid=$this->_needLogin();
        $channel_id=osx_input('post.channel_id',0,'intval');
        $thread_id=osx_input('post.thread_id',0,'intval');
        if($channel_id==0||$thread_id==0){
            return JsonService::fail('非法操作');
        }
        $check_can_recommend=ChannelModel::checkCanRecommend($channel_id,$uid);
        if(!$check_can_recommend['can_recommend']){
            return JsonService::fail('该频道不能推荐');
        }
        //检测是否已存在
        if(ChannelPost::where('status','in',[1,2])->where('channel_id',$channel_id)->where('post_id',$thread_id)->count()){
            return JsonService::fail('该内容已在频道中');
        }
        if(ChannelPostHide::where('channel_id',$channel_id)->where('post_id',$thread_id)->count()){
            return JsonService::fail('该内容已被屏蔽');
        }
        $thread_info=ComThread::find($thread_id);
        $author_info=User::where('uid',$thread_info['author_uid'])->field('nickname,phone')->find();
        if($check_can_recommend['is_admin']||$check_can_recommend['need_audit']==0){
            if(!$check_can_recommend['is_admin']){
                if(!ComForum::isForumAdmin($thread_info['fid'],$uid)){
                    return JsonService::fail('您不是该频道管理员，无法推荐');
                }
            }
            //管理员投稿或非管理员无需审核投稿
            $data=[
                'channel_id'=>$channel_id,
                'post_id'=>$thread_id,
                'post_title'=>$thread_info['title'],
                'post_author'=>'【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')',
                'post_support_count'=>$thread_info['support_count'],
                'post_comment_count'=>$thread_info['reply_count'],
                'post_collect_count'=>$thread_info['collect_count'],
                'post_create_time'=>(isset($thread_info['send_time'])&&$thread_info['send_time']>0)?$thread_info['send_time']:$thread_info['create_time'],
                'post_comment_time'=>$thread_info['last_post_time'],
                'post_update_time'=>$thread_info['update_time'],
                'recommend_uid'=>$uid,
                'status'=>1,
                'post_type'=>2,
                'type'=>$thread_info['type'],
                'post_long'=>1,
                'deadline'=>\app\admin\model\channel\Channel::dealPostLongToTime(1),
                'create_time'=>time(),
                'update_time'=>time()
            ];
            if($thread_info['is_weibo']==1){
                $data['post_title']=text($thread_info['content']);
            }
            $res=ChannelPost::set($data);
            if($res){
                //清除信息流缓存
                Cache::clear('channel_list_change');
                return JsonService::success('操作成功');
            }else{
                return JsonService::fail('操作失败');
            }
        }else{
            if(!ComForum::isForumAdmin($thread_info['fid'],$uid)){
                return JsonService::fail('您不是该频道管理员，无法推荐');
            }

            //非管理员，投稿需要审核
            $data=[
                'channel_id'=>$channel_id,
                'post_id'=>$thread_id,
                'post_title'=>$thread_info['title'],
                'post_author'=>'【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')',
                'recommend_uid'=>$uid,
                'status'=>2,
                'post_type'=>2,
                'type'=>$thread_info['type'],
                'post_long'=>1,
                'create_time'=>time(),
                'update_time'=>time()
            ];
            if($thread_info['is_weibo']==1){
                $data['post_title']=text($thread_info['content']);
            }
            $res=ChannelPost::set($data);
            if($res){
                return JsonService::success('操作成功，请等待审核');
            }else{
                return JsonService::fail('操作失败');
            }
        }
    }

    public function getChannel()
    {
        $channel_id=input('channel_id',2,'intval');
        $model= ChannelModel::get($channel_id);
        $this->apiSuccess($model);
    }

    /**
     * -获取频道数据列表
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function getChannelDataList()
    {
        $channel_id=input('channel_id',2,'intval');
        $page = input('page',1,'intval');
        $uid=get_uid();
        $access=$this->access;
        $video_is_on=SystemConfig::getValue('xcx_video');

        $channel_info=ChannelModel::where('status',1)->find($channel_id);
        if(!$channel_info){
            return JsonService::fail('频道不存在或已禁用');
        }
        $row=$channel_info['list_page_limit'];
        //获取推荐信息列表
        if(isset($access[1])&&$access[1]=='微信小程序'){
            $tag='channel_post_list_'.$page.'_row_'.$row.'_channel_id_'.$channel_id.'_xcx';
        }else{
            $tag='channel_post_list_'.$page.'_row_'.$row.'_channel_id_'.$channel_id;
        }
        $postListCache=Cache::get($tag);
        if(!$postListCache){
            list($postList,$has_more) = ChannelModel::getPostList($channel_info,$page,$row,$access,$video_is_on);
            $postListCache = ['list'=>$postList,'has_more'=>$has_more,'recache_time'=>time(),'time_end'=>time()+10*60];
            Cache::tag('channel_list_change')->set($tag,$postListCache,10*60);
        }
        $postList=$postListCache['list'];
        $has_more=$postListCache['has_more'];
        if($postList!=false){
            if($uid){
                $selfHasChange=Cache::get('index_recommend_list_change_'.$uid);//有点赞、评论时该用户重新获取帖子的点赞评论数
                if($selfHasChange>$postListCache['recache_time']){
                    $postList = ComThread::reGetSupportNum($postList);
                    if($postListCache['time_end']>time()){//有效期还有一段时间
                        $has_time=intval($postListCache['time_end'])-time();
                        $postListCache['list']=$postList;
                        $postListCache['recache_time']=time();
                        Cache::tag('channel_list_change')->set($tag,$postListCache,$has_time);
                    }
                }
            }
            $postList=ComThread::initListUserRelation($postList,false);
        }
        //接口返回进行阅读量统计
        $list=[];
        if(!empty($postList)){
            foreach ($postList as $v){
                $list[]=$v;
            }
        }
        unset($v);
        ChannelModel::views($channel_id,$uid);
        ComSite::views($list);
        $data['row']=$row;
        $data['thread_list']=$postList;
        $data['has_more']=$has_more;

        //end
        $this->apiSuccess($data);

    }
}