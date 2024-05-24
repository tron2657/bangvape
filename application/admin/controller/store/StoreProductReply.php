<?php

namespace app\admin\controller\store;

use service\JsonService;
use app\admin\controller\AuthController;
use app\admin\model\store\StoreProduct;
use traits\CurdControllerTrait;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Request;
use app\admin\model\store\StoreProductReply as ProductReplyModel;
use think\Url;
use app\osapi\model\com\Message;
use app\osapi\model\com\MessageTemplate;
use app\osapi\model\com\MessageRead;
use app\osapi\lib\ChuanglanSmsApi;
use app\admin\model\system\SystemConfig;
use app\commonapi\model\Gong;
use service\FormBuilder as Form;

/**
 * 评论管理 控制器
 * Class StoreProductReply
 * @package app\admin\controller\store
 */
class StoreProductReply extends AuthController
{

    use CurdControllerTrait;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $status=osx_input('status',0);
        $this->assign('year',getMonth('y'));
        $this->assign('status',$status);
        return $this->fetch();
    }

    /**
     * 异步查找产品
     *
     * @return json
     */
    public function reply_list(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['comment',''],
            ['is_reply',''],
            ['data',''],
            'status',
        ]);
        return JsonService::successlayui(ProductReplyModel::ReplyList($where));
    }

    public function indexs()
    {
        $where = Util::getMore([
            ['is_reply',''],
            ['comment',''],
        ],$this->request);
        $product_id = 0;
        $product_id = input('product_id');
        if($product_id)
           $where['product_id'] =  $product_id;
        else
            $where['product_id'] =  0;
        $this->assign('where',$where);
        $this->assign(ProductReplyModel::systemPage($where,$type=1));
        return $this->fetch();
    }

    /**
     * @param $id
     * @return \think\response\Json|void
     */
    public function delete($id){
        if(!$id) return $this->failed('数据不存在');
        $data['is_del'] = 1;
        if(!ProductReplyModel::edit($data,$id)) {
            return Json::fail(ProductReplyModel::getErrorInfo('删除失败,请稍候再试!'));
        } else{
            $reply=ProductReplyModel::get($id);
            Gong::delaction('fashangpinpingjia',$reply['uid'],'商品评论被删除');
            $set=MessageTemplate::getMessageSet(35);
            $time=time_format(time());
            $title = StoreProduct::where('id',$reply['product_id'])->value('store_name');
            $length_title=mb_strlen($title,'UTF-8');
            $length_content=mb_strlen($reply['comment'],'UTF-8');
            if($length_title>7){
                $title=mb_substr($title,0,7,'UTF-8').'…';
            }
            if($length_content>7){
                $reply['comment']=mb_substr($reply['comment'],0,4,'UTF-8').'…';
            }
            $template=str_replace('{年月日时分}',$time,$set['template']);
            $template=str_replace('{评论内容}',$reply['comment'],$template);
            $template=str_replace('{商品名称}',$title,$template);
            if($set['status']==1){
                $message_id=Message::sendMessage($reply['uid'],0,$template,1,$set['title'],1,'index');
                $read_id=MessageRead::createMessageRead($reply['uid'],$message_id,$set['popup'],1);
            }
            if($set['sms']==1&&$set['status']==1){
                $account=db('user')->where('uid',$reply['uid'])->value('phone');
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
            return Json::successful('删除成功!');
        }
    }

    public function set_reply(Request $request){
        $data = Util::postMore([
            'id',
            'content',
        ],$request);
        if(!$data['id']) return Json::fail('参数错误');
        if($data['content'] == '') return Json::fail('请输入回复内容');
        $save['merchant_reply_content'] = $data['content'];
        $save['merchant_reply_time'] = time();
        $save['is_reply'] = 2;
        $res = ProductReplyModel::edit($save,$data['id']);
        if(!$res)
            return Json::fail(ProductReplyModel::getErrorInfo('回复失败,请稍候再试!'));
        else
            return Json::successful('回复成功!');
    }

    public function edit_reply(Request $request){
        $data = Util::postMore([
            'id',
            'content',
        ],$request);
        if(!$data['id']) return Json::fail('参数错误');
        if($data['content'] == '') return Json::fail('请输入回复内容');
        $save['merchant_reply_content'] = $data['content'];
        $save['merchant_reply_time'] = time();
        $save['is_reply'] = 2;
        $res = ProductReplyModel::edit($save,$data['id']);
        if(!$res)
            return Json::fail(ProductReplyModel::getErrorInfo('回复失败,请稍候再试!'));
        else
            return Json::successful('回复成功!');
    }

    public function reply()
    {
        $params = Util::getMore([
            ['id',''],
            ['merchant_reply_content',''],
            ['is_post',0],
        ],$this->request);
        if($params['is_post']==1){
            $ids=explode(',',$params['id']);
            $data['merchant_reply_content']=$params['merchant_reply_content'];
            $data['merchant_reply_time']=time();
            $data['is_reply']=2;
            $res=db('store_product_reply')->where(['id'=>['in',$ids]])->update($data);
            if($res!==false){
                return Json::successful('回复成功!');
            }else{
                return Json::fail(ProductReplyModel::getErrorInfo('回复失败,请稍候再试!'));
            }
        }else {
            $rep = db('store_product_reply')->where('id', $params['id'])->field('id,merchant_reply_content')->find();
            $reply_content = empty($rep) ? '' : $rep['merchant_reply_content'];
            $field = [
                Form::textarea('merchant_reply_content', '回复内容', $reply_content),
                Form::hidden('id', $params['id']),
                Form::hidden('is_post', 1),
            ];
            $form = Form::make_post_form('填写回复内容', $field, Url::build('reply'), 2);
            $this->assign(compact('form'));
            return $this->fetch('public/form-builder');
        }
    }

    /**
     * 批量还原评论
     *
     * @return json
     */
    public function restore(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要还原的评论');
        }else{
            $res = ProductReplyModel::where('id','in',$post['ids'])->update(['is_del'=>0]);
            if($res)
                return JsonService::successful('还原成功');
            else
                return JsonService::fail('还原失败');
        }
    }

    /**
     * 批量删除评论
     *
     * @return json
     */
    public function deletes(){
        $post=Util::postMore([
            ['ids',[]]
        ]);
        if(empty($post['ids'])){
            return JsonService::fail('请选择需要还原的评论');
        }else{
            $res = ProductReplyModel::where('id','in',$post['ids'])->update(['is_del'=>1]);
            if($res){
                $reply=ProductReplyModel::where('id','in',$post['ids'])->select();
                foreach($reply as &$val){
                    Gong::delaction('fashangpinpingjia',$val['uid'],'商品评论被删除');
                    $set=MessageTemplate::getMessageSet(35);
                    $time=time_format(time());
                    $title = StoreProduct::where('id',$val['product_id'])->value('store_name');
                    $length_title=mb_strlen($title,'UTF-8');
                    $length_content=mb_strlen($val['comment'],'UTF-8');
                    if($length_title>7){
                        $title=mb_substr($title,0,7,'UTF-8').'…';
                    }
                    if($length_content>7){
                        $val['comment']=mb_substr($val['comment'],0,4,'UTF-8').'…';
                    }
                    $template=str_replace('{年月日时分}',$time,$set['template']);
                    $template=str_replace('{评论内容}',$val['comment'],$template);
                    $template=str_replace('{商品名称}',$title,$template);
                    if($set['status']==1){
                        $message_id=Message::sendMessage($val['uid'],0,$template,1,$set['title'],1,'index');
                        $read_id=MessageRead::createMessageRead($val['uid'],$message_id,$set['popup'],1);
                    }
                    if($set['sms']==1&&$set['status']==1){
                        $account=db('user')->where('uid',$val['uid'])->value('phone');
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
                }
                unset($val);
                return JsonService::successful('还原成功');
            }else{
                return JsonService::fail('还原失败');
            }
        }
    }

}
