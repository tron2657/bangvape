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
    .layui-card-body p.layuiadmin-big-font {
        font-size: 36px;
        color: #666;
        line-height: 36px;
        padding: 5px 0 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-all;
        white-space: nowrap;
    }
    .layuiadmin-badge, .layuiadmin-btn-group, .layuiadmin-span-color {
        position: absolute;
        right: 15px;
    }
    .layuiadmin-badge {
        top: 50%;
        margin-top: -9px;
        color: #01AAED;
    }
    .layuiadmin-span-color i {
        padding-left: 5px;
    }
    .block-rigit button{
        width: 100px;
        letter-spacing: .5em;
        line-height: 28px;
    }
    .layuiadmin-card-list p.layuiadmin-normal-font {
        padding-bottom: 10px;
        font-size: 20px;
        color: #666;
        line-height: 24px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" style="padding: 7.5px" >
        <div class="layui-col-md12" style="background-color: #fff">
            <div class="layui-card-header" style="margin-left: 10px">财务统计</div>
            {volist name='$content' id='vo' key='k'}
            {if condition="$k == 1"}

            {else/}
            <div class="layui-col-sm3 layui-col-md3" style="margin-right: 20px;width: 300px">
                    <div class="layui-card" style="box-shadow:none; border: 1px solid #ccc">
                        <div class="layui-card-header" style="border-bottom: 1px solid #ccc;">
                            {if condition="$k == 3"}
                             用户钱包总余额
                            {else/}
                            {$vo.name}
                            {/if}
                            <span class="layui-badge layuiadmin-badge" style="background-color: red;color: #fff">{$vo.field}</span>
                        </div>
                        <div class="layui-card-body">
                            <p class="layuiadmin-big-font">{$vo.amount}</p>
                        </div>
                    </div>
                </div>
            {/if}
                
            {/volist}
        </div>
    </div>
    <!--列表-->
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">收益记录</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                    <table class="layui-hide" id="List" lay-filter="List"></table>
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
    layList.tableList('List',"{:Url('get_payment_profit_list')}",function (){
        var join = [
            {field: 'order_id', title: '交易订单号',templet:'#title',width:'20%'},
            {field: 'info', title: '内容',templet:'#content',width:'20%'},
            {field: 'amount', title: '交易金额',templet:'#userinfo',width:'20%'},
            {field: 'profit', title: '抽成金额',width:'20%'},
            {field: 'create_time', title: '创建时间',width:'20%'}
        ];
        return join;
    });
    layList.tool(function (event,data,obj) {
        switch (event) {

        }
    })
    


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