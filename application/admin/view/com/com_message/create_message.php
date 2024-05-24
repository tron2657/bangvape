{extend name="public/modal-frame"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
{/block}
{block name="content"}
<div class="panel">
   <div class="panel-body">
       <form class="form-horizontal" id="signupForm">
           <div class="form-group">
               <div class="col-md-12">
                   <div class="input-group">
                       <span class="input-group-addon">标题</span>
                       {if condition="$style eq 'create'"}
                       <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" value="">
                       {elseif condition="$style eq 'edit'"/}
                       <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" value="{$message.title}">
                       {elseif condition="$style eq 'view'"/}
                       <input maxlength="64" placeholder="请在这里输入标题" name="title" class="layui-input" id="title" value="{$message.title}" readonly>
                       {/if}
                       <input type="hidden" name="id" value="">
                   </div>
               </div>
           </div>
           <div class="form-group">
               <div class="col-md-12">
                   <div class="input-group">
                       <span class="input-group-addon">推送时间</span>
                       <input type="text" class="layui-input" id="send_time" readonly placeholder="请选择推送时间">
                   </div>
               </div>
           </div>
           <div class="form-group">
               <div class="col-md-12" style="display: flex;align-items: center">
                   <div class="input-group" style="width: 70%">
                       <span class="input-group-addon">推送人群</span>
                       {if condition="$style eq 'create'"}
                       <input type="text" class="layui-input" id="choose_user_group" readonly placeholder="全部用户">
                       <input type="hidden" id="group">
                       {elseif condition="$style eq 'edit'"/}
                       <input type="text" class="layui-input" id="choose_user_group" readonly placeholder="全部用户" value="{$message.to_type_uid}">
                       <input type="hidden" id="group" value="{$message.to_uid}">
                       {elseif condition="$style eq 'view'"/}
                       <input type="text" class="layui-input" readonly placeholder="全部用户" value="{$message.to_type_uid}">
                       <input type="hidden" id="group">
                       {/if}
                   </div>
                   <div style="color: #999">不设置推送人群，则默认为全部用户</div>
               </div>
           </div>
           <div class="form-group">
               <div class="col-md-12" style="display: flex;align-items: center">
                   <div class="input-group">
                       <span class="input-group-addon">跳转链接设置</span>
                       {if condition="$style eq 'create'"}
                       <div style="display: flex;align-items: center">
                           <input type="text" class="layui-input" id="choose_url_input" style="width: 310px" readonly placeholder="">
                           <div id="choose_url" style="height: 38px;background-color: #eee;color: #848484;line-height: 38px;padding: 0 10px;cursor: pointer">选择地址</div>
                       </div>
                       {elseif condition="$style eq 'edit'"/}
                       <div style="display: flex;align-items: center">
                           <input type="text" class="layui-input" value="{$message.url}" id="choose_url_input" style="width: 310px" readonly placeholder="">
                           <div id="choose_url" style="height: 38px;background-color: #eee;color: #848484;line-height: 38px;padding: 0 10px;cursor: pointer">选择地址</div>
                       </div>
                       {elseif condition="$style eq 'view'"/}
                       <div style="display: flex;align-items: center">
                           <input type="text" class="layui-input" value="{$message.url}" id="choose_url_input" style="width: 310px" readonly placeholder="">
                       </div>
                       {/if}
                   </div>
                   <div style="color: #999">不设置跳转路径，则默认为不可点击</div>
               </div>
           </div>
           <div class="form-group">
               <div class="col-md-12">
                   <label style="color:#aaa">通知内容</label>
                   {if condition="$style eq 'create'"}
                   <textarea type="text/plain" id="myEditor" style="width:100%;"></textarea>
                   {elseif condition="$style eq 'edit'"/}
                   <textarea type="text/plain" id="myEditor" style="width:100%;">{$message.content}</textarea>
                   {elseif condition="$style eq 'view'"/}
                   <textarea type="text/plain" id="myEditor" style="width:100%;">{$message.content}</textarea>
                   {/if}
               </div>
               <div class="col-md-12" style="margin-top: 10px">
                   <div class="input-group">
                       <span>APP消息推送：</span>
                       <input type="radio" name="send_app" id="send_app" value="0" checked> 不推送
                       <input type="radio" name="send_app" id="send_app" value="1"> 推送
                   </div>
               </div>
           </div>
           {if condition="$style neq 'view'"}
           <div class="form-actions">
               <div class="row">
                   <div class="col-md-offset-4 col-md-9">
                       <button type="button" class="btn btn-w-m btn-info save_news">保存</button>
                   </div>
               </div>
           </div>
           {/if}
       </form>
   </div>
</div>
{/block}
{block name="script"}
    <script>
        $("#choose_user_group").on("click",function () {
            $eb.createModalFrame("选择用户组",'{:Url('com.com_forum/user_select',['name'=>'group','id'=>30,'type'=>1])}',{h:500,w:650})
        })
        $("#choose_url").on("click",function () {
            $eb.createModalFrame("选择链接",'{:Url('link.select/index')}',{h:600,w:750})
        })
        var idData = [];
        window.addEventListener("storage", function (e) {
            console.log(e)
            if (e.key === "group") {
                var reg = new RegExp(",","g");//g,表示全部替换。
                var text = e.newValue.replace(reg,"、");
                $("#choose_user_group").val(text);
                window.localStorage.removeItem("group")
            }else if(e.key === "group_id"){
                idData = e.newValue;
                $("#group").val(idData);
                window.localStorage.removeItem("group_id")
            }else if(e.key === "linkUrl"){
                $("#choose_url_input").val(e.newValue);
                window.localStorage.removeItem("linkUrl")
            }
        });
      $("#fid").change(function () {
        $.ajax({
          url:"{:Url('com.com_thread/select_class')}",
          data:{id:$("#fid").val()},
          type:'post',
          dataType:'json',
          success:function(res){
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
      window.addEventListener("storage", function (e) {
        if(e.key === "bind_username"){
          $(".now_bind_user").val(e.newValue);
          window.localStorage.removeItem("bind_username")
        }else if(e.key === "bind_userId"){
          $(".now_bind_user").attr('data-id',e.newValue);
          window.localStorage.removeItem("bind_userId")
        }
      });
      $.ajax({
        url:"{:Url('get_user')}",
        data:{},
        type:'get',
        dataType:'json',
        success:function(res){
          if(res.code == 200){
            if(res.data){
              $("#now_bind_user").val(res.data.nickname);
              $("#now_bind_user").attr('data-id',res.data.uid);
            }
          }else{
            Toast.error(res.msg);
          }
        }
      });
      function add0(m){return m<10?'0'+m:m }
      function format(shijianchuo)
      {
//shijianchuo是整数，否则要parseInt转换
        var time = new Date(shijianchuo);
        var y = time.getFullYear();
        var m = time.getMonth()+1;
        var d = time.getDate();
        var h = time.getHours();
        var mm = time.getMinutes();
        var s = time.getSeconds();
        return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
      }
    </script>
    {if condition="$style eq 'create'"}
    <script>
      layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
          elem: '#send_time', //指定元素
          type:'datetime',
        });
      });
      /**
       * 提交图文
       * */
      $('.save_news').on('click',function(){
        var sendTime = $('#send_time').val();
        var endTime = $('#end_time').val();
        var list = {};
          list.title = $('#title').val();/* 标题 */
        list.to_uid = $('#group').val();/* 推送人群id */
        list.to_type_uid = $('#choose_user_group').val();/* 推送人群 */
        list.content = getContent();/* 内容 */
        list.url = $('#choose_url_input').val();/* 链接 */
        list.send_time = dataToStamp(sendTime);/* 推送时间 */
        list.fid= $("#fid option:selected").val();
        list.class_id= $("#class_id option:selected").val();
        list.send_app= $("input[name='send_app']:checked").val();
          list.type_now= 1;
        var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
        var objExp=new RegExp(Expression);
          if(list.title == ''){
              $eb.message('error','请输入标题');
              return false;
          }
        if(list.send_time == ''){
          $eb.message('error','请选择推送时间');
          return false;
        }
        if(list.content == ''){
          $eb.message('error','请输入内容');
          return false;
        }
        var data = {};
        $.ajax({
          url:"{:Url('save')}",
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
    {elseif condition="$style eq 'edit'"/}
    <script>
        var sendTime = "{$message.send_time}";
        var sendTimeDate = format(sendTime*1000);
      layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
          elem: '#send_time', //指定元素
          type:'datetime',
          value: sendTimeDate
        });
      });
        /**
         * 提交图文
         * */
        $('.save_news').on('click',function(){
          var sendTime = $('#send_time').val();
            var endTime = $('#end_time').val();
          var list = {};
          list.id = "{$message.id}";
            list.title = $('#title').val();/* 标题 */
            list.to_uid = $('#group').val();/* 推送人群id */
            list.to_type_uid = $('#choose_user_group').val();/* 推送人群 */
          list.content = getContent();/* 内容 */
            list.url = $('#choose_url_input').val();/* 链接 */
          list.send_time = dataToStamp(sendTime);/* 推送时间 */
          list.fid= $("#fid option:selected").val();
          list.class_id= $("#class_id option:selected").val();
          list.type_now= 1;
          var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
          var objExp=new RegExp(Expression);
            if(list.title == ''){
                $eb.message('error','请输入标题');
                return false;
            }
          if(list.send_time == ''){
            $eb.message('error','请选择推送时间');
            return false;
          }
          if(list.content == ''){
            $eb.message('error','请输入内容');
            return false;
          }
          var data = {};
          $.ajax({
            url:"{:Url('update')}",
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
    {elseif condition="$style eq 'view'"/}
    <script>
      var sendTime = "{$message.send_time}";
      var sendTimeDate = format(sendTime*1000);
      $("#send_time").val(sendTimeDate);
    </script>
    {/if}
<script>

  var ue = UE.getEditor('myEditor',{
    autoHeightEnabled: false,
    initialFrameHeight: 400,
    wordCount: false,
    maximumWords: 100000
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
            $('.upload_span').on('click',function (e) {
//                $('.upload').trigger('click');
                createFrame('选择图片','{:Url('widget.images/index')}?fodder=image');
            })

            /**
             * 编辑器上传图片
             * */
            $('.edui-icon-image').on('click',function (e) {
//                $('.upload').trigger('click');
                createFrame('选择图片','{:Url('widget.images/index')}?fodder=image');
            })


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

        </script>
    {if condition="$style eq 'view'"/}
    <script>
      ue.ready(function () {
        ue.setDisabled()
      });
    </script>
    {/if}
{/block}