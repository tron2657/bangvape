

{extend name="public/container"}
{block name="head_top"}

{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">用户uid</label>
                                <div class="layui-input-block">
                                    <input type="text" name="nickname" lay-verify="nickname" class="layui-input" placeholder="请输入用户uid">
                                </div>
                            </div>
                            
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" id="search" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
            <div class="layui-btn-group conrelTable" style="margin-top: 15px;margin-left: 10;">
                        <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" data-type="exec_plan"><i class="fa fa-check-circle-o"></i>执行计划</button>
                         
                    </div>
                <!-- <div class="layui-card-header">优惠券赠送计划</div> -->
                <div class="layui-card-body">
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                  
        
                    <script type="text/html" id="is_fail">
                    <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_fail' lay-text='有效|无效' {{ d.is_fail == 0 ? 'checked' : '' }}>
                        <!-- {{# if(d.is_fail==0){ }}
                        <button class="layui-btn layui-btn-xs">有效</button>
                        {{# }else{ }}
                        <button class="layui-btn layui-btn-xs">失效</button>
                        {{# } }} -->
                    </script>
                    <script type="text/html" id="attach">
                     
                      {{# if(d.souce_type==0 && d.attach && d.attach.order_id && d.attach.member_id){ }}
                               订单编号: {{d.attach.order_id}}<br>
                               续费类型: {{d.attach.member_text}}<br>
                        {{# } }}
            
                        {{# if(d.souce_type==1 && d.attach  && d.attach.event_text){ }}
                               活动: <br>{{d.attach.event_text}}/{{d.attach.event_id}}
                 
                        {{# } }}
                    
                    </script>
<!--                    <script type="text/html" id="act">-->
<!--                        <button type="button" class="layui-btn layui-btn-xs" lay-event="delete"><i class="layui-icon layui-icon-edit"></i>删除</button>-->
<!--                    </script>-->
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
    layList.tableList({o:'List'},layList.U({a:'list'}),function (){
        return [
            {field: 'id', title: '编号', sort: true,event:'id',align: 'center'},
            {field: 'nickname', title: '昵称/UID',align: 'center'},
            {field: 'plan_grant_time', title: '计划发放时间',align: 'center'},
            // {field: 'grant_time', title: '实际发放时间',align: 'center'},
            {field: 'status', title: '发放状态',align: 'left'},
            {field: 'coupon', title: '优惠券',align: 'left'},
            // {field: 'coupon_price', title: '优惠券面值',align: 'center'},
            // {field: 'use_min_price', title: '优惠券最低消费',align: 'center'},
            // {field: 'coupon_time', title: '优惠券有效期限',align: 'center'},
            {field: 'souce_type_text', title: '来源',align: 'center'},
            {field: 'is_fail', title: '是否失效',align: 'center',templet:'#is_fail'},
            {field: 'attach', title: '描述',align: 'center',templet:'#attach'},
        ];
    });
 
    layList.switch('is_fail', function(odj, value) {
   
        if (odj.elem.checked == true) {
            layList.baseGet(layList.Url({
                c: 'user.member_coupon_plan',
                a: 'set_fail',
                p: {
                    value: 0, 
                    id: value
                }
            }), function(res) {
                layList.msg(res.msg);
            });
        } else {
            layList.baseGet(layList.Url({
                c: 'user.member_coupon_plan',
                a: 'set_fail',
                p: {
                    value: 1,
                    id: value
                }
            }), function(res) {
                layList.msg(res.msg);
            });
        }
    });

    var action={
        exec_plan:function(){
            var code = {
                        title: "操作提示",
                        text: "确定强制执行吗？",
                        type: 'info',
                        confirm: '是的，强制执行'
                    };

            var url=layList.U({a:'grant_coupon'});
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            $('#search').click();
                            // layList.reload(where,true);
                        }else
                            return Promise.reject(res.data.msg || '执行失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code);
        }
    }
    $('.conrelTable').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function () {
            action[type] && action[type]();
        })
    })
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    layList.tool(function (layEvent,data,obj) {
        switch (layEvent){
            case 'delete':
                var url=layList.U({a:'delete',q:{id:data.id}});
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
            
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                });
                break;
        }
    });
</script>
{/block}
