{extend name="public/container"}
{block name="head"}
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<style>
    label.error{
        color: #a94442;
        margin-bottom: 0;
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        transform: translate(0, 0);
    }
    .file{
        width: 100px;
        background-color:#676a6c;
        border: none;
    }
    .deleteimg{position: absolute;
        right: 0%;
        top: 0%;
        cursor: pointer;
        background-color:#676a6c;
        color: #fff;
        width: 18px;
        text-align: center;}
    /* .show-box {
        display: none;
    } */
		.radio-box{
			float: left;
			margin-left: 25px;
		}
		.radio-input{
			float: left;
			margin-top: 2px!important;
		}
		.radio-text{
			float: left;
			margin-left: 8px;
		}
		.txy-title{
			font-size: 14px;
			font-weight: bold;
			color: #333333;
			margin-left: 25px;
			margin-bottom: 15px;
		}
        #sou{
            width: 500px;
            height: 350px;
            background-color: white;
            margin-left: 400px;
            margin-top: 30px;
            box-shadow: 2px 2px 20px #888888;
            overflow:hidden;
            display:none;
        }
        #sou_top{
            width:450px ;
            height: 55px;
            margin-top: 10px;
            margin-left:30px ;
        }
        #sou_b{
            width: 500px;
            height: 285px;
            /* background-color: blueviolet; */
            overflow:auto;
            
        }
        .cou_nei{
            width: 450px;
            height: 80px;
            /* background-color: brown; */
            margin-left: 20px;
            overflow:hidden;
            border-bottom: 1px solid Gainsboro;
        
        }
        .nei_wei{
            margin-left: 5px;
            margin-top: 8px;
            font-size: 15px;
        }
        .nei_content{
            margin-left: 5px;
            margin-top: 18px;
            word-wrap: break-word;
	        word-break: break-all;
        }
        .nei_left{
            width: 350px;
            height: 72px;
            /* background-color: chartreuse; */
            float: left;
            
        }
        .nei_right{
            width: 80px;
            height: 72px;
            margin-left: 360px;
            /* background-color: chocolate; */
            overflow:hidden;

        }
        .xuan_d{
            margin-top: 20px;
            margin-left: 15px;
        }
</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="tabs-container ibox-title">
                            <div style="width: 100%;height: 30px;">
                                <h5>创作配置</h5>
                            </div>
                            <div style="margin-left: 300px;margin-top: 30px;">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">创作引导链接</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="title" required  lay-verify="required" placeholder="" value="<?php echo $some;?>" autocomplete="off" class="layui-input" style="width: 500px;float:left;" id="address">
                                        <button type="button"  class="layui-btn" style="height:32px;margin-top: 0px;line-height: 32px;" id="xuan">选择地址</button>
                                    </div>
                                </div>
                            </div>
                            <div id="sou">
                                <div id="sou_top">
                                    <input type="text" name="title"  id="tltle_sou"  lay-verify="required" placeholder="请输入帖子标题"  autocomplete="off" class="layui-input" style="width: 350px;height:30px;float: left;">
                                    <button type="button"  class="layui-btn" style="height:32px;margin-top: 0px;line-height: 32px;margin-left: 10px;" id="sou_kuang">搜索</button>
                                    <p style="font-size: 6px;" id="p_content"></p>
                                </div>
                                <div id="sou_b">
                                    <div id="sou_b_nei">
   
                                    </div>
                                </div>
                               
                            </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>

$('#xuan').click(function(){
    $("#sou").show();
});
$('#sou_kuang').click(function(){
    var name = document.getElementById("tltle_sou").value;
    $.ajax({
             type: "POST",
             url: "writing_sou",
             data: {title:name},
             dataType: "json",
             success: function(data){
                 var some = ''
                 if(data.data.length == 0){
                     some = '没有搜索到相关帖子';
                    $('#p_content').html(some);
                 }else{
                     some = '搜索到' + data.data.length + '个相关的帖子';
                     $('#p_content').html(some);
                 }
                
                var str = '';
			    //对数据做遍历，拼接到页面显示
		        for(var i=0;i<data.data.length;i++){
			        str += '<div class="cou_nei">'+
			        	   	    '<div class="nei_left">'+
			        	   			'<p class="nei_wei">'+ data.data[i].title +'</p>'+
			        	   			'<p class="nei_content" style="float:left;">作者：'+ data.data[i].author_uid +'&nbsp;&nbsp;类型：'+data.data[i].type+'&nbsp;&nbsp;所属板块及分类:'+data.data[i].fid+'-'+data.data[i].class_id+'&nbsp;&nbsp;评论数：'+data.data[i].reply_count+'</p>'+
			        	   		'</div>'+
                                '<div class="nei_right">'+
                                    '<button type="button" class="layui-btn layui-btn-sm layui-btn-primary xuan_d"'+ 'onclick='   +    '"onModified('+ data.data[i].id +')"'    +   '>选定</button>'+
                                '</div>'+
			        	   '</div>';
		        }
		        //放入页面的容器显示
		        $('#sou_b_nei').html(str);
                }
         });

    // alert(name);
});
function onModified(btn){
        $.ajax({
             type: "POST",
             url: "writing_sou",
             data: {id:btn},
             dataType: "json",
             success: function(data){
                $('#tltle_sou').val('');
                $('#address').attr('value',data.data);
                layer.msg(data.msg, {icon: 1});
                $("#sou").hide();//隐藏div
                $('#p_content').html('');
                $(".cou_nei").remove();
             }
        });
    
}
</script>
{/block}