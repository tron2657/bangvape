{extend name="public/modal-frame"}
{block name="head_top"}
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>

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
</style>
{/block}
{block name="content"}
<form class="layui-form" action="" id="signupForm">
  <input type="hidden" name="id" value="{$event['id']}" id="event_id">
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">礼品卡名称</label>
    <div class="layui-input-block-me">
      <input name="store_name" id="store_name" lay-verify="required" type="text" placeholder="请输入礼品卡名称" class="layui-input" value="{$event['store_name']}">
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">礼品卡图片(750*750)</label>
    <div class="layui-input-block-me">
    <div id="select_img" class="img_content">
        <img id= "upload-img-box-img" class="upload-img-box-img" alt="" {if condition="$event['image'] neq null"}src="{$event['image']}"{/if}>
        
        <div id= "delete-btn" {if condition="$event['image'] eq null"}style="display:none" {/if} class="delete-btn"></div>
        <input type="hidden" id="image" name="image" value="{$event['image']}"/>
      </div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">关联商品<br/></label>
    <div class="layui-input-block-me">
      <div id="select_product" class="img_content">
        <img id= "product-img" class="upload-img-box-img" alt="" {if condition="$event['product-img'] neq null"}src="{$event['product-img']}"{/if}>
        
        <div id= "delete-btn" {if condition="$event['product-img'] eq null"}style="display:none" {/if} class="delete-btn"></div>
        <input type="hidden" id="product_id" name="product_id" value="{$event['product_id']}"/>
      </div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required ">价格</label>
    <div class="layui-input-block-me">
      <input name="price" placeholder="0"  lay-verify="required" id="price" type="text" class="layui-input-me  layui-input-inline" value="{$event['price']}">
       
    </div>
  </div>
  <div class="layui-form-item-me layui-required" style="display: none;">
    <label class="layui-form-label-me layui-required ">赠送购物积分</label>
    <div class="layui-input-block-me">
      <input name="give_integral" placeholder="0"  lay-verify="required" id="give_integral" type="text" class="layui-input-me  layui-input-inline" value="{$event['give_integral']}">
       
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required ">排序</label>
    <div class="layui-input-block-me">
      <input name="sort"  lay-verify="required" id="sort" type="number" class="layui-input-me  layui-input-inline" value="{$event['sort']}">
       
    </div>
  </div> 
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required ">状态</label>
    <div class="layui-input-block-me">
        <input name="is_show" lay-filter="enroll_range" lay-verify="required" type="radio" title="上架" value="1" {if condition="$event['is_show'] eq 1"}checked{/if}>
        <br/>
        <input name="is_show" lay-filter="enroll_range" lay-verify="required" type="radio" title="下架" value="0" {if condition="$event['is_show'] eq 0"}checked{/if}>
        <br/>
    </div>
  </div> 

  <div class="layui-form-item-me layui-required">
      <button id="save_event" class="layui-btn-me save_event">提交</button>
  </div>
</form>
{/block}
{block name="script"}
<script>



var form = layui.form, layer = layui.layer;
form.render();
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

$('#save_event').click(function () {
 
  var data={};
  data.id=$('#event_id').val();
  data.store_name=$('#store_name').val();
//   data.store_info=$('#store_info').val();
  data.image=$('#upload-img-box-img')[0].src;
//   data.exchange_product_img=$('#exchange_product_img').val();
  data.product_id=$('#product_id').val();
  data.price=$('#price').val();
  data.give_integral=$('#give_integral').val();
  data.sort=$('#sort').val();
  data.is_show=$('input[name="is_show"]:radio:checked').val();
  // console.log(data.is_show);
  if(data.store_name == ''){ $eb.message('error','请输入礼品卡名称');return false; }
  if(data.image == ''){ $eb.message('error','请选择礼品卡图片');return false; }
  if(data.product_id == ''){ $eb.message('error','请选择关联商品图片');return false; }
  if(data.price == ''){ $eb.message('error','请输入价格');return false; }
  if(data.give_integral == ''){ $eb.message('error','请输入购物赠送积分');return false; }
  if(data.sort == ''){ $eb.message('error','请输入排序');return false; }
  if(data.is_show == ''){ $eb.message('error','请选择状态');return false; }
  if(data.store_name == ''){ $eb.message('error','请输入礼品卡名称');return false; }
  debugger;
  $.ajax({
      url: "{:Url('editSave')}",
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


/**
  * 活动海报
  * */
$('#select_img').on('click', function (e) {
  createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image');
});
 

$('#select_product').each(function(){

  var obj=$(this);
  obj.click(function(){
    createFrame('选择商品', '{:Url('widget.stroe_product/index')}?fodder=image&trial_product=product-img');
  });
  var $img=obj.find('#product-img');
  var $btnDel=obj.find('#delete-btn');
  window.changeField=function(element,data){
    $img.attr("src",data.image);
    $btnDel.css("display","block");
    $("#product_id").val(data.id);
  }

  $btnDel.click(function(event){
    $img.attr("src",null);
    $btnDel.css("display","none");
  e = event;
          if(e.stopPropagation) { //W3C阻止冒泡方法  
              e.stopPropagation();  
          } else {  
              e.cancelBubble = true; //IE阻止冒泡方法  
          }  

  });
})

function changeIMG(index, pic) {
  $("#upload-img-box-img").attr("src",pic);
  $("#delete-btn").css("display","block");
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

var userData ={}
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
