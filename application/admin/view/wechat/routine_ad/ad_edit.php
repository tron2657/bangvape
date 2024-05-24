{extend name="public/container"}
{block name="head_top"}

<link rel="stylesheet" href="/public/static/plug/layui2.5.5/css/eleTree.css">
<link href="{__PLUG_PATH}layui2.5.5/css/layui.css" rel="stylesheet">
<script src="{__PLUG_PATH}layui2.5.5/layui.js"></script>
<style>
    .ad-line-h {
        line-height: 2.5;
    }

    .ad-trigger-check {
        margin-left: 5px
    }

    .ad_config_none {
        display: none;
    }

    label {
        font-weight: initial
    }

    .gray-bg {
        background-color: #fff;
    }
</style>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="panel-body">
            <form class="form-horizontal" id="adForm">
                <div class="layui-form-item" id="edit_unit_id">
                    <label class="layui-form-label" style="width: 100px;">广告位ID</label>
                    <div class="layui-input-block">
                        <input type="text" id="edit_ad_id" name="title" disabled class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_position">
                    <label class="layui-form-label" style="width: 100px;">广告位置</label>
                    <div class="layui-input-block">
                        <span style="line-height: 2.8;">固定位置（一个位置仅支持添加一个流量广告）</span>
                        <div class="layui-bg-gray" style="padding: 0 15px;">
                            <div class="eleTree ele6" lay-filter="data6" id="treeInfo" style="padding: 15px 0;"></div>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_trigger_scene">
                    <label class="layui-form-label" style="width: 100px;">触发场景</label>
                    <div class="layui-input-block">
                        <div class="layui-bg-gray" id="edit_ad_change_checked" style="padding: 15px 15px;">
                            <input type="checkbox" name="ad_checked_trigger" value="1">
                            <span class="ad-trigger-check">商城付款成功</span><br>
                                <input type="checkbox" name="ad_checked_trigger" lay-skin="primary" value="2" title="底部Tab栏切换（插屏广告2）">
                                <span class="ad-trigger-check">底部Tab栏切换（插屏广告2）</span><br>
                                    <input type="checkbox" name="ad_checked_trigger" lay-skin="primary" value="3" title="视频播放暂停">
                                    <span class="ad-trigger-check">视频播放暂停</span><br>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_trigger_interval">
                    <label class="layui-form-label" style="width: 100px;">触发间隔</label>
                    <div class="layui-input-block">
                        <div class="layui-input-inline" style="width: 75%">
                            <input type="number" value="1" id="edit_ad_change_interval" placeholder="请输入" class="layui-input">
                        </div>
                        <div style="width: 20%;float: right;"">
                            <select id="edit_ad_time" class="form-control" style="height: 38px;">
                                <option value="0">分钟</option>
                                <option value="1">小时</option>
                                <option value="2">天</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_theme">
                    <label class="layui-form-label" style="width: 100px;">广告主题</label>
                    <div class="layui-input-block" id="edit_ad_theme" style="padding-top:9px">
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="radio" id="white" name="color" value="1" title="白色" checked="">
                            <label for="white">白色</label>
                        </div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="radio" id="black" name="color" value="2" title="黑色">
                            <label for="black">黑色</label>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_position_raw">
                    <label class="layui-form-label" style="width: 100px;">广告位置</label>
                    <div class="layui-input-block" id="edit_ad_position_raw" style="padding-top:9px">
                        <input id="position_info" type="radio" name="position" value="1" title="信息流（仅横版卡片和横幅）" checked="">
                        <label for="position_info">信息流（仅横版卡片和横幅）</label>
                        <input id="position_on" type="radio" name="position" value="2" title="悬浮格子（仅格子）">
                        <label for="position_on">悬浮格子（仅格子）</label>
                    </div>
                </div>

                <div class="layui-form-item ad_config_none" id="edit_unit_gridnum">
                    <label class="layui-form-label" style="width: 100px;">格子个数</label>
                    <div class="layui-input-block" id="edit_grid_count" style="padding-top:9px">
                        <div class="layui-input-inline" style="width: 100px;">
                            <input id="num5" type="radio" name="num" value=“5” title="5" checked="">
                            <label for="num5">5</label>
                        </div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input id="num8" type="radio" name="num" value="8" title="8">
                            <label for="num8">8</label>
                        </div>
                    </div>
                </div>

                <div class="ad_config_none" id="edit_unit_newwork">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 100px;">任务名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" id="task_name" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 100px;">任务描述</label>
                        <div class="layui-input-block">
                            <input type="text" name="task_desc" id="task_explain" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 100px;">完成数量</label>
                        <div class="layui-input-block">
                            <input type="number" name="ok_num" id="task_require" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 100px;">任务积分</label>
                        <div id="add_integral">
                        </div>

                        <div class="layui-input-block">
                            <button type="button" style="margin-top: 0px;width:100%" class="layui-btn layui-btn-primary" id="add_integral_type">添加积分</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="width: 100px;">任务图标</label>
                        <div class="layui-input-block">
                            <div class="layui-input-inline" style="width: 102px;">
                                <input type="file" class="upload" name="image" style="display: none;" id="image" />
                                <a style="display: block;width: 102px;border: 1px solid #E5E6E7;" class="btn-sm add_image upload_span">
                                    <div class="upload-image-box transition image_img" id="edit_image_data" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                        <input value="" type="hidden" id="image_input" name="local_url">
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 100px;">备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" id="edit_ad_all_remark" lay-verify="title" autocomplete="off" placeholder="请输入备注" class="layui-input">
                    </div>
                </div>

                <div class="layui-row">
                    <div class="layui-form-item">
                        <div class="grid-demo" style="float: right;">
                            <button id="edit_enter_add_cancel" type="button" class="btn col-md-4" style="height: 38px;">取消</button>
                            <button id="edit_enter_add_ad" type="button" class="btn btn-info col-md-4" style="height: 38px;">确定</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $.ajax({
        url: "{:Url('get_ad_detail')}?id={$id}",
        type: 'get',
        dataType: 'json',
        success: function(res) {
            console.log(res);
            var ad_unit = res.data
            $('#edit_ad_id').val(ad_unit.ad_unit_id);

            window.ad_name = ad_unit.name
            window.ad_id = ad_unit.ad_unit_id
            window.ad_slot = ad_unit.ad_slot
            window.ad_status = ad_unit.ad_status
            window.ad_type = ad_unit.ad_type
            window.ad_icon = ad_unit.task ? ad_unit.task.icon : null
            if (ad_unit.task) {
                var data = integral_type(ad_unit.task)
                for (var i = 0; i < data.length; i++) {
                    $("#add_integral").append(function() {
                        return '<div class="layui-input-block" style="margin-bottom: 8px;"><div class="layui-input-inline"><select id="task_integral_type" class="form-control task_integral_type" style="height: 38px;"><option ' + (data[i].type === "one" ? "selected" : "") + ' value="one">想天点</option><option ' + (data[i].type === "buy" ? "selected" : "") + ' value="buy">购物积分</option><option ' + (data[i].type === 'gong' ? 'selected' : '') + ' value="gong">贡献值积分</option><option ' + (data[i].type === 'fly' ? 'selected' : '') + ' value="fly">社区积分</option><option ' + (data[i].type === 'exp' ? 'selected' : '') + ' value="exp">积分类型-经验值积分</option></select></div><div class="layui-input-inline" style="width: 100px;"><input type="number" id="task_integral" value="' + (data[i].value) + '" placeholder="请输入" class="layui-input task_integral"></div><div class="layui-input-inline del_integral" style="width:36px"><i class="layui-icon layui-icon-close-fill" id="del_" style="font-size: 36px; color: #1E9FFF;"></i></div></div>'
                    })
                }
            }
            console.log(ad_slot)

            switch (ad_unit.ad_slot) {
                case 'SLOT_ID_WEAPP_BANNER':
                    $('#edit_unit_position').show();
                    window.ad_tree_type = ad_unit.ad_type
                    ad_position_tree()
                    break;
                case 'SLOT_ID_WEAPP_REWARD_VIDEO':
                    $('#edit_unit_newwork').show();
                    $('#edit_image_data').css('background-image', 'url(' + (ad_icon ? ad_icon : '/public/system/module/wechat/news/images/image.png') + ')')
                    $('#task_name').val(ad_unit.task.name);
                    $('#task_explain').val(ad_unit.task.explain);
                    $('#task_require').val(ad_unit.task.require);
                    break;
                case 'SLOT_ID_WEAPP_INTERSTITIAL':
                    $('#edit_unit_trigger_scene').show();
                    $('#edit_unit_trigger_interval').show();
                    $('#edit_ad_change_checked input[name="ad_checked_trigger"]').each(function(i) {
                        if(ad_unit.trigger_scene) {
                            for (var i = 0; i < ad_unit.trigger_scene.length; i++) {
                                ad_unit.trigger_scene[i]
                                if ($(this).val() == ad_unit.trigger_scene[i]) {
                                    $(this).attr('checked', true)
                                }
                            }
                        }
                    })
                    var data = time_value(ad_unit.trigger_gap)
                    $('#edit_ad_change_interval').val(data.value);
                    $('#edit_ad_time').find("option[value=" + data.type + "]").attr("selected", true);

                    $('#edit_ad_change_checked input[name="ad_checked_trigger"]').each(function(i) {
                        if(ad_unit.trigger_scene) {
                            for (var i = 0; i < ad_unit.trigger_scene.length; i++) {
                                ad_unit.trigger_scene[i]
                                console.log($(this).val())
                                console.log(ad_unit.trigger_scene[i])
                                if ($(this).val() == ad_unit.trigger_scene[i]) {
                                    $(this).attr('checked', true)
                                }
                            }
                        }
                    })
                    break;
                case 'SLOT_ID_WEAPP_VIDEO_FEEDS':
                    $('#edit_unit_theme').show();
                    var type = ad_unit.ad_theme
                    $('#edit_unit_theme input[value=' + type + ']').attr('checked', true)
                    break;
                case 'SLOT_ID_WEAPP_VIDEO_BEGIN':
                    break;
                case 'SLOT_ID_WEAPP_BOX':
                    $('#edit_unit_position').show();
                    ad_position_tree();
                    $('#edit_unit_theme').show();
                    $('#edit_unit_gridnum').show();
                    var type = ad_unit.ad_theme
                    $('#edit_unit_theme input[value=' + type + ']').attr('checked', true)
                    var count = ad_unit.grid_count
                    $('#edit_grid_count input[value=' + count + ']').attr('checked', true)
                    break;
                case 'SLOT_ID_WEAPP_TEMPLATE':
                    $('#edit_unit_position_raw').show();
                    var type = ad_unit.position
                    $('#edit_ad_position_raw input[value=' + type + ']').attr('checked', true)
                    break;
            }
            $('#edit_ad_all_remark').val(ad_unit.remark)
            // $.ajax({
            //     url: "{:Url('add_ad')}",
            //     data: data,
            //     type: 'post',
            //     dataType: 'json',
            //     success: function(res) {
            //         if (res.code == 200) {
            //             parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
            //             parent.layer.close(parent.layer.getFrameIndex(window.name));
            //             console.log('成功ss')
            //         } else {
            //             $eb.message('error', res.msg)
            //         }
            //         console.log(res)
            //     }
            // })
        }
    })

    $("#edit_enter_add_ad").on('click', function() {
        if (ad_slot === 'SLOT_ID_WEAPP_BANNER' || ad_slot === 'SLOT_ID_WEAPP_BOX') {
            return
        }
        var data = {
            name: ad_name,
            ad_unit_id: ad_id,
            ad_slot: ad_slot,
            status: ad_status,
            trigger_scene: [],
            trigger_gap: 0,
            ad_theme: null,
            position: null,
            remark: $('#edit_ad_all_remark').val(),
            task: {
                one: 0,
                buy: 0,
                gong: 0,
                fly: 0,
                exp: null,
                icon: ad_icon
            }
        }
        switch (ad_slot) {
            case 'SLOT_ID_WEAPP_REWARD_VIDEO':
                data.task.name = $('#task_name').val();
                data.task.explain = $('#task_explain').val();
                data.task.reqire = $('#task_require').val();
                data.task.icon = $("#image_input").val();
                // var integral = $('#task_integral_type').val();
                $('#add_integral .task_integral_type').each(function() {
                    var type = $(this).val()
                    switch (type) {
                        case 'one':
                            data.task.one = $(this).parent().parent().find('.task_integral').val()
                            break;
                        case 'buy':
                            data.task.buy = $(this).parent().parent().find('.task_integral').val()
                            break;
                        case 'gong':
                            data.task.gong = $(this).parent().parent().find('.task_integral').val()
                            break;
                        case 'fly':
                            data.task.fly = $(this).parent().parent().find('.task_integral').val()
                            break;
                        case 'exp':
                            data.task.exp = $(this).parent().parent().find('.task_integral').val() ? $(this).parent().parent().find('.task_integral').val() : 0
                            break;
                    }
                })

                break;
            case 'SLOT_ID_WEAPP_INTERSTITIAL':
                $('#edit_ad_change_checked input[name="ad_checked_trigger"]:checked ').each(function(i) {
                    data.trigger_scene.push($(this).val())
                })
                var num = $('#edit_ad_change_interval').val()
                var time_type = $('#edit_ad_time').val();
                data.trigger_gap = adtime(num, time_type)
                break;
            case 'SLOT_ID_WEAPP_VIDEO_FEEDS':
                data.ad_theme = $('#edit_ad_theme input[name="color"]:checked').val()
                break;
            case 'SLOT_ID_WEAPP_VIDEO_BEGIN':
                break;
            case 'SLOT_ID_WEAPP_TEMPLATE':
                data.position = $('#edit_ad_position_raw input[name="position"]:checked').val()
                break;
        }

        console.log(data)

        $.ajax({
            url: "{:Url('update_ad')}?id={$id}",
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.code == 200) {
                    $eb.message('success', res.msg);
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                } else {
                    $eb.message('error', res.msg)
                }
                console.log(res)
            }
        })
    })


    // 上传图片
    $('.upload_span').on('click', function(e) {
        //                $('.upload').trigger('click');
        createFrame('选择图片','{:Url('widget.images/index')}?fodder=image'); 
    })

    function changeIMG(index, pic) {
        $(".image_img").css('background-image', "url(" + pic + ")");
        $(".active").css('background-image', "url(" + pic + ")");
        $('#image_input').val(pic);
    };

    function createFrame(title, src, opt) {
        opt === undefined && (opt = {});
        return layer.open({
            type: 2,
            title: title,
            area: [(opt.w || 720) + 'px', (opt.h || 500) + 'px'],
            fixed: false, //不固定
            maxmin: true,
            moveOut: false, //true  可以拖出窗外  false 只能在窗内拖
            anim: 5, //出场动画 isOutAnim bool 关闭动画
            offset: 'auto', //['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
            shade: 0, //遮罩
            resize: true, //是否允许拉伸
            content: src, //内容
            move: '.layui-layer-title'
        });
    }

    function ad_position_tree() {
        $.ajax({
            url: "{:Url('get_type_tree')}?id={$id}",
            type: 'get',
            dataType: 'json',
            success: function(res) {
                is_tree = res.data
                // for (var i = 0; i < is_tree.length; i++) {
                //     for (var j = 0; j < is_tree[i].children.length; j++) {
                //         for(var l=0;l < ad_tree_type.length;l++) {
                //             if(is_tree[i].children[j].id === ad_tree_type[l]) {
                //                 is_tree[i].children[j]['checked'] = true
                //             }
                //         }
                //     }
                // }
                console.log(is_tree)
                get_type_tree(is_tree)
            }
        })
    }

    function get_type_tree(is_tree) {
        layui.config({
            base: "/public/static/plug/layui2.5.5/css/eleTree.css"
        }).use(['tree'], function() {
            var $ = layui.jquery;
            var eleTree = layui.tree;

            var data = is_tree

            $(".eleTree-search").keydown(function() {
                el6.search($(this).val());
            });
            $(".eleTree-search").keyup(function() {
                el6.search($(this).val());
            });

            var el6 = eleTree.render({
                elem: '#treeInfo',
                data: data,
                id: 'demoId1',
                showCheckbox: true,
                showLine: false,
                defaultExpandAll: true,
            });
            //获取指定的选中信息
            $("#edit_enter_add_ad").click(function() {
                if (ad_slot === 'SLOT_ID_WEAPP_BANNER' || ad_slot === 'SLOT_ID_WEAPP_BOX') {
                    var ad_theme = null
                    var grid_count = null
                    var ad_type_let = []
                    var checkedData = el6.getChecked(false, true); //获取选中节点的数据
                    for (var i = 0; i < checkedData.length; i++) {
                        for (var j = 0; j < checkedData[i].children.length; j++) {
                            ad_type_let.push(checkedData[i].children[j].id)
                        }
                    }
                    if (ad_slot === 'SLOT_ID_WEAPP_BOX') {
                        ad_theme = $('#edit_ad_theme input[name="color"]:checked').val()
                        grid_count = $('#edit_grid_count input[name="num"]:checked').val()
                    }
                } else {
                    return
                }
                $.ajax({
                    url: "{:Url('update_ad')}?id={$id}",
                    data: {
                        name: ad_name,
                        ad_unit_id: ad_id,
                        ad_slot: ad_slot,
                        status: ad_status,
                        ad_type: ad_type_let,
                        ad_theme: ad_theme,
                        grid_count: grid_count
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.code == 200) {
                            $eb.message('success', res.msg);
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        } else {
                            $eb.message('error', res.msg)
                        }
                        console.log(res)
                    }
                })
            });


        });
    }

    $("#add_integral_type").click(function() {
        $("#add_integral").append(function() {
            return '<div class="layui-input-block"><div class="layui-input-inline"><select id="task_integral_type" class="form-control task_integral_type" style="height: 38px;"><option value="one">想天点</option><option value="buy">购物积分</option><option value="gong">贡献值积分</option><option value="fly">社区积分</option><option value="exp">积分类型-经验值积分</option></select></div><div class="layui-input-inline" style="width: 100px;"><input type="number" id="task_integral" value="10" placeholder="请输入" class="layui-input task_integral"></div><div class="layui-input-inline del_integral" style="width:36px"><i class="layui-icon layui-icon-close-fill" id="del_" style="font-size: 36px; color: #1E9FFF;"></i></div></div>'
        })
    })

    $(document).on("click", ".del_integral", function() {
        $(this).parent().remove();
    })

    $("#edit_enter_add_cancel").click(function() {
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    })

    function time_value(time) {
        if (typeof time === 'number') {
            if (time / 86400 >= 1) {
                return {
                    type: '2',
                    value: parseInt(time / 86400)
                }
            } else if (time / 3600 >= 1) {
                return {
                    type: '1',
                    value: parseInt(time / 3600)
                }
            } else if (time / 60 >= 1) {
                return {
                    type: '0',
                    value: parseInt(time / 60)
                }
            }
        } else {
            return {
                type: '0',
                value: 0
            }
        }
    }

    function adtime(num, type) {
        switch (type) {
            case '0':
                return num * 60
                break;
            case '1':
                return num * 3600
                break;
            case '2':
                return num * 86400
                break;
        }
    }

    function integral_type(task) {
        var num = []
        if (typeof task['one'] === 'number' && task['one'] != 0) {
            num.push({
                type: 'one',
                value: task['one']
            })
        }
        if (typeof task['buy'] === 'number' && task['buy'] != 0) {
            num.push({
                type: 'buy',
                value: task['buy']
            })
        }
        if (typeof task['fly'] === 'number' && task['fly'] != 0) {
            num.push({
                type: 'fly',
                value: task['fly']
            })
        }
        if (typeof task['gong'] === 'number' && task['gong'] != 0) {
            num.push({
                type: 'gong',
                value: task['gong']
            })
        }
        if (typeof task['exp'] === 'number' && task['exp'] != 0) {
            num.push({
                type: 'exp',
                value: task['exp']
            })
        }
        return num
    }
</script>
{/block}