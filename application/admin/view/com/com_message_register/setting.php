<!-- 查看帖子详情 -->
{extend name="public/modal-frame"}
{block name="head_top"}
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
{/block}
{block name="content"}
<div class="container">
    <div class="box-line" style="display: flex;margin-top: 20px">
        <div class="left" style="width: 100px">新注册消息</div>
        <div class="right" style="display: flex;">
            <div style="display: flex;align-items: center">
                <input id="none" type="radio" name="condition" value="1" style="margin-top: 0;" {if condition="$is_open eq 1"}checked{/if} >
                <label for="none" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">开启</label>
            </div>
            <div style="display: flex;align-items: center;margin-left: 20px;">
                <input id="none" type="radio" name="condition" value="0"  style="margin-top: 0;" {if condition="$is_open eq 0"}checked{/if} >
                <label for="none" style="margin-bottom: 0;margin-left: 10px;margin-right: 5px;">关闭</label>
            </div>
        </div>
    </div>
    <div style="color: #999;margin-left: 100px;margin-top: 10px;">
        开启后，新用户注册成功后，系统将发送一条新用户注册消息至消息中心
    </div>
    <div class="box-line" style="display: flex;margin-top: 20px">
        <div class="left" style="width: 100px;line-height: 34px">新注册消息</div>
        <div style="display: flex;align-items: center">
            <div style="margin-right: 10px">注册成功之日起</div>
            <input class="form-control valid" value="{$open_time}" id="day" style="margin-right: 10px;width: 200px;" type="number">
            <div>天</div>
        </div>
    </div>
    <div style="color: #999;margin-left: 100px;margin-top: 10px;">
        消息到期后，自动在用户消息中心列表不显示
    </div>
    <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 20px 0 20px 100px;">
        保存
    </div>
</div>
{/block}
{block name="script"}
<script>
    $("#save_btn").on("click",function () {
        var list = {};
        list.is_open = $("input[name='condition']:checked").val();
        list.open_time = $("#day").val();
        if(list.open_time === ""){
            $eb.message('error','请输入天数');
            return false;
        }
        $.ajax({
            url:"{:Url('site_edit')}",
            data:list,
            type:'post',
            dataType:'json',
            success:function(re){
                if(re.code == 200){
                    $eb.message('success',re.msg);
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                }else{
                    $eb.message('error',re.msg);
                }
            }
        })
    })
</script>
{/block}