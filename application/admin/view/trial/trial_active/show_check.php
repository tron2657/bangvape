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
                                                <label class="layui-form-label" style="width: 110px;text-align: left">报名人:</label>
                                                <div class="layui-input-inline">
                                                    <input type="text" name="title" v-model="where.user" lay-verify="user" autocomplete="off" placeholder="填写报名人" class="layui-input" style="width: 173px;padding-left: 5px">
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 110px;">中签状态</label>
                                                <div class="layui-input-block">
                                                    <select name="enroll" v-model="where.enroll" lay-filter="enroll">
                                                        <option value="">全部</option>
                                                        <option value="2">已中签</option>
                                                        <option value="-1">未中签</option>
                                                    </select>
                                                </div>
                                            </div>
                                    
                                        </div>
                                    </div>
                                    <div class="layui-col-lg12">
                                        <div class="layui-input-block">
                                            <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-search"></i>搜索</button>
                                            <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm" style="margin-top: 0px">
                                                <i class="layui-icon layui-icon-refresh"></i>重置</button>
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
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card">
                    <div class="layui-card-header">申请记录</div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container" style="display:flex;justify-content: space-between;align-items:center;margin-top: 10px">
                            <div>
                                <button type="button" class="layui-btn layui-btn-sm" onclick="location.href='{:url('index')}'" style="margin-top: 10px">返回活动管理</button>
                                <button lay-submit="export" lay-filter="export" class="layui-btn layui-btn-sm" style="margin-top: 10px">导出申请表</button>
                            </div>
                        </div>
                        <table class="layui-hide" id="List" lay-filter="List"></table>

                        <script type="text/html" id="status">
                        {{# if(d.status==2){ }}
                          <span style="color:green">申请成功</span>  
                        {{# } }}

                        {{# if(d.status==1){ }}
                            申请中
                        {{# } }}

                        {{# if(d.status==-1){ }}
                            未通过
                        {{# } }}

                        </script>

                        <script type="text/html" id="finish_status">
                        {{# if(d.finish_status==1){ }}
                            已完成
                        {{# } else }}
                        {{# if(d.finish_status==0){ }}
                        未完成
                        {{# } }}

                        </script>
                        <script type="text/html" id="draw_status">
                        {{# if(d.draw_status==1){ }}

                            已领取
                        {{# } else }}
                        {{# if(d.draw_status==0){ }}
                             <span style="color:red">未领取</span>  
                        {{# } }}

                        </script>
                        <script type="text/html" id="order_id">
                        {{# if(d.order_id && d.order_id!='') { }}
                            <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('/admin/order.store_order/order_info')}?oid={{d.order_oid}}')">{{d.order_id}}</button>
                        {{# } }}
                        </script>
  
                        <script type="text/html" id="user">
                            <a style="color:blue" href='javascript:void(0)' onclick="$eb.createModalFrame(this.innerText,'{:Url('/admin/user.user/see')}?uid={{d.uid}}')">{{d.user}}</a>
                        </script>
                        <script type="text/html" id="act_common">
                        <button type="button" class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame(this.innerText,'{:Url('show_user')}?id={{d.id}}')" >查看详情</button>
                        {{# if(d.status==1){ }}
 
                            <button type="button" class="layui-btn layui-btn-xs btn-success"  lay-event="apply_success">
                                通过申请
                            </button>
                            {{# } }}
                            <!-- {:url('show_check')}?id={{d.id}} -->
                                
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
    layList.tableList('List', "{:Url('get_show_check_list')}?id={$id}", function() {
        var join = [{
                type: 'checkbox'
            },
            {
                field: 'id',
                title: 'ID',
                event: 'id',
                width: '5%'
            },
            {
                field: 'user',
                title: '报名人',
                width: '7%',
                toolbar:'#user'
            },
            {
                field: 'create_time',
                title: '申请时间',
                width: '10%'
            },
            {
                field: 'status',
                title: '申请状态',
                width: '7%',
                toolbar: '#status'
            },
            {
                field: 'check_user',
                title: '审核人',
                width: '5%',
 
            },
            {
                field: 'check_time',
                title: '审核时间',
                width: '10%',
            
            },
            {
                field: 'draw_status',
                title: '领取状态',
                width: '7%',
                toolbar: '#draw_status'
            },
            {
                field: 'draw_time',
                title: '领取时间',
                width: '10%',
                
            },
            {
                field: 'finish_status',
                title: '完成状态',
                width: '6%',
                toolbar: '#finish_status'
            },
            {
                field: 'finish_time',
                title: '完成时间',
                width: '10%'
   
            },
            {
                field: 'order_id',
                title: '订单号',
                width: '15%',
                toolbar: '#order_id'
            },
            // {field: 'finish_time', title: '完成时间',width:'20%'},
            {
                field: 'right',
                title: '操作',
                align: 'center',
                toolbar: '#act_common'
            },
        ];
        return join;
    });
    //自定义方法
    var action = {
        //申请通过
        apply_success: function() {

        },



    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function() {
        var type = $(this).data('type');
        $(this).on('click', function() {
            action[type] && action[type]();
        })
    });


    layList.search('export', function(where) {
        location.href = layList.U({
            c: 'trial.trial_active',
            a: 'save_excel',
            q: {
                enroll: where.enroll,
                user: where.user,
                check_user: where.check_user,
                event_id: '{$id}'
            }
        });
    });
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
    layList.tool(function(event, data, obj) {
        switch (event) {
            case 'apply_success':
                if (data.status == 1) {
                    var code = {
                        title: "操作提示",
                        text: "确定通过申请吗？",
                        type: 'info',
                        confirm: '是的，通过该申请'
                    };
                  console.log(data);
                    $eb.$swal('delete', function() {
                        var url = layList.U({
                                c: 'trial.trial_active',
                                a: 'set_enroller_state',
                                q: {id: data.id,status:data.status}
                            });
                  
                 
                        $eb.axios.get(url).then(function(res) {
                            if (res.status == 200 && res.data.code == 200) {
                          
                                $eb.$swal('success', res.data.data);
                                layList.reload({}, true, null, obj);
                            } else {
                                return Promise.reject(res.data.msg || '设置失败');
                            }
                        }).catch(function(err) {
                            $eb.$swal('error', err);
                        });
                    }, code);
                }
                break;        
        }
    })
 
    
    require(['vue'], function(Vue) {
        new Vue({
            el: "#app",
            data: {
                badge: [],
                dataList: [{
                        name: '全部',
                        value: ''
                    },
                    {
                        name: '昨天',
                        value: 'yesterday'
                    },
                    {
                        name: '今天',
                        value: 'today'
                    },
                    {
                        name: '本周',
                        value: 'week'
                    },
                    {
                        name: '本月',
                        value: 'month'
                    },
                    {
                        name: '本季度',
                        value: 'quarter'
                    },
                    {
                        name: '本年',
                        value: 'year'
                    },
                ],
                where: {
                    data: '',
                    enroll: '',
                    user: '',
                    check_user: '',
                },
                showtime: false,
            },
            watch: {

            },
            methods: {
                setData: function(item) {
                    var that = this;
                    if (item.is_zd == true) {
                        that.showtime = true;
                        this.where.data = this.$refs.date_time.innerText;
                    } else {
                        this.showtime = false;
                        this.where.data = item.value;
                    }
                },
                search: function() {
                    layList.reload(this.where, true);
                },
                refresh: function() {
                    layList.reload();
                }
            },
            mounted: function() {
                var that = this;
                layList.laydate.render({
                    elem: this.$refs.date_time,
                    trigger: 'click',
                    eventElem: this.$refs.time,
                    range: true,
                    change: function(value) {
                        that.where.data = value;
                    }
                });
                layList.form.render();
                layList.form.on("select(enroll)", function(data) {
                    that.where.enroll = data.value;
                });
                layList.form.on("select(check_user)", function(data) {
                    that.where.check_user = data.value;
                });
            }
        })
    });

    function dropdown(that) {
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top = offset.top - $(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function(key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if ($(document).height() < top + $(that).next('ul').height()) {
            $(that).next('ul').css({
                'padding': 10,
                'top': -($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height() / 2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        } else {
            $(that).next('ul').css({
                'padding': 10,
                'top': $(that).parent('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
</script>
{/block}