{extend name="public/container"}
{block name="content"}

<div class="layui-fluid">
    <style>
        .zzl_page_list{
            overflow-x: auto;
            overflow-y: hidden;
        }
        .zzl_page_list_content{
            min-width: 1300px;
        }
        .text-more-line{
            word-break: break-all;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }
    </style>
    <div class="layui-row layui-col-space15 zzl_page_list"  id="app">
        <div class="zzl_page_list_content">
            <div class="layui-col-md12">
                <div class="layui-card" >
                    <div class="layui-card-header">搜索条件</div>
                    <div class="layui-card-body">
                        <form class="layui-form layui-form-pane" action="" style="margin-top: 10px">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">频道名称：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="channel_name" class="layui-input" placeholder="搜索频道名称">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">频道状态:</label>
                                    <div class="layui-input-block">
                                        <select name="status">
                                            <option value="">全部</option>
                                            <option value="1">开启</option>
                                            <option value="0">关闭</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <div class="layui-input-inline">
                                        <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>重置</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--产品列表-->
            <div class="layui-col-md12" style="margin-top: 15px;">
                <div class="layui-card">
                    <div class="layui-card-header">自定义频道</div>
                    <div class="layui-card-body">
                            <div class="layui-btn-container" style="margin-bottom: 2px;margin-top: 10px">
                                {if condition="$is_add eq 1"}
                                    <a type="button" class="layui-btn layui-btn-sm" href="{:Url('edit_other')}">新建频道</a>
                                {else/}
                                    <button class="layui-btn layui-btn-sm" data-type="unable" style="margin-top: 10px">新建频道</button>
                                {/if}
                            </div>
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="logo">
                            {{# if(d.logo){ }}
                            <img style="cursor: pointer" onclick="javascript:$eb.openImage(this.src);" src="{{d.logo}}">
                            {{# } }}
                        </script>
                        <script type="text/html" id="status">
                            <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='开启|关闭'  {{ d.status == 1 ? 'checked' : '' }}>
                        </script>
                        <script type="text/html" id="intor">
                            <div class="text-more-line" style="width: 100%">{{d.intor}}</div>
                        </script>
                        <script type="text/html" id="post_intor">
                            <div class="text-more-line" style="width: 100%">{{d.post_intor==null ? '（此频道无内容）':d.post_intor}}</div>
                        </script>
                        <script type="text/html" id="act">
                            <a class="layui-btn layui-btn-xs" href="{:Url('channel.index/edit_other')}?id={{d.id}}" >
                                <i class="fa fa-paste"></i> 编辑
                            </a>
                            <a class="layui-btn layui-btn-xs" href="{:Url('channel.count/index')}?id={{d.id}}" >
                                <i class="fa fa-paste"></i> 数据
                            </a>
                            <a class="layui-btn layui-btn-xs" href="{:Url('channel.post/index')}?channel_id={{d.id}}" >
                                <i class="fa fa-paste"></i> 管理
                            </a>
                            <button type="button" class="layui-btn layui-btn-xs" lay-event="delstor"><i class="layui-icon layui-icon-list"></i>删除</button>
                        </script>
                    </div>
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
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('channel_list',['type'=>2])}",function (){
        return [
            {field: 'id', title: 'ID', event:'id',width:'4%'},
            {field: 'title', title: '频道名称'},
            {field: 'logo', title: '封面',templet:'#logo'},
            {field: 'intor', title: '频道说明',templet:'#intor'},
            {field: 'post_intor', title: '投稿说明',templet:'#post_intor'},
            /*{field: 'status', title: '状态',templet:'#status'},*/
            {field: 'right', title: '操作',align:'left',toolbar:'#act'},
        ];
    });
    //自定义方法
    var action={
        unable:function(){
            var code = {title:"提示",text:"创建数量已达上限，如需添加更多频道请联系客服升级",type:'info',confirm:'联系客服',cancel:'取消',confirmBtnColor:'#0ca6f2'};
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
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });

    layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'channel.index',a:'set_status',p:{status:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'channel.index',a:'set_status',p:{status:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //快速编辑
    /*layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'name':
                action.set_Class('name',id,value);
                break;
            case 'sort':
                action.set_Class('sort',id,value);
                break;
            case 'summary':
                action.set_Class('summary',id,value);
                break;
        }
    });*/
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'channel.index',a:'set_status',q:{id:data.id,status:-1}});
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
