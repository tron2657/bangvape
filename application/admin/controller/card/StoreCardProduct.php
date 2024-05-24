<?php

namespace app\admin\controller\card;

use app\admin\controller\AuthController;
use service\FormBuilder as Form;
use app\admin\model\store\StoreProductAttr;
use app\admin\model\store\StoreProductAttrResult;
use app\admin\model\store\StoreProductRelation;
use app\admin\model\store\StoreProductServices;
use app\admin\model\system\SystemConfig;
use service\JsonService;
use traits\CurdControllerTrait;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Request;
use app\admin\model\store\StoreCategory as CategoryModel;
use app\admin\model\store\StoreProduct as ProductModel;
use think\Url;

use app\admin\model\system\SystemAttachment;
use app\admin\model\card\CardExchange;

/**
 * 产品管理
 * Class StoreProduct
 * @package app\admin\controller\store
 */
class StoreCardProduct extends AuthController
{
    use CurdControllerTrait;

    protected $bindModel = ProductModel::class;
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $type=$this->request->param('type');
        // $type=1;
        if(!$type){
            $type=1;
        }
        //获取分类
        $this->assign('cate',CategoryModel::getTierList());
        //出售中产品
        $onsale =  ProductModel::where(['is_show'=>1,'is_del'=>0,'is_type'=>2])->count();
        //待上架产品
        $forsale =  ProductModel::where(['is_show'=>0,'is_del'=>0,'is_type'=>2])->count();
        //仓库中产品
        $warehouse =  ProductModel::where(['is_del'=>0,'is_type'=>2])->count();
        // //已经售馨产品
        // $outofstock = ProductModel::getModelObject()->where(ProductModel::setData(4))->count();
        // //警戒库存
        // $policeforce =ProductModel::getModelObject()->where(ProductModel::setData(5))->count();
        //回收站
        $recycle =  ProductModel::where(['is_del'=>1,'is_type'=>2])->count();

        $this->assign(compact('type','onsale','forsale','warehouse','recycle'));
        return $this->fetch();
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
            ['is_type',2],
            ['is_column',0],
            ['type',$this->request->param('type')],
            ['recommend_sell',2],
        ]);
        return JsonService::successlayui(ProductModel::ProductList($where));
    }
     /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create_old()
    {
        $open_list=self::_getClientOpenList();
        $field = [
            Form::input('store_name','产品名称')->col(Form::col(24)),
            Form::input('store_info','产品简介(50字以内)')->type('textarea'),
            // Form::input('keyword','产品关键字')->placeholder('多个用英文状态下的逗号隔开'),
            // Form::input('unit_name','产品单位','件'),
            Form::frameImageOne('image','产品主图片(750*750)',Url::build('admin/widget.images/index',array('fodder'=>'image')))->icon('image')->width('100%')->height('500px'),
            // Form::frameImages('slider_image','产品轮播图(750*750)',Url::build('admin/widget.images/index',array('fodder'=>'slider_image')))->maxLength(5)->icon('images')->width('100%')->height('500px')->spin(0),
            Form::number('price','产品售价')->min(0),
            // Form::number('ot_price','产品市场价')->min(0)->col(8),
            // Form::number('give_integral','赠送购物积分')->min(0)->precision(0)->col(8),
            // Form::number('postage','邮费')->min(0)->col(Form::col(8)),
            // Form::number('sales','销量',0)->min(0)->precision(0)->col(8)->readonly(1),
            // Form::number('ficti','虚拟销量')->min(0)->precision(0)->col(8),
            // Form::number('ficti_browse','虚拟浏览量')->min(0)->precision(0)->col(8),
            // Form::number('stock','库存')->min(0)->precision(0)->col(8),
            // Form::number('cost','产品成本价')->min(0)->col(8),
            Form::number('sort','排序'),
            // Form::number('buy_num','限购数（当前无效）',0)->min(0)->col(8),
            // Form::number('platform_get','平台抽取')->min(0)->col(8),
            // Form::number('strip_num','剥比')->min(0)->col(8)->disabled(!in_array('ebapi_distribution',$open_list)),
            Form::radio('is_show','产品状态',0)->options([['label'=>'上架','value'=>1],['label'=>'下架','value'=>0]]),
            // Form::radio('is_hot','热卖单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_benefit','促销单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_best','精品推荐',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_new','首发新品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_postage','是否包邮',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::checkbox('services', '支持服务')->options(StoreProductServices::ServicesOptionList())
        ];
        $form = Form::make_post_form('添加礼品卡',$field,Url::build('save'),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function create()
    {
        $id=osx_input('id',0);
        $product = ProductModel::get($id);
        $cardExchange= CardExchange::where(['product_id'=>$id])->alias('e')->join('StoreProduct p1','e.exchange_product_id=p1.id')->find();
        if($id){
            $product['product-img']=$cardExchange['image'];
            $exchange_id=$cardExchange['exchange_product_id'];
            $product['product_id']=$cardExchange['exchange_product_id'];
        }
        $this->assign(['event'=>$product]);
        return $this->fetch();
    }
    public function editSave(Request $request){
        $data = Util::postMore([
            ['cate_id',[]],
            'store_name',
            ['store_info',''],
            ['keyword',''],
            ['unit_name','件'],
            ['image',[]],
            ['slider_image',[]],
            ['postage',0],
            ['ot_price',0],
            ['price',0],
            ['sort',0],
            ['buy_num',0],
            ['platform_get',0],
            ['strip_num',0],
            ['stock',100000],
            ['sales',0],
            ['ficti',100],
            ['ficti_browse',0],
            ['give_integral',0],
            ['is_show',0],
            ['cost',0],
            ['is_hot',0],
            ['is_benefit',0],
            ['is_best',0],
            ['is_new',0],
            ['mer_use',0],
            ['is_postage',0],
            ['product_id',0],//关联商品
            ['id',0]
        ],$request);
        $id=$data['id'];
        if(!$data['store_name']) return JsonService::successful('error','请输入产品名称');
        if(count($data['image'])<1) return JsonService::successful('error','请上传产品图片');
        if($data['price'] == '' || $data['price'] < 0) return JsonService::successful('error','请输入产品售价');        
        if($data['stock'] == '' || $data['stock'] < 0) return JsonService::successful('error','请输入库存');
        if(mb_strlen($data['store_info'])>50) return JsonService::successful('error','简介超过字数限制');
        $data['image'] = $data['image'][0];        
        $data['add_time'] = time();
        $data['description'] = '';
        $data['is_type']=2;
        if($id!=0){
            ProductModel::update($data,$id);
            CardExchange::where(['product_id'=>$id])->update(['exchange_product_id'=>$data['product_id']]);
            return Json::successful('ok','修改成功');
        }
        $exchange=CardExchange::where(['exchange_product_id'=>$data['product_id']])->find();
        if($exchange){
            return JsonService::successful('error','不能添加兑换商品相同礼品卡!');
        }
        // $exchangeData=[
        //     ['product_id',$id],
        //     ['exchange_product_id',$data['product_id']]
        // ];
        $productModel=new ProductModel();
        $product= $productModel->save($data,[],'id');

        $insertId = $productModel->getQuery()->getLastInsID('id');
        $exchangeData=[
            'product_id'=>$insertId,
            'exchange_product_id'=>$data['product_id']
        ];        
        CardExchange::set($exchangeData);
        
        
        return JsonService::successful('ok','添加礼品卡成功!');
    }
     /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = Util::postMore([
            ['cate_id',[]],
            'store_name',
            'store_info',
            ['keyword',''],
            ['unit_name','件'],
            ['image',[]],
            ['slider_image',[]],
            ['postage',0],
            ['ot_price',0],
            ['price',0],
            ['sort',0],
            ['buy_num',0],
            ['platform_get',0],
            ['strip_num',0],
            ['stock',100000],
            ['sales',0],
            ['ficti',100],
            ['ficti_browse',0],
            ['give_integral',0],
            ['is_show',0],
            ['cost',0],
            ['is_hot',0],
            ['is_benefit',0],
            ['is_best',0],
            ['is_new',0],
            ['mer_use',0],
            ['is_postage',0]
        ],$request);
        if(!$data['store_name']) return Json::fail('请输入产品名称');
        if(count($data['image'])<1) return Json::fail('请上传产品图片');
        if($data['price'] == '' || $data['price'] < 0) return Json::fail('请输入产品售价');        
        if($data['stock'] == '' || $data['stock'] < 0) return Json::fail('请输入库存');
        if(mb_strlen($data['store_info'])>50) return Json::fail('简介超过字数限制');
        $data['image'] = $data['image'][0];        
        $data['add_time'] = time();
        $data['description'] = '';
        $data['is_type']=2;  
        
        ProductModel::set($data);
        return Json::successful('添加礼品卡成功!');
    }
     /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if(!$id) return $this->failed('数据不存在');
        $product = ProductModel::get($id);
        if(!$product) return Json::fail('数据不存在!');
        $open_list=self::_getClientOpenList();
        $field = [
            Form::input('store_name','产品名称',$product->getData('store_name'))->col(Form::col(24)),
            Form::input('store_info','产品简介(50字以内)',$product->getData('store_info'))->type('textarea'),
            // Form::input('keyword','产品关键字')->placeholder('多个用英文状态下的逗号隔开'),
            // Form::input('unit_name','产品单位','件'),
            Form::frameImageOne('image','产品主图片(750*750)',Url::build('admin/widget.images/index',array('fodder'=>'image')),$product->getData('image'))->icon('image')->width('100%')->height('500px'),
            // Form::frameImages('slider_image','产品轮播图(750*750)',Url::build('admin/widget.images/index',array('fodder'=>'slider_image')))->maxLength(5)->icon('images')->width('100%')->height('500px')->spin(0),
            Form::number('price','产品售价',$product->getData('price'))->min(0),
            // Form::number('ot_price','产品市场价')->min(0)->col(8),
            // Form::number('give_integral','赠送购物积分')->min(0)->precision(0)->col(8),
            // Form::number('postage','邮费')->min(0)->col(Form::col(8)),
            // Form::number('sales','销量',0)->min(0)->precision(0)->col(8)->readonly(1),
            // Form::number('ficti','虚拟销量')->min(0)->precision(0)->col(8),
            // Form::number('ficti_browse','虚拟浏览量')->min(0)->precision(0)->col(8),
            // Form::number('stock','库存')->min(0)->precision(0)->col(8),
            // Form::number('cost','产品成本价')->min(0)->col(8),
            Form::number('sort','排序',$product->getData('sort')),
            // Form::number('buy_num','限购数（当前无效）',0)->min(0)->col(8),
            // Form::number('platform_get','平台抽取')->min(0)->col(8),
            // Form::number('strip_num','剥比')->min(0)->col(8)->disabled(!in_array('ebapi_distribution',$open_list)),
            Form::radio('is_show','产品状态',$product->getData('is_show'))->options([['label'=>'上架','value'=>1],['label'=>'下架','value'=>0]]),
            // Form::radio('is_hot','热卖单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_benefit','促销单品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_best','精品推荐',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_new','首发新品',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::radio('is_postage','是否包邮',0)->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(8),
            // Form::checkbox('services', '支持服务')->options(StoreProductServices::ServicesOptionList())
        ];
        $form = Form::make_post_form('编辑产品',$field,Url::build('update',array('id'=>$id)),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

     /**
     * 保存修改的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request,$id)
    {
        $data = Util::postMore([
            ['cate_id',[]],
            'store_name',
            'store_info',
            ['keyword',''],
            ['unit_name','件'],
            ['image',[]],
            ['slider_image',[]],
            ['postage',0],
            ['ot_price',0],
            ['price',0],
            ['sort',0],
            ['buy_num',0],
            ['platform_get',0],
            ['strip_num',0],
            ['stock',100000],
            ['sales',0],
            ['ficti',100],
            ['ficti_browse',0],
            ['give_integral',0],
            ['is_show',0],
            ['cost',0],
            ['is_hot',0],
            ['is_benefit',0],
            ['is_best',0],
            ['is_new',0],
            ['mer_use',0],
            ['is_postage',0]
        ],$request);
        if(!$data['store_name']) return Json::fail('请输入产品名称');
        if(count($data['image'])<1) return Json::fail('请上传产品图片');
        if($data['price'] == '' || $data['price'] < 0) return Json::fail('请输入产品售价');        
        if($data['stock'] == '' || $data['stock'] < 0) return Json::fail('请输入库存');
        if(mb_strlen($data['store_info'])>50) return Json::fail('简介超过字数限制');
        $data['image'] = $data['image'][0];        
        $data['add_time'] = time();
        $data['description'] = '';
        $data['is_type']=2;  
        ProductModel::edit($data,$id);
        return Json::successful('修改礼品卡成功!');
    }
     /**
     * 快速编辑
     *
     * @return json
     */
    public function set_product($field='',$id='',$value=''){
        $field=='' || $id=='' || $value=='' && JsonService::fail('缺少参数');
        if(ProductModel::where(['id'=>$id])->update([$field=>$value]))
            return JsonService::successful('保存成功');
        else
            return JsonService::fail('保存失败');
    }

}