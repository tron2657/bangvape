<?php
/**
 *
 * @author: cyx<cyx@ourstu.com>
 * @day: 2019/4/12
 */

namespace app\admin\model\com;

use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\user\User as UserModel;
use app\admin\model\system\SystemAdmin;
use app\osapi\model\com\MessageRead;

/**
 * 版块 model
 * Class ComForum
 * @package app\admin\model\com
 */
class MessageNews extends ModelBasic
{
    use ModelTrait;

    public static function createMessageNews($data){
        $data['content']=html($data['content']);
        $thread_id=self::insertGetId($data);
        if($thread_id){
            return $thread_id;
        }else{
            return 0;
        }
    }

    public static function delete_ann($id){
        $thread_id=self::where('id',$id)->update(['status' => -1]);
        $tid=self::where('id',$id)->value('tid');
        ComThread::where('id',$tid)->update(['status'=>-1]);
        MessageRead::where('message_id',$id)->where('type',6)->delete();
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function getOne($id){
        $res=self::where('id',$id)->find()->toArray();
        return $res;
    }

    public static function close($id){
        $thread_id=self::where('id',$id)->update(['status' => 0]);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function open($id){
        $map['status']=1;
        $map['send_time']=time();
        $thread_id=self::where('id',$id)->update($map);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function withdraw($id){
        $map['status']=0;
        $thread_id=self::where('id',$id)->update($map);
        if($thread_id!==false){
            return 1;
        }else{
            return 0;
        }
    }


    /*
     * 获取营销消息列表
     * @param $where array
     * @return array
     *
     */
    public static function MessageNewsList($where)
    {
        $model = self::getModelObject($where)->field(['*']);
        $model = $model->order('id desc')->page((int)$where['page'], (int)$where['limit']);
        $data = ($data = $model->select()) && count($data) ? $data->toArray() : [];
        //普通列表
        foreach ($data as &$item){
            switch ($item['status']) {
                case 1:
                    if($item['send_time']>time()){
                        $item['status']='未发送';
                    }elseif($item['end_time']<time()){
                        $item['status']='已过期';
                    }else{
                        $item['status']='已发送';
                    }
                    break;
                case 0:
                    $item['status']='已撤回';
                    break;
                case -1:
                    $item['status']='已删除';
                    break;
            }
            $item['view']=db('com_thread')->where('id',$item['tid'])->value('view_count');
            $item['content'] =  mb_strcut(strip_tags(htmlspecialchars_decode(text($item['content']))),0,180,'utf-8');
            $item['send_time']=time_format($item['send_time']);
            $item['end_time']=time_format($item['end_time']);
            $item['create_time']=time_format($item['create_time']);
            $item['from_uid']='系统通知';
            $item['to_uid']='全体用户';
            $item['admin']=SystemAdmin::where('id',$item['admin_uid'])->value('real_name');
        }
        $count = self::getModelObject($where)->count();
        return compact('count', 'data');
    }

    /**
     * 获取连表MOdel
     * @param $model
     * @return object
     */
    public static function getModelObject($where = [])
    {
        $model = new self();
        if (!empty($where)) {
            if(isset($where['status']) && $where['status']!=''){
                if($where['status']==2){
                    $model->where('status',1);
                    $model->where('send_time','>',time());
                }elseif($where['status']==0){
                    $model->where('status',0);
                }elseif($where['status']==3){
                    $model->where('status',1);
                    $model->where('end_time','<',time());
                }else{
                    $model->where('status',1);
                    $model->where('send_time','<',time());
                    $model->where('end_time','>',time());
                }
            }else{
                $model->where('status','>',-1);
            }
            if(isset($where['title']) && $where['title'] != ''){
                $model->where('title','LIKE',"%{$where['title']}%");
            }
        }
        return $model;
    }

}