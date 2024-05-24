<?php
 


namespace app\commonapi\model\share;

use app\osapi\model\user\UserModel;
use service\JsonService;
use think\Cache;
use traits\ModelTrait;
use basic\ModelBasic;
use think\Url;


class ShareLink extends ModelBasic
{
    /**
     * 自动生成分享链接
     *
     * @param [type] $uid
     * @param [type] $jump_url
     * @param [type] $type 3:活动分享
     * @return array
     */
    public static function gen_share_link($uid,$url,$type='')
    {
        $id=md5($url);
        $domain=get_domain();
        $share=self::where('id',$id)->find();
        if($share==null)
        {
            $share=[
                'id'=>$id,
                'uid'=>$uid,
                'create_time'=>time(),
                'jump_url'=>$url,
                'vist_count'=>0,
                'sharetype'=>$type
             ] ;

             self::insert($share);
             
        }
     
        return $domain.'/s?id='.$share['id'];
    }
}