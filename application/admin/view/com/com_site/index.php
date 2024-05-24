{extend name="public/container"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>

<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
    .common-input{
        margin-left: 10px;
        width: 300px;
        margin-right: 5px;
    }
    .layui-form-label{
        margin-right: 10px;
        font-weight: 600;
        color: #333;
        width: 116px;
        text-align: left;
    }

    .radio {
        margin: 0!important;
        padding-top: 8px;
    }
    .radio input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    .radio input[type="radio"] + .radio-label:before {
        content: '';
        background: #f4f4f4;
        border-radius: 100%;
        border: 1px solid #b4b4b4;
        display: inline-block;
        width: 1.4em;
        height: 1.4em;
        position: relative;
        top: -0.2em;
        margin-right: 1em;
        vertical-align: top;
        cursor: pointer;
        text-align: center;
        -webkit-transition: all 250ms ease;
        transition: all 250ms ease;
    }
    .radio input[type="radio"]:checked + .radio-label:before {
        background-color: #3197EE;
        box-shadow: inset 0 0 0 4px #f4f4f4;
    }
    .radio input[type="radio"]:focus + .radio-label:before {
        outline: none;
        border-color: #3197EE;
    }
    .radio input[type="radio"]:disabled + .radio-label:before {
        box-shadow: inset 0 0 0 4px #f4f4f4;
        border-color: #b4b4b4;
        background: #b4b4b4;
    }
    .radio input[type="radio"] + .radio-label:empty:before {
        margin-right: 0;
    }

    .big_title{
        padding-left: 10px;
        border-left: 3px solid #00aa00;
        font-size: 20px;
        margin:10px  0 ;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="layui-card-header">版块管理</div>
        <form class="form" style="margin: 20px" id="forum">
            <div class="big_title">
                自定义命名
            </div>
            <!--版块名称-->
            <div class="input-box" style="display: flex;align-items: center;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 116px;">版块名称</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="forum1" type="radio" name="forum_name" value="版块" style="margin-top: 0;" checked>
                    <label for="forum1" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">版块</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="forum2" type="radio" name="forum_name" value="圈子" style="margin-top: 0;">
                    <label for="forum2" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">圈子</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="forum3" type="radio" name="forum_name" value="群组" style="margin-top: 0;">
                    <label for="forum3" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">群组</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="forum4" type="radio" name="forum_name" value="部落" style="margin-top: 0;">
                    <label for="forum4" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">部落</label>
                </div>
            </div>
            <div style="display: flex;align-items: center;margin-left: 126px;margin-top: 20px">
                <div class="radio">
                    <input id="forum5" type="radio" name="forum_name" value="自定义" style="margin-top: 0;">
                    <label for="forum5" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                </div>
                <input id="forum_name_input" class="common-input form-control valid" value="" name="forum_name_zdy" type="text">
            </div>
            <!--版块名称 end-->
            <!--版主名称-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 116px;">版主名称</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="admin1" type="radio" name="user_name" value="版主" style="margin-top: 0;" checked>
                    <label for="admin1" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">版主</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="admin2" type="radio" name="user_name" value="圈主" style="margin-top: 0;">
                    <label for="admin2" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">圈主</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="admin3" type="radio" name="user_name" value="组长" style="margin-top: 0;">
                    <label for="admin3" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">组长</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="admin4" type="radio" name="user_name" value="酋长" style="margin-top: 0;">
                    <label for="admin4" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">酋长</label>
                </div>
            </div>
            <div style="display: flex;align-items: center;margin-left: 126px;margin-top: 20px">
                <div class="radio">
                    <input id="admin5" type="radio" name="user_name" value="自定义" style="margin-top: 0;">
                    <label for="admin5" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                </div>
                <input id="admin_name_input" class="common-input form-control valid" value="" name="user_name_zdy" type="text">
            </div>
            <!--版主名称 end-->

            <!--内容名称-->
            <div class="input-box" style="display:flex;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 116px;">内容名称</label>
                <div>
                    <!--帖子名称-->
                    <div class="input-box" style="display: flex;align-items: center;color: #333">
                        <label class="layui-form-label" for="" style="margin-bottom: 0;width: 60px;">帖子:</label>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin10" type="radio" name="com_thread_name" value="帖子" style="margin-top: 0;" {if condition="$data.com_thread_name eq '帖子'"}checked{/if}>
                            <label for="admin10" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">帖子</label>
                        </div>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin11" type="radio" name="com_thread_name" value="文章" style="margin-top: 0;" {if condition="$data.com_thread_name eq '文章'"}checked{/if}>
                            <label for="admin11" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">文章</label>
                        </div>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin12" type="radio" name="com_thread_name" value="长文" style="margin-top: 0;" {if condition="$data.com_thread_name eq '长文'"}checked{/if}>
                            <label for="admin12" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">长文</label>
                        </div>
                    </div>
                    <div style="display: flex;align-items: center;margin-left: 71px;margin-top: 20px;margin-right: 50px">
                        <div class="radio">
                            <input id="admin13" type="radio" name="com_thread_name" value="自定义" style="margin-top: 0;" {if condition="!in_array($data.com_thread_name,['帖子','文章','长文']) "}checked{/if}>
                            <label for="admin13" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                        </div>
                        <input id="admin_name_input" class="common-input form-control valid" name="com_thread_name_zdy" value="{if condition="!in_array($data.com_thread_name,['帖子','文章','长文']) "}{$data.com_thread_name}{/if}" type="text">
                    </div>
                    <!--帖子名称 end-->
                    <!--动态名称-->
                    <div class="input-box" style="display: flex;align-items: center;color: #333;margin-top: 15px">
                        <label class="layui-form-label" for="" style="margin-bottom: 0;width: 60px;">动态:</label>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin14" type="radio" name="weibo_name" value="动态" style="margin-top: 0;" {if condition="$data.weibo_name eq '动态'"}checked{/if}>
                            <label for="admin14" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">动态</label>
                        </div>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin15" type="radio" name="weibo_name" value="微博" style="margin-top: 0;" {if condition="$data.weibo_name eq '微博'"}checked{/if}>
                            <label for="admin15" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">微博</label>
                        </div>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin16" type="radio" name="weibo_name" value="短文" style="margin-top: 0;" {if condition="$data.weibo_name eq '短文'"}checked{/if}>
                            <label for="admin16" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">短文</label>
                        </div>
                    </div>
                    <div style="display: flex;align-items: center;margin-left: 71px;margin-top: 20px;margin-right: 50px">
                        <div class="radio">
                            <input id="admin22" type="radio" name="weibo_name" value="自定义" style="margin-top: 0;" {if condition="!in_array($data.weibo_name,['动态','微博','短文']) "}checked{/if}>
                            <label for="admin22" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                        </div>
                        <input id="admin_name_input" class="common-input form-control valid" value="{if condition="!in_array($data.weibo_name,['动态','微博','短文'])"}{$data.weibo_name}{/if}" name="weibo_name_zdy" type="text">
                    </div>
                    <!--动态名称 end-->
                    <!--资讯名称-->
                    <div class="input-box" style="display: flex;align-items: center;color: #333;margin-top: 15px">
                        <label class="layui-form-label" for="" style="margin-bottom: 0;width: 60px;">资讯:</label>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin17" type="radio" name="news_name" value="资讯" style="margin-top: 0;" {if condition="$data.news_name eq '资讯'"}checked{/if}>
                            <label for="admin17" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">资讯</label>
                        </div>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin18" type="radio" name="news_name" value="新闻" style="margin-top: 0;" {if condition="$data.news_name eq '新闻'"}checked{/if}>
                            <label for="admin18" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">新闻</label>
                        </div>
                    </div>
                    <div style="display: flex;align-items: center;margin-left: 71px;margin-top: 20px;margin-right: 50px">
                        <div class="radio">
                            <input id="admin21" type="radio" name="news_name" value="自定义" style="margin-top: 0;" {if condition="!in_array($data.news_name,['新闻','资讯']) "}checked{/if}>
                            <label for="admin21" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                        </div>
                        <input id="admin_name_input" class="common-input form-control valid" value="{if condition="!in_array($data.news_name,['新闻','资讯']) "}{$data.news_name}{/if}" name="news_name_zdy" type="text">
                    </div>
                    <!--资讯名称 end-->
                    <!--视频名称-->
                    <div class="input-box" style="display: flex;align-items: center;color: #333;margin-top: 15px">
                        <label class="layui-form-label" for="" style="margin-bottom: 0;width: 60px;">视频:</label>
                        <div class="radio" style="display: flex;align-items: center">
                            <input id="admin19" type="radio" name="video_name" value="视频" style="margin-top: 0;"  {if condition="$data.video_name eq '视频'"}checked{/if}>
                            <label for="admin19" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">视频</label>
                        </div>
                    </div>
                    <div style="display: flex;align-items: center;margin-left: 71px;margin-top: 20px;margin-right: 50px">
                        <div class="radio">
                            <input id="admin20" type="radio" name="video_name" value="自定义" style="margin-top: 0;"  {if condition="!in_array($data.video_name,['视频']) "}checked{/if}>
                            <label for="admin20" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 10px;font-weight: 500;line-height: 1;">自定义</label>
                        </div>
                        <input id="admin_name_input" class="common-input form-control valid" value="{if condition="!in_array($data.video_name,['视频']) "}{$data.video_name}{/if}" name="video_name_zdy" type="text">
                    </div>
                    <!--视频名称 end-->
                </div>
                <!--内容名称 end-->
            </div>


            <div class="big_title" style="margin-top: 50px">
                图标提示设置
            </div>
            <div class="log" style="color: #ccc">
                指设置社区帖子列表中符合条件的内容是否显示【新】、【热】、【精】、【荐】、【置顶】等图标
            </div>
            <!-- 新帖图标【新】提示-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">新帖图标【新】提示</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="new1" type="radio" name="new_icon" value="1" style="margin-top: 0;"  {if condition="$data.new_icon eq 1"}checked{/if}>
                    <label for="new1" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">开启</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="new2" type="radio" name="new_icon" value="0" style="margin-top: 0;"  {if condition="$data.new_icon eq 0"}checked{/if}>
                    <label for="new2" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">关闭</label>
                </div>
            </div>
            <!-- 新帖图标【新】提示 end-->
            <!-- 热帖图标【热】提示-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">热帖图标【热】提示</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot3" type="radio" name="hot_icon" value="1" style="margin-top: 0;"  {if condition="$data.hot_icon eq 1"}checked{/if}>
                    <label for="hot3" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">开启</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot4" type="radio" name="hot_icon" value="0" style="margin-top: 0;"  {if condition="$data.hot_icon eq 0"}checked{/if}>
                    <label for="hot4" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">关闭</label>
                </div>
            </div>
            <!-- 热帖图标【热】提示 end-->
            <!-- 精华图标【精】提示-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">精华图标【精】提示</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot5" type="radio" name="essence_icon" value="1" style="margin-top: 0;"  {if condition="$data.essence_icon eq 1"}checked{/if} >
                    <label for="hot5" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">开启</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot6" type="radio" name="essence_icon" value="0" style="margin-top: 0;"  {if condition="$data.essence_icon eq 0"}checked{/if}>
                    <label for="hot6" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">关闭</label>
                </div>
            </div>
            <!-- 精华图标【精】提示 en-->
            <!-- 推荐图标【荐】提示-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">推荐图标【荐】提示</label>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot1" type="radio" name="recommend_icon" value="1" style="margin-top: 0;" {if condition="$data.recommend_icon eq 1"}checked{/if}>
                    <label for="hot1" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">开启</label>
                </div>
                <div class="radio" style="display: flex;align-items: center">
                    <input id="hot2" type="radio" name="recommend_icon" value="0" style="margin-top: 0;"  {if condition="$data.recommend_icon eq 0"}checked{/if}>
                    <label for="hot2" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">关闭</label>
                </div>
            </div>
            <!--推荐图标【荐】提示 end-->
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">新帖有效期</label>
                <div>
                    <div style="display: flex">
                        <input id="new_num_input" class="common-input form-control valid" value="{$data.new_on}" type="text" name="new_on" style="margin-left: 24px"><span>小时</span>
                    </div>
                    <div style="color: #ccc;margin-left: 24px">即N小时内发布的内容，显示【新】图标标识</div>
                </div>
            </div>
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">热帖评论数阈值</label>
                <div>
                    <div style="display: flex">
                    <input id="num_input" class="common-input form-control valid" value="{$data.threshold}" name="threshold" type="text" style="margin-left: 24px;"><span>条</span>
                    </div>
                    <div style="color: #ccc;margin-left: 24px">即内容评论数（含楼中楼评论）超过N条，显示【热】图标标识</div>
                </div>

            </div>
            <div class="input-box" style="display: flex;align-items: center;margin-top: 20px;color: #333">
                <label class="layui-form-label" for="" style="margin-bottom: 0;width: 140px;">内容浏览量统计规则</label>
                <div style="flex-direction:column;display: flex">
                    <div class="radio" style="display: flex;align-items: center">
                        <input id="read_census1" type="radio" name="read_census" value="0" style="margin-top: 0;" checked>
                        <label for="read_census1" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">
                            按详情页阅读量为准(数据量相对较少，数据可信度高,适合以“帖子”等长文章内容为主的社区)
                        </label>
                    </div>
                    <div class="radio" style="display: flex;align-items: center">
                        <input id="read_census2" type="radio" name="read_census" value="1" style=" margin-top: 0;">
                        <label for="read_census2" class="radio-label" style="margin-bottom: 0;margin-left: 5px;margin-right: 50px;font-weight: 500;line-height: 1;">
                            按列表浏览量为准(数据量相对较大，有助于提升用户发帖积极性，较适合以‘动态’等端内容为主的社区)
                        </label>
                    </div>
                </div>
            </div>
            <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 40px 0 20px 116px;">
                保存
            </div>
        </form>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $(function () {
        var forumName = "{$data.forum_name}";
        if(forumName === "版块"){
            $("#forum1").prop("checked",true);
        }else if(forumName === "圈子"){
            $("#forum2").prop("checked",true);
        }else if(forumName === "群组"){
            $("#forum3").prop("checked",true);
        }else if(forumName === "部落"){
            $("#forum4").prop("checked",true);
        }else {
            $("#forum5").prop("checked",true);
            $("#forum_name_input").val(forumName);
        }
        var adminName = "{$data.user_name}";
        if(adminName === "版主"){
            $("#admin1").prop("checked",true);
        }else if(adminName === "圈主"){
            $("#admin2").prop("checked",true);
        }else if(adminName === "组长"){
            $("#admin3").prop("checked",true);
        }else if(adminName === "酋长"){
            $("#admin4").prop("checked",true);
        }else {
            $("#admin5").prop("checked",true);
            $("#admin_name_input").val(adminName);
        }

        var read_census = "{$data.read_census}";

        if(read_census === "1"){
            $("#read_census2").prop("checked",true);
        }else {
            $("#read_census1").prop("checked",true);
        }
    });
    $("#save_btn").on("click",function () {
        var list=$('#forum').serializeArray();
        for(var i=0;i<11;i+=2){
            if(list[i]['value']== '自定义'){
                list[i]['value']=list[i+1]['value'];
            }
        }
        $.ajax({
            url:"{:Url('update')}",
            data:list,
            type:'post',
            dataType:'json',
            success:function(re){
                if(re.code == 200){
                    $eb.message('success',re.msg);
                }else{
                    $eb.message('error',re.msg);
                }
            }
        })
    });
    function checkForumName() {
        var name = $("input[name='forum_name']:checked").val();
        if(name === "自定义"){
            return $("#forum_name_input").val();
        }else {
            return name;
        }
    }
    function checkAdminName() {
        var name = $("input[name='admin_name']:checked").val();
        if(name === "自定义"){
            return $("#admin_name_input").val();
        }else {
            return name;
        }
    }
</script>
{/block}
