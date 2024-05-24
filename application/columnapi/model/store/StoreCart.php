<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/18
 */

namespace app\columnapi\model\store;

use app\columnapi\model\column\ColumnText;
use think\Db;
use basic\ModelBasic;
use traits\ModelTrait;
use app\admin\model\system\SystemConfig;

class StoreCart extends ModelBasic
{
    use ModelTrait;

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    public static function setCart($uid,$product_id,$cart_num = 1,$product_attr_unique = '',$type='product',$is_new = 0,$combination_id=0,$seckill_id = 0,$bargain_id = 0,$buy_type='add_to_cart')
    {
        if(!ColumnText::isValidProduct($product_id))
            return self::setErrorInfo('该产品已下架或删除',false,true);
        $where = ['type'=>$type,'uid'=>$uid,'product_id'=>$product_id,'product_attr_unique'=>$product_attr_unique,'is_new'=>$is_new,'is_pay'=>0,'is_del'=>0,'combination_id'=>$combination_id];
        if($cart = self::where($where)->find()){
            $cart_limit=SystemConfig::getValue('cart_limit');
            if($cart->cart_num>$cart_limit&&$cart_limit>0){
                $cart->cart_num=$cart_limit;
            }
            $cart->add_time = time();
            $cart->result=$cart->save();
            return $cart;
        }else{
            return self::set(compact('uid','product_id','cart_num','product_attr_unique','is_new','type','combination_id'),true);
        }
    }

    public static function removeUserCart($uid,$ids)
    {
        return self::where('uid',$uid)->where('id','IN',$ids)->update(['is_del'=>1]);
    }

    /* 专栏删除购物车上架数据
     * @param $uid
     * @param $productId 商品ID
     * 说明: 单个点击删除
     *      (1)删除时如果相同数据存在多条，只保留最新一条，其它全部删除
     *      (2)最新一条数据改变is_del状态[删除]
     * */
    public static function removeZgUserCartByClick($uid,$productId)
		{
    	$results = self::where('uid',$uid) -> where('product_id',$productId) -> select();
    	$results_num = count($results);

    	if($results_num>1){
    		$lastOne_id = self::where('uid',$uid) -> where('product_id',$productId) -> order('id','desc') -> value('id');
    		self::where('uid',$uid) -> where('product_id',$productId) -> where('id','<>',$lastOne_id) -> delete();

				return self::where('id',$lastOne_id) -> update(['is_del'=>1]);
			}else{
				return self::where('uid',$uid) -> where('product_id',$productId) -> update(['is_del'=>1]);
			}
		}

	/* 专栏清空购物车上架数据
	 * @param $uid
	 * @param $ids 购物车记录id
	 * @param $productIds 商品id
	 * 说明: 勾选清空购物车操作
	 *      (1)正常情况,配合ColumnApi中is_delete()方法,不限条数即可去重
	 *      (2)如果存在多条重复记录未经发现应删除,避免对下架和购买状态造成影响
	 * */
	public static function removeZgUserCart($uid,$ids,$productIds)
	{
		//正常情况: 清空修改记录状态[删除]
		$keepRealRecords = Db('store_cart') -> where('uid',$uid) -> where('id','IN',$ids) -> update(['is_del'=>1]);

		//不正常情况：处理重复记录
		$results = Db('store_cart') -> where('uid',$uid)
																-> where('product_id','IN',$productIds)
																-> field('id,uid,product_id,type')
																-> order('id','desc')
																-> select();
		foreach($results as $key => $value){
			$keepProIds[$key] = $value['product_id'];
		}
		//dump($keepProIds);
		$countProIds = array_count_values($keepProIds);
		foreach($countProIds as $val){
			$theProId = $val;
			if($theProId && $theProId>1){
				self::where('product_id',$theProId) -> where('id','NOT IN',$ids) -> where('type','LIKE','is_zg') -> delete();
			}
		}

		return $keepRealRecords;
	}

    public static function getUserCartNum($uid,$type)
    {
     //商品库存为0的商品自动删除 购物车自动删除
        $id=db('store_product')->whereOr('stock',0)->whereOr('is_del',1)->whereOr('is_show',0)->field('id')->select();
        $id=array_column($id,'id');
        return self::where('uid',$uid)->where('type',$type)->where('is_pay',0)->where('is_del',0)->where('is_new',0)->where('product_id','NOT IN',$id)->count();
    }

    /**
     * TODO 修改购物车库存
     * @param $cartId
     * @param $cartNum
     * @param $uid
     * @return StoreCart|bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function changeUserCartNum($cartId,$cartNum,$uid)
    {
        $count = self::where('uid',$uid)->where('id',$cartId)->count();
        if(!$count) return self::setErrorInfo('参数错误');
        $cartInfo = self::where('uid',$uid)->where('id',$cartId)->field('product_id,combination_id,seckill_id,bargain_id,product_attr_unique')->find()->toArray();
        $stock = 0;
        if($cartInfo['bargain_id']){
            //TODO 获取砍价产品的库存
            $stock = 0;
        }else if($cartInfo['seckill_id']){
            //TODO 获取秒杀产品的库存
            $stock = 0;
        }else if($cartInfo['combination_id']){
            //TODO 获取拼团产品的库存
            $stock = 0;
        }else if($cartInfo['product_id']){
            //TODO 获取普通产品的库存
            $stock = StoreProduct::getProductStock($cartInfo['product_id'],$cartInfo['product_attr_unique']);
        }
        if(!$stock) return self::setErrorInfo('暂无库存');
        if(!$cartNum) return self::setErrorInfo('库存错误');
        if($stock < $cartNum) return self::setErrorInfo('库存不足'.$cartNum);
        return self::where('uid',$uid)->where('id',$cartId)->update(['cart_num'=>$cartNum]);
    }



    public static function getUserZgCartList($uid,$cartIds='',$status=0)
    {
        $productInfoField = 'id,image,images,price,ot_price,cost_price,category_id,sales,name,info,is_show,status,strip_num,is_column';
        $model = new self();
        $valid = $invalid = [];
        $model = $model->where('uid',$uid)->where('type','is_zg')->where('is_pay',0)
            ->where('is_del',0);
        if(!$status) $model->where('is_new',0);
        if($cartIds) $model->where('id','IN',$cartIds);
        $list = $model->select()->toArray();
        if(!count($list)) return compact('valid','invalid');
        foreach ($list as $k=>$cart){
            $product = ColumnText::field($productInfoField)
                ->find($cart['product_id'])->toArray();
            $cart['productInfo'] = $product;
            //商品不存在
            if(!$product){
                $model->where('id',$cart['id'])->update(['is_del'=>1]);
                //商品删除或无库存
            }else{
                $cart['trueStrip_num'] = isset($cart['productInfo']['strip_num'])?$cart['productInfo']['strip_num']:0;
                $cart['truePrice'] = $cart['productInfo']['price'];
                $cart['costPrice'] = $cart['productInfo']['cost_price'];
                $valid[] = $cart;
            }
        }
        return compact('valid','invalid');
    }

    /**
     * 拼团
     * @param $uid
     * @param string $cartIds
     * @return array
     */
    public static function getUserCombinationProductCartList($uid,$cartIds='')
    {
        $productInfoField = 'id,image,slider_image,price,cost,ot_price,vip_price,postage,mer_id,give_integral,cate_id,sales,stock,store_name,unit_name,is_show,is_del,is_postage';
        $model = new self();
        $valid = $invalid = [];
        $model = $model->where('uid',$uid)->where('type','product')->where('is_pay',0)
            ->where('is_del',0);
        if($cartIds) $model->where('id','IN',$cartIds);
        $list = $model->select()->toArray();
        if(!count($list)) return compact('valid','invalid');
        foreach ($list as $k=>$cart){
            $product = StoreProduct::field($productInfoField)
                ->find($cart['product_id'])->toArray();
            $cart['productInfo'] = $product;
            //商品不存在
            if(!$product){
                $model->where('id',$cart['id'])->update(['is_del'=>1]);
            //商品删除或无库存
            }else if(!$product['is_show'] || $product['is_del'] || !$product['stock']){
                $invalid[] = $cart;
            //商品属性不对应
//            }else if(!StoreProductAttr::issetProductUnique($cart['product_id'],$cart['product_attr_unique'])){
//                $invalid[] = $cart;
            //正常商品
            }else{
                $cart['truePrice'] = (float)StoreCombination::where('id',$cart['combination_id'])->value('price');
                $cart['costPrice'] = (float)StoreCombination::where('id',$cart['combination_id'])->value('cost');
                $cart['trueStock'] = StoreCombination::where('id',$cart['combination_id'])->value('stock');
                $valid[] = $cart;
            }
        }

        foreach ($valid as $k=>$cart){
            if($cart['trueStock'] < $cart['cart_num']){
                $cart['cart_num'] = $cart['trueStock'];
                $model->where('id',$cart['id'])->update(['cart_num'=>$cart['cart_num']]);
                $valid[$k] = $cart;
            }
        }

        return compact('valid','invalid');
    }

    /**
     * 是否购买，或者加入购物车
     * [getUserOrder description]
     * @param  [type] $uid [description]
     * @param  [type] $gid [description]
     * @return [type]      [description]
     */
    public static function getUserOrder($uid,$gid,$where=['is_del'=>0,'is_pay'=>1])
    {
        $model = self::where('uid',$uid)
                ->where(['type'=>'is_zg','product_id'=>$gid])
                ->where($where)
                ->limit(1)
                ->find();
        if (!empty($model)) {
            return 1;
        }else{
            return 0;
        }
    }

    /**
    * 书架，拥有的书架，为购买的书架
    */
    public static function CartList($uid,$is_pay = 1,$page = 1,$size = 1000)
    {
        $field = 'b.mer_id,cart.id as cart_id,b.id,b.image,b.store_name,b.price,b.sales+b.ficti sales,b.store_info,a.name,a.type,a.is_read,b.add_time';
        $ct = Db::name('ColumnText')
                ->order('id desc')
                ->limit(10000000000)//不加这一行代码就有可能出现不是最新的代码
                ->buildSql();//构建查询语句ficti
        $lists =  Db::table($ct.' a')
                ->join('StoreProduct b','a.pid=b.id')
                ->join('StoreCart cart','cart.product_id=b.id')
                ->group('a.pid')->fetchSql(true)
                ->where('b.is_show',1)
                ->where('b.is_del',0)->where('b.stock','>',0)->where('b.is_column',1)->where('b.is_type',1);
        if (!empty($sid)) $lists =  $lists->where('b.cate_id',$sid);
        $lists = $lists->where('cart.is_del',0)->where('cart.is_pay',$is_pay)->where('cart.uid',$uid)->where('cart.type','is_zg');
        $lists =  $lists->order('cart.id DESC');
        $lists =  $lists->field($field)
                ->limit(($page-1)*$size,$size)
                ->select();
        return Db::query($lists);
    }

    /**
    * 书架，拥有的书架总条数，为购买的书架总条数
    */
    public static function CartListCount($uid,$is_pay = 1)
    {
        $model = new self;
        $model = $model->alias('cart');
        $model = $model->where('cart.is_del',0);
        if ($is_pay <= 1) $model = $model->where('cart.is_pay',$is_pay);
        $model = $model->where('cart.uid',$uid)->where('cart.type','is_zg');
        $model = $model->join('StoreProduct SP','SP.id=cart.product_id','left');
        $model = $model->where('SP.is_del',0)->where('SP.is_show',1);
        $model = $model->count();
        return $model;
    }

    public static function getCartCountSum($uid,$where=['is_del'=>0])
    {
        return self::where('uid',$uid)
                ->where(['type'=>'is_zg'])
                ->where($where)
                ->limit(1)
                ->count();
    }
}