<?php
namespace  app\core\model\routine;

use app\admin\model\system\SystemConfig;
use think\Cache;
use think\Db;
class RoutineServer{
    /**
     * curl  get方式
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * curl post
     * @param string $url
     * @param string $postData
     * @param array $options
     * @return mixed
     */
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 微信公众号
     * @param string $routineAppId
     * @param string $routineAppSecret
     * @return mixed
     */
    public static function getAccessToken($routineAppId = '',$routineAppSecret = ''){
        $url  ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$routineAppId."&secret=".$routineAppSecret;
        return json_decode(self::curlGet($url),true);
    }

    /**
     * 获取access_token  数据库
     * @return mixed
     */
    public static function get_access_token($send = false){
        $routineAppId = SystemConfig::getValue('routine_appId');
        $routineAppSecret = SystemConfig::getValue('routine_appsecret');
        $accessToken=Cache::get('weixin_access_token_'.$routineAppId);
        if($send || !$accessToken){
            $accessToken =  Db::name('routine_access_token')->where('id',1)->find();
            if(!$send && $accessToken['stop_time'] > time()) {
                Cache::set('weixin_access_token_'.$routineAppId, $accessToken['access_token'],$accessToken['stop_time']-time());
                return $accessToken['access_token'];
            } else {
                $accessToken = self::getAccessToken($routineAppId, $routineAppSecret);
                if(isset($accessToken['access_token'])){
                    $data['access_token'] = $accessToken['access_token'];
                    $data['stop_time'] = time() + 3600;
                    Db::name('routine_access_token')->where('id',1)->update($data);
                    Cache::set('weixin_access_token_'.$routineAppId, $accessToken['access_token'], 3600);
                    return $accessToken['access_token'];
                    return $accessToken['access_token'];
                } else {
                    return '';
                }
            }
        }
        return $accessToken;
    }
}