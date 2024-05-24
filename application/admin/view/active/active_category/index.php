{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item" style="margin-top: 10px">
                            <div class="layui-inline">
                                <label class="layui-form-label">所有分类</label>
                                <div class="layui-input-block">
                                    <select name="pid">
                                        <option value="0">顶级分类</option>
                                        {volist name="cate" id="vo"}
                                            <option value="{$vo.id}">{$vo.html}{$vo.name}</option>
                                        {/volist}
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">分类状态</label>
                                <div class="layui-input-block">
                                    <select name="status">
                                        <option value="">全部</option>
                                        <option value="1">启用</option>
                                        <option value="0">禁用</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label" style="width: 120px">主题分类名称:</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="real_name" v-model="where.real_name" lay-verify="title" autocomplete="off" placeholder="填写主题分类名称" class="layui-input" style="width: 173px;padding-left: 5px">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                    <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
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
                <div class="layui-card-header">分类列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container"  style="margin-top: 10px">
											<a class="layui-btn layui-btn-sm" href="{:Url('index')}">分类首页</a>
                      <button type="button" class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}')">添加分类</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="status">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='显示|隐藏'  {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="sub_forum_index">
                        {{# if(d.pid==0){ }}
                            <a href="{:Url('index')}?pid={{d.id}}">查看子分类</a>
                        {{# } }}
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('create')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='delstor'>
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
    setTimeout(function () {
        $('.alert-info').hide();
    },3000);
    //实例化form
    layList.form.render();
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('get_list')}",function (){
        return [
            {field: 'id', title: 'ID',event:'id',width:'6%'},
            {field: 'pid_name', title: '父级分类',edit:'name',width:'8%'},
            {field: 'name', title: '分类名称'},
            {field: 'url', title: '查看子分类',templet:'#sub_forum_index'},
            {field: 'sort', title: '排序',sort: true,event:'sort',edit:'sort',width:'8%'},
            {field: 'status', title: '状态',templet:'#status',width:'6%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'},
        ];
    });
    //自定义方法
    var action= {
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'com.com_adv',a:'delete'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择广告');
            }
        }
    };
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });



    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'name':
                action.quick_edit('name',id,value);
                break;
            case 'sort':
                action.quick_edit('sort',id,value);
                break;
            case 'url':
                action.quick_edit('url',id,value);
                break;
        }
    });
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'event.event_category',a:'delete',q:{id:data.id}});
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
        }
    })
</script>
{/block}
