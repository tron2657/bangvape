{extend name="public/container"}
{block name="content"}

<div class="layui-fluid">
    <style>
        .zzl_page_list{
            overflow-x: auto;
            overflow-y: hidden;
        }
        .zzl_page_list_content{
            min-width: 1300px;
        }
        .text-more-line{
            word-break: break-all;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }
    </style>
    <div class="layui-row layui-col-space15 zzl_page_list" >
        <div class="zzl_page_list_content">
            <div class="layui-col-md12">
                <div class="layui-tab layui-tab-brief" lay-filter="tab">
                    <ul class="layui-tab-title" style="background-color: white;top: 10px">
                        <li lay-id="list" {eq name='status' value='1'}class="layui-this" {/eq} >
                        <a href="{eq name='status' value='1'}javascript:;{else}{:Url('index',['channel_id'=>$channel_id])}{/eq}">信息流</a>
                        </li>
                        <li lay-id="list" {eq name='status' value='2'}class="layui-this" {/eq} >
                        <a href="{eq name='status' value='2'}javascript:;{else}{:Url('audit',['channel_id'=>$channel_id])}{/eq}">审核池</a>
                        </li>
                        <li lay-id="list" {eq name='status' value='10'}class="layui-this" {/eq}>
                        <a href="{eq name='status' value='10'}javascript:;{else}{:Url('hide',['channel_id'=>$channel_id])}{/eq}">屏蔽列表</a>
                        </li>
                    </ul>
                </div>
                <div class="layui-card"  id="app">
                    <div class="layui-card-header">搜索条件</div>
                    <div class="layui-card-body">
                        <form class="layui-form layui-form-pane" action="" style="margin-top: 10px">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">内容标题：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="post_title" v-model="where.post_title"  lay-filter="post_title" class="layui-input" placeholder="搜索内容标题">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">内容作者：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="post_author" v-model="where.post_author"  lay-filter="post_author" class="layui-input" placeholder="搜索内容作者">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">推送时长:</label>
                                    <div class="layui-input-block">
                                        <select name="post_long" v-model="where.post_long" lay-filter="post_long">
                                            <option value="">-推送时长-</option>
                                            <option value="1">无限制</option>
                                            <option value="2">24小时</option>
                                            <option value="3">3天</option>
                                            <option value="4">7天</option>
                                            <option value="5">30天</option>
                                            <option value="6">180天</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">推送人:</label>
                                    <div class="layui-input-block">
                                        <select name="recommend_uid" v-model="where.recommend_uid" lay-filter="recommend_uid">
                                            <option value="">-推送人-</option>
                                            {volist name="channel_admin_list" id="one_user"}
                                            <option value="{$one_user['uid']}">{$one_user['nickname']}</option>
                                            {/volist}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label" style="padding: 4px 15px;height: 30px;">推送时间:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="" style="margin-left: 125px;">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList" @click="setData(item)" :class="{'layui-btn-primary':where.data!=item.value}" style="margin-top: 0px">{{item.name}}</button>
                                        <button class="layui-btn layui-btn-sm" type="button" ref="time" @click="setData({value:'zd',is_zd:true})" :class="{'layui-btn-primary':where.data!='zd'}" style="margin-top: 0px">自定义</button>
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" v-show="showtime==true" ref="date_time" style="margin-top: 0px">{$year.0} - {$year.1}</button>
                                    </div>
                                </div>
                                <div class="layui">
                                    <div class="layui-input-inline">
                                        <button  @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button  @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>重置</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--产品列表-->
            <div class="layui-col-md12" style="margin-top: 15px;">
                <div class="layui-card">
                    <div class="layui-card-header">
                        {$channel_title} / 审核池
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container" style="margin-bottom: 2px;margin-top: 10px">
                            {eq name="status" value="2"}
                            <button class="layui-btn layui-btn-sm" onclick="audit_posts()">批量审核</button>
                            <button class="layui-btn layui-btn-sm" onclick="audit()">批量驳回</button>
                            {/eq}
                        </div>
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <style>
                            .c_one_post_content{
                                width: 500px;
                                margin: 10px auto;
                                overflow: hidden;
                            }
                            .c_top_line{
                                line-height: 30px;
                            }
                            .c_top_line div{
                                display: inline-block;
                                overflow: hidden;
                                text-overflow:ellipsis;
                                white-space: nowrap;
                            }
                            .c_title{
                                max-width: 250px;
                                margin-right: 15px;
                                font-weight: 600;
                            }
                            .c_topic{
                                max-width: 140px;
                                min-width: 80px;
                                border:1px solid #f2f2f2;
                                text-align: center;
                                padding: 0 5px;
                                border-radius: 5px;
                                margin-right: 10px;
                            }
                            .c_forum{
                                width: 80px;
                                border:1px solid #f2f2f2;
                                text-align: center;
                                padding: 0 5px;
                                border-radius: 5px;
                            }
                            .c_content{
                                line-height: 25px;
                            }
                            .c_left{
                                width: 375px;
                                float: left;
                            }
                            .c_summary{
                                overflow : hidden;
                                text-overflow: ellipsis;
                                display: -webkit-box;
                                -webkit-line-clamp: 3;
                                -webkit-box-orient: vertical;
                                min-height: 80px;
                            }
                            .c_bottom_info{
                                line-height: 25px;
                                width: 100%;
                            }
                            .c_author{
                                max-width: 45%;
                                overflow: hidden;
                                text-overflow:ellipsis;
                                white-space: nowrap;
                            }
                            .c_time{
                                margin-left: 20px;
                            }
                            .c_right{
                                float: left;
                                width: 100px;
                                height: 100px;
                            }
                            .c_right img{
                                width: 100px!important;
                                height: 100px!important;
                                margin: 0;
                            }
                            .clear{
                                clear: both;
                            }
                            .i_dian{
                                border: 5px solid;
                                border-radius: 100%;
                                width: 5px;
                                height: 5px;
                                display: inline-block;
                            }
                            .layui-btn{
                                padding: 0 10px;
                                height: 30px;
                                margin-bottom:10px ;
                                margin-right: 10px;
                                line-height: 30px;
                            }
                        </style>
                        <script type="text/html" id="post_content">
                            <div class="c_one_post_content">
                                <div class="c_top_line">
                                    {{# if(d.post_data.is_weibo!=1){ }}
                                    <div class="c_title">{{d.post_data.title}}</div>
                                    {{# } }}

                                    {{# if(d.post_data.has_topic==1){ }}
                                    <div class="c_topic">#{{d.post_data.topic_title}}#</div>
                                    {{# } }}
                                    <div class="c_forum">{{d.post_data.forum_title}}</div>
                                </div>
                                <div class="c_content">
                                    <div class="c_left">
                                        <div class="c_summary">
                                            {{d.post_data.summary}}
                                        </div>
                                        <div class="c_bottom_info"><span class="c_author">{{d.post_data.author_nickname}}</span> <span class="c_time">{{d.post_data.create_time_show}}</span></div>
                                    </div>
                                    <div class="c_right">
                                        {{# if(d.post_data.logo){ }}
                                        <img src="{{d.post_data.logo}}" style="width: 100px;height: 100px;">
                                        {{# } }}
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </script>
                        <script type="text/html" id="post_info">
                            <div>
                                推送人：{{d.recommend_user_nickname}}
                                <br/>
                                推送时间：{{d.create_time_show}}
                                <br/>
                                推送时长：{{d.post_long_show}}
                                <br/>
                                {{# if(d.deadline_show != ''){ }}
                                截止时间：{{d.deadline_show}}
                                <br/>
                                {{# } }}
                                排序权重：{{d.sort_num}}
                            </div>
                        </script>
                        <script type="text/html" id="act">
                            <button class="layui-btn layui-btn-xs" lay-event='audit_post'>
                                通过
                            </button>
                            <button class="layui-btn layui-btn-xs" style="background-color: red;"  onclick="$eb.createModalFrame('审核反馈','{:Url('audit_fail')}?ids={{d.id}}'),{w:600,h:400}">
                                拒绝
                            </button>

                            <br/>

                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('推送编辑','{:Url('edit_channel_post')}?id={{d.id}}'),{w:600,h:700}">
                                <i class="fa fa-paste"></i> 推送编辑
                            </button>
                            {{# if(d.post_data.show_default_edit == 1){ }}
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑帖子内容','{:Url('com.com_thread/edit')}?id={{d.post_id}}')">
                                <i class="fa fa-paste"></i> 内容编辑
                            </button>
                            {{# }else if(d.post_data.show_weibo_edit==1){ }}
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑动态内容','{:Url('com.com_thread/edit_weibo')}?id={{d.post_id}}')">
                                <i class="fa fa-paste"></i> 内容编辑
                            </button>
                            {{# }else if(d.post_data.show_news_edit==1){ }}
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑资讯内容','{:Url('com.com_thread/edit_news')}?id={{d.post_id}}')">
                                <i class="fa fa-paste"></i> 内容编辑
                            </button>
                            {{# }else if(d.post_data.show_video_edit==1){ }}
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('编辑视频内容','{:Url('com.com_thread/edit_video')}?id={{d.post_id}}')">
                                <i class="fa fa-paste"></i> 内容编辑
                            </button>
                            {{# } }}
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
    var status="{$status}";
    //实例化form
    layList.form.render();
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('audit_list',['channel_id'=>$channel_id])}",function (){
        switch(parseInt(status)){
            case 1:
                break;
            case 2:
                var join = [
                    {type:'checkbox'},
                    {field: 'id', title: 'ID', event:'id',width:'4%'},
                    {field: 'post_content', title: '内容信息',templet:'#post_content',width:'40%'},
                    {field: 'post_info', title: '推送信息',templet:'#post_info',width:'16%'},
                    {field: 'right', title: '操作',align:'left',toolbar:'#act'}
                ];
                break;
            case 0:
                break;
            default:
        }
        return join;
    });

    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    /*layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'channel.index',a:'set_status',p:{status:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'channel.index',a:'set_status',p:{status:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });*/
    //快速编辑
    /*layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'name':
                action.set_Class('name',id,value);
                break;
            case 'sort':
                action.set_Class('sort',id,value);
                break;
            case 'summary':
                action.set_Class('summary',id,value);
                break;
        }
    });*/
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'audit_post':
                var url=layList.U({c:'channel.post',a:'audit_post',q:{id:data.id}});
                var code = {title:"是否要审核通过该内容",text:"审核通过后可在信息流中查看到该内容",confirm:'是的，我要通过'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                            location.reload();
                        }else
                            return Promise.reject(res.data.msg || '操作失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            default:
        }
    })

    // 批量审批通过
    function audit_posts(){
        var ids=layList.getCheckData().getIds('id');

        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }

            var url=layList.U({c:'channel.post',a:'audit_posts',q:{id:str}});
            var code = {title:"是否要批量审批通过内容",text:"审核通过后可在信息流中查看到该内容",confirm:'是的，我要通过'};
            $eb.$swal('delete',function(){
                $eb.axios.get(url).then(function(res){
                    if(res.status == 200 && res.data.code == 200) {
                        $eb.$swal('success', res.data.msg);
                        location.reload();
                    }else
                        return Promise.reject(res.data.msg || '批量操作失败')
                }).catch(function(err){
                    $eb.$swal('error',err);
                });
            },code)
        }else{
            layList.msg('请选择要批量操作的内容');
        }
    }


    // 批量删除
    function audit(){
        var ids=layList.getCheckData().getIds('id');

        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }
            $eb.createModalFrame('审核反馈',"{:Url('audit_fail')}?ids="+str);
            console.log(ids)
        }else{
            layList.msg('请选择要批量拒绝的帖子');
        }
    }
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
                    channel_id:'',
                    data:'',
                    post_title:'',
                    post_author:'',
                    post_long:'',
                    recommend_uid:'',
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
                    $('[data-type="data"]').children(":first").click();
                    $('.layui-this').removeClass('layui-this');
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
                layList.form.on("select(post_long)", function (data) {
                    that.where.post_long = data.value;
                });
                layList.form.on("select(recommend_uid)", function (data) {
                    that.where.recommend_uid = data.value;
                });
                layList.form.on("select(is_recommend)", function (data) {
                    that.where.is_recommend = data.value;
                });
                // 定义查看视频点击按钮事件
                $('body').on("click", '.video-btn', function(e) {
                    var url = e.target.attr('data-url');
                    $('#video-box').css('display', 'block');
                    $('#video').attr('src', url);
                })
            }
        })
    });
</script>
{/block}
