{extend name="public/container"}
{block name="content"}
<div class="layui-fluid" style="background: #fff">
    <div class="layui-tab layui-tab-brief" lay-filter="tab">
        <ul class="layui-tab-title">
         
        {volist name="tabs" id="vo"}
            <li   {eq name='tab_id' value='$vo.tab_id'}class="layui-this" {/eq} >
                <a target="Iframe" href="{:Url('/admin/setting.system_config/index',['type'=>1, 'tab_id'=>$vo.tab_id])}">{$vo.title}</a>
            </li>
        {$vo.tab_id}
        {/volist}

 
        </ul>
    </div>
    <div class="layui-row layui-col-space15"  id="app">
  
</div>
     
    
    </div>
</div>
 
<iframe  src="{:Url('/admin/setting.system_config/index',['type'=>1, 'tab_id'=>$tab_id])}" id="Iframe" name="Iframe" frameborder="0" scrolling="no" width="100%" height="100%" style="border:0px;"></iframe>

<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"} 
{/block}

