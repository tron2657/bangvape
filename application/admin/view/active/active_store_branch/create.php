{extend name="public/modal-frame"}
{block name="head_top"}
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script type="text/javascript" src="//api.map.baidu.com/api?type=webgl&v=1.0&ak=D5LPTr5VIlTToGc2Vou1o468OcUuZicR"></script>
<style>
    .layui-form-select dl{
      min-height:200px;
    }
    .layui-form-item-me{
      margin-bottom: 25px;
      display:block;
      width:100%;
    }
    .layui-form-radio *{
      margin-top: 0;
      font-size: 12px;!important
    }
    .layui-form-mid-me {
      display:inline-block;
    }
    .layui-form-label-me{
      width: 125px;
      text-align: right;
      vertical-align: middle;
      float: left;
      font-size: 12px;
      color: #495060;
      line-height: 1;
      padding: 10px 12px 10px 0;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }
    .layui-required .layui-form-label-me:before{
      content: '*';
      display: inline-block;
      margin-right: 4px;
      line-height: 1;
      font-family: SimSun;
      font-size: 12px;
      color: #ed3f14;
    }
    .layui-input-block-me{
      margin-left: 125px;
      position: relative;
      line-height: 32px;
      font-size: 12px;
    }
    .layui-input-me{
      display: inline-block;
      width: 100%;
      height: 32px;
      line-height: 1.5;
      padding: 4px 7px;
      font-size: 12px;
      border: 1px solid #dddee1;
      border-radius: 4px;
      color: #495060;
      background-color: #fff;
      background-image: none;
      position: relative;
      cursor: text;
    }
    .layui-input-inline{
      display: inline-block;
      width: 40%;
      height: 32px;
      line-height: 1.5;
      padding: 4px 7px;
      font-size: 12px;
      border: 1px solid #dddee1;
      border-radius: 4px;
      color: #495060;
      background-color: #fff;
      background-image: none;
      position: relative;
      cursor: text;
    }
    .layui-btn-me{
      color: #fff;
      background-color: #2d8cf0;
      border-color: #2d8cf0;
      width: 100%;
      padding: 6px 15px 7px 15px;
      font-size: 14px;
      border-radius: 2px;
      border-width: 0px;
    }
    .img_content{
      position: relative;
      height: 80px;
      width:80px;
      display: inline-block;
      text-align: center;
      border: 1px dashed #c0ccda;
      border-radius: 4px;
      overflow: hidden;
      background: #fff;
      position: relative;
      font-family: Ionicons;
      box-shadow: 2px 2px 5px rgba(0,0,0,.1);
      margin-right: 4px;
      box-sizing: border-box;
      background-image:url('/public/system/images/image.png');
      background-size:20px 20px;
      background-position: 50% 50%;
      background-repeat: no-repeat;
    }
    .img_content2{
      position: relative;
      height: 80px;
      width:80px;
      display: inline-block;
      text-align: center;
      border: 1px dashed #c0ccda;
      border-radius: 4px;
      overflow: hidden;
      background: #fff;
      position: relative;
      font-family: Ionicons;
      box-shadow: 2px 2px 5px rgba(0,0,0,.1);
      margin-right: 4px;
      box-sizing: border-box;
      background-image:url('/public/system/images/image.png');
      background-size:20px 20px;
      background-position: 50% 50%;
      background-repeat: no-repeat;
    }
    .image_box{
      position: relative;
      height: 80px;
      width:80px;
      font-size: 20px;
      display: inline-block;
      font-family: Ionicons;
      speak: none;
      font-style: normal;
      font-weight: 400;
      font-variant: normal;
      text-transform: none;
      text-rendering: auto;
      line-height: 1;
      -webkit-font-smoothing: antialiased;
    }
    .upload-img-box-img {
      display:block;
      position: absolute;
      width: 80px;
      height: 80px;
    }
    .delete-btn {
      position: absolute;
      top: 0px;
      right: 0px;
      width: 24px;
      height: 24px;
      cursor: pointer;
      font-size: 20px;
      align-items: center;
      justify-content: center;
      background-image:url('/public/system/images/delete.png');
      background-size:20px 20px;
      background-position: 50% 50%;
      background-repeat: no-repeat;
    }
    .delete-btn-big {
      position: absolute;
      top: 0px;
      right: 0px;
      width: 24px;
      height: 24px;
      cursor: pointer;
      font-size: 20px;
      align-items: center;
      justify-content: center;
      background-image:url('/public/system/images/delete.png');
      background-size:20px 20px;
      background-position: 50% 50%;
      background-repeat: no-repeat;
    }
</style>
{/block}
{block name="content"}
<form class="layui-form" action="" id="signupForm">
  <input type="hidden" name="id" value="{$event['id']}" id="id">
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">名称</label>
    <div class="layui-input-block-me">
      <input name="name" id="name" lay-verify="required" type="text" placeholder="请输入名称" class="layui-input" value="{$event['name']}">
    </div>
  </div> 
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me ">门店负责人</label>
    <div class="layui-input-block-me">
      <input class="layui-input-me layui-input-inline" placeholder="请输入门店负责人"  readonly id="now_bind_user" data-uid="{$event['uid']}"  value="{$event['nickname']}" style="display: inline-block">
      <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 32px;">门店负责人</button>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">门店海报<br/>(800*800)<br/></label>
    <div class="layui-input-block-me">
      <div class="img_content">
        <img id= "upload-img-box-img" class="upload-img-box-img" alt="" {if condition="$event['cover'] neq null"}src="{$event['cover']}"{/if}>
        
        <div id= "delete-btn" {if condition="$event['cover'] eq null"}style="display:none" {/if} class="delete-btn"></div>
        
      </div>
    </div>
  </div>  
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me ">库存数量</label>
    <div class="layui-input-block-me">
    <input name="stock" id="stock" lay-verify="required" type="number" placeholder="请输入库存数量" class="layui-input" value="{$event['stock']}">
    </div>
  </div>
  <div class="layui-form-item-me layui-required"  id="form-item-address">
    <label class="layui-form-label-me layui-required">门店地址</label>
    <div class="layui-input-block-me">
      <input name="address" id="address"  lay-verify="required" type="text" placeholder="请输入活动地点，比如场地名称" class="layui-input-me layui-input-inline" value="{$event['address']}">
    <div class="layui-form-mid-me  layui-word-aux">即活动场地名称，如“国家大剧院</div>

    <style>
       #container {
            overflow: hidden;
            width: 100%;
            height: 500px;
            margin: 0;
            font-family: "微软雅黑";
        }
        .info {
        z-index: 999;
        width: auto;
        min-width: 22rem;
        padding: .75rem 1.25rem;
        margin-left: 1.25rem;
        position: absolute;
        top: 4rem;
        background-color: #fff;
        border-radius: .25rem;
        font-size: 14px;
        color: #666;
        box-shadow: 0 2px 6px 0 rgba(27, 142, 236, 0.5);
    }
    </style>
    <div>
      <table style="width:100%">
        <tr>
          <td style="width: 80%;">
            <div id="container"></div>
            <div class="info"><input type="text" id="mapKeywrod" width="100%" ><input id="btnMapSearch" value="查找" type="button"></div>
          </td>
          <!-- <td style="vertical-align: top;"><div style="height: 100px;" id="r-result"></div></td> -->
        </tr>
      </table>
      <div>  
        <input type="text" disabled id="province" value="{$event['province']}" name="province" />
        <input type="text" disabled id="city" value="{$event['city']}" name="city" />
      
        <input type="text"  disabled id="district" value="{$event['district']}" name="district" />
        <input type="text" disabled id="lat" value="{$event['lat']}" name="lat" />
        <input type="text"  disabled id="lng" value="{$event['lng']}" name="lng" />
      </div>
      <script>

      $(function(){
        // 百度地图API功能
        var map = new BMapGL.Map("container");   
        var mapModule={
          init:function(model)
          {
            if(model.lng=='' || model.lat=='')
            {
              model.lng=0;
              model.lat=0;
            }
            var point=new BMapGL.Point(model.lng, model.lat);
            map.centerAndZoom(point,20);
            map.enableScrollWheelZoom(true);
        
            if(model.lng!='0')
            {
              this.set(point);
            }    
            this.bindEvent();     
          },
          bindEvent:function(){
              map.addEventListener('click', function (e) {     
                  var point=new BMapGL.Point(e.latlng.lng, e.latlng.lat);
                  var gc=new BMapGL.Geocoder();                  
                  gc.getLocation(point,function(rs){
                      console.log(rs);
                      // debugger;
                      $("#address").val(rs.address)
                      $("#city").val(rs.addressComponents.city);
                      $("#province").val(rs.addressComponents.province);
                      $("#district").val(rs.addressComponents.district);
                      $('#lat').val(e.latlng.lat);
                      $('#lng').val(e.latlng.lng);
                  });
                  mapModule.set(point);
                  // alert('点击位置经纬度：' + e.latlng.lng + ',' + e.latlng.lat);
              });

              $("#btnMapSearch").click(function(){  
                var local = new BMapGL.LocalSearch(map, {
                  renderOptions:{map: map}
                });
                local.search($("#mapKeywrod").val());
              });
          },
          set:function(point)
          {   
            
             var marker = new BMapGL.Marker(point);
             map.clearOverlays();
             map.addOverlay(marker);
          }
        }

        mapModule.init({
          lat:"{$event['lat']}",
          lng:"{$event['lng']}"
        });
  
        // var local = new BMapGL.LocalSearch(map, {
        //   renderOptions:{map: map}
        // });
     
  
   
      })

      </script>
    </div>
 
  </div>
  </div>
  <div class="layui-form-item-me layui-required">
      <button id="save_event" class="layui-btn-me save_event">提交</button>
  </div>
</form>
{/block}
{block name="script"}
<script>
$(".bind-product").on("click", function () {
  var obj=$(this);
  obj.click(function(){
    createFrame('选择商品', '{:Url('widget.stroe_product/store_product_batch')}?fodder=image&trial_product=product-img');
  });
  // createFrame('选择商品', '{:Url('widget.stroe_product/index')}?fodder=image&trial_product=product-img');
  // $eb.createModalFrame("选择商品", '{:Url('widget.stroe_product/store_product_batch')}?fodder=image&trial_product=product-img',{w: document.body.clientWidth, h: document.body.clientHeight})
  window.changeField=function(element,data){
    console.log(data);
  $("#product_id").val(data);
    // $img.attr("src",data.image);
    // $btnDel.css("display","block");
    // $("#product_id").val(data.id);
  }
});



var form = layui.form, layer = layui.layer;
form.render();
//输入框默认值
/**
时间区间
 */
layui.use('laydate', function(){
  var laydate = layui.laydate;
  laydate.render({
    elem: '#start_time'
    ,type: 'datetime'
    ,trigger: 'click'
  });
  laydate.render({
    elem: '#end_time'
    ,type: 'datetime'
    ,trigger: 'click'
  });
  laydate.render({
    elem: '#enroll_start_time'
    ,type: 'datetime'
    ,trigger: 'click'
  });
  laydate.render({
    elem: '#enroll_end_time'
    ,type: 'datetime'
    ,trigger: 'click'
  });
});
layui.use('form', function(){
    var form = layui.form;
    form.on('radio(type)', function (data) {        
           console.log(data)
           if (data.value == "0") {
             $("#form-item-address").hide();
             $("#form-item-detailed_address").hide();
           }
           else {
            $("#form-item-address").show();
             $("#form-item-detailed_address").show();
           }
           form.render();
       });
    form.on('radio(enroll_range)', function (data) {        
        console.log(data)
        if (data.value == "2") {
          $("#form-item-group").show();
        }
        else {
          $("#form-item-group").hide();
        }
        form.render();
    });

});

var ue = UE.getEditor('myEditor', {
    autoHeightEnabled: false,
    initialFrameHeight: 400,
    wordCount: false,
    maximumWords: 100000
  });
ue.ready(function () {
    $("#edui2").append('<div id="add_goods" style="cursor: pointer;display: inline-block;height: 22px;line-height: 22px;color: #000;margin-left: 3px;margin-top: 1px">添加商品</div>')
});

$('#save_event').click(function () {
  var data={};
  data.id=$('#id').val();
  data.uid=$('#now_bind_user').attr('data-uid');
  data.name=$('#name').val();
  data.city=$('#city').val();
  data.province=$('#province').val();
  data.district=$('#district').val();
  data.lat=$('#lat').val();
  data.lng=$('#lng').val();
  data.address=$('#address').val();
  data.stock=$('#stock').val();
  data.cover=$('#upload-img-box-img').attr('src');
  if(data.name == ''){ $eb.message('error','请输入名称');return false; }
  if(data.city == ''){ $eb.message('error','请选择城市');return false; }
  if(data.province == ''){ $eb.message('error','请选择省');return false;}
  if(data.district == ''){ $eb.message('error','请选择区');return false;}
  if(data.lat == ''){ $eb.message('error','请选择坐标');return false;}
  if(data.lng == ''){ $eb.message('error','请选择坐标');return false;}
  if(data.address == ''){ $eb.message('error','请选择地址');return false;}
  if(data.stock == ''){ $eb.message('error','请输入库存');return false;}
  if(data.cover == ''){ $eb.message('error','请选择门店海报');return false;} 
  // $.post("{:Url('edit_event')}",data,function (res) {
  //           if(res.code==200){
  //               // $eb.message('success',res.msg);
  //               setTimeout(function () {
  //                               $eb.message('success', re.data);
  //             console.log(parent)
  //             parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
  //             parent.layer.close(parent.layer.getFrameIndex(window.name));
  //               },1500)
  //           }else{
  //               $eb.message('error',res.msg);
  //           }
  //       })

  $.ajax({
      url: "{:Url('edit_event')}",
      data: data,
      type: 'post',
      dataType: 'json',
      success: function (re) {
        if (re.code == 200) {
            if(re.msg == 'ok'){
              $eb.message('success', re.data);
              console.log(parent)
              parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
              parent.layer.close(parent.layer.getFrameIndex(window.name));
          }else{
            $eb.message('error', re.data);
          }
        } else {
          $eb.message('error', re.msg);
        }
      },
      error:function(rs){
        $eb.message('error', '操作失败');
      }
    })
});

$(".bind-user").on("click", function () {
$eb.createModalFrame("绑定用户", '{:Url('admin/com.com_thread/bind_user_vim')}',{w: document.body.clientWidth, h: document.body.clientHeight})
});

$(".bind-group").on("click", function () {
  $eb.createModalFrame("绑定用户组",'{:Url('admin/com.com_forum/user_select',['name'=>'browse'])}',{w: document.body.clientWidth, h: document.body.clientHeight})
});

/**
  * 上传图片
  * */
$('.img_content').on('click', function (e) {
  createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image');
});

$('.img_content2').on('click', function (e) {
  createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image2');
});

function changeIMG(index, pic) {
  if(index=='image'){
    $("#upload-img-box-img").attr("src",pic);
    $("#delete-btn").css("display","block");
  }else{
    $("#upload-img-box-img-big").attr("src",pic);
    $("#delete-btn-big").css("display","block");
  }
 
}
$(".delete-btn").on("click", function (e) {
  $("#upload-img-box-img").attr("src",null);
  $("#delete-btn").css("display","none");
  e = e || window.event;  
          if(e.stopPropagation) { //W3C阻止冒泡方法  
              e.stopPropagation();  
          } else {  
              e.cancelBubble = true; //IE阻止冒泡方法  
          }  
});
$(".delete-btn-big").on("click", function (e) {
  $("#upload-img-box-img-big").attr("src",null);
  $("#delete-btn-big").css("display","none");
  e = e || window.event;  
          if(e.stopPropagation) { //W3C阻止冒泡方法  
              e.stopPropagation();  
          } else {  
              e.cancelBubble = true; //IE阻止冒泡方法  
          }  
});
$('form').on('focus', 'input[type=number]', function (e) {
 $(this).on('mousewheel.disableScroll', function (e) {
 e.preventDefault()
 })
})
$('form').on('blur', 'input[type=number]', function (e) {
 $(this).off('mousewheel.disableScroll')
})

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
var uid='{$event['uid']}';
var userData ={}
if(uid!=''){
  userData['uid'] = uid;
}
$.ajax({
  url: "{:Url('com.com_thread/get_user')}",
  data: userData,
  type: 'get',
  dataType: 'json',
  success: function (res) {
    if (res.code == 200) {
      if (res.data) {
        $("#now_bind_user").val(res.data.nickname);
        $("#now_bind_user").attr('data-uid', res.data.uid);
      }
    } else {
      Toast.error(res.msg);
    }
  }
});
window.addEventListener("storage", function (e) {
  console.log(e);console.log(111);
  if (e.key === "bind_username") {
    $("#now_bind_user").val(e.newValue);
    window.localStorage.removeItem("bind_username")
  }else if(e.key === "bind_userId"){
    $("#now_bind_user").attr('data-uid',e.newValue);
    window.localStorage.removeItem("bind_userId")
  }else if(e.key === "browse"){
    $("#now_bind_group").val(e.newValue);
    window.localStorage.removeItem("browse")
  }else if(e.key === "browse_id"){
    $("#now_bind_group").attr('data-groupid',e.newValue);
    window.localStorage.removeItem("browse_id")
  }
  if (e.key === "group") {
    var reg = new RegExp(",","g");//g,表示全部替换。
    var text = e.newValue.replace(reg,"、");
    $("#group_input").val(text);
    window.localStorage.removeItem("group")
  }else if(e.key === "group_id"){
    idData = e.newValue;
    $("#group").val(idData);
  }
});



</script>
{/block}
