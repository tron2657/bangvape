{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" style="margin-top: -27px">
        <div class="layui-col-md12">
            <div class="layui-card" id="app">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding-bottom: 20px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">

                            <div class="layui-inline">
                                <label class="layui-form-label">评论内容:</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" v-model="where.name" lay-filter="name" class="layui-input" placeholder="请输入内容">
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
                <div class="layui-card-header">评论列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="margin-top: 10px">
                        <button class="layui-btn layui-btn-sm" data-type="restore">还原</button>
                        <button class="layui-btn layui-btn-sm" data-type="remove">清理</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="fid_name">
                        <div style="font-weight:bold">{{d.fid_name}}</div>
                        <div>—{{d.class_name}}</div>
                    </script>
                    <script type="text/html" id="time">
                        <div>创建时间：{{d.create_time}}</div>
                        {{# if (d.status == -1) { }}
                        <div>删除时间：{{d.del_time}}</div>
                        {{# } }}
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('详情','{:Url('reply')}?id={{d.id}}')">
                            回复
                        </button>
                        {{# if (d.status != -1) { }}
                        <button class="layui-btn layui-btn-xs" lay-event='delstor'>
                            <i class="fa fa-warning"></i> 删除
                        </button>
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
    var status = 1;
    //实例化form
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('order_list')}",function (){
        var join = [
            {type:'checkbox'},
            {field: 'id', title: 'ID', event:'id',width:'5%'},
            {field: 'user_message', title: '评论用户', event:'id',width:'14%'},
            {field: 'product', title: '评论商品', event:'id',width:'15%'},
            {field: 'comment', title: '评论内容',width:'15%'},
            {field: 'merchant_reply_content', title: '回复内容',width:'15%'},
            {field: 'score', title: '评论级别',width:'10%'},
            {field: 'create_time', title: '时间',templet:'#time',width:'10%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'12%'},
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
            case 'verify':
                var url=layList.U({c:'com.com_post',a:'quick_edit',q:{id:data.id, field:'status', value:1}});
                var code = {title:"操作提示",text:"你确定要审核通过该版块吗？",type:'info',confirm:'是的，我要审核通过'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '审核失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                }, code)
                break;
            case 'delstor':
                var url=layList.U({c:'Knowledge.KnowledgeComment',a:'delete',q:{id:data.id, field:'status', value:-1}});
                var code = {title:"是否要删除该评论",text:"删除后前端不再显示",confirm:'是的，我要删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'reduction':
                var url=layList.U({c:'com.com_post',a:'quick_edit',q:{id:data.id, field:'status', value:1}});
                var code = {title:"是否要还原该评论",text:"还原后可在前台正常显示",confirm:'是的，我要还原'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '还原失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
        }
    })
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
