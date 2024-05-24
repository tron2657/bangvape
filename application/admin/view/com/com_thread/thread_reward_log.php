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

                            <th class="text-center">操作人</th>
                            <th class="text-center">来源</th>
                            <th class="text-center">奖励说明</th>
                            <th class="text-center">奖励类型</th>
                            <th class="text-center">具体内容</th>
                            <th class="text-center">奖励时间</th>
                            <th class="text-center">奖励渠道</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.do_nickname}
                            </td>
                            <td class="text-center">
                                {$vo.from}
                            </td>
                            <td class="text-center">
                                {$vo.explain}
                            </td>
                            <td class="text-center">
                                {$vo.type}
                            </td>
                            <td class="text-center">
                            {if condition="$vo.exp > 0"}
                                {$vo.exp_name}:{$vo.exp}
                            {/if}
                            {if condition="$vo.fly > 0"}
                                {$vo.fly_name}:{$vo.fly}
                            {/if}
                            {if condition="$vo.buy > 0"}
                                {$vo.buy_name}:{$vo.buy}
                            {/if}
                            {if condition="$vo.gong > 0"}
                                {$vo.gong_name}:{$vo.gong}
                            {/if}
                            {if condition="$vo.exp > 0"}
                                {$vo.exp_name}:{$vo.exp}
                            {/if}
                            {if condition="$vo.exp > 0"}
                                {$vo.exp_name}:{$vo.exp}
                            {/if}
                            </td>
                            <td class="text-center">
                                {$vo.create_time}
                            </td>
                            <td class="text-center">
                                {$vo.model}
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
                
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
