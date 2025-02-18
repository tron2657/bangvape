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
        height: 30px;
        padding-left: 5px;
        margin-right: 5px;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="layui-card-header">分销申请协议</div>
        <div class="form" style="margin: 20px">
            <div class="input-box" style="display: flex;align-items: flex-start;">
                <label for="" style="margin-bottom: 0;width: 78px;text-align: right">协议内容</label>
                <textarea type="text/plain" id="myEditor" style="width:700px;margin-left: 10px;">{$agent_xieyi_config}</textarea>
            </div>
            <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 40px 0 20px 116px;">
                保存
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    var ue = UE.getEditor('myEditor',{
        autoHeightEnabled: false,
        initialFrameHeight: 400,
        wordCount: false,
        maximumWords: 100000
    });
    /**
     * 获取编辑器内的内容
     * */
    function getContent() {
        return (ue.getContent());
    }
    $('#save_btn').on('click',function(){
        var list = {};
        list.agent_xieyi_config = getContent();
        $.ajax({
            url:"{:Url('saveXieyi')}",
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
</script>
{/block}