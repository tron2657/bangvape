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
                                <div class="layui">
                                    <div class="layui-input-inline">
                                        <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
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
                        备选池
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container" style="margin-bottom: 2px;margin-top: 10px">
                            <button class="layui-btn layui-btn-sm" onclick="delete_post()">批量删除</button>
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
                                        {{# if(d.post_data.show_video_edit==1){ }}
                                        <button style="color: #089bf1;
    border: 1px solid #089bf1;
    display: inline-block;
    padding: 3px 25px;
    font-size: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    background: white;">视频</button>
                                        {{# } }}
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
                                备选人：{{d.recommend_user_nickname}}
                                <br/>
                                备选时间：{{d.create_time_show}}
                                <br/>
                                推送时长：{{d.post_long_show}}
                                <br/>
                                排序权重：{{d.sort_num}}
                            </div>
                        </script>
                        <script type="text/html" id="act">
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
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('推送编辑','{:Url('edit_channel_post')}?id={{d.id}}',{w:600,h:700})">
                                <i class="fa fa-paste"></i> 推送编辑
                            </button>
                            <br/>
                            <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('立即推送','{:Url('reset_channel')}?post_id={{d.post_id}}&post_pool_id={{d.id}}',{w:600,h:300})">
                                <i class="fa fa-change"></i> 立即推送
                            </button>
                            <a class="layui-btn layui-btn-xs" lay-event='del' href="javascript:void(0);" style="background-color: red;" >
                                <i class="fa fa-warning"></i> 删除
                            </a>

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
    //实例化form
    layList.form.render();
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('post_list')}",function (){
        var join = [
            {type:'checkbox'},
            {field: 'id', title: 'ID', event:'id',width:'4%'},
            {field: 'post_content', title: '内容信息',templet:'#post_content',width:'40%'},
            {field: 'post_info', title: '推送信息',templet:'#post_info',width:'16%'},
            {field: 'right', title: '操作',align:'left',toolbar:'#act'}
        ];
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
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'del':
                var url=layList.U({c:'channel.post_pool',a:'delete_post',q:{id:data.id}});
                var code = {title:"是否要删除该内容",text:"是否从备选池中删除该内容？",confirm:'是的，我要删除'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                            location.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            default:
        }
    })
    // 批量删除
    function delete_post(){
        var ids=layList.getCheckData().getIds('id');

        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }

            var url=layList.U({c:'channel.post_pool',a:'delete_posts',q:{id:str}});
            var code = {title:"是否要批量删除内容",text:"是否从备选池中批量删除内容？",confirm:'是的，我要删除'};
            $eb.$swal('delete',function(){
                $eb.axios.get(url).then(function(res){
                    if(res.status == 200 && res.data.code == 200) {
                        $eb.$swal('success', res.data.msg);
                        location.reload();
                    }else
                        return Promise.reject(res.data.msg || '批量删除失败')
                }).catch(function(err){
                    $eb.$swal('error',err);
                });
            },code)
        }else{
            layList.msg('请选择要批量删除的内容');
        }
    }
</script>
{/block}
