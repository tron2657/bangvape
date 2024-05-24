{extend name="public/container"}

{block name="content"}

<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>

<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>

<style>
    .my-align-radio {
        display: flex;
        align-items: center;
    }

    .radio-style {
        margin: initial;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">推荐用户列表</li>
                        <li>推荐关注设置</li>
                    </ul>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show">
    <div class="layui-card-body">
                    <div class="layui-btn-container">
                        <button type="button" class="layui-btn layui-btn-sm"  onclick="$eb.createModalFrame('推荐用户设置','{:Url('set_recommend')}')">
                            添加推荐
                        </button>
                        <button class="layui-btn layui-btn-sm" data-type="remove">取消推荐</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <script type="text/html" id="act">
                        <button class="layui-btn layui-btn-xs" lay-event='delstor'>取消推荐</button>
                    </script>
    
                    <script type="text/html" id="switchOnOff">
                    <input type="checkbox" name="attention" lay-skin="switch" lay-text="开启|关闭" value='{{d.id}}' lay-event='attention' lay-filter="attention" {{d.attention == "1" ?"checked":"" }}>
                    </script>
                </div>
    </div>
    <div class="layui-tab-item">
		<label class="col-sm-4 control-label" style="width: auto;padding-top: 0; padding-left: 4px;">首次登录推荐用户引导：</label>
		<div class="col-sm-8">
		    <div class="row">
		        <div class="">
		            <!--单选按钮-->
		            <div class="radio i-checks" style="display:inline" >
		                <label class="" style="padding-left: 0;"  data-role="change_set" data-value="1">
		                    <div class="iradio_square-green " style="position: relative;">
		                        <div class=" checked" style="position: relative;">
		                            {if condition="$pz eq '1'"}
		                            <input type="radio"  checked="checked" name="attention_radio" value="1" lay-filter="attention_radio" style="position: absolute; opacity: 0;">
		                            {else/}
		                            <input type="radio" name="attention_radio" value="1" lay-filter="attention_radio" style="position: absolute; opacity: 0;">
		                            {/if}
		                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
		                        </div>
		                    </div>
		                    <i></i> 开启
		                </label>
		            </div>
		            <div class="radio i-checks" style="display:inline"  >
		                <label class="" style="padding-left: 0;" data-role="change_set" data-value="0">
		                    <div class="iradio_square-green" style="position: relative;">
		                        <div class="" style="position: relative;">
		                            {if condition="$pz eq '0'"}
		                            <input type="radio"  checked="checked" name="attention_radio" value="0" lay-filter="attention_radio" style="position: absolute; opacity: 0;">
		                            {else/}
		                            <input type="radio" name="attention_radio" value="0" lay-filter="attention_radio" style="position: absolute; opacity: 0;">
		                            {/if}
		                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
		                        </div>
		                    </div>
		                    <i></i> 关闭
		                </label>
		            </div>
		
		        </div>
		        <div class="">
		            <span class="help-block m-b-none">开启后新用户首次登录后显示推荐关注引导页面，选择感兴趣的用户（若无可选用户，则直接跳过引导页面）</span>
		        </div>
		    </div>
		</div>
			<div class="btn" id="at_ra" style="background-color: #0092DC;color: #fff;margin: 40px 160px;">
			    保存
			</div>
           
       </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
{/block}
{block name="script"}
<script>
	var is_on;
	$().ready(function() {
	    $('.i-checks').iCheck({
	        checkboxClass: 'icheckbox_square-green',
	        radioClass: 'iradio_square-green',
	    });
	});
	is_on = $('.chose-box').attr('data-open');
	
	if($('.chose-box').attr('data-open') == 0) {
	    $('input[id="chose2"]').iCheck('check');
	} else if ($('.chose-box').attr('data-open') == 1) {
	    $('input[id="chose1"]').iCheck('check');
	}
	$('input[name="chose"]').on('ifChanged', function(){
	    if ($('input[id="chose1"]').prop("checked")) {
	        is_on = 1;
	    } else if ($('input[id="chose2"]').prop("checked")){
	        is_on = 0;
	    }
	})
    setTimeout(function () {
        $('.alert-info').hide();
    },3000);
    //实例化form
    layList.form.render();
    //加载列表
    layList.tableList('List',"{:Url('user_list')}",function (){
        return [
            {type:'checkbox'},
            {field: 'id', title: 'ID'},
            {field: 'nickname', title: '用户名'},
            //{field: 'reason', title: '推荐原因'},
            {field: 'sort', title: '推荐排序',edit:'sort'},
            {field: 'create_time', title: '推荐时间'},
            {field:'attention',title:'默认关注',templet: '#switchOnOff'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act'},
        ];
    });
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'user.user_recommend',a:'quick_edit',q:{id:data.id, field:'status', value:0}});
                var code = {title:"是否要取消推荐",text:"确定要取消推荐吗",confirm:'确定'};
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', '');
                            obj.del();
                        }else
                            return Promise.reject(res.data.msg || '取消失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },code)
                break;
        }
    })
    //自定义方法
    var action= {
        quick_edit:function(field, id, value){
            layList.baseGet(layList.Url({c:'user.user_recommend',a:'quick_edit',q:{field:field,id:id,value:value}}),function (res) {
                layList.msg(res.msg);
            });
        },
        remove:function(){
            var ids=layList.getCheckData().getIds('id');
            if(ids.length){
                var code = {title:"操作提示",text:"确定要取消推荐吗？ ",type:'info',confirm:'确定'};
                $eb.$swal('delete',function(){
                    layList.basePost(layList.Url({c:'user.user_recommend',a:'del_recommend'}),{ids:ids},function (res) {
                        layList.msg(res.msg);
                        layList.reload();
                    });
                },code);
            }else{
                layList.msg('请选择要取消推荐的用户');
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
    //查询
    layList.search('search',function(where){
        layList.reload(where,true);
    });

    layList.switch('status',function (odj,value) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({c:'com.com_adv',a:'quick_edit',p:{value:1,field:'status',id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({c:'com.com_adv',a:'quick_edit',p:{value:0,field:'status', id:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });

    //快速编辑
    layList.edit(function (obj) {
        var id=obj.data.id,value=obj.value;
        switch (obj.field) {
            case 'name':
                action.quick_edit('name',id,value);
                break;
            case 'sort':
                action.quick_edit('sort',id,value);
                break;
            case 'url':
                action.quick_edit('url',id,value);
                break;
        }
    });
    
    
    layList.form.on('switch(attention)', function(data){
        layList.baseGet(layList.Url({c:'user.user_recommend',a:'attention_edit',p:{id:data.value,attention:data.elem.checked}}),function(some) {
                layList.msg(some.msg);
        });
    });  
    layui.use('element', function(){
            var element = layui.element;
  
    });
    $("#at_ra").click(function(){
        var qw = $("input[name='attention_radio']:checked").val();
        layList.baseGet(layList.Url({c:'user.user_recommend',a:'recommend_config_edit',p:{config_edit:qw}}),function(some) {
                layList.msg(some.msg);
        });
    });
    
    //监听并执行排序
    // layList.sort(['id','sort'],true);
    //点击事件绑定
 /*   layList.tool(function (event,data,obj) {
        switch (event) {
            case 'delstor':
                var url=layList.U({c:'com.com_adv',a:'delete',q:{id:data.id}});
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
                })
                break;
        }
    })*/
</script>
{/block}
