<?php


namespace app\admin\model\com;

use app\admin\controller\setting\SystemAdmin as SettingSystemAdmin;
use app\admin\model\user\User;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\com\ComForum as ForumModel;
use app\admin\model\system\SystemAdmin;
/**
 * Class ComThreadClass
 * @package app\admin\model\store
 */
class ComForumAdminScoreLog extends ModelBasic
{
    use ModelTrait;

    /*
     * 异步获取分类列表
     * @param $where
     * @return array
     */
    public static function LogList($where){
        $model = self::getModelObject($where)->field(['*']);
        $model = $model->page((int)$where['page'], (int)$where['limit']);
        $data = ($data = $model->order('create_time desc')->select()) && count($data) ? $data->toArray() : [];
        foreach ($data as &$item){
            $item['exp_name']=db('system_rule')->where('flag','exp')->value('name');
            $item['fly_name']=db('system_rule')->where('flag','fly')->value('name');
            $item['buy_name']=db('system_rule')->where('flag','buy')->value('name');
            $item['gong_name']=db('system_rule')->where('flag','gong')->value('name');
            $item['one_name']=db('system_rule')->where('flag','one')->value('name');
            $item['two_name']=db('system_rule')->where('flag','two')->value('name');
            $item['three_name']=db('system_rule')->where('flag','three')->value('name');
            $item['four_name']=db('system_rule')->where('flag','four')->value('name');
            $item['five_name']=db('system_rule')->where('flag','five')->value('name');
            $item['thread'] = ComThread::where('id',$item['tid'])->value('title');
            switch ($item['model']){
                case 1:
                    $item['model']='前台';
                    $item['do_nickname']=User::where('uid',$item['do_uid'])->value('nickname');
                    break;
                case 2:
                    $item['model']='后台';
                    $admin=SystemAdmin::get($item['do_uid']);

                    if($admin)
                    {
                        $item['do_nickname']=$admin['account'];
                    }

                    break;
            }
            switch ($item['type']){
                case 1:
                    $item['type']='积分';
                    break;
            }
            $item['nickname']=User::where('uid',$item['uid'])->value('nickname');
          
            $item['create_time']=time_format($item['create_time']);
        }
        $count=self::getModelObject($where)->count();
        return compact('count','data');
    }
    /**
     * 获取奖励次数
     */
    public static function getRewardCount($tid){
        $count=self::where('tid',$tid)->count();
        return $count;
    }
    /**
     * @param $where
     * @return array
     */
    public static function getModelObject($where=[]){
        $model=new self();
        if(!empty($where)){
            // data 日期
            $model->where(function($query) use($where){
                switch ($where['data']) {
                    case 'yesterday':
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                        $query->whereTime('create_time', $where['data']);
                        break;
                    case 'quarter':
                        $start = strtotime(Carbon::now()->startOfQuarter());
                        $end   = strtotime(Carbon::now()->endOfQuarter());
                        $query->whereTime('create_time', 'between', [$start, $end]);
                        break;
                    case '':
                        ;
                        break;
                    default:
                        $between = explode(' - ', $where['data']);
                        $query->whereTime('create_time', 'between', [$between[0], $between[1]]);
                        break;
                }
            });
            if(isset($where['uid']) && $where['uid']!=''){
                $uids = db('user')->where('nickname','LIKE',"%{$where['uid']}%")->column('uid');
                if($uids){
                    $model->where('uid', 'in', $uids);
                }
            }
            if(isset($where['do_uid']) && $where['do_uid']!=''){
                $uids = db('user')->where('nickname','LIKE',"%{$where['do_uid']}%")->column('uid');
                if($uids){
                    $model->where('do_uid', 'in', $uids);
                }
            }
            if(isset($where['model']) && $where['model']!=''){
                $model = $model->where('model',$where['model']);
            }
            if(isset($where['tid'])){
                $model->where('tid',$where['tid']);
            }
        }
        return $model;
    }

}