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
        padding: 30px 0;
        font-size: 18px;
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
    .wrapper-content{
        padding-left: 0;
        height: 100%;
        background-color: #fff;
    }
    .gray-bg{
        height: 223px;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="form">
            <div class="input-box">
                <div class="left-box">H5环境强制登录：</div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.must_weixin_login neq '0'"}
                    <input class="radio" name="must_weixin_login" type="radio" value="1" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="must_weixin_login" type="radio" value="1" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">开启</label>
                </div>
                <div style="display: flex;align-items: center">
                    {if condition="$data.must_weixin_login eq '0'"}
                    <input class="radio" name="must_weixin_login" type="radio" value="0" style="margin-top: 0;" checked>
                    {else/}
                    <input class="radio" name="must_weixin_login" type="radio" value="0" style="margin-top: 0;">
                    {/if}
                    <label for="invite_code" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">关闭</label>
                </div>
            </div>
            <div style="width: 100%;border-top: 1px solid #eee;margin-top: 73px;"></div>
            <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 15px 0 15px 430px;padding: 6px 30px">
                保存
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $('#save_btn').on("click",function() {
        $.ajax({
            url:"{:Url('edit_set')}",
            data:{
                type:"must_weixin_login",
                status:$("input[name='must_weixin_login']:checked").val()
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

