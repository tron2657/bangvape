{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">广告名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">广告ID</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ad_unit_id" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">广告类型</label>
                                <div class="layui-input-block">
                                    <select name="ad_slot">
                                        <option value=" ">全部</option>
                                        {volist name='ad_slot' id='vo'}
                                        <option value="{$key}">{$vo}</option>
                                        {/volist}
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
                <div class="layui-card-header">广告管理</div>
                <div class="layui-card-body">
                    {if condition="in_array('weixin_flow', $open_list)"}
                    <div class="alert alert-info" role="alert">
                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                        <span>因小程序未提供控制广告位开关的接口，此处使用「显示状态」控制广告在前端的显示与隐藏，若需要完全关闭广告请前往小程序后台操作。</span>
                    </div>
                    {else/}
                    <div class="alert alert-warning" role="alert">
                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                        <span>未开通该功能使用权限，如需开通请联系客服。</span>
                    </div>
                    {/if}
                    <div class="layui-btn-container">
                        {if condition="in_array('weixin_flow', $open_list)"}
                        <button class="layui-btn layui-btn-sm"  onclick="$eb.createModalFrame(this.innerText,'{:Url('create_ad')}')">新建广告 </button>
                        {else/}
                        <button class="layui-btn layui-btn-sm" data-type="unable" style="margin-top: 10px">新建广告</button>
                        {/if}
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="status">
                        {{ d.status == 1 ? '<span class="text-info">已开启</span>' : '<span class="text-muted">已关闭</span>' }}
                    </script>
                    <script type="text/html" id="is_show">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显示|隐藏' {{ d.is_show == 1 ? 'checked' : '' }} {{ d.status == 0 ? 'disabled' : '' }}>
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('ad_edit')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='del_ad'>
                            <i class="fa fa-warning"></i> 删除
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
    layList.tableList('List',"{:Url('ad_list')}",function (){
        return [
            {field: 'name', title: '广告位名称'},
            {field: 'ad_unit_id', title: '广告位ID'},
            {field: 'ad_slot_name', title: '广告类型'},
            {field: 'status', title: '广告位状态', align: 'center', templet: '#status'},
            {field: 'is_show', title: '显示状态', align: 'center', templet: '#is_show'},
            {field: 'right', title: '操作', align: 'center', toolbar: '#act'}
        ];
    });
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'wechat.routine_ad',a:'set_show',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'wechat.routine_ad',a:'set_show',p:{is_show:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    layList.tool(function (event, data, obj) {
        if (event === 'del_ad') {
            var url=layList.U({c:'wechat.routine_ad',a:'delete',q:{id:data.id}});
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
            });
        }
    });
     //自定义方法
     var action={
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

