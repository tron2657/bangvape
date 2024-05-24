{extend name="public/container"}

{block name="content"}
<style>
.btns{
    width: 80px !important;
    height: 28px !important;
    background-color: #39f !important;
}
.btns:nth-child(1){
    margin-right: 10px;
}
.layui-table-click{
    background-color: #fff !important;
}
</style>
<div class="layui-fluid">
        <div class="layui-col-md12">
            <div class="layui-tab layui-tab-brief" lay-filter="tab">
                <ul class="layui-tab-title" style="background-color: white;top: 10px">
                    <li lay-id="list" class="layui-this">
                    <a href="">设置渠道</a>
                    </li>
                    <li lay-id="list">
                    <a href="{:Url('admin/payment.index/set_limit')}">钱包设置</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--产品列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="act_common">
                            <button type="button" class="layui-btn layui-btn-xs btns" lay-event="see">查看详情</button>
                            <button type="button" class="layui-btn layui-btn-xs btns" lay-event="edit">编辑</button>
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
    layList.tableList('List',"{:Url('get_pay_method')}",function (){
        var join = [
            // {type:'checkbox'},
            {field: 'method', title: '渠道ID',align:'center',width:'25%'},
            {field: 'name', title: '渠道名称',align:'center',width:'25%'},
            {field: 'business', title: '商户id',align:'center',width:'25%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'25.3%'},
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
                $eb.createModalFrame('支付配置详情',layList.Url({c:'payment.index',a:'detail',p:{id:data.id}}),{h:800,w:1200});
                break;
            case 'edit':
                $eb.createModalFrame('编辑配置',layList.Url({c:'payment.index',a:'edit',p:{id:data.id}}),{h:800,w:1200});
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
                    fid:'',
                    type:'',
                    name:'',
                    uid:''
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
                layList.form.on("select(fid)", function (data) {
                    that.where.fid = data.value;
                });
                layList.form.on("select(type)", function (data) {
                    that.where.type = data.value;
                });
            }
        })
    });
</script>
{/block}
