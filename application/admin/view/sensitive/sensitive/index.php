{extend name="public/container"}
{block name="head_top"}
<style>
    .pop {
        width: 750px;
        height: 430px;
        background-color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        display: none;
        border: solid #C0C7CD 1px;
    }
    .pop-title {
        width: 100%;
        height: 40px;
        border-bottom: #EEEEEE;
        background-color: #F8F8F8;
        padding: 0 20px 0 20px;
        display: flex;
        justify-content: space-between;
    }
    .pop-title span {
        line-height: 40px;
        font-size: 14px;
        color: #333;
    }
    #close {
        display: inline-block;
        width: 20px;
        height: 20px;
        line-height: 40px;
        cursor: pointer;
    }
    .no-setting {
        width: 500px;
        margin: 150px auto 0;
        text-align: center;
    }
    .no-setting p:first-of-type{
        font-size: 20px;
        color: #333;
    }
    .no-setting p:nth-of-type(2) span{
        font-size: 18px;
        color: #2d8cf0;
    }
    .show {
        position: relative;
        top: 24px;
        opacity: 0;
    }
    .upload-file {
        width: 200px;
        height: 40px;
        line-height: 40px;
        position: absolute;
        bottom: 70px;
        left: 50%;
        transform: translateX(-50%);
    }
    .chose-btn {
        position: absolute;
        right: 150px;
        top: 120px;
    }
    #fileName {
        font-size: 14px;
        margin-right: 10px;
        position: absolute;
        left: 266px;
        top: 119px;
        width: 217px;
        height: 33px;
        line-height: 33px;
        text-align: center;
        border: solid 1px #C0C7CD;
    }
    .download-file1 {
        position: absolute;
        left: 130px;
        top: 160px;
        opacity: 0;
    }
    .copy-file {
        position: absolute;
        left: 140px;
        top: 153px;
        text-decoration: underline;
        color: #02A7F0;
    }
    .chose {
        position: absolute;
        top: 124px;
        left: 70px;
    }
    .col-position {
        position: absolute;
        left: 340px;
        top: -5px;
    }
</style>
{/block}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-row layui-col-space15" id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">

                        <div class="layui-card-body">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">关键词</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="sensitive" class="layui-input" placeholder="请输入敏感词关键字">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg12 col-position">
                                    <div class="layui-input-block" style="margin-left: 2px;float: left;top: 15px;">
                                        <div class="layui-inline">
                                            <div class="layui-input-inline">
                                                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                                    <i class="layui-icon layui-icon-search"></i>搜索</button>
                                                <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                                    <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--版块列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="alert alert-info" role="alert">
                        先下载模板再进行文件上传
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="layui-btn-container" style="display: flex;align-items: center">
                        <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:document.body.clientHeight,w:document.body.clientWidth})">添加</button>
                        <button class="layui-btn layui-btn-sm add-pop" data-type="add">批量导入</button>
                        <button class="layui-btn layui-btn-sm" data-type="remove">批量删除</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--操作-->
                    <script type="text/html" id="act_one">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('edit')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='delstor' style="background-color: #ED5565;color: #fff">
                            <i class="fa fa-warning"></i> 删除
                        </button>
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- 批量导入弹窗 -->
<div class="pop" id="pop-box">
    <div class="pop-title">
        <div><span>批量导入</span></div>
        <div id="close"><span>X</span></div>
    </div>
    <div class="pop-container">
        <div class="chose">请选择导入文件(XLS格式)</div>
        <form action="{:url('upSensitive')}" method="post" class="" enctype="multipart/form-data" id="addform">
            <div class="" style="display: flex;">
                <a class="layui-btn layui-btn-sm btn-choose chose-btn">选择文件</a>
                <div id="fileName" style="font-size: 14px;margin-right: 10px;"></div>
                <input type="file" id="examfile" name="examfile" class="choose-input" accept=".csv" style="display: none" multiple>
                <a class="layui-btn layui-btn-sm btn-submit upload-file">上传文件</a>
            </div>
        </form>
        <div><a class="copy-file" download="敏感词模板文件.csv" href="{__ADMIN_PATH}mbwj.csv">下载模板文件</a></div>
        <!-- <a class="layui-btn layui-btn-sm download-file1">下载模板文件</a> -->
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('sensitive_list')}",function (){
        join=[
            {type:'checkbox'},
            {field: 'id', title: 'ID'},
            {field: 'sensitive', title: '敏感词',width:'40%'},
            {field: 'create_time', title: '添加时间'},
            {field: 'level', title: '级别'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_one'},
        ];
        return join;
    })
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'sensitive.Sensitive',a:'delete',q:{id:data.id}});
                var code = {title:"是否要删除该敏感词",text:"删除后无法找回",confirm:'是的，我要删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
        }
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })
    function dropdown(that){
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top=offset.top-$(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if($(document).height() < top+$(that).next('ul').height()){
            $(that).next('ul').css({
                'padding': 10,
                'top': - ($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parent('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'sort':
                action.set_column('sort',id,value);
                break;
        }
    });
    layList.switch('is_status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'shop.shop_column',a:'set_on',p:{is_on:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'shop.shop_column',a:'set_on',p:{is_on:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //自定义方法
    var action={
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定要批量删除吗？ ",type:'info',confirm:'确定'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'sensitive.sensitive',a:'remove'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要删除的数据');
            }
        }
    };
    //排序
    layList.sort(function (obj) {
        var type = obj.type;
        switch (obj.field){
            case 'id':
                // layList.reload({order: layList.order(type,'p.id')},true,null,obj);
                break;
            case 'sales':
                layList.reload({order: layList.order(type,'p.sales')},true,null,obj);
                break;
        }
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

    layList.laydate.render({
        elem:'#date_time',
        trigger:'click',
        eventElem:'#zd',
        range:true,
        change:function (value){
            $('#data').val(value);
            $('#date_time').text(value);
        }
    });

    var setData = function(val, ele){
        var $data = $('#data');
        $data.val(val);
        $(ele).parent().find('button').addClass('layui-btn-primary');
        $(ele).removeClass('layui-btn-primary');
        if(val == 'zd'){
            $('#date_time').show();
        }else{
            $('#date_time').hide();
        }
    }

    // 开启批量导入弹窗
    $('.add-pop').click(function() {
        $('#pop-box').css('display', 'block')
    })
    // 关闭批量导入弹窗
    $('#close').click(function() {
        $('#pop-box').css('display', 'none')
    })
</script>

<script type="text/javascript">
  $(function(){
    $('.btn-choose').click(function(){
      $('.choose-input').click();
    });


    $("body").on("change","input[type='file']",function(){
      var filePath=$(this).val();
      if(filePath.length > 0){
        var arr=filePath.split('\\');
        var fileName=arr[arr.length-1];
        $("#fileName").html(fileName);
      }
    });

    //表单提交时判断是否有文件存在
    $(".btn-submit").click(function(){
      var filePath = $("input[name='examfile']").val();
      if(filePath === ''){
        Toast.success("请选择文件！");
        return false;
      }else{
        registPost();
      }
    });

    function registPost () {
      var files = $('#examfile')[0].files //获取上传的文件列表
      var formData = new FormData(); //新建一个formData对象
      formData.append("examfile", files[0]); //append()方法添加字段
      $.ajax({
        type: "post",
        url: "{:url('upSensitive')}",
        data: formData,
        dataType:"text",
        processData: false,
        contentType:false,
      }).success(function(message) {
        Toast.success(JSON.parse(message).msg);
        layList.reload();
      }).fail(function(err){
        Toast.success("上传失败");
      })
    }
  });
</script>
{/block}
