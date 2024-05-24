<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/1
 * Time: 14:03
 */

namespace app\osapi\model\channel;


use app\osapi\model\BaseModel;
use traits\ModelTrait;

class ChannelUser extends BaseModel
{
    use ModelTrait;

    /**
     * -重置我的频道
     * @param $uid
     * @param $channel_ids
     * @param $type
     * @return bool
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public static function resetMyChannel($uid,$channel_ids,$type='')
    {
        $delete_old=0;
        if($channel_ids!=''){
            $channel_ids=explode(',',$channel_ids);
            $sort_num=1;
            self::where('uid',$uid)->delete();
            $delete_old=1;
            if($type=='register'){
                $channel_ids=db('channel')->where(['status'=>1])->whereOr(['id'=>['in',$channel_ids]])->whereOr(['type'=>1])->order('default_open_status desc,default_sort asc')->column('id');
            }
            foreach ($channel_ids as $val){
                // if(intval($val)>4){//小于4的标识为系统频道，不添加到用户频道中
                    // if($sort_num==1){
                    //     self::where('uid',$uid)->delete();
                    //     $delete_old=1;
                    // }
                    self::set([
                        'channel_id'=>intval($val),
                        'uid'=>$uid,
                        'sort_num'=>$sort_num,
                        'create_time'=>time(),
                        'status'=>1
                    ]);
                    $sort_num++;
                // }
            }
            unset($sort_num,$val);
        }
        if(!$delete_old){
            self::where('uid',$uid)->setField('status',0);
        }
        return true;
    }
}