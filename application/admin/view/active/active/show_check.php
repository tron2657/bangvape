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
                            <!-- <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('show_user')}?id={{d.id}}')" >查看详情</button> -->
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
            // {type:'checkbox'},
            {field: 'id', title: 'ID', event:'id',width:'3%'},
            {field: 'user', title: '报名人',width:'15%'},
            {field: 'create_time', title: '报名时间',width:'20%'},
            {field: 'check_time', title: '核销时间' },
            {field: 'check_user', title: '核销人' }
            // {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'20%'}
        ];
        return join;
    });
    //自定义方法
    var action={
        
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

     
    layList.search('export',function(where){
        location.href=layList.U({c:'active.active',a:'save_excel',q:{enroll:where.enroll,user:where.user,check_user:where.check_user,event_id:'{$id}'}});
    });
    //监听并执行排序
  
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
