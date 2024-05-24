{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" style="margin-top: -27px">
        <div class="layui-col-md12">
            <div class="layui-tab layui-tab-brief" lay-filter="tab">
            </div>
            <div class="layui-card" id="app">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding-bottom: 20px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <div class="layui-inline">
                                    <label class="layui-form-label">奖励人</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="do_uid" v-model="where.do_uid" lay-filter="do_uid" class="layui-input" placeholder="请输入内容">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">被奖励人</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="uid" v-model="where.uid" lay-filter="uid" class="layui-input" placeholder="请输入内容">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">来源</label>
                                    <div class="layui-input-block">
                                        <select name="model" v-model="where.model" lay-filter="model">
                                            <option value="">全部</option>
                                            <option value="1">前台</option>
                                            <option value="2">后台</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg12" style="display: flex;align-items: baseline">
                                    <label class="layui-form-label">创建时间:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="" style="margin-left: 13px;">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList" @click="setData(item)" :class="{'layui-btn-primary':where.data!=item.value}">{{item.name}}</button>
                                        <button class="layui-btn layui-btn-sm" type="button" ref="time" @click="setData({value:'zd',is_zd:true})" :class="{'layui-btn-primary':where.data!='zd'}">自定义</button>
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" v-show="showtime==true" ref="date_time">{$year.0} - {$year.1}</button>
                                    </div>
                                </div>
                                <div class="layui-col-lg12" style="float: left;left: 123px;top: 10px;">
                                    <div class="layui-input-inline">
                                        <button @click="search" type="button"
                                                class="layui-btn layui-btn-sm layui-btn-normal">
                                            <i class="layui-icon layui-icon-search"></i>搜索
                                        </button>
                                        <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
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
                <div class="layui-card-header">奖励列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="margin-top: 10px">

                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="merchant_reply_content">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;">{{d.merchant_reply_content}}</div>
                        <div>{{d.merchant_reply_time}}</div>
                    </script>
                    <script type="text/html" id="uid">
                        <p>{{d.nickname}}【{{d.uid}}】</p>
                    </script>
                    <script type="text/html" id="do_uid">
                        <p>{{d.do_nickname}}【{{d.do_uid}}】</p>
                    </script>
                    <script type="text/html" id="from">
                        <p>{{d.model}}</p>
                        <p>{{d.from}}</p>
                    </script>
                    <!--图片-->
                    <script type="text/html" id="tid">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;">{{d.thread}}</div>
                    </script>
                    <script type="text/html" id="reward">
                        {{# if(d.exp > 0){ }}
                        <p>{{d.exp_name}}:{{d.exp}}</p>
                        {{# } }}
                        {{# if(d.fly > 0){ }}
                        <p>{{d.fly_name}}:{{d.fly}}</p>
                        {{# } }}
                        {{# if(d.buy > 0){ }}
                        <p>{{d.buy_name}}:{{d.buy}}</p>
                        {{# } }}
                        {{# if(d.gong > 0){ }}
                        <p>{{d.gong_name}}:{{d.gong}}</p>
                        {{# } }}
                        {{# if(d.one > 0){ }}
                        <p>{{d.one_name}}:{{d.one}}</p>
                        {{# } }}
                        {{# if(d.two > 0){ }}
                        <p>{{d.two_name}}:{{d.two}}</p>
                        {{# } }}
                        {{# if(d.three > 0){ }}
                        <p>{{d.three_name}}:{{d.three}}</p>
                        {{# } }}
                        {{# if(d.four > 0){ }}
                        <p>{{d.four_name}}:{{d.four}}</p>
                        {{# } }}
                        {{# if(d.five > 0){ }}
                        <p>{{d.five_name}}:{{d.five}}</p>
                        {{# } }}
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
    //加载列表
    layList.tableList('List',"{:Url('log_list')}",function (){
        return [
            {field: 'id', title: 'ID',width:'4%'},
            {field: 'uid', title: '被奖励人',width:'8%',templet:'#uid'},
            {field: 'do_uid', title: '奖励人',width:'8%',templet:'#do_uid'},
            {field: 'from', title: '来源',width:'8%',templet:'#from'},
            {field: 'tid', title: '涉及内容',templet:'#tid'},
            {field: 'explain', title: '奖励说明'},
            {field: 'type', title: '奖励类型',width:'8%'},
            {field: 'reward', title: '具体内容',templet:'#reward'},
            {field: 'create_time', title: '奖励时间',width:'10%'},
        ];
    });

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
                    uid:'',
                    model:''
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
                layList.form.on("select(model)", function (data) {
                    that.where.model = data.value;
                });
                layList.form.on("select(type)", function (data) {
                    that.where.type = data.value;
                });
            }
        })
    });
</script>
{/block}
