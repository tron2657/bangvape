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
                                        <div class="layui-inline">
                                            <label class="layui-form-label">交易单号:</label>
                                            <div class="layui-input-inline" style="margin-left: 42px">
                                                <input type="text" name="order" v-model="where.order" lay-verify="title" autocomplete="off" placeholder="填写交易单号" class="layui-input" style="width: 173px;padding-left: 5px">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">交易内容:</label>
                                            <div class="layui-input-inline" style="margin-left: 42px">
                                                <input type="text" name="info" v-model="where.info" lay-verify="title" autocomplete="off" placeholder="填写交易内容" class="layui-input" style="width: 173px;padding-left: 5px">
                                            </div>
                                        </div>
                                        <div class="layui-inline" >
                                            <label class="layui-form-label">创建时间:</label>
                                            <div class="layui-input-block" data-type="data" v-cloak="">
                                                <button type="button" class="layui-btn layui-btn-sm layui-btn-primary"  ref="date_time" style="margin-top: 0;width: 180px"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-inline" >
                                            <label class="layui-form-label">支付渠道:</label>
                                            <div class="layui-input-inline" style="width: 173px;margin-left: 42px">
                                                <select name="pay_type" v-model="where.pay_type" lay-filter="pay_type">
                                                    <option value="">全部</option>
                                                    <option value="weixin" >微信</option>
                                                    <option value="yue" >钱包余额</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline" style="margin-left: 16px;">
                                            <label class="layui-form-label">支付状态:</label>
                                            <div class="layui-input-inline" style="width: 173px;margin-left: 42px">
                                                <select name="status" v-model="where.status" lay-filter="status">
                                                    <option value="">全部</option>
                                                    <option value="1" >交易完成</option>
                                                    <option value="0" >交易关闭</option>
                                                    <option value="2" >交易中</option>
                                                    <option value="-1" >交易失败</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-input-block">
                                            <!-- <div class="layui-col-lg12 " style="margin-bottom: 10px">
                                               <input type="checkbox" name="more" lay-skin="primary" title="更多选项">
                                            </div> -->
                                            <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
                                            <!-- <button @click="excel" type="button" class="layui-btn layui-btn-warm layui-btn-sm export" type="button">
                                                <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button> -->
                                            <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
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
                    <div class="layui-card-header">交易记录</div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="act_common">
                            <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('admin/payment.index/order_detail')}?id={{d.id}}')">查看详情</button>
                            <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('admin/payment.index/show_order_log')}?id={{d.id}}')">变更记录</button>
                        </script>
                        <script type="text/html" id="img">
                           <img src="{{d.avatar}}">
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
    layList.tableList('List',"{:Url('get_trade_list')}",function (){
        var join = [
            {type:'checkbox'},
            {field: 'order_id', title: '交易单号', event:'id',width:'12%'},
            {field: 'nickname', title: '用户名',templet:'#title',width:'10%'},
            {field: 'info', title: '交易内容',width:'12%',toolbar:'#img'},
            {field: 'amount', title: '金额',width:'10%'},
            {field: 'pay_type', title: '交易渠道',width:'12%'},
            {field: 'create_time', title: '创建时间',width:'14%'},
            {field: 'status_name', title: '状态',width:'10%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'10%'},
        ];
        return join;
    });
    //自定义方法
    var action={
        // 批量删除
        delete:function(field,id,value){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'com.com_post',a:'delete'}),{ids:ids},function (res) {
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

    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'see':
                $eb.createModalFrame(data.nickname+'-会员详情',layList.Url({c:'user.user',a:'see',p:{uid:data.uid}}));
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
                where:{
                    order:'',
                    info:'',
                    data:'',
                    pay_type:'',
                    status:''
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
                layList.form.on("select(pay_type)", function (data) {
                    that.where.pay_type = data.value;
                });
                layList.form.on("select(status)", function (data) {
                    that.where.status = data.value;
                });
            }
        })
    });
</script>
{/block}
