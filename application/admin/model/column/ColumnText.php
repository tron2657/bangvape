<?php

namespace app\admin\model\column;

use app\columnapi\model\column\ColumnCollect;
use basic\ModelBasic;
use service\PHPExcelService;
use traits\ModelTrait;
use think\Db;
use app\admin\model\column\ColumnCategory;

class ColumnText extends ModelBasic
{
    use ModelTrait;
    public static function systemCouponIssuePage($pid,$page = 1,$limit = 20)
    {
    	$page = intval($page);
    	$limit = intval($limit);
        $data = self::where('pid',$pid)->page($page,$limit)->order('id desc')->select()->toArray();
        $count =  self::where('pid',$pid)->count();
        return ['data' => $data,'count' => $count, 'total' => ceil($count / $limit)];
    }

    static public function contents($id)
    {
        return self::where('id',$id)->find()->toArray();
    }
    static public function updateMV($data,$id)
    {
        return self::save($data,$id);
    }

    public static function ColumnList($where){
        $model=self::getColumnModelObject($where);
        $model=$model->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            $cateName = db('column_category')->where('id', 'IN', $item['category_id'])->column('cate_name', 'id');
            $item['cate_name']=is_array($cateName) ? implode(',',$cateName) : '';
            $item['nickname']=db('column_author')->where('id',$item['author_id'])->value('nickname');
            $item['collect'] = db('column_collect')->where('pid',$item['id'])->where('status',1)->count();//收藏
            switch($item['is_free']){
                case 1:
                    $item['is_free']='免费';
                    break;
                case 0:
                    $item['is_free']='付费';
                    break;
            }
        }
        $count=self::getColumnModelObject($where)->count();
        return compact('count','data');
    }

    /**
     * 获取连表MOdel
     * @param $model
     * @return object
     */
    public static function getColumnModelObject($where=[]){
        $model=new self();
        if(!empty($where)){
            if(isset($where['is_free']) && $where['is_free']!=''){
                $model = $model->where('is_free',$where['is_free']);
            }
            if(isset($where['is_column']) && $where['is_column']!=''){
                $model = $model->where('is_column',$where['is_column']);
            }
            if(isset($where['is_show']) && $where['is_show']!=''){
                $model = $model->where('is_show',$where['is_show']);
            }
            if(isset($where['status']) && $where['status']!=''){
                $model = $model->where('status',$where['status']);
            }
            if(isset($where['type']) && $where['type']!=''){
                $model = $model->where('type',$where['type']);
            }
            if(isset($where['store_name']) && $where['store_name']!=''){
                $model = $model->where('name|keyword|id','LIKE',"%$where[store_name]%");
            }
            if(isset($where['cate_id']) && trim($where['cate_id'])!=''){
                $catid1 = $where['cate_id'].',';//匹配最前面的cateid
                $catid2 = ','.$where['cate_id'].',';//匹配中间的cateid
                $catid3 = ','.$where['cate_id'];//匹配后面的cateid
                $catid4 = $where['cate_id'];//匹配全等的cateid
//                $model = $model->whereOr('category_id','LIKE',["%$catid%",$catidab]);
                $sql = " LIKE '$catid1%' OR `category_id` LIKE '%$catid2%' OR `category_id` LIKE '%$catid3' OR `category_id`=$catid4";
                $model->where(self::getPidSql($where['cate_id']));
            }
            if(isset($where['order']) && $where['order']!=''){
                $model = $model->order(self::setOrder($where['order']));
            }else{
                $model = $model->order('id desc');
            }
            if(isset($where['author_name']) && $where['author_name']!=''){
                $author_uids = db('column_author')->where('nickname','LIKE',"%{$where['author_name']}%")->column('id');
                if($author_uids){
                    $model->where('author_id', 'in', $author_uids);
                }
            }
        }
        return $model;
    }

    public static function ProductList($where){
        $model=self::getModelObject($where);
        $model=$model->page((int)$where['page'],(int)$where['limit']);
        // return $model->select();
        $data=($data=$model->select()) && count($data) ? $data->toArray():[];
        foreach ($data as &$item){
            $cateName = db('column_category')->where('id', 'IN', $item['category_id'])->column('cate_name', 'id');
            $item['cate_name']=is_array($cateName) ? implode(',',$cateName) : '';
            $item['nickname']=db('column_author')->where('id',$item['author_id'])->value('nickname');
            $item['collect'] = db('column_collect')->where('pid',$item['id'])->where('status',1)->count();//收藏
            switch($item['is_free']){
                case 1:
                    $item['is_free']='免费';
                    break;
                case 0:
                    $item['is_free']='付费';
                    break;
            }
        }
        $count=self::getModelObject($where)->count();
        return compact('count','data');
    }

    /**
     * 获取连表MOdel
     * @param $model
     * @return object
     */
    public static function getModelObject($where=[]){
        $model=new self();
        if(!empty($where)){
            if(isset($where['pid']) && $where['pid']!=''){
                $model = $model->where('find_in_set(:id,pid)',['id'=>$where['pid']]);
            }
            if(isset($where['store_name']) && $where['store_name']!=''){
                $model = $model->where('name|keyword|id','LIKE',"%$where[store_name]%");
            }
            if(isset($where['order']) && $where['order']!=''){
                $model = $model->order(self::setOrder($where['order']));
            }else{
                $model = $model->order('id desc');
            }
        }
        return $model;
    }

    /** 如果有子分类查询子分类获取拼接查询sql
     * @param $cateid
     * @return string
     */
    protected static function getPidSql($cateid){

        $sql = self::getCateSql($cateid);
        $ids = ColumnCategory::where('pid', $cateid)->column('id');
        //查询如果有子分类获取子分类查询sql语句
        if($ids) foreach ($ids as $v) $sql .= " OR ".self::getcatesql($v);
        return $sql;
    }

    /**根据cateid查询产品 拼sql语句
     * @param $cateid
     * @return string
     */
    protected static function getCateSql($cateid){
        $lcateid = $cateid.',%';//匹配最前面的cateid
        $ccatid = '%,'.$cateid.',%';//匹配中间的cateid
        $ratidid = '%,'.$cateid;//匹配后面的cateid
        return  " `category_id` LIKE '$lcateid' OR `category_id` LIKE '$ccatid' OR `category_id` LIKE '$ratidid' OR `category_id`=$cateid";
    }

    /**
     * 获取真实销量排行
     * @param array $where
     * @return array
     */
    public static function getSaleslists($where,$is_free=0){
        $data=self::setWhere($where)->where('a.is_pay',1)
            ->group('a.product_id')
            ->where('b.is_free',$is_free)
            ->field(['sum(a.cart_num) as num_product','b.name','b.image','b.price','b.id','b.is_column'])
            ->order('num_product desc')
            ->page((int)$where['page'],(int)$where['limit'])
            ->select();
        $count=self::setWhere($where)->where('a.is_pay',1)->where('b.is_free',$is_free)->group('a.product_id')->count();
        foreach ($data as &$item){
            $item['sum_price']=bcmul($item['num_product'],$item['price'],2);
        }
        return compact('data','count');
    }

    /**
     * 设置查询条件
     * @param array $where
     * @return array
     */
    public static function setWhere($where){
        $time['data']='';
        if(isset($where['start_time']) && $where['start_time']!='' && isset($where['end_time']) && $where['end_time']!=''){
            $time['data']=$where['start_time'].' - '.$where['end_time'];
        }else{
            $time['data']=isset($where['data'])? $where['data']:'';
        }
        $model=self::getModelTime($time, Db::name('store_cart')->alias('a')->join('__COLUMN_TEXT__ b','a.product_id=b.id'),'a.add_time');
        if(isset($where['title']) && $where['title']!=''){
            $model=$model->where('b.name|b.id','like',"%$where[title]%");
        }
        if(isset($where['is_column']) && $where['is_column']!=''){
            $model=$model->where('b.is_column',$where['is_column']);
        }
        $model=$model->where('a.type','is_zg');
        return $model;
    }

    /**
     * 专栏单品合计
     * @param string $type
     * @param string $data
     * @return array
     * @author xzj
     * @date 2020/10/19
     */
    public static function getColumnSummaryMerge($type='',$data=''){
        $res1 = self::getColumnSummary($type,$data,1);
        $res2 = self::getColumnSummary($type,$data,0);
        $count = $res1['count']+$res2['count'];
        $legdata = array_merge($res1['legdata'],$res2['legdata']);
        $datetime = array_unique(array_merge($res1['datetime'],$res2['datetime']));
        rsort($datetime);
        $dt1 = array_flip($res1['datetime']);
        $dt2 = array_flip($res2['datetime']);
        $chatrList = array_merge($res1['chatrList'],$res2['chatrList']);
        foreach ($datetime as $k => $v) {
            $chatrList[0]['data'][$k] = isset($dt1[$v]) ? $res1['chatrList'][0]['data'][$dt1[$v]] : 0;
            $chatrList[1]['data'][$k] = isset($dt1[$v]) ? $res1['chatrList'][1]['data'][$dt1[$v]] : 0;
            $chatrList[2]['data'][$k] = isset($dt2[$v]) ? $res2['chatrList'][0]['data'][$dt2[$v]] : 0;
            $chatrList[3]['data'][$k] = isset($dt2[$v]) ? $res2['chatrList'][1]['data'][$dt2[$v]] : 0;
        }
        $badge = $res1['badge'];
        return compact('datetime','chatrList','legdata','badge','count');
    }

    /**
     * 专栏统计
     **/
    public static function getColumnSummary($type='',$data='',$is_column=1){
        if($is_column==1){
            $legdata=['专栏收藏','专栏销量'];
        }else{
            $legdata=['单品销量','单品收藏'];
        }
        $model=self::setWhereType(self::order('id desc'),$type,$is_column);
        $list=self::getModelTime(compact('data'),$model,'create_time')
            ->field('FROM_UNIXTIME(create_time,"%Y-%m-%d") as un_time,count(id) as count,sum(sales) as sales')
            ->group('un_time')
            ->distinct(true)
            ->select()
            ->each(function($item) use($data){
                $item['collect']=ColumnCollect::getModelTime(compact('data'),new ColumnCollect,'create_time')->where(['status'=>1])->count();
                $item['like']='';
            })->toArray();
        $chatrList=[];
        $datetime=[];
        $data_item=[];
        $itemList=[0=>[],1=>[],2=>[],3=>[]];
        foreach ($list as $item){
            $itemList[0][]=(int)$item['sales'];
            $itemList[1][]=$item['count'];
            $itemList[2][]=$item['like'];
            $itemList[3][]=$item['collect'];
            array_push($datetime,$item['un_time']);
        }
        foreach ($legdata as $key=>$leg){
            $data_item['name']=$leg;
            $data_item['type']='line';
            $data_item['data']=$itemList[$key];
            $chatrList[]=$data_item;
            unset($data_item);
        }
        unset($leg);
        $badge['free_column']=self::getColumnCount(1,1);
        $badge['column']=self::getColumnCount(0,1);
        $badge['free_product']=self::getColumnCount(1,0);
        $badge['product']=self::getColumnCount(0,0);
        $count=self::setWhereType(self::getModelTime(compact('data'),new self(),'create_time'),$type,$is_column)->count();
        return compact('datetime','chatrList','legdata','badge','count');
    }

    //获取 badge 内容
    public static function getColumnCount($is_free=1,$is_column=1){
        return [
                'name'=>'数量',
                'field'=>'个',
                'count'=>self::where('status',1)->where('is_free',$is_free)->where('is_column',$is_column)->count(),
                'content'=>'数量',
                'background_color'=>'layui-bg-blue',
        ];
    }

    public static function setWhereType($model,$type,$is_column){
        switch ($type){
            case 2:
                $data = ['is_show'=>0,'status'=>1,'is_column'=>$is_column];
                break;
            case 5:
                $data = ['status'=>-1,'is_column'=>$is_column];
                break;
            default:
                $data = ['is_column'=>$is_column];
                break;
        }
        if(isset($data)) $model = $model->where($data);
        return $model;
    }

    //获取差评
    public static function getnegativelist($where){
        $list=self::alias('s')->join('ColumnProductReply r','s.id=r.product_id')
            ->field('s.id,s.name,s.price,s.is_column,count(r.product_id) as count')
            ->page((int)$where['page'],(int)$where['limit'])
            ->where('r.product_score',1)
            ->order('count desc')
            ->group('r.product_id')
            ->select();
        if(count($list)) $list=$list->toArray();
        $count=self::alias('s')->join('ColumnProductReply r','s.id=r.product_id')->group('r.product_id')->where('r.product_score',1)->count();
        return ['count'=>$count,'data'=>$list];
    }

    /**
     * 单个商品详情的头部查询
     * @param $id
     * @param $where
     * @return array[]
     * @author xzj
     * @date 2020/10/17
     */
    public static function getProductBadgeList($id,$where){
        $data['data']=$where;
        $list=self::setWhere($data)
            ->field(['sum(a.cart_num) as num_product','b.id','b.price'])
            ->where('a.is_pay',1)
            ->group('a.product_id')
            ->order('num_product desc')
            ->select();
        //排名
        $ranking=0;
        //销量
        $xiaoliang=0;
        //销售额 数组
        $list_price=[];
        foreach ($list as $key=>$item){
            if($item['id']==$id){
                $ranking=$key+1;
                $xiaoliang=$item['num_product'];
            }
            $value['sum_price']=$item['price']*$item['num_product'];
            $value['id']=$item['id'];
            $list_price[]=$value;
        }
        //排序
        $list_price=self::my_sort($list_price,'sum_price',SORT_DESC);
        //销售额排名
        $rank_price=0;
        //当前销售额
        $num_price=0;
        if($list_price!==false && is_array($list_price)){
            foreach ($list_price as $key=>$item){
                if($item['id']==$id){
                    $num_price=$item['sum_price'];
                    $rank_price=$key+1;
                    continue;
                }
            }
        }
        return [
            [
                'name'=>'销售额排名',
                'field'=>'名',
                'count'=>$rank_price,
                'background_color'=>'layui-bg-blue',
            ],
            [
                'name'=>'销量排名',
                'field'=>'名',
                'count'=>$ranking,
                'background_color'=>'layui-bg-blue',
            ],
            [
                'name'=>'商品销量',
                'field'=>'名',
                'count'=>$xiaoliang,
                'background_color'=>'layui-bg-blue',
            ],
            [
                'name'=>'点赞次数',
                'field'=>'个',
                'count'=>0,
                'background_color'=>'layui-bg-blue',
            ],
            [
                'name'=>'销售总额',
                'field'=>'元',
                'count'=>$num_price,
                'background_color'=>'layui-bg-blue',
                'col'=>12,
            ],
        ];
    }

    /**
     * 处理二维数组排序
     * @param $arrays
     * @param $sort_key
     * @param int $sort_order
     * @param int $sort_type
     * @return array|false|mixed
     * @author xzj
     * @date 2020/10/17
     */
    public static function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }
        if(isset($key_arrays)){
            array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
            return $arrays;
        }
        return false;
    }

    /**
     * 查询单个商品的销量曲线图
     * @param $where
     * @return array
     * @author xzj
     * @date 2020/10/17
     */
    public static function getProductCurve($where){
        $list=self::setWhere($where)
            ->where('a.product_id',$where['id'])
            ->where('a.is_pay',1)
            ->field(['FROM_UNIXTIME(a.add_time,"%Y-%m-%d") as _add_time','sum(a.cart_num) as num'])
            ->group('_add_time')
            ->order('_add_time asc')
            ->select();
        $seriesdata=[];
        $date=[];
        $zoom='';
        foreach ($list as $item){
            $date[]=$item['_add_time'];
            $seriesdata[]=$item['num'];
        }
        if(count($date)>$where['limit']) $zoom=$date[$where['limit']-5];
        return compact('seriesdata','date','zoom');
    }

    /**
     * 查询单个商品的销售列表
     * @param $where
     * @return mixed
     * @author xzj
     * @date 2020/10/17
     */
    public static function getSalelList($where){
        $data = self::setWhere($where)
            ->where(['a.product_id'=>$where['id'],'a.is_pay'=>1])
            ->field(['FROM_UNIXTIME(a.add_time,"%Y-%m-%d") as _add_time','a.uid','b.price','a.id','a.cart_num as num'])
            ->page((int)$where['page'],(int)$where['limit'])
            ->select();
        if (!empty($data)) {
            foreach ($data as &$val) {
                $val['nickname'] = ($nickname = db('user')->where('uid',$val['uid'])->value('nickname')) ? $nickname : '';
            }
        }
        return $data;
    }

    /**
     * 本地图片加上域名
     * @param $images
     * @return array|string
     * @author xzj
     * @date 2020/10/30
     */
    public static function checkImages($images)
    {
        if (is_string($images)) {
            return ( stripos($images,'http') !==0 && stripos($images,'/') === 0 ) ? get_domain() . $images : $images;
        }
        if (is_array($images)) {
            foreach ($images as &$image) {
                if (is_string($image) && stripos($image,'http') !==0 && stripos($image,'/') === 0) $image = get_domain() . $image;
            }
            unset($image);
            return $images;
        }
        return $images;
    }

    /**
     * 导出Excel
     * @param array $where
     * @author xzj
     * @date 2020/11/5
     */
    public static function SaveProductExport($where = [])
    {
        $list=self::setWhere($where);
        $list = $list->where('a.is_pay',1)
            ->field(['sum(a.cart_num) as num_product','b.name','b.image','b.price','b.id'])
            ->order('num_product desc')
            ->group('a.product_id')
            ->select();
        $export=[];
        foreach ($list as $item){
            $export[]=[
                $item['id'],
                $item['name'],
                $item['price'],
                bcmul($item['num_product'],$item['price'],2),
                $item['num_product'],
            ];
        }
        PHPExcelService::setExcelHeader(['商品编号','商品名称','商品售价','销售额','销量'])
            ->setExcelTile('产品销量排行','产品销量排行',' 生成时间：'.date('Y-m-d H:i:s',time()))
            ->setExcelContent($export)
            ->ExcelSave();
    }

}
