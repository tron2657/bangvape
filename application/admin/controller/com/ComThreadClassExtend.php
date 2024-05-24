<?php
namespace app\admin\controller\com;

use app\admin\controller\AuthController;
use service\FormBuilder as Form;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Cache;
use think\Request;
use think\Url;
use app\admin\model\com\ComThreadClassExtend as ThreadClassExtendModel;
use app\admin\model\com\ComThreadClassExtend as ComComThreadClassExtend;

/**
 * 版块控制器
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class ComThreadClassExtend extends AuthController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function edit($class_id)
    {   
        if(!$class_id) return $this->failed('数据不存在');
        $data = ThreadClassExtendModel::get([
            'class_id'=>$class_id
        ]);
        if(!$data) 
        {
            $data=[
                'id'=>0,
                'tg_enable'=>0,
                'tg_start_time'=>0,
                'tg_end_time'=>0,
                'class_id'=>$class_id,
            ];
        }
        else{
            $data=$data->toArray();
        }
 
        $field = [
            Form::radio('tg_enable','是否开启',$data['tg_enable'])->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            Form::dateTimeRange('tg_timeRange','投稿开始时间',$data['tg_start_time'],$data['tg_end_time']),
            Form::input('class_id','板块分类ID',$data['class_id']) ,                        
            Form::hidden('id','唯一标识',$data['id']) ,
        ]; 

        $form = Form::make_post_form('编辑分类',$field,Url::build('save',array('id'=>$data['id'])),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

  /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request, $id)
    {
        $data = Util::postMore([  
            ['tg_enable',0,'intval'],          
           [ 'id',''],
   
           [ 'tg_timeRange',[0,0]],
            'class_id'          
        ],$request);
        $data['tg_timeRange'][0]= strtotime($data['tg_timeRange'][0]) ;
        $data['tg_timeRange'][1]= strtotime($data['tg_timeRange'][1]) ;



        $timeRange= $data['tg_timeRange'];
        if($id==0)
        {
            $insertData=[
                'tg_enable'=>$data['tg_enable'],
                'class_id'=>$data['class_id'],
                'tg_start_time'=>$timeRange[0],
                'tg_end_time'=>$timeRange[1],
            ];
            ComComThreadClassExtend::set($insertData);
            return Json::successful('添加成功!');
        }
        else{
            // $entity=ComComThreadClassExtend::get($data['id']);
            // $timeRange= explode('-', $data['tg_timeRange']);
            $updateData=[
                'tg_enable'=>$data['tg_enable'],
                'class_id'=>$data['class_id'],
                'tg_start_time'=>$timeRange[0],
                'tg_end_time'=>$timeRange[1],
            ];
            $res=ComComThreadClassExtend::where('id',$id)->update($updateData);
            return Json::successful('更新成功!');
        }
   

    }
       
}
