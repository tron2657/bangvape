{extend name="public/container"}
{block name="head_top"}
<!-- 全局js -->
<script src="{__PLUG_PATH}echarts/echarts.common.min.js"></script>
<script src="{__PLUG_PATH}echarts/theme/macarons.js"></script>
<script src="{__PLUG_PATH}echarts/theme/westeros.js"></script>
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
{/block}
{block name="content"}
<div id="app">
    <form class="layui-form">
        <div class="layui-input-block" style="width: 200px;margin-left: 0;margin-bottom: 10px">
            <select style="
    border:none;
    background: none;
    padding: 5px;
    margin: 5px;
    color: #7d7d7d;font-size:18px;appearance:inherit;width: 200px;padding-right: 40px" lay-filter="select_channel">
                {volist name="channel_list" id="one_channel"}
                <option value="{$one_channel.id}">{$one_channel['title']}</option>
                {/volist}
            </select>
        </div>



    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>数据概况</h5>
                </div>
                <div class="ibox-content" style="padding: 0 20px">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-sm-3 ui-sortable">
                                <div class="ibox float-e-margins info-box">
                                    <span class="glyphicon glyphicon-question-sign tip-bitten" data-original-title="截止到当前时间，频道内总内容数目" data-placement="top" data-toggle="tooltip"></span>
                                    <div class="ibox-title">
                                        <h5>内容数</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h1 class="no-margins">{{count_content.content_num}}</h1>
                                        <small>较昨日：
                                            <i v-if="count_content.is_up==1" class="up_icon"></i>
                                            <i v-else-if="count_content.is_down==1" class="down_icon"></i>
                                            {{count_content.change_rate}}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 ui-sortable">
                                <div class="ibox float-e-margins info-box">
                                    <span class="glyphicon glyphicon-question-sign tip-bitten"  data-original-title="截止到当前时间，频道总浏览量" data-placement="top" data-toggle="tooltip"></span>
                                    <div class="ibox-title">
                                        <h5>浏览数</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h1 class="no-margins">{{count_view.view_num}}</h1>
                                        <small>较昨日：
                                            <i v-if="count_view.is_up==1" class="up_icon"></i>
                                            <i v-else-if="count_view.is_down==1" class="down_icon"></i>
                                            {{count_view.change_rate}}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div v-show="count_open_rate.has_open_rate==1" class="col-sm-3 ui-sortable">
                                <div class="ibox float-e-margins info-box">
                                    <span class="glyphicon glyphicon-question-sign tip-bitten"  data-original-title="开启率 = 开启数目 / 总用户数目" data-placement="top" data-toggle="tooltip"></span>
                                    <div class="ibox-title">
                                        <h5>开启率</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <h1 class="no-margins">{{count_open_rate.rate}}%</h1>
                                        <small>较昨日：
                                            <i v-if="count_open_rate.is_up==1" class="up_icon"></i>
                                            <i v-else-if="count_open_rate.is_down==1" class="down_icon"></i>
                                            {{count_open_rate.change_rate}}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>内容数</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart-content echarts" ref="content_num_echart" id="flot-dashboard-chart1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>新增浏览</h5>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-white"
                                    :class="{'active': active == 'thirtyday'}" @click="getNewlist('thirtyday')">30天
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'week'}"
                                    @click="getNewlist('week')">周
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'month'}"
                                    @click="getNewlist('month')">月
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'year'}"
                                    @click="getNewlist('year')">年
                            </button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart-content echarts" ref="new_num_echart" id="flot-dashboard-chart1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div>
            <div class="col-lg-6" style="padding-right: 7.5px">
                <div class="layui-card">
                    <div class="layui-card-header">内容数排行　
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table layuiadmin-page-table" lay-skin="line">
                            <thead>
                            <tr>
                                <th>排名</th>
                                <th>频道名称</th>
                                <th>内容数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(one_channel,index) in channel_num_list">
                                <td>
                                    <span v-if="index ==1" style="color:red">{{index}}.</span>
                                    <span v-else-if="index ==2" style="color:#0ca6f2">{{index}}.</span>
                                    <span v-else>{{index}}.</span>
                                </td>
                                <td><span>{{one_channel.title}}</span></td>
                                <td><span>{{one_channel.count_num}}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div sty>
            <div class="col-lg-6" style="padding-left: 7.5px">
                <div class="layui-card">
                    <div class="layui-card-header"  style="padding: 0 10px 0 15px;">
                        浏览量排行
                        <!--<select style="    float: right;
    border-color: rgb(191, 191, 191);
    padding: 5px;
       margin: 4px 0;
    border-radius: 3px;
    color: #7d7d7d;" v-model="select_id" @change="getViewRank(select_id)">
                            <option :value="1">总浏览量（默认）</option>
                            <option :value="2">昨日新增浏览</option>
                            <option :value="3">7日新增浏览</option>
                            <option :value="4">30日新增浏览</option>
                        </select>-->
                        <div class="layui-input-block" style="display: inline-block;float: right;margin: 2.5px;">
                            <select lay-filter="select_view_type">
                                <option value="1">总浏览量（默认）</option>
                                <option value="2">昨日新增浏览</option>
                                <option value="3">7日新增浏览</option>
                                <option value="4">30日新增浏览</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table layuiadmin-page-table" lay-skin="line">
                            <thead>
                            <tr>
                                <th>排名</th>
                                <th>频道名称</th>
                                <th>浏览量</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(one_channel,index) in view_num_list">
                                <td>
                                    <span v-if="index ==1" style="color:red">{{index}}.</span>
                                    <span v-else-if="index ==2" style="color:#0ca6f2">{{index}}.</span>
                                    <span v-else>{{index}}.</span>
                                </td>
                                <td><span>{{one_channel.title}}</span></td>
                                <td><span>{{one_channel.count_num}}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!--<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>新增用户</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" ref="user_echart" id="flot-dashboard-chart2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
{/block}
{block name="script"}
<style scoped>
    .layui-form-select .layui-input{
        line-height: 36px;
        height: 36px;
    }
    .clear-both{
        clear:both;
    }
    .tip-ques {
        position: relative;
        display: block;
        margin: 200px auto;
        width: 200px;
        padding: 10px 20px;
        font-size: 20px;
        background: #fff;
        color: #6bdf4e;
        border: 1px solid #6bdf4e;
        cursor: pointer;
    }
    .tip-ques::after {
        content: attr(data-tip);
        display: none;
        position: absolute;
        padding: 5px 10px;
        left: 50%;
        bottom: 100%;
        margin-bottom: 12px;
        transform: translateX(-50%);
        font-size: 16px;
        background: #000;
        color: #fff;
        cursor: default;
    }
    .tip-ques::before {
        content: " ";
        position: absolute;
        display: none;
        left: 50%;
        bottom: 100%;
        transform: translateX(-50%);
        margin-bottom: 3px;
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 9px solid #000;
    }
    .tip-ques:hover::after,
    .tip-ques:hover::before {
        display: block;
    }

    .top-choose-content{
        display: flex;
        justify-content: space-between;
        background-color: #fff;
        height: 50px;
        align-items: center;
        padding-left: 15px;
        padding-right: 20px;
        border-radius: 7px;
    }
    .top-choose-content .left{
        font-size: 18px;
        color: #000;
        font-weight: 600;
    }
    .top-choose-content .left span{
        font-size: 18px;
        color: #999;
        font-weight: 500;
    }
    .top-choose-content .right{
        font-size: 14px;
        color: #333;
        display: flex;
    }
    .top-choose-content .right span{
        display: block;
        margin: 0 10px;
    }
    .top-choose-content .right .tab{
        cursor: pointer;
        position: relative;
    }
    .top-choose-content .right .top-active-tab{
        color: #0ca6f2;
    }
    .top-choose-content .right .top-active-tab:before{
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #0ca6f2;
        bottom: -3px;
    }
    .statis-content{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 60px 0 30px;
    }
    .statis-tab-box-content{
        display: flex;
    }
    .statis-tab-box-content .tab-box{
        width: 85px;
        height: 35px;
        line-height: 33px;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        text-align: center;
        font-size: 12px;
        cursor: pointer;
        position: relative;
    }
    .statis-tab-box-content .tab-box:before{
        content: '';
        position: absolute;
        height: 25px;
        width: 1px;
        background-color: #eee;
        right: 0;
        top: 5px;
    }
    .statis-tab-box-content .tab-box:first-child{
        border-left: 1px solid #eee;
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }
    .statis-tab-box-content .tab-box:last-child{
        border-right: 1px solid #eee;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    .statis-tab-box-content .tab-box:last-child:before{
        content: none;
    }
    .statis-tab-box-content .active-tab{
        border: 1px solid #0ca6f2!important;
        color: #0ca6f2;
        border-radius: 6px;
    }
    .statis-tab-box-content .active-tab:before{
        content: none;
    }

    .data-content .down-img{
        position: absolute;
        cursor: pointer;
        width: 15px;
        top: 140px;
        right: 50px;
    }
    .first-box{
        display: none;
    }
    .second-box{
        display: none;
    }
    .box {
        width: 0px;
    }

    .community-tables {
        margin: 10px;
        width: 48%;
        border: 1px solid #f2f2f2;
    }

    .community-tables p {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .data-box{
        background-color: inherit;
        padding: 0;
    }
    .data-box h5{
        margin-top: 10px;
        margin-left: 15px;
        font-size: 18px;
        margin-bottom: 15px;
        color: #000;
    }
    .data-content{
        border-radius: 7px;
        padding: 0;
        position: relative;
    }
    tr{
        background-color: #fff!important;
    }
    .center-box{
        text-align: center!important;
    }
    .today{
        font-size: 22px!important;
        color: #0ca6f2;
    }
    .question-box img{
        width: 15px!important;
        height: 15px!important;
        margin-left: 0;
        margin-bottom: 3px;
    }
    .question-box{
        position: relative;
    }
    .question-box .tip-box{
        position: absolute;
        background-color: rgba(0,0,0,0.7);
        color: #fff;
        font-size: 12px;
        width: 150px;
        z-index: 1;
        border-radius: 5px;
        padding: 0 3px;
        text-align: left;
        top: 40px;
        right: 42px;
        display: none;
    }
    .tip-box:before{
        content: '';
        width: 0;
        height: 0;
        position: absolute;
        border-bottom: 6px solid rgba(0,0,0,0.7);
        border-right: 6px solid transparent;
        border-left: 6px solid transparent;
        top: -6px;
        left: 68px;
    }

    .select {
        display: inline-block;
        width: 200px;
        height: 32px;
        position: relative;
        vertical-align: middle;
        padding: 0;
        overflow: hidden;
        background-color: #fff;
        color: #555;
        border: 1px solid #e6e6e6;
        text-shadow: none;
        border-radius: 2px;
        transition: box-shadow 0.25s ease;
        z-index: 2;
        margin-left: 20px;
    }

    .select:hover {
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
    }

    .select:before {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        border: 6px solid transparent;
        border-top-color: #ccc;
        top: 12px;
        right: 10px;
        cursor: pointer;
        z-index: -2;
    }
    .select select {
        padding-left: 10px;
        cursor: pointer;
        line-height: 30px;
        width: 100%;
        border: none;
        background: transparent;
        background-image: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        height: 32px;
    }
    .select select:focus {
        outline: none;
    }
    .option {
        padding:0 30px 0 10px;
        min-height:40px;
        display:flex;
        align-items:center;
        background:#fff;
        border-top:#222 solid 1px;
        position:absolute;
        top:0;
        width: 100%;
        pointer-events:none;
        order:2;
        z-index:1;
        transition:background .4s ease-in-out;
        box-sizing:border-box;
        overflow:hidden;
        white-space:nowrap;

    }
    .option:hover {
        background:#f2f2f2;
    }
    .option:active {
        background:#0092DC;
    }
    .down_icon{
        color: red;
        display: inline-block;
        width: 0;
        height: 0;
        margin:0 5px;
        vertical-align: middle;
        border-top: 6px dashed;
        border-right: 6px solid transparent;
        border-left: 6px solid transparent;
    }
    .up_icon{
        color: #0092DC;
        display: inline-block;
        width: 0;
        height: 0;
        margin:0 5px;
        vertical-align: middle;
        border-bottom: 6px dashed;
        border-right: 6px solid transparent;
        border-left: 6px solid transparent;
    }
    .info-box{
        border: 1px solid #e8e8e8;
        box-shadow: none;
        border-radius: 3px;
        margin-top: 20px;
        margin-bottom: 25px;
    }
    .ui-sortable .ibox-title {
        cursor: auto;
    }
    .tip-bitten{
        margin: 13px;
        float: right;
        color: #ccc;
    }
</style>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    require(['vue','axios','layer'],function(Vue,axios,layer){
        new Vue({
            el:"#app",
            data:{
                option:{},
                myChart:{},
                active:'thirtyday',
                channel_num_list:[],
                view_num_list:[],
                select_id:1,
                channel_id:"{$channel_id}",
                count_content:[],
                count_view:[],
                count_open_rate:[],
            },
            methods:{
                getDefaultInfo:function () {
                    var that=this;
                    axios.get("{:Url('getDefaultInfo')}?channel_id="+that.channel_id).then((res)=>{
                        console.log(res.data.data);
                    that.count_content=res.data.data.count_content;
                    that.count_view=res.data.data.count_view;
                    that.count_open_rate=res.data.data.count_open_rate;
                });
                },
                change_channel:function () {
                    this.getDefaultInfo();
                    this.getlist();
                    this.getNewlist();
                },
                getlist:function (e) {
                    var that=this;
                    axios.get("{:Url('content_num_echart')}?channel_id="+that.channel_id).then((res)=>{
                        that.myChart.content_num_echart.clear();
                    console.log(res.data);
                        that.myChart.content_num_echart.setOption(that.chartsetoption(res.data.data));
                    });
                },
                getNewlist:function(e){
                    var that=this;
                    var cycle = e!=null ? e :'thirtyday';
                    axios.get("{:Url('new_num_echart')}?channel_id="+that.channel_id+"&cycle="+cycle).then((res)=>{
                        that.myChart.new_num_echart.clear();
                        that.myChart.new_num_echart.setOption(that.chartsetoption(res.data.data));
                        that.active = cycle;
                    });
                },
                getContentRank:function (e) {
                    var that=this;
                    axios.get("{:Url('content_rank')}").then((res)=>{
                        console.log(res.data.data);
                        that.channel_num_list=  res.data.data ;
                    });
                },
                getViewRank:function (e) {
                    var that=this;
                    var type = e!=null ? e :'1';
                    axios.get("{:Url('view_rank')}?type="+type).then((res)=>{
                        console.log(res.data.data);
                    that.view_num_list=  res.data.data ;
                });
                },
                chartsetoption:function(data){
                    this.option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                crossStyle: {
                                    color: '#999'
                                }
                            }
                        },
                        toolbox: {
                            feature: {
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: false},
                                saveAsImage: {show: true}
                            }
                        },
                        legend: {
                            data:data.legend
                        },
                        grid: {
                            x: 70,
                            x2: 50,
                            y: 60,
                            y2: 50
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: data.xAxis,
                                axisPointer: {
                                    type: 'shadow'
                                },
                                axisLabel:{
                                    interval: 0,
                                    rotate:40
                                }


                            }
                        ],
                        yAxis:[{type : 'value'}],
                        series: data.series
                    };
                    return  this.option;
                },
                setChart:function(name,myChartname){
                    this.myChart[myChartname] = echarts.init(name,'macarons');//初始化echart
                }
            },
            mounted:function () {
                const self = this;
                this.getDefaultInfo();
                this.setChart(self.$refs.content_num_echart,'content_num_echart');//内容数图标
                this.setChart(self.$refs.new_num_echart,'new_num_echart');//新增浏览数图标
                this.getlist();
                this.getNewlist();
                this.getContentRank();
                this.getViewRank();

                $('[data-toggle="tooltip"]').tooltip();
                $(".question-img").hover(function(){
                    $(this).next().show()
                },function(){
                    $(this).next().hide()
                });
                var that=this;
                //实例化form
                layList.form.render();
                layui.use(["form"],function () {
                    var form = layui.form;
                    form.on('select(select_channel)', function(data){
                        that.channel_id=data.value;
                        that.change_channel();
                    });
                    form.on('select(select_view_type)', function(data){
                        that.getViewRank(data.value);
                    });
                })
            }
        });
    });
</script>
{/block}
