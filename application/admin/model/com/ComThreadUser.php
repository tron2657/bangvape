<?php


namespace app\admin\model\com;

use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\com\ComForum as ForumModel;
/**
 * Class ComThreadClass
 * @package app\admin\model\store
 */
class ComThreadUser extends ModelBasic
{
    use ModelTrait;

    protected $table='osx_thread_user';


    /**
     * 绑定uid
     * @param $now_uid
     * @param $bind_uid
     * @return int
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public static function set_thread_user($now_uid,$bind_uid){
        $content=self::where(['id'=>1])->value('content');
        $data=json_decode($content,true);
        $data[$now_uid]=$bind_uid;
        $content=json_encode($data);
        return self::where(['id'=>1])->update(['content'=>$content]);
    }

    /**
     * 获取绑定信息
     * @param $now_uid
     * @return int
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.5
     */
    public static function get_thread_user($now_uid){
        $content=self::where(['id'=>1])->value('content');
        $data=json_decode($content,true);
        $bind_uid=1;
        if($data){
            if(array_key_exists($now_uid,$data)){
                $bind_uid=$data[$now_uid];
            }
        }
        return $bind_uid;
    }
}