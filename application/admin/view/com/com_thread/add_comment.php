{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<script src="/public/static/plug/vue/dist/vue.min.js"></script>
<link href="/public/static/plug/iview/dist/styles/iview.css" rel="stylesheet">
<script src="/public/static/plug/iview/dist/iview.min.js"></script>
<script src="/public/static/plug/jquery/jquery.min.js"></script>
<script src="/public/static/plug/form-create/province_city.js"></script>
<script src="/public/static/plug/form-create/form-create.min.js"></script>
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<style>
    /*弹框样式修改*/
    .ivu-modal-body{padding: 5;}
    .ivu-modal-confirm-footer{display: none;}
    .ivu-date-picker {display: inline-block;line-height: normal;width: 280px;}

    /**链接选择器组件选择范围优化 css**/
    .ivu-icon-ios-close.ivu-input-icon div:before{content: '';}
    .ivu-icon-link-select{width: 100%;text-align: right;}
    .ivu-icon-link-select div{width: 80px;float: right;background: #eeeeee;color: #848484;text-align: center;height: 30px;margin-top: 1px;margin-right: 1px;border-bottom-right-radius: 4px;border-top-right-radius: 4px;font-size: 14px; }
    .ivu-icon-link-select div:before{content: '选择地址';}
    .ivu-icon-link-select:hover div{background: #d2cece;}
    /**链接选择器组件选择范围优化 end**/
    .message_show{
        margin-left: 125px;
        display: flex;
        justify-content: space-around;
    }
    .info_show{
        margin-left: 125px;
        color: #ccc;
        line-height: 18px;
    }
    .label_show{
        margin-top: 25px;
    }
    .ivu-form-item-content{
        margin-left: 125px;
        display:flex;
        justify-content: space-between
    }
    .layui-form-radio{
        margin-top: 0;
    }
    .gray-bg{
        background-color: #fff;
    }
</style>
<script>
    /**链接选择器组件选择范围优化 js**/
    $(function () {
        $('.ivu-icon-ios-close.ivu-input-icon').html('<div></div>');
        $('.ivu-icon-link-select').html('<div></div>');
    })
    /**链接选择器组件选择范围优化 end**/
</script>
<style id="form-create-style">.form-create{padding:25px;} .fc-upload-btn,.fc-files{display: inline-block;width: 58px;height: 58px;text-align: center;line-height: 58px;border: 1px solid #c0ccda;border-radius: 4px;overflow: hidden;background: #fff;position: relative;box-shadow: 2px 2px 5px rgba(0,0,0,.1);margin-right: 4px;box-sizing: border-box;}.__fc_h{display:none;}.__fc_v{visibility:hidden;} .fc-files>.ivu-icon{vertical-align: middle;}.fc-files img{width:100%;height:100%;display:inline-block;vertical-align: top;}.fc-upload .ivu-upload{display: inline-block;}.fc-upload-btn{border: 1px dashed #c0ccda;}.fc-upload-btn>ivu-icon{vertical-align:sub;}.fc-upload .fc-upload-cover{opacity: 0; position: absolute; top: 0; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,.6); transition: opacity .3s;}.fc-upload .fc-upload-cover i{ color: #fff; font-size: 20px; cursor: pointer; margin: 0 2px; }.fc-files:hover .fc-upload-cover{opacity: 1; }.fc-hide-btn .ivu-upload .ivu-upload{display:none;}.fc-upload .ivu-upload-list{margin-top: 0;}.fc-spin-icon-load{animation: ani-fc-spin 1s linear infinite;} @-webkit-keyframes ani-fc-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}50%{-webkit-transform:rotate(180deg);transform:rotate(180deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes ani-fc-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}50%{-webkit-transform:rotate(180deg);transform:rotate(180deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}</style></head>
{/block}
{block name="content"}
<form autocomplete="off" class="ivu-form ivu-form-label-right form-create" id="signupForm">
    <input type="hidden" class="form-control" name="is_post" value="1" validate="" style="width:100%"/>
    <div class="ivu-row">
        <div class="ivu-col ivu-col-span-24">
            <div class="ivu-form-item">
                <label for="fc_visit" class="ivu-form-item-label" style="width: 125px;">已选帖子数量:</label>
                <div class="ivu-form-item-content">
                    {$num}
                    <input type="hidden" name="ids" id="ids" value="{$ids}">
                </div>
            </div>
            <div class="ivu-form-item">
                <!-- 访问审核-->
                <label for="fc_visit" class="ivu-form-item-label" style="width: 125px;">评论时间:</label>
                <div class="ivu-form-item-content">
                    <div  class="ivu-radio-group">
                        <label class="ivu-radio-wrapper ivu-radio-group-item">
                                <span class="ivu-radio">
                                    <span class="ivu-radio-inner"></span>
                                    <input type="radio" class="ivu-radio-input" name="time" value="24">
                                </span>
                                24小时
                        </label>
                        <label class="ivu-radio-wrapper ivu-radio-group-item" data-show="show" data-id="audit">
                                <span class="ivu-radio">
                                    <span class="ivu-radio-inner"></span>
                                    <input type="radio" class="ivu-radio-input" name="time" value="48">
                                </span>
                               48小时
                        </label>
                        <label class="ivu-radio-wrapper ivu-radio-group-item" data-show="show" data-id="audit">
                                <span class="ivu-radio">
                                    <span class="ivu-radio-inner"></span>
                                    <input type="radio" class="ivu-radio-input" name="time" value="72">
                                </span>
                            72小时
                        </label>
                    </div>
                </div>
                <div class="info_show">即设置每条评论的发布时间，建议尽量选择离当前时间点较近的时间段，系统将在此时间段内随时生成评论时间</div>
            </div>
            <!-- 帖子浏览权限-->
            <div class="ivu-form-item">
                <label for="fc_visit" class="ivu-form-item-label" style="width: 125px;">评论数:</label>
                <div class="ivu-form-item-content" style="margin-left: 125px;">
                    <div  class="ivu-radio-group">
                        <label class="ivu-radio-wrapper ivu-radio-group-item">
                            <input type="text" name="num" id="num" class="layui-input ivu-input" value="">
                        </label>
                    </div>
                </div>
                <div class="info_show">即每条选中的帖子对应注入的评论数，建议1~3条，不宜过多</div>
            </div>
            <!-- 访问审核-->
            <div class="ivu-form-item">
                <label for="fc_visit" class="ivu-form-item-label" style="width: 125px;">评论内容:</label>
                <div class="ivu-form-item-content">
                    <div  class="ivu-radio-group">
                        <label class="ivu-radio-wrapper ivu-radio-group-item">
                            <span class="ivu-radio">
                                <span class="ivu-radio-inner"></span>
                                <input type="radio" class="ivu-radio-input" name="temp" value="1">
                            </span>
                            评论模板随机选择
                        </label><br/>
                        <label class="ivu-radio-wrapper ivu-radio-group-item" data-show="show" data-id="audit">
                            <span class="ivu-radio">
                                <span class="ivu-radio-inner"></span>
                                <input type="radio" class="ivu-radio-input" name="temp" value="2">
                            </span>
                            自定义内容
                        </label><br/>
                        <label class="ivu-radio-wrapper ivu-radio-group-item" data-show="show" data-id="audit">
                            <textarea rows="3" name="temp_content" style="width: 400px"></textarea>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="ivu-col ivu-col-span-24" >
            <div class="ivu-col ivu-col-span-24">
                <button type="button" class="ivu-btn ivu-btn-primary ivu-btn-long ivu-btn-large" data-role="submit">
                    <i class="ivu-icon ivu-icon-ios-upload"></i>
                    <span>提交</span>
                </button>
                <p style="color: red;text-align: center;margin-top: 10px">修改版块权限后，需要去维护->刷新缓存->清除缓存,前端才能生效。</p>
            </div>

        </div>
    </div>
</form>
{/block}
{block name="script"}
<script>
    $('.ivu-radio-wrapper').click(function () {
        $(this).addClass('ivu-radio-wrapper-checked').children().addClass('ivu-radio-checked').children().attr("checked","checked");
        $(this).siblings().removeClass('ivu-radio-wrapper-checked').children().removeClass('ivu-radio-checked').children().remove('checked');
        var id=$(this).attr('data-id');
        var show=$(this).attr('data-show');
        if(show === "show"){
            $("#choose_group_box_"+id).css("display","flex");
        }else{
            $("#choose_group_box_"+id).css("display","none");
        }
    });
    window.addEventListener("storage", function (e) {
//        var reg = new RegExp(",","g");//g,表示全部替换。
//        var text = e.newValue.replace(reg,"、");
        $('#'+e.key).val(e.newValue);
        window.localStorage.removeItem(e.key);
    });
    $('[data-role="submit"]').click(function () {
        var data=$('#signupForm').serializeArray();
        $.post("{:Url('add_comment')}",data,function (res) {
            if(res.code==200){
                $eb.message('success',res.msg);
                setTimeout(function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    console.log($(".page-tabs-content .active").index());
                    window.frames[$(".page-tabs-content .active").index()].location.reload();
                },1500)
            }else{
                $eb.message('error',res.msg);
            }
        })
    });
</script>
{/block}