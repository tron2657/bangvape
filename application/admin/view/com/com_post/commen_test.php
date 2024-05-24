{extend name="public/container"}

{block name="content"}
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
<style>
    .my-align-radio {
        display: flex;
        align-items: center;
    }

    .radio-style {
        margin: initial;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="tabs-container ibox-title">
                <div class="<!--ibox-content--> p-m m-t-sm">
                    <form method="post" class="form-horizontal" id="signupForm" action="/admin/setting.system_config/save_basics.html">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" style="padding-top: 0;">是否开启社区图片评论（评论支持添加图片）</label>
                            <div class="col-md-8">
                                <div class="radio i-checks" style="display:inline">
                                    <label class="col-sm-2 my-align-radio" style="padding-left: 0;">
                                        <div class="iradio_square-green" style="position: relative;">
                                            <div class="iradio_square-green" style="position: relative;"><input type="radio" value="1" name="value" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;" <?php if($comment_photo_set == 1) {echo 'checked';}?>></ins></div>
                                        </div>
                                        <i></i> 开启
                                    </label>
                                </div>
                                <div class="radio i-checks checked" style="display:inline">
                                    <label class="col-sm-2 my-align-radio" style="padding-left: 0;">
                                        <div class="iradio_square-green " style="position: relative;">
                                            <div class="iradio_square-green" style="position: relative;"><input type="radio" checked="checked" value="0" name="value" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;" <?php if($comment_photo_set == 0) {echo 'checked';}?>></ins></div>
                                        </div>
                                        <i></i> 关闭
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-4">
                            <button type="button" class="layui-btn layui-btn-sm" id="sub" style="margin-top: 10px">提交</button>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
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

    if ($('.chose-box').attr('data-open') == 0) {
        $('input[id="chose2"]').iCheck('check');
    } else if ($('.chose-box').attr('data-open') == 1) {
        $('input[id="chose1"]').iCheck('check');
    }
    $('input[name="chose"]').on('ifChanged', function() {
        if ($('input[id="chose1"]').prop("checked")) {
            is_on = 1;
        } else if ($('input[id="chose2"]').prop("checked")) {
            is_on = 0;
        }
    })

    $('[data-role="change_set"]').click(function() {
        var value = $(this).attr('data-value');
        if (value == 0) {
            $('#channel_set').css('display', 'none');
        } else {
            $('#channel_set').css('display', 'block');
        }
    });

    $('#sub').on('click', function() {
        var formlist = $('#signupForm').serializeArray()
        console.log(formlist)
        layList.baseGet(layList.Url({
            c: 'com.com_post',
            a: 'commen_test',
            p: {
                value: formlist[0].value
            }
        }), function(res) {
            layList.msg(res.msg);
        });
    })
</script>
{/block}