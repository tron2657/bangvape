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
                                    <label class="layui-form-label">关键字：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="nickname" class="layui-input" placeholder="用户昵称、手机号或ID">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">频道:</label>
                                    <div class="layui-input-block">
                                        <select name="channel_id">
                                            <option value="">-选择频道-</option>

                                            {volist name="channel_list" id="one_channel"}
                                            <option value="{$one_channel['id']}">{$one_channel['title']}</option>
                                            {/volist}
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
                    <div class="layui-card-header">频道管理员</div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="avatar">
                            <img style="cursor: pointer" onclick="javascript:$eb.openImage(this.src);" src="{{d.avatar}}">
                        </script>
                        <script type="text/html" id="status">
                            <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='开启|关闭'  {{ d.status == 1 ? 'checked' : '' }}>
                        </script>
                        <script type="text/html" id="act">
                            <a class="layui-btn layui-btn-xs" lay-event='del' href="javascript:void(0);" style="background-color: red;" >
                                <i class="fa fa-warning"></i> 删除
                            </a>
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
    //加载列表
    layList.tableList('List',"{:Url('admin_list')}",function (){
        return [
            {field: 'id', title: 'ID', event:'id',width:'4%'},
            {field: 'user_nickname', title: '昵称'},
            {field: 'avatar', title: '头像',templet:'#avatar'},
            {field: 'channel_name', title: '管理频道'},
            {field: 'do_nickname', title: '操作人'},
            {field: 'create_time_show', title: '操作时间'},
            {field: 'status', title: '状态',templet:'#status'},
            {field: 'right', title: '操作',align:'left',toolbar:'#act'},
        ];
    });

    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'channel.admin',a:'set_status',p:{status:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'channel.admin',a:'set_status',p:{status:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'del':
                var url=layList.U({c:'channel.admin',a:'delete_admin',q:{id:data.id}});
                var code = {title:"是否要删除该管理员",text:"是否从列表中删除该管理员？",confirm:'是的，我要删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                            location.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            default:
        }
    })
</script>
{/block}
