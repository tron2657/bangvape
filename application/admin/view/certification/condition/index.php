{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-title">
                <div class="ibox-tools">

                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="m-b m-l">
                        <form action="" class="form-inline">

                            <select name="status" aria-controls="editable" class="form-control input-sm">
                                <option value="">状态</option>
                                <option value="1" {eq name="params.status" value="1"}selected="selected"{/eq}>开启</option>
                                <option value="0" {eq name="params.status" value="0"}selected="selected"{/eq}>关闭</option>
                            </select>
                           
                        <div class="input-group">
                            <input type="text" name="keyword" value="{$params.keyword}" placeholder="请输入关键词/标识/条件名称" class="input-sm form-control"> <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search" ></i>搜索</button> </span>
                        </div>
                        </form>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>

                            <th class="text-center">ID</th>
                            <th class="text-center">标识</th>
                            <th class="text-center">条件名称</th>
                            <th class="text-center">状态</th>
                            <th class="text-center">排序</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.name}
                            </td>
                            <td class="text-center">
                                {$vo.desc}
                            </td>
                            <td class="text-center">
                                <i class="fa {eq name='vo.status' value='1'}fa-check text-navy{else/}fa-close text-danger{/eq}"></i>
                            </td>
                            <td class="text-center">
                                {$vo.sort}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-xs" data-url="{:Url('status',array('id'=>$vo['id']))}" type="button">{eq name='vo.status' value='1'}<i class="fa fa-warning"></i> 禁用{else/}<i class="fa fa-success"></i> 启用{/eq}
                                </button>
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
        var _this = $(this),url =_this.data('url');
        $eb.axios.get(url).then(function(res){
            console.log(res);
            if(res.status == 200 && res.data.code == 200) {
                $eb.$swal('success',res.data.msg);
                window.location.reload();
            }else
                return Promise.reject(res.data.msg || '操作失败')
        }).catch(function(err){
            $eb.$swal('error',err);
        });
    });
</script>
{/block}
