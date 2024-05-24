{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff;margin-top: -10px;">
    <div class="layui-tab layui-tab-brief" lay-filter="tab">
        <ul class="layui-tab-title">
            <li lay-id="list" {eq name='type_tab' value='1'}class="layui-this" {/eq} >
                <a href="{:Url('index',['is_column'=>$is_column,'type'=>$type,'is_show'=>1,'status'=>1,'type_tab'=>1])}">出售中产品({$onsale})</a>
            </li>
            <li lay-id="list" {eq name='type_tab' value='2'}class="layui-this" {/eq}>
                <a href="{:Url('index',['is_column'=>$is_column,'type'=>$type,'is_show'=>0,'status'=>1,'type_tab'=>2])}">待上架产品({$forsale})</a>
            </li>
            <li lay-id="list" {eq name='type_tab' value='3'}class="layui-this" {/eq}>
                <a href="{:Url('index',['is_column'=>$is_column,'type'=>$type,'status'=>1,'type_tab'=>3])}">仓库中产品({$warehouse})</a>
            </li>
            <li lay-id="list" {eq name='type_tab' value='4'}class="layui-this" {/eq}>
                <a href="{:Url('index',['is_column'=>$is_column,'type'=>$type,'status'=>-1,'type_tab'=>4])}">产品回收站({$recycle})</a>
            </li>
        </ul>
    </div>
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">产品名称</label>
                                <div class="layui-input-block">
                                    <input type="text" style="width:250px" name="store_name" class="layui-input" placeholder="请输入商品名称">
                                    <input type="hidden" name="type" value="{$type}">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">所有分类</label>
                                <div class="layui-input-block">
                                    <select name="cate_id">
                                        <option value=" ">全部</option>
                                        {volist name='cate' id='vo'}
                                        <option value="{$vo.id}">{$vo.html}{$vo.cate_name}</option>
                                        {/volist}
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">售卖类型</label>
                                <div class="layui-input-block">
                                    <select name="is_free">
                                        <option value=" ">全部</option>
                                        <option value="1">免费</option>
                                        <option value="0">付费</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">作者昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" style="width:250px" name="author_name" class="layui-input" placeholder="请输入作者昵称">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                   <!-- <button class="layui-btn layui-btn-primary layui-btn-sm export"  lay-submit="export" lay-filter="export">
                                        <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>-->
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
                <div class="layui-card-body">
                    <div class="alert alert-info" role="alert">
                        列表[产品价格],[虚拟销量],[库存]可进行快速修改,双击或者单击进入编辑模式,失去焦点可进行自动保存
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="layui-btn-container">
                        {if condition="$type eq ''"}
                            {if condition="$is_free_ban AND $is_end_ban"}
                            <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{h:700,w:650})">新建专栏</button>
                            {else/}
                            <button class="layui-btn layui-btn-sm" data-type="unable">新建专栏</button>
                            {/if}
                        {/if}
                        {if condition="$type eq 1"}
                            {if condition="$is_free_ban AND $is_end_ban"}
                            <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create_text')}',{h:700,w:650})">新建单品</button>
                            {else/}
                            <button class="layui-btn layui-btn-sm" data-type="unable">新建单品</button>
                            {/if}
                        {/if}
                        {if condition="$type eq 2"}
                            {if condition="$is_free_ban AND $is_end_ban"}
                            <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create_audio')}',{h:700,w:650})">新建单品</button>
                            {else/}
                            <button class="layui-btn layui-btn-sm" data-type="unable">新建单品</button>
                            {/if}
                        {/if}
                        {if condition="$type eq 3"}
                            {if condition="$is_free_ban AND $is_end_ban"}
                            <button class="layui-btn layui-btn-sm" onclick="$eb.createModalFrame(this.innerText,'{:Url('create_video')}',{h:700,w:650})">新建单品</button>
                            {else/}
                            <button class="layui-btn layui-btn-sm" data-type="unable">新建单品</button>
                            {/if}
                        {/if}
                        <!--<button class="layui-btn layui-btn-sm" data-type="show">批量上架</button>-->
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--图片-->
                    <script type="text/html" id="image">
                        <img style="cursor: pointer" lay-event="open_image" src="{{d.image}}">
                    </script>
                    <!--上架|下架-->
                    <script type="text/html" id="checkboxstatus">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='上架|下架'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script>
                    <!--收藏-->
                    <script type="text/html" id="like">
                        <span><i class="layui-icon layui-icon-praise"></i> {{d.like}}</span>
                    </script>
                    <!--产品名称-->
                    <script type="text/html" id="store_name">
                        <h4>{{d.name}}</h4>
                        <p>价格:<font color="red">{{d.price}}</font>剥比:<font color="red">{{d.strip_num}}</font> </p>
                        {{# if(d.cate_name!=''){ }}
                        <p>分类:{{d.cate_name}}</p>
                        {{# } }}
                        <p>浏览量:{{d.read_count}}</p>
                    </script>
                    <!--操作-->
                    <script type="text/html" id="act">
                        {if condition="$type eq ''"}
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-编辑','{:Url('edit')}?id={{d.id}}',{h:700,w:650})">
                            编辑
                        </button>
                        {/if}
                        {if condition="$type eq 1"}
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-编辑','{:Url('edit_text')}?id={{d.id}}',{h:700,w:650})">
                            编辑
                        </button>
                        {/if}
                        {if condition="$type eq 2"}
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-编辑','{:Url('edit_audio')}?id={{d.id}}',{h:700,w:650})">
                            编辑
                        </button>
                        {/if}
                        {if condition="$type eq 3"}
                        <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" onclick="$eb.createModalFrame('{{d.name}}-编辑','{:Url('edit_video')}?id={{d.id}}',{h:700,w:650})">
                            编辑
                        </button>
                        {/if}
                        {if condition="$type eq 0"}
                        <a  class="layui-btn layui-btn-xs" href="{:Url('content')}?pid={{d.id}}">
                            内容管理
                        </a>
                        {/if}
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
	                          {{# if(d.recommend_sell === 0){ }}
	                          <li>
		                          <a href="javascript:void(0);" lay-event='recommend_column'>
			                          <i class="nfa nfa-nfx"></i> 推荐分销
		                          </a>
	                          </li>
	                          {{# }else{ }}
	                          <li>
		                          <a href="javascript:void(0);" lay-event='cancel_recommend_column'>
			                          <i class="nfa nfa-nfx"></i> 取消推荐分销
		                          </a>
	                          </li>
	                          {{# } }}
                            <li>
                                <a href="{:Url('column.columnProductReply/index')}?product_id={{d.id}}">
                                    <i class="nfa nfa-npl"></i> 评论查看
                                </a>
                            </li>
                            {{# if(d.status == 1){ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='delstor'>
                                    <i class="fa fa-trash"></i> 移到回收站
                                </a>
                            </li>
                            {{# } }}
                            {{# if(d.status == -1){ }}
                            <li>
                                <a href="javascript:void(0);" lay-event='restore'>
                                    <i class="fa fa-trash"></i> 还原
                                </a>
                            </li>
                            {{# } }}
                        </ul>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('column_list',['is_column'=>$is_column,'type'=>$type,'is_show'=>$is_show,'status'=>$status])}",function (){
        return join=[
            {field: 'id', title: 'ID', sort: true,event:'id',width:'6%'},
            {field: 'img', title: '图片',templet:'#image',width:'10%'},
            {field: 'store_name', title: '信息',templet:'#store_name'},
            {field: 'is_free', title: '类型',width:'6%'},
            {field: 'nickname', title: '作者',width:'6%'},
            {field: 'ficti_sales', title: '虚拟销量',edit:'ficti',width:'8%'},
            {field: 'sales', title: '销量',sort: true,event:'sales',width:'8%'},
            {field: 'sort', title: '排序',edit:'sort',width:'6%'},
            {field: 'collect', title: '收藏',width:'6%'},
            {field: 'is_show', title: '上架状态',templet:"#checkboxstatus",width:'8%'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'}
        ];
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })
    // 期刊下拉框
    function periodical(that){
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top=offset.top-$(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if($(document).height() < top+$(that).next('ul').height()){
            $(that).next('ul').css({
                'padding': 10,
                'top': - ($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parent('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
    // 操作下拉
    function dropdown(that){
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top=offset.top-$(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if($(document).height() < top+$(that).next('ul').height()){
            $(that).next('ul').css({
                'padding': 10,
                'top': - ($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parent('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'price':
                action.set_product('price',id,value);
                break;
            case 'stock':
                action.set_product('stock',id,value);
                break;
            case 'sort':
                action.set_product('sort',id,value);
                break;
            case 'ficti':
                action.set_product('ficti',id,value);
                break;
        }
    });
    //上下加产品
    layList.switch('is_show',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'column.column_list',a:'set_show',p:{is_show:1,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'column.column_list',a:'set_show',p:{is_show:0,id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    //点击事件绑定
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'column.column_list',a:'delete',q:{id:data.id}});
                var code = {title:"操作提示",text:"确定将该产品移入回收站吗？",type:'info',confirm:'是的，移入回收站'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'restore':
                var url=layList.U({c:'column.column_list',a:'delete',q:{id:data.id}});
                var code = {title:"操作提示",text:"确定将该产品还原吗？",type:'info',confirm:'是的，还原'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '还原失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
            case 'open_image':
                $eb.openImage(data.image);
                break;
            case 'recommend_column':
                var url=layList.U({
		                c:'column.column_list',
		                a:'recommendProduct',
		                q:{id:data.id,recommend_sell:1}
                });
                var code = {title:"操作提示",text:"你确定要推荐该商品吗？",type:'info',confirm:'是的，我要推荐'};

                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            window.location.reload();
                        }else
                            return Promise.reject(res.data.msg || '推荐失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                }, code)
                break;
            case 'cancel_recommend_column':
                var url=layList.U({c:'column.column_list',a:'recommendProduct',q:{id:data.id,recommend_sell:0}});
                var code = {title:"操作提示",text:"你确定要取消推荐该商品吗？",type:'info',confirm:'是的，我要取消'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            window.location.reload();
                        }else
                            return Promise.reject(res.data.msg || '取消失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                }, code)
                break;
        }
    })
    //排序
    layList.sort(function (obj) {
        var type = obj.type;
        switch (obj.field){
            case 'id':
                layList.reload({order: layList.order(type,'p.id')},true,null,obj);
                break;
            case 'sales':
                layList.reload({order: layList.order(type,'p.sales')},true,null,obj);
                break;
        }
    });
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });
    //自定义方法
    var action={
        set_product:function(field,id,value){
            layList.baseGet(layList.Url({c:'column.column_list',a:'set_product',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
        show:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                layList.basePost(layList.Url({c:'column.column_list',a:'product_show'}),{ids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要上架的产品');
            }
        },
        unable:function(){
            var code = {title:"提示",text:"该功能未开通或已过期，如需开通，请联系客服！",type:'info',confirm:'联系客服',cancel:'取消',confirmBtnColor:'#0ca6f2'};
            $eb.$swal('delete',function(){
                $eb.createModalFrame('联系客服','https://h5a.opensns.cn/auth/Index/tip_box.html',{h:600,w:700})
            }, code)
        },
    };
    //多选事件绑定
    $('.layui-btn-container').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function(){
            action[type] && action[type]();
        })
    });
</script>
{/block}
