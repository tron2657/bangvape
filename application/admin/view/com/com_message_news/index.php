{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-tab layui-tab-brief" lay-filter="tab">
    </div>
    <div class="layui-row layui-col-space15" id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                </div>
            </div>
        </div>
        <!--版块列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="alert alert-info" role="alert">
                        公告管理
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="layui-btn-container">
                        {if condition="$is_free_ban AND $is_end_ban"}
                        <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create_message_news')}',{h:document.body.clientHeight,w:document.body.clientWidth})">创建营销消息</button>
                        <button class="layui-btn layui-btn-sm" data-type="remove">批量删除</button>
                        {else/}
                        <button class="layui-btn layui-btn-sm" data-type="unable">创建营销消息</button>
                        <button class="layui-btn layui-btn-sm" data-type="unable">批量删除</button>
                        {/if}
                    </div>
                    <div class="layui-card">
                        <div class="layui-card-header">搜索条件</div>
                        <div class="layui-card-body" style="padding: 0; margin-top: 12px">
                            <form class="layui-form">
                                <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                                    <div class="layui-card-body ">
                                        <div class="layui-row layui-col-space10 layui-form-item">
                                            <div class="layui-col-lg12">
                                                <div class="layui-inline">
                                                    <label class="layui-form-label">消息状态</label>
                                                    <div class="layui-input-block" style="width: 173px">
                                                        <select name="status" v-model="where.status" lay-filter="status">
                                                            <option value="">全部</option>
                                                            <option value="1">已发送</option>
                                                            <option value="2">未发送</option>
                                                            <option value="3">已过期</option>
                                                            <option value="0">已撤回</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="layui-inline">
                                                    <label class="layui-form-label">消息标题</label>
                                                    <div class="layui-input-inline" style="margin-left: 56px">
                                                        <input type="text" name="title" v-model="where.title" lay-verify="title" autocomplete="off" placeholder="请输入消息标题" class="layui-input" style="width: 173px;padding-left: 5px">
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
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_verify' lay-text='启用|禁用'  {{ d.display == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="logo">
                        {{#  if(d.logo==''){ }}
                        {{#  } else { }}
                        <img style="cursor: pointer" onclick="javascript:$eb.openImage(this.src);" src="{{d.logo}}">
                        {{#  } }}
                    </script>
                    <!--操作-->
                    <script type="text/html" id="act_one">
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </li>
                        </ul>
                        {if condition="$is_free_ban AND $is_end_ban"}
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('edit')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        {else/}
                        <button class="layui-btn layui-btn-xs" data-type="unable">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        {/if}

                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('详情','{:Url('admin/com.com_thread/view_thread')}?id={{d.tid}}')">
                            <i class="fa fa-paste"></i> 详情
                        </button>
                    </script>
                    <script type="text/html" id="title">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;">{{d.title}}</div>
                    </script>
                    <script type="text/html" id="summary">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 3;overflow: hidden;">{{d.summary}}</div>
                    </script>
                    <script type="text/html" id="act_tre">
                        {{#  if(d.status=='已发送'){ }}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" lay-event='withdraw'>
                                    <i class="fa fa-trash"></i> 撤回
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </li>
                        </ul>
                        {{#  } }}
                        {{#  if(d.status=='未发送'){ }}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" lay-event='withdraw'>
                                        <i class="fa fa-trash"></i> 撤回
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </li>
                        </ul>
                        {{#  } }}
                        {{#  if(d.status=='已撤回'){ }}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </li>
                        </ul>
                        {{#  } }}
                        {{#  if(d.status=='已过期'){ }}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </li>
                        </ul>
                        {{#  } }}
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑','{:Url('edit')}?id={{d.id}}')">
                            <i class="fa fa-paste"></i> 编辑
                        </button>
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('详情','{:Url('admin/com.com_thread/view_thread')}?id={{d.tid}}')">
                            <i class="fa fa-paste"></i> 详情
                        </button>
                    </script>
                    <script type="text/html" id="to_type_uid">
                        {{#  if(d.to_type_uid=='' || d.to_type_uid=='0'){ }}
                        <div>全部用户</div>
                        {{#  } else { }}
                        <div>{{d.to_type_uid}}</div>
                        {{#  } }}
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
    layList.tableList('List',"{:Url('message_news_list')}",function (){
        return [
            {type:'checkbox'},
            {field: 'id', title: 'ID', event:'fid',width:'5%'},
            {field: 'title', title: '标题',width:'10%',templet:'#title'},
            {field: 'summary', title: '摘要',width:'16%',templet:'#summary'},
            {field: 'logo', title: '海报',templet:'#logo',width:'6%'},
            {field: 'admin', title: '操作人',width:'10%'},
            {field: 'status', title: '状态',width:'5%'},
            {field: 'create_time', title: '创建时间',width:'8%'},
            {field: 'send_time', title: '推送时间',width:'8%'},
            {field: 'to_type_uid', title: '推送人群',width:'8%',templet:'#to_type_uid'},
            {field: 'view', title: '阅读量',width:'6%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_tre',width:'15%'},
        ];
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
            case 'summary':
                action.set_forum('summary',id,value);
                break;
            case 'sort':
                action.set_forum('sort',id,value);
                break;
        }
    });
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                console.log($eb);
                var url  = layList.U({c:'com.com_message_news',a:'delete',q:{id:data.id}});
                var code = {title:"操作提示",text:"你确定要删除该消息吗？",type:'info',confirm:'是的，删除该消息'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'open':
                var url=layList.U({c:'com.com_message_news',a:'open',q:{id:data.id}});
                var code = {title:"操作提示",text:"你确定推送该消息吗？",type:'info',confirm:'是的，推送该消息'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            layList.reload({},true,null,obj);
                        }else{
                            return Promise.reject(res.data.msg || '推送失败');
                        }
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
                break;
            case 'withdraw':
                var url=layList.U({c:'com.com_message_news',a:'withdraw',q:{id:data.id}});
                var code = {title:"操作提示",text:"你确定撤回该消息吗？",type:'info',confirm:'是的，撤回该消息'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            layList.reload({},true,null,obj);
                        }else{
                            return Promise.reject(res.data.msg || '推送失败');
                        }
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
                break;
            case 'close':
                var url=layList.U({c:'com.com_message_news',a:'close',q:{id:data.id}});
                var code = {title:"操作提示",text:"你确定关闭该消息吗？",type:'info',confirm:'是的，关闭该消息'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            layList.reload({},true,null,obj);
                        }else{
                            return Promise.reject(res.data.msg || '关闭失败');
                        }
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
                break;
        }
    })
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
    //自定义方法
    var action={
        set_forum:function(field,id,value){
            layList.baseGet(layList.Url({c:'com.com_forum',a:'set_forum',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
        set_verify:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定提交审核版块吗？",type:'info',confirm:'是的，提交审核'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_forum',a:'forum_verify'}),{ids:ids, status:2},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要提交审核的版块');
            }
        },
        verify:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定审核通过版块吗？",type:'info',confirm:'是的，审核通过'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_forum',a:'forum_verify'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要审核的版块');
            }
        },
        del: function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定批量删除版块吗？",type:'info',confirm:'是的，删除'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_forum',a:'del'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要删除的版块');
            }
        },
        band:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定批量驳回版块吗？",type:'info',confirm:'是的，驳回'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_forum',a:'ban'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要驳回的版块');
            }
        },
        restore:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定还原吗？",type:'info',confirm:'是的，还原'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_forum',a:'forum_verify'}),{ids:ids, status:1},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要还原的版块');
            }
        },
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定要批量删除吗？ ",type:'info',confirm:'确定'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'com.com_message_news',a:'remove'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要删除的数据');
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
    $('body').find('button').each(function () {
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
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
</script>
{/block}
