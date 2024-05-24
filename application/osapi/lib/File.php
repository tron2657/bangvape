<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/5/28
 * Time: 16:21
 */

namespace app\osapi\lib;


use app\admin\model\system\SystemConfig;
use app\core\util\TencentCosService;
use app\osapi\model\file\Picture;
use app\osapi\model\user\UserModel;
use app\wechat\sdk\WechatAuth;
use Complex\Exception;
use think\Config;


class File
{

    protected static $error_info;

    /*
     * 当前先兼容原版的，后续再进行改进、升级
     * 2020-02-07增加腾讯云对象存储COS方案
     *  todo 优化上传方案
     *  todo 兼容相关配置项
     *  todo 兼容七牛云
     */
    /**
     * 单图片上传
     * @param $files 表单上传文件，在各个接口中获取传输。获取方式：request()->file('file')；其中‘file’可变，和前端对应
     * @return array
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    public static function uploadPicture($files)
    {
        // 获取表单上传文件
        //$files = request()->file('file');//改为在各个接口中获取
        //todo 多图支持，直接对file进行foreach就行；
        $file=$files;

        $tmp_info=$file->getInfo();
//        $tmp_info= self::check_need_rotate($tmp_info);
        $max_file_size=SystemConfig::getValue('picture_max');
        if($tmp_info['size']>intval($max_file_size)*1024*1024){
            self::_setError('上传失败，图片大小超出上限'.$max_file_size.'M');
            return false;
        }
        $isExist=Picture::checkExist($tmp_info['tmp_name']);
        if($isExist){
            $res=$isExist;
        }else{
            $upload_type=SystemConfig::getValue('picture_store_place');
            if(($file->getMime())=='image/webp'){
                $upload_type='local';//webp格式图片只支持本地上传
            }
            switch ($upload_type) {
                case 'Tencent_COS'://腾讯云COS
                    $picture_upload=Config::get('TENCENT_COS_PICTURE_UPLOAD');
                    if(!$file->check($picture_upload)){
                        $err=$file->getError();
                        self::_setError('图片上传失败：'.$err);
                        return false;
                    }

                    //调用腾讯云上传
                    $result=TencentCosService::tencentCOSUpload($tmp_info);
                    if($result['result']==true){
                        $res = Picture::uploadTencentCOS($result['info']);
                        if(!$res){
                            // 写入数据库失败
                            self::_setError('图片信息写入数据库失败');
                            return false;
                        }
                    }else{
                        self::_setError($result['info']);
                        return false;
                    }
                    break;
                case 'local':
                default:
                    $picture_upload=Config::get('PICTURE_UPLOAD');
                    $info = $file->validate($picture_upload)->rule($picture_upload['nameBuilder'])->move($picture_upload['rootPath']);
                    if ($info) {
                        // 成功上传后 获取上传信息
                        $res = Picture::upload($info);
                        if(!$res){
                            // 写入数据库失败
                            self::_setError('图片信息写入数据库失败');
                            return false;
                        }
                    } else {
                        // 上传失败获取错误信息
                        self::_setError($file->getError());
                        return false;
                    }
                    break;
            }
        }
        $path=$res['path'];
        $result = [
            'code' => 0,
            'msg' => '上传成功',
            'id' => $res['id'],
            'path' => $path,
        ];
        return $result;
    }

    public static function check_need_rotate($file)
    {

        $name=date('YmdHis').rand(1000,9999).'.jpg';
        $savePathFile = '/upload/temps/';
        $targetName = __DIR__.$savePathFile.$name;
        $res=self::isAnimatedGif($file['tmp_name'].'/'.$file['name']);
        if($res==1){
            return $file;
        }
        $image = imagecreatefromstring(file_get_contents($file['tmp_name']));
        try{
            $exif = exif_read_data($file['tmp_name']);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $image = imagerotate($image,90,0);
                        break;
                    case 3:
                        $image = imagerotate($image,180,0);
                        break;
                    case 6:
                        $image = imagerotate($image,-90,0);
                        break;
                }
                imagejpeg($image,$targetName);
                $file['name']=$name;
                $file['type']="image/jpeg";
                $file['tmp_name']=$savePathFile;
            }

        }catch(Exception $e){

         }
        return $file;
    }

    public static function  isAnimatedGif($filename) {
        $fp = fopen($filename, 'rb');
        $filecontent = fread($fp, filesize($filename));
        fclose($fp);
        return strpos($filecontent, chr(0x21) . chr(0xff) . chr(0x0b) . 'NETSCAPE2.0') === FALSE ? 0 : 1;
    }
    /**
     * 多图上传
     * @param $files 表单上传文件，在各个接口中获取传输。获取方式：request()->file('mulitfile')；其中‘mulitfile’可变,接口中确定，和前端对应
     * @return array
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    public static function uploadMulitPicture($files)
    {
        // 获取表单上传文件
        //$files = request()->file('mulitfile');//改为在各个接口中获取
        $success=[];

        foreach ($files as $k=>$file){
            $tmp_info=$file->getInfo();
            $isExist=Picture::checkExist($tmp_info['tmp_name']);
            if($isExist){
                $success[$k]=[
                    'num'=>$k,
                    'info'=>'已存在',
                    'id'=>$isExist['id'],
                    'path'=>$isExist['type']=='local'?get_domain() . $isExist['path']:$isExist['path']
                ];
            }else{
                $upload_type=SystemConfig::getValue('picture_store_place');
                switch ($upload_type){
                    case 'Tencent_COS'://腾讯云COS
                        $picture_upload=Config::get('TENCENT_COS_PICTURE_UPLOAD');
                        if(!$file->check($picture_upload)){
                            $err=$file->getError();
                            // 写入数据库失败
                            $success[$k]=[
                                'num'=>$k,
                                'info'=>'图片上传失败：'.$err
                            ];
                            continue;
                        }

                        //调用腾讯云上传
                        $result=TencentCosService::tencentCOSUpload($tmp_info);
                        if($result['result']==true){
                            $res = Picture::uploadTencentCOS($result['info']);
                            if(!$res){
                                // 写入数据库失败
                                $success[$k]=[
                                    'num'=>$k,
                                    'info'=>'图片信息写入数据库失败'
                                ];
                                continue;
                            }
                            $success[$k]=[
                                'num'=>$k,
                                'info'=>'上传成功',
                                'id'=>$res['id'],
                                'path'=>$res['path']
                            ];
                        }else{
                            // 写入数据库失败
                            $success[$k]=[
                                'num'=>$k,
                                'info'=>$result['info']
                            ];
                            continue;
                        }
                        break;
                    case 'local':
                    default:
                        $picture_upload=Config::get('PICTURE_UPLOAD');

                        $info = $file->validate($picture_upload)->rule($picture_upload['nameBuilder'])->move($picture_upload['rootPath']);
                        dump($info);exit;
                        if ($info) {
                            // 成功上传后 获取上传信息
                            $res = Picture::upload($info);
                            if(!$res){
                                // 写入数据库失败
                                $success[$k]=[
                                    'num'=>$k,
                                    'info'=>'图片信息写入数据库失败'
                                ];
                                continue;
                            }
                        } else {
                            // 上传失败获取错误信息
                            $success[$k]=[
                                'num'=>$k,
                                'info'=>$file->getError()
                            ];
                            continue;
                        }
                        $success[$k]=[
                            'num'=>$k,
                            'info'=>'上传成功',
                            'id'=>$res['id'],
                            'path'=>get_domain() . $res['path']
                        ];
                        break;
                }
            }
        }
        $result = [
            'code' => 0,
            'msg' => '批量上传执行完成，具体执行结果请查看result字段',
            'result' => $success
        ];
        return $result;
    }

    /**
     * 上传base64位图片-上传头像专用
     * @param $fileData
     * @return array
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    public static function uploadAvatar($fileData)
    {
        if ($fileData == '' || $fileData == 'undefined') {
            self::_setError('参数错误');
            return false;
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $fileData, $file_info)) {
            $base64_body = substr(strstr($fileData, ','), 1);
            empty($aExt) && $aExt = $file_info[2];
        } else {
            $base64_body = $fileData;
        }
        $picture_upload_base64=Config::get('PICTURE_UPLOAD_BASE64');
        if (!in_array($aExt, $picture_upload_base64['ext'])) {
            self::_setError('非法操作,上传照片格式不符');
            return false;
        }
        $hasPhp = base64_decode($base64_body);
        if (strpos($hasPhp, '<?php') !== false) {
            self::_setError('非法操作');
            return false;
        }
        $driver =SystemConfig::getValue('picture_store_place');

        switch ($driver){
            case 'local'://本地上传
                $uid=get_uid();
                $root_path=$picture_upload_base64['avatarPath'] . '/' .$uid ;
                $file_name=md5(microtime(true)) . '.' . $aExt;
                $path = $root_path. '/' . $file_name;
                if(!file_exists($root_path)){
                    mkdir($root_path, 0777, true);
                }
                $data = base64_decode($base64_body);
                $rs = file_put_contents($path, $data);
                if($rs){
                    // 成功上传后 获取上传信息
                    $save_path=$picture_upload_base64['db_avatarPath'] . '/' .$uid;
                    $result = [
                        'code' => 0,
                        'msg' => '上传成功',
                        'path' => get_domain().$save_path.'/'.$file_name,
                    ];
                }else{
                    // 上传失败获取错误信息
                    self::_setError('图片上传失败');
                    return false;
                }
                break;
            case 'Tencent_COS'://腾讯云COS
                $isExist=Picture::checkExist(null,$base64_body);
                if($isExist){
                    return $isExist;
                }

                $res=TencentCosService::tencentCOSUploadBase64($base64_body);
                if($res['result']==true){
                    $res_db = Picture::uploadBase64TencentCOS($res['info']);
                    if(!$res_db){
                        // 写入数据库失败
                        self::_setError('图片信息写入数据库失败');
                        return false;
                    }else{
                        $result = [
                            'code' => 0,
                            'msg' => '上传成功',
                            'path' => $res['info']['path'],
                        ];
                    }
                }else{
                    self::_setError($res['info']);
                    return false;
                }
            default:
        }
        return $result;

    }

    /**
     * 将图片上传
     * @param string $ids
     * @return array
     * @throws \Exception
     */
    public static function wechatJsSDKUpload($ids = '')
    {
        $id_arr = explode(',', $ids);
        if (empty($id_arr)) {
            return [];
        }
        $appId = SystemConfig::getValue('wechat_appid');
        $appSecret = SystemConfig::getValue('wechat_appsecret');
        $wechat = new WechatAuth($appId, $appSecret);
        $token = $wechat->getAccessToken();
        $data = [];
        foreach ($id_arr as $v) {
            $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token['access_token'].'&media_id='.$v;
            $result = self::doGet($url);
            if (empty($result) || is_array($json = json_decode($result, true))) {
                continue;
            }
            if (stripos($result, 'image/jpeg') > 0) {
                $type = 'image/jpeg';
                $filename = uniqid('tmp_').'.jpg';
                $ext = '.jpg';
            } elseif (stripos($result, 'image/gif') > 0) {
                $type = 'image/gif';
                $filename = uniqid('tmp_').'.gif';
                $ext = '.gif';
            } else {
                $type = 'image/png';
                $filename = uniqid('tmp_').'.png';
                $ext = '.png';
            }
            $filePath = ROOT.'/public/uploads/'.$filename;
            file_put_contents($filePath, $result);
            if (!file_exists($filePath)) continue;
            $files = new \think\File($filePath);
            $files->setUploadInfo(['name'=>$filename,'type'=>$type,'tmp_name'=>$filePath,'error'=>UPLOAD_ERR_OK,'size'=>filesize($filePath)]);
            $res = self::uploadPicture($files);
            if (is_array($res) && isset($res['path'])) {
                $data[] = [
                    'media_id' => $v,
                    'id' => intval($res['id']),
                    'path' => $res['path']
                ];
            } else {
                $picture_upload=Config::get('PICTURE_UPLOAD');
                $saveName = date('Ymd') . DS . md5(uniqid().mt_rand()) . $ext;
                $path = ROOT . $picture_upload['db_rootPath'] . DS . $saveName;
                $path_dir = dirname($path);
                if (!is_dir($path_dir)) {
                    if (!mkdir($path_dir, 0755, true)) {
                        continue;
                    }
                }
                if (!copy($filePath, $path) || !file_exists($path)) {
                    continue;
                }
                $files->setSaveName($saveName);
                $res = Picture::upload($files);
                if ($res && $res['path']) {
                    $data[] = [
                        'media_id' => $v,
                        'id' => intval($res['id']),
                        'path' => $res['path']
                    ];
                }
            }
            if (file_exists($filePath)) @unlink($filePath);
        }
        return $data;
    }

    /**
     * get请求
     * @param $url
     * @return bool|string
     */
    public static function doGet($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 6);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $content = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if (!$content || $error) {
            return '';
        }

        return $content;
    }

    public static function getError()
    {
        return self::$error_info;
    }

    private static function _setError($error='操作失败')
    {
        self::$error_info=$error;
        return true;
    }
}