{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12" id="app">
            <div class="layui-tab layui-tab-brief" lay-filter="tab">
            </div>
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">昵称/UID</label>
                                <div class="layui-input-block">
                                    <input type="text" name="uid" v-model="where.uid" class="layui-input" placeholder="请输入用户昵称或者UID">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">搜索词</label>
                                <div class="layui-input-block">
                                    <input type="text" name="keyword" v-model="where.keyword" class="layui-input" placeholder="请输入关键词">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">时间选择</label>
                                <div class="layui-input-block" data-type="data" v-cloak="">
                                    <input type="text" ref="date_time" v-model="where.data" class="layui-input" placeholder="" style="width: 180px">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" @click="search" type="button">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                    <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
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
                <div class="layui-card-header">搜索日志</div>
                <div class="layui-card-body">
                    <div class="alert alert-info" role="alert">
                        搜索日志
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="layui-btn-container">
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>

                    <script type="text/html" id="nickname">
                        {{# if(d.uid>0){ }}
                        <p>{{d.nickname}}【{{d.uid}}】 </p>
                        {{# }else{ }}
                        <p>游客 </p>
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
    setTimeout(function () {
        $('.alert-info').hide();
    },3000);
    //实例化form

    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('search_list')}",function (){
        return [
            {field: 'id', title: 'ID',width:'4%'},
            {field: 'uid', title: '用户',templet:'#nickname'},
            {field: 'keyword', title: '搜索词'},
            {field: 'model', title: '触发版块'},
            {field: 'create_time', title: '时间'},
            {field: 'source', title: '来源'},
        ];
    });

    var action={
        unable:function(){
            var code = {title:"提示",text:"该功能未开通或已过期，如需开通，请联系客服！",type:'info',confirm:'联系客服',cancel:'取消',confirmBtnColor:'#0ca6f2'};
            $eb.$swal('delete',function(){
                $eb.createModalFrame('联系客服','https://h5a.opensns.cn/auth/Index/tip_box.html',{h:600,w:700})
            }, code)
        },
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });

    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'com.com_forum_admin',a:'del',q:{id:data.id}});
                var code = {title:"你确定要删除这条信息吗",text:"删除即取消权限并删除历史记录，无法恢复，请慎重操作！",confirm:'是的，我要删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            layList.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                        layList.reload();
                    });
                },code)
                break;
            case 'open':
                var url=layList.U({c:'com.com_forum_admin',a:'open',q:{id:data.id}});
                var code = {title:"是否开启该版主",text:"开启后可再次禁用",confirm:'是的，我要开启'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            layList.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                        layList.reload();
                    });
                },code)
                break;
            case 'close':
                var url=layList.U({c:'com.com_forum_admin',a:'close',q:{id:data.id}});
                var code = {title:"是否禁用该版主",text:"禁用后可再次开启",confirm:'是的，我要禁用'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            layList.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                        layList.reload();
                    });
                },code)
                break;
        }
    })

    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });

    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
                where:{
                    data:'',
                    keyword:'',
                    uid:''
                }
            },
            watch: {

            },
            methods: {
                search:function () {
                    layList.reload(this.where,true);
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
            }
        })
    });
</script>
{/block}
