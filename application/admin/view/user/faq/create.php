{extend name="public/modal-frame"}{block name="head_top"}<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet"><link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet"><script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script><script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script><script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script><script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script><script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script><style type="text/css">  .ivu-form-item-content {    position: relative;    line-height: 32px;    font-size: 12px;}  .ivu-radio-group {    display: inline-block;    font-size: 12px;    vertical-align: middle;}  .ivu-radio-checked .ivu-radio-inner {    border-color: #2d8cf0;}  .ivu-radio-input {    position: relative;    margin-right: 10px;  }  .ivu-radio {    display: inline-block;    margin-right: 4px;    white-space: nowrap;    position: relative;    line-height: 1;    vertical-align: middle;    cursor: pointer;}.ivu-radio-group-item{    position: relative;    margin-right: 10px;}.ivu-radio-wrapper {    font-size: 12px;    vertical-align: middle;    display: inline-block;    position: relative;    white-space: nowrap;    margin-right: 8px;    cursor: pointer;}.ivu-radio-inner:after {    position: absolute;    width: 8px;    height: 8px;    left: 2px;    top: 2px;    border-radius: 6px;    display: table;    border-top: 0;    border-left: 0;    content: ' ';    background-color: #2d8cf0;    opacity: 0;    -webkit-transition: all .2s ease-in-out;    transition: all .2s ease-in-out;    -webkit-transform: scale(0);    -ms-transform: scale(0);    transform: scale(0);}.input-group-addon {    border: none;  }</style>{/block}{block name="content"}<div class="panel">   <div class="panel-body">       <form class="form-horizontal" id="signupForm">           <div class="form-group">               <div class="col-md-12">                   <div class="input-group">                       <span class="input-group-addon">问题</span>                       {if condition="request()->action() eq 'create'"}                       <input maxlength="64" placeholder="请在这里输入问题" name="title" class="layui-input" id="title" value="">                       {elseif condition="request()->action() eq 'edit'"/}                       <input maxlength="64" placeholder="请在这里输入问题" name="title" class="layui-input" id="title" value="{$data.title}">                       {elseif condition="request()->action() eq 'view'"/}                       <input maxlength="64" placeholder="请在这里输入问题" name="title" class="layui-input" id="title" value="{$data.title}" readonly>                       {/if}                       <input type="hidden" name="id" value="">                   </div>               </div>           </div>           <div class="form-group">               <div class="col-md-12">                   <label style="color:#aaa">问题说明</label>                   {if condition="request()->action() eq 'create'"}                   <textarea type="text/plain" id="myEditor" style="width:100%;"></textarea>                   {elseif condition="request()->action() eq 'edit'"/}                   <textarea type="text/plain" id="myEditor" style="width:100%;">{$data.desc}</textarea>                   {elseif condition="request()->action() eq 'view'"/}                   <textarea type="text/plain" id="myEditor" style="width:100%;">{$data.desc}</textarea>                   {/if}               </div>           </div>           <div class="form-group">               <div class="col-md-12">                   <div class="input-group">                       <span class="input-group-addon">状态</span>                       {if condition="request()->action() eq 'create'"}                       <label class="ivu-radio-wrapper ivu-radio-group-item"><span class="ivu-radio"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="0"></span>关闭</label>                       <label class="ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-checked"><span class="ivu-radio ivu-radio-checked"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="1" checked></span>开启</label>                       {elseif condition="request()->action() eq 'edit'"/}                       <label class="ivu-radio-wrapper ivu-radio-group-item"><span class="ivu-radio"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="0"  {if condition="$data.status eq 0"}checked{/if}></span>关闭</label>                       <label class="ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-checked"><span class="ivu-radio ivu-radio-checked"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="1" {if condition="$data.status eq 1"}checked{/if}></span>开启</label>                       {elseif condition="request()->action() eq 'view'"/}                       <label class="ivu-radio-wrapper ivu-radio-group-item"><span class="ivu-radio"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="0" {if condition="$data.status eq 0"}checked{/if} readonly></span>关闭</label>                       <label class="ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-checked"><span class="ivu-radio ivu-radio-checked"><span class="ivu-radio-inner"></span> <input type="radio" class="ivu-radio-input" name="status" value="1" {if condition="$data.status eq 1"}checked{/if} readonly></span>开启</label>                       {/if}                   </div>               </div>           </div>           <div class="form-group">               <div class="col-md-12">                   <div class="input-group">                       <span class="input-group-addon">排序</span>                       {if condition="request()->action() eq 'create'"}                       <input maxlength="10" placeholder="请输入排序" name="sort" class="layui-input" id="sort">                       {elseif condition="request()->action() eq 'edit'"/}                       <input maxlength="10" placeholder="请输入排序" name="sort" class="layui-input" id="sort" value="{$data.sort}">                       {elseif condition="request()->action() eq 'view'"/}                       <input maxlength="10" placeholder="请输入排序" name="sort" class="layui-input" id="sort" value="{$data.sort}" readonly>                       {/if}                   </div>               </div>           </div>           <div class="form-actions">               <div class="row">                   <div class="col-md-offset-4 col-md-9">                       <button type="button" class="btn btn-w-m btn-info save_news">保存</button>                   </div>               </div>           </div>       </form>   </div></div>{/block}{block name="script"}        {if condition="request()->action() eq 'create'"}    <script>      /**       * 提交       * */      $('.save_news').on('click',function(){                var dataform = {};        dataform.title = $('#title').val();        dataform.desc = getContent();        dataform.status = $('input:radio[name="status"]:checked').val();        dataform.sort = $('#sort').val();               if(dataform.title == ''){          $eb.message('error','请输入问题');          return false;        }        if(dataform.desc == ''){          $eb.message('error','请输入问题说明');          return false;        }        if(dataform.status == ''){          $eb.message('error','请选择状态');          return false;        }        if(dataform.sort == ''){          $eb.message('error','请输入排序');          return false;        }                var data = {};        $.ajax({          url:"{:Url('save')}",          data:dataform,          type:'post',          dataType:'json',          success:function(re){            if(re.code == 200){              //data[re.data] = dataform;              $eb.message('success',re.msg);              setTimeout(function (e) {                parent.$(".J_iframe:visible")[0].contentWindow.location.reload();                parent.layer.close(parent.layer.getFrameIndex(window.name));              },600)            }else{              $eb.message('error',re.msg);            }          }        })      });    </script>    {elseif condition="request()->action() eq 'edit'"/}    <script>            /**       * 提交       * */      $('.save_news').on('click',function(){        var dataform = {};        dataform.id = "{$data.id}";        dataform.title = $('#title').val();        dataform.desc = getContent();        dataform.status = $('input:radio[name="status"]:checked').val();        dataform.sort = $('#sort').val();               if(dataform.title == ''){          $eb.message('error','请输入问题');          return false;        }        if(dataform.desc == ''){          $eb.message('error','请输入问题说明');          return false;        }        if(dataform.status == ''){          $eb.message('error','请选择状态');          return false;        }        if(dataform.sort == ''){          $eb.message('error','请输入排序');          return false;        }        var data = {};        $.ajax({          url:"{:Url('update')}",          data:dataform,          type:'post',          dataType:'json',          success:function(re){            if(re.code == 200){              //data[re.data] = dataform;              $eb.message('success',re.msg);              setTimeout(function (e) {                parent.$(".J_iframe:visible")[0].contentWindow.location.reload();                parent.layer.close(parent.layer.getFrameIndex(window.name));              },600)            }else{              $eb.message('error',re.msg);            }          }        })      });    </script>    {elseif condition="request()->action() eq 'view'"/}        {/if}    <script>  var ue = UE.getEditor('myEditor',{    autoHeightEnabled: false,    initialFrameHeight: 400,    wordCount: false,    maximumWords: 100000  });            /**            * 获取编辑器内的内容            * */            function getContent() {              return (ue.getContent());            }            function hasContent() {                return (UM.getEditor('myEditor').hasContents());            }            function createFrame(title,src,opt){                opt === undefined && (opt = {});                return layer.open({                    type: 2,                    title:title,                    area: [(opt.w || 700)+'px', (opt.h || 650)+'px'],                    fixed: false, //不固定                    maxmin: true,                    moveOut:false,//true  可以拖出窗外  false 只能在窗内拖                    anim:5,//出场动画 isOutAnim bool 关闭动画                    offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]                    shade:0,//遮罩                    resize:true,//是否允许拉伸                    content: src,//内容                    move:'.layui-layer-title'                });            }                        /**             * 上传图片             * */            $('.upload_span').on('click',function (e) {//                $('.upload').trigger('click');                createFrame('选择图片','{:Url('widget.images/index')}?fodder=image');            })            /**             * 编辑器上传图片             * */            $('.edui-icon-image').on('click',function (e) {//                $('.upload').trigger('click');                createFrame('选择图片','{:Url('widget.images/index')}?fodder=image');            })                               </script>    {if condition="request()->action() eq 'view'"/}    <script>      ue.ready(function () {        ue.setDisabled()      });    </script>    {/if}{/block}