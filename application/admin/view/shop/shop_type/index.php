{extend name="public/container"}
{block name="head_top"}
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
<style>
body {
    font-size: 13px !important;
}
.box {
    background-color: #fff;
    height: 130px;
    border-radius: 4px;
}
.line {
    display: flex;
    font-weight: 400;
    color: #666666;
    width: 1500px;
}
.container {
    padding-top: 100px;
}
.chose-box {
    width: 500px;
    display: flex;
    position: relative;
    left: 47px;
}
.chose-box label{
    font-weight: normal !important;
}
.line>div:first-of-type {
    width: 250px;
    margin-right: 15px;
}
.line>div:first-of-type>span {
    display: inline-block;
    float: right;
    font-weight: 750;
}
.bottom {
    margin-top: 10px;
}
#checkedLevel {
    border: solid 1px #E5E6E7;
}
.submit {
    width: 100%;
    height: 30px;
    border-radius: 4px;
    border: none;
    background-color: #02A7F0;
    color: #fff;
}
option {
    border: solid 1px #E5E6E7;
    appearance: none
}
.type-select {
    width: 500px;
    position: relative;
    left: -33px;
}
.checks {
    margin-right: 4px;
}
.text {
    display: inline-block;
    margin-left: 5px;
}
</style>
{/block}
{block name="content"}

<!-- <div class="layui-fluid"> -->
    <!-- <div class="layui-row layui-col-space15"  id="app"> -->

        <!--产品列表-->
        <!-- <div class="layui-col-md12"> -->
            <!-- <div class="layui-card"> -->
                <!-- <div class="layui-card-header">积分类型列表</div> -->
                <!-- <div class="layui-card-body"> -->
                    <!-- <div class="layui-btn-container"> -->
<!--                        <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}')">添加积分类型</button>-->
                        <!-- <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame('积分商城兑换类型','{:Url('edit_score_type')}')" style="margin-top: 10px">积分商城兑换类型</button> -->
<div class="row gray-bg">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">

            <div class="tabs-container ibox-title">
            <div class="tab-content">
                <div class="<!--ibox-content--> p-m m-t-sm">
                        <div class="box">
                            <div>
                            <div class="top line" style="position: relative;left: -27px;">
                                <div style="position: relative;left: 33px;"><span>是否开启积分商城</span></div>
                                <div class="chose-box" data-open="{$is_on}">
                                    <div class="i-checks checks">
                                        <label for="chose1"><input type="radio" id="chose1" name="chose" class="radioItem"><span class="text">开启</span></label>
                                    </div>
                                    <div class="i-checks">
                                        <label for="chose2"><input type="radio" id="chose2" name="chose" class="radioItem"><span class="text">关闭</span></label>
                                    </div>
                                </div>
                                <div style="position: relative;left: 27px">如关闭积分商城，则前端不会在【我的】页面、常用工具中显示"积分商城"图标及入口</div>
                            </div>
                            <div class="bottom line">
                                <div style="position: relative;left: 6px;"><span>积分商城支持积分类型</span></div>
                                <div class="type-select">
                                <select id='checkedLevel' style="width:300px;height:28px;position: relative;left: 54px;top: -4px;">
                                {volist name="list" id="vo"}
                                    {if condition = "$vo['flag'] eq $type"}
　　                            　　<option data-flag="{$vo.flag}" selected>{$vo.name}</option>
                                    {else/}
　　                            　　<option data-flag="{$vo.flag}">{$vo.name}</option>
                                    {/if}
                                {/volist}                                                                                                                                                        
　　                            </select>   
                                </div>
                                <div>即设置积分商城支持使用哪种积分类型</div>
                            </div>
                                <div class="form-group" style="text-align: center;margin-top: 15px">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <div class="btn btn-primary" data-role="submit" class="submit" onclick="submit()">提交</div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script>
var is_on;
var flag;
$().ready(function() {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});
is_on = $('.chose-box').attr('data-open');

if($('.chose-box').attr('data-open') == 0) {
    $('input[id="chose2"]').iCheck('check');
} else if ($('.chose-box').attr('data-open') == 1) {
    $('input[id="chose1"]').iCheck('check');
}
$('input[name="chose"]').on('ifChanged', function(){
        if ($('input[id="chose1"]').prop("checked")) {
                is_on = 1;
            } else if ($('input[id="chose2"]').prop("checked")){
                is_on = 0;
        }
    })

$("#checkedLevel").change(function(e){
    var ind = document.getElementById("checkedLevel").selectedIndex;
	flag = $('option').eq(ind).attr('data-flag');
});
    var ind = document.getElementById("checkedLevel").selectedIndex;
	flag = $('option').eq(ind).attr('data-flag'); 

function submit() {
    $.ajax({
        type: 'POST',
        url: "{:Url('save_score_type')}",
        data: {is_on,flag},
        dataType: 'json',
        success(res) {
            console.log(res)
            $eb.message('success',res.msg)
        }
    })
}
</script>
{/block}
