{extend name="public/modal-frame"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
.panel{
	margin-bottom: 0;
	border-bottom: 0;
}
.panel-body{
	border-bottom: 0;
}
.box{
	width: 100%;
	height: 100%;
}
.top-title{
	font-size: 18px;
	font-weight: 600;
	height: 40px;
	line-height: 40px;
	color: #333333;
	padding-left: 5px;
}
.top-now{
	display: none;
	width: 100%;
	max-height: 200px;
	height: 120px;
}
.top-type-box{
	width: 260px;
	height: 60px;
	background-color: #F5F5F5;
	border: 1px solid #D9D9D9;
	border-radius: 8px;
	float: left;
	margin-bottom: 10px;
	margin-right: 20px;
	padding: 10px;
}
.top-type-left{
	width: 90%;
	height: 100%;
	float: left;
}
.left-text{
	width: 100%;
	height: 50%;
	color: #333333;
	font-size: 14px;
	display: flex;
	align-items: center;
}
.left-time{
	width: 100%;
	height: 50%;
	color: #7F7F7F;
	font-size: 12px;
	display: flex;
	align-items: center;
}
.top-type-right{
	width: 10%;
	height: 100%;
	float: right;
	display: flex;
	justify-content: center;
	align-items: center;
}
.right-close{
	color: #868686;
	font-size: 20px;
	font-family: inherit;
	cursor:pointer;
}
.content-box{
	padding: 40px;
}
.content-title{
	font-size: 14px;
	font-weight: 600;
	color: #333333;
	border: 0;
}
.number-box{
	width: 100%;
	height: 40px;
	margin-top: 15px;
	padding-left: 10px;
	margin-bottom: 30px;
}
.number{
	background-color: #F2F2F2;
	width: 65px;
	height: 40px;
	font-size: 14px;
	line-height: 40px;
	text-align: center;
	margin-left: 15px;
	float: left;
	border: 0;
}
.number-new{
	background-color: #F2F2F2;
	width: 65px;
	height: 40px;
	font-size: 14px;
	line-height: 40px;
	text-align: center;
	margin-left: 15px;
	float: left;
	border: 0;
	background-color: #0075FF;
	color: #ffffff;
}
.number-text{
	width: 80px;
	height: 40px;
	font-size: 14px;
	line-height: 40px;
	text-align: center;
	float: left;
	margin-left: 30px;
}
.number-input{
	width: 100px;
	height: 40px;
	border: 1px solid #D7D7D7;
	padding: 5px;
	float: left;
	margin-left: 15px;
	appearance: none;
}
.reason{
	width: 100%;
	height: 80px;
	border: 1px solid #D7D7D7;
	margin-top: 30px;
	resize: none;
	padding: 5px;
}
.button{
	width: 90%;
	height: 40px;
	background-color: #169BD5;
	color: #FFFFFF;
	font-size: 14px;
	display: flex;
	justify-content: center;
	align-items: center;
	border-radius: 4px;
	border: 1px solid #169BD5;
	margin-left: 5%;
}
.content-name{
	color: #333333;
	float: left;
	margin-top: 2px;
}
.content-text{
	color: #AAAAAA;
	float: left;
}
.radio-box{
	height: 100%;
	float: left;
	margin-right: 4px;
}
#integral{
	display: none;
}
.times-box{
	width: 100%;
	height: 35px;
	margin-bottom: 30px;
}
.times-group{
	width: 100%;
	height: 100%;
	padding-left: 20px;
	font-size: 14px;
	color: #333333;
}
.times-group-day{
	width: 70px;
	height: 30px;
	border: 1px solid #797979;
	text-align: center;
	line-height: 30px;
	border-radius: 15px;
	margin-right: 30px;
	float: left;
	font-size: 14px;
	color: #333333;
	cursor:pointer;
}
.times-group-title{
	float: left;
	width: 20%;
	height: 100%;
	display: flex;
	align-items: center;
}
.times-group-input{
	float: left;
	width: 60%;
	height: 100%;
	border: 1px solid #D7D7D7;
	padding: 5px;
}
</style>
{/block}
{block name="content"}
<div class="panel">
    <div class="panel-body">
        <form class="form-horizontal" id="signupForm">
					<!-- 置顶 -->
					{if condition="$type eq 'top'"}
					<div class="box">
						<div style="padding: 20px;height: 100%;">
							<div class="top-now">
								<div class="top-title">
									当前置顶
								</div>
								<div id="topbox"></div>
							</div>
							<div class="top-title">
								置顶形式
							</div>
							<!-- <div style="padding-left: 10px;height: 60px;margin-top: 20px;">
								<div class="radio-box" style="padding-top: 10px;">
									<input class="input-checkbox" type="radio" name="sex" value="index_top"/>
								</div>
								<div style="height: 50px;float: left;">
									<div class="content-name">推荐频道置顶</div>
									<br />
									<div class="content-text">内容会在推荐列表置顶显示</div>
								</div>
							</div> -->
							<div style="padding-left: 10px;height: 60px;">
								<div class="radio-box" style="padding-top: 10px;">
									<input class="input-checkbox" type="radio" name="sex" value="top" checked="true"/>
								</div>
								<div style="height: 50px;float: left;">
									<!-- <div class="content-name">版块内标题置顶</div>
									<br />
									<div class="content-text">内容会在版块首页置顶区域显示，仅显示标题</div> -->

									<div class="content-name">置顶</div>
									<br />
									<div class="content-text">内容会在版块首页置顶区域显示</div>
								</div>
							</div>
							<!-- <div style="padding-left: 10px;height: 60px;">
								<div class="radio-box" style="padding-top: 10px;">
									<input class="input-checkbox" type="radio" name="sex" value="detail_top"/>
								</div>
								<div style="height: 50px;float: left;">
									<div class="content-name">版块内列表置顶</div>
									<br />
									<div class="content-text">内容会在版块首页内容列表中置顶显示</div>
								</div>
							</div> -->
							<div class="top-title">
								时长设置
							</div>
							<div class="times-box" style="margin-bottom: 20px;margin-top: 20px;">
								<div class="times-group">
									<input class="times-group-day" type="text" name="" value="1天" readonly onclick="choosetime(0)" />
									<input class="times-group-day" type="text" name="" value="3天" readonly onclick="choosetime(1)" />
									<input class="times-group-day" type="text" name="" value="7天" readonly onclick="choosetime(2)" />
									<input class="times-group-day" type="text" name="" value="30天" readonly onclick="choosetime(3)" />
									<input class="times-group-day" type="text" name="" value="90天" readonly onclick="choosetime(4)" />
									<input class="times-group-day" type="text" name="" value="365天" readonly style="margin-right: 0;" onclick="choosetime(5)" />
								</div>
							</div>
							<div class="times-box">
									<div class="times-group">
											<span class="times-group-title">自定义截止时间</span>
											<input type="text" class="times-group-input" id="send_time" readonly onclick="choosetime(6)">
									</div>
							</div>
							<div class="top-title">
								积分奖励
							</div>
							<div style="padding-left: 10px;height: 40px;margin-top: 20px;">
								<div class="radio-box">
									<input class="input-radio" id="hide" type="radio" name="sexed" value="noreward" checked="checked"/>
								</div>
								<div class="content-name">不奖励</div>
							</div>
							<div style="padding-left: 10px;height: 40px;">
								<div class="radio-box">
									<input class="input-radio" id="show" type="radio" name="sexed" value="reward"/>
								</div>
								<div class="content-name">奖励</div>
							</div>
						</div>
					</div>
					<div class="box" id="integral">
						<div class="content-box" style="padding-top: 0;padding-bottom: 0;">
							{volist name="score" id="v"}
							<input class="content-title" type="text" name="{$v.flag}" value="{$v.name}" readonly />
							<div class="number-box">
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="1" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="3" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="5" readonly onclick="choosenum(this)"/>
								<div class="number-text">自定义分值</div>
								<input class="number-input" id="{$v.flag}" type="number" oninput="if(value<1)value=0" name="{$v.flag}" value="" onchange="choosenums(this)" onclick="choosenumber(this)"/>
							</div>
							{/volist}
							<div class="content-title">
								理由说明
							</div>
							<textarea class="reason" id="textarea-reason" name="reason"></textarea>
						</div>
					</div>
					<button type="button" class="button" style="margin-top: 50px;">确定</button>
					{/if}
					
					<!-- 加精 -->
					{if condition="$type eq 'essence'"}
					<div class="box">
						<div style="padding: 20px;height: 100%;">
							<div class="top-title">
								内容设置为精华
							</div>
							<div style="padding-left: 10px;height: 60px;margin-top: 20px;">
								<div class="radio-box" style="padding-top: 10px;">
									<input class="input-checkbox" name="sex" value="essence" type="radio" checked="checked"/>
								</div>
								<div style="height: 100%;float: left;">
									<div class="content-name">内容设置为精华</div>
									<br />
									<div class="content-text">内容会在该板块精华列表中显示</div>
								</div>
							</div>
							<div class="top-title">
								积分奖励
							</div>
							<div style="padding-left: 10px;height: 40px;margin-top: 20px;">
								<div class="radio-box">
									<input class="input-radio" id="hide" type="radio" name="sexed" value="noreward" checked="checked"/>
								</div>
								<div class="content-name">不奖励</div>
							</div>
							<div style="padding-left: 10px;height: 40px;">
								<div class="radio-box">
									<input class="input-radio" id="show" type="radio" name="sexed" value="reward"/>
								</div>
								<div class="content-name">奖励</div>
							</div>
						</div>
					</div>
					<div class="box" id="integral">
						<div class="content-box" style="padding-top: 0;">
							{volist name="score" id="v"}
							<input class="content-title" type="text" name="{$v.flag}" value="{$v.name}" readonly />
							<div class="number-box">
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="1" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="3" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="5" readonly onclick="choosenum(this)"/>
								<div class="number-text">自定义分值</div>
								<input class="number-input" id="{$v.flag}" type="number" oninput="if(value<1)value=0" name="{$v.flag}" value="" onchange="choosenums(this)" onclick="choosenumber(this)"/>
							</div>
							{/volist}
							<div class="content-title">
								理由说明
							</div>
							<textarea class="reason" id="textarea-reason" name="reason"></textarea>
						</div>
					</div>
					<button type="button" class="button">确定</button>
					{/if}
					
					<!-- 推荐 -->
					{if condition="$type eq 'recommend'"}
					<div class="box">
						<div style="padding: 20px;height: 100%;">
							<div class="top-title">
								推荐到首页
							</div>
							<div style="padding-left: 10px;height: 60px;margin-top: 20px;">
								<div class="radio-box" style="padding-top: 10px;">
									<input class="input-checkbox" type="radio" name="sex" value="recommend" checked="checked"/>
								</div>
								<div style="height: 100%;float: left;">
									<div class="content-name">推荐到首页</div>
									<br />
									<div class="content-text">内容会在首页推荐列表显示</div>
								</div>
							</div>
							<div class="top-title">
								时长设置
							</div>
							<div class="times-box" style="margin-bottom: 20px;margin-top: 20px;">
								<div class="times-group">
									<input class="times-group-day" type="text" name="" value="1天" readonly onclick="choosetime(0)" />
									<input class="times-group-day" type="text" name="" value="3天" readonly onclick="choosetime(1)" />
									<input class="times-group-day" type="text" name="" value="7天" readonly onclick="choosetime(2)" />
									<input class="times-group-day" type="text" name="" value="30天" readonly onclick="choosetime(3)" />
									<input class="times-group-day" type="text" name="" value="90天" readonly onclick="choosetime(4)" />
									<input class="times-group-day" type="text" name="" value="365天" readonly style="margin-right: 0;" onclick="choosetime(5)" />
								</div>
							</div>
							<div class="times-box">
									<div class="times-group">
											<span class="times-group-title">自定义截止时间</span>
											<input type="text" class="times-group-input" id="send_time" readonly onclick="choosetime(6)">
									</div>
							</div>
							<div class="top-title">
								积分奖励
							</div>
							<div style="padding-left: 10px;height: 40px;margin-top: 20px;">
								<div class="radio-box">
									<input class="input-radio" id="hide" type="radio" name="sexed" value="noreward" checked="checked"/>
								</div>
								<div class="content-name">不奖励</div>
							</div>
							<div style="padding-left: 10px;height: 40px;">
								<div class="radio-box">
									<input class="input-radio" id="show" type="radio" name="sexed" value="reward"/>
								</div>
								<div class="content-name">奖励</div>
							</div>
						</div>
					</div>
					<div class="box" id="integral">
						<div class="content-box" style="padding-top: 0;">
							{volist name="score" id="v"}
							<input class="content-title" type="text" name="{$v.flag}" value="{$v.name}" readonly />
							<div class="number-box">
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="1" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="3" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="5" readonly onclick="choosenum(this)"/>
								<div class="number-text">自定义分值</div>
								<input class="number-input" id="{$v.flag}" type="number" oninput="if(value<1)value=0" name="{$v.flag}" value="" onchange="choosenums(this)" onclick="choosenumber(this)"/>
							</div>
							{/volist}
							<div class="content-title">
								理由说明
							</div>
							<textarea class="reason" id="textarea-reason" name="reason"></textarea>
						</div>
					</div>
					<button type="button" class="button">确定</button>
					{/if}
					
					<!-- 积分奖励 -->
					{if condition="$type eq 'reward'"}
					<div class="box">
						<div class="top-title">
							积分奖励
						</div>
						<div class="content-box">
							{volist name="score" id="v"}
							<input class="content-title" type="text" name="{$v.flag}" value="{$v.name}" readonly />
							<div class="number-box">
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="1" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="3" readonly onclick="choosenum(this)"/>
								<input class="number {$v.flag}" type="text" name="{$v.flag}" value="5" readonly onclick="choosenum(this)"/>
								<div class="number-text">自定义分值</div>
								<input class="number-input" id="{$v.flag}" type="number" oninput="if(value<1)value=0" name="{$v.flag}" value="" onchange="choosenums(this)" onclick="choosenumber(this)"/>
							</div>
							{/volist}
							<div class="content-title">
								理由说明
							</div>
							<textarea class="reason" id="textarea-reason" name="reason"></textarea>
						</div>
					</div>
					<button type="button" class="button">确定</button>
					{/if}
        </form>
    </div>
</div>
{/block}
{block name="script"}
<script>
	$(document).ready(function(){
		var top=[<?php echo $top?>];
		var thetoptype=top[0];
		var list={};
		list.id=thetoptype.id;
		list.value=0;
		if(thetoptype.index_top==1){
			$(".top-now").css({
				"display":"block"
			})
			$("#topbox").append('<div class="top-type-box"><div class="top-type-left"><div class="left-text">推荐频道置顶</div><div class="left-time">到期：'+ thetoptype.index_top_end_time +'</div></div><div class="top-type-right"><div class="right-close" id="close">×</div></div></div>')
			$("#close").click(function(){
				layer.open({
					title:'取消置顶'
					,content: '确认后，将取消当前置顶形式'
					,btn: ['确认', '取消']
					,no: function(index, layero){
						
					}
					,yes: function(index, layero){
					 list.field="index_top";
					 $.ajax({
					 	url:"{:Url('quick_edit_index_top')}",
					 	data:list,
					 	type:'get',
					 	dataType:'json',
					 	success:function(re){
					 		if(re.code==200){
					 			$eb.message('success',re.msg);
					 			setTimeout(function (e) {
					 				parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
					 				parent.layer.close(parent.layer.getFrameIndex(window.name));
					 			}, 600)
					 		}else{
					 			$eb.message('error',re.msg);
					 		}
					 	}
					 })
					}
					,cancel: function(){ 
						
					}
				});
			})
		}
		if(thetoptype.is_top==1){
			$(".top-now").css({
				"display":"block"
			})
			$("#topbox").append('<div class="top-type-box"><div class="top-type-left"><div class="left-text">版块内标题置顶</div><div class="left-time">到期：'+ thetoptype.top_end_time +'</div></div><div class="top-type-right"><div class="right-close" id="closeone">×</div></div></div>')
			$("#closeone").click(function(){
				layer.open({
					title:'取消置顶'
					,content: '确认后，将取消当前置顶形式'
					,btn: ['确认', '取消']
					,no: function(index, layero){
						
					}
					,yes: function(index, layero){
					 list.field="is_top";
					 $.ajax({
					 	url:"{:Url('quick_edit')}",
					 	data:list,
					 	type:'get',
					 	dataType:'json',
					 	success:function(re){
					 		if(re.code==200){
					 			$eb.message('success',re.msg);
					 			setTimeout(function (e) {
					 				parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
					 				parent.layer.close(parent.layer.getFrameIndex(window.name));
					 			}, 600)
					 		}else{
					 			$eb.message('error',re.msg);
					 		}
					 	}
					 })
					 
					}
					,cancel: function(){ 
						
					}
				});
			})
		}
		if(thetoptype.detail_top==1){
			$(".top-now").css({
				"display":"block"
			})
			$("#topbox").append('<div class="top-type-box"><div class="top-type-left"><div class="left-text">版块内列表置顶</div><div class="left-time">到期：'+ thetoptype.detail_top_end_time +'</div></div><div class="top-type-right"><div class="right-close" id="closetwo">×</div></div></div>')
			$("#closetwo").click(function(){
				layer.open({
					title:'取消置顶'
					,content: '确认后，将取消当前置顶形式'
					,btn: ['确认', '取消']
					,no: function(index, layero){
						
					}
					,yes: function(index, layero){
					 list.field="detail_top";
					 $.ajax({
					 	url:"{:Url('quick_edit_detail_top')}",
					 	data:list,
					 	type:'get',
					 	dataType:'json',
					 	success:function(re){
					 		if(re.code==200){
					 			$eb.message('success',re.msg);
					 			setTimeout(function (e) {
					 				parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
					 				parent.layer.close(parent.layer.getFrameIndex(window.name));
					 			}, 600)
					 		}else{
					 			$eb.message('error',re.msg);
					 		}
					 	}
					 })
					}
					,cancel: function(){ 
						
					}
				});
			})
		}
	})
	$(document).ready(function(){
		$("#hide").click(function(){
			$("#integral").hide()
		});
		$("#show").click(function(){
			$("#integral").show()
		});
	})
	//选择时间
	function choosetime(e){
		if(e<6){
			$(".times-group-day").eq(e).css({
				"border":"1px solid #0075FF",
				"color":"#0075FF"
			})
			$(".times-group-day").eq(e).attr('name','times')
			$("#send_time").val("")
			$("#send_time").attr('name','')
		}else{
			for(var i=0;i<6;i++){
				$(".times-group-day").eq(i).attr('name','')
			}
			$("#send_time").attr('name','times')
		}
		for(var i=0;i<6;i++){
			if(i!=e){
				$(".times-group-day").eq(i).css({
					"border":"1px solid #797979",
					"color":"#333333"
				})
				$(".times-group-day").eq(i).attr('name','')
			}
		}
	}
	//选择积分数值
	var numlist=[]
	function choosenums(e){
		numlist.push({flag:e.name,num:e.value})
		for(var i=0;i<numlist.length;i++){
			for(var j=i+1;j<numlist.length;j++){
				if(numlist[i].flag==numlist[j].flag){
					numlist.splice(i,1)
					i--
				}
			}
		}
	}
	function choosenum(e){
		var classname=document.getElementsByClassName(e.name).length;
		for(var i=0;i<classname;i++){
			document.getElementsByClassName(e.name)[i].style=''
		}
		e.style="color:#ffffff;background-color:#0075FF"
		$("#"+e.name).val("")
		numlist.push({flag:e.name,num:e.value})
		for(var i=0;i<numlist.length;i++){
			for(var j=i+1;j<numlist.length;j++){
				if(numlist[i].flag==numlist[j].flag){
					numlist.splice(i,1)
					i--
				}
			}
		}
	}
	function choosenumber(e){
		var classname=document.getElementsByClassName(e.name).length;
		for(var i=0;i<classname;i++){
			document.getElementsByClassName(e.name)[i].style=''
		}
	}
	//自定义截止时间
	function dataToStamp(data) {
		if(data === ""){
				return "";
		}else {
				var str = data.replace(/-/g,'/');
				var dataTime = new Date(str);
				dataTime = Date.parse(dataTime);
				dataTime = dataTime / 1000;
				return dataTime;
		}
	}
	function timestampToTime(timestamp) {
		var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
		var Y = date.getFullYear() + '-';
		var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
		var D = date.getDate() <10 ? '0' + (date.getDate()) : date.getDate() + ' ';
		var h = date.getHours() <10 ? '0' + (date.getHours()) : date.getHours() + ':';
		var m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes()) + ':';
		var s = (date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds());
		return Y+M+D+h+m+s;
	}
	layui.use('laydate', function(){
		var laydate = layui.laydate;
	//执行一个laydate实例
		laydate.render({
			elem: '#send_time', //指定元素
			type:'datetime',
			min:new Date().getTime()
		});
	});
	//点击确定提交表单
	$(".button").on('click',function(){
		var type="{$type}";
		var toptype=""
		var formlist=$('#signupForm').serializeArray();
		for(var i=0;i<formlist.length;i++){
			if(formlist[i].name=='sex'){
				toptype=formlist[i].value
			}
		}
		//置顶
		if(type=='top'){
			if(toptype==''){
				$eb.message('error', '请选择置顶方式');
				return false;
			}
			var list={};
			var integraltype=false;
			for(var i=0;i<formlist.length;i++){
				if(formlist[i].value==='noreward'){
					integraltype=true
					break
				}else{
					integraltype=false
				}
			}
			if(integraltype == true){
				list.id="{$id}";
				list.type=toptype;
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward='';
				list.reward_explain='';
				for(var i=0;i<formlist.length;i++){
					if(formlist[i].name=='times'&&formlist[i].value!=''){
						var str=formlist[i].value
						if(escape(str).indexOf("%u")<0){
							list.time_type=2;
							list.end_time=formlist[i].value
						}else{
							list.time_type=1;
							var end_time=formlist[i].value
							end_time=end_time.substr(0,end_time.length-1)
							list.end_time=end_time
						}
					}
				}
				if(list.end_time==''){
					$eb.message('error', '请设置时长');
					return false;
				}
			}else{
				list.id="{$id}";
				list.type=toptype;
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward=numlist;
				list.reward_explain=$("#textarea-reason").val();
				for(var i=0;i<formlist.length;i++){
					if(formlist[i].name=='times'&&formlist[i].value!=''){
						var str=formlist[i].value
						if(escape(str).indexOf("%u")<0){
							list.time_type=2;
							list.end_time=formlist[i].value
						}else{
							list.time_type=1;
							var end_time=formlist[i].value
							end_time=end_time.substr(0,end_time.length-1)
							list.end_time=end_time
						}
					}
				}
				if(list.end_time==''){
					$eb.message('error', '请设置时长');
					return false;
				}
				if(list.reward==''){
					$eb.message('error', '请选择奖励内容');
					return false;
				}
				if(list.reward_explain==''){
					$eb.message('error', '请输入理由');
					return false;
				}
			}
			$.ajax({
				url:"{:Url('set_management')}",
				data:list,
				type:'post',
				dataType:'json',
				success:function(re){
					if(re.code==200){
						$eb.message('success',re.msg);
						setTimeout(function (e) {
							parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						}, 600)
					}else{
						$eb.message('error',re.msg);
					}
				}
			})
		}
		//加精
		if(type=='essence'){
			var list={};
			var integraltype=false;
			for(var i=0;i<formlist.length;i++){
				if(formlist[i].value==='noreward'){
					integraltype=true
					break
				}else{
					integraltype=false
				}
			}
			if(integraltype == true){
				list.id="{$id}";
				list.type="{$type}";
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward='';
				list.reward_explain='';
			}else{
				list.id="{$id}";
				list.type="{$type}";
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward=numlist;
				list.reward_explain=$("#textarea-reason").val();
				if(list.reward==''){
					$eb.message('error', '请选择奖励内容');
					return false;
				}
				if(list.reward_explain==''){
					$eb.message('error', '请输入理由');
					return false;
				}
			}
			$.ajax({
				url:"{:Url('set_management')}",
				data:list,
				type:'post',
				dataType:'json',
				success:function(re){
					if(re.code==200){
						$eb.message('success',re.msg);
						setTimeout(function (e) {
							parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						}, 600)
					}else{
						$eb.message('error',re.msg);
					}
				}
			})
		}
		//推荐
		if(type=='recommend'){
			var list={};
			var integraltype=false;
			for(var i=0;i<formlist.length;i++){
				if(formlist[i].value==='noreward'){
					integraltype=true
					break
				}else{
					integraltype=false
				}
			}
			if(integraltype == true){
				list.id="{$id}";
				list.type="{$type}";
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward='';
				list.reward_explain='';
				for(var i=0;i<formlist.length;i++){
					if(formlist[i].name=='times'&&formlist[i].value!=''){
						var str=formlist[i].value
						if(escape(str).indexOf("%u")<0){
							list.time_type=2;
							list.end_time=formlist[i].value
						}else{
							list.time_type=1;
							var end_time=formlist[i].value
							end_time=end_time.substr(0,end_time.length-1)
							list.end_time=end_time
						}
					}
				}
				if(list.end_time==''){
					$eb.message('error', '请设置时长');
					return false;
				}
			}else{
				list.id="{$id}";
				list.type="{$type}";
				list.is_post=1;
				list.time_type='';
				list.end_time='';
				list.reward=numlist;
				list.reward_explain=$("#textarea-reason").val();
				for(var i=0;i<formlist.length;i++){
					if(formlist[i].name=='times'&&formlist[i].value!=''){
						var str=formlist[i].value
						if(escape(str).indexOf("%u")<0){
							list.time_type=2;
							list.end_time=formlist[i].value
						}else{
							list.time_type=1;
							var end_time=formlist[i].value
							end_time=end_time.substr(0,end_time.length-1)
							list.end_time=end_time
						}
					}
				}
				if(list.end_time==''){
					$eb.message('error', '请设置时长');
					return false;
				}
				if(list.reward==''){
					$eb.message('error', '请选择奖励内容');
					return false;
				}
				if(list.reward_explain==''){
					$eb.message('error', '请输入理由');
					return false;
				}
			}
			$.ajax({
				url:"{:Url('set_management')}",
				data:list,
				type:'post',
				dataType:'json',
				success:function(re){
					if(re.code==200){
						$eb.message('success',re.msg);
						setTimeout(function (e) {
							parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						}, 600)
					}else{
						$eb.message('error',re.msg);
					}
				}
			})
		}
		//积分奖励
		if(type=='reward'){
			var list={};
			list.id="{$id}";
			list.type="{$type}";
			list.is_post=1;
			list.time_type='';
			list.end_time='';
			list.reward=numlist;
			list.reward_explain=$("#textarea-reason").val();
			if(list.reward==''){
				$eb.message('error', '请选择奖励内容');
				return false;
			}
			if(list.reward_explain==''){
				$eb.message('error', '请输入理由');
				return false;
			}
			$.ajax({
				url:"{:Url('set_management')}",
				data:list,
				type:'post',
				dataType:'json',
				success:function(re){
					if(re.code==200){
						$eb.message('success',re.msg);
						setTimeout(function (e) {
							parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						}, 600)
					}else{
						$eb.message('error',re.msg);
					}
				}
			})
		}
	})
</script>
{/block}