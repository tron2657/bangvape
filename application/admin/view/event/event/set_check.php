{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<style>
    .content_all{
        display: flex;
    }
    .gray-bg{
        background: #fff;
    }
    .wrapper{
        margin-top: 0;
        padding-top: 0;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .ibox-content{
        margin-top: 0;
        padding-top: 0;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .content_all .left{
        /* width: 80px; */
        border-right: 4px solid #ccc;
    }
    .content_all .right{
        flex:1;
    }
    .bind-tips{
        font-size:16px;
        border:none;
        color:#333;
        text-decoration: none;
    }
    .bind-tips:hover{
        color:#333;
    }
    .user-lists{
        display:flex;
        margin-top:20px;
    }
    .tab_show{
        padding:0 10px;
        line-height: 40px;
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
        cursor: pointer;
    }
    .active{
        background-color: #00CCFF;
        color: #fff;
    }
    .often-user{
        margin-top:20px;
    }
    .often-user-lists{
        display:flex;
        flex-wrap:wrap;
        flex-direction:row;
    }
    .often-user-lists .user-name{
        margin:10px;
        padding:10px;
        border:none;
        background-color:#fff;
        outline:none;
    }
    .often-user-lists .user-name:hover{
        color:#FFF;
        background-color:#1E9FFF
    }
</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-12" style="margin: 0;padding: 0;">
        <div class="ibox">
            <div class="ibox-content">
                <form class="layui-form" action="" style="padding:20px;width: 100%">
                    <a class="" name="input">设置活动核销员</a>
                    <div class="user-lists">
                        <div style="flex:1">
                            <select name="uids" id="bind_select" xm-select-skin="normal" xm-select-height="38px" xm-select="user_select" xm-select-search="{:Url('Admin/com.com_thread/find_users')}" xm-select-radio>
                                <option value="">请输入用户昵称搜索</option>
                            </select>
                        </div>
                        <!-- <br/> -->
                        <button class="btn btn-primary layui-btn" id="save" style="margin-left:20px" type="button">
                            <i class="fa  fa-arrow-circle-o-right"></i>
                            设置核销员
                        </button>
                    </div>
                    <div class="often-user">
                        <div>当前已经设置</div>
                        <div>
                            <table class="layui-hide" id="List" lay-filter="List"></table>
                            <script type="text/html" id="act_common">
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 删除
                                </a>
                            </script>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    var formSelects = layui.formSelects;
    formSelects.config('user_select', {
        type: 'get',                //请求方式: post, get, put, delete...
        searchName: 'nickname',      //自定义搜索内容的key值
        clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
    }, false);
    $.ajax({
        url:"{:Url('Admin/com.com_thread/get_user')}",
        data:{},
        type:'get',
        dataType:'json',
        success:function(res){
            if(res.code == 200){
             
 
                if(res.data){
                    if(res.data.uid)
                    {
                        var selectHtml = '<option value='+res.data.uid+' selected>'+res.data.nickname+'</option>';
                        $("#bind_select").append(selectHtml);
                    }

                    var form = layui.form;
                    form.render();
     
                }
            }else{
                Toast.error(res.msg);
            }
        }
    });
    $('#save').on('click',function(){
        var selectVal = formSelects.value('user_select', 'val')[0];
        $.ajax({
            url:"{:Url('bind_check')}",
            data:{
                uid:selectVal,
                id:'{$id}'
            },
            type:'post',
            dataType:'json',
            success:function(res){
                if(res.code == 200){
                    Toast.success(res.data);
                    layList.reload();
                }else{
                    Toast.error(res.msg);
                }
            }
        });
    });

    $('[data-role="user"]').click(function () {
        var is_vest=$(this).attr('data-value');
        $(this).addClass('active').siblings().removeClass('active');
        add_bind_log(is_vest);
    });
    add_bind_log(0);
    function add_bind_log(is_vest) {
        $.post("{:Url('get_bind_log')}",{is_vest:is_vest},function (res) {
            $('#bind_log').empty().append(res.data.html);
            choose();
        })
    }
    var choose=function(){
    $('[data-role="choose_uid"]').unbind('click');
    $('[data-role="choose_uid"]').click(function () {
        var formSelects = layui.formSelects;
        var uid=$(this).attr('data-uid');
        $.ajax({
            url:"{:Url('Admin/com.com_thread/get_user')}",
            data:{uid:uid},
            type:'get',
            dataType:'json',
            success:function(res){
                if(res.code == 200){
                    $('.xm-select-parent').remove();
                    var selectHtml = '<option value='+res.data.uid+' selected>'+res.data.nickname+'</option>';
                    if(res.data){
                        $("#bind_select").empty().append(selectHtml);
                        var form = layui.form;
                        form.render();
                        formSelects.config('user_select', {
                            type: 'get',                //请求方式: post, get, put, delete...
                            searchName: 'nickname',      //自定义搜索内容的key值
                            clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
                        }, false);
                    }
                }else{
                    Toast.error(res.msg);
                }
            }
        });
    });
};
</script>
<script>
    layList.tableList('List',"{:Url('get_check_list')}?event_id={$id}",function (){
        var join = [
            {field: 'id', title: 'ID', event:'id',width:'10%'},
            {field: 'uid', title: 'UID', width:'10%'},
            {field: 'nickname', title: '用户昵称',width:'30%'},
            {field: 'create_time', title: '设置时间',width:'30%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act_common',width:'20%'},
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
            case 'delstor':
                $.post("{:url('set_check_status')}",{id:data.id,field:'status', value:-1},function (res) {
                    if(res.code==200){
                        layList.reload();
                    }
                    Toast.success(res.data);
                });
//                var url=layList.U({c:'event.event',a:'set_check_status',q:{id:data.id, field:'status', value:-1}});
//                var code = {title:"是否要删除该核销员",text:"",confirm:'是的，我要删除'};
//                $eb.$swal('delete',function(){
//                    $eb.axios.get(url).then(function(res){
//                        if(res.status == 200 && res.data.code == 200) {
//                            $eb.$swal('success', '');
//                            obj.del();
//                        }else
//                            return Promise.reject(res.data.msg || '删除失败')
//                    }).catch(function(err){
//                        $eb.$swal('error',err);
//                    });
//                },code)
                break;
        }
    });
</script>
{/block}
