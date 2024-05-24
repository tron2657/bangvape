{extend name="public/container"}
{block name="content"}
<style>
    .layui-table-body {
        position: relative;
        overflow: auto;
        margin-right: -1px;
        margin-bottom: -1px;
    }
</style>
<div class="row" style="width: 100%;margin-left: 0;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="layui-card-header" style="border-bottom: 1px solid #eee;">注册登陆方式</div>
        <div class="layui-fluid">
            <div class="alert alert-info" style="margin-top: 10px;" role="alert">
                提示:至少开启一种登录方式
            </div>
            <table lay-filter="demo">
                <thead>
                <tr>
                    <th lay-data="{field:'username', width:'60%'}">注册登录方式</th>
                    <th lay-data="{field:'experience', width:'20%'}">状态</th>
                    <th lay-data="{field:'sign', width:'20%'}">操作</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data" id="v"}
                <tr>
                    {if condition="$v.type eq '1'"}
                    <td>手机号</td>
                    {elseif condition="$v.type eq '2'"/}
                    <td>邮箱</td>
                    {elseif condition="$v.type eq '3'"/}
                    <td>微信<span style="color: #999">（第三方）</span></td>
                    {/if}
                    <td>
                        {if condition="$v.status eq '1'"}
                        <input type='checkbox' name='id' lay-skin='switch' value="{$v.type}" lay-filter='is_verify' lay-text='' checked>
                        {else/}
                        <input type='checkbox' name='id' lay-skin='switch' value="{$v.type}" lay-filter='is_verify' lay-text=''>
                        {/if}
                    </td>
                    <td>
                        {if condition="$v.type eq '3'"}
                        <button class="layui-btn layui-btn-xs" onclick="$eb.createModalFrame('微信登录','{:Url('weixin_set')}',{h:300,w:600})">
                            编辑
                        </button>
                        {/if}
                    </td>
                </tr>
                {/volist}
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    layList.form.render();
    var table = layui.table;
    //转换静态表格
    table.init('demo', {
        height: 157, //设置高度
        limit: 3 //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
        //支持所有基础参数
    });
    layList.switch('is_verify',function (odj,value) {
        console.log(odj.elem)

        //layList.form.render();
        var status = 1;
        if(odj.elem.checked){
            status = 1
        }else {
            status = 0
        }
        $.ajax({
            url: "{:Url('edit')}",
            data: {
                type:value,
                status:status
            },
            type: 'post',
            dataType: 'json',
            success: function (res) {
                if(res.msg === "至少开启一种注册登录方式"){
                    $(odj.elem).prop('checked', true);
                    layList.form.render();
                    $eb.message('error', res.msg);
                }else {
                    $eb.message('success', res.msg);
                }
            }
        });
    });
</script>
{/block}
