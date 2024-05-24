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
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    .createFollowCheck input[type="number"] {
        -moz-appearance: textfield;
    }
    .common-input{
        margin-left: 10px;
        width: 300px;
        margin-right: 5px;
    }
</style>
{/block}
{block name="content"}
<style>
    .zzl_page_list{
        overflow-x: auto;
        overflow-y: hidden;
    }
    .zzl_page_list_content{
        min-width: 1200px;
    }
    .zzl_page_list_child{
        overflow: hidden;
    }
</style>
<div class="row zzl_page_list" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12 zzl_page_list_content" style="background-color: #fff">

        <div class="layui-card-header">
            {if condition="$channel['id'] neq 0"}
            {$channel['title']} /
            {/if}
            {$top_title}
        </div>
        <form id="form" class="form-horizontal zzl_page_list_child" style="margin-top: 50px;">
            <style>
                .input_label .checkbox_label{
                    padding-top: 0;
                    line-height: 30px;
                    margin-right: 30px;
                }
                .input_label .checkbox_label input{
                    margin-top: 7px;
                    width: 16px;
                    height: 16px;
                    margin-left: -20px;
                    position: absolute;
                }
                .right_input {
                    width: 150px;
                    display: inline-block;
                    margin-bottom:20px ;
                    margin-left: 10px;
                }
                .bind_user_label{
                    border: 1px solid #e5e6e7;
                    height: 38px;
                    line-height: 36px;
                    overflow: hidden;
                    margin-left: -15px;
                }
                .bind_user_label span{
                    display: inline-block;
                    line-height: 20px;
                    padding: 2px 10px;
                    margin-right: 5px;
                    border-radius: 5px;
                    color: #848484;
                    font-weight: 400;
                    border: 1px solid#cecece;
                }
            </style>
            <input type="hidden" value="{$channel['id']}" name="id">
            <div class="form-group">
                <label class="col-sm-12" style="padding-top: 0;margin-left: 30px;border-left: 4px solid #0ca6f2;line-height: 30px">基础设置</label>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道名称:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" required maxlength="4" name="title" id="title" placeholder="请输入内容（限4字以内）" value="{$channel['title']}">
                </div>
            </div>
            <div class="form-group">
                <label for="intor" class="col-sm-2 col-md-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道说明:</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="intor" id="intor" required maxlength="140" rows="5" placeholder="请输入说明信息">{$channel['intor']}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="logo" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;"> </span>频道封面:<br/>(40*40)</label>
                <div class="col-sm-6">
                    <input type="file" class="upload" name="logo_input" style="display: none;" id="logo_input" />
                    <a style="display: block;width: 102px;border: 1px solid #E5E6E7;margin-left: 10px;" class="btn-sm add_image upload_span" >
                        {if condition="$channel['logo'] neq '' "}
                        <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('{$channel.logo}')">
                            <input value="{$channel['logo']}" type="hidden" id="logo" name="logo">
                        </div>
                        {else/}
                        <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                            <input value="" type="hidden" id="logo" name="logo">
                        </div>
                        {/if}
                    </a>
                </div>
            </div>
            <div class="form-group">
                <label for="admin_user" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道管理员:</label>
                <div class="col-sm-6">
                    <input type="hidden" name="admin_user" id="admin_user" value="{$channel['admin_user']}" required>
                    <div class="col-sm-12">
                        <label class="col-sm-9 bind_user_label now_bind_user">
                            {if condition="!$channel['admin_user']"}
                            <div style="color: #bbb3b3;font-weight: 400">点击右侧按钮选择</div>
                            {else/}
                            {volist name="channel['admin_user_list']" id="one_admin_user"}
                            <span>{$one_admin_user}</span>
                            {/volist}
                            {/if}
                        </label>
                        <button type="button" class="btn btn-w-m btn-info bind-user col-sm-2" style="height: 38px;">选择用户</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="post_type" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道推送形式:</label>
                <div class="col-sm-6 input_label">
                    <label class="checkbox-inline checkbox_label">
                        <input type="checkbox" id="post_type1" name="post_type[]" value="1"> 自动推送
                    </label>
                    <label class="checkbox-inline checkbox_label">
                        <input type="checkbox" id="post_type2" name="post_type[]" value="2"> 手动推送
                    </label>
                </div>
            </div>



            <div id="user_push_set_block" style="display: none">
                <div class="form-group">
                    <label class="col-sm-12" style="padding-top: 0;margin-left: 30px;border-left: 4px solid #0ca6f2;line-height: 30px">手动推荐规则</label>
                </div>
                <div class="recommend_block" style="padding: 0 20px;">
                    <div class="form-group">
                        <label for="post_audit" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道审核:</label>
                        <div class="col-sm-6 input_label">
                            <label class="checkbox-inline checkbox_label">
                                <input type="radio" id="post_audit1" name="post_audit" value="1"> 开启
                            </label>
                            <label class="checkbox-inline checkbox_label">
                                <input type="radio" id="post_audit0" name="post_audit" value="0"> 关闭
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="post_intor" class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>频道投稿说明:</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="post_intor" id="post_intor" required maxlength="140" rows="5" placeholder="请输入说明信息">{$channel['post_intor']}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div id="auto_push_set_block" style="display: none">
                <div class="form-group">
                    <label class="col-sm-12" style="padding-top: 0;margin-left: 30px;border-left: 4px solid #0ca6f2;line-height: 30px">自动推荐规则</label>
                </div>
                <div class="recommend_block" style="padding: 0 20px;">
                    <div class="form-group">
                        <div class="col-sm-2" style="position: relative;text-align: right;margin-bottom: 80px;">
                            <img src="{$system_img}" style="width: 70px;height: 70px"/>
                            <div  style="position: absolute;left: 100%;top: 100%;width: 400px;text-align: left;margin-left: -85px;line-height: 25px;margin-top: 10px;">
                                <div style="font-weight: 600;color: #676a75">对接社区</div>
                                <div style="color: #9999a5">自动对接社区特定内容，并且设定相关规则的触发条件</div>
                            </div>
                        </div>
                        <div class="col-sm-6" style="height: 20px">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>来源设置:</label>
                        <div class="col-sm-8" style="padding-top: 40px;">
                            <div class="form-group input_label">
                                <label class="col-sm-2 checkbox-inline checkbox_label" style="margin-right: 0;text-align: right;line-height: 40px;">
                                    <input type="radio" id="from_type0" name="from_type" value="0" style="margin-top: 12px"> 来自版块
                                </label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="from_forum_ids" id="from_forum_ids" required value="{$channel['from_forum_ids']}">
                                    <div class="col-sm-12">
                                        <label class="col-sm-9 bind_user_label now_bind_forum">
                                            {if condition="!$channel['from_forum_ids']"}
                                            <div style="color: #bbb3b3;font-weight: 400">点击右侧按钮选择</div>
                                            {else/}
                                            {volist name="channel['from_forum_list']" id="one_from_forum"}
                                            <span>{$one_from_forum}</span>
                                            {/volist}
                                            {/if}
                                        </label>
                                        <button type="button" class="btn btn-w-m btn-info bind-forum col-sm-2" style="height: 38px;">选择版块</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group input_label">
                                <label class="col-sm-2 checkbox-inline checkbox_label" style="margin-right: 0;text-align: right;line-height: 40px;">
                                    <input type="radio" id="from_type1" name="from_type" value="1" style="margin-top: 12px"> 来自用户
                                </label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="from_user_ids" id="from_user_ids" required value="{$channel['from_user_ids']}">
                                    <div class="col-sm-12">
                                        <label class="col-sm-9 bind_user_label now_bind_from_user">
                                            {if condition="!$channel['from_user_ids']"}
                                            <div style="color: #bbb3b3;font-weight: 400">点击右侧按钮选择</div>
                                            {else/}
                                            {volist name="channel['from_user_list']" id="one_from_user"}
                                            <span>{$one_from_user}</span>
                                            {/volist}
                                            {/if}
                                        </label>
                                        <button type="button" class="btn btn-w-m btn-info bind-from-user col-sm-2" style="height: 38px;">绑定用户</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group input_label">
                                <label class="col-sm-2 checkbox-inline checkbox_label" style="margin-right: 0;text-align: right;line-height: 40px;">
                                    <input type="radio" id="from_type2" name="from_type" value="2" style="margin-top: 12px"> 来自全站
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>条件设置:</label>
                        <div class="col-sm-8" style="padding-top: 40px;">
                            <div class="form-group">
                                <label for="condition_post_hot_type" class="col-sm-2 control-label">帖子热度</label>
                                <div class="col-sm-8 input_label">
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="radio" id="condition_post_hot_type1" name="condition_post_hot_type" value="1"> 同时满足三项
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="radio" id="condition_post_hot_type2" name="condition_post_hot_type" value="2"> 满足其中一项
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <div>1.评论数大于<input type="text" name="condition_post_hot_comment" class="form-control right_input" maxlength="6" placeholder="请输入数字" value="{$channel['condition_post_hot_comment']}"></div>
                                    <div>2.阅读数大于<input type="text" name="condition_post_hot_read" class="form-control right_input" maxlength="6" placeholder="请输入数字" value="{$channel['condition_post_hot_read']}"></div>
                                    <div>3.点赞数大于<input type="text" name="condition_post_hot_support" class="form-control right_input" maxlength="6" placeholder="请输入数字" value="{$channel['condition_post_hot_support']}"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="condition_post_type" class="col-sm-2 control-label">帖子类型</label>
                                <div class="col-sm-8 input_label">
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="radio" id="condition_post_type0" name="condition_post_type" value="0"> 全部
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="radio" id="condition_post_type1" name="condition_post_type" value="1"> 只取精华帖
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="radio" id="condition_post_type2" name="condition_post_type" value="2"> 只取置顶帖
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="condition_post_content" class="col-sm-2 control-label">帖子内容</label>
                                <div class="col-sm-8 input_label">
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="checkbox" id="condition_post_content1" name="condition_post_content[]" value="1"> 帖子
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="checkbox" id="condition_post_content2" name="condition_post_content[]" value="2"> 视频
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="checkbox" id="condition_post_content3" name="condition_post_content[]" value="3"> 资讯
                                    </label>
                                    <label class="checkbox-inline checkbox_label">
                                        <input type="checkbox" id="condition_post_content4" name="condition_post_content[]" value="4"> 动态
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="condition_post_send_time" class="col-sm-2 control-label">发布时间</label>
                                <div class="col-sm-6">
                                    <select name="condition_post_send_time" id="condition_post_send_time" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="1">无限制</option>
                                        <option value="2">24小时</option>
                                        <option value="3">3天</option>
                                        <option value="4">7天</option>
                                        <option value="5">30天</option>
                                        <option value="6">180天</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="condition_post_comment_time" class="col-sm-2 control-label">最后回复时间</label>
                                <div class="col-sm-6">
                                    <select name="condition_post_comment_time" id="condition_post_comment_time" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="1">无限制</option>
                                        <option value="2">24小时</option>
                                        <option value="3">3天</option>
                                        <option value="4">7天</option>
                                        <option value="5">30天</option>
                                        <option value="6">180天</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="condition_post_update_time" class="col-sm-2 control-label">最后修改时间</label>
                                <div class="col-sm-6">
                                    <select name="condition_post_update_time" id="condition_post_update_time" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="1">无限制</option>
                                        <option value="2">24小时</option>
                                        <option value="3">3天</option>
                                        <option value="4">7天</option>
                                        <option value="5">30天</option>
                                        <option value="6">180天</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>排序设置:</label>
                        <div class="col-sm-8" style="padding-top: 40px;">
                            <div class="form-group">
                                <label for="list_sort_type" class="col-sm-2 control-label">排序方式</label>
                                <div class="col-sm-6">
                                    <select name="list_sort_type" id="list_sort_type" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="1">按点赞数目倒序</option>
                                        <option value="2">按评论数目倒序</option>
                                        <option value="3">按收藏数目倒序</option>
                                        <option value="4">按发布时间倒序</option>
                                        <option value="5">按回复时间倒序</option>
                                        <option value="6">按修改时间倒序</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red;margin-right: 5px;">*</span>性能设置:</label>
                        <div class="col-sm-8" style="padding-top: 40px;">
                            <div class="form-group">
                                <label for="list_page_limit" class="col-sm-2 control-label">单页数量<br/>（<100）</label>
                                <div class="col-sm-4">
                                    <select name="list_page_limit" id="list_page_limit" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="自定义">自定义</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" maxlength="3" name="list_page_limit_input" id="list_page_limit_input" placeholder="请输入数字" value="{$channel['list_page_limit_input']}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="list_update_interval" class="col-sm-2 control-label">数据刷新率<br/>（<24小时）</label>
                                <div class="col-sm-4">
                                    <select name="list_update_interval" id="list_update_interval" class="form-control">
                                        <option value="">--请选择--</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="自定义">自定义</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" maxlength="2" name="list_update_interval_input" id="list_update_interval_input" placeholder="请输入数字" value="{$channel['list_update_interval_input']}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <hr/>
        <div class="col-sm-offset-2">
            <div class="col-sm-8" style="text-align: center">
                <button type="button" id="save_btn" class="btn btn-primary" style="padding: 10px 50px;margin: 20px 15px 44px;background-color: #0ca6f2; ">提交</button>
                <button type="button" onclick="history.go(-1);" class="btn btn-default" style="padding: 10px 50px;margin: 20px 15px 44px;background-color: #fff;border: 1px solid #0ca6f2;color: #0ca6f2; ">取消</button>
            </div>
        </div>
    </div>
</div>

<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
{/block}
{block name="script"}
<script>
    var channel_id="{$channel['id']}";

    /**
     * 上传图片
     * */
    $('.upload_span').on('click',function (e) {
        createFrame('选择图片','{:Url('widget.images/index')}?fodder=logo_input');
    })
    function changeIMG(index,pic){
        $(".image_img").css('background-image',"url("+pic+")");
        $(".active").css('background-image',"url("+pic+")");
        $('#logo').val(pic);
    };
    function createFrame(title,src,opt){
        opt === undefined && (opt = {});
        return layer.open({
            type: 2,
            title:title,
            area: [(opt.w || 700)+'px', (opt.h || 650)+'px'],
            fixed: false, //不固定
            maxmin: true,
            moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
            anim:5,//出场动画 isOutAnim bool 关闭动画
            offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
            shade:0,//遮罩
            resize:true,//是否允许拉伸
            content: src,//内容
            move:'.layui-layer-title'
        });
    }
    /**
     * 上传图片 end
     * */

    $(".bind-user").on("click", function () {
        $eb.createModalFrame("绑定频道管理员", '{:Url('bind_user_vim')}?channel_id='+channel_id,{w: 800, h: 400})
    });
    $(".bind-from-user").on("click", function () {
        $eb.createModalFrame("自动推荐来源-绑定用户", '{:Url('bind_from_user_vim')}?channel_id='+channel_id,{w: 800, h: 400})
    });
    $(".bind-forum").on("click", function () {
        $eb.createModalFrame("自动推荐来源-绑定版块", '{:Url('bind_from_forum_vim')}?channel_id='+channel_id,{w: 800, h: 400})
    });

    window.addEventListener("storage", function (e) {
        if (e.key === "bind_usernames") {
            var nicknames=JSON.parse(e.newValue);
            var user_html='';
            $(".now_bind_user").empty();
            for(var i=0;i<100;i++){
                if(nicknames[i]==undefined) {
                    break;
                }
                user_html='<span>'+nicknames[i]+'</span>';
                $(".now_bind_user").append(user_html);
            }
        } else if (e.key === "bind_userIds") {
            var userIds=JSON.parse(e.newValue);
            if(userIds[0]!=undefined){
                $('#admin_user').val(userIds.join(","));
            }
        }else if(e.key === "bind_from_usernames"){
            var nicknames=JSON.parse(e.newValue);
            var user_html='';
            $(".now_bind_from_user").empty();
            for(var i=0;i<100;i++){
                if(nicknames[i]==undefined) {
                    break;
                }
                user_html='<span>'+nicknames[i]+'</span>';
                $(".now_bind_from_user").append(user_html);
            }
        } else if (e.key === "bind_from_userIds") {
            var userIds=JSON.parse(e.newValue);
            if(userIds[0]!=undefined){
                $('#from_user_ids').val(userIds.join(","));
            }
        }else if(e.key === "bind_from_forum_names"){
            var forum_names=JSON.parse(e.newValue);
            var forum_html='';
            $(".now_bind_forum").empty();
            for(var i=0;i<100;i++){
                if(forum_names[i]==undefined) {
                    break;
                }
                forum_html='<span>'+forum_names[i]+'</span>';
                $(".now_bind_forum").append(forum_html);
            }
        } else if (e.key === "bind_from_forum_ids") {
            var forum_ids=JSON.parse(e.newValue);
            if(forum_ids[0]!=undefined){
                $('#from_forum_ids').val(forum_ids.join(","));
            }
        }
    });
    var clear_storage=function () {
        window.localStorage.removeItem("bind_usernames");
        window.localStorage.removeItem("bind_userIds");

        window.localStorage.removeItem("bind_from_usernames");
        window.localStorage.removeItem("bind_from_userIds");

        window.localStorage.removeItem("bind_from_forum_names");
        window.localStorage.removeItem("bind_from_forum_ids");
    }

    var check_show_hide=function () {
        if($('#list_page_limit').val()=='自定义'){
            $('#list_page_limit_input').show();
        }else{
            $('#list_page_limit_input').hide();
        }
        if($('#list_update_interval').val()=='自定义'){
            $('#list_update_interval_input').show();
        }else{
            $('#list_update_interval_input').hide();
        }
        if($('#post_type1').is(':checked')){
            $('#auto_push_set_block').show();
        }else{
            $('#auto_push_set_block').hide();
        }
        if($('#post_type2').is(':checked')){
            $('#user_push_set_block').show();
        }else{
            $('#user_push_set_block').hide();
        }
    }

    $(function () {
        $('#list_page_limit').change(function () {
            check_show_hide();
        })
        $('#list_update_interval').change(function () {
            check_show_hide();
        })
        $('#post_type1').change(function () {
            check_show_hide();
        });
        $('#post_type2').change(function () {
            check_show_hide();
        });

        //初始化默认值，顺序不能改start
        if("{$channel['post_type1']}"==1){
            $('#post_type1').attr("checked",true);
        }
        if("{$channel['post_type2']}"==1){
            $('#post_type2').attr("checked",true);
        }
        $('#post_audit'+"{$channel['post_audit']}").attr("checked",true);
        $('#from_type'+"{$channel['from_type']}").attr("checked",true);
        $('#condition_post_hot_type'+"{$channel['condition_post_hot_type']}").attr("checked",true);
        $('#condition_post_type'+"{$channel['condition_post_type']}").attr("checked",true);
        if("{$channel['condition_post_content1']}"==1){
            $('#condition_post_content1').attr("checked",true);
        }
        if("{$channel['condition_post_content2']}"==1){
            $('#condition_post_content2').attr("checked",true);
        }
        if("{$channel['condition_post_content3']}"==1){
            $('#condition_post_content3').attr("checked",true);
        }
        if("{$channel['condition_post_content4']}"==1){
            $('#condition_post_content4').attr("checked",true);
        }
        $('#list_page_limit').val("{$channel['list_page_limit']}");
        $('#list_update_interval').val("{$channel['list_update_interval']}");
        check_show_hide();
        //初始化默认值，顺序不能改end

        //初始化其他默认值，顺序可改start
        $('#condition_post_send_time').val("{$channel['condition_post_send_time']}");
        $('#condition_post_comment_time').val("{$channel['condition_post_comment_time']}");
        $('#condition_post_update_time').val("{$channel['condition_post_update_time']}");
        $('#list_sort_type').val("{$channel['list_sort_type']}");
        //初始化其他默认值，顺序可改end

        //初始化localstorage
        clear_storage();
        //初始化localstorage end

        $('#save_btn').on('click',function(){
            clear_storage();

            var list=$('#form').serialize();
            $.ajax({
                url:"{:Url('saveChannelData')}",
                data:list,
                type:'post',
                dataType:'json',
                success:function(re){
                    if(re.code == 200){
                        $eb.message('success',re.msg);
                        setTimeout(function () {
                            location.href=re.data.url;
                        },2000);
                    }else{
                        $eb.message('error',re.msg);
                    }
                }
            })
        });
    });

</script>
{/block}