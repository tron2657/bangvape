<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2020/7/16
 * Time: 14:04
 */

namespace app\admin\controller\channel;


use app\admin\controller\AuthController;
use app\admin\model\channel\Channel;
use app\admin\model\channel\ChannelCountContent;
use app\admin\model\channel\ChannelCountOpenRate;
use app\admin\model\channel\ChannelCountView;
use app\admin\model\channel\ChannelCountViewLog;
use app\admin\model\channel\ChannelPost;
use service\JsonService;

class Count extends AuthController
{
    public function index()
    {
        $channel_id=osx_input('id',2,'intval');
        $this->assign('channel_id',$channel_id);
        $channel_list=Channel::getAllChannelList();
        $this->assign('channel_list',$channel_list);
        return $this->fetch();
    }

    public function getDefaultInfo()
    {
        $channel_id=osx_input('channel_id',2,'intval');

        $yesterday_time=strtotime(date('Y-m-d',strtotime('-1 day')));
        $today_start_time=strtotime(date('Y-m-d'));

        //内容数
        $yesterday_content_num=ChannelCountContent::where('channel_id',$channel_id)
            ->where('create_time',$yesterday_time)//今日凌晨1点统计的是昨日的内容数。实际上今日凌晨1点前的都算昨日的
            ->sum('post_num');
        $today_content_num=ChannelPost::where('channel_id',$channel_id)
            ->where('status',1)->where('is_hide',0)->where('deadline','>',time())->count();
        $content['content_num']=$today_content_num;
        if($today_content_num>$yesterday_content_num){
            if($yesterday_content_num>0){
                $content['change_rate']=($yesterday_content_num-$today_content_num)*100/$yesterday_content_num;
            }else{
                $content['change_rate']='100.0';
            }
            $content['is_up']=1;
        }elseif ($today_content_num<$yesterday_content_num){
            $content['change_rate']=($yesterday_content_num-$today_content_num)*100/$yesterday_content_num;
            $content['is_down']=1;
        }else{
            $content['change_rate']='0.0';
        }
        $content['change_rate']=number_format($content['change_rate'], 2);

        //浏览数
        $yesterday_view_num=ChannelCountView::where('channel_id',$channel_id)->where('type',1)->where('create_time',$yesterday_time)->value('view_num');
        $today_view_num=ChannelCountViewLog::where('channel_id',$channel_id)
            ->where('create_time','egt',$today_start_time)->count();
        $view['view_num']=$today_view_num;
        if($today_view_num>$yesterday_view_num){
            if($yesterday_view_num>0){
                $view['change_rate']=($yesterday_view_num-$today_view_num)*100/$yesterday_view_num;
            }else{
                $view['change_rate']='100.0';
            }
            $view['is_up']=1;
        }elseif ($today_view_num<$yesterday_view_num){
            $view['change_rate']=($yesterday_view_num-$today_view_num)*100/$yesterday_view_num;
            $view['is_down']=1;
        }else{
            $view['change_rate']='0.0';
        }
        $view['change_rate']=number_format($view['change_rate'], 2);

        $data['count_content']=$content;
        $data['count_view']=$view;
        if($channel_id!=2){
            //开启率  新的计算 默认都是开启的
            //开启率=（总人数-未设置开启的人数）/总人数
            $open_rate=[];
            $yesterday_open_rate=ChannelCountOpenRate::where('channel_id',$channel_id)->where('create_time',$yesterday_time)->value('rate');
            $today_open_num=db('channel_user')->where('channel_id',$channel_id)->where('status',1)->count();
            $all_set_num=db('channel_user')->where(['id'=>['gt',0]])->group('uid')->count();
            $total_user=db('user')->where('status',1)->count();
            $today_open_rate=($total_user-($all_set_num-$today_open_num))*100/$total_user;
            $open_rate['rate']=number_format($today_open_rate,2);
            if($today_open_rate>$yesterday_open_rate){
                if($yesterday_open_rate>0){
                    $open_rate['change_rate']=($yesterday_open_rate-$today_open_rate)*100/$yesterday_open_rate;
                }else{
                    $open_rate['change_rate']='100.0';
                }
                $open_rate['is_up']=1;
            }elseif ($today_open_rate<$yesterday_open_rate){
                $open_rate['change_rate']=($yesterday_open_rate-$today_open_rate)*100/$yesterday_open_rate;
                $open_rate['is_down']=1;
            }else{
                $open_rate['change_rate']='0.0';
            }
            $open_rate['change_rate']=number_format($open_rate['change_rate'], 2);

            $data['count_open_rate']=$open_rate;
            $data['count_open_rate']['has_open_rate']=1;
        }else{
            $data['count_open_rate']['has_open_rate']=0;
        }
        return JsonService::success($data);
    }

    /**
     * -内容数展示
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function content_num_echart()
    {
        header('Content-type:text/json');
        $channel_id=osx_input('channel_id',0,'intval');
        if(!$channel_id){
            return JsonService::fail('非法请求');
        }
        $datalist = [];
        for($i=-30;$i < 0;$i++){
            $datalist[date('m-d',strtotime($i.' day'))] = date('m-d',strtotime($i.' day'));
        }
        $datebefor = date('Y-m-d',strtotime('-30 day'));
        $dateafter = date('Y-m-d');

        $field_time="FROM_UNIXTIME(create_time,'%m-%d')";
        $post_num_list = ChannelCountContent::where('create_time','between time',[$datebefor,$dateafter])
            ->where('channel_id',$channel_id)
            ->where('post_type',2)
            ->field($field_time." as day,post_num")
            ->order('create_time asc')
            ->select();

        $auto_post_num_list = ChannelCountContent::where('create_time','between time',[$datebefor,$dateafter])
            ->where('channel_id',$channel_id)
            ->where('post_type',1)
            ->field($field_time." as day,post_num")
            ->order('create_time asc')
            ->select();
        if($post_num_list){
            $post_num_list=$post_num_list->toArray();
            $post_num_list=array_combine(array_column($post_num_list,'day'),$post_num_list);
        }
        if($auto_post_num_list){
            $auto_post_num_list=$auto_post_num_list->toArray();
            $auto_post_num_list=array_combine(array_column($auto_post_num_list,'day'),$auto_post_num_list);
        }
        if(!count($post_num_list)&&!count($auto_post_num_list)) return JsonService::fail('无数据');

        $cycle_list = [];
        foreach ($datalist as $dk=>$dd){
            $cycle_list[$dd]['day']=$dd;
            if(!empty($post_num_list[$dd])){
                $cycle_list[$dd]['recommend_post_num'] = $post_num_list[$dd]['post_num'];
            }else{
                $cycle_list[$dd]['recommend_post_num'] = 0;
            }

            if(!empty($auto_post_num_list[$dd])){
                $cycle_list[$dd]['auto_post_num'] = $auto_post_num_list[$dd]['post_num'];
            }else{
                $cycle_list[$dd]['auto_post_num'] = 0;
            }
        }
        $chartdata = [];
        $data = [];//临时
        $chartdata['yAxis']['max_recommend_post_num'] = 0;//最大手动推荐数量
        $chartdata['yAxis']['max_auto_post_num'] = 0;//最大自动推荐数量
        foreach ($cycle_list as $k=>$v){
            $data['day'][] = $v['day'];
            $data['recommend_post_num'][] = $v['recommend_post_num'];
            $data['auto_post_num'][] = $v['auto_post_num'];
            if($chartdata['yAxis']['max_recommend_post_num'] < $v['recommend_post_num'])
                $chartdata['yAxis']['max_recommend_post_num'] = $v['recommend_post_num'];//最大手动推荐数量
            if($chartdata['yAxis']['max_auto_post_num'] < $v['auto_post_num'])
                $chartdata['yAxis']['max_auto_post_num'] = $v['auto_post_num'];//最大自动推荐数量
        }
        $chartdata['legend'] = ['手动推荐','自动推荐'];//分类
        $chartdata['xAxis'] = $data['day'];//X轴值
        $series= ['normal'=>['label'=>['show'=>true,'position'=>'top']]];
        $chartdata['series'][] = ['name'=>$chartdata['legend'][0],'type'=>'line','itemStyle'=>$series,'data'=>$data['recommend_post_num']];//分类1值
        $chartdata['series'][] = ['name'=>$chartdata['legend'][1],'type'=>'line','itemStyle'=>$series,'data'=>$data['auto_post_num']];//分类2值
        return JsonService::success('ok',$chartdata);
    }


    /**
     * -新增浏览数展示
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function new_num_echart()
    {
        header('Content-type:text/json');
        $cycle=osx_input('cycle','thirtyday','text');//默认30天
        $channel_id=osx_input('channel_id',0,'intval');
        if(!$channel_id){
            return JsonService::fail('非法请求');
        }
        $datalist = [];
        switch ($cycle){
            case 'thirtyday':
                for($i=-30;$i <= 0;$i++){
                    $datalist[date('m-d',strtotime($i.' day'))] = date('m-d',strtotime($i.' day'));
                }
                $datebefor = date('Y-m-d',strtotime('-30 day'));
                $dateafter = date('Y-m-d');

                $log_type=1;
                $field_time="FROM_UNIXTIME(create_time,'%m-%d')";
                $y_name='日浏览量';


                $this_key=date('m-d');
                $time_start = strtotime(date('Y-m-d'));
                break;
            case 'week':
                $week_num = date("W", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
                $year_start = strtotime(date('Y-01-01'));
                // 判断第一天是否为第一周的开始
                if (intval(date('W',$year_start))===1){
                    $start = $year_start;//把第一天做为第一周的开始
                }else{
                    $start = strtotime('+1 monday',$year_start);//把第一个周一作为开始
                }
                for($i=0;$i < $week_num;$i++){
                    if($i==0){
                        $key=$start;
                    }else{
                        $key=strtotime($i.' monday',$start);
                    }
                    $datalist[$key] = '第'.($i+1).'周';
                }
                $datebefor = date('Y-01-01');//本年第一天
                $dateafter = date('Y-m-d');

                $log_type=2;
                $field_time="create_time";
                $y_name='周浏览量';

                $this_key=strtotime(date('Y-m-d',time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $time_start = $this_key;
                break;
            case 'month':
                $datalist=array('01'=>'一月','02'=>'二月','03'=>'三月','04'=>'四月','05'=>'五月','06'=>'六月','07'=>'七月','08'=>'八月','09'=>'九月','10'=>'十月','11'=>'十一月','12'=>'十二月');


                $datebefor = date('Y-01-01');
                $dateafter = date('Y-m-d');

                $log_type=3;
                $field_time="FROM_UNIXTIME(create_time,'%m')";
                $y_name='月浏览量';

                $this_key=date('m');
                $time_start = strtotime(date('Y-m-01'));
                break;
            case 'year':
                $this_year=date('y');
                $start_year=$this_year-9;
                for($i=0;$i < 10;$i++){
                    $datalist[($start_year+$i)] = (2000+$start_year+$i).'年';
                }

                $datebefor = date($start_year.'-01-01');
                $dateafter = date('Y-m-d');

                $log_type=4;
                $field_time="FROM_UNIXTIME(create_time,'%y')";
                $y_name='年浏览量';

                $this_key=date('y');
                $time_start = strtotime(date('Y-01-01'));
                break;
            default:
                break;
        }
        $view_num_list = ChannelCountView::where('create_time','between time',[$datebefor,$dateafter])
            ->where('channel_id',$channel_id)
            ->where('type',$log_type)
            ->field($field_time." as day,view_num")
            ->order('create_time asc')
            ->select();
        if(count($view_num_list)){
            $view_num_list=$view_num_list->toArray();
            $view_num_list=array_combine(array_column($view_num_list,'day'),$view_num_list);
        }
        $cycle_list = [];
        if(count($view_num_list)){
            foreach ($datalist as $key=>$dd){
                $cycle_list[$key]['day']=$dd;
                if(!empty($view_num_list[$key])){
                    $cycle_list[$key]['view_num'] = $view_num_list[$key]['view_num'];
                }else{
                    $cycle_list[$key]['view_num'] = 0;
                }
            }
        }else{
            foreach ($datalist as $key=>$dd){
                $cycle_list[$key]['day']=$dd;
                $cycle_list[$key]['view_num'] = 0;
            }
        }
        $cycle_list[$this_key]['view_num']=ChannelCountViewLog::where('channel_id',$channel_id)
            ->where('create_time','egt',$time_start)->count();
        $chartdata = [];
        $data = [];//临时
        $chartdata['yAxis']['max_view_num'] = 0;//最大新增浏览数量
        foreach ($cycle_list as $k=>$v){
            $data['day'][] = $v['day'];
            $data['view_num'][] = $v['view_num'];
            if($chartdata['yAxis']['max_view_num'] < $v['view_num'])
                $chartdata['yAxis']['max_view_num'] = $v['view_num'];//最大新增浏览数量
        }
        $chartdata['xAxis'] = $data['day'];//X轴值
        $series= ['normal'=>['label'=>['show'=>true,'position'=>'top']]];
        $chartdata['series'][] = ['name'=>$y_name,'type'=>'line','itemStyle'=>$series,'data'=>$data['view_num']];//分类1值
        return JsonService::success('ok',$chartdata);
    }

    /**
     * -内容数排行
     * @author zzl(zzl@dianyun.ren)
     * @date 2020-7
     */
    public function content_rank()
    {
        $channel_list=Channel::getAllChannelList();
        foreach ($channel_list as &$val){
            $val['count_num']=ChannelPost::where('channel_id',$val['id'])
                ->where('status',1)->where('is_hide',0)->where('deadline','>',time())->count();
        }
        unset($val);
        $count_num=array_column($channel_list,'count_num');
        $ids = array_column($channel_list,'id');

        array_multisort($count_num, SORT_DESC, $ids, $channel_list);
        $new_channel_list=[];
        $i=1;
        foreach ($channel_list as $val){
            $new_channel_list[$i]=$val;
            $i++;
        }
        unset($val);
        return JsonService::success($new_channel_list);
    }

    public function view_rank()
    {
        $type=osx_input('type',1,'intval');
        switch ($type){
            case 1://总浏览量
                $start_time=0;
                $end_time=time();
                break;
            case 2://昨日新增浏览量
                $start_time=strtotime(date('Y-m-d',strtotime('-1 day')));
                $end_time=strtotime(date('Y-m-d'))-1;
                break;
            case 3://7日新增浏览量
                $start_time=strtotime(date('Y-m-d',strtotime('-7 day')));
                $end_time=time();
                break;
            case 4://30日新增浏览量
                $start_time=strtotime(date('Y-m-d',strtotime('-30 day')));
                $end_time=time();
                break;
        }
        $channel_list=Channel::getAllChannelList();
        foreach ($channel_list as &$val){
            $val['count_num']=ChannelCountViewLog::where('channel_id',$val['id'])
                ->where('create_time','between',[$start_time,$end_time])->count();
        }
        unset($val);
        $count_num=array_column($channel_list,'count_num');
        $ids = array_column($channel_list,'id');

        array_multisort($count_num, SORT_DESC, $ids, $channel_list);
        $new_channel_list=[];
        $i=1;
        foreach ($channel_list as $val){
            $new_channel_list[$i]=$val;
            $i++;
        }
        unset($val);
        return JsonService::success($new_channel_list);
    }
}