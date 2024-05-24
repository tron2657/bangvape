{extend name="public/modal-frame"}
{block name="head_top"}
<style>
.content{
    display:flex;
    flex-direction: row;
    background:#FFFFFF;
    color:#666666;
}
h3{
    margin-bottom:30px;
}
.left_content{
    display:inline-block;
    width:400px;
    padding:30px;
}
.save_fields{
    margin-top:30px;
}
.right_content{
    margin-top:30px;
    margin-right:30px;
    flex:1 1 auto;
    border: 1px solid #EEE;
    padding:30px;
}
.div-checked{
    display:flex;
    flex-wrap:wrap;
    align-content: flex-start;
    align-items: flex-start;
}
.div-checked i{
    font-style:normal;
}
.div-checked label {
    cursor: pointer;
    position: relative;
    display: inline-block;
    width: 150px;
    height: 38px;
    margin-top: 12px;
    margin-right: 12px;
}
.div-checked input[type="checkbox"] {
    opacity: 0;
}

.div-checked input[type="checkbox"]:checked+i {
    border-color: #339FDF;
    color: #339FDF;
}
.div-checked i {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 1px solid #ccc;
    text-align: center;
    line-height: 36px;
}

span:after {
      opacity: 1;
      content: '';
      position: absolute;
      width: 5px;
      height: 10px;
      background: transparent;
      top: 8px;
      right: 5px;
      border: 1px solid #fff;
      border-top: none;
      border-left: none;
      -webkit-transform: rotate(35deg);
      -moz-transform: rotate(35deg);
      -o-transform: rotate(35deg);
      -ms-transform: rotate(35deg);
      transform: rotate(35deg);
    }
/**
* 选中状态，span(三角形)样式
* @type {String}
*/
.div-checked input[type="checkbox"]:checked+i+span {
    width: 0px;
    height: 0px;
    border-color: #339FDF transparent;
    border-width: 0px 0px 20px 20px;
    border-style: solid;
    position: absolute;
    right: 0px;
    bottom:0px;
    opacity: 1;
}
input[type="checkbox"]{
    margin-bottom: 8px;
    margin-right: 8px;
}
.selected_div{
    
}
.selected_item{
    margin-bottom:12px;
}
.selected_checkbox{
    display:inline-block;
    margin-bottom:16px;
    margin-right:16px;
}
.selected_div .selected_label{
    width:200px;
    padding-top:8px;
    padding-left:12px;
    padding-bottom:8px;
    margin-right:20px;
    display:inline-block;
    border: 1px solid #ccc;
}
.delete-btn{
    padding-left:30px;
    display:inline-block;
    width: 24px;
    height: 24px;
}
.delete-btn img{
    display:inline-block;
    width: 24px;
    height: 24px;
}
</style>
{/block}
{block name="content"}
<div class="content">
    <input name="event_id" id="event_id" value="{$id}" type="hidden">
    <div class="left_content">
        <h3 id="title">已选（{$bind|count}）</h3>
        <div class="selected_div">
            {volist name='$bind' id='vo'}
                <div id="{$vo['field']}" class="selected_item">
                    <label >
                        <span class="selected_label">{$vo['field_name']}</span>
                        <input name="selected_checkbox" class="selected_checkbox" type="checkbox" value="{$vo['field']}" {if condition="$vo.is_need eq 1 "}checked{/if}></input>必填
                        </label>
                    <div class="delete-btn"><img id="{$vo['field']}" src="{$url}/{__ADMIN_PATH}css/delete_black.png" alt=""></div>
                </div>
            {/volist}
        </div>
        <button type="submit" class="layui-btn save_fields" lay-submit="" lay-filter="demo1">提交</button>
    </div>
    <div class="right_content">
        <h3 sytle="">可选资料项</h3>
        <div class="div-checked">
            {volist name='$datum' id='v'}
                <label ><input id="{$v['field']}" class="choosable_checkbox" type="checkbox" value="{$v['field']}" data-name="{$v['name']}" {if condition="in_array($v['field'],$field)"}checked{/if}><i>{$v['name']}</i><span></span></label>
            {/volist}
        </div>
    </div>
    
</div>
{/block}
{block name="script"}
<script>
var datas = ['AAAA','BBBB','CCCC','DDDD'];
var choosableList = [];
var selectList=[];
Array.prototype.remove = function(val) { 
    var index = this.indexOf(val); 
    if (index > -1) { 
        this.splice(index, 1); 
    } 
};

/**
 */
$('.save_fields').click(function () {
//    var selected_checkboxs =  $('.selected_checkbox');
////    console.log(selected_checkboxs)
    var name=[];
    var need=[];
    $('input[name="selected_checkbox"]').each(function(){ 
        /**
        * 已选值 
         */
        console.log($(this).val());
        name.push($(this).val());
        /**是否必选
         */
        console.log($(this)[0].checked);
        need.push($(this)[0].checked);
    });
    console.log(need);
    console.log(name);
    var id=$('#event_id').val();
    $.post("{:url('set_bind_field')}",{name:name,need:need,id:id},function (res) {
         console.log(res);
         if (res.code == 200) {
          $eb.message('success', res.data);
          setTimeout(function (e) {
            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
            parent.layer.close(parent.layer.getFrameIndex(window.name));
          }, 600)
        } else {
          $eb.message('error', res.data);
        }
    })
});

///**
// * 循环可选数据
// */
//for(item of datas){
//    $(".div-checked").append('<label ><input id="'+item+'" class="choosable_checkbox" type="checkbox" value="'+item+'"><i>'+item+'</i><span></span></label>')
//}
/**
 * 可选数据选择
 */
$(".choosable_checkbox").click(function(e) {
    var name=$(this).attr('data-name');
    if(e.target.checked){
        choosableList.push(e.target.value)
        $(".selected_div").append('<div id="'+e.target.value+'" class="selected_item"><label><span class="selected_label">'+name+'</span><input name="selected_checkbox" class="selected_checkbox" type="checkbox" value="'+e.target.value+'">必填</label><div class="delete-btn"><img id="'+e.target.value+'" src="{$url}/{__ADMIN_PATH}css/delete_black.png" alt=""></div></div>');
    }else{
        choosableList.remove(e.target.value)
        $('.selected_div  #'+e.target.value+'').remove()
    }
    showTitle();
});

$("body").on("click", ".delete-btn", function (e) {
    choosableList.remove(e.target.id)
    $('.selected_div  #'+e.target.id+'').remove();
    $('.div-checked #'+e.target.id).attr("checked",false);
    showTitle();
});

function showTitle(){
    $('#title').text('已选（'+ choosableList.length+'）')
}

</script>
{/block}
