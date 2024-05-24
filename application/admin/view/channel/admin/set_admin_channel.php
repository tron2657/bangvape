{extend name="public/container"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
    .content_all{
        display: flex;
    }
    .ibox-content{
        margin-top: 0;
        padding-top: 0;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .content_all .left{
        /* width: 80px; */
        border-right: 4px solid #ccc;
    }
    .content_all .right{
        flex:1;
    }
    .wrapper-content{
        padding: 0 15px;
    }


    .control-label{
        width: 200px;
        float: left;
        text-align: right;
        margin-top: 20px;
        line-height: 30px;
        padding-right: 10px;
    }
    .input_label{
        width: 500px;
        float: left;
        margin-top: 20px;
    }
    .clear{
        clear: both;
    }

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
</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-12" style="margin: 0;padding: 0;">
        <div class="ibox">
            <div class="ibox-content">
                <div class="content_all">
                    <div class="right">
                        <div class="row">
                            <form id="reset_form" class="form-horizontal" action="" style="padding:20px;">
                                <input type="hidden" name="uid" value="{$uid}">
                                <div class="form-group">
                                    <label for="post_type" class="control-label"><span style="color: red;margin-right: 5px;">*</span>选择频道:</label>
                                    <div class="input_label">
                                        {volist name="channel_list" id="one_channel"}
                                        <label class="checkbox-inline checkbox_label">
                                            <input type="checkbox" name="channel_ids[]" value="{$one_channel['id']}" {eq name="one_channel.now_has" value="1"}checked{/eq}> {$one_channel['title']}
                                        </label>
                                        {/volist}
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </form>
                            <hr/>
                            <div class="col-sm-offset-2">
                                <div class="col-sm-8" style="text-align: right">
                                    <button type="button" id="save_reset_btn" class="btn btn-primary" style="padding: 10px 50px;margin: 20px 15px 44px;background-color: #0ca6f2; ">确认</button>
                                    <button type="button" id="un_do" class="btn btn-default" style="padding: 10px 50px;margin: 20px 15px 44px;background-color: #fff;border: 1px solid #0ca6f2;color: #0ca6f2; ">取消</button>
                                </div>
                            </div>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    </div>
</div>
        <script src="{__ADMIN_PATH}js/layuiList.js"></script>
        <script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
        <link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    $(function () {
        $('#save_reset_btn').on('click',function(){
            var data=$('#reset_form').serialize();

            $.ajax({
                url:"{:Url('do_set_admin_channel')}",
                data:data,
                type:'post',
                dataType:'json',
                success:function(re){
                    if(re.code == 200){
                        $eb.message('success',re.msg);
                        setTimeout(function () {
                            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        },600);
                    }else{
                        $eb.message('error',re.msg);
                    }
                }
            })
        });
        $('#un_do').on('click',function () {
            parent.layer.close(parent.layer.getFrameIndex(window.name));
        })
    })

</script>
{/block}
