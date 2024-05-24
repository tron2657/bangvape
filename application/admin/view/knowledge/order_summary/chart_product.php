{extend name="public/container"}
{block name="head_top"}
<style>
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
    .block-rigit{
        text-align: right;
    }
    .block-rigit button{
        width: 100px;
        letter-spacing: .5em;
        line-height: 28px;
    }
    .layuiadmin-card-list{
        padding: 1.6px;
    }
    .layuiadmin-card-list p.layuiadmin-normal-font {
        padding-bottom: 10px;
        font-size: 20px;
        color: #666;
        line-height: 24px;
    }
</style>
<script src="{__PLUG_PATH}echarts.common.min.js"></script>
{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">产品状态:</label>
                                    <div class="layui-input-block" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" :class="{'layui-btn-primary':status!=item.value}" @click="setStatus(item)" type="button" v-for="item in statusList" style="margin-top:0px">{{item.name}}</button>
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">类型:</label>
                                    <div class="layui-input-block" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" :class="{'layui-btn-primary':typeStatus!=item.value}" @click="setType(item)" type="button" v-for="item in typeList" style="margin-top:0px">{{item.name}}</button>
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">创建时间:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList" @click="setData(item)" :class="{'layui-btn-primary':data!=item.value}" style="margin-top:0px">{{item.name}}</button>
                                        <button class="layui-btn layui-btn-sm" type="button" ref="time" @click="setData({value:'zd',is_zd:true})" :class="{'layui-btn-primary':data!='zd'}" style="margin-top:0px">自定义</button>
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" v-show="showtime==true" ref="date_time" style="margin-top:0px">{$year.0} - {$year.1}</button>
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <div class="layui-input-block">
                                        <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top:0px">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm" style="margin-top:0px">
                                            <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md3" style="width: 100%;display: flex;justify-content: space-between" v-cloak="">
            <div class="layui-col-sm3 layui-card" style="width: 24%" v-if="badge.column">
                <div class="layui-card-header">
                    付费专栏数量
                    <span class="layui-badge layuiadmin-badge" style="background-color: #0ca6f2;color: #fff">{{badge.column.field}}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{{badge.column.count}}</p>
                    <p>
                        {{badge.column.content}}
                        <span class="layuiadmin-span-color">{{badge.column.sum}}<i ></i></span>
                    </p>
                </div>
            </div>
            <div class="layui-col-sm3 layui-card" style="width: 24%" v-if="badge.free_column">
                <div class="layui-card-header">
                    免费专栏数量
                    <span class="layui-badge layuiadmin-badge" style="background-color: #0ca6f2;color: #fff">{{badge.free_column.field}}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{{badge.free_column.count}}</p>
                    <p>
                        {{badge.free_column.content}}
                        <span class="layuiadmin-span-color">{{badge.free_column.sum}}<i ></i></span>
                    </p>
                </div>
            </div>
            <div class="layui-col-sm3 layui-card" style="width: 24%" v-if="badge.free_product">
                <div class="layui-card-header">
                    付费单品数量
                    <span class="layui-badge layuiadmin-badge" style="background-color: #0ca6f2;color: #fff">{{badge.free_product.field}}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{{badge.free_product.count}}</p>
                    <p>
                        {{badge.free_product.content}}
                        <span class="layuiadmin-span-color">{{badge.free_product.sum}}<i ></i></span>
                    </p>
                </div>
            </div>
            <div class="layui-col-sm3 layui-card" style="width: 24%;margin-bottom: 15px;" v-if="badge.product">
                <div class="layui-card-header">
                    免费单品数量
                    <span class="layui-badge layuiadmin-badge" style="background-color: #0ca6f2;color: #fff">{{badge.product.field}}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{{badge.product.count}}</p>
                    <p>
                        {{badge.product.content}}
                        <span class="layuiadmin-span-color">{{badge.product.sum}}<i ></i></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12">
            <div class="layui-card">
                <div class="layui-card-header">图表展示:</div>
                <div class="layui-card-body layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-btn-container" ref="echarts_list" id="echarts_list" style="height:400px"></div>
                    </div>
                </div>
            </div>
        </div>
       <!-- <div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">销量排行　<span class="layui-badge layui-bg-orange">TOP10</span></div>
                <div class="layui-card-body">
                    <p class="layuiadmin-normal-font">商品销售总计：<span style="color:lightblue">{{SalesList.sum_count}}</span> 件 共计 <span style="color:coral">{{SalesList.sum_price}}</span>元</p>
                    <div class="layuiadmin-card-list" v-for="item in SalesList.list">
                        <span>{{item.store_name}}</span>
                        <div class="layui-progress layui-progress-big" lay-showpercent="yes">
                            <div class="layui-progress-bar" :class="item.class" :style="{'width':item.w+'%'}"><span class="layui-progress-text">{{item.p_count}}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">差评产品</div>
                <div class="layui-card-body">
                    <table class="layui-table layuiadmin-page-table" lay-skin="line">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                            <th>单价</th>
                            <th>差评数量</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in negativeList">
                            <td><span class="first">{{item.name}}</span></td>
                            <td><i class="layui-icon layui-icon-rmb">{{item.price}}</i></td>
                            <td><span>{{item.count}}</span></td>
                            <td><span v-if="item.is_column==1" >专栏</span><span v-else>单品</span></td>
                            <td><button type="button" class="layui-btn layui-btn-xs" @click="edit(item)"><i class="layui-icon layui-icon-edit"></i>编辑</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--<div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">利润排行　<span class="layui-badge layui-bg-orange">TOP10</span></div>
                <div class="layui-card-body">
                    <p class="layuiadmin-normal-font">商品销售总计：<span style="color:lightblue">{{ProfityList.sum_count}}</span> 件 共计 <span style="color:coral">{{ProfityList.sum_price}}</span>元</p>
                    <table class="layui-table layuiadmin-page-table" lay-skin="line">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                            <th>销量占比(%)</th>
                            <th>购买个数</th>
                            <th>利润(￥)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in ProfityList.list">
                            <td><span class="first">{{item.store_name}}</span></td>
                            <td><i class="layui-icon layui-icon-log"> {{item.w}}</i></td>
                            <td><span>{{item.p_count}}</span></td>
                            <td> {{item.sum_price}} <i class="layui-icon layui-icon-rmb"></i></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>-->
        <div class="layui-row layui-col-space15">
            <!-- <div class="layui-col-sm4 layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">待补货商品</div>
                    <div class="layui-card-body">
                        <p class="layuiadmin-normal-font">注:库存列可进行快速编辑,点击当前页面空白保存编辑</p>
                        <table class="layui-hide" id="PhList" lay-filter="PhList"></table>
                        <script type="text/html" id="bar">
                            <button type="button" class="layui-btn layui-btn-xs" lay-event="PhList_edit"><i class="layui-icon layui-icon-edit"></i>编辑</button>
                        </script>
                    </div>
                </div>
            </div> -->
            <!--<div class="layui-col-sm4 layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">差评产品</div>
                    <div class="layui-card-body">
                        <table class="layui-table layuiadmin-page-table" lay-skin="line">
                            <thead>
                                <tr>
                                    <th>商品名称</th>
                                    <th>单价</th>
                                    <th>差评数量</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in negativeList">
                                    <td><span class="first">{{item.store_name}}</span></td>
                                    <td><i class="layui-icon layui-icon-rmb">{{item.price}}</i></td>
                                    <td><span>{{item.count}}</span></td>
                                    <td><button type="button" class="layui-btn layui-btn-xs" @click="edit(item)"><i class="layui-icon layui-icon-edit"></i>编辑</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->
            <!-- <div class="layui-col-sm4 layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">退货产品</div>
                    <div class="layui-card-body">
                        <table class="layui-table layuiadmin-page-table" lay-skin="line">
                            <thead>
                            <tr>
                                <th>商品名称</th>
                                <th>单价</th>
                                <th>退货数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in TuiPriesList">
                                <td><span class="first">{{item.store_name}}</span></td>
                                <td><i class="layui-icon layui-icon-rmb">{{item.price}}</i></td>
                                <td><span>{{item.count}}</span></td>
                                <td><button type="button" class="layui-btn layui-btn-xs" @click="edit(item)"><i class="layui-icon layui-icon-edit"></i>编辑</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    require(['vue'],function(Vue){
        new Vue({
            el:"#app",
            data:{
                option:{},
                badge:[],
                SalesList:[],
                ProfityList:[],
                negativeList:[],
                TuiPriesList:[],
                statusList:[
                    {name:'全部商品',value:''},
                    {name:'待上架产品',value:1},
                    {name:'产品回收站',value:5},
                ],
                typeList:[
                    {name:'全部',value:2},
                    {name:'专栏',value:1},
                    {name:'单品',value:0},
                ],
                dataList:[
                    {name:'全部',value:''},
                    {name:'今天',value:'today'},
                    {name:'本周',value:'week'},
                    {name:'本月',value:'month'},
                    {name:'本季度',value:'quarter'},
                    {name:'本年',value:'year'},
                ],
                status:'',
                data:'',
                title:'全部商品',
                count:0,
                myChart:{},
                showtime:false,
                typeStatus:2
            },
            watch:{

            },
            methods:{
                info:function(){
                    var that=this;
                    var index=layList.layer.load(2,{shade: [0.3,'#fff']});
                    layList.baseGet(layList.Url({a:'get_echarts_product',q:{type:this.status,is_column:this.typeStatus,data:this.data}}),function (res){
                        console.log(res.data.badge.column.field)
                        layList.layer.close(index);
                        that.badge=res.data.badge;
                        that.count=res.data.count;
                        var option=that.setoption(res.data.datetime,res.data.legdata,res.data.chatrList);
                        that.myChart.list.setOption(option);
                    },function () {
                        layList.layer.close(index);
                    });
                },
                edit:function(item){
                    $eb.createModalFrame('编辑',layList.U({c:'store.store_product',a:'edit',p:{id:item.id}}));
                },
                getSalesList:function(){
                    var that=this;
                    layList.baseGet(layList.Url({a:'get_echarts_maxlist',q:{data:this.data}}),function (rem) {
                        that.SalesList=rem.data;
                    });
                },
                getProfityList:function(){
                    var that=this;
                    layList.baseGet(layList.Url({a:'get_echarts_profity',q:{data:this.data}}),function (rem) {
                        that.ProfityList=rem.data;
                    });
                },
                // getTuiPriesList:function(){
                //     var that=this;
                //     layList.baseGet(layList.Url({a:'getTuiPriesList'}),function (rem) {
                //         that.TuiPriesList=rem.data;
                //     });
                // },
                getLackList:function(){
                    var that=layList;
                    var data={
                        url:that.U({a:'getLackList'}),
                        limit:10
                    };
                    layList.tables('PhList',data,[
                        {field: 'store_name', title: '商品信息'},
                        {field: 'price', title: '单价'},
                        {field: 'stock', title: '库存',edit:'stock'},
                        {field: 'right', title: '操作',align:'center',toolbar:'#bar'},
                    ]);
                    that.table.on('edit(PhList)',function (obj) {
                        var value = obj.value //得到修改后的值
                            ,data = obj.data //得到所在行所有键值
                            ,field = obj.field; //得到字段
                        switch (field){
                            case 'stock':
                                that.basePost(that.U({a:'editField',p:{id:obj.data.id}}),{stock:obj.value},function (rem) {
                                    that.msg(rem.msg);
                                });
                                break;
                            default:
                                console.log('未检测到指定字段'+name);
                                break;
                        }
                    });
                    // that.table.on('tool(PhList)', function (obj) {
                    //     var data = obj.data, layEvent = obj.event;
                    //         switch (layEvent) {
                    //             case 'PhList_edit':
                    //                 $eb.createModalFrame('编辑',that.U({c:'store.store_product',a:'edit',p:{id:data.id}}));
                    //                 break;
                    //             default:
                    //                 console.log('暂未监听到事件');
                    //                 break
                    //         }
                    // });
                },
                getnegativeList:function(){
                    var that=this;
                    layList.baseGet(layList.Url({a:'getnegativelist'}),function(rem) {
                        that.negativeList=rem.data.data;
                    });
                },
                setoption:function(xdata,legdata,seriesdata){
                    return this.option={
                        title: {text:this.title+'('+this.count+')'+'件',},
                        tooltip: {show: true},
                        legend: {data:legdata,},
                        xAxis : [{type : 'category', data :xdata,}],
                        yAxis : [{type : 'value'}],
                        series:seriesdata,
                    };
                },
                setChart:function(name,myChartname){
                    this.myChart[myChartname]=echarts.init(name);
                },
                setStatus:function (item){
                    this.status=item.value;
                    this.title=item.name;
                },
                setType:function (item){
                    this.typeStatus=item.value;
                },
                setData:function(item){
                    var that=this;
                    if(item.is_zd==true){
                        that.showtime=true;
                        this.data=this.$refs.date_time.innerText;
                    }else{
                        this.showtime=false;
                        this.data=item.value;
                    }
                },
                refresh:function () {
                    this.status='';
                    this.data='';
                    this.info();
                },
                search:function(){
                    this.info();
                }
            },
            mounted:function () {
                this.setChart(this.$refs.echarts_list,'list');
                this.info();
                this.getSalesList();
                this.getProfityList();
                this.getLackList();
                this.getnegativeList();
                //this.getTuiPriesList();
                layList.laydate.render({
                    elem:this.$refs.date_time,
                    trigger:'click',
                    eventElem:this.$refs.time,
                    range:true,
                });
            }
        });
    })
</script>
{/block}