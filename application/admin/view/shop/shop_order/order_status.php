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

                            <th class="text-center">订单编号</th>
                            <th class="text-center">操作记录</th>
                            <th class="text-center">操作时间</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.oid}
                            </td>
                            <td class="text-center">
                                {$vo.change_message}
                            </td>
                            <td class="text-center">
                                {$vo.change_time|date='Y-m-d H:i:s',###}
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
{/block}
