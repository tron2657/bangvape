{extend name="public/container"}
{block name="head_top"}

<link rel="stylesheet" href="/public/static/plug/layui2.5.5/css/eleTree.css">
<link rel="stylesheet" href="{__PLUG_PATH}layui2.5.5/css/layui.css">
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
    <div class="col-sm-12" style="background-color: #fff;min-width: 600px;">
        <div class="panel-body" style="min-width: 600px;">
            <div id="ad_alert" class="alert alert-info" role="alert" style="display: none;margin-right: -15px;">
                <i class="glyphicon glyphicon-exclamation-sign"></i>
                <span>Banner广告支持添加到固定的广告位置，在微信小程序端替换原有的自建广告</span>
            </div>
            <form class="form-horizontal" id="adForm">
                <div class="form-group">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告位ID</label>
                            <div class="layui-input-block">

                                <div class="layui-input-inline" style="width:75%">
                                    <input type="text" id="ad_unit_id" name="title" lay-verify="title" autocomplete="off" placeholder="请输入微信小程序后台创建的广告位ID" class="layui-input">
                                </div>
                                <div class="layui-input-inline" style="width:20%;float: right;">
                                    <button id="read_ad_data" type="button" class="btn btn-w-m btn-info col-md-4" style="height: 38px;">读取数据</button>
                                    <button id="update_ad_id" type="button" class="btn btn-w-m btn-info col-md-4 ad_config_none" style="height: 38px;">修改ID</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ad_config_none" id="ad_unit_info">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;"></label>
                            <div class="layui-input-block">
                                <div class="layui-bg-gray" style="padding: 0 15px;">
                                    <div class="ad-line-h">广告名称：<span id="ad_name"></span></div>
                                    <div class="ad-line-h">广告类型：<span id="ad_slot"></span></div>
                                    <div class="ad-line-h">广告状态：<span id="ad_status"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ad_config_none" id="ad_unit_position">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告位置</label>
                            <div class="layui-input-block">
                                <span style="line-height: 2.8;">固定位置（一个位置仅支持添加一个流量广告）</span>
                                <div class="layui-bg-gray" style="padding: 0 15px;">
                                    <div class="eleTree ele6" lay-filter="data6" id="treeInfo" style="padding: 15px 0;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_position_self">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告位置</label>
                            <div class="layui-input-block">
                                <span style="line-height: 2.8;" id="ad_self_value"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_position_raw">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告位置</label>
                            <div class="layui-input-block" id="ad_position_raw" style="width: 80%;padding-top:9px">
                                <input id="position_info" type="radio" name="position" value="1" title="信息流（仅横版卡片和横幅）" checked="">
                                <label for="position_info">信息流（仅横版卡片和横幅）</label>
                                <input id="position_on" type="radio" name="position" value="2" title="悬浮格子（仅格子）">
                                <label for="position_on">悬浮格子（仅格子）</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_newwork">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">新建任务</label>
                            <div class="layui-input-block">
                                <div class="layui-bg-gray" style="padding: 15px 15px;">
                                    <div class="layui-form-item" style="margin-bottom: 8px;">
                                        <label class="layui-form-label" style="width: 90px;">任务名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="title" id="task_name" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item" style="margin-bottom: 8px;">
                                        <label class="layui-form-label" style="width: 90px;">任务描述</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="task_desc" id="task_explain" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item" style="margin-bottom: 8px;">
                                        <label class="layui-form-label" style="width: 90px;">完成数量</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="ok_num" id="task_require" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item" style="margin-bottom: 8px;">
                                        <label class="layui-form-label" style="width: 90px;">任务积分</label>
                                        <div id="add_integral">
                                            <div class="layui-input-block" style="margin-bottom: 8px;">
                                                <div class="layui-input-inline">
                                                    <select id="task_integral_type" class="form-control task_integral_type" style="height: 38px;">
                                                        <option value="one">想天点</option>
                                                        <option value="buy">购物积分</option>
                                                        <option value="gong">贡献值积分</option>
                                                        <option value="fly">社区积分</option>
                                                        <option value="exp">积分类型-经验值积分</option>
                                                    </select>
                                                </div>
                                                <div class="layui-input-inline" style="width: 100px;">
                                                    <input type="number" id="task_integral" value="10" placeholder="请输入" class="layui-input task_integral">
                                                </div>
                                                <div class="layui-input-inline del_integral" style="width:36px">
                                                    <i class="layui-icon layui-icon-close-fill" id="del_" style="font-size: 36px; color: #1E9FFF;"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="layui-input-block">
                                            <button type="button" style="margin-top: 0px;width:100%" class="layui-btn layui-btn-primary" id="add_integral_type">添加积分</button>
                                        </div>
                                    </div>
                                    <div class="layui-form-item" style="margin-bottom: 8px;">
                                        <label class="layui-form-label" style="width: 90px;">任务图标</label>
                                        <div class="layui-input-block">
                                            <div class="layui-input-inline">
                                                <input type="file" class="upload" name="image" style="display: none;" id="image" />
                                                <a style="display: block;width: 102px;border: 1px solid #E5E6E7;" class="btn-sm add_image upload_span">
                                                    <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                                        <input value="" type="hidden" id="image_input" name="local_url" />
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ad_config_none" id="ad_unit_trigger_scene">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">触发场景</label>
                            <div class="layui-input-block">
                                <div class="layui-bg-gray" id="ad_change_checked" style="padding: 15px 15px;">
                                    <input type="checkbox" name="ad_checked_trigger" value="1" />
                                    <span class="ad-trigger-check">商城付款成功</span><br>
                                    <input type="checkbox" name="ad_checked_trigger" lay-skin="primary" value="2" title="底部Tab栏切换（插屏广告2）" />
                                    <span class="ad-trigger-check">底部Tab栏切换（插屏广告2）</span><br>
                                    <input type="checkbox" name="ad_checked_trigger" lay-skin="primary" value="3" title="视频播放暂停" />
                                    <span class="ad-trigger-check">视频播放暂停</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_trigger_interval">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">触发间隔</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline" style="width: 75%">
                                    <input type="number" value="1" id="ad_change_interval" placeholder="请输入" class="layui-input">
                                </div>
                                <div style="width: 20%;float: right;">
                                    <select id="ad_time" class="form-control" style="height: 38px;">
                                        <option value="0">分钟</option>
                                        <option value="1">小时</option>
                                        <option value="2">天</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_theme">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">广告主题</label>
                            <div class="layui-input-block" id="ad_theme" style="width: 80%;padding-top:9px">
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
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_gridnum">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">格子个数</label>
                            <div class="layui-input-block" id="grid_count" style="width: 80%;padding-top:9px">
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
                    </div>
                </div>

                <div class="form-group ad_config_none" id="ad_unit_remark">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 100px;">备注</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" id="ad_all_remark" lay-verify="title" autocomplete="off" placeholder="请输入备注" class="layui-input">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="layui-row">
                        <div class="layui-form-item">
                            <div class="grid-demo" style="float: right;">
                                <button id="enter_add_cancel" type="button" class="btn col-md-4" style="height: 38px;">取消</button>
                                <button id="enter_add_ad" type="button" class="btn btn-info col-md-4" style="height: 38px;">确定</button>
                            </div>
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
    // info is 所有权限
    var info = eval('<?php echo json_encode($open_list);?>')
    var power = false
    for(var i=0;i<info.length;i++) {
        if(info[i] === 'weixin_flow') {
            power = true
        }
    }
    var ad_slot_alert = {
        "SLOT_ID_WEAPP_BANNER": "Banner广告支持添加到固定的广告位置，在微信小程序端替换原有的自建广告",
        "SLOT_ID_WEAPP_REWARD_VIDEO": "激励式广告支持以日常任务的形式添加到用户的任务列表中，观看广告获取积分",
        "SLOT_ID_WEAPP_INTERSTITIAL": "插屏广告支持选择触发场景，对应操作触发后显示广告",
        "SLOT_ID_WEAPP_VIDEO_FEEDS": "视频广告支持在频道信息流中随机展示，可在基础设置中编辑展现频次",
        "SLOT_ID_WEAPP_VIDEO_BEGIN": "视频贴片广告支持在视频内容播放前显示",
        "SLOT_ID_WEAPP_BOX": "格子广告支持添加到固定的广告位置，在微信小程序端替换原有的自建广告",
        "SLOT_ID_WEAPP_TEMPLATE": "原生模版广告仅支持横版卡片、横幅和格子形式，请确保形式正确"
    };
    var ad_slot_alert_error = {
        "SLOT_ID_WEAPP_REWARD_VIDEO_ERROR": "您未购买任务系统，无法添加激励式广告",
        "SLOT_ID_WEAPP_VIDEO_BEGIN_ERROR": "您未购买横版视频模块，无法添加视频贴片广告",
        "SLOT_ID_ERROR": "广告位ID错误，请前往微信小程序后台查看广告位ID",
        "SLOT_ID_IS_NULL": "请输入广告位ID并读取数据",
        "SLOT_ID_IS_CREATE": "当前广告位ID已创建",
        "SLOT_SELF_ERROR": "固定位置广告仅支持Banner广告和格子广告"
    };
    var ad_slot_alert_error_power = {
        "SLOT_ID_WEAPP_BANNER": "您未购买任务系统，无法添加Banner广告",
        "SLOT_ID_WEAPP_REWARD_VIDEO": "您未购买任务系统，无法添加激励式广告",
        "SLOT_ID_WEAPP_INTERSTITIAL": "您未购买任务系统，无法添加插屏广告",
        "SLOT_ID_WEAPP_VIDEO_FEEDS": "您未购买任务系统，无法添加视频广告",
        "SLOT_ID_WEAPP_VIDEO_BEGIN": "您未购买任务系统，无法添加视频贴片",
        "SLOT_ID_WEAPP_BOX": "您未购买任务系统，无法添加格子广告",
        "SLOT_ID_WEAPP_TEMPLATE": "您未购买任务系统，无法添加原生模板广告",
    }
    var ad_id_hint = {

    }
    var ad_slot_obj = {
        "AD_UNIT_TYPE_BANNER": "Banner广告",
        "AD_UNIT_TYPE_REWARED_VIDEO": "激励视频广告",
        "AD_UNIT_TYPE_INTERSTITIAL": "插屏广告",
        "AD_UNIT_TYPE_VIDEO_FEEDS": "视频广告",
        "AD_UNIT_TYPE_VIDEO_BEGIN": "视频贴片广告",
        "AD_UNIT_TYPE_BOX": "格子广告",
        "AD_UNIT_TYPE_TEMPLATE": "原始模板广告",
    };
    var type_list = {
        "2": "社区首页广告位",
        "4": "帖子详情页广告位",
        "5": "个人中心广告位",
        "7": "首页精品推荐广告位",
        "8": "商城首页广告位",
        "9": "推广中心广告位",
        "10": "积分商城首页广告位",
        "11": "知识商城首页广告位",
        "13": "认证中心首页广告位",
        "97": "板块列表页广告位"
    }
    var is_tree = null
    var ad_self_build_type = '{$type}'
    // 读取ID
    $('#read_ad_data').on('click', function() {
        var ad_name, ad_slot, ad_info, ad_status, ad_id
        let ad_unit_id = $('#ad_unit_id').val();
        if (ad_unit_id == '') {
            $eb.message('error', '请填写广告位ID！')
            return;
        }
        $.ajax({
            url: "{:Url('get_ad_unit')}",
            data: {
                ad_unit_id: ad_unit_id
            },
            type: 'get',
            dataType: 'json',
            success: function(res) {
                if (res.code == 0 && res.count > 0) {
                    var ad_unit = res.data[0];
                    console.log(ad_unit);
                    window.ad_name = ad_unit.ad_unit_name
                    window.ad_slot = ad_unit.ad_slot
                    window.ad_info = JSON.stringify(ad_unit)
                    $('#ad_name').text(ad_unit.ad_unit_name);
                    $('#ad_slot').text(ad_slot_obj[ad_unit.ad_unit_type]);
                    if (ad_unit.ad_unit_status === 'AD_UNIT_STATUS_ON') {
                        $('#ad_status').text('已开启').attr('class', 'text-info');
                        window.ad_status = 1
                    } else {
                        $('#ad_status').text('已关闭').attr('class', 'text-muted');
                        window.ad_status = 0
                    }
                    $('#ad_unit_info').show();
                    if (power) {
                        $('#ad_unit_remark').show();
                    }
                    console.log(ad_self_build_type, '哈哈哈哈哈 ')

                    switch (ad_unit.ad_slot) {
                        case 'SLOT_ID_WEAPP_BANNER':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            if (ad_self_build_type) {
                                $('#ad_unit_position_self').show();
                                $('#ad_self_value').text(type_list[ad_self_build_type])
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                                $('#ad_alert').removeClass('alert-warning')
                            } else {
                                $('#ad_unit_position').show();
                            }
                            ad_position_tree()
                            break;
                        case 'SLOT_ID_WEAPP_REWARD_VIDEO':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            $('#ad_unit_newwork').show();
                            if(ad_self_build_type) {
                                $('#ad_alert span').text(ad_slot_alert_error['SLOT_SELF_ERROR']);
                                $('#ad_alert').addClass('alert-warning')
                            } else {
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                            }
                            break;
                        case 'SLOT_ID_WEAPP_INTERSTITIAL':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            $('#ad_unit_trigger_scene').show();
                            $('#ad_unit_trigger_interval').show();
                            if(ad_self_build_type) {
                                $('#ad_alert span').text(ad_slot_alert_error['SLOT_SELF_ERROR']);
                                $('#ad_alert').addClass('alert-warning')
                            } else {
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                            }
                            break;
                        case 'SLOT_ID_WEAPP_VIDEO_FEEDS':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            $('#ad_unit_theme').show();
                            if(ad_self_build_type) {
                                $('#ad_alert span').text(ad_slot_alert_error['SLOT_SELF_ERROR']);
                                $('#ad_alert').addClass('alert-warning')
                            } else {
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                            }
                            break;
                        case 'SLOT_ID_WEAPP_VIDEO_BEGIN':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            if(ad_self_build_type) {
                                $('#ad_alert span').text(ad_slot_alert_error['SLOT_SELF_ERROR']);
                                $('#ad_alert').addClass('alert-warning')
                            } else {
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                            }
                            break;
                        case 'SLOT_ID_WEAPP_BOX':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            ad_position_tree();
                            $('#ad_unit_theme').show();
                            $('#ad_unit_gridnum').show();
                            if (ad_self_build_type) {
                                $('#ad_unit_position_self').show();
                                $('#ad_self_value').text(type_list[ad_self_build_type])
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                                $('#ad_alert').removeClass('alert-warning')
                            } else {
                                $('#ad_unit_position').show();
                            }
                            break;
                        case 'SLOT_ID_WEAPP_TEMPLATE':
                            if (!power) {
                                $('#ad_alert').show();
                                $('#ad_alert span').text(ad_slot_alert_error_power[ad_unit.ad_slot]);
                                $('#ad_alert').addClass('alert-warning')
                                return
                            }
                            $('#ad_unit_position_raw').show();
                            if(ad_self_build_type) {
                                $('#ad_alert span').text(ad_slot_alert_error['SLOT_SELF_ERROR']);
                                $('#ad_alert').addClass('alert-warning')
                            } else {
                                $('#ad_alert span').text(ad_slot_alert[ad_unit.ad_slot]);
                            }
                            break;
                    }
                    $('#ad_alert').show();
                    $('#ad_unit_id').attr('disabled', 'disabled');
                    console.log()
                    $('#read_ad_data').hide();
                    $('#update_ad_id').show();
                } else {
                    $eb.message('error', res.msg)
                }
            }
        })
    })

    // 修改ID
    $("#update_ad_id").on('click', function() {
        layer.confirm('修改ID将重置您已经编辑的数据', {
            btn: ['确定', '取消'] //按钮
        }, function(ind) {
            $('#ad_unit_info').hide();
            $('#ad_unit_position').hide();
            $('#ad_unit_newwork').hide();
            $('#ad_unit_trigger_scene').hide();
            $('#ad_unit_trigger_interval').hide();
            $('#ad_unit_theme').hide();
            $('#ad_unit_theme').hide();
            $('#ad_unit_gridnum').hide();
            $('#ad_unit_position_raw').hide();
            $('#ad_unit_position_self').hide();
            $('#read_ad_data').show();
            $('#update_ad_id').hide();
            $('#ad_unit_remark').hide();
            $('#ad_alert').hide();
            $('#ad_unit_id').attr("disabled", false);
            document.getElementById("adForm").reset()
            layer.close(ind);
        }, function() {});
    })

    // 添加确定
    $("#enter_add_ad").on('click', function() {
        if (!power) {
            layer.open({
                title: '提示',
                content: '您未购买任务系统，无法添加广告'
            });
            return
        }
        if (!$('#ad_unit_id').val()) {
            layer.open({
                title: '提示',
                content: ad_slot_alert_error['SLOT_ID_IS_NULL']
            });
            return
        }
        if (ad_self_build_type) {
            $eb.message('error', ad_slot_alert_error['SLOT_SELF_ERROR'])
            return
        } 
        if (ad_slot === 'SLOT_ID_WEAPP_BANNER' || ad_slot === 'SLOT_ID_WEAPP_BOX') {
            return
        }
        ad_id = $('#ad_unit_id').val()
        var data = {
            name: ad_name,
            ad_unit_id: ad_id,
            ad_slot: ad_slot,
            ad_info: ad_info,
            status: ad_status,
            trigger_scene: [],
            trigger_gap: 0,
            ad_theme: null,
            position: null,
            remark: $('#ad_all_remark').val(),
            task: {}
        }
        switch (ad_slot) {
            case 'SLOT_ID_WEAPP_REWARD_VIDEO':
                data.task.name = $('#task_name').val();
                data.task.explain = $('#task_explain').val();
                data.task.require = $('#task_require').val();
                data.task.icon = $("#image_input").val();
                // var integral = $('#task_integral_type').val();
                $('#add_integral .task_integral_type').each(function() {
                    var type = $(this).val()
                    console.log(type)
                    console.log($(this).parent().parent().find('.task_integral').val())
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
                            data.task.exp = $(this).parent().parent().find('.task_integral').val()
                            break;
                    }
                })

                // var num = $('#task_integral').val();
                // data['task'][integral] = num
                break;
            case 'SLOT_ID_WEAPP_INTERSTITIAL':
                $('#ad_change_checked input[name="ad_checked_trigger"]:checked ').each(function(i) {
                    data.trigger_scene.push($(this).val())
                })
                var num = $('#ad_change_interval').val()
                var time_type = $('#ad_time').val();
                data.trigger_gap = adtime(num, time_type)
                break;
            case 'SLOT_ID_WEAPP_VIDEO_FEEDS':
                data.ad_theme = $('#ad_theme input[name="color"]:checked').val()
                console.log(ad_theme)
                break;
            case 'SLOT_ID_WEAPP_VIDEO_BEGIN':
                break;
            case 'SLOT_ID_WEAPP_TEMPLATE':
                data.position = $('#ad_position_raw input[name="position"]:checked').val()
                break;
        }

        $.ajax({
            url: "{:Url('add_ad')}",
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res.code == 200) {
                    parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                    console.log('成功ss')
                } else {
                    $eb.message('error', res.msg)
                }
                console.log(res)
            }
        })
    })

    // eleTree
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
            $("#enter_add_ad").click(function() {
                if (ad_slot === 'SLOT_ID_WEAPP_BANNER' || ad_slot === 'SLOT_ID_WEAPP_BOX') {
                    var ad_type = []
                    var ad_theme = null
                    var grid_count = null
                    ad_id = $('#ad_unit_id').val()
                    var checkedData = el6.getChecked(false, true); //获取选中节点的数据
                    if (ad_self_build_type) {
                        ad_type = [ad_self_build_type]
                    } else {
                        for (var i = 0; i < checkedData.length; i++) {
                            for (var j = 0; j < checkedData[i].children.length; j++) {
                                ad_type.push(checkedData[i].children[j].id)
                            }
                        }
                    }
                    if (ad_slot === 'SLOT_ID_WEAPP_BOX') {
                        ad_theme = $('#ad_theme input[name="color"]:checked').val()
                        grid_count = $('#grid_count input[name="num"]:checked').val()
                    }
                } else {
                    return
                }
                $.ajax({
                    url: "{:Url('add_ad')}",
                    data: {
                        name: ad_name,
                        ad_unit_id: ad_id,
                        ad_slot: ad_slot,
                        ad_info: ad_info,
                        status: ad_status,
                        ad_type: ad_type,
                        ad_theme: ad_theme,
                        grid_count: grid_count
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.code == 200) {
                            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
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

    // 取消
    $("#enter_add_cancel").click(function() {
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    })



    //添加积分
    $("#add_integral_type").click(function() {
        $("#add_integral").append(function() {
            return '<div class="layui-input-block" style="margin-bottom: 8px;"><div class="layui-input-inline"><select id="task_integral_type" class="form-control task_integral_type" style="height: 38px;"><option value="one">想天点</option><option value="buy">购物积分</option><option value="gong">贡献值积分</option><option value="fly">社区积分</option><option value="exp">积分类型-经验值积分</option></select></div><div class="layui-input-inline" style="width: 100px;"><input type="number" id="task_integral" value="10" placeholder="请输入" class="layui-input task_integral"></div><div class="layui-input-inline del_integral" style="width:36px"><i class="layui-icon layui-icon-close-fill" id="del_" style="font-size: 36px; color: #1E9FFF;"></i></div></div>'
        })
    })

    //删除添加积分
    $(document).on("click", ".del_integral", function() {
        $(this).parent().remove();
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
            url: "{:Url('get_type_tree')}",
            type: 'get',
            dataType: 'json',
            success: function(res) {
                is_tree = res.data
                get_type_tree(is_tree)
            }
        })
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
</script>
{/block}