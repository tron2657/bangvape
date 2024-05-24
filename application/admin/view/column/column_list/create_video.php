{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<link href="{__FRAME_PATH}css/columnadd.css" rel="stylesheet">
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<script src="{__ADMIN_PATH}js/vod-js-sdk-v6.js"></script>
<style>
    .gray-bg {
        background-color: #fff;
    }

    .title {
        color: #333;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .form-horizontal {
        padding-left: 20px;
        padding-right: 40px;
    }
    .input-group-addon{
        min-width: 122px;
        text-align: right;
        line-height: 20px;
    }
    .input-group{
        display: flex;
    }
    .layui-form-select{
        width: 100%;
    }
    .xm-select-parent{
        width: 100%;
    }
    .upload-img-box {
        border: 1px solid #E5E6E7;
        padding: 5px 10px;
        margin-right: 5px;
        margin-top: 5px;
        border-radius: 3px;
        position: relative;
        width: 102px;
        height: 92px;
    }

    .upload-img-box-img {
        width: 80px;
        height: 80px;
    }

    .delete-btn {
        display: none;
        position: absolute;
        top: 5px;
        right: 10px;
        width: 80px;
        height: 80px;
        cursor: pointer;
        font-size: 20px;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .delete-btn2 {
        display: none;
        position: absolute;
        top: 5px;
        right: 10px;
        width: 80px;
        height: 80px;
        cursor: pointer;
        font-size: 20px;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .delete-btn img {
        width: 30px;
        height: 30px;
    }
    .delete-btn2 img {
        width: 30px;
        height: 30px;
    }
    .layui-form-radioed .layui-anim{
        color: #0ca6f2!important;
    }
    .layui-form-radio .layui-anim:hover{
        color: #0ca6f2!important;
    }
    .state-box .layui-form-radio{
        margin-top: 3px;
    }
    .column-box:last-child{
        border-bottom: none!important;
    }
</style>
{/block}
{block name="content"}
<div class="body">
    <form class="form-horizontal layui-form">
        {if condition="$style eq 'create'"}
        <div>
            <div class="title">
                商品信息
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品分类：</span>
                        <select name="category" id="category" value="" lay-filter="aihao" style="width: 100%">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品名称：</span>
                        <input placeholder="请输入" name="title" class="layui-input" id="title" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">作者：</span>
                        <select name="uids" id="bind_select" xm-select-skin="normal" xm-select="user_select" xm-select-search="{:Url('get_author_list')}" xm-select-radio>
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品简介：</span>
                        <textarea class="layui-textarea" id="info" placeholder="请输入简介信息" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品关键字：</span>
                        <input placeholder="多个英文状态下的逗号隔开" name="title" class="layui-input" id="keyword" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品主要图片：<br>（300*200px）</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: block;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span" id="upload_img_box">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="" type="hidden" id="image_input" name="local_url">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品轮播图片：<br>（400*200px）</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content2" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: block;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span2" id="upload_img_box2">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="" type="hidden" id="image_input2" name="local_url">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="title">
                商品详情
            </div>
            <textarea type="text/plain" id="myEditor" style="width:100%;padding-left: 10px;margin-bottom: 20px;"></textarea>
            <div class="title">
                视频上传
            </div>
            {if($switch==1)}
            <div class="added-line">
                <i class="nfa nfa-tengxunvicon"></i>
                系统已开启云点播服务，可直接上传腾讯云
            </div>
            <div class="cloud-video-upload">
                <div class="upload-tips">请上传以*.mp4格式为主的视频文件，上传过程中请耐心等待提示</div>
                <div class="layui-upload-list" id="video-expand"></div>
                <div class="button-box" style="position: relative">
                    <div type="button" class="layui-btn layui-btn-normal" id="qcloud-video-select">
                        选择视频文件
                    </div>
                    <input type="file" style="position: absolute;opacity: 0;left: 167px;top: 0;width: 120px;height: 38px;cursor: pointer" id="video-file" class="video-file">
                    <div type="button" class="layui-btn" id="qcloud-video-upload" style="background-color: #009688">
                        开始上传
                    </div>
                    <!--<input type="file" name="hiddenPath" id="hiddenPath" style="display: none">-->
                    <!-- <input type="hidden" name="content" id="urli"> -->
                </div>
            </div>
            <div class="layui-progress layui-progress-big" lay-filter="demo">
                <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
            </div>
            {else}
            <div class="added-line">
                <i class="nfa nfa-yzhuyi"></i>
                系统尚未设置云点播上传服务，可以使用本地模式上传
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-control" style="height:auto">
                        <label style="color:#ccc">视频上传：请上传 *.rmvb , *.avi , *.mp4 格式的视频文件</label>
                        <!-- <button class="layui-btn layui-btn-sm"  id="upload">上传图片</button> -->
                        <button type="button" class="layui-btn" id="video-upload">
                            <i class="layui-icon">&#xe67c;</i>上传视频
                        </button>
                        <input type="hidden" name="content" id="urli">
                    </div>
                </div>
            </div>
            <!-- <div class="form-group" style="padding: 0 15px;"> -->
            <div class="layui-progress layui-progress-big" lay-filter="demo">
                <div class="layui-progress-bar layui-bg-blue" lay-percent="0%"></div>
            </div>
            <!-- </div> -->
            {/if}
            <div class="title" style="margin-top: 20px;">
                售卖方式
            </div>
            <div style="display: flex;">
                <input type="radio" name="big-sale-mode" value="0" title="单独售卖" checked lay-filter="filter">
                <div style="padding: 4px 20px 8px 20px;background-color: #f2f2f2;border-radius: 10px;flex: 1">
                    <div>
                        <input type="radio" name="price" value="0" title="付费" checked lay-filter="filter">
                        <input type="radio" name="price" value="1" title="免费" lay-filter="filter">
                    </div>
                    <div class="pay-content">
                        <div class="form-group" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品售价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="price" value="0" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">成本价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="cost_price" value="0" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品市场价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="ot_price" value="0" type="number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex;margin-top: 15px;">
                <input type="radio" name="big-sale-mode" value="1" title="关联专栏" lay-filter="filter">
                <div style="padding: 8px 20px 12px 20px;background-color: #f2f2f2;border-radius: 10px;flex: 1">
                    <div style="color: #999;margin-top: 4px;">
                        <span style="color: #0ca6f2;cursor: pointer" onclick="$eb.createModalFrame('选择专栏','{:Url('select_column')}',{h:500,w:550})">+ 添加专栏</span>（至少添加一个专栏）
                    </div>
                    <div id="column_content">

                    </div>
                </div>
            </div>
            <div class="title" style="margin-top: 20px;">
                其它设置
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">关联栏目：</span>
                        <select name="column" id="column" value="" lay-filter="select_base_column" xm-select="select_base_column" xm-select-type="1" style="width: 100%">

                        </select>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">排序：</span>
                            <input placeholder="" name="title" class="layui-input" id="sort" value="0" type="number">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">销量：</span>
                            <input placeholder="" name="title" class="layui-input" id="sales" value="0" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">剥比：</span>
                            <input placeholder="" name="title" class="layui-input" id="strip_num" value="0" type="number">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">虚拟销量：</span>
                            <input placeholder="" name="title" class="layui-input" id="ficti_sales" value="0" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">赠送积分：</span>
                            <input placeholder="" name="title" class="layui-input" id="score" value="0" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group state-box">
                        <span class="input-group-addon" style="border: none">商品状态：</span>
                        <input type="radio" name="state" value="1" title="上架" checked lay-filter="stateFilter">
                        <input type="radio" name="state" value="0" title="下架" lay-filter="stateFilter">
                    </div>
                </div>
            </div>
            <div style="display: flex;justify-content: center">
                <button type="button" class="btn btn-w-m btn-info save_news" id="save">确定</button>
            </div>
        </div>
        {else/}
        <div>
            <div class="title">
                商品信息
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品分类：</span>
                        <select name="category" id="category" value="" lay-filter="aihao" style="width: 100%">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品名称：</span>
                        <input placeholder="请输入" name="title" class="layui-input" id="title" value="{$info.name}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">作者：</span>
                        <select name="uids" id="bind_select" xm-select-skin="normal" xm-select="user_select" xm-select-search="{:Url('get_author_list')}" xm-select-radio>
                            <option value="">请选择</option>
                            <option value="{$info.author_id}" selected>{$info.nickname}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品简介：</span>
                        <textarea class="layui-textarea" id="info" placeholder="请输入简介信息" style="resize: none;">{$info.introduction}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品关键字：</span>
                        <input placeholder="多个英文状态下的逗号隔开" name="title" class="layui-input" id="keyword" value="{$info.keyword}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品主要图片：<br>（300*200px）</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: none;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span" id="upload_img_box">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="{$info.image}" type="hidden" id="image_input" name="local_url">
                                </div>
                            </a>
                            <div class="upload-img-box">
                                <div class="delete-btn"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div>
                                <img class="upload-img-box-img" src="{$info.image}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">商品轮播图片：<br>（400*200px）</span>
                        <input type="file" class="upload" name="image" style="display: none;" id="image"/>
                        <div id="img_content2" style="display: flex;max-width: 600px;flex-wrap: wrap">
                            <a style="display: block;width: 102px;height: 92px;border: 1px solid #E5E6E7;margin-top: 5px;margin-right: 5px;"
                               class="btn-sm add_image upload_span2" id="upload_img_box2">
                                <div class="upload-image-box transition image_img"
                                     style="height: 80px;background-repeat:no-repeat;background-size:contain;background-image:url('/public/system/module/wechat/news/images/image.png')">
                                    <input value="" type="hidden" id="image_input2" name="local_url">
                                </div>
                            </a>
                            {volist name="info.images" id="v"}
                            <div class="upload-img-box">
                                <div class="delete-btn2"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div>
                                <img class="upload-img-box-img" src="{$v}" alt="">
                            </div>
                            {/volist}
                        </div>
                    </div>
                </div>
            </div>
            <div class="title">
                商品详情
            </div>
            <textarea type="text/plain" id="myEditor" style="width:100%;padding-left: 10px;margin-bottom: 20px;"></textarea>
            <div class="title">
                售卖方式
            </div>
            <div style="display: flex;">
                {if condition="$info.pid neq '0'"}
                <input type="radio" name="big-sale-mode" value="0" title="单独售卖" lay-filter="filter">
                {else/}
                <input type="radio" name="big-sale-mode" value="0" title="单独售卖" checked lay-filter="filter">
                {/if}
                <div style="padding: 4px 20px 8px 20px;background-color: #f2f2f2;border-radius: 10px;flex: 1">
                    <div>
                        {if condition="$info.is_free eq '0'"}
                        <input type="radio" name="price" value="0" title="付费" checked lay-filter="filter">
                        <input type="radio" name="price" value="1" title="免费" lay-filter="filter">
                        {/if}
                        {if condition="$info.is_free eq '1'"}
                        <input type="radio" name="price" value="0" title="付费" lay-filter="filter">
                        <input type="radio" name="price" value="1" title="免费" checked lay-filter="filter">
                        {/if}
                    </div>
                    {if condition="$info.is_free eq '0'"}
                    <div class="pay-content">
                        <div class="form-group" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品售价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="price" value="{$info.price}" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">成本价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="cost_price" value="{$info.cost_price}" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品市场价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="ot_price" value="{$info.ot_price}" type="number">
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                    {if condition="$info.is_free eq '1'"}
                    <div class="pay-content" style="display: none;">
                        <div class="form-group" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品售价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="price" value="{$info.price}" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">成本价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="cost_price" value="{$info.cost_price}" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon" style="border: none;background-color: #f2f2f2;">商品市场价：</span>
                                    <input placeholder="" name="title" class="layui-input" id="ot_price" value="{$info.ot_price}" type="number">
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                </div>
            </div>
            <div style="display: flex;margin-top: 15px;">
                {if condition="$info.pid neq '0'"}
                <input type="radio" name="big-sale-mode" value="1" title="关联专栏" checked lay-filter="filter">
                {else/}
                <input type="radio" name="big-sale-mode" value="1" title="关联专栏" lay-filter="filter">
                {/if}
                <div style="padding: 8px 20px 12px 20px;background-color: #f2f2f2;border-radius: 10px;flex: 1">
                    <div style="color: #999;margin-top: 4px;">
                        <span style="color: #0ca6f2;cursor: pointer" onclick="$eb.createModalFrame('选择专栏','{:Url('select_column')}',{h:500,w:550})">+ 添加专栏</span>（至少添加一个专栏）
                    </div>
                    <div id="column_content">
                        {if condition="$info.pid neq ''"}
                        {volist name="info.pid_info" id="v"}
                        <div class="column-box" style="display: flex;align-items: center;padding: 10px 0;border-bottom: 1px solid #333;">
                            <img src="{$v.image}" style="width: 64px;height: 64px;" alt="">
                            <div style="margin-left: 10px;width: 260px;">
                                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">{$v.name}</div>
                                <div style="margin-top: 5px;">{$v.nickname}</div>
                                <div style="margin-top: 5px;">{$v.price}</div>
                            </div>
                            <div data-id="{$v.id}" class="cancel-relation" style="background-color: #fff;border: 1px solid #e4e4e4;color: #333;padding: 5px 10px;font-size: 12px;border-radius: 4px;cursor: pointer;flex-shrink: 0">取消关联</div>
                        </div>
                        {/volist}
                        {/if}
                    </div>
                </div>
            </div>
            <div class="title" style="margin-top: 20px;">
                其它设置
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">关联栏目：</span>
                        <select name="column" id="column" value="" lay-filter="select_base_column" xm-select="select_base_column" xm-select-type="1" style="width: 100%">

                        </select>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">排序：</span>
                            <input placeholder="" name="title" class="layui-input" id="sort" value="{$info.sort}" type="number">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">销量：</span>
                            <input placeholder="" name="title" class="layui-input" id="sales" value="{$info.sales}" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">剥比：</span>
                            <input placeholder="" name="title" class="layui-input" id="strip_num" value="{$info.strip_num}" type="number">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">虚拟销量：</span>
                            <input placeholder="" name="title" class="layui-input" id="ficti_sales" value="{$info.ficti_sales}" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none">赠送积分：</span>
                            <input placeholder="" name="title" class="layui-input" id="score" value="{$info.score}" type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group state-box">
                        <span class="input-group-addon" style="border: none">商品状态：</span>
                        {if condition="$info.is_show eq '1'"}
                        <input type="radio" name="state" value="1" title="上架" checked lay-filter="stateFilter">
                        <input type="radio" name="state" value="0" title="下架" lay-filter="stateFilter">
                        {/if}
                        {if condition="$info.is_show eq '0'"}
                        <input type="radio" name="state" value="1" title="上架" lay-filter="stateFilter">
                        <input type="radio" name="state" value="0" title="下架" checked lay-filter="stateFilter">
                        {/if}
                    </div>
                </div>
            </div>
            <div style="display: flex;justify-content: center">
                <button type="button" class="btn btn-w-m btn-info save_news" id="save">确定</button>
            </div>
        </div>
        {/if}
    </form>
</div>
{/block}
{block name="script"}
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    var formSelects = layui.formSelects;
    var form = layui.form;
    form.render();
    formSelects.config('user_select', {
        type: 'get',                //请求方式: post, get, put, delete...
        searchName: 'nickname',      //自定义搜索内容的key值
        clearInput: true,          //当有搜索内容时, 点击选项是否清空搜索内容, 默认不清空
    }, false);
    form.on('radio(filter)', function(data){
        if(data.value === "0"){
            $(".pay-content").show();
        }else {
            $(".pay-content").hide();
            $("#price").val(0);
            $("#cost_price").val(0);
            $("#ot_price").val(0);
        }
    });
    /**
     * 上传单图
     * */
    $('.upload_span').on('click', function (e) {
        createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image');
    });
    /**
     * 上传多图
     * */
    $('.upload_span2').on('click', function (e) {
        createFrame('选择图片', '{:Url('widget.images/index')}?fodder=image2');
    });
    /*图片删除按钮*/
    $("body").on("mouseover mouseout", ".upload-img-box", function (event) {
        if (event.type === "mouseover") {
            $(this).find("div").css("display", "flex");
        } else if (event.type === "mouseout") {
            $(this).find("div").css("display", "none");
        }
    });
    var imgVal = [];//单图
    var imgVal2 = [];//多图
    /*删除单图*/
    $("body").on("click", ".delete-btn", function () {
        var index = $(this).parent().index();
        imgVal.splice(index-1, 1);
        var imgInputVal = imgVal.join(",");
        $('#image_input').val(imgInputVal);
        $(this).parent().remove();
        $("#upload_img_box").show();
    });
    /*删除多图*/
    $("body").on("click", ".delete-btn2", function () {
        var index = $(this).parent().index();
        imgVal2.splice(index-1, 1);
        var imgInputVal = imgVal2.join(",");
        $('#image_input2').val(imgInputVal);
        $(this).parent().remove();
        $("#upload_img_box2").show();
    });
    /*获取选择图片*/
    function changeIMG(index, pic) {
        console.log(index)
        if(index === "image"){
            $("#img_content").append('<div class="upload-img-box"><div class="delete-btn"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div><img class="upload-img-box-img" src=' + pic + '></div>');
            $("#upload_img_box").hide();
            imgVal.push(pic);
            var imgInputVal = imgVal.join(",");
            $('#image_input').val(imgInputVal);
        }else if(index === "image2"){
            $("#img_content2").append('<div class="upload-img-box"><div class="delete-btn2"><img src="{__ADMIN_PATH}css/delete.png" alt=""></div><img class="upload-img-box-img" src=' + pic + '></div>');
            if(imgVal.length === 9){
                $eb.message('error', '最多上传9张');
                $("#upload_img_box2").hide();
                return;
            }
            imgVal2.push(pic);
            var imgInputVal = imgVal2.join(",");
            $('#image_input2').val(imgInputVal);
            if(imgVal2.length === 9){
                $("#upload_img_box2").hide();
            }
        }
    }
    /*打开窗口*/
    function createFrame(title, src, opt) {
        opt === undefined && (opt = {});
        return layer.open({
            type: 2,
            title: title,
            area: [(opt.w || 600) + 'px', (opt.h || 550) + 'px'],
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
    /*编辑器*/
    var ue = UE.getEditor('myEditor', {
        autoHeightEnabled: false,
        initialFrameHeight: 300,
        wordCount: false,
        maximumWords: 100000,
        autoFloatEnabled:false
    });
    /*获取编辑器内的内容*/
    function getContent() {
        return (ue.getContent());
    }
    /*监听关联栏目*/
    var columnIdArr = [];
    window.addEventListener("storage", function (e) {
        if(e.key === "add_column"){
            var columnVal = e.newValue;
            columnVal = JSON.parse(columnVal);
            window.localStorage.removeItem("add_column")
            var html = "";
            for(var k in columnVal){
                if(columnIdArr.length !== 0){
                    for(var l in columnIdArr){
                        if(columnIdArr[l] === columnVal[k].id){
                            columnVal.splice(k,1);
                        }
                    }
                }
            }
            for(var i in columnVal){
                columnIdArr.push(columnVal[i].id)
                var payNum = "";
                if(columnVal[i].price === '0.00'){
                    payNum = "免费";
                }else {
                    payNum = "￥"+columnVal[i].price;
                }
                html += '<div class="column-box" style="display: flex;align-items: center;padding: 10px 0;border-bottom: 1px solid #333;">\n' +
                    '                            <img src="'+columnVal[i].image+'" style="width: 64px;height: 64px;" alt="">\n' +
                    '                            <div style="margin-left: 10px;width: 260px;">\n' +
                    '                                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">'+columnVal[i].name+'</div>\n' +
                    '                                <div style="margin-top: 5px;">'+columnVal[i].nickname+'</div>\n' +
                    '                                <div style="margin-top: 5px;">'+payNum+'</div>\n' +
                    '                            </div>\n' +
                    '                            <div data-id="'+columnVal[i].id+'" class="cancel-relation" style="background-color: #fff;border: 1px solid #e4e4e4;color: #333;padding: 5px 10px;font-size: 12px;border-radius: 4px;cursor: pointer;flex-shrink: 0">取消关联</div>\n' +
                    '                        </div>'
            }
            $("#column_content").append(html)
            console.log(columnIdArr)
        }
    });
    $("body").on("click",".cancel-relation",function () {
        var id = $(this).data("id");
        $(this).parent().remove();
        for(var i in columnIdArr){
            if(columnIdArr[i] === id){
                columnIdArr.splice(i,1);
            }
        }
    })
    /*上传视频*/
    var mediaFile = "";
    var video_info = "";
    function getSignature() {
        return $eb.axios.post('/commonapi/Index/createSignature').then(function (response) {
            return response.data.data.signature;
        })
    };
    $('#video-file').on('change', function() {
        mediaFile = document.getElementById("video-file").files[0];
        var file = document.getElementById("video-file").files[0];
        var tag = $(['<span class="uploaded-info" id="upload1">',
            '<span class="tit">文件名：</span><span class="info">['+file.name+']</span>',
            '<span class="tit">大小：</span><span class="info">['+(file.size/1024).toFixed(1)+'KB]</span>',
            '<span><button class="layui-btn layui-btn-xs layui-btn-danger in-delete">删除</button></span>',
            '</span>'].join(''));
        $('#video-expand').append(tag);
    })
    //删除
    $('body').on('click','.in-delete', function(){
        $('#video-file').val('');
        $('#video-expand').empty();
    });

    $('#qcloud-video-upload').click(function() {
        // console.log('111', mediaFile)
        var tcVod = new TcVod.default({
            getSignature: getSignature
        })
        var uploader = tcVod.upload({
            mediaFile: mediaFile,
        })
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
            $eb.message('success',"上传成功");
        })
            .catch(function (err) {
                console.log(err)
                $eb.message('error',"上传失败");
            })
    })

    layui.use('upload', function(){
        var upload = layui.upload;
        var qCloudUpload = layui.upload;
        //执行实例
        var uploadInst = upload.render({

            elem: '#video-upload',
            url: '{:Url('widget.uplodes/upload')}',//'http://www.shop.me/index.php/admin/widget.uplodes/upload',
            data: {
                path: 'video'
            },
            field: 'file',
            accept: 'video',
            done: function (res) {
                var urli = res.src
                video_info = res.src;
                $('#urli').val(urli);
                $eb.message('success','上传成功');
                return false;
            },
            progress: function(n){
                console.log(n)
                var percent = n + '%' //获取进度百分比
                layui.use('element', function(){
                    var element = layui.element;
                    element.progress('demo', percent); //可配合 layui 进度条元素使用
                    $('#percent').attr('lay-percent', percent)
                });
            }
        });
    });
</script>
{if condition="$style eq 'create'"}
<script>
    /*获取栏目*/
    $.ajax({
        url: "{:Url('get_class_list')}",
        data: {},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.code === 200) {
                for(var i in res.data){
                    $("#column").append('<option value='+res.data[i].id+'>'+res.data[i].name+'</option>')
                }
                formSelects.render();
            }
        }
    });
    /*获取分类*/
    $.ajax({
        url: "{:Url('get_category_list')}",
        data: {},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.code === 200) {
                for(var i in res.data){
                    $("#category").append('<option value='+res.data[i].id+'>'+res.data[i].cate_name+'</option>')
                }
                form.render();
            }
        }
    });
    $("#save").on("click",function () {
        var pid = "";
        var bigSaleMode = parseInt($("input[name='big-sale-mode']:checked").val());
        if(bigSaleMode === 0){
            pid = '0'
        }else {
            columnIdArr = [];
            $('#column_content .column-box .cancel-relation').each(function (index, domEle) {
                if (columnIdArr.indexOf($(domEle).data('id')) < 0) columnIdArr.push($(domEle).data('id'));
            })
            pid = columnIdArr.join(',')
        }
        var list = {};
        list.is_column = 0;//专栏
        list.type = 3;//视频
        list.pid = pid;//关联专栏
        list.category_id = $("#category option:selected").val();//分类
        list.name = $("#title").val();//专栏名称
        list.author_id = formSelects.value('user_select', 'val')[0];//作者uid
        list.introduction = $("#info").val();//简介
        list.keyword = $("#keyword").val();//关键字
        list.image = $("#image_input").val();//主页图片
        list.images = $("#image_input2").val();//轮播图
        list.content = getContent();//详情
        list.is_free = $("input[name='price']:checked").val();//免费付费
        list.price = $("#price").val();//售价
        list.cost_price = $("#cost_price").val();//成本价
        list.ot_price = $("#ot_price").val();//市场价
        list.cid = layui.formSelects.value('select_base_column','val').join(",");//关联栏目
        list.sort = $("#sort").val();//排序
        list.sales = $("#sales").val();//销量
        list.strip_num = $("#strip_num").val();//剥比
        list.ficti_sales = $("#ficti_sales").val();//虚拟销量
        list.score = $("#score").val();//赠送积分
        list.is_show = $("input[name='state']:checked").val();//上下架
        if (video_info !== "") {
            list.info = video_info.video.url;/* 视频 */
        } else {
            list.info = video_info;
        }
        if(list.category_id === ""){
            $eb.message('error', '请选择商品分类');
            return false;
        }
        if(list.name === ""){
            $eb.message('error', '请输入商品名称');
            return false;
        }
        if(list.author_id === ""|| list.author_id === undefined){
            $eb.message('error', '请选择作者');
            return false;
        }
        if(list.image === ""){
            $eb.message('error', '请选择主要图片');
            return false;
        }
        if(list.images === ""){
            $eb.message('error', '请选择轮播图片');
            return false;
        }
        if(list.content === ""){
            $eb.message('error', '请输入商品详情');
            return false;
        }
        if(list.is_free === 0){
            if(list.price <= 0){
                $eb.message('error', '请输入售价');
                return false;
            }
            if(list.cost_price <= 0){
                $eb.message('error', '请输入成本价');
                return false;
            }
            if(list.ot_price <= 0){
                $eb.message('error', '请输入市场价');
                return false;
            }
        }
        if(bigSaleMode === 1){
            list.price = 0;//售价
            list.cost_price = 0;//成本价
            list.ot_price = 0;//市场价
            if(columnIdArr.length === 0){
                $eb.message('error', '请关联专栏');
                return false;
            }
        }
        if(list.info === ""){
            $eb.message('error', '请上传视频');
            return false;
        }
        $.ajax({
            url: "{:Url('save')}",
            data: list,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.code === 200) {
                    $eb.message('success', res.msg);
                    parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                }else {
                    $eb.message('error', res.msg);
                }
            }
        });
    })
</script>
{/if}
{if condition="$style eq 'edit'"}
{volist name="info.images" id="v"}
<script>
    imgVal2.push("{$v}");
</script>
{/volist}
<script>
    audio_url = '{$info.media_url}';
    //关联栏目
    var columnId = "{$info.pid}";
    columnIdArr = columnId.split(',');
    var imgInputVal = imgVal2.join(",");
    $('#image_input2').val(imgInputVal);
    /*获取分类*/
    var NowCategoryId = "{$info.category_id}";
    $.ajax({
        url: "{:Url('get_category_list')}",
        data: {},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.code === 200) {
                for(var i in res.data){
                    if(res.data[i].id == NowCategoryId){
                        $("#category").append('<option selected="true" value='+res.data[i].id+'>'+res.data[i].cate_name+'</option>')
                    }else {
                        $("#category").append('<option value='+res.data[i].id+'>'+res.data[i].cate_name+'</option>')
                    }
                }
                form.render();
            }
        }
    });
    /*获取栏目*/
    var NowClassId = "{$info.cid}";
    var NowClassIdArr = NowClassId.split(',');
    $.ajax({
        url: "{:Url('get_class_list')}",
        data: {},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.code === 200) {
                for(var i in res.data){
                    for(var j in NowClassIdArr){
                        if(NowClassIdArr[j] == res.data[i].id){
                            $("#column").append('<option selected="true" value='+res.data[i].id+'>'+res.data[i].name+'</option>')
                        }else {
                            $("#column").append('<option value='+res.data[i].id+'>'+res.data[i].name+'</option>')
                        }
                    }
                }
                formSelects.render();
            }
        }
    });
    var contentHtml = $('<div>').html('{$info.content}').html();
    contentHtml.replace(/&quot;/g, "\"");
    ue.addListener("ready", function () {
        ue.setContent(contentHtml);
    });
    $("#save").on("click",function () {
        var pid = "";
        var bigSaleMode = parseInt($("input[name='big-sale-mode']:checked").val());
        if(bigSaleMode === 0){
            pid = '0'
        }else {
            columnIdArr = [];
            $('#column_content .column-box .cancel-relation').each(function (index, domEle) {
                if (columnIdArr.indexOf($(domEle).data('id')) < 0) columnIdArr.push($(domEle).data('id'));
            })
            pid = columnIdArr.join(',')
        }
        var list = {};
        list.is_column = 0;//专栏
        list.type = 3;//视频
        list.pid = pid;//关联专栏
        list.id = "{$info.id}";//id
        list.category_id = $("#category option:selected").val();//分类
        list.name = $("#title").val();//专栏名称
        list.author_id = formSelects.value('user_select', 'val')[0];//作者uid
        list.introduction = $("#info").val();//简介
        list.keyword = $("#keyword").val();//关键字
        list.image = $("#image_input").val();//主页图片
        list.images = $("#image_input2").val();//轮播图
        list.content = getContent();//详情
        list.is_free = $("input[name='price']:checked").val();//免费付费
        list.price = $("#price").val();//售价
        list.cost_price = $("#cost_price").val();//成本价
        list.ot_price = $("#ot_price").val();//市场价
        list.cid = layui.formSelects.value('select_base_column','val').join(",");//关联栏目
        list.sort = $("#sort").val();//排序
        list.sales = $("#sales").val();//销量
        list.strip_num = $("#strip_num").val();//剥比
        list.ficti_sales = $("#ficti_sales").val();//虚拟销量
        list.score = $("#score").val();//赠送积分
        list.is_show = $("input[name='state']:checked").val();//上下架
        if (video_info !== "") {
            list.info = video_info.video.url;/* 视频 */
        } else {
            list.info = video_info || '{$info.info}';
        }
        if(list.category_id === ""){
            $eb.message('error', '请选择商品分类');
            return false;
        }
        if(list.name === ""){
            $eb.message('error', '请输入商品名称');
            return false;
        }
        if(list.author_id === ""|| list.author_id === undefined){
            $eb.message('error', '请选择作者');
            return false;
        }
        if(list.image === ""){
            $eb.message('error', '请选择主要图片');
            return false;
        }
        if(list.images === ""){
            $eb.message('error', '请选择轮播图片');
            return false;
        }
        if(list.content === ""){
            $eb.message('error', '请输入商品详情');
            return false;
        }
        if(list.is_free === 0){
            if(list.price <= 0){
                $eb.message('error', '请输入售价');
                return false;
            }
            if(list.cost_price <= 0){
                $eb.message('error', '请输入成本价');
                return false;
            }
            if(list.ot_price <= 0){
                $eb.message('error', '请输入市场价');
                return false;
            }
        }
        if(bigSaleMode === 1){
            list.price = 0;//售价
            list.cost_price = 0;//成本价
            list.ot_price = 0;//市场价
            if(columnIdArr.length === 0){
                $eb.message('error', '请关联专栏');
                return false;
            }
        }
        if(!list.info){
            $eb.message('error', '请上传视频');
            return false;
        }
        $.ajax({
            url: "{:Url('update')}",
            data: list,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.code === 200) {
                    $eb.message('success', res.msg);
                    parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                }else {
                    $eb.message('error', res.msg);
                }
            }
        });
    })
</script>
{/if}

{if condition="$column"}
<script>
    $("input[name='big-sale-mode']").eq(1).attr('checked', 'true');
    let taskList = {
        image:"{$column.image}",
        price:"{$column.price}",
        name:"{$column.name}",
        nickname:"123",
        id:"{$column.id}"
    };
    columnIdArr.push(taskList.id)
    let payNum = "";
    if(taskList.price === '0.00'){
        payNum = "免费";
    }else {
        payNum = "￥"+taskList.price;
    }
    let html = '<div class="column-box" style="display: flex;align-items: center;padding: 10px 0;border-bottom: 1px solid #333;">\n' +
        '                            <img src="'+taskList.image+'" style="width: 64px;height: 64px;" alt="">\n' +
        '                            <div style="margin-left: 10px;width: 260px;">\n' +
        '                                <div style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">'+taskList.name+'</div>\n' +
        '                                <div style="margin-top: 5px;">'+taskList.nickname+'</div>\n' +
        '                                <div style="margin-top: 5px;">'+payNum+'</div>\n' +
        '                            </div>\n' +
        '                            <div data-id="'+taskList.id+'" class="cancel-relation" style="background-color: #fff;border: 1px solid #e4e4e4;color: #333;padding: 5px 10px;font-size: 12px;border-radius: 4px;cursor: pointer;flex-shrink: 0">取消关联</div>\n' +
        '                        </div>'
    $("#column_content").append(html)
</script>
{/if}
{/block}
