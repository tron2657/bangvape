<?php

namespace app\admin\controller\widget;
use app\admin\model\system\SystemConfig;
use app\core\util\TencentCosService;
use app\osapi\model\file\Picture;
use think\Request;
use think\Url;
use app\admin\model\system\SystemAttachment as SystemAttachmentModel;
use app\admin\model\system\SystemAttachmentCategory as Category;
use app\admin\controller\AuthController;
use service\UploadService as Upload;
use service\JsonService as Json;
use service\UtilService as Util;
use service\FormBuilder as Form;
 
/**
 * 文件校验控制器
 * Class SystemFile
 * @package app\admin\controller\system
 *
 */
class StroeProduct extends AuthController
{
    /**
     * 附件列表
     * @return \think\response\Json
     */
   public function index()
   {
       $pid =input('pid');
       if(!$pid){
           $pid=0;
       }
       $this->assign('pid',$pid);
       //分类标题
        // $typearray =\app\admin\model\store\StoreCategory::CategoryList(null)['data'];
    //  $this->assign('cate',CategoryModel::getTierList());
        $typearray = \app\admin\model\store\StoreCategory::getTierList();
       $this->assign(compact('typearray'));
       $this->assign('type',1);
//       $typearray = self::dir;
//       $this->assign(compact('typearray'));
       $this->assign(SystemAttachmentModel::getAll($pid));
       return $this->fetch('widget/stroe_product');
   }

   public function store_product_batch(){
    $pid =input('pid');
    if(!$pid){
        $pid=0;
    }
    $this->assign('pid',$pid);
    //分类标题
     // $typearray =\app\admin\model\store\StoreCategory::CategoryList(null)['data'];
 //  $this->assign('cate',CategoryModel::getTierList());
     $typearray = \app\admin\model\store\StoreCategory::getTierList();
    $this->assign(compact('typearray'));
    $this->assign('type',1);
//       $typearray = self::dir;
//       $this->assign(compact('typearray'));
    $this->assign(SystemAttachmentModel::getAll($pid));
    return $this->fetch('widget/store_product_batch');
   }

    /**
     * 异步查找产品
     *
     * @return json
     */
    public function product_ist(){
        $where=Util::getMore([
            ['page',1],
            ['limit',20],
            ['store_name',''],
            ['cate_id',''],
            ['excel',0],
            ['order',''],
            ['is_type',0],
            ['is_column',0],
            ['type',$this->request->param('type')],
            ['recommend_sell',2],
        ]);
        return \service\JsonService::successlayui(\app\admin\model\store\StoreProduct::ProductList($where));
    }


}
