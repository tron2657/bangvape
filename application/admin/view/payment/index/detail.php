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
</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">

            <div class="tabs-container ibox-title">
            <div class="tab-content">
                <div class="<!--ibox-content--> p-m m-t-sm">
                    <form method="post" class="form-horizontal" id="signupForm" action="{:Url('save_basics')}">
                        <input type="hidden" value="{$tab_id}" name="tab_id"/>
                        {volist name="list" id="vo"}
                        {eq name="$vo['config_tab_id']" value="$tab_id"}
                        <div class="form-group">
                            <label class="col-sm-2 control-label" {eq name="$vo['type']" value="radio"}style="padding-top: 0;"{/eq}>{$vo.info}</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        {switch name="$vo['type']" }
                                        {case value="text" break="1"}<!-- 文本框-->
                                            <input type="{$vo.type}" class="form-control" name="{$vo.menu_name}" value="{$vo.value}" validate="{$vo['required']}" readonly style="width: {$vo.width}%"/>
                                        {/case}
                                        {case value="textarea" break="1"}<!--多行文本框-->
                                            <textarea name="{$vo.menu_name}" cols="{$vo.width}" rows="{$vo.high}" class="form-control" style="width: {$vo.width}%">{$vo.value}</textarea>
                                        {/case}
                                        {case value="checkbox" break="1"}<!--多选框-->
                                            <?php
                                            $parameter = array();
                                            $option = array();
                                            if($vo['parameter']){
                                                $parameter = explode("\n",$vo['parameter']);
                                                foreach ($parameter as $k=>$v){
                                                    $option[$k] = explode('=>',$v);
                                                }
//                                                dump($parameter);
    //                                            exit();
                                            }
                                            $checkbox_value = $vo['value'];
                                            if(!is_array($checkbox_value)) $checkbox_value = explode("\n",$checkbox_value);
    //                                        dump($checkbox_value);
    //                                        exit();
                                            ?>
                                            {volist name="option" id="son" key="k"}
                                                {if condition="in_array($son[0],$checkbox_value)"}
                                                    <label class="checkbox-inline i-checks">
                                                        <input type="checkbox" value="{$son.0}" name="{$vo.menu_name}[]" checked="checked">{$son.1}</label>
                                                {else/}
                                                    <label class="checkbox-inline i-checks">
                                                        <input type="checkbox" value="{$son.0}" name="{$vo.menu_name}[]">{$son.1}</label>
                                                {/if}
                                            {/volist}
                                        {/case}
                                        {case value="radio" break="1"}<!--单选按钮-->
                                            <?php
                                                $parameter = array();
                                                $option = array();

                                                if($vo['parameter']){
                                                    $parameter = explode("\n",$vo['parameter']);
                                                    foreach ($parameter as $k=>$v){
                                                        $option[$k] = explode('=>',$v);
                                                    }
                                                }
                                            ?>
                                            {volist name="option" id="son"}
                                                {if condition="$son[0] eq $vo['value']"}
                                                    <div class="radio i-checks checked" style="display:inline">
                                                        <label class="" style="padding-left: 0;">
                                                            <div class="iradio_square-green " style="position: relative;">
                                                                <input type="radio" checked="checked" value="{$son.0}" name="{$vo.menu_name}" style="position: absolute; opacity: 0;">
                                                            </div>
                                                            <i></i> {$son.1}
                                                        </label>
                                                    </div>
                                                {else /}
                                                    <div class="radio i-checks" style="display:inline">
                                                        <label class="" style="padding-left: 0;">
                                                            <div class="iradio_square-green" style="position: relative;">
                                                                <input type="radio" value="{$son.0}" name="{$vo.menu_name}" style="position: absolute; opacity: 0;">
                                                            </div>
                                                            <i></i> {$son.1}
                                                        </label>
                                                    </div>
                                                {/if}
                                            {/volist}
                                        {/case}
                                        {case value="upload" break="1"}<!--文件上传-->
                                            <?php
                                                 $img_image = $vo['value'];
                                                 $num_img = 0;
                                                 if(!empty($img_image)){
                                                     $num_img = 1;
                                                 }
                                            ?>
                                 <!--文件-->{if condition="$vo['upload_type'] EQ 3"}

                                        <div style="display: inline-flex;">
                                                    <input type="file" class="{$vo.menu_name}_1" name="{$vo.menu_name}" style="display: none;" data-name="{$vo.menu_name}" id="{$vo.menu_name}" data-type = "{$vo.upload_type}" />
                                                        {if condition="$num_img LT 1"}
                                                         <div class="file-box">
                                                            <div class="file {$vo.menu_name}">
                                                            </div>
                                                         </div>
                                                        {else/}
                                                            {volist name="$vo['value']" id="img"}
                                                            <div class="file-box">
                                                                <div class="file {$vo.menu_name}" style="position: relative;">
                                                                    <a href="http://<?php echo $_SERVER['SERVER_NAME'].$img;?>" target="_blank">
                                                                        <span class="corner"></span>
                                                                        <div class="icon">
                                                                            <i class="fa fa-file"></i>
                                                                        </div>
                                                                        <div class="file-name">
                                                                            <?php
                                                                            //显示带有文件扩展名的文件名
                                                                            echo basename($img);
                                                                            ?>
                                                                        </div>
                                                                    </a>
                                                                    <div data-name="{$vo.menu_name}" data-image="{$img}" class="deleteimg" onclick="delPic(this)" title="删除">×</div>
                                                                    <input type="hidden" name="{$vo.menu_name}[]" value="{$img}">
                                                                </div>
                                                            </div>
                                                            {/volist}
                                                        {/if}
                                                        <div class="clearfix"></div>
                                                </div>
                                 <!--多图-->{elseif condition="$vo['upload_type'] EQ 2"/}
                                                <div style="margin-top: 20px;">
                                                    <input type="file" class="{$vo.menu_name}_1" name="{$vo.menu_name}" style="display: none;" data-name="{$vo.menu_name}" id="{$vo.menu_name}" data-type = "{$vo.upload_type}" />
                                                    <button class="btn btn-w-m btn-primary flag" type="button" data-name="{$vo.menu_name}"><i class="fa fa-upload"></i>添加图片</button>
<!--                                                    <span class="flag" style="margin-top: 5px;width: 86px;height: 27px;border-radius: 6px;cursor:pointer;padding: .5rem 1rem;background-color: #18a689;color: #fff;text-align: center;" data-name="{$vo.menu_name}" >添加图片</span>-->
                                                    <div class="attachment upload_image_{$vo.menu_name}" style="display:block;margin:20px 0 5px -44px">
                                                        {volist name="$vo['value']" id="img"}
                                                        <div class="file-box">
                                                            <div class="file {$vo.menu_name}" style="position: relative;">
                                                                    <span class="corner"></span>
                                                                    <div class="image open_image">
                                                                        <img alt="image" class="img-responsive" data-image="{$img}" src="{$img}" style="width:100%;height:100%;cursor: pointer">
                                                                    </div>
                                                                    <div class="file-name">
                                                                        <?php
                                                                        //显示带有文件扩展名的文件名
                                                                        echo basename($img);
                                                                        ?>
                                                                    </div>
                                                                <div data-name="{$vo.menu_name}" data-image="{$img}" class="deleteimg" onclick="delPic(this)" title="删除">×</div>
                                                                <input type="hidden" name="{$vo.menu_name}[]" value="{$img}">
                                                            </div>
                                                        </div>
                                                        {/volist}
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                 <!--单图-->{else/}
                                                <div style="display: inline-flex;">
                                                    
                                                <!-- <div style="display: inline-flex;" class="show-box"> -->
                                                    <input type="file" class="{$vo.menu_name}_1" name="{$vo.menu_name}" style="display: none;" data-name="{$vo.menu_name}" id="{$vo.menu_name}" data-type = "{$vo.upload_type}" />
                                                    <div class="flag" style="width: 100px;height: 80px;background-image:url('/public/system/module/wechat/news/images/image.png');cursor: pointer"  data-name="{$vo.menu_name}" >
                                                    </div>
                                                    {if condition="$num_img LT 1"}
                                                            <div class="file-box">
                                                                <div class="{$vo.menu_name}">
                                                                </div>
                                                            </div>
                                                        {else/}
                                                            {volist name="$vo['value']" id="img"}
                                                                <div class="file-box">
                                                                    <div class="{$vo.menu_name}">
                                                                        <div style="position: relative;" class="file">
                                                                                <div class="image open_image">
                                                                                    <img alt="image" class="img-responsive" data-image="{$img}" src="{$img}" style="width: 100%;height: 100%;cursor: pointer">
                                                                                </div>
                                                                            <div data-name="{$vo.menu_name}" data-image="{$img}" class="deleteimg" onclick="delPic(this)" title="删除">×</div>
                                                                            <input type="hidden" name="{$vo.menu_name}[]" value="{$img}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/volist}
                                                            <div class="clearfix"></div>
                                                        {/if}
                                                    </div>
                                            {/if}
                                        {/case}
                                        {/switch}
                                    </div>
                                     <div class="col-md-6">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> {$vo.desc}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="hr-line-dashed"></div>-->
                        {/eq}
                        {/volist}
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    $eb = parent._mpApi;
    $().ready(function() {
        $("#signupForm").validate();
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

    });
</script>
{/block}