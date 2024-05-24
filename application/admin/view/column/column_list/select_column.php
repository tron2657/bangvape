{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<style>
    .gray-bg {
        background-color: #fff;
    }
    .layui-laypage{
        margin: 0;
    }
    .wrapper-content{
        padding-bottom: 10px;
    }
</style>
{/block}
{block name="content"}
<div class="body">
    <div class="top-content" style="display: flex;justify-content: space-between">
        <div style="display: flex;">
            <input type="text" style="width:150px;margin-right: 5px;" name="author_name" class="layui-input" placeholder="输入专栏名称">
            <button class="layui-btn layui-btn-sm layui-btn-primary" style="background-color: #f2f2f2;height: 100%;color: #6f6f6f;padding: 0 20px">搜索</button>
        </div>
        <button class="layui-btn layui-btn-sm layui-btn-normal" style="padding: 0 20px" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:700,w:650})">新建专栏</button>
    </div>
    <div style="display: flex;background-color: #f2f2f2;width: 100%;padding: 10px;margin-top: 10px;justify-content: space-between">
        <div style="color: #333;flex: 3">专栏</div>
        <div style="color: #333;flex: 1.5">状态</div>
        <div style="color: #333">价格</div>
    </div>
    <div id="list_content" style="height: 262px;overflow: auto">

    </div>
    <div id="page_wrapper" style="margin-top: 10px;height: 35px;"></div>
    <div style="border-top: 1px solid #eee;display: flex;justify-content: flex-end;padding-top: 10px;padding-right: 10px">
        <button type="button" class="btn btn-info save_news" style="width: 90px!important;" id="save">确定</button>
    </div>
</div>
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    var laypage = '';
    layui.use('laypage', function(){
        laypage = layui.laypage;
    });
    $.ajax({
        url: "{:Url('column_list')}",
        data: {
            page: 1,
            limit: 10,
            is_column: 1,
            is_show: 1,
        },
        type: 'get',
        dataType: 'json',
        success: function (res) {
            //执行一个laypage实例
            laypage.render({
                elem: 'page_wrapper', //注意，这里的 test1 是 ID，不用加 # 号
                count: res.count, //数据总数，从服务端得到
                limit: 10,
                jump: function (obj, first) {
                    jumpPage(obj.curr)
                }
            });
            if(res.count <= 10){
                $("#page_wrapper").hide();
            }
            var data = res.data;
            var html = '';
            var isShow = '';
            var payNum = '';
            for(var i in data){
                if(data[i].is_show){
                    isShow = "已上架";
                }else {
                    isShow = "未上架";
                }
                if(data[i].is_free === '免费'){
                    payNum = "免费";
                }else {
                    payNum = "￥"+data[i].price;
                }
                html += '<div style="display: flex;align-items: center;padding: 10px;border-bottom: 1px solid #eee;">\n' +
                    '            <input name="task" style="margin-top: 0;margin-right: 10px;" type="checkbox" value="store_pay" id="store_pay"/>\n' +
                    '            <img src="'+data[i].image+'" style="width: 48px;height: 48px;" alt="">\n' +
                    '            <div style="margin-left: 10px;width: 228px">\n' +
                    '                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">'+data[i].name+'</div>\n' +
                    '                <div style="margin-top: 5px;">'+data[i].nickname+'</div>\n' +
                    '            </div>\n' +
                    '            <div>'+isShow+'</div>\n' +
                    '            <div style="width: 144px;text-align: right;color: #F95E5A">'+payNum+'</div>\n' +
                    '        </div>'
            }
            $("#list_content").html(html);
        }
    });
    function jumpPage(page) {
        $.ajax({
            url: "{:Url('column_list')}",
            data: {
                page: page,
                limit: 10,
                is_column: 1,
                is_show: 1,
            },
            type: 'get',
            dataType: 'json',
            success: function (res) {
                var data = res.data;
                var html = '';
                var isShow = '';
                var payNum = '';
                if(data.length === 0){
                    html = '<div style="text-align: center;margin-top: 100px;">无专栏数据，<span style="color: #0ca6f2;cursor: pointer" onclick="$eb.createModalFrame(\'新建专栏\',\'{:Url(\'create\')}\',{h:700,w:650})">立即创建</span></div>';
                    $("#list_content").html(html);
                    return;
                }
                for(var i in data){
                    if(data[i].is_show){
                        isShow = "已上架";
                    }else {
                        isShow = "未上架";
                    }
                    if(data[i].is_free === '免费'){
                        payNum = "免费";
                    }else {
                        payNum = "￥"+data[i].price;
                    }
                    var toData = {
                        name:data[i].name,
                        nickname:data[i].nickname,
                        price:data[i].price,
                        image:data[i].image,
                        id:data[i].id,
                    };
                    toData = JSON.stringify(toData)
                    html += '<div style="display: flex;align-items: center;padding: 10px;border-bottom: 1px solid #eee;">\n' +
                        '            <input name="task" style="margin-top: 0;margin-right: 10px;" type="checkbox" value='+toData+' id="store_pay"/>\n' +
                        '            <img src="'+data[i].image+'" style="width: 48px;height: 48px;" alt="">\n' +
                        '            <div style="margin-left: 10px;width: 228px">\n' +
                        '                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">'+data[i].name+'</div>\n' +
                        '                <div style="margin-top: 5px;">'+data[i].nickname+'</div>\n' +
                        '            </div>\n' +
                        '            <div style="flex-shrink: 0;">'+isShow+'</div>\n' +
                        '            <div style="width: 144px;text-align: right;color: #F95E5A">'+payNum+'</div>\n' +
                        '        </div>'
                }
                $("#list_content").html(html);
            }
        });
    }
    $("#save").on("click",function () {
        var taskList=[];
        $('input[name="task"]:checked').each(function(){
            var data = JSON.parse($(this).val());
            taskList.push(data);//向数组中添加元素
        });
        taskList = JSON.stringify(taskList)
        window.localStorage.setItem("add_column",taskList);
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    })
</script>
{/block}
