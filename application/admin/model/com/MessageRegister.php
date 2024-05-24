<?php
/**
 *
 * @author: cyx<cyx@ourstu.com>
 * @day: 2019/4/12
 */

namespace app\admin\model\com;

use service\PHPExcelService;
use think\Db;
use traits\ModelTrait;
use basic\ModelBasic;
use service\UtilService;
use app\admin\model\user\User as UserModel;
use app\commonapi\controller\Sensitive;
use service\JsonService;

/**
 * 版块 model
 * Class ComForum
 * @package app\admin\model\com
 */
class MessageRegister extends ModelBasic
{
    use ModelTrait;

    public static function createMessage($data){
        $data['content']=html($data['content']);
        $thread_id=self::insert($data);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function delete_ann($id){
        $thread_id=self::where('id',$id)->update(['status' => -1]);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function getOne($id){
        $res=self::where('id',$id)->find()->toArray();
        return $res;
    }

    public static function close($id){
        $thread_id=self::where('id',$id)->update(['status' => 0]);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    public static function open($id){
        $thread_id=self::where('id',$id)->update(['status' => 1,'send_time'=>time()]);
        if($thread_id){
            return 1;
        }else{
            return 0;
        }
    }

    /*
     * 获取通知列表
     * @param $where array
     * @return array
     *
     */
    public static function MessageRegisterList($where)
    {
        $data = ($data = self::where('id',1)->page((int)$where['page'], (int)$where['limit'])->select()) && count($data) ? $data->toArray() : [];
        //普通列表
        foreach ($data as &$item){
            $item['content'] = json_decode($item['content']);
            $item['nickname']=UserModel::where('uid',$item['author_uid'])->value('nickname');
            $item['avatar']=UserModel::where('uid',$item['author_uid'])->value('avatar');
            $item['send_time']=time_format($item['send_time']);
            $item['create_time']=time_format($item['create_time']);
        }
        $count = self::where('id',1)->count();
        return compact('count', 'data');
    }


    public static function editThread($data)
    {
        $data['content']=html($data['content']);
        $data['content']=self::_limitPictureCount($data['content']);
        $data['content']=html($data['content']);
        $sensitive1=Sensitive::sensitive($data['title'],'后台帖子');
        if($sensitive1['status']==0){
            JsonService::fail('标题包含敏感词"'.$sensitive1['word'].'",请检查后重新输入');
        }
        $content=text($data['content']);
        $sensitive2=Sensitive::sensitive($content,'后台帖子');
        if($sensitive2['status']==0){
            JsonService::fail('内容包含敏感词"'.$sensitive2['word'].'",请检查后重新输入');
        }
        if($data['type']==1){
            if($data['image']==''&&$data['is_auto_image']==1&&$data['from']=='HouTai'){
                $data['image']=self::_contentToImage($data['content']);
                if(!$data['image']){
                    $data['image']='';
                }else{
                    if(is_array($data['image'])){
                        $data['image']=json_encode($data['image']);
                    }
                }
            }else{
                if($data['image']){
                    $data['image']  = explode(",",$data['image']);
                    $data['image']=json_encode($data['image']);
                }
            }
        }else{
            $data['image']=self::_contentToImage($data['content']);
            if(!$data['image']){
                $data['image']='';
            }else{
                if(is_array($data['image'])){
                    $data['image']=json_encode($data['image']);
                }
            }
        }
        unset($data['is_auto_image']);
        $thread_data=$data;
        if($thread_data['summary']==''||in_array($thread_data['type'],array(1,6))){
            if($thread_data['is_weibo']==1){
                $thread_data['summary'] = $thread_data['content']; //获取内容的前60个字符作为摘要
            }else{
                $thread_data['summary'] = mb_substr(text(strip_tags($thread_data['content'], '<p></p><br><span></span>')),0,60,'UTF-8'); //获取内容的前60个字符作为摘要
            }
        }else{
            $sensitive3=Sensitive::sensitive($thread_data['summary'],'后台帖子');
            if($sensitive3['status']==0){
                JsonService::fail('摘要包含敏感词"'.$sensitive3['word'].'",请检查后重新输入');
            }
        }
        $thread_data['content']=json_encode($thread_data['content']);
        $post_data=[
            'fid'=>$data['fid'],
            'author_uid'=>$data['author_uid'],
            'title'=>$data['title'],
            'content'=>$data['content'],
            'image'=>$data['image'],
        ];
        $result=ComPost::where('tid',$data['id'])->where('is_thread',1)->update($post_data);
        $res=self::where('id',$data['id'])->update($thread_data);
        if ($result!==false && $res!==false) {
            return true;
        }else{
            return false;
        }

    }

    /**
     * 图片限制
     * @param $content
     * @return mixed
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    private static function _limitPictureCount($content){
        //默认最多显示10张图片
        $maxImageCount = '40';

        //正则表达式配置
        $beginMark = 'BEGIN0000hfuidafoidsjfiadosj';
        $endMark = 'END0000fjidoajfdsiofjdiofjasid';
        $imageRegex = '/<img(.*?)\\>/i';
        $reverseRegex = "/{$beginMark}(.*?){$endMark}/i";

        //如果图片数量不够多，那就不用额外处理了。
        $imageCount = preg_match_all($imageRegex, $content);
        if ($imageCount <= $maxImageCount) {
            return $content;
        }

        //清除伪造图片
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //临时替换图片来保留前$maxImageCount张图片
        $content = preg_replace($imageRegex, "{$beginMark}$1{$endMark}", $content, $maxImageCount);

        //替换多余的图片
        $content = preg_replace($imageRegex, "", $content);

        //将替换的东西替换回来
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //返回结果
        return $content;
    }

    /**
     * 将content中的图片信息提取出来,取前三张图
     * @param $content
     * @return array|null
     * @author 郑钟良(zzl@ourstu.com)
     * @date slf
     */
    private static function _contentToImage($content)
    {
        $content = htmlspecialchars_decode($content);  //将编码过的字符转回html标签
        preg_match_all('/<img[^>]*\>/', $content, $match);  //获取图片标签
        if (count($match[0])>1) {  //若有多张图片，循环处理
            foreach ($match[0] as $k => &$v) {
                if($k==9){
                    break;
                }
                $img = substr(substr($v, 10), 0, -2);
                //从10开始才是src路径，然后再截取去掉最后的标签符号
                $length = "-" . strlen(strstr($v, 'title'));
                //组件传上来的img标签里会自动有width属性，计算这部分长度然后也去掉
                $imgs[] = substr($img, 0, $length);
                //$imgs[] = $img;
                //去掉width属性，此时只剩下一个完整路径
            }
            unset($v);
        } else {  //单图处理
            foreach ($match[0] as $k => &$v) {
                if($k==9){
                    break;
                }
                $img = substr(substr($v, 10), 0, -2);
                $length = "-" . strlen(strstr($v, 'title'));
                //组件传上来的img标签里会自动有width属性，计算这部分长度然后也去掉
                $imgs = substr($img, 0, $length);
            }
            unset($v);
        }
        if ($match[0] == null) {
            $imgs = null;
        }
        return $imgs;
    }

}