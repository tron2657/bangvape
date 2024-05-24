<?php
/**
 *  聊天内容
 * Created by PhpStorm.
 * User: zxh
 * Date: 2020/4/29
 * Time: 14:26
 */


namespace app\commonapi\model\talk;

use service\JsonService;
use think\Cache;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Url;


class TalkContent extends ModelBasic
{
    /**
     * 添加聊天内容
     * @param $data
     * @return int|string
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public static function addData($data){
        $data['create_time']=time();
        $data['status']=1;
        return self::insertGetId($data);
    }

    /**
     * 获取会话记录
     * @param $talk_id
     * @param $uid
     * @param $page
     * @param $limit
     * @return array
     * @author zxh  zxh@ourstu.com
     *时间：2020.5.20
     */
    public static function get_talk_list($talk_id,$uid,$page,$limit){
        $order='create_time desc';
        $list=self::where(['talk_id'=>$talk_id])->order($order)->page($page,$limit)->select();
        foreach ($list as &$v){
            $data['seat']=$v['uid']==$uid?'right':'left';
        }
        unset($v);
        $count=self::where(['talk_id'=>$talk_id])->count();
        return ['list'=>$list,'count'=>$count];
    }


}