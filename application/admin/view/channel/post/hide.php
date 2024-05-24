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
            </div>
            <!--产品列表-->
            <div class="layui-col-md12" style="margin-top: 15px;">
                <div class="layui-card">
                    <div class="layui-card-header">
                        {$channel_title}&nbsp;/&nbsp;屏蔽管理
                    </div>
                    <div class="layui-card-body">
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
                        <script type="text/html" id="hide_info">
                            <div>
                                屏蔽人：{{d.hide_user_nickname}}
                                <br/>
                                屏蔽时间：{{d.create_time_show}}
                            </div>
                        </script>
                        <script type="text/html" id="act">
                            <a class="layui-btn layui-btn-xs" lay-event='not_hide' href="javascript:void(0);" style="background-color: red;" >
                                <i class="fa fa-warning"></i> 取消屏蔽
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
    var status="{$status}";
    //实例化form
    layList.form.render();
    // layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    // layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    //加载列表
    layList.tableList('List',"{:Url('hide_list',['channel_id'=>$channel_id])}",function (){
        switch(parseInt(status)){
            case 1:
                break;
            case 2:
                break;
            case 10:
                var join = [
                    {field: 'id', title: 'ID', event:'id',width:'4%'},
                    {field: 'post_content', title: '内容信息',templet:'#post_content',width:'40%'},
                    {field: 'post_info', title: '屏蔽信息',templet:'#hide_info',width:'16%'},
                    {field: 'right', title: '操作',align:'left',toolbar:'#act'}
                ];
                break;
            default:
        }
        return join;
    });

    //多选事件绑定
    /*$('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });*/
    //查询
    /*layList.search('search',function(where){
        layList.reload(where,true);
    });*/
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
            case 'not_hide':
                var url=layList.U({c:'channel.post',a:'cancel_hide_post',q:{id:data.id}});
                var code = {title:"是否要取消屏蔽该内容",text:"取消屏蔽后可以再次屏蔽",confirm:'是的，我要取消屏蔽'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                            location.reload();
                        }else
                            return Promise.reject(res.data.msg || '取消屏蔽失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            default:
        }
    })
</script>
{/block}
