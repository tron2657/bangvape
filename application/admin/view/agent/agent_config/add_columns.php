{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
{/block}
{block name="content"}
<div class="row">
	<div class="col-sm-12">
		<div class="ibox">
			<div class="ibox-content">
				<form class="layui-form" action="" style="padding:20px;">
					<fieldset><legend><a name="input">通过专栏商品名称、ID快速找到专栏商品</a></legend></fieldset>
					<select name="uids" id="bind_select" xm-select="product_select" xm-select-search="{:Url('com.com_thread/find_column_products')}" xm-select-radio>
						<option value="">请输入专栏商品名称、ID</option>
					</select>
					<br/>
					<button class="btn btn-primary" id="save" type="button">
						<i class="fa  fa-arrow-circle-o-right"></i>
						添加
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    var formSelects = layui.formSelects;
    var form = layui.form;
    form.render();
    formSelects.config('product_select', {
        type: 'get',                //请求方式: post, get, put, delete...
        searchName: 'nickname',      //自定义搜索内容的key值
        clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
    }, false);


    $('#save').on('click',function(){
        var selectVal = formSelects.value('product_select', 'val')[0];
        if(selectVal){
            selectVal = decodeURIComponent(selectVal)
            var selectName = formSelects.value('product_select', 'name')[0];
            Toast.success("添加成功");
            window.localStorage.setItem("add_columns_val",selectVal);
            setTimeout(function (e) {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            },600)
        }else {
            Toast.error("请选择商品");
        }

    });
</script>
{/block}
