{extend name="public/modal-frame"}
{block name="head_top"}
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<style>
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
      display:none;
      position: absolute;
      width: 80px;
      height: 80px;
    }
    .delete-btn {
      display:none;
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
    <label class="layui-form-label-me">活动主题</label>
    <div class="layui-input-block-me">
      <input name="title" id="title" lay-verify="required" type="text" placeholder="请输入活动主题" class="layui-input" value="{$event['title']}" readonly>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">活动分类</label>
    <div class="layui-input-block-me">
      <select class="layui-select layui-input" name="cate_id" id="cate_id" lay-verify="required" readonly>
        {volist name='cate' id='v'}
        <option value="{$v['value']}" {if condition="$event['cate_id'] eq $v['value']"}selected{/if}>{$v['label']}</option>
        {/volist}
      </select>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me ">发起人</label>
    <div class="layui-input-block-me">
      <input class="layui-input-me layui-input-inline" placeholder="请输入你要绑定的发起人"  readonly id="now_bind_user" data-uid="{$event['uid']}"  value="{$event['nickname']}" style="display: inline-block" readonly>
<!--      <button type="button" class="btn btn-w-m btn-info bind-user" style="height: 32px;">发起人</button>-->
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">活动海报(长方形)</label>
    <div class="layui-input-block-me">
      <div class="img_content">
        <img id= "upload-img-box-img" class="upload-img-box-img" style="display: block;position: relative" alt="" src="{$event['cover']}" readonly>
        <div id= "delete-btn" class="delete-btn"></div>
      </div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">所属版块</label>
    <div class="layui-input-block-me">
    <select class="layui-select layui-input" name="forum_id" id="forum_id" lay-verify="required" readonly>
      {volist name='forum' id='v'}
          <option value="{$v['value']}" {if condition="$event['forum_id'] eq $v['value']"}selected{/if}>{$v['label']}</option>
      {/volist}
    </select>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me">活动类型</label>
    <div class="layui-input-block-me">
      <input type="radio"   title="线上活动" value="0" {if condition="$event['type'] eq 0"}checked{/if} readonly>
      <input type="radio"  title="线下活动" value="1" {if condition="$event['type'] eq 1"}checked{/if} readonly>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">活动时间</label>
    <div class="layui-input-block-me">
      <input type="text" class="layui-input-me layui-input-inline" id="start_time"  value="{$event['start_time']}"  name="start_time" placeholder="请选择活动开始时间" readonly>
      <div class="layui-form-mid-me ">到</div>
      <input type="text" class="layui-input-me layui-input-inline" id="end_time"   value="{$event['end_time']}" name="end_time" placeholder="请选择活动结束时间" readonly>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">活动地点</label>
    <div class="layui-input-block-me">
      <input name="address" id="address"  lay-verify="required" type="text" placeholder="请输入活动地点，比如场地名称" class="layui-input-me layui-input-inline" value="{$event['address']}" readonly>
    <div class="layui-form-mid-me  layui-word-aux">即活动场地名称，如“国家大剧院</div>
  </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">具体地址</label>
    <div class="layui-input-block-me">
      <input name="detailed_address" id="detailed_address"  lay-verify="required" type="text" placeholder="请输入活动具体地址" class="layui-input-me layui-input-inline" value="{$event['detailed_address']}" readonly>
      <div class="layui-form-mid-me  layui-word-aux">即具体活动地址，如“北京市西城区西长安街2号</div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">报名时间</label>
    <div class="layui-input-block-me">
      <input type="text" lay-verify="required" class="layui-input-me layui-input-inline" id="enroll_start_time" name="enroll_start_time" value="{$event['enroll_start_time']}" placeholder="请选择报名开始时间" readonly>
      <div class="layui-form-mid-me ">到</div>
      <input type="text" lay-verify="required" class="layui-input-me layui-input-inline" id="enroll_end_time" name="enroll_end_time" value="{$event['enroll_end_time']}" placeholder="请选择报名结束时间" readonly>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required ">报名人数</label>
    <div class="layui-input-block-me">
      <input name="enroll_count" lay-verify="required" id="enroll_count" type="number" class="layui-input-me  layui-input-inline" value="{$event['enroll_count']}" readonly>
      <span class="layui-word-aux">人  默认不限制人数</span>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">报名范围</label>
    <div class="layui-input-block-me">
      <input type="radio" title="全部用户" value="0" {if condition="$event['enroll_range'] eq 0"}checked{/if} readonly>
      <br/>
      <input  type="radio" title="仅限活动所在版块粉丝" value="1" {if condition="$event['enroll_range'] eq 1"}checked{/if} readonly>
      <br/>
      <input  type="radio" title="指定用户组报名" value="2" {if condition="$event['enroll_range'] eq 2"}checked{/if} readonly>
      <div id="form-item-group" {if condition="$event['enroll_range']!=2"} style="display:none;" {/if} >
      <div style="margin-top: 10px;{if condition=" id="choose_group_box">
        <input type="text" id="group_input" class="layui-input" value="{$event.g_name}">
        <input type="hidden" id="group" name="group" class="layui-input" value="{$event.g_id}">
      </div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">费用</label>
    <div class="layui-input-block-me">
      <input type="radio" title="免费" value="0" {if condition="$event['price_type'] eq 0"}checked{/if} readonly>
      <br/>
      <div>
      <input  type="radio" title="积分支付" value="1" {if condition="$event['price_type'] eq 1"}checked{/if} readonly>
      <input name="price" id="price" type="text" class="layui-input-me layui-input-inline" placeholder="请输入报名所需积分数值" value="{$event['price']}" readonly>
      </div>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <label class="layui-form-label-me layui-required">是否推荐</label>
    <div class="layui-input-block-me">
      <input type="radio" title="是" value="1" {if condition="$event['is_recommend'] eq 1"}checked{/if} readonly>
      <input type="radio" title="否" value="0" {if condition="$event['is_recommend'] eq 0"}checked{/if} readonly>
    </div>
  </div>
  <div class="layui-form-item-me layui-required">
    <textarea type="text/plain" id="myEditor" style="width:100%;"></textarea>
  </div>
<!--  <div class="layui-form-item-me layui-required">-->
<!--      <button id="save_event" class="layui-btn-me save_event">提交</button>-->
<!--  </div>-->
</form>
{/block}
{block name="script"}
<script>
var form = layui.form, layer = layui.layer;
form.render();
//输入框默认值
$('#myEditor').val("{$event['content']}");
/**
时间区间
 */


var ue = UE.getEditor('myEditor', {
    autoHeightEnabled: false,
    initialFrameHeight: 400,
    wordCount: false,
    maximumWords: 100000
  });
</script>
{/block}
