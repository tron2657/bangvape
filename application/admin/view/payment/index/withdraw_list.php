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
    .layui-btn-container .layui-btn{
        width: 80px !important;
        height: 28px !important;
    }
    .laytable-cell-1-0-0, .laytable-cell-1-0-1, .laytable-cell-1-0-3, .laytable-cell-1-0-4, .laytable-cell-1-0-6, .laytable-cell-1-0-7 {
        text-align: center;
    }
    .layui-badge{
        background-color: #fe5722 !important;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <!--搜索条件-->
        <div class="layui-col-md12" style="margin-top: -20px">
            <div class="layui-tab layui-tab-brief" lay-filter="tab">
                <ul class="layui-tab-title" style="background-color: white;top: 10px">
                    <li lay-id="list" {eq name='status' value=''}class="layui-this" {/eq} >
                    <a href="{eq name='status' value=''}javascript:;{else}{:Url('withdraw_list',['status'=>''])}{/eq}">全部</a>
                    </li>
                    <li lay-id="list" {eq name='status' value='0'}class="layui-this" {/eq}>
                    <a href="{eq name='status' value='0'}javascript:;{else}{:Url('withdraw_list',['status'=>0])}{/eq}">待审核</a>
                    </li>
                    <li lay-id="list" {eq name='status' value='1'}class="layui-this" {/eq} >
                    <a href="{eq name='status' value='1'}javascript:;{else}{:Url('withdraw_list',['status'=>1])}{/eq}">已审核</a>
                    </li>
                    <li lay-id="list" {eq name='status' value='-1'}class="layui-this" {/eq} >
                    <a href="{eq name='status' value='-1'}javascript:;{else}{:Url('withdraw_list',['status'=>-1])}{/eq}">已驳回</a>
                    </li>

                    <li lay-id="list" {eq name='status' value='2'}class="layui-this" {/eq}>
                    <a href="{eq name='status' value='2'}javascript:;{else}{:Url('withdraw_list',['status'=>2])}{/eq}">已打款</a>
                    </li>
                </ul>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding: 0; margin-top: 12px">
                    <form class="layui-form">
                        <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                            <div class="layui-card-body ">
                                <div class="layui-row layui-col-space10 layui-form-item">
                                    <div class="layui-col-lg12">
                                        <label class="layui-form-label">提现时间:</label>
                                        <div class="layui-input-block" data-type="data" v-cloak="">
                                            <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList" @click="setData(item)" :class="{'layui-btn-primary':where.data!=item.value}" style="margin-top: 0px">{{item.name}}</button>
                                            <button class="layui-btn layui-btn-sm" type="button" ref="time" @click="setData({value:'zd',is_zd:true})" :class="{'layui-btn-primary':where.data!='zd'}" style="margin-top: 0px">自定义</button>
                                            <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" v-show="showtime==true" ref="date_time" style="margin-top: 0px">{$year.0} - {$year.1}</button>
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">关键词:</label>
                                            <div class="layui-input-inline" style="margin-left: 56px">
                                                <input type="text" name="real_name" v-model="where.real_name" lay-verify="title" autocomplete="off" placeholder="标题、正文关键词" class="layui-input" style="width: 173px;padding-left: 5px">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">提现方式:</label>
                                            <div class="layui-input-block" style="width: 173px">
                                                <select name="pay_type" v-model="where.pay_type" lay-filter="pay_type">
                                                    <option value="">全部</option>
                                                    <option value="weixin">微信</option>
                                                    <option value="alipay">支付宝</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="layui-col-lg12">
                                        <div class="layui-input-block">
                                            <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
                                            <button @click="excel" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="fa fa-floppy-o"></i>导出</button>
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
    {volist name='$content' id='vo'}
        <div class="layui-col-sm3 layui-col-md3" style="margin-right: 20px">
            <div class="layui-card">
                <div class="layui-card-header">
                    {$vo.name}
                    <span class="layui-badge layuiadmin-badge" style="background-color: red;color: #fff">{$vo.field}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{$vo.amount}</p>
                </div>
            </div>
        </div>
    {/volist}
    <!--列表-->
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">提现列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--用户信息-->
                    <script type="text/html" id="act_common">
                        {{# if(d.status==0){ }}
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" lay-event='sure' lay-filter='sure'>审核通过</button>
                        <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('set_withdraw_fail')}?id={{d.id}}')">驳回</button>
                        {{# }}}
                        {{#if(d.status==1){ }}
                        <button type="button" class="layui-btn layui-btn-xs" lay-event='pay' lay-filter='pay'">打款</button>
                        {{# }}}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('show_order_log')}?id={{d.id}}')">变更记录</button>
                    </script>
                        <script type="text/html" id="money">
                          <div>提现金额：{{d.money}}</div>
                          <div>打款金额：{{d.reality_money}}</div>
                          {{#if(d.code){ }}
                          <div>收款码：   <img style="cursor: pointer" onclick="javascript:$eb.openImage(this.src);"
                                           src="{{d.code}}"></div>
                          {{# }}}
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
    var status = '<?= $status?>';
    var real_name='<?=$real_name?>';
    layList.tableList('List',"{:Url('get_withdraw_list',['status'=>$status])}",function (){
        var join = [
            // {type:'checkbox'},
            {field: 'id', title: 'ID', event:'id',width:'5%'},
            {field: 'order_id', title: '提现单号',templet:'#title',width:'16%'},
            {field: 'user_message', title: '用户',width:'16%'},
            {field: 'create_time', title: '申请时间',templet:'#userinfo',width:'10%'},
            {field: 'type', title: '提现方式',width:'10%'},
            {field: 'money', title: '提现金额',templet:'#money',width:'10%'},
            {field: 'status_name', title: '状态',width:'10%'},
            {field: 'remark', title: '备注',templet:'#view_count',width:'11%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'12%'},
        ];
        return join;
    });
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'sure':
                var url=layList.U({c:'payment.index',a:'set_withdraw_status',q:{status:1,id:data.id}});
                var code = {title:"操作提示",text:"你确定要审核通过吗？",type:'info',confirm:'是的，我要审核通过'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            window.location.reload();
                        }else
                            return Promise.reject(res.data.msg || '审核失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                }, code);
                break;

            case 'pay':
                var url=layList.U({c:'payment.index',a:'set_withdraw_status',q:{status:2,id:data.id}});
                var code = {title:"操作提示",text:"你确认已经打款了吗？",type:'info',confirm:'是的，已经打款'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            window.location.reload();
                        }else
                            return Promise.reject( '打款成功')
                    }).catch(function(err){
                        $eb.$swal('error','打款失败');
                    });
                }, code);
                break;
        }
    });
    
    //自定义方法
    var action={
        move: function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                $eb.createModalFrame('迁移帖子', layList.Url({c:'com.com_thread',a:'move', p:{
                    ids:ids
                }}));
            }else{
                layList.msg('请选择要迁移的帖子');
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

    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
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