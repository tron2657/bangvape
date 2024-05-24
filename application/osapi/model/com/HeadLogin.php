<?php
/**
 * 头条小程序登录
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/5
 * Time: 10:23
 */
namespace app\osapi\model\com;

use basic\ModelBasic;
use think\Cache;


class HeadLogin extends ModelBasic
{

    /**
     * 获取token
     * @return mixed
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.22
     */
    public static function get_access_token(){
        $access_token=Cache::get('head_login_access_token');
        if(!$access_token){
            $appid='tta1fdd7403ec75c5e';
            $secret='d02ca22777f4ee17bea0bf7a4dcbbc611501a186';
            $url='https://developer.toutiao.com/api/apps/token';
            $data['appid']=$appid;
            $data['secret']=$secret;
            $data['grant_type']='client_credential';
            $res=self::curl_get($url,$data);
            $res=self::object_to_array($res);
            $access_token=$res['access_token'];
            Cache::set('head_login_access_token',$access_token,$res['expires_in']);
        }
        return $access_token;
    }

    /**
     * 请求登录
     * @param string $code
     * @param string $anonymous_code
     * @return bool|mixed
     * @author zxh  zxh@ourstu.com
     *时间：2020.6.22
     */
    public static function get_code_session($code='',$anonymous_code=''){
        if(!$code&&!$anonymous_code) return false;
        $appid='tta1fdd7403ec75c5e';
        $secret='d02ca22777f4ee17bea0bf7a4dcbbc611501a186';
        $data['appid']=$appid;
        $data['secret']=$secret;
        $data['code']=$code;
        $data['anonymous_code']=$anonymous_code;
        $url='https://developer.toutiao.com/api/apps/jscode2session';
        $res=self::curl_get($url,$data);
        $res=json_decode($res);
        return $res;
    }

    public static function head_login($code='',$anonymous_code='')
    {
        if (!$code && !$anonymous_code) return false;

        $user = self::get_code_session($code, $anonymous_code);
        $user=self::object_to_array($user);
        return $user;
    }
    //get请求
    public static function curl_get($url, $data) {
        $postData = http_build_query($data); //做一层过滤
        $loginUrl=$url.'?'.$postData;
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$loginUrl);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $result=curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    public static function getUrl($url, $header = false) {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回数据不直接输出
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //指定gzip压缩
        //add header
        if(!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //add ssl support
        if(substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用
        }
        //add 302 support
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($ch,CURLOPT_COOKIEFILE, $this->lastCookieFile); //使用提交后得到的cookie数据
            $content = curl_exec($ch); //执行并存储结果
        curl_close($ch);
        dump($content);exit;
        return $content;
    }


    public static function object_to_array($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)self::object_to_array($v);
            }
        }

    return $obj;
    }
}