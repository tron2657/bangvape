<?php
namespace app\admin\controller\card;

use app\admin\controller\AuthController;
use service\FormBuilder as Form;
use service\CacheService;
use service\HookService;
use service\JsonService;
use service\SystemConfigService;
use service\UtilService as Util;
use service\JsonService as Json;

use traits\CurdControllerTrait;
use service\UploadService as Upload;
use think\Request;
use app\admin\model\card\Card as CardModel;
use app\admin\model\user\User;
use app\admin\model\card\CardStatus;
use app\admin\model\card\CardSend;
use think\Url;

use app\admin\model\system\SystemAttachment;

/**
 * 商品管理
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class Card extends AuthController
{
    public function index(){
        // $config = SystemConfigService::more(['pay_routine_appid','pay_routine_appsecret','pay_routine_mchid','pay_routine_key','pay_routine_client_cert','pay_routine_client_key']);
        // $this->assign([
        //     'year'=>getMonth('y'),
        //     'real_name'=>$this->request->get('real_name',''),
        //     'orderCount'=>CardOrderModel::orderCount(),
        // ]);
        $status=osx_input('status',0);
        $this->assign('year',getMonth('y'));
        $this->assign('status',$status);
        return $this->fetch();
    }
    
    public function card_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['comment',''],
            ['is_reply',''],
            ['data',''],
            'status',
        ]);
        return JsonService::successlayui(CardModel::CardList($where));
    }

    /**
     * 兑换记录
     */
    public function card_status($card_id){
        if(!$card_id) return $this->failed('数据不存在');
        $this->assign(CardStatus::systemPage($card_id));
        //  $order_id=db('store_order')->where(['id'=>$oid])->value('order_id');
        //  $this->assign('order_id',$order_id);
        return $this->fetch();
    }

    /**
     * 收送记录
     */
    public function card_send_history($card_id){
        if(!$card_id) return $this->failed('数据不存在');
        $this->assign(CardSend::systemPage($card_id));
        return $this->fetch();
    }

}
