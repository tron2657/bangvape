<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/6/25
 * Time: 14:11
 */

namespace app\admin\model\channel;


use app\admin\model\com\ComForum;
use app\admin\model\com\ComThread;
use app\admin\model\com\ComTopic;
use app\admin\model\user\User;
use basic\ModelBasic;
use Carbon\Carbon;
use traits\ModelTrait;

class ChannelPost extends ModelBasic
{
    use ModelTrait;

    public static function getPostListPage($map,$page=1,$r=20,$time_data=null,$order='id desc')
    {
        $model=self::getModelObject($time_data);
        $data=($data=$model->where($map)->order($order)->page($page,$r)->select()) && count($data) ? $data->toArray() :[];
        foreach ($data as &$val){
            //帖子详情处理
            $val['post_data']=self::dealPostData($val['post_id']);

            if($val['post_type']==2){
                $val['recommend_user_nickname']=User::where('uid',$val['recommend_uid'])->value('nickname');
            }else{
                $val['recommend_user_nickname']='自动推送，无推送人';
            }
            $val['create_time_show']=time_format($val['create_time']);
            $val['post_long_show']=Channel::dealPostLongToTitle($val['post_long']);
            if($val['deadline']!=0&&$val['deadline']!=2145888000){
                $val['deadline_show']=time_format($val['deadline']);
            }else{
                $val['deadline_show']='';
            }
            if($val['deadline']!=0&&$val['deadline']<time()){
                $val['already_end']=1;
            }else{
                $val['already_end']=0;
            }
        }
        unset($val);
        $count=$model->where($map)->count();
//dump($data);exit;
        return compact('count','data');
    }

    /**
     * 获取连表Model
     * @param $time_data
     * @return ChannelPost
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-6
     */
    public static function getModelObject($time_data)
    {
        $model = new self();
        if (!empty($time_data)) {
            // data 日期
            $model->where(function ($query) use ($time_data) {
                switch ($time_data) {
                    case 'yesterday':
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                        $query->whereTime('create_time', $time_data);
                        break;
                    case 'quarter':
                        $start = strtotime(Carbon::now()->startOfQuarter());
                        $end = strtotime(Carbon::now()->endOfQuarter());
                        $query->whereTime('create_time', 'between', [$start, $end]);
                        break;
                    case '':
                        ;
                        break;
                    default:
                        $between = explode(' - ', $time_data);
                        $query->whereTime('create_time', 'between', [$between[0], $between[1]]);
                        break;
                }
            });
        }
        return $model;
    }

    public static function dealPostData($post_id)
    {
        $poster=ComThread::field('id,fid,author_uid,create_time,summary,title,image,cover,oid,is_weibo,type')->find($post_id);
        if($poster==null)
        {
            $post_data=[
                'forum_title'=>$post_id.'数据已被删除',
                'topic_title'=>$post_id.'数据已被删除',];
            return  $post_data;
        }
          
        $post_data=$poster->toArray();
        $post_data['forum_title']=ComForum::where('id',$post_data['fid'])->value('name');
        $post_data['author_nickname']=User::where('uid',$post_data['author_uid'])->value('nickname');
        $post_data['create_time_show']=time_format($post_data['create_time']);
        $topic_id=intval($post_data['oid']);
        if($topic_id>0){
            $topic_title=ComTopic::where('id',$topic_id)->value('title');
            if($topic_title){
                $post_data['topic_title']=$topic_title;
                $post_data['has_topic']=1;
            }else{
                $post_data['has_topic']=0;
            }
        }else{
            $post_data['has_topic']=0;
        }

        if($post_data['cover']){
            $image=getThumbImage($post_data['cover'],200,200);
            $post_data['logo']=get_root_path($image['src']);
        }else{
            $post_data['logo']='';
        }
        if(!$post_data['logo']||$post_data['logo']==''){
            if($post_data['image']){
                $images=self::getPostImages($post_data['image']);
                if(isset($images[0])){
                    $image_one=getThumbImage($images[0],200,200);
                    $post_data['logo']=get_root_path($image_one['src']);
                }
            }
        }

        $post_data['show_weibo_edit']=0;
        $post_data['show_default_edit']=0;
        $post_data['show_news_edit']=0;
        $post_data['show_video_edit']=0;

        if($post_data['is_weibo']==1){
            $post_data['show_weibo_edit']=1;
        }else{
            switch ($post_data['type']){
                case 1:
                case 2:
                case 3:
                case 5:
                    $post_data['show_default_edit']=1;
                    break;
                case 4:
                    $post_data['show_news_edit']=1;
                    break;
                case 6:
                case 7:
                    $post_data['show_video_edit']=1;
                    break;
                default:
                    $post_data['show_default_edit']=1;
            }
        }

        return $post_data;
    }

    /**
     * -处理帖子图片字段，转化成图片数组
     * @param $image
     * @return array|mixed
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function getPostImages($image)
    {
        //内容图片处理
        $imgs = json_decode($image,true);


        if(is_array($imgs)){
            foreach($imgs as &$val){
                $val=str_replace('"','',$val);
                $val=get_root_path($val);
            }
            unset($val);
        }else{
            if($image!='null' && $image!='' && $image!='[]') {
                $image=str_replace('"','',$image);
                $imgs=get_root_path($image);
                $arr=array();
                $arr[]=$imgs;
                $imgs=$arr;
            }else{
                $imgs=[];
            }
        }
        //内容图片处理
        return $imgs;
    }
}