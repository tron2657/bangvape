{extend name="public/container"}
{block name="head_top"}
<script src="{__PLUG_PATH}city.js"></script>
<style>
    .layui-btn-xs{margin-left: 0px !important;}
    legend{
        width: auto;
        border: none;
        font-weight: 700 !important;
    }
    .site-demo-button{
        padding-bottom: 20px;
        padding-left: 10px;
    }
    .layui-form-label{
        width: auto;
    }
    .layui-input-block input{
        width: 50%;
        height: 34px;
    }
    .layui-form-item{
        margin-bottom: 0;
    }
    .layui-input-block .time-w{
        width: 200px;
    }
    .layui-table-body{overflow-x: hidden;}
    .layui-btn-group button i{
        line-height: 30px;
        margin-right: 3px;
        vertical-align: bottom;
    }
    .back-f8{
        background-color: #F8F8F8;
    }
    .layui-input-block button{
        border: 1px solid #e5e5e5;
    }
    .avatar{width: 50px;height: 50px;}
    .layui-table-cell {
        padding: 0 10px !important;
    }
    /*.laytable-cell-1-0-3 {*/
        /*width: 120px !important;*/
    /*}*/
    /*.laytable-cell-1-0-4 {*/
        /*width: 137px !important;*/
    /*}*/
    /*.laytable-cell-1-0-7 {*/
        /*width: 91px !important;*/
        /*text-align: center !important;*/
    /*}*/
    /*.laytable-cell-1-0-8 {*/
        /*width: 224px !important;*/
    /*}*/
    /*.laytable-cell-1-0-9 {*/
        /*width: 224px !important;*/
    /*}*/
    /*.laytable-cell-1-0-11 {*/
        /*width: 302px !important;*/
    /*}*/
    .anim-style {
        top: 33px  !important;
    }
    .layui-table-body {
        overflow: initial !important;
    }
</style>
{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <!--搜索条件-->
        <div class="layui-col-md12" style="margin-top: -20px">
            <div class="ibox-title">
                <h5>会员搜索</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="display: block;">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">用户搜索：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" lay-verify="nickname" style="width: 100%" autocomplete="off" placeholder="请输入昵称或uid" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">手机号：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="phone" lay-verify="nickname" style="width: 100%" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">邮箱：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="mail" lay-verify="mail" style="width: 100%" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
                            </div>
                        </div>
                        <!-- <div class="layui-inline">
                            <label class="layui-form-label">用户类型：</label>
                            <div class="layui-input-inline">
                                <select name="user_type" lay-verify="user_type">
                                    <option value="">全部</option>
                                    <option value="wechat">微信公众号</option>
                                    <option value="routine">微信小程序</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="layui-inline">
                            <label class="layui-form-label">状　　态：</label>
                            <div class="layui-input-inline">
                                <select name="status" lay-verify="status">
                                    <option value="">全部</option>
                                    <option value="1">正常</option>
                                    <option value="0">禁用</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">性　　别：</label>
                            <div class="layui-input-inline">
                                <select name="sex" lay-verify="sex">
                                    <option value="">全部</option>
                                    <option value="1">男</option>
                                    <option value="2">女</option>
                                    <option value="0">保密</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">是否VIP：</label>
                            <div class="layui-input-inline">
                                <select name="is_vip" lay-verify="is_vip">
                                    <option value="">全部</option>
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <!-- <div class="layui-inline">
                            <label class="layui-form-label">消费情况：</label>
                            <div class="layui-input-inline">
                                <select name="pay_count" lay-verify="pay_count">
                                    <option value="">全部</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2-5</option>
                                    <option value="3">5-10</option>
                                    <option value="4">10-20</option>
                                    <option value="5">20以上</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="layui-inline">
                            <label class="layui-form-label">发帖次数：</label>
                            <div class="layui-input-inline">
                                <select name="post_count" lay-verify="post_count">
                                    <option value="">全部</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2-5</option>
                                    <option value="3">5-10</option>
                                    <option value="4">10-20</option>
                                    <option value="5">20以上</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">注册时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input time-w" name="user_time" lay-verify="user_time"  id="user_time" placeholder=" - ">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="" lay-filter="search" >
                                <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索</button>
                        </label>
                        <label class="layui-form-label">
                            <button onclick="javascript:layList.reload();" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                        </label>
                    </div>
                </form>
            </div>
        </div>
        <!--end-->
        <!-- 中间详细信息-->
        <!--enb-->
    </div>
    <!--列表-->
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12">
            <div class="layui-card">
                    <div class="layui-btn-group conrelTable" style="margin-top: 15px;margin-left: 10;">
                        <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" data-type="set_grant"><i class="fa fa-check-circle-o"></i>发送优惠券</button>
                        <!-- <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" data-type="set_column_grant"><i class="fa fa-check-circle-o"></i>发送知识商城优惠券</button> -->
                        <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" data-type="change_score"><i class="fa fa-check-circle-o"></i>加减积分</button>
                        <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" onclick="injection()"><i class="fa fa-check-circle-o"></i>用户组</button>
                        <button class="layui-btn layui-btn-sm layui-btn-normal" type="button" data-type="refresh"><i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                    </div>
                    <div class="layui-card-body">
                    <table class="layui-hide" id="userList" lay-filter="userList">

                    </table>
                    <script type="text/html" id="nickname">
                        {{d.nickname}}
                 
                        {{#  if(d.isVip){ }}
                            <font style="color:red" title="{{d.overdue_time_show}} 到期" >(Vip)</font>
                            <br>                          
                        {{#  }  }}
                    </script>
                    <script type="text/html" id="user_type">
                        {{d.user_type}}
                    </script>
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='status' lay-skin='switch' value="{{d.uid}}" lay-filter='status' lay-text='正常|禁止'  {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                    <script type="text/html" id="avatar">
                        {{#  if(d.avatar==''){ }}
                        <p style="height: 80px;margin-top: 15px;margin-left: 15px"><img class="avatar" style=""  data-image="{{d.avatar}}" src="/public/system/images/avatar.png" "></p>
                        {{#  } else { }}
                        <p style="height: 80px;margin-top: 15px;margin-left: 15px"><img class="avatar" style=""  data-image="{{d.avatar}}" src="{{d.avatar}}" "></p>
                        {{#  } }}
                    </script>
                    <script type="text/html" id="barDemo">
                        <button type="button" class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</button>
                        <button type="button" class="layui-btn layui-btn-xs" lay-event="see"><i class="fa fa-paste"></i>详情</button>
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit anim-style" style="margin-left: 157px">
                            {{# if(d.is_recommend=='0'){ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='recommend'>推荐关注</a>
                            </li>
                            {{# }else if(d.is_recommend=='1'){ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='remove_recommend'>取消推荐关注</a>
                            </li>
                            {{#  } }}
							 <li>
                                <a href="javascript:void(0);" lay-event='clear_user'>一键清空</a>
                            </li>
                            <li>
                                {if condition="($is_free_ban AND $is_end_ban) AND in_array('osapi_forum_power',$open_list) AND in_array('user_power',$open_list)"}
                                <a onclick="$eb.createModalFrame('用户组管理','{:Url('see_group_uid')}?uid={{d.uid}}')">用户组管理</a>
                                {else/}
                                <a lay-event='unable'>用户组管理</a>
                                {/if}
                            </li>
                            <li>
                                {if condition="$is_free_ban AND $is_end_ban"}
                                <a onclick="$eb.createModalFrame('用户权限','{:Url('admin/group.group_power/edit_manage_power')}?uid={{d.uid}}&type=1')">查看用户权限</a>
                                {else/}
                                <a lay-event='unable'>查看用户权限</a>
                                {/if}
                            </li>
                            <li>
                                <a onclick="$eb.createModalFrame('选择频道(新增有效，去除需到频道管理员列表操作)','{:Url('admin/channel.admin/set_admin_channel')}?uid={{d.uid}}')">设为频道管理员</a>
                            </li>
                        </ul>
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end-->
</div>

<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/content.min.js?v=1.0.0"></script>
{/block}
{block name="script"}
<script>
    $('#province-div').hide();
    $('#city-div').hide();
    layList.select('country',function (odj,value,name) {
        var html = '';
        $.each(city,function (index,item) {
            html += '<option value="'+item.label+'">'+item.label+'</option>';
        })
        if(odj.value == 'domestic'){
            $('#province-div').show();
            $('#city-div').show();
            $('#province-top').siblings().remove();
            $('#province-top').after(html);
            $('#province').val('');
            layList.form.render('select');
        }else{
            $('#province-div').hide();
            $('#city-div').hide();
        }
        $('#province').val('');
        $('#city').val('');
    });
    layList.select('province',function (odj,value,name) {
        var html = '';
        $.each(city,function (index,item) {
            if(item.label == odj.value){
                $.each(item.children,function (indexe,iteme) {
                    html += '<option value="'+iteme.label+'">'+iteme.label+'</option>';
                })
                $('#city').val('');
                $('#city-top').siblings().remove();
                $('#city-top').after(html);
                layList.form.render('select');
            }
        })
    });
    layList.form.render();
    layList.tableList('userList',"{:Url('get_user_list',['uid'=>$uid])}",function () {
        return [
                {type:'checkbox'},
                {field: 'uid', title: '编号', width:'4%',event:'uid'},
                {field: 'avatar', title: '头像', event:'open_image', width: '6%', templet: '#avatar'},
                {field: 'nickname', title: '昵称',width:'10%', templet: '#nickname'},
            
                {field: 'phone', title: '手机号码', width:'6%'},
                {field: 'sex', title: '性别',width:'6%'},
                {field: 'wallet', title: '钱包',width:'10%'},
                {field: 'post_count', title: '发帖次数',align:'center',width:'6%'},
                {field: 'fans', title: '粉丝数',align:'center',width:'6%'},
                {field: 'add_time', title: '首次访问日期',align:'center',width:'12%'},
                {field: 'last_time', title: '最近访问日期',align:'center',width:'12%'},
                {field: 'status', title: '状态',templet:"#checkboxstatus",width:'6%'},
                {fixed: 'right', title: '操作', width: '15%', align: 'center', toolbar: '#barDemo'}
            ];
    });
    layList.date('last_time');
    layList.date('add_time');
    layList.date('user_time');
    layList.date('time');
    //监听并执行 uid 的排序
    layList.sort(function (obj) {
        var layEvent = obj.field;
        var type = obj.type;
        switch (layEvent){
            case 'uid':
                layList.reload({order: layList.order(type,'u.uid')},true,null,obj);
                break;
            case 'now_money':
                layList.reload({order: layList.order(type,'u.now_money')},true,null,obj);
                break;
            case 'integral':
                layList.reload({order: layList.order(type,'u.integral')},true,null,obj);
                break;
        }
    });
    // 用户组
    function injection(){
        var ids=layList.getCheckData().getIds('uid');
        if(ids.length){
            var str='';
            for(var i=0;i<ids.length;i++){
                str+=ids[i]+',';
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }

            $eb.createModalFrame('用户组设置',"{:Url('add_group_uid')}?ids="+str);
            console.log(ids)
        }else{
            layList.msg('请选择要设置用户组的用户');
        }
    }
    //监听并执行 uid 的排序
    layList.tool(function (event,data) {
        var layEvent = event;
        switch (layEvent){
            case 'edit':
                $eb.createModalFrame('编辑',layList.Url({a:'edit',p:{uid:data.uid}}));
                break;
            case 'see':
                $eb.createModalFrame(data.nickname+'-会员详情',layList.Url({a:'see',p:{uid:data.uid}}));
                break;
          case 'recommend':
            var code = {title:"操作提示",text:"确定要推荐该用户吗？ ",type:'info',confirm:'确定'};
            $eb.$swal('delete',function(){
              layList.basePost(layList.Url({c:'user.user_recommend',a:'recommend_user'}),{uid:data.uid},function (res) {
                layList.msg(res.msg);
                layList.reload();
              });
            },code);
            break;
          case 'remove_recommend':
            var code = {title:"操作提示",text:"确定要取消推荐吗？ ",type:'info',confirm:'确定'};
            $eb.$swal('delete',function(){
              layList.basePost(layList.Url({c:'user.user_recommend',a:'cancel_recommend'}),{uid:data.uid},function (res) {
                layList.msg(res.msg);
                layList.reload();
              });
            },code);
            break;
            case 'clear_user':
                var code = {title:"操作提示",text:"是否要清空该用户发布的所有内容,清空后无法还原 ",type:'info',confirm:'清空'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'user.user',a:'clear_user'}),{uid:data.uid},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
                break;
            case 'unable':
                var code = {title:"提示",text:"该功能未开通或已过期，如需开通，请联系客服！",type:'info',confirm:'联系客服',cancel:'取消',confirmBtnColor:'#0ca6f2'};
                $eb.$swal('delete',function(){
                    $eb.createModalFrame('联系客服','https://h5a.opensns.cn/auth/Index/tip_box.html',{h:600,w:700})
                }, code)
                break;
        }
    });
//    layList.sort('uid');
    //监听并执行 now_money 的排序
    // layList.sort('now_money');
    //监听 checkbox 的状态
    layList.switch('status',function (odj,value,name) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({a:'set_status',p:{status:1,uid:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({a:'set_status',p:{status:0,uid:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    layList.search('search',function(where){
        // if(where['user_time_type'] != '' && where['user_time'] == '') return layList.msg('请选择选择时间');
        // if(where['user_time_type'] == '' && where['user_time'] != '') return layList.msg('请选择访问情况');
        if(where['user_time'] != ''){
            where['user_time_type'] = 'add_time';
        }
        layList.reload(where,true);
    });
    //下拉框
    $(document).click(function (e) {
      $('.layui-nav-child').hide();
    })
    function dropdown(that) {
      var oEvent = arguments.callee.caller.arguments[0] || event;
      oEvent.stopPropagation();
      var offset = $(that).offset();
      var top = offset.top - $(window).scrollTop();
      var index = $(that).parents('tr').data('index');
      $('.layui-nav-child').each(function (key) {
        if (key != index) {
          $(this).hide();
        }
      })
      if ($(document).height() < top + $(that).next('ul').height()) {
        $(that).next('ul').css({
          'padding': 10,
          'top': -($(that).parents('td').height() / 2 + $(that).height() + $(that).next('ul').height() / 2),
          'min-width': 'inherit',
          'position': 'absolute'
        }).toggle();
      } else {
        $(that).next('ul').css({
          'padding': 10,
          'top': $(that).parents('td').height() / 2 + $(that).height(),
          'min-width': 'inherit',
          'position': 'absolute'
        }).toggle();
      }
    }
    var action={
        set_status_f:function () {
           var ids=layList.getCheckData().getIds('uid');
           if(ids.length){
               layList.basePost(layList.Url({a:'set_status',p:{is_echo:1,status:0}}),{uids:ids},function (res) {
                   layList.msg(res.msg);
                   layList.reload();
               });
           }else{
               layList.msg('请选择要封禁的会员');
           }
        },
        set_status_j:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                layList.basePost(layList.Url({a:'set_status',p:{is_echo:1,status:1}}),{uids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要解封的会员');
            }
        },
        set_grant:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送优惠券',layList.Url({c:'ump.store_coupon',a:'grant',p:{id:str}}),{'w':document.body.clientWidth});
            }else{
                layList.msg('请选择要发送优惠券的会员');
            }
        },
        set_column_grant:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送知识商城优惠券',layList.Url({c:'ump.column_coupon',a:'grant',p:{id:str}}),{'w':document.body.clientWidth});
            }else{
                layList.msg('请选择要发送优惠券的会员');
            }
        },
        change_score:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('加减积分',layList.Url({c:'user.user',a:'edit_score',p:{uids:str}}),{'w':800,'h':600});
            }else{
                layList.msg('请选择要加减积分的会员');
            }
        },
        set_template:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
            }else{
                layList.msg('请选择要发送模板消息的会员');
            }
        },
        set_info:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送站内信息',layList.Url({c:'user.user_notice',a:'notice',p:{id:str}}),{'w':document.body.clientWidth});
            }else{
                layList.msg('请选择要发送站内信息的会员');
            }
        },
        set_custom:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送客服图文消息',layList.Url({c:'wechat.wechat_news_category',a:'send_news',p:{id:str}}),{'w':document.body.clientWidth});
            }else{
                layList.msg('请选择要发送客服图文消息的会员');
            }
        },
        refresh:function () {
            layList.reload();
        }
    };
    $('.conrelTable').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function () {
            action[type] && action[type]();
        })
    })
    $(document).on('click',".open_image",function (e) {
        var image = $(this).data('image');
        $eb.openImage(image);
    })
</script>
{/block}
