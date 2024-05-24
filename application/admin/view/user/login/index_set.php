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
        width: 200px;
        height: 30px;
        padding-left: 5px;
    }
    .title{
        padding-left: 15px;
        margin-top: 30px;
        color: #333;
        font-weight: 600;
    }
    .input-box{
        display: flex;
        align-items: center;
        margin-top: 25px;
        margin-bottom: 25px;
    }
    .left-box{
        width: 160px;
        text-align: right;
        margin-left: 30px;
        margin-right: 10px;
    }
    label{
        font-weight: 500;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="layui-card-header" style="font-weight: 600">基础设置</div>
        <div class="form">
            <div class="title">基础设置</div>
            <div class="input-box">
                <div class="left-box">注册登录是否强制设置：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.is_force_login eq '0'"}
                    <input class="radio" name="is_force_login" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="is_force_login" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="is_force_login" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">打开应用时，不强制登录，但使用部分功能时一定要登录</label>
                </div>
            </div>
            <div class="input-box">
                <div class="left-box"></div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.is_force_login eq '1'"}
                    <input class="radio" name="is_force_login" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="is_force_login" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="is_force_login" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">打开应用时，需强制登录<span style="font-size: 12px;color: #999">（微信小程序除外）</span></label>
                </div>
            </div>
            <div class="title">第三方注册绑定设置</div>
            <div class="input-box">
                <div class="left-box">邀请码注册：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.invite_code neq '0'"}
                    <input class="radio" name="invite_code" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="invite_code" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">开启</label>
                </div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.invite_code eq '0'"}
                    <input class="radio" name="invite_code" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="invite_code" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">关闭</label>
                </div>
            </div>
            <div class="input-box">
                <div class="left-box">是否设置邀请码为必填项：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.invite_code_need eq '1'"}
                    <input class="radio" name="invite_code_need" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="invite_code_need" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code_need" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">是</label>
                </div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.invite_code_need eq '0'"}
                    <input class="radio" name="invite_code_need" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="invite_code_need" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code_need" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">否</label>
                </div>
            </div>
            <div class="title">基础设置</div>
            <div class="input-box">
                <div class="left-box">第三方注册绑定：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.support_third_login eq '0'"}
                    <input class="radio" name="support_third_login" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="support_third_login" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="support_third_login" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">开启</label>
                </div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.support_third_login eq '1'"}
                    <input class="radio" name="support_third_login" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="support_third_login" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="support_third_login" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">关闭</label>
                </div>
            </div>
            <div class="input-box">
                <div class="left-box">是否设置绑定为必填项：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.other_login_must eq '1'"}
                    <input class="radio" name="other_login_must" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="other_login_must" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="none" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">是</label>
                </div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.other_login_must eq '0'"}
                    <input class="radio" name="other_login_must" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="other_login_must" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="none" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">否</label>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $('.radio').change(function() {
        $.ajax({
            url:"{:Url('edit_set')}",
            data:{
                type:$(this).attr("name"),
                status:$(this).val()
            },
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
</script>
{/block}

