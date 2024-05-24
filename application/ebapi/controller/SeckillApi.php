<?php
namespace app\ebapi\controller;

use app\ebapi\model\store\StorePink;
use app\ebapi\model\store\StoreProductRelation;
use app\ebapi\model\store\StoreProductReply;
use app\ebapi\model\store\StoreSeckill;
use app\core\util\GroupDataService;
use service\JsonService;
use service\UtilService;


/**
 * 小程序秒杀api接口
 * Class SeckillApi
 * @package app\routine\controller
 *
 */
class SeckillApi extends AuthController
{

    /*
     * 白名单不验证token 如果传入token执行验证获取信息，没有获取到用户信息
     * */
    public static function whiteList()
    {
        return [
            'seckill_index',
            'seckill_list',
            'seckill_detail',
        ];
    }

    /**
     * 秒杀列表页
     * @return \think\response\Json
     */
    public function seckill_index(){
        $lovely = GroupDataService::getData('routine_lovely')?:[];//banner图
        list($time_last,$seckillTimeIndex)=StorePink::get_pink_time();
        $data['lovely'] = isset($lovely[0]) ? $lovely[0] : '';
        $data['seckillTime'] = $time_last;
        $data['seckillTimeIndex'] = $seckillTimeIndex;
        return JsonService::successful($data);
    }

    public function seckill_list(){
        $data = UtilService::postMore([['time',0],['offset',0],['limit',20]]);
//        if(!$data['time']) return JsonService::fail('参数错误');
//        $timeInfo = GroupDataService::getDataNumber($data['time']);
//        $activityEndHour = bcadd((int)$timeInfo['time'],(int)$timeInfo['continued'],0);
//        $startTime = bcadd(strtotime(date('Y-m-d')),bcmul($timeInfo['time'],3600,0));
//        $stopTime = bcadd(strtotime(date('Y-m-d')),bcmul($activityEndHour,3600,0));
        $seckillInfo = StoreSeckill::seckillList($data['time'],$data['offset'],$data['limit']);
        if(count($seckillInfo)){
            foreach ($seckillInfo as $key=>&$item){
                $percent = (int)bcmul(bcdiv($item['sales'],bcadd($item['stock'],$item['sales'],0),2),100,0);
                $item['percent'] = $percent ? $percent : 10;
            }
        }
        return JsonService::successful($seckillInfo);
    }
    /**
     * 秒杀详情页
     * @param Request $request
     * @return \think\response\Json
     */
    public function seckill_detail(){
        $data = UtilService::postMore(['id']);
        $id = $data['id'];
        if(!$id || !($storeInfo = StoreSeckill::getValidProduct($id))) return JsonService::fail('商品不存在或已下架!');
        $storeInfo['userLike'] = StoreProductRelation::isProductRelation($storeInfo['product_id'],$this->userInfo['uid'],'like','product_seckill');
        $storeInfo['like_num'] = StoreProductRelation::productRelationNum($storeInfo['product_id'],'like','product_seckill');
        $storeInfo['userCollect'] = StoreProductRelation::isProductRelation($storeInfo['product_id'],$this->userInfo['uid'],'collect','product_seckill');
        $storeInfo['uid'] = $this->userInfo['uid'];
        $data['storeInfo'] = $storeInfo;
        setView($this->userInfo['uid'],$id,$storeInfo['product_id'],'viwe');
        $data['reply'] = StoreProductReply::getRecProductReply($storeInfo['product_id']);
        $data['replyCount'] = StoreProductReply::productValidWhere()->where('product_id',$storeInfo['id'])->count();
        return JsonService::successful($data);
    }
}