<?php
/**
 * 用户限制
 * Created by PhpStorm.
 * User: zxh
 * Date: 2020/3/31
 * Time: 9:20
 */
namespace app\commonapi\controller;

use app\osapi\controller\Base;
use Doctrine\Common\Cache\Cache;
use service\JsonService;
use app\commonapi\model\share\ShareLink as ShareLinkModel;
class ShareLink extends Base
{
    public  function get_share_link($id)
    {
        $item=ShareLinkModel::get($id);
        $vist_count=$item['vist_count']+1;
        ShareLinkModel::where('id',$id)->update(['vist_count'=>$vist_count]);
        return $this->apiSuccess(['jump_url'=>$item['jump_url']]);
        
    }

    
}