<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/18
 */

namespace app\ebapi\model\store;

use app\admin\model\ump\StoreSeckill;
use app\core\model\routine\RoutineTemplate;//待完善
use app\ebapi\model\user\User;
use basic\ModelBasic;
use traits\ModelTrait;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;
use app\admin\model\system\SystemConfig;
use app\core\util\RoutineTemplateService;
use app\ebapi\model\user\WechatUser;

/**
 * 拼团Model
 * Class StorePink
 * @package app\wap\model\store
 */
class StorePink extends ModelBasic
{
    use ModelTrait;

    /*
     * 获取拼团完成的用户
     * @param int $uid 用户id
     * @return array
     * */
    public static function getPinkOkList($uid)
    {
        $list=self::where(['a.status'=>2,'a.is_refund'=>0])->where('a.uid','neq',$uid)->order('a.add_time desc')->alias('a')->join('__USER__ u','u.uid=a.uid')->column('u.nickname');
        foreach ($list as &$item){
            $item_start=mb_substr($item,0,1);
            $item_end=mb_substr($item,-1,1);
            $item=$item_start.'***'.$item_end;
            $item.=' 拼团成功';
        }
        return $list;
    }
    /*
     * 获取拼团完成的商品总件数
     * */
    public static function getPinkOkSumTotalNum($id)
    {

        return self::where('status',2)->where('is_refund',0)->sum('total_num');
    }
    /**
     * 获取一条拼团数据
     * @param $id
     * @return mixed
     */
    public static function getPinkUserOne($id){
        $model = new self();
        $model = $model->alias('p');
        $model = $model->field('p.*,u.nickname,u.avatar');
        $model = $model->where('id',$id);
        $model = $model->join('__USER__ u','u.uid = p.uid');
        $list = $model->find();
        if($list) return $list->toArray();
        else return [];
    }

    /**
     * 获取拼团的团员
     * @param $id
     * @return mixed
     */
    public static function getPinkMember($id){
        $model = new self();
        $model = $model->alias('p');
        $model = $model->field('p.*,u.nickname,u.avatar');
        $model = $model->where('k_id',$id);
        $model = $model->where('is_refund',0);
        $model = $model->join('__USER__ u','u.uid = p.uid');
        $model = $model->order('id asc');
        $list = $model->select();
        if($list) return $list->toArray();
        else return [];
    }

    /**
     * 设置结束时间
     * @param $idAll
     * @return $this
     */
    public static function setPinkStopTime($idAll){
        $model = new self();
        $model = $model->where('id','IN',$idAll);
        return $model->update(['stop_time'=>time(),'status'=>2]);
    }

    /**
     * 获取正在拼团的数据  团长
     * @param int $cid 产品id
     * @param int $isAll 是否查找所有拼团
     * @return array
     */
    public static function getPinkAll($cid,$isAll=false){
        $model = new self();
        $model = $model->alias('p');
        $model = $model->field('p.*,u.nickname,u.avatar');
        $model = $model->where('stop_time','GT',time());
        $model = $model->where('cid',$cid);
        $model = $model->where('k_id',0);
        $model = $model->where('is_refund',0);
        $model = $model->order('add_time desc');
        $model = $model->join('__USER__ u','u.uid = p.uid');
        $list = $model->select();
        $list=count($list) ? $list->toArray() : [];
        if($isAll){
            $pindAll = array();
            foreach ($list as &$v){
                $v['count'] = self::getPinkPeople($v['id'],$v['people']);
                $v['h'] = date('H',$v['stop_time']);
                $v['i'] = date('i',$v['stop_time']);
                $v['s'] = date('s',$v['stop_time']);
                $pindAll[] = $v['id'];//开团团长ID
            }
            return [$list,$pindAll];
        }
        return $list;
    }

    /**
     * 获取还差几人
     */
    public static function getPinkPeople($kid,$people){
        $model = new self();
        $model = $model->where('k_id',$kid)->where('is_refund',0);
        $count = bcadd($model->count(),1,0);
        return bcsub($people,$count,0);
    }

    /**
     * 判断订单是否在当前的拼团中
     * @param $orderId
     * @param $kid
     * @return bool
     */
    public static function getOrderIdAndPink($orderId,$kid){
        $model = new self();
        $pink = $model->where('k_id',$kid)->whereOr('id',$kid)->column('order_id');
        if(in_array($orderId,$pink))return true;
        else return false;
    }

    /**
     * 判断用户是否在团内
     * @param $id
     * @return int|string
     */
    public static function getIsPinkUid($id = 0,$uid = 0){
         $pinkT = self::where('id',$id)->where('uid',$uid)->where('is_refund',0)->count();
         $pink = self::whereOr('k_id',$id)->where('uid',$uid)->where('is_refund',0)->count();
         if($pinkT) return true;
         if($pink) return true;
         else return false;
    }


    /**
     * 判断是否发送模板消息 0 未发送 1已发送
     * @param $uidAll
     * @return int|string
     */
    public static function isTpl($uidAll,$pid){
        if(is_array($uidAll)){
            $countK = self::where('uid','IN',implode(',',$uidAll))->where('is_tpl',0)->where('id',$pid)->count();
            $count = self::where('uid','IN',implode(',',$uidAll))->where('is_tpl',0)->where('k_id',$pid)->count();
        }
        else {
            $countK = self::where('uid',$uidAll)->where('is_tpl',0)->where('id',$pid)->count();
            $count = self::where('uid',$uidAll)->where('is_tpl',0)->where('k_id',$pid)->count();
        }
        return bcadd($countK,$count,0);
    }
    /**
     * 拼团成功提示模板消息
     * @param $uidAll
     * @param $pid
     */
    public static function orderPinkAfter($uidAll,$pid){
//         foreach ($uidAll as $v){
//             $openid = WechatUser::uidToOpenid($v);
//             WechatTemplateService::sendTemplate($openid,WechatTemplateService::ORDER_USER_GROUPS_SUCCESS,[
//                 'first'=>'亲，您的拼团已经完成了',
//                 'keyword1'=> self::where('id',$pid)->whereOr('k_id',$pid)->where('uid',$v)->value('order_id'),
//                 'keyword2'=> self::alias('p')->where('p.id',$pid)->whereOr('p.k_id',$pid)->where('p.uid',$v)->join('__STORE_COMBINATION__ c','c.id=p.cid')->value('c.title'),
//                 'remark'=>'点击查看订单详情'
//             ],Url::build('My/order_pink_after',['id'=>$pid],true,true));
//         }
         self::beginTrans();
         $res1 = self::where('uid','IN',implode(',',$uidAll))->where('id',$pid)->whereOr('k_id',$pid)->update(['is_tpl'=>1]);
         $res2 = true;
//         if(SystemConfigService::get('colonel_status')) $res2 = self::setRakeBackColonel($pid);
//         else $res2 = true;
         $res = $res1 && $res2;
         self::checkTrans($res);
        $pink = self::where('uid','IN',implode(',',$uidAll))->where('id',$pid)->whereOr('k_id',$pid)->select();
        foreach($pink as &$value){
            $set=MessageTemplate::getMessageSet(18);
            $template=str_replace('{订单编号}', $value['order_id'], $set['template']);
            if($set['status']==1){
                $message_id=Message::sendMessage($value['uid'],0,$template,1,$set['title'],1,'','store_order',$value['order_id']);
                $read_id=MessageRead::createMessageRead($value['uid'],$message_id,$set['popup'],1);
            }
            if($set['sms']==1&&$set['status']==1){
                $account=User::where('uid',$value['uid'])->value('phone');
                $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
                $template='【'.$config['cl_sms_sign'].'】'.$template;
                $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
                $sms=json_decode($sms,true);
                if ($sms['code']==0) {
                    $read_data['is_sms']=1;
                    $read_data['sms_time']=time();
                    MessageRead::where('id',$read_id)->update($read_data);
                }
            }
            $oid=db('store_order')->where('order_id',$value['order_id'])->value('id');
            $goodsName = StoreOrderCartInfo::getProductNameList($oid);
            $goodsName=implode(',',$goodsName);
            $length=mb_strlen($goodsName,'UTF-8');
            if($length>15){
                $goodsName=mb_substr($goodsName,0,15,'UTF-8').'…';
            }
            RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($value['uid']),RoutineTemplateService::PINK_SUCCESS, [
                'phrase5'=>['value'=>'拼团成功'],
                'thing2'=>['value'=>$goodsName],
                'time6'=>['value'=>date("Y-m-d H:i:s",time())],
            ],'','/packageB/order/detail?id='.$value['order_id']);
        }

    }

    /**
     * 拼团失败发送的模板消息
     * @param $uid
     * @param $pid
     */
    public static function orderPinkAfterNo($uid,$pid,$formId='',$fillTilt='',$isRemove=false){
        $store=self::alias('p')->where('p.id|p.k_id',$pid)->field('c.*')->where('p.uid',$uid)->join('__STORE_COMBINATION__ c','c.id=p.cid')->find();
        $pink=self::where('id|k_id',$pid)->where('uid',$uid)->find();
        $set=MessageTemplate::getMessageSet(19);
        $template=str_replace('{订单编号}', $pink['order_id'], $set['template']);
        if($set['status']==1){
            $message_id=Message::sendMessage($pink['uid'],0,$template,1,$set['title'],1,'','store_order',$pink['order_id']);
            $read_id=MessageRead::createMessageRead($pink['uid'],$message_id,$set['popup'],1);
        }
        if($set['sms']==1&&$set['status']==1){
            $account=User::where('uid',$pink['uid'])->value('phone');
            $config = SystemConfig::getMore('cl_sms_sign,cl_sms_template');
            $template='【'.$config['cl_sms_sign'].'】'.$template;
            $sms=ChuanglanSmsApi::sendSMS($account,$template); //发送短信
            $sms=json_decode($sms,true);
            if ($sms['code']==0) {
                $read_data['is_sms']=1;
                $read_data['sms_time']=time();
                MessageRead::where('id',$read_id)->update($read_data);
            }
        }
        $oid=db('store_order')->where('order_id',$pink['order_id'])->value('id');
        $goodsName = StoreOrderCartInfo::getProductNameList($oid);
        $goodsName=implode(',',$goodsName);
        $length=mb_strlen($goodsName,'UTF-8');
        if($length>15){
            $goodsName=mb_substr($goodsName,0,15,'UTF-8').'…';
        }
        RoutineTemplateService::sendTemplate(WechatUser::getMiniOpenId($pink['uid']),RoutineTemplateService::PINK_ERROR, [
            'thing4'=>['value'=>'拼团失败'],
            'thing2'=>['value'=>$goodsName],
            'date5'=>['value'=>date('Y/m/d H:i',time())],
        ],'','/packageB/order/detail?id='.$pink['order_id']);
        if($isRemove){
            RoutineTemplate::sendOut('PINK_REMOVE',$uid,[
                'keyword1'=>$store->title,
                'keyword2'=>$pink->order_id,
                'keyword3'=>$pink->price,
            ],$formId,'/pages/order_details/index?order_id='.$pink->order_id);
        }else{
            RoutineTemplate::sendOut('PINK_Fill',$uid,[
                'keyword1'=>$store->title,
                'keyword2'=>$fillTilt,
                'keyword3'=>$pink->order_id,
                'keyword4'=>date('Y-m-d H:i:s',$pink->add_time),
                'keyword5'=>'申请退款金额：￥'.$pink->price,
            ],$formId,'/pages/order_details/index?order_id='.$pink->order_id);
        }
        self::where('id',$pid)->update(['status'=>3,'stop_time'=>time()]);
        self::where('k_id',$pid)->update(['status'=>3,'stop_time'=>time()]);
    }

    /**
     * 获取当前拼团数据返回订单编号
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getCurrentPink($id,$uid){
        $pink = self::where('id',$id)->where('uid',$uid)->find();
        if(!$pink) $pink = self::where('k_id',$id)->where('uid',$uid)->find();
        return StoreOrder::where('id',$pink['order_id_key'])->value('order_id');
    }

    public static function systemPage($where){
        $model = new self;
        $model = $model->alias('p');
        $model = $model->field('p.*,c.title');
        if($where['data'] !== ''){
            list($startTime,$endTime) = explode(' - ',$where['data']);
            $model = $model->where('p.add_time','>',strtotime($startTime));
            $model = $model->where('p.add_time','<',strtotime($endTime));
        }
        if($where['status']) $model = $model->where('p.status',$where['status']);
        $model = $model->where('p.k_id',0);
        $model = $model->order('p.id desc');
        $model = $model->join('StoreCombination c','c.id=p.cid');
        return self::page($model,function($item)use($where){
            $item['count_people'] = bcadd(self::where('k_id',$item['id'])->count(),1,0);
        },$where);
    }

    public static function isPinkBe($data,$id){
        $data['id'] = $id;
        $count = self::where($data)->count();
        if($count) return $count;
        $data['k_id'] = $id;
        $count = self::where($data)->count();
        if($count) return $count;
        else return 0;
    }
    public static function isPinkStatus($pinkId){
        if(!$pinkId) return false;
        $stopTime = self::where('id',$pinkId)->value('stop_time');
        if($stopTime < time()) return true; //拼团结束
        else return false;//拼团未结束
    }

    /**
     * 判断拼团结束 后的状态
     * @param $pinkId
     * @return bool
     */
    public static function isSetPinkOver($pinkId){
        $people = self::where('id',$pinkId)->value('people');
        $stopTime = self::where('id',$pinkId)->value('stop_time');
        if($stopTime < time()){
            $countNum = self::getPinkPeople($pinkId,$people);
            if($countNum) return false;//拼团失败
            else return true;//拼团成功
        }else return true;
    }

    /**
     * 拼团退款
     * @param $id
     * @return bool
     */
    public static function setRefundPink($oid){
        $res = true;
        $order = StoreOrder::where('id',$oid)->find();
        if($order['pink_id']) $id = $order['pink_id'];
        else return $res;
        $count = self::where('id',$id)->where('uid',$order['uid'])->find();//正在拼团 团长
        $countY = self::where('k_id',$id)->where('uid',$order['uid'])->find();//正在拼团 团员
        if(!$count && !$countY) return $res;
        if($count){//团长
            //判断团内是否还有其他人  如果有  团长为第二个进团的人
            $kCount = self::where('k_id',$id)->order('add_time asc')->find();
            if($kCount){
                $res11 = self::where('k_id',$id)->update(['k_id'=>$kCount['id']]);
                $res12 = self::where('id',$kCount['id'])->update(['stop_time'=>$count['add_time']+86400,'k_id'=>0]);
                $res1 = $res11 && $res12;
                $res2 = self::where('id',$id)->update(['stop_time'=>time()-1,'k_id'=>0,'is_refund'=>$kCount['id'],'status'=>3]);
            }else{
                $res1 = true;
                $res2 = self::where('id',$id)->update(['stop_time'=>time()-1,'k_id'=>0,'is_refund'=>$id,'status'=>3]);
            }
            //修改结束时间为前一秒  团长ID为0
            $res = $res1 && $res2;
        }else if($countY){//团员
            $res =  self::where('id',$countY['id'])->update(['stop_time'=>time()-1,'k_id'=>0,'is_refund'=>$id,'status'=>3]);
        }
        return $res;

    }



    /**
     * 拼团人数完成时，判断全部人都是未退款状态
     * @param $pinkIds
     * @return bool
     */
    public static function setPinkStatus($pinkIds){
        $orderPink = self::where('id','IN',$pinkIds)->where('is_refund',1)->count();
        if(!$orderPink) return true;
        else return false;
    }


    /**
     * 创建拼团
     * @param $order
     * @return mixed
     */
    public static function createPink($order){
        $order = StoreOrder::tidyOrder($order,true)->toArray();
        if($order['pink_id']){//拼团存在
            $res = false;
            $pink['uid'] = $order['uid'];//用户id
            if(self::isPinkBe($pink,$order['pink_id'])) return false;
            $pink['order_id'] = $order['order_id'];//订单id  生成
            $pink['order_id_key'] = $order['id'];//订单id  数据库id
            $pink['total_num'] = $order['total_num'];//购买个数
            $pink['total_price'] = $order['pay_price'];//总金额
            $pink['k_id'] = $order['pink_id'];//拼团id
            foreach ($order['cartInfo'] as $v){
                $pink['cid'] = $v['combination_id'];//拼团产品id
                $pink['pid'] = $v['product_id'];//产品id
                $pink['people'] = StoreCombination::where('id',$v['combination_id'])->value('people');//几人拼团
                $pink['price'] = $v['productInfo']['price'];//单价
                $pink['stop_time'] = 0;//结束时间
                $pink['add_time'] = time();//开团时间
                $res = self::set($pink);
            }
            RoutineTemplate::sendOut('PINK_TRUE',$order['uid'],[
                'keyword1'=>StoreCombination::where('id',$pink['cid'])->value('title'),
                'keyword2'=>User::where('uid',self::where('id',$pink['k_id'])->value('uid'))->value('nickname'),
                'keyword3'=>date('Y-m-d H:i:s',$pink['add_time']),
                'keyword3'=>$pink['total_price'],
            ],'','/pages/order_details/index?order_id='.$pink['order_id']);
            //处理拼团完成
            list($pinkAll,$pinkT,$count,$idAll,$uidAll)=self::getPinkMemberAndPinkK($pink);
            if($pinkT['status']==1){
                if(!$count)//组团完成
                    self::PinkComplete($uidAll,$idAll,$pink['uid'],$pinkT);
                else
                    self::PinkFail($pinkAll,$pinkT,0);
            }
            if($res) return true;
            else return false;
        }else{
            $res = false;
            $pink['uid'] = $order['uid'];//用户id
            $pink['order_id'] = $order['order_id'];//订单id  生成
            $pink['order_id_key'] = $order['id'];//订单id  数据库id
            $pink['total_num'] = $order['total_num'];//购买个数
            $pink['total_price'] = $order['pay_price'];//总金额
            $pink['k_id'] = 0;//拼团id
            foreach ($order['cartInfo'] as $v){
                $pink['cid'] = $v['combination_id'];//拼团产品id
                $pink['pid'] = $v['product_id'];//产品id
                $pink['people'] = StoreCombination::where('id',$v['combination_id'])->value('people');//几人拼团
                $pink['price'] = $v['productInfo']['price'];//单价
                $stopTime = StoreCombination::where('id',$v['combination_id'])->value('stop_time');//获取拼团产品结束的时间
                if($stopTime < time()+86400){
                    $pink['stop_time'] = $stopTime;//结束时间
                } else{
                    $pink['stop_time'] = time()+86400;//结束时间
                }
                $pink['add_time'] = time();//开团时间
                $res1 = self::set($pink,true);
                $res2 = StoreOrder::where('id',$order['id'])->update(['pink_id'=>$res1->id]);
                $res = $res1->result && $res2;
            }
            RoutineTemplate::sendOut('OPEN_PINK_SUCCESS',$order['uid'],[
                'keyword1'=>date('Y-m-d H:i:s',$pink['add_time']),
                'keyword2'=>date('Y-m-d H:i:s',$pink['stop_time']),
                'keyword3'=>StoreCombination::where('id',$pink['cid'])->value('title'),
                'keyword4'=>$pink['order_id'],
                'keyword4'=>$pink['total_price'],
            ],'','/pages/order_details/index?order_id='.$pink['order_id']);
            if($res) return true;
            else return false;
        }
    }
    /*
     * 获取一条今天正在拼团的人的头像和名称
     * */
    public static function getPinkSecondOne()
    {
        $addTime =  mt_rand(time()-30000,time());
         return self::where('p.add_time','GT',$addTime)->alias('p')->where('p.status',1)->join('User u','u.uid=p.uid')->field('u.nickname,u.avatar as src')->find();
    }
    /**
     * 拼团成功后给团长返佣金
     * @param int $id
     * @return bool
     */
//    public static function setRakeBackColonel($id = 0){
//        if(!$id) return false;
//        $pinkRakeBack = self::where('id',$id)->field('people,price,uid,id')->find()->toArray();
//        $countPrice = bcmul($pinkRakeBack['people'],$pinkRakeBack['price'],2);
//        if(bcsub((float)$countPrice,0,2) <= 0) return true;
//        $rakeBack = (SystemConfigService::get('rake_back_colonel') ?: 0)/100;
//        if($rakeBack <= 0) return true;
//        $rakeBackPrice = bcmul($countPrice,$rakeBack,2);
//        if($rakeBackPrice <= 0) return true;
//        $mark = '拼团成功,奖励佣金'.floatval($rakeBackPrice);
//        self::beginTrans();
//        $res1 = UserBill::income('获得拼团佣金',$pinkRakeBack['uid'],'now_money','colonel',$rakeBackPrice,$id,0,$mark);
//        $res2 = User::bcInc($pinkRakeBack['uid'],'now_money',$rakeBackPrice,'uid');
//        $res = $res1 && $res2;
//        self::checkTrans($res);
//        return $res;
//    }

    /*
    *  拼团完成更改数据写入内容
    * @param array $uidAll 当前拼团uid
    * @param array $idAll 当前拼团pink_id
    * @param array $pinkT 团长信息
    * @return int
    * */
    public static function PinkComplete($uidAll,$idAll,$uid,$pinkT)
    {
        $pinkBool=6;
        try{
            if(self::setPinkStatus($idAll)){
                self::setPinkStopTime($idAll);
                if(in_array($uid,$uidAll)){
                    if(self::isTpl($uidAll,$pinkT['id'])) self::orderPinkAfter($uidAll,$pinkT['id']);
                    $pinkBool = 1;
                }else  $pinkBool = 3;
            }
            return $pinkBool;
        }catch (\Exception $e){
            self::setErrorInfo($e->getMessage());
            return $pinkBool;
        }
    }

    /*
     * 拼团失败 退款
     * @param array $pinkAll 拼团数据,不包括团长
     * @param array $pinkT 团长数据
     * @param int $pinkBool
     * @param boolen $isRunErr 是否返回错误信息
     * @param boolen $isIds 是否返回记录所有拼团id
     * @return int| boolen
     * */
    public static function PinkFail($pinkAll,$pinkT,$pinkBool,$isRunErr=true,$isIds=false){
        self::startTrans();
        $pinkIds=[];
        try{
            if($pinkT['stop_time'] < time()){//拼团时间超时  退款
                //团员退款
                foreach ($pinkAll as $v){
                    if(StoreOrder::orderApplyRefund(StoreOrder::getPinkOrderId($v['order_id_key']),$v['uid'],'拼团时间超时',true) && self::isTpl($v['uid'],$pinkT['id'])){
                        self::orderPinkAfterNo($v['uid'],$v['k_id']);
                        if($isIds) array_push($pinkIds,$v['id']);
                        $pinkBool = 2;
                    }else{
                        if($isRunErr) return self::setErrorInfo(StoreOrder::getErrorInfo(),true);
                    }
                }
                //团长退款
                if(StoreOrder::orderApplyRefund(StoreOrder::getPinkOrderId($pinkT['order_id_key']),$pinkT['uid'],'拼团时间超时',true) && self::isTpl($pinkT['uid'],$pinkT['id'])){
                    self::orderPinkAfterNo($pinkT['uid'],$pinkT['id']);
                    if($isIds) array_push($pinkIds,$pinkT['id']);
                    $pinkBool = 2;
                }else{
                    if($isRunErr) return self::setErrorInfo(StoreOrder::getErrorInfo(),true);
                }
                if(!$pinkBool) $pinkBool = 3;
            }
            self::commit();
            if($isIds) return $pinkIds;
            return $pinkBool;
        }catch (\Exception $e){
            self::rollback();
            return self::setErrorInfo($e->getMessage());
        }
    }

    /*
     * 获取参团人和团长和拼团总人数
     * @param array $pink
     * @return array
     * */
    public static function getPinkMemberAndPinkK($pink){
        //查找拼团团员和团长
        if($pink['k_id']){
            $pinkAll = self::getPinkMember($pink['k_id']);
            $pinkT = self::getPinkUserOne($pink['k_id']);
        }else{
            $pinkAll = self::getPinkMember($pink['id']);
            $pinkT = $pink;
        }
        $count = count($pinkAll);
        $count=(int)$pinkT['people']-$count-1;
        $idAll = [];
        $uidAll =[];
        //收集拼团用户id和拼团id
        foreach ($pinkAll as $k=>$v){
            $idAll[$k] = $v['id'];
            $uidAll[$k] = $v['uid'];
        }
        $idAll[] = $pinkT['id'];
        $uidAll[] = $pinkT['uid'];
        return [$pinkAll,$pinkT,$count,$idAll,$uidAll];
    }
    /*
     * 取消开团
     * @param int $uid 用户id
     * @param int $pink_id 团长id
     * @return boolean
     * */
    public static function removePink($uid,$cid,$pink_id,$formId,$nextPinkT=null)
    {
        $pinkT=self::where(['uid'=>$uid,'id'=>$pink_id,'cid'=>$cid,'k_id'=>0,'is_refund'=>0,'status'=>1])->where('stop_time','GT',time())->find();
        if(!$pinkT) return self::setErrorInfo('未查到拼团信息，无法取消');
        self::startTrans();
        try{
            list($pinkAll,$pinkT,$count,$idAll,$uidAll)=self::getPinkMemberAndPinkK($pinkT);
            if(count($pinkAll)){
                if(self::getPinkPeople($pink_id,$pinkT->people)){
                    //拼团未完成，拼团有成员取消开团取 紧跟团长后拼团的人
                    if(isset($pinkAll[0])) $nextPinkT=$pinkAll[0];
                }else{
                    //拼团完成
                    self::PinkComplete($uidAll,$idAll,$uid,$pinkT);
                    return self::setErrorInfo(['status'=>200,'msg'=>'拼团已完成，无法取消']);
                }
            }
            //取消开团
            if(StoreOrder::orderApplyRefund(StoreOrder::getPinkOrderId($pinkT['order_id_key']),$pinkT['uid'],'拼团取消开团',true) && self::isTpl($pinkT['uid'],$pinkT['id']))
                self::orderPinkAfterNo($pinkT['uid'],$pinkT['id'],$formId,'拼团取消开团',true);
            else
                return self::setErrorInfo(['status'=>200,'msg'=>StoreOrder::getErrorInfo()],true);
            //当前团有人的时候
            if(is_array($nextPinkT)){
                self::where('id',$nextPinkT['id'])->update(['k_id'=>0]);
                self::where('k_id',$pinkT['id'])->update(['k_id'=>$nextPinkT['id']]);
            }
            self::commitTrans();
            return true;
        }catch (\Exception $e){
            return self::setErrorInfo($e->getLine().':'.$e->getMessage(),true);
        }
    }

    /**
     * 获取抢购时间
     */
    public static function get_pink_time(){
        $now_time=time();
        $toady=strtotime(date('Y-m-d',$now_time).' 00:00:00');
        $today_end=$toady+24*3600;
        $time_list=db('store_seckill')->where(['start_time'=>['elt',$toady],'stop_time'=>['egt',$today_end]])->column('time_type');
        if(!$time_list){
            return ['',0];
        }
        $data=[];
        //筛选出有的秒杀的时间段
        foreach ($time_list as $v){
            $data_value=explode(',',str_replace('|','',$v));
            $data=array_merge($data,$data_value);
        }
        unset($v);
        $data=array_unique($data);
        //获取当前处于哪个抢购时间段
        $now=floor(($now_time-$toady)/3600);
        $time_type=StoreSeckill::get_time_list();
        $time_last=[];
        $seckillTimeIndex=0;
        foreach ($time_type as $key=>$v){
            if(!in_array($v['value'],$data)){
                 unset($time_type[$key]);
                 continue;
            }
            if($v['value']>$now){
                $value['state'] = '即将开始';
                $value['status'] = 2;
            }else if($v['value']<$now){
                $value['state'] = '已结束';
                $value['status'] = 0;
            }else{
                $value['state'] = '抢购中';
                $value['status'] = 1;
                $seckillTimeIndex=count($time_last);
            }
            $value['stop'] = $toady+($v['value']+1)*3600;
            $value['time']=$v['label_name'];
            $value['id']=$v['value'];
            $time_last[]=$value;
        }
        unset($key,$v);
        return [$time_last,$seckillTimeIndex];
    }
}