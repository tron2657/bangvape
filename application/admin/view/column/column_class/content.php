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

    .blue-text {
        color: #0ca6f2;
    }
    .layui-laypage{
        margin: 0;
    }
    .refresh-btn {
        cursor: pointer;
        border: 1px solid #797979;
        background-color: #f2f2f2;
        color: #666;
        border-radius: 5px;
        width: 80px;
        text-align: center;
        height: 34px;
        line-height: 32px;
    }
    .tips-line{
        border: 1px solid #409eff;
        background-color: #c6e2ff;
        color: #409EFF;
        height: 40px;
        line-height: 38px;
        padding-left: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    input[type="number"]{
        -moz-appearance: textfield;
    }
</style>
{/block}
{block name="content"}
<div class="body">
    <div style="display: flex;justify-content: space-between">
        <div style="display: flex;justify-content: space-between;">
            <div style="width: 180px;color: #333">
                <div>当前栏目样式为：<span class="blue-text" id="top1">纵向双列</span></div>
                <div>栏目内总商品数：<span class="blue-text" id="top2">3</span></div>
            </div>
            <div style="width: 180px;color: #333">
                <div>行数：<span class="blue-text" id="top3">1</span></div>
                <div>首页显示商品数：<span class="blue-text" id="top4">2</span></div>
            </div>
        </div>
        <div class="refresh-btn">刷新</div>
    </div>
    <div class="tips-line">说明：商品的排序权重越大，排序越靠前</div>
    <div style="display: flex;background-color: #f2f2f2;width: 100%;padding: 10px;margin-top: 10px;justify-content: space-between">
        <div style="color: #333;flex: 3">商品</div>
        <div style="color: #333;flex: 1.5">首页显示</div>
        <div style="color: #333">排序权重</div>
    </div>
    <div class="list-content" id="list_content" style="height: 300px;overflow: auto">

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
        url: "{:Url('content_list')}",
        data: {
            pid: "{$pid}",
            page: 1,
            limit : 10
        },
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            var data = res.count;
            $("#top3").html(data.num);
            $("#top2").html(data.product_count);
            if (data.type === 1) {
                $("#top1").html("纵向单列");
                $("#top4").html(1 * data.num);
            } else {
                $("#top1").html("纵向双列");
                $("#top4").html(2 * data.num);
            }
            //执行一个laypage实例
            laypage.render({
                elem: 'page_wrapper', //注意，这里的 test1 是 ID，不用加 # 号
                count: data.product_count, //数据总数，从服务端得到
                limit: 10,
                jump: function (obj, first) {
                    jumpPage(obj.curr)
                }
            });
            if(data.product_count <= 10){
                $("#page_wrapper").hide();
            }
        }
    });
    function jumpPage(page) {
        $.ajax({
            url: "{:Url('content_list')}",
            data: {
                page: page,
                limit: 10,
                pid: "{$pid}",
            },
            type: 'get',
            dataType: 'json',
            success: function (res) {
                var data = res.count.product;
                var price = "";
                var html = '';
                var isShow = "";
                var typeName = "";
                var showNum = parseInt($("#top4").html());
                var pageBase = (page-1)*10;
                if(data.length === 0){
                    html = '<div style="text-align: center;margin-top: 100px;">无内容，请在商品编辑页添加关联栏目</div>';
                    $("#list_content").html(html);
                    return;
                }
                for(var i in data){
                    if(data[i].info.price === "0.00"){
                        price = "免费"
                    }else {
                        price = "￥"+data[i].info.price
                    }
                    if(data[i].info.type === 0){
                        typeName = "专栏";
                    }else if(data[i].info.type === 1){
                        typeName = "图文";
                    }else if(data[i].info.type === 2){
                        typeName = "音频";
                    }else if(data[i].info.type === 3){
                        typeName = "视频";
                    }
                    if(pageBase+parseInt(i)+1 <= showNum){
                        isShow = "显示"
                    }else {
                        isShow = ""
                    }
                    html += '<div style="display: flex;align-items: center;padding: 10px;border-bottom: 1px solid #eee;">\n' +
                        '            <img src="'+data[i].info.image+'" style="width: 48px;height: 48px;" alt="">\n' +
                        '            <div style="margin-left: 10px;width: 418px;">\n' +
                        '                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;display: flex;align-items: center">'+data[i].info.name+'<span style="margin-left: 5px;border: 1px solid #666;background-color: #f2f2f2;padding: 0 10px;font-size: 12px;border-radius: 4px">'+typeName+'</span></div>\n' +
                        '                <div style="margin-top: 5px;"><span style="width: 100px;display: inline-block;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">'+data[i].info.nickname+'</span><span style="color: #F95E5A;margin-left: 5px;">'+price+'</span></div>\n' +
                        '            </div>\n' +
                        '            <div style="width: 26px;">'+isShow+'</div>\n' +
                        '            <div class="sort-btn" data-id="'+data[i].id+'" style="width: 52px;text-align: center;margin-left: 188px;cursor: pointer">'+data[i].sort+'</div>\n' +
                        '        </div>'
                }
                $("#list_content").html(html);
            }
        });
    }
    $(".refresh-btn").on("click",function () {
        jumpPage(1)
    });
    $("body").on("click",".sort-btn",function () {
        var id = $(this).data("id");
        var sort = $(this).html();
        $(this).after('<input class="sort-input" data-id="'+id+'" type="number" value="'+sort+'" style="width: 52px;margin-left: 188px;">');
        $(".sort-input").focus();
        $(this).remove();
    });
    $("body").on("blur",".sort-input",function () {
        var id = $(this).data("id");
        var sort = $(this).val();
        $.ajax({
            url: "{:Url('sort')}",
            data: {
                id: id,
                sort: sort
            },
            type: 'post',
            dataType: 'json',
            success: function (res) {
                console.log(res)
            }
        });
        $(this).after('<div class="sort-btn" data-id="'+id+'" style="width: 52px;text-align: center;margin-left: 188px;cursor: pointer">'+sort+'</div>');
        $(this).remove();
    });
</script>
{/block}
