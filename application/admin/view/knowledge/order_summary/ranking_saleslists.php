{extend name="public/container"}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body" style="margin-top:10px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">商品名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">时间范围</label>
                                <div class="layui-input-inline" style="width: 200px;">
                                    <input type="text" name="start_time" placeholder="开始时间" id="start_time" class="layui-input">
                                </div>
                                <div class="layui-form-mid">-</div>
                                <div class="layui-input-inline" style="width: 200px;">
                                    <input type="text" name="end_time" placeholder="结束时间" id="end_time" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">产品类型</label>
                                <div class="layui-input-block">
                                    <select name="is_column">
                                        <option value=" ">全部</option>
                                        <option value="1">专栏</option>
                                        <option value="0">单品</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                    <button class="layui-btn layui-btn-primary layui-btn-sm export"  lay-submit="export" lay-filter="export">
                                        <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">资金监控日志</div>
                <div class="layui-card-body">
                    <table class="layui-hide" id="userList" lay-filter="userList"></table>
                    <script type="text/html" id="number">
                        <p><span>{{d.sum_price}}</span></p>
                    </script>
                    <script type="text/html" id="bar">
                        {if condition="$is_free eq 0"}
                        <button type="button" class="layui-btn layui-btn-xs" lay-event="see"><i class="layui-icon layui-icon-list"></i>详情</button>
                        {/if}
                        {if condition="$is_free eq 1"}
                        <button type="button" class="layui-btn layui-btn-xs" lay-event="see-free"><i class="layui-icon layui-icon-list"></i>订阅记录</button>
                        {/if}
                    </script>
                    <script type="text/html" id="type">
                        {{# if(d.is_column == 1){ }}
                        <p>专栏</p>
                        {{# } }}
                        {{# if(d.is_column == 0){ }}
                        <p>单品</p>
                        {{# } }}
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    layList.form.render();
    layList.date({elem:'#start_time',theme:'#393D49',type:'datetime'});
    layList.date({elem:'#end_time',theme:'#393D49',type:'datetime'});
    var is_free = '<?= $is_free?>';
    layList.tableList('userList',"{:Url('getSaleslists',['is_free'=>$is_free])}",function () {
        switch (parseInt(is_free)) {
            case 0:
                var join = [
                    {field: 'id', title: '商品编号', sort: true,event:'id'},
                    {field: 'image', title: '商品图片',templet:'<p><img class="avatar " style="cursor: pointer" data-image="{{d.image}}" src="{{d.image}}" alt="{{d.name}}"></p>' },
                    {field: 'is_', title: '产品类型',templet:'#type'},
                    {field: 'name', title: '商品名称' },
                    {field: 'price', title: '商品售价' },
                    {field: 'number', title: '销售额',sort:true,templet:'#number'},
                    {field: 'num_product', title: '销量'},
                    {fixed: 'right', title: '操作',templet:'#bar'},
                ];
                break;
            case 1:
                var join = [
                    {field: 'id', title: '商品编号', sort: true,event:'id'},
                    {field: 'image', title: '商品图片',templet:'<p><img class="avatar " style="cursor: pointer" data-image="{{d.image}}" src="{{d.image}}" alt="{{d.name}}"></p>' },
                    {field: 'is_', title: '产品类型',templet:'#type'},
                    {field: 'name', title: '商品名称' },
                    {field: 'num_product', title: '订阅量'},
                    {fixed: 'right', title: '操作',templet:'#bar'},
                ];
                break;
        }
        return join;
    });
    layList.search('search',function(where){
        if(where.start_time!=''){
            if(where.end_time==''){
                layList.msg('请选择结束时间');
                return;
            }
        }
        if(where.end_time!=''){
            if(where.start_time==''){
                layList.msg('请选择开始时间');
                return;
            }
        }
        layList.reload(where,true);
    });
    layList.search('export',function(where){
        location.href=layList.U({a:'save_product_export',q:{start_time:where.start_time,end_time:where.end_time,title:where.title}});
    });
    layList.tool(function (event,data){
        switch (event){
            case 'see':
                layList.createModalFrame(data.name+'-详情',layList.Url({a:'product_info',p:{id:data.id,is_free:0}}),{w:768,h:800});
                break;
            case 'see-free':
                layList.createModalFrame(data.name+'-商品订阅记录',layList.Url({a:'product_info',p:{id:data.id,is_free:1}}),{w:768,h:800});
                break;
        }
    });
</script>
{/block}