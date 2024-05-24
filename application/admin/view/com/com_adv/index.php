{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div id="suggest-alert" class="alert alert-info" role="alert">
                提示：建议添加2~4张为宜。
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item" style="margin-top: 10px">
                            <div class="layui-inline">
                                <label class="layui-form-label">广告状态</label>
                                <div class="layui-input-block">
                                    <select name="status">
                                        <option value="">全部</option>
                                        <option value="1">启用</option>
                                        <option value="0">禁用</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">展现平台</label>
                                <div class="layui-input-block">
                                    <select name="platform">
                                        <option value="">全部</option>
                                        <option value="6">微信小程序</option>
                                        <option value="7">App</option>
                                        <option value="1">微信小程序（iOS）</option>
                                        <option value="2">微信小程序（Android）</option>
                                        <option value="3">iOS App</option>
                                        <option value="4">Android App</option>
                                        <option value="5">H5</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="layui-inline">
                                <label class="layui-form-label">关键词</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" class="layui-input" placeholder="请输入名称">
                                </div>
                            </div> -->
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
                <div class="layui-card-header">广告列表</div>
                <div class="layui-card-body">
                    {if condition="$add eq 1"}
                    <div class="alert alert-info" role="alert" style="color: #666;">
                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                        当前广告位支持添加微信小程序流量主广告，流量主广告在微信小程序端将替换自建广告，其他平台的自建广告不受影响。（已添加数量：{$num}/1）
                        {if condition="in_array('weixin_flow', $open_list)"}
                        <a id="add-at-once" href="javascript:void(0);" style="color: #337ab7">立即添加</a>
                        {else/}
                        <a id="add_none" href="javascript:void(0);" style="color: #337ab7">立即添加</button>
                        {/if}
                    </div>
                    {/if}
                    <div class="layui-btn-container">
                        <button type="button" class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create', ['type'=>$type])}')" style="margin-top: 10px">添加数据</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="icon">
                        <img style="cursor: pointer;{{ d.pic == '' ? 'display:none' : '' }}" onclick="javascript:$eb.openImage(this.src);" src="{{d.pic}}">
                    </script>
                    <script type="text/html" id="status">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}},{{d.ad_type}}" lay-filter='status' lay-text='启用|禁用'  {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" lay-event="edit_ad">
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
    var status = '<?=$status?>';
    var num = '{$num}';
    setTimeout(function () {
        $('#suggest-alert').hide();
    },3000);
    //实例化form
    layList.form.render();
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('adv_list',['type'=>$type, 'status'=>$status])}",function (){
        return [
            {field: 'id', title: 'ID',event:'id', width: 50},
            {field: 'name', title: '广告名称', width: '15%'},
            {field: 'icon', title: '图片',templet:'#icon'},
            {field: 'url', title: '链接'},
            {field: 'ad_type_name', title: '类型'},
            {field: 'sort', title: '排序', width: 60},
            {field: 'platform', title: '展示平台', width: '25%'},
            {field: 'status', title: '显示状态',templet:'#status', width: 92},
            //{field: 'create_time', title: '创建时间',edit:'#create_time'},
            {field: 'update_time', title: '更新时间'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act', width: 140},
        ];
    });
    //自定义方法
    var action= {
        quick_edit:function(field, id, value){
            layList.baseGet(layList.Url({c:'com.com_adv',a:'quick_edit',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
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

    layList.switch('status',function (odj,value) {
        var arr = value.split(',');
        var checked = odj.elem.checked ? 1 : 0;
        var url;
        if (arr[1] == '0') {
            url = layList.Url({c:'com.com_adv',a:'quick_edit',p:{value:checked,field:'status',id:arr[0]}});
        } else {
            url = layList.Url({c:'wechat.routine_ad',a:'set_show',p:{is_show:checked,id:arr[0]}});
        }
        layList.baseGet(url, function (res) {
            layList.msg(res.msg);
        });
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
                var url = data.ad_type === 0 ? layList.U({c:'com.com_adv',a:'delete',q:{id:data.id}}) : layList.U({c:'wechat.routine_ad',a:'delete',q:{id:data.id}});
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
            case 'edit_ad':
                var url = data.ad_type === 0 ? layList.U({c:'com.com_adv',a:'edit',q:{id:data.id}}) : layList.U({c:'wechat.routine_ad',a:'ad_edit',q:{id:data.id,type:'{$type}'}})
                $eb.createModalFrame('编辑',url);
        }
    })

    $('#add-at-once').on('click', function (){
        if (num === '0') {
            $eb.createModalFrame('添加广告',layList.U({c:'wechat.routine_ad',a:'create_ad',q:{type:'{$type}'}}));
        } else {
            layList.msg('数量已达上限');
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
    $("#add_none").on('click',function(){
        action['unable']()
    })
</script>
{/block}
