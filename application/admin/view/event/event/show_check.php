{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" style="margin-top: -27px">
        <div class="layui-col-md12">
            <div class="layui-card" id="app">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding: 0; margin-top: 12px">
                    <form class="layui-form">
                        <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                            <div class="layui-card-body ">
                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-lg12">

                                        <div class="layui-form-item" style="margin-top: 10px">
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;text-align: left">报名人:</label>
                                                <div class="layui-input-inline">
                                                    <input type="text" name="title" v-model="where.user" lay-verify="user" autocomplete="off" placeholder="填写报名人" class="layui-input" style="width: 173px;padding-left: 5px">
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">核销状态</label>
                                                <div class="layui-input-block">
                                                    <select name="enroll" v-model="where.enroll" lay-filter="enroll">
                                                        <option value="">全部</option>
                                                        <option value="1">已核销</option>
                                                        <option value="2">未核销</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">核销人</label>
                                                <div class="layui-input-block">
                                                    <select name="type" v-model="where.check_user" lay-filter="check_user">
                                                        <option value="">全部</option>
                                                        {volist name='check_user' id='v'}
                                                            <option value="{$v['uid']}">{$v['nickname']}</option>
                                                        {/volist}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-input-block">
                                            <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
                                            <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-refresh" ></i>重置</button>
                                        </div>
                                    </div>
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
                <div class="layui-card">
                    <div class="layui-card-header">报名/核销记录</div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                            <div>
                                <button type="button" class="layui-btn layui-btn-sm" onclick="location.href='{:url('index')}'" style="margin-top: 10px">返回活动管理</button>
                                <button lay-submit="export" lay-filter="export" class="layui-btn layui-btn-sm" style="margin-top: 10px">导出报名表</button>
                            </div>
                        </div>
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="act_common">
                            <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('show_user')}?id={{d.id}}')" >查看详情</button>
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
    layList.tableList('List',"{:Url('get_show_check_list')}?id={$id}",function (){
        var join = [
            {type:'checkbox'},
            {field: 'id', title: 'ID', event:'id',width:'3%'},
            {field: 'user', title: '报名人',width:'15%'},
            {field: 'create_time', title: '报名时间',width:'20%'},
            {field: 'check_time', title: '核销时间',width:'20%'},
            {field: 'check_user', title: '核销人',width:'20%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'20%'},
        ];
        return join;
    });
    //自定义方法
    var action={
        // 批量删除
        delete:function(field,id,value){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'event.event',a:'delete'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要删除的评论');
            }
        },
        // 清理
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'com.com_post',a:'remove'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要清理的评论');
            }
        },
        restore:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'com.com_post',a:'restore'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要还原的评论');
            }
        }
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

    layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'com.com_post',a:'quick_edit',p:{value:1,field:'status',id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'com.com_post',a:'quick_edit',p:{value:0,field:'status', id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    layList.search('export',function(where){
        location.href=layList.U({c:'event.event',a:'save_excel',q:{enroll:where.enroll,user:where.user,check_user:where.check_user,event_id:'{$id}'}});
    });
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'see':
                $eb.createModalFrame(data.nickname+'-会员详情',layList.Url({c:'user.user',a:'see',p:{uid:data.uid}}));
                break;
            case 'set_hot':
                var url=layList.U({c:'event.event',a:'set_recommend',q:{id:data.id, field:'is_recommend', value: data.is_recommend == 1?0:1}});
                if(data.is_recommend == 1){
                    var code = {title:"操作提示",text:"确定取消活动推荐操作吗？",type:'info',confirm:'是的，取消推荐该活动'};
                }else{
                    var code = {title:"操作提示",text:"确定将该活动设为推荐吗？",type:'info',confirm:'是的，设为推荐'};
                }
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            layList.reload({},true,null,obj);
                        }else{
                            return Promise.reject(res.data.msg || '设置失败');
                        }
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
                break;
            case 'delete':
                var url=layList.U({c:'event.event',a:'set_event_status',q:{id:data.id, status:-1}});
                var code = {title:"操作提示",text:"确定要将活动删除吗？",type:'info',confirm:'是的，删除该活动'};
                var code2 = {title:"操作提示",text:"再次确认是否删除该活动？",type:'info',confirm:'确认,要删除'};
                $eb.$swal('delete',function(){
                    $eb.$swal('delete',function(){
                        $eb.axios.get(url).then(function(res){
                            if(res.status == 200 && res.data.code == 200) {
                                $eb.$swal('success','');
                                layList.reload({},true,null,obj);
                            }else{
                                return Promise.reject(res.data.data || '设置失败');
                            }
                        }).catch(function(err){
                            $eb.$swal('error',err);
                        });
                    },code2);
                },code);
                break;
            case 'open':
                layList.basePost(layList.Url({c:'event.event',a:'set_event_status'}),{id:data.id,'status':1},function (res) {
                    layList.msg(res.data);
                    layList.reload();
                });
                break;
        }
    })
    // 批量驳回
    function delete_forum(){
        var ids=layList.getCheckData().getIds('id');
        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }
            $eb.createModalFrame('驳回理由',"{:Url('set_reason')}?id="+str);
        }else{
            layList.msg('请选择要批量驳回的申请');
        }
    }
    // 批量通过
    function pass_forum(){
        var ids=layList.getCheckData().getIds('id');
        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }
            var url=layList.U({c:'group.visit_audit',a:'set_audit',q:{id:str, field:'status', value:1}});
            var code = {title:"操作提示",text:"你确定要通过这些用户的申请吗？",type:'info',confirm:'是的,我要审核通过'};
            $eb.$swal('delete',function(){
                $eb.axios.get(url).then(function(res){
                    if(res.status == 200 && res.data.code == 200) {
                        $eb.$swal('success','审核成功');
//                        obj.del();
                        setTimeout(function () {
//                            var index = parent.layer.getFrameIndex(window.name);
//                            parent.layer.close(index);
//                            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
//                            console.log($(".page-tabs-content .active").index());
                            window.frames[$(".page-tabs-content .active").index()].location.reload();
                        },1500)
                    }else
                        return Promise.reject('审核失败')
                }).catch(function(err){
                    $eb.$swal('error',err);
                });
            }, code)
        }else{
            layList.msg('请选择要批量通过的申请');
        }
    }
    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
                badge: [],
                dataList: [
                    {name: '全部', value: ''},
                    {name: '昨天', value: 'yesterday'},
                    {name: '今天', value: 'today'},
                    {name: '本周', value: 'week'},
                    {name: '本月', value: 'month'},
                    {name: '本季度', value: 'quarter'},
                    {name: '本年', value: 'year'},
                ],
                where:{
                    data:'',
                    enroll:'',
                    user:'',
                    check_user:'',
                },
                showtime: false,
            },
            watch: {

            },
            methods: {
                setData:function(item){
                    var that=this;
                    if(item.is_zd==true){
                        that.showtime=true;
                        this.where.data=this.$refs.date_time.innerText;
                    }else{
                        this.showtime=false;
                        this.where.data=item.value;
                    }
                },
                search:function () {
                    layList.reload(this.where,true);
                },
                refresh:function () {
                    layList.reload();
                }
            },
            mounted:function () {
                var that=this;
                layList.laydate.render({
                    elem:this.$refs.date_time,
                    trigger:'click',
                    eventElem:this.$refs.time,
                    range:true,
                    change:function (value){
                        that.where.data=value;
                    }
                });
                layList.form.render();
                layList.form.on("select(enroll)", function (data) {
                    that.where.enroll = data.value;
                });
                layList.form.on("select(check_user)", function (data) {
                    that.where.check_user = data.value;
                });
            }
        })
    });
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
</script>
{/block}
