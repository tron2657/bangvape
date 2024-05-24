<?php
/**
 * 知识商城优惠券前台领取表
 * 2020-10
 */

namespace app\columnapi\model\column;


use basic\ModelBasic;
use traits\ModelTrait;

class ColumnCouponIssue extends ModelBasic
{
    use ModelTrait;

    /**
     * 获取可领取的优惠券
     * @param $uid
     * @param $limit
     * @return array
     */
    public static function getIssueCouponList($uid,$limit)
    {
        $list = self::validWhere('A')->join('__COLUMN_COUPON__ B','A.cid = B.id')
            ->field('A.*,B.coupon_price,B.use_min_price,B.title')->order('B.sort DESC,A.id DESC')->limit($limit)->select()->toArray()?:[];
        foreach ($list as &$v){
            $v['is_use'] = ColumnCouponIssueUser::be(['uid'=>$uid,'issue_coupon_id'=>$v['id']]);
            $v['add_time']=date('Y/m/d',$v['add_time']);
            $v['end_time']=$v['end_time'] ? date('Y/m/d',$v['end_time']) : date('Y/m/d',time()+86400);
        }
        return $list;
    }

    /**
     * 领取优惠券
     * @param $id
     * @param $uid
     * @return bool|object
     */
    public static function issueUserCoupon($id,$uid)
    {
        $issueCouponInfo = self::validWhere()->where('id',$id)->find();
        if(!$issueCouponInfo) return self::setErrorInfo('领取的优惠劵已领完或已过期!');
        if(ColumnCouponIssueUser::be(['uid'=>$uid,'issue_coupon_id'=>$id]))
            return self::setErrorInfo('已领取过该优惠劵!');
        self::beginTrans();
        $res1 = false != ColumnCouponUser::addUserCoupon($uid,$issueCouponInfo['cid']);
        $res2 = false != ColumnCouponIssueUser::addUserIssue($uid,$id);
        $res3 = true;
        if($issueCouponInfo['total_count'] > 0){
            $issueCouponInfo['remain_count'] -= 1;
            $res3 = false !== $issueCouponInfo->save();
        }
        $res = $res1 && $res2 && $res3;
        self::checkTrans($res);
        return $res;
    }

    public static function validWhere($prefix = '')
    {
        $model = new self;
        if($prefix){
            $model->alias($prefix);
            $prefix .= '.';
        }
        $newTime = time();
        return $model->where("{$prefix}status",1)
            ->where(function($query) use($newTime,$prefix){
                $query->where(function($query) use($newTime,$prefix){
                    $query->where("{$prefix}start_time",'<',$newTime)->where("{$prefix}end_time",'>',$newTime);
                })->whereOr(function ($query) use($prefix){
                    $query->where("{$prefix}start_time",0)->where("{$prefix}end_time",0);
                });
            })->where("{$prefix}is_del",0)
            ->where('remain_count','>',0);
    }
}