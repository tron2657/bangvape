<?php

namespace app\admin\controller\user;
use app\admin\controller\setting\SystemGroupData;
use service\FormBuilder as Form;
use service\JsonService as Json;
use service\UploadService as Upload;
use service\UtilService as Util;
use think\Request;
use think\Url;
use app\admin\model\system\SystemGroup as GroupModel;
use app\admin\model\system\SystemGroupData as GroupDataModel;
use app\admin\controller\AuthController;
use app\admin\model\system\SystemAttachment;

class MemberExclusive  extends AuthController
{
    public function index()
    { 
        $tab_id=osx_input('tab_id','111');
         
        $this->assign([
            'tab_id' => $tab_id,
            'tabs'=>[
                ['tab_id'=>111,'title'=>'会员折扣'],
                ['tab_id'=>112,'title'=>'会员赠送'],
                ['tab_id'=>114,'title'=>'积分返利']
            ]
        ]);
        return $this->fetch();
    }

    public function FieldFilter($var){
        if($var=='')
        {
            return true;
        }
        return false;
    }

    public function edit($gid,$id)
    {
        $GroupData = GroupDataModel::get($id);
        $GroupDataValue = json_decode($GroupData["value"],true);
        $GroupDataValue['code']=isset($GroupDataValue['code'])?$GroupDataValue['code']:['type'=>'input','value'=>''];
        $Fields = GroupModel::getField($gid);

        $f = array();
        foreach ($Fields['fields'] as $key => $value) {
            if( ($GroupDataValue['code']['value']!='exclusive_discount' && $value['title']=='vip_discount') )
            {
                break;
            }
            $info = [];
            if(isset($value["param"])){
                $value["param"] = str_replace("\r\n","\n",$value["param"]);//防止不兼容
                $params = explode("\n",$value["param"]);
                if(is_array($params) && !empty($params)){
                    foreach ($params as $index => $v) {
                        $vl = explode('=>',$v);
                        if(isset($vl[0]) && isset($vl[1])){
                            $info[$index]["value"] = $vl[0];
                            $info[$index]["label"] = $vl[1];
                        }
                    }
                }
            }
            $fvalue = isset($GroupDataValue[$value['title']]['value'])?$GroupDataValue[$value['title']]['value']:'';
            // if($value['title']=='vip_discount' && $GroupDataValue['exclusive_discount']['value'] )
            // {
            //     break;
            // }
            switch ($value['type']){
                case 'input':
                    $f[] = Form::input($value['title'],$value['name'],$fvalue);
                    break;
                case 'textarea':
                    $f[] = Form::input($value['title'],$value['name'],$fvalue)->type('textarea');
                    break;
                case 'radio':

                    $f[] = Form::radio($value['title'],$value['name'],$fvalue)->options($info);
                    break;
                 case 'checkbox':
                     $f[] = Form::checkbox($value['title'],$value['name'],$fvalue)->options($info);
                    break;
                 case 'upload':
                     if(!empty($fvalue)){
                         $image = is_string($fvalue) ? $fvalue : $fvalue[0];
                     }else{
                         $image = '';
                     }
                     $f[] = Form::frameImageOne($value['title'],$value['name'],Url::build('admin/widget.images/index',array('fodder'=>$value['title'],'big'=>1)),$image)->icon('image');
                    break;
                 case 'uploads':
                     $images = !empty($fvalue) ? $fvalue:[];
                     $f[] = Form::frameImages($value['title'],$value['name'],Url::build('admin/widget.images/index', array('fodder' => $value['title'],'big'=>1)),$images)->maxLength(5)->icon('images')->width('100%')->height('550px')->spin(0);
                    break;
                 case 'select':
                     $f[] = Form::select($value['title'],$value['name'],$fvalue)->setOptions($info);
                    break;
                default:
                    $f[] = Form::input($value['title'],$value['name'],$fvalue);
                    break;

            }
        }
        $f[] = Form::input('sort','排序',$GroupData["sort"]);
        $f[] = Form::radio('status','状态',$GroupData["status"])->options([['value'=>1,'label'=>'显示'],['value'=>2,'label'=>'隐藏']]);
        $form = Form::make_post_form('添加用户通知',$f,Url::build('update',compact('id')),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }
}
