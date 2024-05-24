<?php

namespace app\columnapi\model\column;

use think\Cache;
use think\Model;
use think\Db;
use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCollect extends ModelBasic
{
    use ModelTrait;
    /**
     * 是否收藏
     * qhy
     */
    public static function isCollect($id){
        $uid=get_uid();
        $count=self::where('uid',$uid)->where('pid',$id)->where('status',1)->count();
        if($count > 0){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 收藏
     * qhy
     */
    public static function doCollect($id,$is_column,$uid){
        $count=self::where('uid',$uid)->where('pid',$id)->count();
        if($count){
            $data['status']=1;
            $data['create_time']=time();
            $res=self::where('uid',$uid)->where('pid',$id)->update($data);
            return $res;
        }else{
            $data['uid']=$uid;
            $data['pid']=$id;
            $data['is_column']=$is_column;
            $data['status']=1;
            $data['create_time']=time();
            $res=self::insert($data);
            return $res;
        }
    }

    /**
     * 取消收藏
     * qhy
     */
    public static function delCollect($id,$uid){
        $data['status']=0;
        $data['create_time']=time();
        $res=self::where('uid',$uid)->where('pid',$id)->update($data);
        return $res;
    }

    /**
     * 我的收藏
     * qhy
     */
    public static function getCollectList($is_column,$uid){
        ColumnUserBuy::checkColumnBuy($uid);
        $list=self::where('uid',$uid)->where('is_column',$is_column)->where('status',1)->order('create_time desc')->select();
        foreach($list as $k=>&$value){
            $product=ColumnText::where('id',$value['pid'])->where('status',1)->where('is_show',1)->find();
            if($product){
                $product['image'] = ColumnText::checkImages($product['image']);
                $product['images'] = ColumnText::checkImages(json_decode($product['images'], true));
                $product['author']=db('column_author')->where('id',$product['author_id'])->find();
                $product['is_buy']=0;
                if($product['pid']==0){
                    if($product['is_free']==0){
                        $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$product['id'])->where('is_free',0)->where('status',1)->count();
                        if($user_buy>0){
                            $product['is_buy']=1;
                        }
                    }else{
                        $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$product['id'])->where('status',1)->count();
                        if($user_buy>0){
                            $product['is_buy']=1;
                        }
                    }
                }else{
                    $pid = explode(',',$product['pid']);
                    foreach($pid as &$val){
                        $column=self::where('id',$val)->find();
                        if($column['is_free']==0){
                            $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$val)->where('is_free',0)->where('status',1)->count();
                            if($user_buy>0){
                                $product['is_buy']=1;
                            }
                        }else{
                            $user_buy=ColumnUserBuy::where('uid',$uid)->where('pid',$val)->where('status',1)->count();
                            if($user_buy>0){
                                $product['is_buy']=1;
                            }
                        }
                    }
                    unset($val);
                }
                $value['product']=$product;
            }else{
                unset($list[$k]);
            }
        }
        unset($k);
        unset($value);
        return $list;
    }

}
