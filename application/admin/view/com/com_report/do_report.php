{extend name="public/container"}
{block name="content"}
<link rel="stylesheet" href="/public/static/plug/layui2.5.5/css/eleTree.css">
<link rel="stylesheet" href="{__PLUG_PATH}layui2.5.5/css/layui.css">
<script src="{__PLUG_PATH}layui2.5.5/layui.js"></script>
<style>
    .gray-bg {
        background-color: #fff;
    }

    .do_report {
        color: #333;
    }

    .desc-font {
        color: #acacac;
    }

    label {
        font-weight: 400;
    }

    .score-box {
        width: 70px !important;
        line-height: 2.5em;
        text-align: center;
        background-color: #f2f2f2;
        cursor: pointer;
    }

    .score-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .select-chunk {
        background-color: #0092dc;
        color: #fff;
    }
    .score {
        display: none;
    }
</style>
<div class="do_report">
    <div class="layui-fluid">
        <form class="form-horizontal" id="adForm">
            <legend>内容/账号处理</legend>
            <div class="layui-form-item">
                <div class="layui-form-label" style="width: 40px;padding:9px 10px">
                    <input type="checkbox" id="delcontent" name="checkbox" value="delete_content" lay-skin="primary" title="删除内容">
                </div>
                <div>
                    <label for="delcontent">删除内容</label>
                    <div class="desc-font">删除被举报的内容</div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-label" style="width: 40px;padding:9px 10px">
                    <input type="checkbox" id="nosay" name="checkbox" value="choose_prohibit" lay-skin="primary" title="账号禁言">
                </div>
                <div class="layui-input-inline">
                    <label for="nosay">账号禁言</label>
                    <div class="desc-font">被举报用户账号禁言</div>
                    <div style="margin-top:9px;">
                        <div style="margin-left: 20px;">
                            <input type="radio" id="time1" name="time" value="10800" title="禁言3小时">
                            <label for="time1">禁言3小时</label>
                        </div>
                        <div style="margin-left: 20px;">
                            <input type="radio" id="time2" name="time" value="86400" title="禁言1天">
                            <label for="time2">禁言1天</label>
                        </div>
                        <div style="margin-left: 20px;">
                            <input type="radio" id="time3" name="time" value="3600" title="禁言1小时">
                            <label for="time3">禁言1小时</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-label" style="width: 40px;padding:9px 10px">
                    <input type="checkbox" id="userdisabled" name="checkbox" value="user_delete" lay-skin="primary" title="账号禁用">
                </div>
                <div>
                    <label for="userdisabled">账号禁用</label>
                    <div class="desc-font">被举报用户账号禁用</div>
                </div>
            </div>

            <legend>积分处理</legend>
            <div class="score-count" class="layui-form-item">
                <div style="margin-left: 20px;">
                    <input type="radio" id="minus" name="score" value="1" title="不扣分" checked>
                    <label for="minus">不扣分</label>
                </div>
                <div style="margin-left: 20px;">
                    <input type="radio" id="plus" name="score" value="2" title="扣分">
                    <label for="plus">扣分</label>
                </div>
            </div>
            <div class="score">
                <div class="layui-form-item" style="margin-left: 20px;">
                    <div class="score-title">经验值</div>
                    <div class="layui-input-block" id="ex_chunk" style="margin-left: 0;">
                        <div class="layui-input-inline score-box"><span>1</span></div>
                        <div class="layui-input-inline score-box"><span>3</span></div>
                        <div class="layui-input-inline score-box"><span>5</span></div>
                        <div>
                            <div class="layui-input-inline" style="width: 70px;line-height: 35px;">自定义分值</div>
                            <input type="number" value="" id="ex_score" class="layui-input layui-input-inline">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item" style="margin-left: 20px;">
                    <div class="score-title">社区积分</div>
                    <div class="layui-input-block" id="community_chunk" style="margin-left: 0;">
                        <div class="layui-input-inline score-box"><span>1</span></div>
                        <div class="layui-input-inline score-box"><span>3</span></div>
                        <div class="layui-input-inline score-box"><span>5</span></div>
                        <div>
                            <div class="layui-input-inline" style="width: 70px;line-height: 35px;">自定义分值</div>
                            <input type="number" id="community_score" class="layui-input layui-input-inline">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item" style="margin-left: 20px;">
                    <div class="score-title">购物积分</div>
                    <div class="layui-input-block" id="shop_chunk" style="margin-left: 0;">
                        <div class="layui-input-inline score-box"><span>1</span></div>
                        <div class="layui-input-inline score-box"><span>3</span></div>
                        <div class="layui-input-inline score-box"><span>5</span></div>
                        <div>
                            <div class="layui-input-inline" style="width: 70px;line-height: 35px;">自定义分值</div>
                            <input type="number" value="" id="shop_score" class="layui-input layui-input-inline">
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-form-item" style="margin-top: 30px;">
                <button id="enter" type="button" class="btn btn-info col-md-4" style="width: 100%;">确定</button>
            </div>
        </form>
        <input type="hidden" id="forum_id" />
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    var list = {}
    var exp_num = 0
    var fly_num = 0
    var buy_num = 0
    $('#nosay').click(function() {
        cancel_select()
    })

    function cancel_select() {
        if ($('#nosay').prop("checked")) {
            $('#time1').prop("checked", "checked");
        } else {
            $('#time1').removeAttr("checked");
            $('#time2').removeAttr("checked");
            $('#time3').removeAttr("checked");
        }
    }
    // 单选多选框
    $(function() {
        $(":checkbox").each(function() {
            $(this).click(function() {
                if ($(this).is(":checked")) {
                    $(":checkbox").each(function() {
                        $(this).prop("checked", false);
                    });
                    $(this).prop("checked", true);
                    cancel_select()
                }
            });
        });
    });

    $('#community_chunk .score-box').click(function() {
        $('#community_chunk .score-box').removeClass("select-chunk")
        $(this).addClass('select-chunk')
        $('#community_score').val('')
        exp_num = parseInt($(this).find('span').text())
    })
    $('#ex_chunk .score-box').click(function() {
        $('#ex_chunk .score-box').removeClass("select-chunk")
        $(this).addClass('select-chunk')
        $('#ex_score').val('')
        fly_num = parseInt($(this).find('span').text())
    })
    $('#shop_chunk .score-box').click(function() {
        $('#shop_chunk .score-box').removeClass("select-chunk")
        $(this).addClass('select-chunk')
        $('#shop_score').val('')
        buy_num = parseInt($(this).find('span').text())
    })
    // focus
    $('#ex_score').focus(function() {
        $('#ex_chunk .score-box').removeClass("select-chunk")
        $('#ex_score').val('0')
    })
    $('#community_score').focus(function() {
        $('#community_chunk .score-box').removeClass("select-chunk")
        $('#community_score').val('0')
    })
    $('#shop_score').focus(function() {
        $('#shop_chunk .score-box').removeClass("select-chunk")
        $('#shop_score').val('0')
    })
    // 积分处理
    $('.score-count').click(function() {
        if ($('input:radio[name=score]:checked').val() === '1') {
            $('.score').hide()
        } else if ($('input:radio[name=score]:checked').val() === '2') {
            $('.score').show()
        } else {
            return
        }
    })
    $('#enter').on('click', function() {
        var chk_value = []
        $('input:checkbox[name=checkbox]:checked').each(function() {
            chk_value.push($(this).val())
        });
        list.id = $('#forum_id').val()
        list.type = chk_value[0] ? chk_value[0] : 'no_deal'
        list.choose_data = $("input[name='time']:checked").val()
        if ($('input:radio[name=score]:checked').val() === '1') {
            list.score = [{
                flag: "exp",
                num: 0
            }, {
                flag: "fly",
                num: 0
            }, {
                flag: "buy",
                num: 0
            }];
        } else {
            list.score = [{
                flag: "exp",
                num: exp_num
            }, {
                flag: "fly",
                num: fly_num
            }, {
                flag: "buy",
                num: buy_num
            }];
        }

        console.log(list)
        $.ajax({
            url: "{:Url('report')}",
            data: list,
            type: 'post',
            dataType: 'json',
            success: function(re) {
                if (re.code == 200) {
                    $eb.message('success', re.msg);
                    setTimeout(function(e) {
                        parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    }, 600)
                } else {
                    $eb.message('error', re.msg);
                }
            }
        })
    })

    function getUrlParam(name) {
              var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
              var r = window.location.search.substr(1).match(reg);  //匹配目标参数
              if (r != null) return unescape(r[2]); return null; //返回参数值
          }
  var id = getUrlParam('id');
  $('#forum_id').val(""+id+"");
  console.log($('#forum_id').val())
</script>
{/block}