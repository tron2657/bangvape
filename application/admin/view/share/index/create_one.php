{extend name="public/container"}
{block name="content"}
<link href="/public/static/plug/iview/dist/styles/iview.css" rel="stylesheet">
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
	.upload-img-box {
	    border: 1px solid #E5E6E7;
	    border-radius: 3px;
	    width: 58px;
	    height: 58px;
	}
	
	.upload-img-box-img {
	    width: 58px;
	    height: 58px;
			text-align: center;
			line-height: 58px;
			border-radius: 4px;
			box-shadow: 2px 2px 5px rgba(0,0,0,.1);
	}
	
	.delete-btn {
	    width: 58px;
	    height: 58px;
	    cursor: pointer;
	    display: flex;
			font-size: 20px;
	    align-items: center;
	    justify-content: center;
	    background-color: rgba(0, 0, 0, 0.5);
	}
	
	.delete-btn img {
	    width: 30px;
	    height: 30px;
	}
	
	html,body{
		width: 100%;
		height: 100%;
		font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
		color: #495060;
	}
	.wrapper{
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
	}
	.box{
		width: 100%;
		height: 100%;
		background-color: #ffffff;
	}
	.form-box{
		width: 100%;
		height: 100%;
		padding: 25px;
	}
	.div-box{
		width: 100%;
		height: 60px;
		float: left;
	}
	.div-left{
		width: 125px;
		height: 32px;
		padding: 10px 12px 10px 0;
		color: #495060;
		font-size: 12px;
		text-align: right;
		line-height: 1;
		float: left;
	}
	.div-icon{
		color: #ed3f14;
		font-size: 12px;
		line-height: 1;
		margin-right: 2px;
		display: inline-block;
		font-family: simsun;
	}
	.div-right{
		width: 570px;
		height: 32px;
		float: left;
	}
	.div-input{
		width: 100%;
		height: 32px;
		line-height: 1.5;
		padding: 4px 7px;
		font-size: 12px;
		border: 1px solid #dddee1;
		border-radius: 4px;
		color: #495060;
		font-family: inherit;
	}
	.div-input::-webkit-input-placeholder{
		color: #BBBEC4;
	}
	.image-box{
		display: inline-block;
		width: 58px;
		height: 58px;
		text-align: center;
		line-height: 58px;
		border-radius: 4px;
		overflow: hidden;
		box-shadow: 2px 2px 5px rgba(0,0,0,.1);
		margin-right: 4px;
		border: 1px dashed #c0ccda;
	}
	.div-careful{
		width: 100%;
		height: 100%;
		line-height: 1.5;
		text-align: left;
		color: #AAAAAA;
	}
	.div-careful-span{
		display: inline-block;
		color: #02A7F0;
		text-decoration: underline;
	}
	.input-radio{
		margin-top: 0 !important;
		cursor: pointer
	}
	.number-box{
		width: 80px;
		height: 32px;
		float: left;
	}
	.input-number{
		width: 100%;
		height: 100%;
		line-height: 32px;
		padding: 0 7px;
		text-align: left;
		border: 0;
		color: #666;
		border-radius: 4px;
		border: 1px solid #dddee1;
	}
	.button-box{
		width: 100%;
		height: 36px;
		float: left;
	}
	.button{
		width: 100%;
		height: 100%;
		float: left;
		color: #fff;
		background-color: #2d8cf0;
		border-color: #2d8cf0;
		padding: 6px 15px 7px 15px;
		font-size: 14px;
		border-radius: 4px;
		font-weight: 400;
		text-align: center;
		border: 0;
	}
	.button-span{
		margin-left: 4px;
		font-weight: 400;
		line-height: 1.5;
	}
	#hbsl{
		cursor: pointer
	}
</style>
{if condition="$style eq 'create'"}
<div class="box">
	<form class="form-box" id="createForm">
		<div class="div-box">
			<div class="div-left">
				<span class="div-icon">*</span>
				海报名称
			</div>
			<div class="div-right">
				<input type="text" class="div-input" placeholder="请输入海报名称" name="title" value="" />
			</div>
		</div>
		<div class="div-box" style="height: 75px;">
			<div class="div-left" style="height: 44px;">
				海报图片(563*1000px)
			</div>
				<div class="form-group" id="upload_img_content">
				    <div class="col-md-12">
				        <div class="input-group" style="display: flex;float: left;">
				            <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
				                <div class="image-box upload_span" id="upload_img_box">
													<i class="ivu-icon ivu-icon-image" style="font-size: 20px;"></i>
				                  <input value="" type="hidden" id="image_input" name="url">
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
		</div>
		<div class="div-box">
			<div class="div-left"></div>
			<div class="div-right" style="width: 560px;height: 44px;">
				<div class="div-careful">
					注意：海报尺寸要求：563*1000px，底部边距230px内显示用户二维码、用户名、邀请码等信息。如上传海报不符合该标准，则可能导致前端海报显示变形。<span class="div-careful-span" id="hbsl">查看海报示例</span>
				</div>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				海报内置文字颜色
			</div>
			<div class="div-right" style="display: flex;align-items: center;">
				<input type="radio" class="input-radio" name="colour" value="1" checked="checked"/><span style="margin-left: 5px;">黑色</span>
				<input type="radio" class="input-radio" name="colour" value="2" style="margin-left: 20px;" /><span style="margin-left: 5px;">白色</span>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				排序
			</div>
			<div class="div-right">
				<div class="number-box">
					<input type="number" class="input-number" name="sort" value=""  onKeyUp="value=(parseInt(value=value.replace(/\D/g,''),10))" oninput="if(value<0)value=0"/>
				</div>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				是否显示
			</div>
			<div class="div-right" style="display: flex;align-items: center;">
				<input type="radio" class="input-radio" name="status" value="1" checked="checked"/><span style="margin-left: 5px;">显示</span>
				<input type="radio" class="input-radio" name="status" value="0" style="margin-left: 20px;" /><span style="margin-left: 5px;">隐藏</span>
			</div>
		</div>
		<div class="div-box">
			<div class="button-box">
				<button type="button" class="button" id="createsub">
					<i class="ivu-icon ivu-icon-ios-upload"></i><span class="button-span">提交</span>
				</button>
			</div>
		</div>
	</form>
</div>
{/if}
{if condition="$style eq 'edit'"/}
<div class="box">
	<form class="form-box" id="editForm">
		<input type="hidden" name="id" value="<?php echo $info['id']?>" />
		<div class="div-box">
			<div class="div-left">
				<span class="div-icon">*</span>
				海报名称
			</div>
			<div class="div-right">
				<input type="text" class="div-input" placeholder="请输入海报名称" name="title" value="<?php echo $info['title']?>" />
			</div>
		</div>
		<div class="div-box" style="height: 75px;">
			<div class="div-left" style="height: 44px;">
				海报图片(563*1000px)
			</div>
				<div class="form-group" id="upload_img_content">
				    <div class="col-md-12">
				        <div class="input-group" style="display: flex;float: left;">
				            <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
				                <div class="upload-img-box" id="hbimage">
													<div class="delete-btn" style="display: none;">
														<img src="{__ADMIN_PATH}css/delete.png" alt="">
													</div>
													<img class="upload-img-box-img" src='<?php echo $info['url']?>'>
												</div>
												<div class="image-box upload_span" id="upload_img_box" style="display: none;">
													<i class="ivu-icon ivu-icon-image" style="font-size: 20px;"></i>
				                  <input value="<?php echo $info['url']?>" type="hidden" id="image_input" name="url">
				                </div>
				            </div>
				        </div>
				    </div>
				</div>
		</div>
		<div class="div-box">
			<div class="div-left"></div>
			<div class="div-right" style="width: 560px;height: 44px;">
				<div class="div-careful">
					注意：海报尺寸要求：563*1000px，底部边距230px内显示用户二维码、用户名、邀请码等信息。如上传海报不符合该标准，则可能导致前端海报显示变形。<span class="div-careful-span" id="hbsl">查看海报示例</span>
				</div>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				海报内置文字颜色
			</div>
			<div class="div-right" style="display: flex;align-items: center;">
				<input type="radio" class="input-radio" name="colour" value="1" <?php if($info['colour'] == '1') echo 'checked'?>/><span style="margin-left: 5px;">黑色</span>
				<input type="radio" class="input-radio" name="colour" value="2" style="margin-left: 20px;" <?php if($info['colour'] == '2') echo 'checked'?> /><span style="margin-left: 5px;">白色</span>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				排序
			</div>
			<div class="div-right">
				<div class="number-box">
					<input type="number" class="input-number" name="sort" value="<?php echo $info['sort']?>"  onKeyUp="value=(parseInt(value=value.replace(/\D/g,''),10))" oninput="if(value<0)value=0"/>
				</div>
			</div>
		</div>
		<div class="div-box">
			<div class="div-left">
				是否显示
			</div>
			<div class="div-right" style="display: flex;align-items: center;">
				<input type="radio" class="input-radio" name="status" value="1" <?php if($info['status'] == '1') echo 'checked'?>/><span style="margin-left: 5px;">显示</span>
				<input type="radio" class="input-radio" name="status" value="0" style="margin-left: 20px;" <?php if($info['status'] == '0') echo 'checked'?>/><span style="margin-left: 5px;">隐藏</span>
			</div>
		</div>
		<div class="div-box">
			<div class="button-box">
				<button type="button" class="button" id="editsub">
					<i class="ivu-icon ivu-icon-ios-upload"></i><span class="button-span">提交</span>
				</button>
			</div>
		</div>
	</form>
</div>
{/if}
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
{if condition="$style eq 'create'"}
<script>
		//点击上传图片后选择图片
		function createFrame(title, src, opt) {
		  opt === undefined && (opt = {});
		  return layer.open({
		    type: 2,
		    title: title,
		    area: [(opt.w || 728) + 'px', (opt.h || 458) + 'px'],
		    fixed: false, //不固定
		    maxmin: true,
		    moveOut: false,//true  可以拖出窗外  false 只能在窗内拖
		    anim: 5,//出场动画 isOutAnim bool 关闭动画
		    offset: 'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
		    shade: 0.5,//遮罩
		    resize: false,//是否允许拉伸
		    content: src,//内容
		    move: false
		  });
		}
		function changeIMG(index, pic) {
		  $("#img_content").append('<div class="upload-img-box" id="hbimage"><div class="delete-btn" style="display: none;"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div><img class="upload-img-box-img" src=' + pic + '></div>');
		  $("#upload_img_box").hide();
			$('#image_input').attr("value",pic);
			$("#hbimage").hover(function(){
				$(".delete-btn").show()
				$(".upload-img-box-img").hide()
				$(".delete-btn").on('click',function(){
					$("#hbimage").remove()
					$("#upload_img_box").show();
				})
			},function(){
				$(".delete-btn").hide()
				$(".upload-img-box-img").show()
			})
		}
</script>
{elseif condition="$style eq 'edit'"/}
<script>
		//点击上传图片后选择图片
		function createFrame(title, src, opt) {
		  opt === undefined && (opt = {});
		  return layer.open({
		    type: 2,
		    title: title,
		    area: [(opt.w || 728) + 'px', (opt.h || 458) + 'px'],
		    fixed: false, //不固定
		    maxmin: true,
		    moveOut: false,//true  可以拖出窗外  false 只能在窗内拖
		    anim: 5,//出场动画 isOutAnim bool 关闭动画
		    offset: 'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
		    shade: 0.5,//遮罩
		    resize: false,//是否允许拉伸
		    content: src,//内容
		    move: false
		  });
		}
		$("#hbimage").hover(function(){
			$(".delete-btn").show()
			$(".upload-img-box-img").hide()
			$(".delete-btn").on('click',function(){
				$("#hbimage").remove()
				$("#upload_img_box").show()
			})
		},function(){
			$(".delete-btn").hide()
			$(".upload-img-box-img").show()
		})
		function changeIMG(index, pic) {
		  $("#img_content").append('<div class="upload-img-box" id="hbimage"><div class="delete-btn" style="display: none;"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div><img class="upload-img-box-img" src=' + pic + '></div>');
		  $("#upload_img_box").hide();
		  $('#image_input').attr("value",pic);
			$("#hbimage").hover(function(){
				$(".delete-btn").show()
				$(".upload-img-box-img").hide()
				$(".delete-btn").on('click',function(){
					$("#hbimage").remove()
					$("#upload_img_box").show()
				})
			},function(){
				$(".delete-btn").hide()
				$(".upload-img-box-img").show()
			})
		}
</script>
{/if}
	<script>
	//上传图片
	$('.upload_span').on('click', function (e) {
		createFrame('请选择海报图片(563*1000px)', '{:Url('widget.images/index')}?fodder=image');
	});
	//点击查看海报示例
	$(document).on('click','#hbsl',function(){
		var img='<img src="{__ADMIN_PATH}/images/hbsl.png" alt="" style="width: 225px;height: 100%;float:left;"/><img src="{__ADMIN_PATH}/images/yt.png" alt=""  style="width: 225px;height: 100%;float:right;"/>'
		layer.open({
			title:false,
			btn:false,
			area:['500px','500px'],
			content:img
		})
	})
	//点击提交
	//新增数据
	$('#createsub').on('click',function(){
		var formlist=$('#createForm').serializeArray();
		console.log(formlist)
		for(var i=0;i<formlist.length;i++){
			if(formlist[i].name=='title'&&formlist[i].value==''){
				$eb.message('error', '请输入海报名称');
				return false;
			}
			if(formlist[i].name=='url'&&formlist[i].value==''){
				$eb.message('error', '请选择海报图片');
				return false;
			}
			if(formlist[i].name=='sort'&&formlist[i].value==''){
				$eb.message('error', '请输入排序');
				return false;
			}
		}
		$.ajax({
			url:"{:Url('saveOne')}",
			data:formlist,
			type:'post',
			dataType: 'json',
			success:function(re){
				if (re.code == 200){
					$eb.message('success',re.msg);
					setTimeout(function (e) {
					  parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
					  parent.layer.close(parent.layer.getFrameIndex(window.name));
					},600)
				}else{
					$eb.message('error',re.msg);
				}
			}
		})
	})
	//编辑
	$('#editsub').on('click',function(){
		var formlist=$('#editForm').serializeArray();
		console.log(formlist)
		for(var i=0;i<formlist.length;i++){
			if(formlist[i].name=='title'&&formlist[i].value==''){
				$eb.message('error', '请输入海报名称');
				return false;
			}
			if(formlist[i].name=='url'&&formlist[i].value==''){
				$eb.message('error', '请选择海报图片');
				return false;
			}
			if(formlist[i].name=='sort'&&formlist[i].value==''){
				$eb.message('error', '请输入排序');
				return false;
			}
		}
		$.ajax({
			url:"{:Url('update_one')}",
			data:formlist,
			type:'post',
			dataType: 'json',
			success:function(re){
				if (re.code == 200){
					$eb.message('success',re.msg);
					setTimeout(function (e) {
					  parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
					  parent.layer.close(parent.layer.getFrameIndex(window.name));
					},600)
				}else{
					$eb.message('error',re.msg);
				}
			}
		})
	})
</script>
{/block}