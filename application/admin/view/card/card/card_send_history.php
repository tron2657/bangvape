{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>

                            <th class="text-center">赠送用户id</th>
                            <th class="text-center">接收用户id</th>
                            <th class="text-center">赠送语</th>
                            <th class="text-center">状态</th>
                            <th class="text-center">添加时间</th>
                            <th class="text-center">接收时间</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.send_uid}
                            </td>
                            <td class="text-center">
                                {$vo.recieve_uid}
                            </td>
                            <td class="text-center">
                               
                                {$vo.message}
                            </td>
                            <td class="text-center">
                                {if condition="$vo.status eq 0"}
                                    未领取
                                {elseif condition="$vo.status eq 1"}
                                    已领取
                                {elseif condition="$vo.status eq -1"}
                                    已撤销
                                {/if}
                            </td>
                            <td class="text-center">
                                {$vo.add_time|date='Y-m-d H:i:s',###}
                            </td>
                            <td class="text-center">
                                {if condition="$vo.recieve_time eq null"}
                                    无
                                {else}
                                    {$vo.recieve_time|date='Y-m-d H:i:s',###}
                                {/if}
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
                {include file="public/inner_page"}
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $('.btn-warning').on('click',function(){
        window.t = $(this);
        var _this = $(this),url =_this.data('url');
        $eb.$swal('delete',function(){
            $eb.axios.get(url).then(function(res){
                console.log(res);
                if(res.status == 200 && res.data.code == 200) {
                    $eb.$swal('success',res.data.msg);
//                        _this.parents('tr').remove();
                }else
                    return Promise.reject(res.data.msg || '修改失败')
            }).catch(function(err){
                $eb.$swal('error',err);
            });
        },{'title':'您确定要修改优惠券的状态吗？','text':'修改后将无法恢复并且已发出的优惠券将失效,请谨慎操作！','confirm':'是的，我要修改'})
    });
    $('.btn-danger').on('click',function(){
        window.t = $(this);
        var _this = $(this),url =_this.data('url');
        $eb.$swal('delete',function(){
            $eb.axios.get(url).then(function(res){
                console.log(res);
                if(res.status == 200 && res.data.code == 200) {
                    $eb.$swal('success',res.data.msg);
                        _this.parents('tr').remove();
                }else
                    return Promise.reject(res.data.msg || '删除失败')
            }).catch(function(err){
                $eb.$swal('error',err);
            });
        },{'title':'您确定要删除优惠券吗？','text':'删除后将无法恢复,请谨慎操作！','confirm':'是的，我要删除'})
    });
    $(".open_image").on('click',function (e) {
        var image = $(this).data('image');
        $eb.openImage(image);
    })
</script>
{/block}
