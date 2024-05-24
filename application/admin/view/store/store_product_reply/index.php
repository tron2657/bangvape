{extend name="public/container"}

{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" style="margin-top: -27px">
        <div class="layui-col-md12">
            <div class="layui-tab layui-tab-brief" lay-filter="tab">
                <ul class="layui-tab-title" style="background-color: white;top: 10px">
                    {eq name="status" value="-1"}
                    <li lay-id="list">
                        <a href="{:Url('index')}">评论列表</a>
                    </li>
                    <li lay-id="list" class="layui-this">
                        <a href="javascript:;">回收站</a>
                    </li>
                    {else /}
                    <li lay-id="list" class="layui-this">
                        <a href="javascript:;">评论列表</a>
                    </li>
                    <li lay-id="list">
                        <a href="{:Url('index',['status'=>-1,])}">回收站</a>
                    </li>
                    {/eq}
                </ul>
            </div>
            <div class="layui-card" id="app">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="padding-bottom: 20px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <div class="layui-inline">
                                    <label class="layui-form-label">评论内容:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="comment" v-model="where.comment" lay-filter="comment" class="layui-input" placeholder="请输入内容">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">评价状态:</label>
                                    <div class="layui-input-block">
                                        <select name="is_reply" v-model="where.is_reply" lay-filter="is_reply">
                                            <option value="">全部</option>
                                            <option value="0">未回复</option>
                                            <option value="2">已回复</option>
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
                <div class="layui-card-header">评论列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" style="margin-top: 10px">
                        {eq name="status" value="-1"}
                        <button class="layui-btn layui-btn-sm" data-type="restore">还原</button>
                        {else /}
                        <button class="layui-btn layui-btn-sm" data-type="delete">批量删除</button>
                        <button class="layui-btn layui-btn-sm" onclick="reply()">批量回复</button>
                        {/eq}
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="comment">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;">{{d.comment}}</div>
                        <div>{{d.add_time}}</div>
                    </script>
                    <script type="text/html" id="merchant_reply_content">
                        <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;">{{d.merchant_reply_content}}</div>
                        <div>{{d.merchant_reply_time}}</div>
                    </script>
                    <script type="text/html" id="nickname">
                        <p>{{d.nickname}}【{{d.uid}}】</p>
                    </script>
                    <!--图片-->
                    <script type="text/html" id="avatar">
                        {{#  if(d.avatar==''){ }}
                        {{#  } else { }}
                        <img style="cursor: pointer" onclick="javascript:$eb.openImage(this.src);" src="{{d.avatar}}">
                        {{#  } }}
                    </script>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('详情','{:Url('reply')}?id={{d.id}}')">
                            回复
                        </button>
                        <button class="layui-btn layui-btn-xs" lay-event='reply'>
                            <i class="fa fa-warning"></i> 删除
                        </button>
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
    var status = '<?=$status?>';
    //实例化form
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('reply_list',[ 'status'=>$status])}",function (){
        if(status == '-1'){
            var join = [
                {type:'checkbox'},
                {field: 'id', title: 'ID', event:'id',width:'5%'},
                {field: 'avatar', title: '头像',templet:'#avatar',width:'6%'},
                {field: 'nickname', title: '作者',templet:'#nickname',width:'10%'},
                {field: 'store_name', title: '评价商品',width:'12%'},
                {field: 'comment', title: '评论内容',templet:'#comment'},
                {field: 'merchant_reply_content', title: '回复内容',templet:'#thread_content'},
                {field: 'del_time', title: '删除时间',width:'9%'},
            ];
        }else{
            var join = [
                {type:'checkbox'},
                {field: 'id', title: 'ID', event:'id',width:'5%'},
                {field: 'avatar', title: '头像',templet:'#avatar',width:'6%'},
                {field: 'nickname', title: '作者',templet:'#nickname',width:'10%'},
                {field: 'store_name', title: '评价商品',width:'12%'},
                {field: 'comment', title: '评论内容',templet:'#comment'},
                {field: 'merchant_reply_content', title: '回复内容',templet:'#thread_content'},
                {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'},
            ];
        }
        return join;
    });
    //自定义方法
    var action={
        // 清理
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'store.store_product_reply',a:'remove'}),{ids:ids},function (res) {
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
                layList.basePost(layList.Url({c:'store.store_product_reply',a:'restore'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要还原的评论');
            }
        },
        // 批量删除
        delete:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'store.store_product_reply',a:'deletes'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要删除的评论');
            }
        }
    };

    // 批量回复
    function reply(){
        var ids=layList.getCheckData().getIds('id');

        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }
            $eb.createModalFrame('理由',"{:Url('reply')}?id="+str+'&&status={$status}');
            console.log(ids)
        }else{
            layList.msg('请选择要批量删除的帖子');
        }
    }

    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });


    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'store.store_product_reply',a:'delete',q:{id:data.id, field:'status', value:-1}});
                var code = {title:"是否要删除该评论",text:"删除后可在回收站中还原",confirm:'是的，我要删除'};
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
                layList.form.on("select(is_reply)", function (data) {
                    that.where.is_reply = data.value;
                });
            }
        })
    });
</script>
{/block}
