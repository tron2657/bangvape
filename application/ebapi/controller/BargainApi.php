<?php
namespace app\ebapi\controller;


use app\core\model\routine\RoutineCode;
use app\core\model\routine\RoutineTemplate;//待完善
use app\core\util\SystemConfigService;
use app\ebapi\model\store\StoreBargain;
use app\ebapi\model\store\StoreBargainUser;
use app\ebapi\model\store\StoreBargainUserHelp;
use app\core\util\GroupDataService;
use service\JsonService;
use service\UtilService;


/**
 * TODO 小程序砍价活动api接口
 * Class BargainApi
 * @package app\ebapi\controller
 */
class BargainApi extends AuthController
{

    /**
     * TODO 获取砍价列表参数
     */
    public function get_bargain_config(){
        $lovely = GroupDataService::getData('routine_lovely')?:[];//banner图
        $info = isset($lovely[2]) ? $lovely[2] : [];
        return JsonService::successful($info);
    }

    /**
     * TODO 获取砍价列表
     */
    public function get_bargain_list()
    {
        $data = UtilService::postMore([['offset',0],['limit',20]]);
        $bargainList = StoreBargain::getList($data['offset'],$data['limit']);
        StoreBargainUser::editBargainUserStatus($this->uid);// TODO 判断过期砍价活动
        return JsonService::successful($bargainList);
    }

    /**
     * TODO 砍价详情和当前登录人信息
     * @param int $bargainId  $bargainId 砍价产品
     * @return \think\response\Json
     */
    public function get_bargain(){
        list($bargainId) = UtilService::postMore([['bargainId',0]],null,true);
        if(!$bargainId) return JsonService::fail('参数错误');
        $bargain = StoreBargain::getBargainTerm($bargainId);
        if(empty($bargain)) return JsonService::fail('砍价已结束');
        $bargain['time'] = time();
        $data['userInfo'] = $this->userInfo;
        $data['bargain'] = $bargain;
        return JsonService::successful($data);
    }


    /**
     * TODO  开启砍价
     * @param int $bargainId $bargainId 砍价产品编号
     * @param int $bargainUserId  $bargainUserId 开启砍价的用户编号
     */
    public function set_bargain(){
        list($bargainId) = UtilService::postMore([['bargainId',0]],null,true);
        if(!$bargainId) return JsonService::fail('参数错误');
        $count = StoreBargainUser::isBargainUser($bargainId,$this->uid);
        if($count === false) return JsonService::fail('参数错误');
        else if($count) return JsonService::successful('参与成功');
        else $res = StoreBargainUser::setBargain($bargainId,$this->uid);
        if(!$res) return JsonService::fail('参与失败');
        else return JsonService::successful('参与成功');
    }

    /**
     * TODO 帮好友砍价
     * @param int $bargainId $bargainId  砍价产品
     * @param int $bargainUserUid  $bargainUserUid 开启砍价用户编号
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function set_bargain_help(){
        list($bargainId,$bargainUserUid) = UtilService::postMore([['bargainId',0],['bargainUserUid',0]],null,true);
        if(!$bargainId || !$bargainUserUid) return JsonService::fail('参数错误');
        $res = StoreBargainUserHelp::setBargainUserHelp($bargainId,$bargainUserUid,$this->userInfo['uid']);
        if($res) {
            if(!StoreBargainUserHelp::getSurplusPrice($bargainId,$bargainUserUid)){
                $bargainUserTableId = StoreBargainUser::getBargainUserTableId($bargainId,$bargainUserUid);// TODO 获取用户参与砍价表编号
                $bargainInfo = StoreBargain::get($bargainId);//TODO 获取砍价产品信息
                $bargainUserInfo = StoreBargainUser::get($bargainUserTableId);// TODO 获取用户参与砍价信息
                RoutineTemplate::sendBargainSuccess($bargainInfo,$bargainUserInfo,$bargainUserUid);//TODO 砍价成功给开启砍价用户发送模板消息
            }
            return JsonService::successful('砍价成功');
        }
        else return JsonService::fail('砍价失败');
    }

    /**
     * TODO 获取砍价帮
     * @param int $bargainId $bargainId 砍价产品
     * @param int $bargainUserUid $bargainUserUid 开启砍价用户编号
     * @param int $offset
     * @param int $limit
     */
    public function get_bargain_user(){
        list($bargainId,$bargainUserUid,$offset,$limit) = UtilService::postMore([
            ['bargainId',0],
            ['bargainUserUid',0],
            ['offset',0],
            ['limit',20]
        ],null,true);
        if(!$bargainId) return JsonService::fail('参数错误');
        $bargainUserTableId = StoreBargainUser::getBargainUserTableId($bargainId,$bargainUserUid); //TODO 砍价帮获取参与砍价表编号
        $storeBargainUserHelp = StoreBargainUserHelp::getList($bargainUserTableId,$offset,$limit);
        return JsonService::successful($storeBargainUserHelp);
    }

    /**
     * TODO 添加砍价分享次数
     */
    public function add_share_bargain(){
        list($bargainId) = UtilService::postMore([['bargainId',0]],null,true);
        $data['lookCount'] = StoreBargain::getBargainLook();//TODO 观看人数
        $data['shareCount'] = StoreBargain::getBargainShare();//TODO 分享人数
        $data['userCount'] = StoreBargainUser::count();//TODO 参与人数
        if(!$bargainId) return JsonService::successful($data);
        StoreBargain::addBargainShare($bargainId);
        $data['shareCount'] = StoreBargain::getBargainShare();//TODO 分享人数
        return JsonService::successful($data);
    }

    /**
     * TODO 添加砍价浏览次数
     */
    public function add_look_bargain(){
        list($bargainId) = UtilService::postMore([['bargainId',0]],null,true);
        $data['lookCount'] = StoreBargain::getBargainLook();//TODO 观看人数
        $data['shareCount'] = StoreBargain::getBargainShare();//TODO 分享人数
        $data['userCount'] = StoreBargainUser::count();//TODO 参与人数
        if(!$bargainId) return JsonService::successful($data);
        StoreBargain::addBargainLook($bargainId);
        $data['lookCount'] = StoreBargain::getBargainLook();//TODO 观看人数
        return JsonService::successful($data);
    }

    /**
     * TODO 获取砍价帮总人数、剩余金额、进度条、已经砍掉的价格
     * @param int $bargainId
     * @param int $bargainUserUid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_bargain_help_count(){
        list($bargainId,$bargainUserUid) = UtilService::postMore([['bargainId',0],['bargainUserUid',0]],null,true);
        if(!$bargainId || !$bargainUserUid) return JsonService::fail('参数错误');
        $count = StoreBargainUserHelp::getBargainUserHelpPeopleCount($bargainId,$bargainUserUid);//TODO 获取砍价帮总人数
        $price = StoreBargainUserHelp::getSurplusPrice($bargainId,$bargainUserUid);//TODO 获取砍价剩余金额
        $bargainUserTableId = StoreBargainUser::getBargainUserTableId($bargainId,$bargainUserUid);//TODO 获取用户参与砍价表编号
        $alreadyPrice = StoreBargainUser::getBargainUserPrice($bargainUserTableId);//TODO 用户已经砍掉的价格 好友砍价之后获取用户已经砍掉的价格
        $pricePercent = StoreBargainUserHelp::getSurplusPricePercent($bargainId,$bargainUserUid);//TODO 获取砍价进度条
        $data['count'] = $count;
        $data['price'] = $price;
        $data['alreadyPrice'] = $alreadyPrice;
        $data['pricePercent'] = $pricePercent > 10 ? $pricePercent : 10;
        return JsonService::successful($data);
    }

    /**
     * TODO 获取帮忙砍价砍掉多少金额
     * @param int $bargainId
     * @param int $bargainUserUid
     */
    public function get_bargain_user_bargain_price(){
        list($bargainId,$bargainUserUid) = UtilService::postMore([['bargainId',0],['bargainUserUid',0]],null,true);
        if(!$bargainId || !$bargainUserUid) return JsonService::fail('参数错误');
        $bargainUserTableId = StoreBargainUser::getBargainUserTableId($bargainId,$bargainUserUid);//TODO 获取用户参与砍价表编号
        $price = StoreBargainUserHelp::getBargainUserBargainPrice($bargainId,$bargainUserTableId,$this->uid,'price');// TODO 获取用户砍掉的金额
        if($price) return JsonService::successful('ok',$price);
        else return JsonService::fail('获取失败');
    }

    /**
     * TODO 获取砍价状态
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function set_status(){
        list($bargainId,$bargainUserUid) = UtilService::postMore([['bargainId',0],['bargainUserUid',0]],null,true);
        if($bargainUserUid != $this->uid) $status = 1;
        else $status = 0;
        if(!$status && !StoreBargainUserHelp::getSurplusPrice($bargainId,$bargainUserUid)){//砍价成功
           $statusSql = StoreBargainUser::getBargainUserStatus($bargainId,$bargainUserUid);
           if($statusSql == 1) $status = 3;
           else if($statusSql == 2) $status = 4;
           else if($statusSql == 3) $status = 5;
        }else if($status && !StoreBargainUserHelp::isBargainUserHelpCount($bargainId,$bargainUserUid,$this->userInfo['uid'])) $status = 2;
        return JsonService::successful('ok',$status);
    }

    /**
     * TODO 获取砍价产品  个人中心 我的砍价
     * @throws \think\Exception
     */
    public function bargain_list(){
        $page=osx_input('page',0,'intval');
        $limit=osx_input('limit',20,'intval');
        StoreBargainUser::editBargainUserStatus($this->uid);// TODO 判断过期砍价活动
        $list = StoreBargainUser::getBargainUserAll($this->uid,$page,$limit);
        if(count($list)) return JsonService::successful($list);
        else return JsonService::fail('暂无参与砍价');
    }

    /**
     * TODO 取消砍价
     */
    public function cancel_bargain(){
        list($bargainId) = UtilService::postMore([['bargainId',0]],null,true);
        $status = StoreBargainUser::getBargainUserStatus($bargainId,$this->uid);
        if($status != 1) return JsonService::fail('状态错误');
        $id = StoreBargainUser::getBargainUserTableId($bargainId,$this->uid);
        $res = StoreBargainUser::edit(['is_del'=>1],$id);
        if($res) return JsonService::successful('取消成功');
        else return JsonService::successful('取消失败');
    }

    /**
     * TODO 生成海报
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function bargain_share_poster()
    {
        list($bargainId) = UtilService::postMore([['id',0]],null,true);
        $storeBargainInfo = StoreBargain::getBargain($bargainId);
        $price = StoreBargainUserHelp::getSurplusPrice($bargainId,$this->uid);//TODO 获取砍价剩余金额
        try{
            $data['title'] = $storeBargainInfo['title'];
            $data['image'] = substr($storeBargainInfo['image'],stripos($storeBargainInfo['image'], '/public/uploads/'),strlen($storeBargainInfo['image']));
            $data['price'] = $price;
            $data['label'] = '已砍至';
            $data['msg'] = '还差'.$price.'元即可砍价成功';
            $path = makePathToUrl('routine/codepath/bargain/',4);
            if($path == '') return JsonService::fail('生成上传目录失败,请检查权限!');
            $codePath = $path.$bargainId.'_'.$this->userInfo['uid'].'_bargain.jpg';
            if(!file_exists($codePath)){
                $res = RoutineCode::getPageCode('pages/activity/goods_bargain_details/index','id='.$bargainId.'&bargain='.$this->uid,280);
                if($res) file_put_contents($codePath,$res);
                else return JsonService::fail('二维码生成失败');
            }
            $data['url'] = $codePath;
            $path = makePathToUrl('routine/share/bargain',3);
            if($path == '') return JsonService::fail('生成上传目录失败,请检查权限!');
            $filename = ROOT_PATH.$path.'/'.$bargainId.'_'.$this->userInfo['uid'].'_bargain.jpg';
            UtilService::setShareMarketingPoster($data,$filename);
        }catch (\Exception $e){
            return JsonService::fail('成海报失败',['line'=>$e->getLine(),'message'=>$e->getMessage()]);
        }
        $domain = SystemConfigService::get('site_url').'/';
        $poster = $domain.$path.'/'.$bargainId.'_'.$this->userInfo['uid'].'_bargain.jpg';
        return JsonService::successful('ok',$poster);
    }
}