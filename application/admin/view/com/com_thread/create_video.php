{extend name="public/container"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}js/filereader.js"></script>

<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<link href="{__PLUG_PATH}layui2.5.5/css/layui.css" rel="stylesheet">
<script src="{__PLUG_PATH}layui2.5.5/layui.js"></script>

<script src="/application/admin/view/column/column_textns/vod-js-sdk-v6.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<style>
.searchBox {
	    position: relative;
	    display: flex;
	    height: 38px;
	    z-index: 999;
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
  .cover-box {
    width: 102px;
    border: solid 1px #E5E6E7;
  }
  #show-img {
    width: 88px;
    height: 75px;
  }
</style>
<script type="text/javascript" src="{__PLUG_PATH}axios.min.js"></script>
{/block}
{block name="content"}
<div class="row" style="width: 100%;margin-left: 0;">
   <div class="col-sm-12" style="background-color: #fff">
       <div class="panel-body">
           <form class="form-horizontal" id="signupForm">
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group">
                           <span class="input-group-addon">标题</span>
                           {if condition="$style eq 'create_video'"}
                           <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" value="">
                           {elseif condition="$style eq 'edit_video'"/}
                           <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" value="{$info.title}">
                           {elseif condition="$style eq 'view_video'"/}
                           <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" readonly value="{$info.title}">
                           {/if}
                           <input type="hidden" name="id" value="">
                       </div>
                   </div>
               </div>
               {if condition="$style neq 'create_video'"}
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group">
                           {if condition="$style neq 'create_video'"}
                           <span class="input-group-addon">虚拟浏览量</span>
                           {/if}
                           {if condition="$style eq 'create_video'"}
                           <!-- <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input" id="false_view" value="" type="number"> -->
                           {elseif condition="$style eq 'edit_video'"/}
                           <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input" id="false_view" value="{$info.false_view}" type="number">
                           {elseif condition="$style eq 'view_video'"/}
                           <input maxlength="64" placeholder="请输入虚拟浏览量，不输入默认为0" name="false_view" class="layui-input" id="false_view" value="{$info.false_view}" readonly type="number">
                           {/if}
                           <input type="hidden" name="id" value="">
                       </div>
                   </div>
               </div>
               {/if}
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group" style="display: flex">
                           <span class="input-group-addon" style="width: auto;line-height: 24px">作者</span>
                           {if condition="$style eq 'create_video'"}
                           <input class="layui-input now_bind_user" readonly id="now_bind_user" value="" style="display: inline-block">
                           <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 38px;">绑定用户</button>
                           {elseif condition="$style eq 'edit_video'"/}
                           <input class="layui-input now_bind_user" readonly id="" value="{$info.user}" data-id="{$info.author_uid}" style="display: inline-block">
                           <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 38px;">绑定用户</button>
                           {elseif condition="$style eq 'view_video'"/}
                           <input class="layui-input now_bind_user" readonly id="" value="{$info.user}" data-id="{$info.author_uid}" style="display: inline-block">
                           {/if}
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group">
                           <!-- <span class="input-group-addon">封面(225*150)</span> -->
                           {if condition="$style eq 'create_video'"}
                           <span class="input-group-addon">上传视频</span>
                            <div style="display: flex;align-items: flex-end">
                            <input type="file" class="upload" name="image" style="display: none;" id="image" />
                           <a style="display: block;width: 102px;border: 1px solid #E5E6E7;" class="btn-sm add_image upload_span">
                               <div id="video-box" class="upload-image-box transition" style="height: 80px;background-repeat:no-repeat;background-size:80px 70px;background-image:url('/public/system/module/wechat/news/images/video.png');
                                    background-position: center;word-break: break-all">
                                    <div style="display: none" id="content-box"><span>已选择 " </span><span id="content" style="color: red"></span><span> "</span></div>
                                    <input type="file" style="position: absolute;
                                      opacity: 0;
                                      left: 89px;
                                      top: 7px;
                                      width: 100px;
                                      height: 80px;" id="video-file" class="video-file">
                                   <input value="" type="hidden" id="video_input" name="local_url">
                                    <div class="del" style="background-image:url('/public/system/module/wechat/news/images/delete.png');height: 20px;
                                        width: 20px;
                                        background-size: contain;
                                        position: absolute;
                                        top: -5px;
                                        left: 175px;
                                        display: none"></div>
                               </div>
                           </a>
			                    <div type="button" class="layui-btn layui-btn-normal" id="qcloud-video-upload" style="margin-left: 30px;">
				                    开始上传
			                    </div>
                            </div>
                            <div class="layui-progress layui-progress-big" lay-filter="demo" style="height: 4px;width: 102px;position: absolute;display: none" id="progress-tipe">
		                          <div class="layui-progress-bar layui-bg-blue" lay-percent="0%" style="height: 100%"></div>
	                          </div>
                           {elseif condition="$style eq 'edit_video'"/}
                           
                           {elseif condition="$style eq 'view_video'"/}
                           
                           {/if}
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group">
                           <!-- <span class="input-group-addon">封面(225*150)</span> -->
                           {if condition="$style eq 'create_video' and in_array('0', $video_cover_type)"}
                           <span class="input-group-addon">上传封面</span>
                           <input type="file" class="upload upload-cover" name="image" id="image" style="display: none"/>
                           <div class="btn-sm cover-box">
                           <input type="file" class="upload" name="image" style="display: none;" id="image" />
                                <a style="display: block;padding: 0" class="btn-sm add_image upload_span upload_click">
                                    <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                        <input value="" type="hidden" id="image_input" name="local_url" />
                                    </div>
                                </a>
                           </div>
                           <!-- <a style="display: block;width: 102px;border: 1px solid #E5E6E7;" class="btn-sm add_image upload_span" id="upload_img"> -->
                               <!-- <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:80px 80px;background-image:url('/public/system/module/wechat/news/images/image.png');background-size: 100px 85px;
                                    background-position: -9px -3px;">
                                   <input value="" type="hidden" id="image_input" name="local_url">
                               </div> -->
                           <!-- </a> -->
                           {elseif condition="$style eq 'edit_video'"/}
                           <span class="input-group-addon">上传封面</span>
                           <input type="file" class="upload upload-cover" name="image" id="image" style="display: none"/>
                           <div class="btn-sm cover-box">
                           <input type="file" class="upload" name="image" style="display: none;" id="image" />
                                <a style="display: block;padding: 0" class="btn-sm add_image upload_span upload_click">
                                    <div class="upload-image-box transition image_img" style='height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url("{$info.video_cover}")'>
                                        <input value="" type="hidden" id="image_input" name="local_url" />
                                    </div>
                                </a>
                           </div>
                           {elseif condition="$style eq 'view_video'"/}
                           <input type="file" class="upload" name="image" style="display: none;" id="image" />
                           <a style="display: block;width: 102px;border: 1px solid #E5E6E7;cursor: default" class="btn-sm add_image" >
                               <div class="upload-image-box transition image_img" style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('{$info.cover}')">
                                   <input value="{$info.cover}" type="hidden" id="image_input" name="local_url">
                               </div>
                           </a>
                           {/if}
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-md-12">
                       <div class="input-group" data-select="$select">
                           <span class="input-group-addon">版块分类</span>
                           {if condition="$style eq 'create_video'"}
                           <select class="layui-select layui-input" name="fid" id="fid">
                               <option value="">请选择版块分类</option>
                               {volist name="select" id="vo"}
                               <option value="{$vo.id}">{$vo.name}</option>
                               {/volist}
                           </select>
                           {elseif condition="$style eq 'edit_video'"/}
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
                           {elseif condition="$style eq 'view_video'"/}
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
                           {if condition="$style eq 'create_video'"}
                           <select class="layui-select layui-input" name="class_id" id="class_id">
                               <option value="">不选择</option>
                           </select>
                           {elseif condition="$style eq 'edit_video'"/}
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
                           {elseif condition="$style eq 'view_video'"/}
                           <select class="layui-select layui-input" name="class_id" id="class_id" disabled value="{$info.class_id}">
                               <option value="">不选择</option>
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
               
               <div class="form-group">
                   <div class="col-md-12">
                       <label style="color:#aaa">内容</label>
                       {if condition="$style eq 'create_video'"}
                       <textarea type="text/plain" id="myEditor" style="width:100%;z-index:1"></textarea>
                       {elseif condition="$style eq 'edit_video'"/}
                       <textarea type="text/plain" id="myEditor" style="width:100%;z-index:1"></textarea>
                       {elseif condition="$style eq 'view_video'"/}
                       <textarea type="text/plain" id="myEditor" style="width:100%;z-index:1"></textarea>
                       {/if}
                   </div>
               </div>
               {if condition="$style eq 'create_video'"}
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
                                   <button type="button" class="btn btn-w-m btn-info bind-channel">选择频道</button>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               {/if}
               <div class="form-actions">
                   <div class="row">
                       <div class="col-md-offset-4 col-md-9">
                           {if condition="$style eq 'create_video'"}
                           <button type="button" class="btn btn-w-m btn-info save_news">发布</button>
                           {elseif condition="$style eq 'edit_video'"/}
                           <button type="button" class="btn btn-w-m btn-info save_news">保存</button>
                           {elseif condition="$style eq 'view_video'"/}

                           {/if}
                       </div>
                   </div>
               </div>
           </form>
       </div>
   </div>
</div>
{/block}
{block name="script"}
<script src="{__MODULE_PATH}widget/file.js"></script>
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
// 后台发布视频
let mediaFile;
let video_info;
let coverFile;
$('#video-file').on('change', function() {
    if (document.getElementById("video-file").files[0].type.split('/')[0] == 'video') {
        mediaFile = document.getElementById("video-file").files[0];
        $('#video-box').css('background-image', '');
        $('#content-box').css('display', 'block');
        $('#content').text(document.getElementById("video-file").files[0].name);
        $('.del').css('display', 'block');
        console.log('123', mediaFile)
        // var fr = new FileReader();
        // fr.readAsDataURL(mediaFile);  // 将文件读取为Data URL
        // fr.onload = function(e) {
        //       var result = e.target.result;
        //       var video = $('<video src="' + result + '">');
        //       $('#video-box').css('background-image', '');
        //       $('#content-box').css('display', 'block');
        //       $('#content').text(document.getElementById("video-file").files[0].name);
        //       $('.del').css('display', 'block');
        //       $('#content-box').html('').append(video);
        //       console.log('123', mediaFile)
        //   }
    } else {
      $('#video-file').val('')
      $eb.message('error', '请选择视频上传！')
    }
})
// $('#image-chose').on('change', function() {
//   if (document.getElementById("image-chose").files[0].type.split('/')[0] == 'image') {
//     coverFile = document.getElementById("image-chose").files[0]
//     if (coverFile) {
//         var reader = new FileReader();
//         reader.readAsDataURL(coverFile);//异步读取文件内容，结果用data:url的字符串形式表示
//         /*当读取操作成功完成时调用*/
//         reader.onload = function(e) {
//         console.log(e); //查看对象属性里面有个result属性，属性值，是一大串的base64格式的东西，这个就是我们要的图片
//         console.log(this.result);//取得数据 这里的this指向FileReader（）对象的实例reader
//         $('#show-img').attr("src", this.result)//赋值给img标签让它显示出来 
//       }
//     }
//   } else {
//     $eb.message('error', '请选择封面上传！')
//   }
// }) 
$('.del').on('click', function() {
  $('#video-box').css('background-image', "url('/public/system/module/wechat/news/images/video.png')");
  $('#content-box').css('display', 'none');
  $('#video-file').val('')
  $('.del').css('display', 'none');
  $('#progress-tipe').css('display', 'none');
  $('#qcloud-video-upload').css('display', 'block');
})

$("#topicInput").each(function(){
    $topicInput=$(this);
    {  
        $topicInput.focus(function(event){
            console.log('inputBox聚焦’')
            searchtext();
            $(window).on('scrollstop', function(){
                console.log('scrollstop’')
            })
            event.stopPropagation();
        }); 
        $topicInput.click(function(event){
            console.log('inputBox点击’')
            searchtext();
            $('#searchList').empty();
            event.stopPropagation();
        }); 
    }
   

    $searchList= $("#searchList");
    {
        $searchList.click(function(event){
            $topicInput.val(event.target.innerText.replace(/#/g,"").replace("新话题 ",""))            
        });
    }
   
    $(document).click(function(){
        if($topicInput.val() != ''){
            $searchList.empty();
        }else {
            $searchList.empty();
        }
    });

});
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

		

// 获取签名
function getSignature() {
  return axios.post('/commonapi/Index/createSignature').then(function (response) {
    return response.data.data.signature;
  })
};

$('#qcloud-video-upload').click(function() {
    console.log(mediaFile)
    if(typeof(mediaFile) === 'undefined'){
        $eb.message('error',"请选择视频");
        return;
    }
    var tcVod = new TcVod.default({
        getSignature: getSignature
    })
    var uploader = tcVod.upload({
        mediaFile: mediaFile, 
        coverFile: coverFile
    })
    $('#progress-tipe').css('display', 'block');
    uploader.on('media_progress', function(info) {
        console.log(info.percent) // 进度
        var n = info.percent * 100;
        var percent = n + '%'
        layui.use('element', function(){
            var element = layui.element;
            element.progress('demo', percent);
            $('#percent').attr('lay-percent', percent)
        });
    })
    uploader.done().then(function(doneResult) {
        video_info = doneResult;
        $('#qcloud-video-upload').css('display', 'none');
        console.log(669,video_info)
        $eb.message('success',"上传成功");
    })
    .catch(function (err) {
        $eb.message('error',"上传失败");
    })           
})

  $.ajax({
    url:"{:Url('get_user')}",
    data:{},
    type:'get',
    dataType:'json',
    success:function(res){
      if(res.code == 200){
        if(res.data){
          console.log(res)
          $("#now_bind_user").val(res.data.nickname);
          $("#now_bind_user").attr('data-id',res.data.uid);
          $("#now_bind_user").attr('data-id',res.data.uid);
          // $("#image_input").attr('data-id',res.data.uid);
        }
      }else{
        Toast.error(res.msg);
      }
    }
  });
  window.addEventListener("storage", function (e) {
    if(e.key === "bind_username"){
      $(".now_bind_user").val(e.newValue);
      window.localStorage.removeItem("bind_username")
    }else if(e.key === "bind_userId"){
      $(".now_bind_user").attr('data-id',e.newValue);
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
      url:"{:Url('select_class')}",
      data:{id:$("#fid").val()},
      type:'post',
      dataType:'json',
      success:function(res){
        console.log(res)
        if(res.code == 200){
          var optionHtml = '<option value="">不选择</option>';
          for(var i in res.data){
            optionHtml += '<option value='+res.data[i].id+'>'+res.data[i].name+'</option>'
          }
          $("#class_id").html(optionHtml)
        }else{

        }
      }
    })
  })
  $(".bind-user").on("click",function () {
    $eb.createModalFrame("绑定用户",'{:Url('bind_user_vim')}',{w:document.body.clientWidth,h:document.body.clientHeight})
  });
            var ue = UE.getEditor('myEditor',{
              autoHeightEnabled: false,
              initialFrameHeight: 400,
              wordCount: false,
              maximumWords: 100000,
              zIndex:1
            });
            /**
            * 获取编辑器内的内容
            * */
            function getContent() {
                return (ue.getContent());
            }
            function hasContent() {
                return (UM.getEditor('myEditor').hasContents());
            }
            function createFrame(title,src,opt){
                opt === undefined && (opt = {});
                return layer.open({
                    type: 2,
                    title:title,
                    area: [(opt.w || 700)+'px', (opt.h || 650)+'px'],
                    fixed: false, //不固定
                    maxmin: true,
                    moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
                    anim:5,//出场动画 isOutAnim bool 关闭动画
                    offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                    shade:0,//遮罩
                    resize:true,//是否允许拉伸
                    content: src,//内容
                    move:'.layui-layer-title'
                });
            }
            function changeIMG(index,pic){
                $(".image_img").css('background-image',"url("+pic+")");
                $(".active").css('background-image',"url("+pic+")");
                $('#image_input').val(pic);
            };
            /**
             * 上传图片
             * */
            $('#image-chose').on('click',function (e) {
            // $('.upload').trigger('click');
                createFrame('选择图片','{:Url('widget.images/index')}?fodder=image');
            })
            /**
             *上传视频
             **/
      //       $('#upload_video').on('click',function (e) {
      //       // $('.upload').trigger('click');
      //           createFrame('选择视频','{:Url('widget.videos/index')}?fodder=image');
			// })



            $('.article-add ').on('click',function (e) {
                var num_div = $('.type-all').children('div').length;
                if(num_div > 7){
                  $eb.message('error','一组图文消息最多可以添加8个');
                  return false;
                }
                var url = "/public/system/module/wechat/news/images/image.png";
                html = '';
                html += '<div class="news-item transition active news-image" style=" margin-bottom: 20px;background-image:url('+url+')">'
                    html += '<input type="hidden" name="new_id" value="" class="new-id">';
                    html += '<span class="news-title del-news">x</span>';
                html += '</div>';
                $(this).siblings().removeClass("active");
                $(this).before(html);
            })
            $(document).on("click",".del-news",function(){
                $(this).parent().remove();
            })
            $(document).ready(function() {
                var config = {
                    ".chosen-select": {},
                    ".chosen-select-deselect": {allow_single_deselect: true},
                    ".chosen-select-no-single": {disable_search_threshold: 10},
                    ".chosen-select-no-results": {no_results_text: "沒有找到你要搜索的分类"},
                    ".chosen-select-width": {width: "95%"}
                };
                for (var selector in config) {
                    $(selector).chosen(config[selector])
                }
            })
        </script>
    {if condition="$style eq 'create_video'"}
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

        $('.upload_click').on('click', function(e) {
            //                $('.upload').trigger('click');
            createFrame('选择图片','{:Url('widget.images/index')}?fodder=image'); 
        })
        function changeIMG(index, pic) {
            $(".image_img").css('background-image', "url(" + pic + ")");
            $(".active").css('background-image', "url(" + pic + ")");
            $('#image_input').val(pic);
        };
        function createFrame(title, src, opt) {
            opt === undefined && (opt = {});
            return layer.open({
                type: 2,
                title: title,
                area: [(opt.w || 720) + 'px', (opt.h || 500) + 'px'],
                fixed: false, //不固定
                maxmin: true,
                moveOut: false, //true  可以拖出窗外  false 只能在窗内拖
                anim: 5, //出场动画 isOutAnim bool 关闭动画
                offset: 'auto', //['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                shade: 0, //遮罩
                resize: true, //是否允许拉伸
                content: src, //内容
                move: '.layui-layer-title'
            });
        }
      /**
       * 提交图文
       * */
      $('.save_news').on('click',function(){
          console.log(123)
          console.log(video_info)
        $('.save_news').attr('disabled', 'disabled');
        var sendTime = $('#send_time').val();
        var list = {};
        list.title = $('#title').val();/* 标题 */
        list.false_view = $('#false_view').val();/* 虚拟浏览量 */
        list.author_uid = $('.now_bind_user').data("id");/* 作者 */
        list.content = getContent();/* 内容 */
        list.send_time = dataToStamp(sendTime);/* 推送时间 */
          if(typeof(video_info) === "undefined"){
              $eb.message('error','请上传视频');
              $('.save_news').removeAttr('disabled');
              return false;
          }
        list.video_url = video_info.video.url;/* 视频url */
        list.video_id = video_info.fileId;/* 视频url */
        list.fid= $("#fid option:selected").val();
        list.class_id= $("#class_id option:selected").val();
        list.video_cover = $("#image_input").val();/* 图片*/
        list.topic = $("#topicInput").val();
        var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
        var objExp=new RegExp(Expression);
        if(list.title == ''){
          $eb.message('error','请输入标题');
            $('.save_news').removeAttr('disabled');
          return false;
        }
        if(list.author_uid == ''){
          $eb.message('error','请输入作者');
            $('.save_news').removeAttr('disabled');
          return false;
        }
        if (!list.video_url) {
          $eb.message('error', '请选择一个视频并上传！')
            $('.save_news').removeAttr('disabled');
          return false
        }
        if(list.fid == ''){
          $eb.message('error','请选择版块分类');
            $('.save_news').removeAttr('disabled');
          return false;
        }
        if(list.content == ''){
          $eb.message('error','请输入内容');
            $('.save_news').removeAttr('disabled');
          return false;
        }
          if($('#recommend_to_channel').is(':checked')){
              list.recommend_to_channel_ids=$('#to_channel_ids').val();
              if(list.recommend_to_channel_ids==''){
                  $eb.message('error', '请选择同步到哪些频道');
                  $('.save_news').removeAttr('disabled');
                  return false;
              }
          }
        var data = {};
        $.ajax({
          url:"{:Url('add_video')}",
          data:list,
          type:'post',
          dataType:'json',
          success:function(re){
            if(re.code == 200){
              $('.save_news').removeAttr('disabled');
              data[re.data] = list;
              $('.type-all>.active>.new-id').val(re.data);
              $eb.message('success',re.msg);
              setTimeout(function (e) {
                parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                parent.layer.close(parent.layer.getFrameIndex(window.name));
              },600)
            }else{
              $('.save_news').removeAttr('disabled');
              $eb.message('error',re.msg);
            }
          }
        })
      });
    </script>
    {elseif condition="$style eq 'edit_video'"/}
    <script>
         $('.upload_click').on('click', function(e) {
            //                $('.upload').trigger('click');
            createFrame('选择图片','{:Url('widget.images/index')}?fodder=image'); 
        })
        function changeIMG(index, pic) {
            $(".image_img").css('background-image', "url(" + pic + ")");
            $(".active").css('background-image', "url(" + pic + ")");
            $('#image_input').val(pic);
        };
        function createFrame(title, src, opt) {
            opt === undefined && (opt = {});
            return layer.open({
                type: 2,
                title: title,
                area: [(opt.w || 720) + 'px', (opt.h || 500) + 'px'],
                fixed: false, //不固定
                maxmin: true,
                moveOut: false, //true  可以拖出窗外  false 只能在窗内拖
                anim: 5, //出场动画 isOutAnim bool 关闭动画
                offset: 'auto', //['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                shade: 0, //遮罩
                resize: true, //是否允许拉伸
                content: src, //内容
                move: '.layui-layer-title'
            });
        }
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
        var contentHtml = $('<div>').html({$info.content}).html();
        contentHtml.replace(/&quot;/g,"\"");
        ue.addListener("ready", function () {
            ue.setContent(contentHtml);
        });
      /**
       * 提交图文
       * */
      $('.save_news').on('click',function(){
          var sendTime = $('#send_time').val();
        var list = {};
        list.id = '{$info.id}';
        list.type = 6;
        list.title = $('#title').val();/* 标题 */
          list.false_view = $('#false_view').val();/* 虚拟浏览量 */
        list.author_uid = $('.now_bind_user').data("id");/* 作者 */
        list.content = getContent();/* 内容 */
          list.send_time = dataToStamp(sendTime);/* 推送时间 */
        list.fid= $("#fid option:selected").val();
        list.class_id= $("#class_id option:selected").val();
        list.video_cover = $("#image_input").val();/* 图片*/
        var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
        var objExp=new RegExp(Expression);
        if(list.title == ''){
          $eb.message('error','请输入标题');
          return false;
        }
        if(list.author_uid == ''){
          $eb.message('error','请输入作者');
          return false;
        }
        if(list.fid == ''){
          $eb.message('error','请选择版块分类');
          return false;
        }
        if(list.content == ''){
          $eb.message('error','请输入内容');
          return false;
        }
        var data = {};
        $.ajax({
          url:"{:Url('edit_thread')}",
          data:list,
          type:'post',
          dataType:'json',
          success:function(re){
            if(re.code == 200){
              data[re.data] = list;
              $('.type-all>.active>.new-id').val(re.data);
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
      });
    </script>
    {elseif condition="$style eq 'view_video'"/}
    <script>
        var sendTime = "{$info.send_time}";
        var sendTimeDate = format(sendTime*1000);
        $("#send_time").val(sendTimeDate);
        var contentHtml = $('<div>').html({$info.content}).html();
        contentHtml.replace(/&quot;/g,"\"");
        ue.addListener("ready", function () {
            ue.setContent(contentHtml);
        });
      //不可编辑
      ue.ready(function () {
        ue.setDisabled()
      });
    </script>
    {/if}
{/block}