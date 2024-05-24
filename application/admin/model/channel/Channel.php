<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/6/23
 * Time: 17:16
 */

namespace app\admin\model\channel;


use app\admin\model\com\ComThread;
use app\admin\model\user\User;
use basic\ModelBasic;
use think\Cache;
use traits\ModelTrait;

class Channel extends ModelBasic
{
    use ModelTrait;

    /**
     * 分页获取频道列表
     * @param $map
     * @param int $page
     * @param int $r
     * @param string $order
     * @return array
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public static function getChannelListPage($map,$page=1,$r=20,$order='id asc')
    {
        $data=($data=self::where($map)->order($order)->page($page,$r)->select()) && count($data) ? $data->toArray() :[];
        $count=self::where($map)->count();

        return compact('count','data');
    }


    public static function dealPostLongToTitle($long_id)
    {
        switch ($long_id){
            case 1:
                $title='无限制';
                break;
            case 2:
                $title='24小时';
                break;
            case 3:
                $title='3天';
                break;
            case 4:
                $title='7天';
                break;
            case 5:
                $title='30天';
                break;
            case 6:
                $title='180天';
                break;
            default:
                $title='无限制';
        }
        return $title;
    }

    public static function dealPostLongToTime($long_id)
    {
        switch ($long_id){
            case 1:
                $end_time=2145888000;//'无限制'  2038年1月1日
                break;
            case 2:
                $end_time=time()+24*60*60;//'24小时'
                break;
            case 3:
                $end_time=time()+3*24*60*60;//'3天';
                break;
            case 4:
                $end_time=time()+7*24*60*60;//'7天';
                break;
            case 5:
                $end_time=time()+30*24*60*60;//'30天';
                break;
            case 6:
                $end_time=time()+180*24*60*60;//'180天';
                break;
            default:
                $end_time=2145888000;//'无限制'
        }
        return $end_time;
    }


    public static function getAllChannelList()
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
     * -发帖时同步到频道
     * @param $post_id
     * @param $channel_ids
     * @param $adminId
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function recommendToChannel($post_id,$channel_ids,$adminId)
    {
        $thread_info=ComThread::find($post_id);
        $author_info=User::where('uid',$thread_info['author_uid'])->field('nickname,phone')->find();
        $deadline=self::dealPostLongToTime(1);
        $images=ChannelPost::getPostImages($thread_info['image']);
        if(count($images)){
            if($thread_info['type']==4){
                //资讯固定为单图
                $image_show_type=1;
            }elseif(count($images)>3){
                $image_show_type=3;
            }else{
                $image_show_type=count($images);
            }
        }else{
            $image_show_type=4;//无图
        }
        $new_default_data=[
            'post_id'=>$post_id,
            'post_title'=>$thread_info['title'],
            'post_author'=>'【'.$thread_info['author_uid'].'】'.$author_info['nickname'].'('.$author_info['phone'].')',
            'post_support_count'=>$thread_info['support_count'],
            'post_comment_count'=>$thread_info['reply_count'],
            'post_collect_count'=>$thread_info['collect_count'],
            'post_create_time'=>(isset($thread_info['send_time'])&&$thread_info['send_time']>0)?$thread_info['send_time']:$thread_info['create_time'],
            'post_comment_time'=>$thread_info['last_post_time'],
            'post_update_time'=>$thread_info['update_time'],
            'recommend_uid'=>$adminId,
            'status'=>1,
            'is_hide'=>0,
            'post_type'=>2,
            'type'=>$thread_info['type'],
            'post_long'=>1,
            'deadline'=>$deadline,
            'sort_num'=>0,
            'image_show_type'=>$image_show_type,
            'is_top'=>0,
            'create_time'=>time(),
            'update_time'=>time(),
        ];
        if($thread_info['is_weibo']==1){
            $data['post_title']=text($thread_info['content']);
        }
        $channel_ids=explode(',',$channel_ids);
        foreach ($channel_ids as $val){
            if(!(ChannelPost::where('post_id',$post_id)->where('channel_id',$val)->where('status','gt',0)->count())){
                //不存在则添加
                $save_data=$new_default_data;
                $save_data['channel_id']=$val;
                ChannelPost::set($save_data);
                //清除信息流缓存
                Cache::clear('channel_list_change');
            }
        }
        unset($val);
    }

    /**
     * 单个频道更新
     * @param $id
     * @return bool
     */
    public static function follow_channel_one($id){
        self::beginTrans();
        //删除所有的id位置的内容
        $res1=db('channel_user')->where(['status'=>1,'channel_id'=>$id])->group('uid')->delete();
        if($res1===false){
            self::rollbackTrans();
            return false;
        }
        //所有存在自定义设置的uid
        $uids=db('channel_user')->where('status',1)->group('uid')->select();
        if(empty($uids)){
            self::rollbackTrans();
            return true;
        }
        //存在固定的数量
        $flxed_channel=db('channel')->where(['fixed'=>1])->column('id');
        $flxed_count=count($flxed_channel);

        //非固定数量往后移动
        $res2=db('channel_user')->where(['status'=>1,'channel_id'=>['not in',$flxed_channel]])->setInc('sort_num',1);
        if($res2===false){
            self::rollbackTrans();
            return false;
        }
        //插入同步的内容
        $value=[
            'channel_id'=>$id,
            'sort_num'=>$flxed_count+1,
            'create_time'=>time(),
            'status'=>1
        ];
        $data=[];
        foreach ($uids as $v){
            $value['uid']=$v['uid'];
            $data[]=$value;
        }
        unset($v);
        $res3=db('channel_user')->insertAll($data);
        if($res3!==false){
            self::commitTrans();
            return true;
        }else{
            self::rollbackTrans();
            return false;
        }
    }
}