{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-tab layui-tab-brief" lay-filter="tab">

    </div>
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">栏目名称</label>
                                <div class="layui-input-block">
                                    <input type="text" style="width:250px" name="name" class="layui-input" placeholder="请输入栏目名称">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                   <!-- <button class="layui-btn layui-btn-primary layui-btn-sm export"  lay-submit="export" lay-filter="export">
                                        <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>-->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--产品列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-btn-container">
                        {if condition="$is_free_ban AND $is_end_ban"}
                        <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:700,w:1100})">添加栏目</button>
                        {else/}
                        <button class="layui-btn layui-btn-sm" data-type="unable">添加作者</button>
                        {/if}
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--图片-->
                    <script type="text/html" id="image">
                        <img style="cursor: pointer" lay-event="open_image" src="{{d.img}}">
                    </script>
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='显示|不显示'  {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                    <!--收藏-->
                    <script type="text/html" id="like">
                        <span><i class="layui-icon layui-icon-praise"></i> {{d.like}}</span>
                    </script>
                    <!--操作-->
                    <script type="text/html" id="act">
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-编辑','{:Url('edit')}?id={{d.id}}',{h:700,w:1100})">
                            编辑
                        </button>
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-内容管理','{:Url('content')}?id={{d.id}}',{h:600,w:800})">
                            内容管理
                        </button>
                        <button class="layui-btn layui-btn-xs layui-btn-normal" lay-event='delstor'>
                            删除
                        </button>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('class_list')}",function (){
        return join=[
            {field: 'id', title: 'ID', sort: true,event:'id',width:'6%'},
            {field: 'name', title: '栏目名称',width:'10%'},
            {field: 'summary', title: '描述'},
            {field: 'type', title: '栏目样式',width:'6%'},
            {field: 'status', title: '上架状态',templet:"#checkboxstatus",width:'8%'},
            {field: 'sort', title: '排序',sort: true,event:'sort'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'}
        ];
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })
    // 操作下拉
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

    //上下加产品
    layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'column.column_class',a:'set_status',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'column.column_class',a:'set_status',p:{is_show:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'column.column_author',a:'delete',q:{id:data.id}});
                var code = {title:"操作提示",text:"确定要删除吗？",type:'info',confirm:'是的，删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
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
    //排序
    layList.sort(function (obj) {
        var type = obj.type;
        switch (obj.field){
            case 'id':
                layList.reload({order: layList.order(type,'id')},true,null,obj);
                break;
            case 'sort':
                layList.reload({order: layList.order(type,'sort')},true,null,obj);
                break;
        }
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    //自定义方法
    var action={
        set_product:function(field,id,value){
            layList.baseGet(layList.Url({c:'column.column_list',a:'set_product',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
        show:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'column.column_list',a:'product_show'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要上架的产品');
            }
        },
        unable:function(){
            var code = {title:"提示",text:"该功能未开通或已过期，如需开通，请联系客服！",type:'info',confirm:'联系客服',cancel:'取消',confirmBtnColor:'#0ca6f2'};
            $eb.$swal('delete',function(){
                $eb.createModalFrame('联系客服','https://h5a.opensns.cn/auth/Index/tip_box.html',{h:600,w:700})
            }, code)
        },
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });
</script>
{/block}
