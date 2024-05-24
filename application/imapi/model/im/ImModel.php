<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/5
 * Time: 10:23
 */
namespace app\imapi\model\im;
use app\admin\model\system\SystemConfig;
use app\osapi\model\user\UserModel;
use Doctrine\Common\Cache\Cache;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Db;

class ImModel extends ModelBasic
{
    public static function send_post($url, $post_data) {
        $postData = http_build_query($post_data); //做一层过滤
        $url.=$postData;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ;
    }

    /**
     * 注册im账号
     * @return bool
     */
    public static function get_read_count($uid,$code){
        $uid=$uid?$uid:get_uid();
        if(!$uid) return false;
        $url=SystemConfig::getValue('im_url');
        $url=$url.'public/api/login/get_read_count?';
        $data=[
            'osx_uid'=>$uid,
            'code'=>$code
        ];
        $result=self::send_post($url,$data);
        $result=json_decode($result,true);
        $result=$result['data'];
        if($result['status']==0){
          return 0;
        }
        $count=$result['count'];
        return $count;
    }

    /**
     * 更新用户数据
     * @param $uid
     * @param $code
     * @return bool|int|mixed
     */
    public static function update_user_info($uid,$code){
        $uid=$uid?$uid:get_uid();
        if(!$uid) return false;
        $user=db('user')->where(['uid'=>$uid])->field('uid,avatar,nickname,bind_im_uid')->find();
        if($user['bind_im_uid']==0){
            return false;
        }
        $url=SystemConfig::getValue('im_url');
        $url=$url.'public/api/user/update_user_info?';
        $data=[
            'osx_uid'=>$uid,
            'code'=>$code,
            'nickname'=>$user['nickname'],
            'avatar'=>$user['avatar'],
        ];
        $result=self::send_post($url,$data);
        $result=json_decode($result,true);
        $result=$result['data'];
        if($result['status']==0){
            return 0;
        }
        return $result;
    }
}