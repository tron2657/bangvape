{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="margin-top:10px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-block">
                                    <select name="status">
                                        <option value="">全部</option>
                                        <option value="1">启用</option>
                                        <option value="0">禁用</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
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
                <div class="layui-card-header">服务保障列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="margin-top:10px">
                        <button type="button" class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('services_create')}')">添加分类</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="icon">
                        <img style="cursor: pointer" lay-event='open_image' src="{{d.icon}}">
                    </script>
                    <script type="text/html" id="status">
                        <input type="checkbox" name="status" lay-skin="switch" value="{{d.id}}" lay-filter="status" lay-text='开启|禁用'  {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('services_edit')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='del_services'>
                            <i class="fa fa-warning"></i> 删除
                        </button>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('services_list')}",function (){
        return [
            {field: 'id', title: '编号', align: 'center', width: 70},
            {field: 'name', title: '服务名称'},
            {field: 'icon', title: '图标', templet: '#icon'},
            {field: 'explain', title: '服务说明'},
            {field: 'sort', title: '排序', align: 'center', width: 70},
            {field: 'status', title: '状态', align: 'center', templet: '#status', width: 100},
            {field: 'right', title: '操作', align: 'center', toolbar: '#act'}
        ];
    });
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    layList.switch('status',function (obj, value) {
        var url = layList.Url({c:'store.store_product',a:'set_services_status',p:{status:(obj.elem.checked?1:0),id:value}});
        layList.baseGet(url,function (res) {
            layList.msg(res.msg);
        });
    })
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'del_services':
                var url=layList.U({c:'store.store_product',a:'delete_services',q:{id:data.id}});
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
                })
                break;
            case 'open_image':
                $eb.openImage(data.icon);
                break;
        }
    })
</script>
{/block}
