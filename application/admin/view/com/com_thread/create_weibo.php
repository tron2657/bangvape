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
	.searchBox {
	    position: relative;
	    display: flex;
	    height: 38px;
	    z-index: 1;
	}
	.searchBox .inputBox {
	    width: 100%;
	    padding-left: 15px;
	    padding-right: 30px;
	    color: #595959;
	    background:rgba(245,245,245,1);
	    border-radius: 4px;
	    border: none;
	    font-size:14px;
	    font-family:PingFangSC-Regular,PingFang SC;
	    font-weight:400;
	}
	.searchBox .searchImgBox {
	    position: absolute;
	    right: 26px;
	    line-height: 32px;
	}
	
	.searchBox .searchListBox {
	    position: absolute;
	    width: 184px;
	    max-height: 168px;
	    top: 40px;
	    /* padding: 4px 0; */
	    box-shadow:0px 9px 28px 8px rgba(0,0,0,0.05),0px 6px 16px 0px rgba(0,0,0,0.08),0px 3px 6px -4px rgba(0,0,0,0.12);
	    border-radius:2px;
	    overflow-y:scroll;
	}
	
	
	.searchBox .searchListBox::-webkit-scrollbar{
	    display:none
	}
	
	.searchBox .searchListBox>a {
		display: block;
	    height: 32px;
	    line-height: 32px;
	    padding-left: 12px;
		box-sizing: content-box;
	    font-size: 14px;
	    color:rgba(0,0,0,0.65);
	    background: rgba(255,255,255,1);
	    cursor: pointer;
	}
	
	.searchBox .searchListBox>a:first-child {
	    /* margin-top: 4px; */
		border-top-width: 4px;
		border-top-style: solid;
		border-top-color: #FFFFFF;
	}
	
	.searchBox .searchListBox>a:last-child {
	    /* margin-bottom: 4px; */
		border-bottom-width: 4px;
		border-bottom-style: solid;
		border-bottom-color: #FFFFFF;
	}
	
	.searchBox .searchListBox>a:hover {
	    background:rgba(245,245,245,1);
	}
    .upload-img-box {
        border: 1px solid #E5E6E7;
        padding: 5px 10px;
        margin-right: 5px;
        margin-top: 5px;
        border-radius: 3px;
        position: relative;
        width: 102px;
        height: 92px;
    }

    .upload-img-box-img {
        width: 80px;
        height: 80px;
    }

    .delete-btn {
        display: none;
        position: absolute;
        top: 5px;
        right: 10px;
        width: 80px;
        height: 80px;
        cursor: pointer;
        font-size: 20px;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .delete-btn img {
        width: 30px;
        height: 30px;
    }
</style>
{/block}
{block name="content"}
<div class="panel">
    <div class="panel-body">
        <form class="form-horizontal" id="signupForm">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group" style="display: flex">
                        <span class="input-group-addon" style="width: auto;line-height: 24px">作者</span>
                        {if condition="$style eq 'create'"}
                        <input class="layui-input now_bind_user" readonly id="now_bind_user" value=""
                               style="display: inline-block">
                        <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 38px;">绑定用户</button>
                        {elseif condition="$style eq 'edit_weibo'"/}
                        <input class="layui-input now_bind_user" readonly id="" value="{$info.user}"
                               data-id="{$info.author_uid}" style="display: inline-block">
                        <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 38px;">绑定用户</button>
                        {elseif condition="$style eq 'view_weibo'"/}
                        <input class="layui-input now_bind_user" readonly id="" value="{$info.user}"
                               data-id="{$info.author_uid}" style="display: inline-block">
                        {/if}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">虚拟浏览量</span>
                        {if condition="$style eq 'create'"}
                        <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input"
                               id="false_view" value="" type="number">
                        {elseif condition="$style eq 'edit_weibo'"/}
                        <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input"
                               id="false_view" value="{$info.false_view}" type="number">
                        {elseif condition="$style eq 'view_weibo'"/}
                        <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input"
                               id="false_view" value="{$info.false_view}" readonly type="number">
                        {/if}
                        <input type="hidden" name="id" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group" data-select="$select">
                        <span class="input-group-addon">版块分类</span>
                        {if condition="$style eq 'create'"}
                        <select class="layui-select layui-input" name="fid" id="fid" value="">
                            <option value="">请选择版块分类</option>
                            {volist name="select" id="vo"}
                            <option value="{$vo.id}">{$vo.name}</option>
                            {/volist}
                        </select>
                        {elseif condition="$style eq 'edit_weibo'"/}
                        <select class="layui-select layui-input" name="fid" id="fid" value="{$info.fid}">
                            <option value="">请选择版块分类</option>
                            {volist name="select" id="vo"}
                            {if condition="$vo.id eq $info.fid"}
                            <option value="{$vo.id}" selected>{$vo.name}</option>
                            {else/}
                            <option value="{$vo.id}">{$vo.name}</option>
                            {/if}
                            {/volist}
                        </select>
                        {elseif condition="$style eq 'view_weibo'"/}
                        <select class="layui-select layui-input" name="fid" id="fid" disabled value="{$info.fid}">
                            <option value="">请选择版块分类</option>
                            {volist name="select" id="vo"}
                            {if condition="$vo.id eq $info.fid"}
                            <option value="{$vo.id}" selected>{$vo.name}</option>
                            {else/}
                            <option value="{$vo.id}">{$vo.name}</option>
                            {/if}
                            {/volist}
                        </select>
                        {/if}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">二级分类</span>
                        {if condition="$style eq 'create'"}
                        <select class="layui-select layui-input" name="class_id" id="class_id" value="">
                            <option value="">不选择</option>
                        </select>
                        {elseif condition="$style eq 'edit_weibo'"/}
                        <select class="layui-select layui-input" name="class_id" id="class_id" value="{$info.class_id}">
                            <option value="">不选择</option>
                            {volist name="class" id="v"}
                            {if condition="$v.id eq $info.class_id"}
                            <option value="{$v.id}" selected>{$v.name}</option>
                            {else/}
                            <option value="{$v.id}">{$v.name}</option>
                            {/if}
                            {/volist}
                        </select>
                        {elseif condition="$style eq 'view_weibo'"/}
                        <select class="layui-select layui-input" name="class_id" id="class_id" disabled
                                value="{$info.class_id}">
                            <option value="">未关联分类</option>
                            {volist name="class" id="v"}
                            {if condition="$v.id eq $info.class_id"}
                            <option value="{$v.id}" selected>{$v.name}</option>
                            {else/}
                            <option value="{$v.id}">{$v.name}</option>
                            {/if}
                            {/volist}
                        </select>
                        {/if}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">虚拟发布时间</span>
                        <input type="text" class="layui-input" id="send_time" readonly placeholder="非必填项，适用于特殊场景需求，虚拟发布时间必须早于真实发布时间">
                    </div>
                </div>
            </div>
			<div class="form-group">
			    <div class="col-md-12">
			        <div class="input-group">
			            <span class="input-group-addon">话题标签</span>
						<!-- 搜索框 -->
							<li class="searchBox">
							    <input class="layui-input" autocomplete='off' id="topicInput" name="topicInput" placeholder="话题" oninput="searchtext()">
							    <div class="searchListBox" id="searchList">	<!-- 所搜结果 -->
									<!-- <a class="J_menuItem" href=""></a> -->
							    </div>
							</li>
			
			        </div>
			    </div>
			</div>
            {if condition="$style eq 'create'"}
            <div class="form-group" id="upload_img_content">
                <div class="col-md-12">
                    <div class="input-group" style="display: flex">
                        <span class="input-group-addon" style="width: auto;line-height: 24px;border: none">封面</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: block;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span" id="upload_img_box">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="" type="hidden" id="image_input" name="local_url">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {/if}

            {if condition="$style eq 'edit_weibo'"}
            <div class="form-group" id="upload_img_content">
                <div class="col-md-12">
                    <div class="input-group" style="display: flex">
                        <span class="input-group-addon" style="width: auto;line-height: 24px;border: none">封面</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: block;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span" id="upload_img_box">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="" type="hidden" id="image_input" name="local_url">
                                </div>
                            </a>
                            {volist name="info.image" id="v"}
                            <div class="upload-img-box">
                                <div class="delete-btn"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div>
                                <img class="upload-img-box-img" src="{$v}" alt="">
                            </div>
                            {/volist}
                        </div>
                    </div>
                </div>
            </div>

            {/if}

            {if condition="$style eq 'view_weibo'"}
            <div class="form-group" id="upload_img_content">
                <div class="col-md-12">
                    <div class="input-group" style="display: flex">
                        <span class="input-group-addon" style="width: auto;line-height: 24px;border: none">封面</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            {volist name="info.image" id="v"}
                            <div class="upload-img-box">
                                <!--<div class="delete-btn"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div>-->
                                <img class="upload-img-box-img" src="{$v}" alt="">
                            </div>
                            {/volist}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-group">
                <div class="col-md-12">
                    <label style="color:#aaa">动态内容</label>
                    {if condition="$style eq 'create'"}
                    <textarea id="text_content" style="width:100%;height: 300px;resize:none;padding: 5px;"></textarea>
                    {elseif condition="$style eq 'edit_weibo'"/}
                    <textarea id="text_content" style="width:100%;height: 300px;resize:none;padding: 5px;">{$info.content}</textarea>
                    {elseif condition="$style eq 'view_weibo'"/}
                    <textarea readonly style="width:100%;height: 300px;resize:none;padding: 5px;">{$info.content}</textarea>
                    {/if}
                </div>
            </div>
            {if condition="$style eq 'create'"}
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="input-group" style="display: flex;width: 100%">
                        <label style="display: flex;align-items: center;width: 120px;">
                            <input type="checkbox" id="recommend_to_channel" name="recommend_to_channel" style="margin-top: 0;margin-right: 5px;">同步到频道
                        </label>
                        <div id="select_channel_block" style="width: 550px; display: none;">
                            <input type="hidden" name="to_channel_ids" id="to_channel_ids" value="">
                            <div class="col-sm-12">
                                <style>
                                    .bind_channel_label{
                                        border: 1px solid #e5e6e7;
                                        height: 38px;
                                        line-height: 36px;
                                        overflow: hidden;
                                        margin-left: -15px;
                                        width: 400px;
                                        overflow: hidden;
                                        padding-left: 5px;
                                    }
                                    .bind_channel_label span{
                                        display: inline-block;
                                        line-height: 20px;
                                        padding: 2px 10px;
                                        margin-right: 5px;
                                        border-radius: 5px;
                                        color: #848484;
                                        font-weight: 400;
                                        border: 1px solid#cecece;
                                    }
                                    .bind-channel{
                                        height: 38px;
                                        margin-left: -5px;
                                        margin-top: -42px;
                                        background-color: #bbb3b3;
                                    }
                                    .bind-channel:hover,.bind-channel:active,.bind-channel:focus{
                                        background-color: #bbb3b3!important;
                                        border-color: #bbb3b3!important;
                                    }
                                </style>
                                <label class="bind_channel_label now_channel_title">
                                    <div style="color: #bbb3b3;font-weight: 400;">点击右侧按钮选择</div>
                                </label>
                                <button type="button" class="btn btn-w-m btn-info bind-channel" style="background-color: #0CA6F2;">选择频道</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-9">
                        {if condition="$style eq 'create'"}
                        <button type="button" class="btn btn-w-m btn-info save_news">发布</button>
                        {elseif condition="$style eq 'edit_weibo'"/}
                        <button type="button" class="btn btn-w-m btn-info save_news">保存</button>
                        {elseif condition="$style eq 'view_weibo'"/}
                        {/if}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{/block}
{block name="script"}
<script>
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
        var D = date.getDate() + ' ';
        var h = date.getHours() + ':';
        var m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes()) + ':';
        var s = (date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds());
        return Y+M+D+h+m+s;
    }
  $.ajax({
    url: "{:Url('get_user')}",
    data: {},
    type: 'get',
    dataType: 'json',
    success: function (res) {
      if (res.code == 200) {
        if (res.data) {
          $("#now_bind_user").val(res.data.nickname);
          $("#now_bind_user").attr('data-id', res.data.uid);
        }
      } else {
        Toast.error(res.msg);
      }
    }
  });
  window.addEventListener("storage", function (e) {
    if (e.key === "bind_username") {
      $(".now_bind_user").val(e.newValue);
      window.localStorage.removeItem("bind_username")
    } else if (e.key === "add_goods_val") {
      var goodsVal = e.newValue;
      goodsVal = JSON.parse(goodsVal);
      ue.focus();
      ue.execCommand('inserthtml', '<div class="product-box" data-id="' + goodsVal.id + '" style="display: flex;border: 1px solid #000;padding: 10px;margin-bottom: 5px"><img src=' + goodsVal.img + ' width="155" height="110"/><div class="product-info" style="margin-left: 20px;"><div class="product-name" style="height: 86px;">' + goodsVal.name + '</div><div class="product-price">￥' + goodsVal.price + '</div></div></div>');
      window.localStorage.removeItem("add_goods_val")
    } else if (e.key === "bind_userId") {
      $(".now_bind_user").attr('data-id', e.newValue);
      window.localStorage.removeItem("bind_userId")
    }else if(e.key === "bind_channel_title"){
        var channel_title=JSON.parse(e.newValue);
        var channel_html='';
        $(".now_channel_title").empty();
        for(var i=0;i<100;i++){
            if(channel_title[i]==undefined) {
                break;
            }
            channel_html='<span>'+channel_title[i]+'</span>';
            $(".now_channel_title").append(channel_html);
        }
        window.localStorage.removeItem("bind_channel_title")
    } else if (e.key === "bind_channel_ids") {
        var channel_ids=JSON.parse(e.newValue);
        if(channel_ids[0]!=undefined){
            $('#to_channel_ids').val(channel_ids.join(","));
        }
        window.localStorage.removeItem("bind_channel_ids")
    }
  });
    $('#recommend_to_channel').change(function () {
        $('#select_channel_block').toggle();
    })

    $(".bind-channel").on("click", function () {
        $eb.createModalFrame("同步到频道-选择频道", '{:Url('channel.index/recommend_to_channel')}',{w: 800, h: 400})
    });
  $("#fid").change(function () {
    $.ajax({
      url: "{:Url('select_class')}",
      data: {id: $("#fid").val()},
      type: 'post',
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          var optionHtml = '<option value="">不选择</option>';
          for (var i in res.data) {
            optionHtml += '<option value=' + res.data[i].id + '>' + res.data[i].name + '</option>'
          }
          $("#class_id").html(optionHtml)
        } else {

        }
      }
    })
  })

  function hasContent() {
    return (UM.getEditor('myEditor').hasContents());
  }

  function createFrame(title, src, opt) {
    opt === undefined && (opt = {});
    return layer.open({
      type: 2,
      title: title,
      area: [(opt.w || 700) + 'px', (opt.h || 650) + 'px'],
      fixed: false, //不固定
      maxmin: true,
      moveOut: false,//true  可以拖出窗外  false 只能在窗内拖
      anim: 5,//出场动画 isOutAnim bool 关闭动画
      offset: 'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
      shade: 0,//遮罩
      resize: true,//是否允许拉伸
      content: src,//内容
      move: '.layui-layer-title'
    });
  }


  $(".bind-user").on("click", function () {
    $eb.createModalFrame("绑定用户", '{:Url('bind_user_vim')}',{w: document.body.clientWidth, h: document.body.clientHeight})
  });
  var autoImg = 1;
  $("input[name='show']").on("change", function () {
    var change = $("input[type='checkbox']").is(':checked'); //checkbox选中判断
    if (change) {
      $("#upload_img_content").hide();
      autoImg = 1;
    } else {
      $("#upload_img_content").show();
      autoImg = 0
    }
  });

  $("body").on("mouseover mouseout", ".upload-img-box", function (event) {
    if (event.type === "mouseover") {
      $(this).find("div").css("display", "flex");
    } else if (event.type === "mouseout") {
      $(this).find("div").css("display", "none");
    }
  });

  var imgVal = [];
  $("body").on("click", ".delete-btn", function () {
    var index = $(this).parent().index();
    imgVal.splice(index-1, 1);
    var imgInputVal = imgVal.join(",");
    $('#image_input').val(imgInputVal);
    $(this).parent().remove();
    $("#upload_img_box").show();
  });


  function changeIMG(index, pic) {
    $("#img_content").append('<div class="upload-img-box"><div class="delete-btn"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div><img class="upload-img-box-img" src=' + pic + '></div>');
    if(imgVal.length === 9){
      $eb.message('error', '最多上传9张');
      $("#upload_img_box").hide();
      return;
    }
    imgVal.push(pic);
    var imgInputVal = imgVal.join(",");
    $('#image_input').val(imgInputVal);
    if(imgVal.length === 9){
      $("#upload_img_box").hide();
    }
  }
	
	// 搜索框
	function searchtext(){ 
	    var inputVal =$("#topicInput").val()
	    var inBox = document.getElementById("topicInput");
	    var sList = document.getElementById("searchList");
	    clearTimeout();
		$('#searchList').empty()
		var item = null;
		item = document.createElement('a');
		//item.innerHTML = '加载中';
		//sList.appendChild(item);
		$('#searchList>a').css({
		        "text-align":"center",
		        "padding-left":"0"
		    })
		// input输入框有值的时候请求
	    if(inputVal != '') {
			console.log(inputVal)
			setTimeout(function(){
				$.ajax({
					url: '/admin/com.com_topic/search_topic',
					data: {search:inputVal},
					type: 'GET',
					dataType: 'json',
					success: function(res) {
						// console.log(res,'res');
						sList.innerHTML = '';
						console.log(res)
						var len = res.data.length;
						// 有无搜索结果
						if(len == 0) {
							item = document.createElement('a');
							item.innerHTML = "新话题 " + "#" + inputVal + "#";
							sList.appendChild(item);
							$('#searchList>a').css({
								"color":"#1890FF",
							})
						}else {
							// console.log(res.count,'res.count');
							let data = res.data;
							let dataFilter = data.filter(function (x) {
							    return x.have_menu != 1;
							});
							console.log(dataFilter,'dataFilter');
							let match =false;
							for(var i=0;i<dataFilter.length;i++){
								if (dataFilter[i].title === inputVal){
									match = true
								}
							}
							if (!match){
								item = document.createElement('a');
								item.innerHTML = "新话题 " + "#" + inputVal + "#";
								sList.appendChild(item);
								$('#searchList>a').css({
									"color":"#1890FF",
								})
							}
							for(var i=0;i<dataFilter.length;i++){
								item = document.createElement('a');
								item.innerHTML = "#" + dataFilter[i].title + "#" ;
								// console.log(countFilter[i].params,typeof countFilter[i].params,'111'); 
								
								// console.log(item.href);
								sList.appendChild(item);
								$('#searchList a').addClass('J_menuItem');
							}
						}
					},
				})
			},500);
	    }else {
			// $('.searchListBox').empty()
			setTimeout(function(){
				item =null;
				$('#searchList').empty();
				console.log('无内容')
				clearTimeout();
				return
			},500);
	    }
	}
		$("#topicInput").focus(function(event){
			console.log('inputBox聚焦’')
			searchtext();
			$(window).on('scrollstop', function(){
				console.log('scrollstop’')
			})
			event.stopPropagation();
		});  
		
		$("#topicInput").click(function(event){
			console.log('inputBox点击’')
			searchtext();
			$('#searchList').empty();
			event.stopPropagation();
		});  
		$("#searchList").click(function(event){
			console.log(event)
			$("#topicInput").val(event.target.innerText.replace(/#/g,"").replace("新话题 ",""))
			
		});
		/* $(".inputBox").blur(function(){
			console.log(321)
			// if(document.querySelector(".inputBox").value != ''){
			// 	$(".searchListBox").hide();
			// }else {
			// 	$('.searchListBox').empty()
			// }
		 }); */
		
		$(document).click(function(){
		     console.log('离开input')
		     if($("#topicInput").val() != ''){
				 console.log('离开hide')
		     	$("#searchList").empty();
		     }else {
				 console.log('离开empty')
		     	$('#searchList').empty()
		     }
		});
	
  /**
   * 上传图片
   * */
  $('.upload_span').on('click', function (e) {
    createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image');
  });
</script>
{if condition="$style eq 'create'"}
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
            elem: '#send_time', //指定元素
            type:'datetime',
            max:timestampToTime(new Date().getTime()/1000)
        });
    });
  $('.save_news').on('click', function () {
      var sendTime = $('#send_time').val();
    var list = {};
    list.type = 1;
    list.from = "HouTai";
    list.is_weibo = 1;
    list.false_view = $('#false_view').val();/* 虚拟浏览量 */
    list.image = $('#image_input').val();/* 封面 */
    list.author_uid = $('.now_bind_user').data("id");/* 作者 */
    list.content = $("#text_content").val();/* 内容 */
      list.send_time = dataToStamp(sendTime);/* 推送时间 */
    list.fid = $("#fid option:selected").val();
    list.class_id = $("#class_id option:selected").val();
	list.topic = $("#topicInput").val();
    //list.is_auto_image = autoImg;
    var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
    var objExp = new RegExp(Expression);
    if (list.author_uid == '') {
      $eb.message('error', '请输入作者');
      return false;
    }
    if (list.fid == '') {
      $eb.message('error', '请选择版块分类');
      return false;
    }
    if (list.content == '') {
      $eb.message('error', '请输入内容');
      return false;
    }

      if($('#recommend_to_channel').is(':checked')){
          list.recommend_to_channel_ids=$('#to_channel_ids').val();
          if(list.recommend_to_channel_ids==''){
              $eb.message('error', '请选择同步到哪些频道');
              return false;
          }
      }

    var data = {};
    $.ajax({
      url: "{:Url('add_weibo')}",
      data: list,
      type: 'post',
      dataType: 'json',
      success: function (re) {
        if (re.code == 200) {
          data[re.data] = list;
          $('.type-all>.active>.new-id').val(re.data);
          $eb.message('success', re.msg);
          setTimeout(function (e) {
            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
            parent.layer.close(parent.layer.getFrameIndex(window.name));
          }, 600)
        } else {
          $eb.message('error', re.msg);
        }
      }
    })
  });
</script>
{elseif condition="$style eq 'edit_weibo'"/}
{volist name="info.image" id="v"}
<script>
  imgVal.push("{$v}")
</script>
{/volist}
<script>
    var sendTime = "{$info.send_time}";
    var sendTimeDate = timestampToTime(sendTime);
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
            elem: '#send_time', //指定元素
            type:'datetime',
            value: sendTimeDate,
            max:timestampToTime(new Date().getTime()/1000)
        });
    });
  var imgInputVal = imgVal.join(",");
  $('#image_input').val(imgInputVal);

  $('.save_news').on('click', function () {
      var sendTime = $('#send_time').val();
    var list = {};
    list.id = '{$info.id}';
    list.type = 1;
    list.is_weibo = 1;
    list.from = newFrom;
    list.image = $('#image_input').val();/* 封面 */
    list.false_view = $('#false_view').val();/* 虚拟浏览量 */
    list.author_uid = $('.now_bind_user').data("id");/* 作者 */
    list.content = $("#text_content").val();/* 内容 */
      list.send_time = dataToStamp(sendTime);/* 推送时间 */
    list.fid = $("#fid option:selected").val();
    list.class_id = $("#class_id option:selected").val();
	list.topic = $("#topicInput").val();
    //list.is_auto_image = autoImg;
    var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
    var objExp = new RegExp(Expression);
    if (list.author_uid == '') {
      $eb.message('error', '请输入作者');
      return false;
    }
    if (list.fid == '') {
      $eb.message('error', '请选择版块分类');
      return false;
    }
    if (list.content == '') {
      $eb.message('error', '请输入内容');
      return false;
    }
    var data = {};
    $.ajax({
      url: "{:Url('edit_thread')}",
      data: list,
      type: 'post',
      dataType: 'json',
      success: function (re) {
        if (re.code == 200) {
          data[re.data] = list;
          $('.type-all>.active>.new-id').val(re.data);
          $eb.message('success', re.msg);
          setTimeout(function (e) {
            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
            parent.layer.close(parent.layer.getFrameIndex(window.name));
          }, 600)
        } else {
          $eb.message('error', re.msg);
        }
      }
    })
  });
</script>
{elseif condition="$style eq 'view_weibo'"/}
<script>
    var sendTime = "{$info.send_time}";
    var sendTimeDate = format(sendTime*1000);
    $("#send_time").val(sendTimeDate);
</script>
{/if}

{if condition="$style eq 'edit_weibo'"}
{if condition="$info.from eq 'HouTai'"}
<script>
  var newFrom = "HouTai"
</script>
{else/}
<script>
  var newFrom = "{$info.from}"
</script>
{/if}
{else/}
<script>
  var newFrom = "HouTai"
</script>
{/if}
{/block}