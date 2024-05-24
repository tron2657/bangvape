{extend name="public/container"}
{block name="content"}
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
    .layui-table-cell p{
        height: auto !important;
        line-height: !important;
    }
    .layui-input-block button{
        border: 1px solid rgba(0,0,0,0.1);
    }
    .layui-card-body{
        padding-left: 10px;
        padding-right: 10px;
    }
    .layuiadmin-span-color i {
        padding-left: 5px;
    }
    .block-rigit button{
        width: 100px;
        letter-spacing: .5em;
        line-height: 28px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <!--搜索条件-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding: 0; margin-top: 12px">
                    <form class="layui-form">
                        <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                            <div class="layui-card-body ">
                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-lg12">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">用户:</label>
                                            <div class="layui-input-inline" style="margin-left: 56px">
                                                <input type="text" name="real_name" v-model="where.real_name" lay-verify="title" autocomplete="off" placeholder="标题、正文关键词" class="layui-input" style="width: 173px;padding-left: 5px">
                                            </div>
                                        </div>

                                        <div class="layui-input-block" style="margin-left: 97px">
                                            <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
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
        <!--end-->
        <!-- 中间详细信息-->
        <!--enb-->
    </div>
    <!--列表-->
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">用户钱包列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                
                    <script type="text/html" id="act_common">
                        <a type="button" class="layui-btn layui-btn-xs" href="{:Url('admin/payment.trade/index')}?uid={{d.uid}}">变更记录</a>
                    </script>
                 
                </div>
            </div>
        </div>
    </div>
    <!--end-->
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    var real_name='<?=$real_name?>';
 
    layList.tableList('List',"{:Url('get_user_wallet_list')}",function (){
        var join = [
//            {type:'checkbox'},
            {field: 'uid', title: 'UID', event:'id',width:'7%'},
            {field: 'nickname', title: '用户昵称',templet:'#title',width:'16%'},
            {field: 'amount', title: '钱包余额',width:'16%'},
            // {field: 'token_ios', title: '代币余额(ios)',width:'16%'},
            // {field: 'token_other', title: '代币余额(其他)',width:'16%'},
            {field: 'update_time', title: '变更时间',width:'16%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common' },
        ];
        return join;
    });
   
    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
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
                    real_name:real_name || '',
                    excel:0,
                },
                showtime: false,
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
                    this.where.excel=0;
                    layList.reload(this.where,true);
                },
                refresh:function () {
                    $('[data-type="data"]').children(":first").click();
                    layList.reload();
                },
                excel:function () {
                    this.where.excel=1;
                    location.href=layList.U({c:'agent.cash_out',a:'cash_out_list',q:this.where});
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
                    that.where.cid = data.value;
                });
            }
        })
    });
</script>
{/block}