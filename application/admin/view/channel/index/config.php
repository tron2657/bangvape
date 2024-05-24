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
    a{
        color: #333;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12 layui-col-md12" style="background-color: #fff">
        <div class="layui-tab layui-tab-brief" lay-filter="tab" style="margin-left: -15px;margin-top: -10px;">
            <ul class="layui-tab-title" style="background-color: white;top: 10px">
                <li lay-id="list" class="layui-this">
                    <a href="javascript:;">基础设置</a>
                </li>
                <li lay-id="list">
                    <a href="{:Url('channel.index/navSet')}">频道设置</a>
                </li>
            </ul>
        </div>
        <form id="form" class="form-horizontal" style="margin-top: 50px">

            <div class="form-group">
                <label class="col-sm-12" style="padding-top: 0;margin-left: 30px;border-left: 4px solid #0ca6f2;line-height: 30px">用户自定义频道设置</label>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" style="padding-top: 0;">用户自定义频道 ：</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="">
                            <!--单选按钮-->
                            <div class="radio i-checks" style="display:inline" >
                                <label class="" style="padding-left: 0;"  data-role="change_set" data-value="1">
                                    <div class="iradio_square-green " style="position: relative;">
                                        <div class=" checked" style="position: relative;">
                                            {if condition="$channel_config.channel_edit_page_open eq '1'"}
                                            <input type="radio" checked="checked" value="1" name="channel_edit_page_open" style="position: absolute; opacity: 0;">
                                            {else/}
                                            <input type="radio" value="1" name="channel_edit_page_open" style="position: absolute; opacity: 0;">
                                            {/if}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </div>
                                    <i></i> 开启
                                </label>
                            </div>
                            <div class="radio i-checks" style="display:inline"  >
                                <label class="" style="padding-left: 0;" data-role="change_set" data-value="0">
                                    <div class="iradio_square-green" style="position: relative;">
                                        <div class="" style="position: relative;">
                                            {if condition="$channel_config.channel_edit_page_open eq '0'"}
                                            <input type="radio" checked="checked" value="0" name="channel_edit_page_open" style="position: absolute; opacity: 0;">
                                            {else/}
                                            <input type="radio" value="0" name="channel_edit_page_open" style="position: absolute; opacity: 0;">
                                            {/if}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </div>
                                    <i></i> 关闭
                                </label>
                            </div>

                        </div>
                        <div class="">
                            <span class="help-block m-b-none">开启后支持用户自定义编辑频道导航栏，对频道进行排序、添加和移除操作（注：关闭后不再计算频道开启率）</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="channel_set"  {if condition="$channel_config.channel_edit_page_open eq '0'"} style="display: none" {/if}>
                <div class="form-group">
                    <label class="col-sm-12" style="padding-top: 0;margin-left: 30px;border-left: 4px solid #0ca6f2;line-height: 30px">频道引导设置</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top: 0;">首次登录频道引导 ：</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="">
                                <!--单选按钮-->
                                <div class="radio i-checks" style="display:inline">
                                    <label class="" style="padding-left: 0;">
                                        <div class="iradio_square-green " style="position: relative;">
                                            <div class=" checked" style="position: relative;">
                                                {if condition="$channel_config.channel_first_page_open eq '1'"}
                                                <input type="radio" checked="checked" value="1" name="channel_first_page_open" style="position: absolute; opacity: 0;">
                                                {else/}
                                                <input type="radio" value="1" name="channel_first_page_open" style="position: absolute; opacity: 0;">
                                                {/if}
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                            </div>
                                        </div>
                                        <i></i> 开启
                                    </label>
                                </div>
                                <div class="radio i-checks" style="display:inline">
                                    <label class="" style="padding-left: 0;">
                                        <div class="iradio_square-green" style="position: relative;">
                                            <div class="" style="position: relative;">
                                                {if condition="$channel_config.channel_first_page_open eq '0'"}
                                                <input type="radio" checked="checked" value="0" name="channel_first_page_open" style="position: absolute; opacity: 0;">
                                                {else/}
                                                <input type="radio" value="0" name="channel_first_page_open" style="position: absolute; opacity: 0;">
                                                {/if}
                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                            </div>
                                        </div>
                                        <i></i> 关闭
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <span class="help-block m-b-none"> 开启后新用户首次登录后显示频道引导页面，选择感兴趣的频道（若无可选频道，则直接跳过频道引导页）</span>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="form-group">-->
<!--                    <label class="col-sm-2 control-label" style="padding-top: 0;">是否支持跳过频道引导：</label>-->
<!--                    <div class="col-sm-10">-->
<!--                        <div class="row">-->
<!--                            <div class="">-->
<!--                                <!--单选按钮-->
<!--                                <div class="radio i-checks" style="display:inline">-->
<!--                                    <label class="" style="padding-left: 0;">-->
<!--                                        <div class="iradio_square-green " style="position: relative;">-->
<!--                                            <div class=" checked" style="position: relative;">-->
<!--                                                {if condition="$channel_config.channel_first_page_can_jump eq '1'"}-->
<!--                                                <input type="radio" checked="checked" value="1" name="channel_first_page_can_jump" style="position: absolute; opacity: 0;">-->
<!--                                                {else/}-->
<!--                                                <input type="radio" value="1" name="channel_first_page_can_jump" style="position: absolute; opacity: 0;">-->
<!--                                                {/if}-->
<!--                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <i></i> 开启-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="radio i-checks" style="display:inline">-->
<!--                                    <label class="" style="padding-left: 0;">-->
<!--                                        <div class="iradio_square-green" style="position: relative;">-->
<!--                                            <div class="" style="position: relative;">-->
<!--                                                {if condition="$channel_config.channel_first_page_can_jump eq '0'"}-->
<!--                                                <input type="radio" checked="checked" value="0" name="channel_first_page_can_jump" style="position: absolute; opacity: 0;">-->
<!--                                                {else/}-->
<!--                                                <input type="radio" value="0" name="channel_first_page_can_jump" style="position: absolute; opacity: 0;">-->
<!--                                                {/if}-->
<!--                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <i></i> 关闭-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--<!--                            <div class="col-md-8">-->
<!--<!--                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 关闭频道引导页面上不显示跳过按钮，用户必须选择频道后才能进入首页</span>-->
<!--<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </form>

        <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 40px 0 20px 116px;">
            保存
        </div>
    </div>
</div>

<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
{/block}
{block name="script"}
<script>
    var is_on;
    $().ready(function() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
    is_on = $('.chose-box').attr('data-open');

    if($('.chose-box').attr('data-open') == 0) {
        $('input[id="chose2"]').iCheck('check');
    } else if ($('.chose-box').attr('data-open') == 1) {
        $('input[id="chose1"]').iCheck('check');
    }
    $('input[name="chose"]').on('ifChanged', function(){
        if ($('input[id="chose1"]').prop("checked")) {
            is_on = 1;
        } else if ($('input[id="chose2"]').prop("checked")){
            is_on = 0;
        }
    })

    $(function () {
        $('#save_btn').on('click',function(){
            var list=$('#form').serialize();
            $.ajax({
                url:"{:Url('saveChannelConfig')}",
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
    })

    $('[data-role="change_set"]').click(function () {
        var value=$(this).attr('data-value');
        if(value==0){
            $('#channel_set').css('display','none');
        }else{
            $('#channel_set').css('display','block');
        }
    });
</script>
{/block}