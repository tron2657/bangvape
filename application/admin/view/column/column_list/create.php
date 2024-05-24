{extend name="public/container"}
{block name="head_top"}
<link rel="stylesheet" href="{__PLUG_PATH}formselects/formSelects-v4.css">
<script src="{__PLUG_PATH}formselects/formSelects-v4.min.js"></script>
<script src="{__PLUG_PATH}sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
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
</style>
{/block}
{block name="content"}
<div class="body">
    <form class="form-horizontal layui-form">
        {if condition="$style eq 'create'"}
        <div>
            <div class="title">
                专栏信息
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏分类：</span>
                        <select name="category" id="category" value="" lay-filter="aihao" style="width: 100%">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏名称：</span>
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
                        <span class="input-group-addon" style="border: none">专栏简介：</span>
                        <textarea class="layui-textarea" id="info" placeholder="请输入简介信息" style="resize: none;"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏关键字：</span>
                        <input placeholder="多个英文状态下的逗号隔开" name="title" class="layui-input" id="keyword" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏主要图片：<br>（400*200px）</span>
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
                        <span class="input-group-addon" style="border: none">专栏轮播图片：<br>（400*200px）</span>
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
<!--            <div class="title">
                专栏详情
            </div>
            <textarea type="text/plain" id="myEditor" style="width:100%;padding-left: 10px;margin-bottom: 20px;"></textarea> -->
            <div class="title">
                专栏价格
            </div>
            <div style="padding: 4px 20px 8px 20px;background-color: #f2f2f2;border-radius: 10px;">
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
                        <span class="input-group-addon" style="border: none">专栏状态：</span>
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
                专栏信息
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏分类：</span>
                        <select name="category" id="category" value="" lay-filter="aihao" style="width: 100%">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏名称：</span>
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
                        <span class="input-group-addon" style="border: none">专栏简介：</span>
                        <textarea class="layui-textarea" id="info" placeholder="请输入简介信息" style="resize: none;">{$info.introduction}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏关键字：</span>
                        <input placeholder="多个英文状态下的逗号隔开" name="title" class="layui-input" id="keyword" value="{$info.keyword}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon" style="border: none">专栏主要图片：<br>（400*200px）</span>
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
                        <span class="input-group-addon" style="border: none">专栏轮播图片：<br>（400*200px）</span>
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
<!--            <div class="title">
                专栏详情
            </div>
            <textarea type="text/plain" id="myEditor" style="width:100%;padding-left: 10px;margin-bottom: 20px;"></textarea> -->
            <div class="title">
                专栏价格
            </div>
            <div style="padding: 4px 20px 8px 20px;background-color: #f2f2f2;border-radius: 10px;">
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
                        <span class="input-group-addon" style="border: none">专栏状态：</span>
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
    // /*编辑器*/
    // var ue = UE.getEditor('myEditor', {
    //     autoHeightEnabled: false,
    //     initialFrameHeight: 300,
    //     wordCount: false,
    //     maximumWords: 100000,
    //     autoFloatEnabled:false
    // });
    /*获取编辑器内的内容*/
    // function getContent() {
    //     return (ue.getContent().replace(/\"/g,"\'"));
    // }
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
        var list = {};
        list.is_column = 1;//专栏
        list.category_id = $("#category option:selected").val();//分类
        list.name = $("#title").val();//专栏名称
        list.author_id = formSelects.value('user_select', 'val')[0];//作者uid
        list.introduction = $("#info").val();//简介
        list.keyword = $("#keyword").val();//关键字
        list.image = $("#image_input").val();//主页图片
        list.images = $("#image_input2").val();//轮播图
        list.content = "";//详情
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
		console.log(list.author_id)
        if(list.category_id === ""){
            $eb.message('error', '请选择专栏分类');
            return false;
        }
        if(list.name === ""){
            $eb.message('error', '请输入专栏名称');
            return false;
        }
        if(list.author_id === "" || list.author_id === undefined){
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
        // if(list.content === ""){
        //     $eb.message('error', '请输入专栏详情');
        //     return false;
        // }
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
    // var contentHtml = $('<div>').html("{$info.content}").html();
    // contentHtml.replace(/&quot;/g, "\"");
    // ue.addListener("ready", function () {
    //     ue.setContent(contentHtml);
    // });
    $("#save").on("click",function () {
        var list = {};
        list.is_column = 1;//专栏
        list.id = "{$info.id}";//专栏
        list.category_id = $("#category option:selected").val();//分类
        list.name = $("#title").val();//专栏名称
        list.author_id = formSelects.value('user_select', 'val')[0];//作者uid
        list.introduction = $("#info").val();//简介
        list.keyword = $("#keyword").val();//关键字
        list.image = $("#image_input").val();//主页图片
        list.images = $("#image_input2").val();//轮播图
        list.content = "";//详情
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
        if(list.category_id === ""){
            $eb.message('error', '请选择专栏分类');
            return false;
        }
        if(list.name === ""){
            $eb.message('error', '请输入专栏名称');
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
        // if(list.content === ""){
        //     $eb.message('error', '请输入专栏详情');
        //     return false;
        // }
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
{/block}
