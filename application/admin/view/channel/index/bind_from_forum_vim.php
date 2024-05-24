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
        cursor:normal;
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
                <div class="content_all" style="height: 340px">
                    <div class="right">
                        <div>
                            <form class="layui-form" action="" style="padding:20px;">
                                <a class="bind-tips" name="input">通过搜索版块ID、名称快速选择版块</a>
                                <div class="user-lists">
                                    <div style="flex:1">
                                        <select name="forum_ids" id="bind_select" xm-select-skin="normal" xm-select-height="38px" xm-select="forum_select" xm-select-search="{:Url('find_forums')}">
                                            <option value="">请选择绑定版块</option>
                                        </select>
                                    </div>
                                    <!-- <br/> -->
                                    <button class="btn btn-primary layui-btn" id="save" style="margin-left:20px" type="button">
                                        <i class="fa  fa-arrow-circle-o-right"></i>
                                        选定
                                    </button>
                                </div>
                            </form>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    var formSelects = layui.formSelects;
    var channel_id="{$channel_id}";
    var add_storage_user=function () {
        var forum_names=JSON.parse(window.localStorage.getItem("bind_from_forum_names"));
        if(forum_names==undefined){
            $.ajax({
                url:"{:Url('get_already_from_forum')}",
                data:{channel_id:channel_id},
                type:'get',
                dataType:'json',
                success:function(res){
                    if(res.code == 200){
                        if(res.data){
                            for(var i=0;i<res.data.count;i++){
                                var selectHtml = '<option value='+res.data.forum[i].id+' selected>'+res.data.forum[i].name+'</option>';
                                $("#bind_select").append(selectHtml);
                            }
                            var form = layui.form;
                            form.render();
                            formSelects.config('forum_select', {
                                type: 'get',                //请求方式: post, get, put, delete...
                                searchName: 'keywords',      //自定义搜索内容的key值
                                clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
                            }, false);
                        }
                    }else{
                        Toast.error(res.msg);
                    }
                }
            });
        }else{
            var forum_ids=JSON.parse(window.localStorage.getItem("bind_from_forum_ids"));
            var selectHtml='';
            for(var i=0;i<100;i++){
                if(forum_names[i]==undefined) {
                    break;
                }
                selectHtml = '<option value='+forum_ids[i]+' selected>'+forum_names[i]+'</option>';
                $("#bind_select").append(selectHtml);
            }
            var form = layui.form;
            form.render();
            formSelects.config('forum_select', {
                type: 'get',                //请求方式: post, get, put, delete...
                searchName: 'keywords',      //自定义搜索内容的key值
                clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
            }, false);
        }
    };
    add_storage_user();

    $('#save').on('click',function(){
        var selectVal = formSelects.value('forum_select', 'val');
        var selectName = formSelects.value('forum_select', 'name');
        window.localStorage.removeItem("bind_from_forum_names");
        window.localStorage.removeItem("bind_from_forum_ids");
        window.localStorage.setItem("bind_from_forum_names",JSON.stringify(selectName));
        window.localStorage.setItem("bind_from_forum_ids",JSON.stringify(selectVal));
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    });
</script>
{/block}
