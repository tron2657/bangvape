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
                                                <label class="layui-form-label" style="width: 110px;text-align: left">名称:</label>
                                                <div class="layui-input-inline">
                                                    <input type="text" name="name" v-model="where.name" lay-verify="name" autocomplete="off" placeholder="名称" class="layui-input" style="width: 173px;padding-left: 5px">
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">省</label>
                                                <div class="layui-input-block">

                                                    <select name="province" v-model="where.province" lay-filter="province">
                                                        <option value="">-请选择-</option>
                                                        {volist name="provinc_list" id="vo"}
                                                        <option value="{$vo.province}">{$vo.province}</option>
                                                        {/volist}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">市</label>
                                                <div class="layui-input-block">
                                                    <select name="city" v-model="where.city" lay-filter="city">
                                                            <option value="">-请选择-</option>
                                                            {volist name="city_list" id="vo"}
                                                            <option value="{$vo.city}">{$vo.city}</option>
                                                            {/volist}
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">区域</label>
                                                <div class="layui-input-block">
                                                    <select name="district" v-model="where.district" lay-filter="province">
                                                    <option value="">-请选择-</option>
                                                    {volist name="district_list" id="vo"}
                                                            <option value="{$vo.district}">{$vo.district}</option>
                                                            {/volist}
                                                    </select>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-input-block">
                                            <button lay-submit="search" lay-filter="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
                                                <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
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
        <div class="layui-col-md12" >
            <div class="layui-card">
                <div class="layui-card">
                    <div class="layui-card-header">活动列表</div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                            <div>
                                {if condition="in_array('event',$open_list)"}
                                <button type="button" class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}')" style="margin-top: 10px">创建门店</button>
                                {else/}
                                <button type="button" class="layui-btn layui-btn-sm" id="unable" style="margin-top: 10px">创建活动</button>
                                {/if}

                            </div>
                        </div>
                        <table class="layui-hide" id="List" lay-filter="List"></table>
                        <script type="text/html" id="is_close">
                            <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_close' lay-text='关闭|正常'  {{ d.is_close == 1 ? 'checked' : '' }}>
                        </script>
                        <script type="text/html" id="act_common">
                                <!-- <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}?id={{d.id}}')" >查看详情</button> -->
                                <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}?id={{d.id}}')" >编辑</button>
                            </ul>
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
    layList.form.render();
    layList.tableList('List',"{:Url('get_list')}",function (){        
            var join = [
                {type:'checkbox'},
                {field: 'id', title: 'ID', event:'id',width:'3%'},
                {field: 'name', title: '名称',width:'20%'},
                {field: 'province', title: '省',width:'5%'},
                {field: 'city', title: '市',width:'5%'},
                {field: 'district', title: '雨花区',width:'5%'},
                {field: 'address', title: '地址',width:'50%'},
                {field: 'is_close', title: '是否关闭',templet:'#is_close',width:'6%'},
                {field: 'right', title: '操作',align:'center',toolbar:'#act_common'},
            ];
        return join;
    });
    layList.switch('is_close',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'active.active_store_branch',a:'set_is_close',p:{is_close:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'active.active_store_branch',a:'set_is_close',p:{is_close:0, id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //自定义方法
    var action={
        // 批量删除
        delete:function(field,id,value){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'event.event',a:'delete'}),{ids:ids},function (res) {
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
            layList.baseGet(layList.Url({c:'event.event',a:'set_event_status',p:{status:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'event.event',a:'set_event_status',p:{status:0, id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });

    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'see':
                $eb.createModalFrame(data.nickname+'-会员详情',layList.Url({c:'user.user',a:'see',p:{uid:data.uid}}));
                break;
            case 'set_hot':
                var url=layList.U({c:'event.event',a:'set_recommend',q:{id:data.id, field:'is_recommend', value: data.is_recommend == 1?0:1}});
                if(data.is_recommend == 1){
                    var code = {title:"操作提示",text:"确定取消活动推荐操作吗？",type:'info',confirm:'是的，取消推荐该活动'};
                }else{
                    var code = {title:"操作提示",text:"确定将该活动设为推荐吗？",type:'info',confirm:'是的，设为推荐'};
                }
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success','');
                            layList.reload({},true,null,obj);
                        }else{
                            return Promise.reject(res.data.msg || '设置失败');
                        }
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
                break;
            case 'delete':
                var url=layList.U({c:'event.event',a:'set_event_status',q:{id:data.id, status:-1}});
                var code = {title:"操作提示",text:"确定要将活动删除吗？",type:'info',confirm:'是的，删除该活动'};
                var code2 = {title:"操作提示",text:"再次确认是否删除该活动？",type:'info',confirm:'确认,要删除'};
                $eb.$swal('delete',function(){
                    $eb.$swal('delete',function(){
                        $eb.axios.get(url).then(function(res){
                            if(res.status == 200 && res.data.code == 200) {
                                $eb.$swal('success','');
                                layList.reload({},true,null,obj);
                            }else{
                                return Promise.reject(res.data.data || '设置失败');
                            }
                        }).catch(function(err){
                            $eb.$swal('error',err);
                        });
                    },code2);
                },code);
                break;
            case 'open':
                layList.basePost(layList.Url({c:'event.event',a:'set_event_status'}),{id:data.id,'status':1},function (res) {
                    layList.msg(res.data);
                    layList.reload();
                });
                break;
        }
    })
    //下拉框
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
</script>
{/block}
