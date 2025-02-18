<!DOCTYPE html>
<!--suppress JSAnnotator -->
<html lang="zh-CN">
<head>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <link rel="stylesheet" href="//at.alicdn.com/t/font_1497503_6psufycrn4m.css">
    <script src="{__PLUG_PATH}jquery-1.10.2.min.js"></script>
    <script src="{__PLUG_PATH}layui/layui.js"></script>
</head>
<style>
    .layui-btn + .layui-btn {
        margin: 0;
    }

    .main {
        margin: 12px 0;
    }

    .main-top {
        border-bottom: 1px solid #e5e5e5;
        height: 5px;
        width: 100%;
        position: fixed;
        top: 0;
        background-color: #FFFFFF;
        z-index: 100;
    }

    .main .left {
        max-width: 125px;
        height: 100%;
        width: 115px;
        border-right: 1px solid #e5e5e5;
        border-left: 1px solid #e5e5e5;
        float: left;
    }

    .main .left .left-top {
        position: fixed;
        padding: 10px 10px 0;
        height: 35px;
        border-bottom: 1px solid #e5e5e5;
        background-color: #eee;
    }

    .main .left .tabs-left {
        overflow-y: auto;
        height: 100%;
        width: 115px;
        position: fixed;
        top: 58px;
        border-right: 1px solid #e5e5e5;
    }

    .main ::-webkit-scrollbar {
        width: 3px;
        height: auto;
        background-color: #ddd;
    }

    .main ::-webkit-scrollbar-thumb {
        border-radius: 1px;
        -webkit-box-shadow: inset 0 0 6px rgba(255, 255, 255, .3);
        background-color: #02a7f0;
    }

    .main ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        border-radius: 1px;
        background: #e5e5e5;
    }

    .main .left .nav {
        margin: 0;
        padding-bottom: 100px;
    }

    .main .left .nav li {
        padding: 4px;
        height: 22px;
    }

    .main .left .nav li.active {
        background-color: #02a7f0;
    }

    .main .left .nav li.active a {
        color: #a7b1c2;
    }

    .main .left .nav li.child {
        padding: 2px;
        padding-left: 7px;
    }

    .main .right {
        width: calc(100% - 117px);
        float: right;
    }

    .main .right .right-top {
        position: fixed;
        background-color: #fff;
        z-index: 1000;
        width: 100%;
        padding: 7px 10px 0;
        height: 38px;
        border-bottom: 1px solid #e5e5e5;
        border-top: 1px solid #e5e5e5;
    }

    .main .right .imagesbox {
        position: fixed;
        top: 58px;
        min-height: 200px;
        height: calc(100% - 88px);;
        overflow-y: auto;
    }

    .main .right .imagesbox .image-item {
        position: relative;
        display: inline-block;
        width: 112px;
        height: 112px;
        border: 1px solid #ECECEC;
        background-color: #F7F6F6;
        cursor: default;
        margin: 10px 0 0 10px;
        padding: 5px;
    }

    .main .right .imagesbox .image-item img {
        width: 112px;
        height: 112px;
    }

    .main .right .imagesbox .on {
        border: 3px dashed #0092DC;
        padding: 3px;
    }

    .main .right .foot-tool {
        position: fixed;
        bottom: 0px;
        width: calc(100% - 117px);
        background-color: #fff;
        height: 30px;
        padding: 7px 10px 0;
        border-top: 1px solid #e5e5e5;
    }

    .main .right .foot-tool .page {
        padding: 0px 10px;
        float: right;
    }

    .main .right .foot-tool .page ul {
        width: 100%
    }

    .main .right .foot-tool .page li {
        float: left;
        margin: 0px;
    }

    .main .right .foot-tool .page .disabled span {
        background-color: #e6e6e6 !important;
        color: #bbb !important;
        cursor: no-drop;
        padding: 0px 10px;
        height: 30px;
        line-height: 30px;
        display: block;
    }

    .main .right .foot-tool .page .active span {
        background-color: #428bca;
        color: #fff;
        border-color: #428bca;
        padding: 0px 10px;
        height: 30px;
        line-height: 30px;
        display: block;
    }

    .main .right .foot-tool .page li a {
        border: 1px solid #e5e5e5;
        padding: 0px 10px;
        height: 28px;
        line-height: 28px;
        display: block;
    }

    .right-content {
        display: none;
        padding-left: 20px;
        padding-right: 15px;
        overflow: auto;
        height: 315px;
    }

    .active-content {
        display: block;
    }

    .right-title {
        font-size: 14px;
        color: #333;
        margin-top: 10px;
        display: flex;
        align-items: center;
    }

    .tab-box-content {
        display: flex;
        flex-wrap: wrap;
    }

    .tab-box {
        border: 1px solid #aaa;
        color: #333;
        margin-top: 10px;
        margin-right: 10px;
        width: 84px;
        padding: 4px 5px;
        text-align: center;
        font-size: 14px;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
        cursor: pointer;
    }


    .nav-tabs li{
        cursor: pointer;
    }
    .active{
        color: #fff;
    }
    .search-content{
        margin-top: 10px;
        display: flex;
        align-items: center;
    }
    .search-box{
        background-color: #FFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 6px 12px;
        width: 400px;
    }
    .search-box::-webkit-input-placeholder {
        color: #dadada;
    }
    .search-box:-moz-placeholder {
        color: #dadada;
    }
    .search-box:-ms-input-placeholder {
        color: #dadada;
    }
    .search-box:focus{
        border-color: #0ca6f2;
    }
    .search-btn{
        width: 70px;
        height: 30px;
        line-height: 30px;
        color: #fff;
        background-color: #0ca6f2;
        margin-left: 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 4px;
    }
    .upload-btn{
        width: 70px;
        height: 30px;
        line-height: 30px;
        color: #fff;
        background-color: #0ca6f2;
        margin-left: 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 4px;
    }
    .tip-text{
        font-size: 12px;
        color: #666;
    }
    .search-result-text{
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }
    .result-box{
        display: flex;
        border-bottom: 1px solid #eee;
        margin-top: 10px;
        padding-bottom: 10px;
    }
    .logo-img{
        width: 60px;
        height: 60px;
        margin-right: 10px;
    }
    .info-box{
        width: 400px;
    }
    .info-box .title{
        font-size: 16px;
    }
    .info-text{
        margin-top: 22px;
        font-size: 12px;
    }
    .info-text span{
        margin-right: 10px;
    }
    .select-btn{
        margin-top: 18px;
        font-size: 14px;
        height: 24px;
        line-height: 24px;
        color: #333;
        text-align: center;
        width: 50px;
        border-radius: 2px;
        border: 1px solid #666;
        margin-right: 0;
        padding: 0;
    }
    .search-result-shop-title{
        font-size: 16px;
        color: #333;
        margin-top: 10px;
    }
    .active-tab {
        border-color: #0ca6f2;
        color: #0ca6f2;
    }
</style>
<body>
<div class="main">
    <div class="main-top"></div>
    <div class="left">
        <div class="tabs-left" style="margin-top: -52px">
            <ul class="nav nav-tabs">
                {volist name="all_tab_list" id="v"}
                {if condition="$i eq '1'"}
                <li class="active list-tab" data-name="{$key}">{$v.title}</li>
                {else/}
                <li class="list-tab" data-name="{$key}">{$v.title}</li>
                {/if}
                {/volist}
            </ul>
        </div>
    </div>
    <div class="right">

        {volist name="all_tab_list" id="v"}
        {switch name="$v.type" }
            {case value="link_list" break="1"}
                {switch name="$v.level" }
                    {case value="1" break="1"}
                        <div data-key="{$key}" class="right-content">
                            <div class="right-title"><i class="iconfont iconleixing"></i>{$v.title}</div>
                            <div class="tab-box-content">
                                {volist name="v.link_list" id="tab" key="k"}
                                <div class="tab-box ConfirmChoices" data-link-val="{$v.title}-{$tab.title}||{$tab.link_url}">{$tab.title}</div>
                                {/volist}
                            </div>
                        </div>
                    {/case}
                    {case value="2" break="1"}
                        {if condition="$i eq '1'"}
                            <div data-key="{$key}" class="right-content  active-content">
                                {volist name="v.tab_list" id="tab" key="k"}
                                <div class="right-title"><i class="iconfont iconleixing"></i>{$tab.tab_title}</div>
                                <div class="tab-box-content">
                                    {volist name="$tab.link_list" id="child_tab" key="k"}
                                    <div class="tab-box ConfirmChoices" data-link-val="{$v.title}-{$child_tab.title}||{$child_tab.link_url}">{$child_tab.title}</div>
                                    {/volist}
                                </div>
                                {/volist}
                            </div>
                        {else/}
                            <div data-key="{$key}" class="right-content">
                                {volist name="v.tab_list" id="tab" key="k"}
                                <div class="right-title"><i class="iconfont iconleixing"></i>{$tab.tab_title}</div>
                                <div class="tab-box-content">
                                    {volist name="$tab.link_list" id="child_tab" key="k"}
                                    <div class="tab-box ConfirmChoices" data-link-val="{$v.title}-{$child_tab.title}||{$child_tab.link_url}">{$child_tab.title}</div>
                                    {/volist}
                                </div>
                                {/volist}
                            </div>
                        {/if}
                    {/case}
                {/switch}
            {/case}
            {case value="input_select"}
                <div data-key="{$key}" class="right-content">
                    <div class="right-title"><i class="iconfont iconleixing"></i>{$v.title}</div>
                    <div class="search-content">
                        <input class="search-box" placeholder="{$v.tip}" data-keyword-key="{$key}" type="text">
                        <div class="search-btn" data-search-key="{$key}" data-search-url="{$v.select_url}">搜索</div>
                    </div>
                    <div data-result-key="{$key}" class="search-box-content">

                    </div>
                </div>
            {/case}
            {case value="input_url"}
            <div data-key="{$key}" class="right-content">
                <div class="right-title"><i class="iconfont iconleixing"></i>{$v.title}</div>
                <div class="search-content">
                    <input class="search-box" id="search_box" type="text">
                </div>
                <div style="display: flex;align-items: center;margin-top: 3px;">
                    <input id="target" type="checkbox">
                    <label for="target" style="font-size: 12px;color: #666">新窗口打开</label>
                </div>
                <div style="font-size: 12px;color: #999">
                    <div style="color: red">*使用须知:</div>
                    <div>1.站外链接点击为新窗口打开(仅H5浏览器中有效，app、小程序微信环境下均无效)</div>
                    <div>2.有效输入地址格式举例:https://www.baidu.com/</div>
                </div>
                <div class="upload-btn ConfirmChoices" style="margin-top: 10px;margin-left: 0;">提交</div>
            </div>
            {/case}
            {case value = "wechat_input"}
            <div data-key="{$key}" class="right-content" style="color: #999">
                <div class="right-title"><i class="iconfont iconleixing"></i>{$v.title}</div>
                <div class="search-content">
                    <div style="width: 100px;text-align: end;">
                        <span>app_id</span><i class="layui-icon layui-icon-about" style="margin: 0 5px;"></i>
                    </div>
                    <input class="search-box" placeholder="[微信]要打开的小程序AppId" style="margin-left: 10px;" id="app_id" type="text">
                </div>
                <div class="search-content">
                    <div style="width: 100px;text-align: end;">
                        <span>path</span><i class="layui-icon layui-icon-about" style="margin: 0 5px;"></i>
                    </div>
                    <input class="search-box" placeholder="[微信]打开的页面路径，如pages/index/index，开头请勿加“/”" style="margin-left: 10px;" id="app_path" type="text">
                </div>
                <div class="upload-btn ConfirmChoicesLink" style="margin-top: 10px;margin-left: 0;">提交</div>
            </div>
            {/case}
        {/switch}
        {/volist}

    </div>
</div>
</body>
</html>
<script>
    $(".list-tab").on("click",function () {
      $(".active").removeClass("active");
      $(this).addClass("active");
      var thisName = $(this).data("name");
      $(".active-content").removeClass("active-content");
      $('[data-key='+thisName+']').addClass("active-content");
    });

    $("body").on("click",".tab-box",function () {
      $(".active-tab").removeClass("active-tab");
      $(this).addClass("active-tab");
    })


    $(".search-btn").on("click",function () {
      var url = $(this).data("search-url");
      var key = $(this).data("search-key");
      var keyWord = $('[data-keyword-key='+key+']').val();
      var resultHtml = "";
      $.ajax({
        url:url,
        data:{
          keyword:keyWord
        },
        type:'get',
        dataType:'json',
        success:function(res){
          if(key === "forum"){
            console.log(res.data.count)
            if(res.data.count === 0){
              resultHtml = '<p class="search-result-text">抱歉！未查询到与“'+keyWord+'”相关的版块，请更换关键字后重试。</p>'
            }else {
              resultHtml = '<p class="search-result-text">搜索到'+res.data.count+'个相关版块</p>'
              for(var i in res.data.list){
                resultHtml += '<div class="result-box">\n' +
                  '                            <img class="logo-img" src='+res.data.list[i].logo+' alt="">\n' +
                  '                            <div class="info-box">\n' +
                  '                                <div class="title">'+res.data.list[i].name+'</div>\n' +
                  '                                <div class="info-text"><span>版块类型:'+toForumType(res.data.list[i].type)+'</span><span>上级版块:'+res.data.list[i].pid_name+'</span></div>\n' +
                  '                            </div>\n' +
                  '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.tab_title+'-'+res.data.list[i].link_title+'||'+res.data.list[i].link_url+'">选定</div>\n' +
                  '                        </div>'
              }
            }
          }else if(key === "goods"){
            var totalCount = res.data.eb_goods.count+res.data.zg_goods.count+res.data.zg_column_goods.count+res.data.shop_goods.count;
            if(totalCount === 0){
              resultHtml = '<p class="search-result-text">抱歉！未查询到与“'+keyWord+'”相关的商品，请更换关键字后重试。</p>'
            }else {
              resultHtml = '<p class="search-result-text">搜索到'+totalCount+'个相关商品</p>'
              if(res.data.eb_goods.count > 0){
                resultHtml += '<p class="search-result-shop-title">商城商品</p>'
                for(var i in res.data.eb_goods.list){
                  resultHtml += '<div class="result-box">\n' +
                    '                            <img class="logo-img" src='+res.data.eb_goods.list[i].image+' alt="">\n' +
                    '                            <div class="info-box">\n' +
                    '                                <div class="title">'+res.data.eb_goods.list[i].store_name+'</div>\n' +
                    '                                <div class="info-text"><span>价格:'+res.data.eb_goods.list[i].price+'</span><span>销量:'+res.data.eb_goods.list[i].sales+'</span></div>\n' +
                    '                            </div>\n' +
                    '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.eb_goods.tab_title+'-'+res.data.eb_goods.list[i].link_title+'||'+res.data.eb_goods.list[i].link_url+'">选定</div>\n' +
                    '                        </div>'
                }
              }
              if(res.data.zg_column_goods.count > 0){
                  resultHtml += '<p class="search-result-shop-title">知识付费专栏商品</p>'
                  res.data.zg_column_goods.list.forEach(function (item,index,array){
                      resultHtml += '<div class="result-box">\n' +
                          '<img class="logo-img" src='+item.image+' alt="">\n' +
                          '<div class="info-box">\n' +
                          '<div class="title">'+item.store_name+'</div>\n' +
                          '<div class="info-text"><span>价格:'+item.price+'</span><span>销量:'+item.sales+'</span></div>\n' +
                          '</div>\n' +
                          '<div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.zg_column_goods.tab_title+'-'+item.link_title+'||'+item.link_url+'">选定</div>\n' +
                          '</div>'
                  })
              }
              if(res.data.zg_goods.count > 0){
                resultHtml += '<p class="search-result-shop-title">知识付费单品商品</p>'
                for(var i in res.data.zg_goods.list){
                  resultHtml += '<div class="result-box">\n' +
                    '                            <img class="logo-img" src='+res.data.zg_goods.list[i].image+' alt="">\n' +
                    '                            <div class="info-box">\n' +
                    '                                <div class="title">'+res.data.zg_goods.list[i].store_name+'</div>\n' +
                    '                                <div class="info-text"><span>价格:'+res.data.zg_goods.list[i].price+'</span><span>销量:'+res.data.zg_goods.list[i].sales+'</span></div>\n' +
                    '                            </div>\n' +
                    '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.zg_goods.tab_title+'-'+res.data.zg_goods.list[i].link_title+'||'+res.data.zg_goods.list[i].link_url+'">选定</div>\n' +
                    '                        </div>'
                }
              }
              if(res.data.shop_goods.count > 0){
                resultHtml += '<p class="search-result-shop-title">积分商城商品</p>'
                for(var i in res.data.shop_goods.list){
                  resultHtml += '<div class="result-box">\n' +
                    '                            <img class="logo-img" src='+res.data.shop_goods.list[i].image+' alt="">\n' +
                    '                            <div class="info-box">\n' +
                    '                                <div class="title">'+res.data.shop_goods.list[i].store_name+'</div>\n' +
                    '                                <div class="info-text"><span>价格:'+res.data.shop_goods.list[i].score_price+'积分+'+res.data.shop_goods.list[i].cash_price+'元</span><span>销量:'+res.data.shop_goods.list[i].sales+'</span></div>\n' +
                    '                            </div>\n' +
                    '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.shop_goods.tab_title+'-'+res.data.shop_goods.list[i].link_title+'||'+res.data.shop_goods.list[i].link_url+'">选定</div>\n' +
                    '                        </div>'
                }
              }
            }
          }else if(key === "post"){
            if(res.data.count === 0){
              resultHtml = '<p class="search-result-text">抱歉！未查询到与“'+keyWord+'”相关的帖子，请更换关键字后重试。</p>'
            }else {
              resultHtml = '<p class="search-result-text">搜索到'+res.data.count+'个相关帖子</p>'
              for(var i in res.data.list){
                resultHtml += '<div class="result-box">\n' +
                  '                            <div class="info-box" style="width: 455px">\n' +
                  '                                <div class="title">'+res.data.list[i].title+'</div>\n' +
                  '                                <div class="info-text"><span>作者:'+res.data.list[i].user_info.nickname+'</span><span>类型:'+toForumType(res.data.list[i].type)+'</span><span>所属版块及分类:'+toPname(res.data.list[i].forum_name,res.data.list[i].class_name)+'</span><span>评论:'+res.data.list[i].reply_count+'</span></div>\n' +
                  '                            </div>\n' +
                  '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.tab_title+'-'+res.data.list[i].link_title+'||'+res.data.list[i].link_url+'">选定</div>\n' +
                  '                        </div>'
              }
            }
          }else if(key==='topic' || key=='jc_topic') {
              if (res.data.count === 0) {
                  resultHtml = '<p class="search-result-text">抱歉！未查询到与“' + keyWord + '”相关的话题，请更换关键字后重试。</p>'
              } else {
                  resultHtml = '<p class="search-result-text">搜索到' + res.data.count + '个相关话题</p>'
                  for (var i in res.data.list) {
                      resultHtml += '<div class="result-box">\n' +
                          '                            <div class="info-box" style="width: 455px">\n' +
                          '                                <div class="title">' + res.data.list[i].title + '</div>\n' +
                          '                            </div>\n' +
                          '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="' + res.data.tab_title + '-' + res.data.list[i].link_title + '||' + res.data.list[i].link_url + '">选定</div>\n' +
                          '                        </div>'
                  }
              }
         }else if(key =="activity" || key=="trial"){
              if(res.data.count === 0){
                  resultHtml = '<p class="search-result-text">抱歉！未查询到与“'+keyWord+'”相关的活动，请更换关键字后重试。</p>'
              }else {
                  resultHtml = '<p class="search-result-text">搜索到'+res.data.count+'个相关活动</p>'
                  for(var i in res.data.list){
                      resultHtml += '<div class="result-box">\n' +
                          '                            <img class="logo-img" src='+res.data.list[i].cover+' alt="">\n' +
                          '                            <div class="info-box">\n' +
                          '                                <div class="title">'+res.data.list[i].title+'</div>\n' +
                          '                                <div style="font-size: 12px;margin-top: 5px">'+res.data.list[i].address+'</div><div style="font-size: 12px;">'+res.data.list[i].create_time+'</div>\n' +
                          '                            </div>\n' +
                          '                            <div class="tab-box select-btn ConfirmChoices" data-link-val="'+res.data.tab_title+'-'+res.data.list[i].link_title+'||'+res.data.list[i].link_url+'">选定</div>\n' +
                          '                        </div>'
                  }
              }
          }
          console.log(key);
            console.log(resultHtml);
          $('[data-result-key='+key+']').html(resultHtml);
        }
      });
    })

    function toForumType(type){
      if(type === 1){
        return"普通版块";
      }else if(type === 6){
        return"视频（横版）";
      }else if(type === 8){
        return"聚合版块";
      }else if(type === 4){
        return"资讯";
      }
    }
    
    function toPname(fName,cName) {
      if(cName === "未关联分类"){
        return fName
      }else {
        return fName+"-"+cName
      }
    }

    $(document).keyup(function(event){
      if(event.keyCode === 13){
        var key = $(".active").data("name");
        if(key === "forum" || key === "goods" || key === "post"){
          $(".search-btn").click();
        }
      }
    });
</script>
<script>
  var parentinputname = '{$Request.param.fodder}';//父级input name
  $("body").on("click",".ConfirmChoices",function () {
      var urlText = $("#search_box").val();
      var linkVal = $(this).data("link-val");
      var activeLi = $(".active").data("name");
      var target = $('#target').is(":checked");
    if(parent.$f){
      if(activeLi === "other"){
        if(target){
          parent.$f.setValue(parentinputname,"站外链接||"+urlText+"||blank");
        }else {
          parent.$f.setValue(parentinputname,"站外链接||"+urlText);
        }
      }else {
        parent.$f.setValue(parentinputname,linkVal);
      }
      parent.$f.closeModal();
    }else {
        if(activeLi === "other"){
            if(target){
                window.localStorage.setItem("linkUrl","站外链接||"+urlText+"||blank");
            }else {
                window.localStorage.setItem("linkUrl","站外链接||"+urlText);
            }
        }else {
            window.localStorage.setItem("linkUrl",linkVal);
        }
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    }
  })
  $("body").on("click",".ConfirmChoicesLink",function () {
    var appId = $("#app_id").val();
    var path = $("#app_path").val();
    parent.$f.setValue(parentinputname,"跳转小程序||"+'wxid='+appId+'&path='+path);
    parent.$f.closeModal();
  })
</script>


