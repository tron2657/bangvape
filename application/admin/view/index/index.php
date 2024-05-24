<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <script type="text/javascript" src="{__PLUG_PATH}axios.min.js"></script>
    <link href="https://at.alicdn.com/t/font_1768836_0zhmoiw0s2xl.css" rel="stylesheet">
    <title>后台管理系统</title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="{__FRAME_PATH}css/bootstrap.min.css" rel="stylesheet">
    <link href="{__FRAME_PATH}css/font-awesome.min.css" rel="stylesheet">
    <link href="{__FRAME_PATH}css/animate.min.css" rel="stylesheet">
    <!-- <link href="{__FRAME_PATH}css/style.min.css" rel="stylesheet"> -->
    <!-- 复制style.min.css并修改的文件 -->
    <link href="{__FRAME_PATH}css/style.copy.css" rel="stylesheet">
    <link href="{__FRAME_PATH}css/icon-home-page.css" rel="stylesheet">
    <style>
        .plus-content{
            width: 56px;
            display: flex;
            flex-direction: column;
            background-color: #1890FF;
            align-items: center;
        }
        .plus-box{
            display: flex;
            width: 56px;
            height: 56px;
            justify-content: center;
            align-items: center;
            color: #fff;
            opacity: 0.7;
        }
        .plus-box:hover{
            opacity: 1;
        }
        .plus-content .active-plus-box{
            background: #40A9FF;
            opacity: 1;
        }
        .plus-img{
            width: 24px;
            height: 24px;
        }
        .plus-text{
            margin-top: 3px;
            color: #fff;
            font-size: 12px!important;
        }
        .slimScrollDiv{
            width: 100%!important;
        }
        .new-ul{
            display: none;
        }
        .active-ul{
            display: block;
        }
        .message-box{
            position: fixed;
            right: 12px;
            bottom: 50px;
            width: 300px;
            height: 300px;
            z-index: 9999;
        }
        .close-msg{
            position: absolute;
            left: 0;
            top: -20px;
            padding: 0 5px;
            display: inline-block;
            height: 20px;
            color: #fff;
            background-color: #0ca6f2;
            cursor: pointer;
        }
        .icon {
            display: inline-block;
            width: 12px;
            height: 12px;
        }
        .icon img {
            width: 100%;
            height: 100%;
            position: relative;
            top: -1px;
        }
        /* 点击查询的样式 */
        .circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: absolute;
        }
        .animated-circles {
            position: absolute;
            right: 100px;
            bottom: 100px;
            cursor: pointer;
        }
        .zixun {
            z-index: 20;
        }
        .c-1 {
            animation: 8s scaleToggleOne infinite;
        }
        .c-2 {
            animation: 8s scaleToggleTwo infinite;

        }
        .c-3 {
            animation: 8s scaleToggleThree infinite;
        }
        /* 点击查询框的动画 */
        @keyframes scaleToggleOne { 
            0% {
            transform:scale(1);
            -webkit-transform:scale(1)
            }
            12% {
            transform:scale(2);
            -webkit-transform:scale(2)
            }
            25% {
            transform:scale(1);
            -webkit-transform:scale(1)
            }
        }
        @keyframes scaleToggleTwo { 
    0% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    5% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    15% {
    transform:scale(2);
    -webkit-transform:scale(2)
    }
    25% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    }
    @keyframes scaleToggleThree { 
    0% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    7% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    15% {
    transform:scale(2);
    -webkit-transform:scale(2)
    }
    25% {
    transform:scale(1);
    -webkit-transform:scale(1)
    }
    }
    .zixun img {
        width: 100%;
        height: 100%;
    }
    .msg {
        width: 115px;
        height: 30px;
        background-color: rgba(18,150,219);
        position: absolute;
        left: -135px;
        top: 10px;
        display: none;

    }
    .msg span {
        font-size: 16px;
        color: white;
        line-height: 30px;
        margin-left: 20px;
    }
    .small-arrow {
        width: 0px;
        border: 10px solid transparent;
        border-left-color: rgba(18,150,219);
        position: absolute;
        right: -20px;
        top: 5px;
    }
    .pop {
        width: 750px;
        height: 630px;
        background-color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        display: none;
        border: solid #C0C7CD 1px;
    }
    .pop-title {
        width: 100%;
        height: 40px;
        border-bottom: #EEEEEE;
        background-color: #F8F8F8;
        padding: 0 20px 0 20px;
        display: flex;
        justify-content: space-between;
    }
    .pop-title span {
        line-height: 40px;
        font-size: 14px;
        color: #333;
    }
    #close {
        display: inline-block;
        width: 20px;
        height: 20px;
        line-height: 40px;
        cursor: pointer;
    }
    .no-setting {
        width: 500px;
        margin: 150px auto 0;
        text-align: center;
    }
    .no-setting p:first-of-type{
        font-size: 20px;
        color: #333;
    }
    .no-setting p:nth-of-type(2) span{
        font-size: 18px;
        color: #2d8cf0;
    }
    .show {
        position: relative;
        top: 24px;
        opacity: 0;
    }
    #list-box {
        width: 430px;
        height: 50px;
        list-style: none;
        display: flex;
        margin: 50px auto 0px;
        justify-content: space-between;
        font-size: 20px;
        font-weight: 400;
        line-height: 50px;
        border-bottom: solid 1px #F2F2F2;
    }
    #list-box>li {
        width: 80px;
        text-align: center;
        cursor: pointer;
    }
    #list-box>li:first-of-type {
        border-bottom: #02A7F0 4px solid;
    }
    .contain {
        width: 100%;
        height: 400px;
        position: absolute;
    }
    .contain:first-of-type {
        z-index: 100;
    }
    .contain>div {
        width: 200px;
        height: 200px;
    }
    .contain>div>img{
        width: 200px;
        height: 200px;
    }
    .one>div {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
    .two {
        display: flex;
        justify-content: space-evenly;
    }
    .two>div {
        position: relative;
        top: 100px;
    }
    .tip {
        position: absolute;
        bottom: -40px;
        left: 62px;
        color: #02A7F0;
        text-decoration: underline;
        font-size: 20px;
        cursor: pointer;
    }
    .title {
        font-size: 20px;
        position: absolute;
        top: -35px;
        left: 75px;
    }
    .support{
        display: inline-block;
        width: 100%;
        font-size: 20px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }
    .close-img {
        width: 12px;
        height: 12px;
    }
    .searchBox {
        position: relative;
        display: flex;
        height: 48px;
        padding: 8px;
        z-index: 1;
    }
    .searchBox .inputBox {
        width: 100%;
        padding-left: 15px;
        padding-right: 30px;
        color: #595959;
        background:rgba(245,245,245,1);
        border-radius: 4px;
        border: none;
        font-size:14px;
        font-family:PingFangSC-Regular,PingFang SC;
        font-weight:400;
    }
    .searchBox .searchImgBox {
        position: absolute;
        right: 26px;
        line-height: 32px;
    }

    .searchBox .searchListBox {
        position: absolute;
        width: 184px;
        max-height: 168px;
        top: 40px;
        /* padding: 4px 0; */
        box-shadow:0px 9px 28px 8px rgba(0,0,0,0.05),0px 6px 16px 0px rgba(0,0,0,0.08),0px 3px 6px -4px rgba(0,0,0,0.12);
        border-radius:2px;
        overflow-y:scroll;
    }
	
	
    .searchBox .searchListBox::-webkit-scrollbar{
        display:none
    }

    .searchBox .searchListBox>a {
		display: block;
        height: 32px;
        line-height: 32px;
        padding-left: 12px;
		box-sizing: content-box;
        font-size: 14px;
        color:rgba(0,0,0,0.65);
        background: rgba(255,255,255,1);
        cursor: pointer;
    }

    .searchBox .searchListBox>a:first-child {
        /* margin-top: 4px; */
		border-top-width: 4px;
		border-top-style: solid;
		border-top-color: #FFFFFF;
    }

    .searchBox .searchListBox>a:last-child {
        /* margin-bottom: 4px; */
		border-bottom-width: 4px;
		border-bottom-style: solid;
		border-bottom-color: #FFFFFF;
    }

    .searchBox .searchListBox>a:hover {
        background:rgba(245,245,245,1);
    }

    .tooltiptext {
        visibility: hidden;
        width:80px;
        height:32px;
        background:rgba(0,0,0,0.75);
        box-shadow:0px 9px 28px 8px rgba(0,0,0,0.05),0px 6px 16px 0px rgba(0,0,0,0.08),0px 3px 6px -4px rgba(0,0,0,0.12);
        border-radius:2px;
        font-size:14px;
        font-family:PingFangSC-Regular,PingFang SC;
        font-weight:400;
        color:rgba(255,255,255,1);
        line-height:32px;
        text-align: center;
    }

    .tooltipbottom{
        /* 定位 */
        position: absolute;
        z-index: 1;
        top: 112%;
        left: -50%;
    }
	
	.tooltipright{
	    /* 定位 */
	    position: absolute;
		z-index: 1;
		left: 62px;
		width: 94px;
        z-index: 999;
	}

    .tooltipbottom .Sharp_corners {
        position: absolute;
        height: 0;
        font-size: 0;
        line-height: 0;
        border-style: solid;
        border-width: 0px 5px 6px 5px;
        border-color: rgba(0,0,0,0.75) transparent;
        z-index: 100;
        transform: translate(-20px,-6px);
    }
	
	.tooltipright .Sharp_corners {
	    position: absolute;
		height: 0;
		font-size: 0;
		line-height: 0;
		border-style: solid;
		border-width: 5px 6px 5px 0px;
		border-color: transparent rgba(0,0,0,0.75);
		z-index: 100;
		transform: translate(-81px,10px);
	}

    .zyk:hover .tooltiptext {
        visibility: visible;
    }
	
	.plus-box:hover .tooltiptext {
        visibility: visible;
    }
    .content-tabs {
        margin: 0;
    }
    </style>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="loading_content" style="width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);position: absolute;top: 0;left: 0;z-index: 10000;display: none;">
        <div style="position: absolute; top: 50%;left: 50%;transform: translate(-50%,-50%);width: 100px;height: 100px;text-align: center;border-radius: 6px;background-color: #fff">
            <img style="width: 50px;height: 50px;margin-top: 15px;" alt="image" src="{__ADMIN_PATH}images/load.gif"/>
            <div style="margin-top: 5px;">更新中</div>
        </div>
    </div>
    <div id="wrapper" >
        <!-- 点击查询框 -->
        <!-- <a href="https://dct.zoosnet.net/lr/chatpre.aspx?id=dct70858541&lng=cn&e=hezuo&r=&rf1=http%3a//192.168.31.131/p/product&rf2=.php&p=http%3a//192.168.31.131/p/index.php&cid=1518162243949518404080&sid=1520306073574298250017" target="_blank">
            <div class="animated-circles" style="box-shadow: 4px 5px 5px  #DADADA;">
                <div class="msg"><span>点击咨询</span><div class="small-arrow"></div></div>
                <div class="zixun circle" style="background-color: #fefefe"><img src="/public/system/images/zixun1.png" alt=""></div>
                <div class="circle c-1" style="background-color: rgba(255,182,77,.25);"></div>
                <div class="circle c-2" style="background-color: rgba(255,182,77,.25);"></div>
                <div class="circle c-3" style="background-color: rgba(255,182,77,.25);"></div>
            </div>
        </a> -->
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>

            <div style="display: flex;height: 100%">
                <div class="plus-content">
                    <div class="roll-user-nav" style="position: static;margin-top: 10px;">
                        <!-- 用户 -->
                        <a data-toggle="dropdown" class="dropdown-toggle user-msgs" style="display: block" href="#">
                            <img src="/public/system/images/001.png" alt="">
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem admin_close" href="{:Url('setting.systemAdmin/adminInfo')}">个人资料</a>
                            </li>
                            <!-- <li><a class="admin_close" target="_blank" href="http://www.thisky.com/">联系我们</a> -->
                            </li>
                            <li class="divider"></li>
                            <li><a href="{:Url('Login/logout')}">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <a class="plus-box J_menuItem active-plus-box" id="you_web" href="/admin/index/main.html" data-index="100" style="margin-top: 20px">
                        <img class="plus-img" src="{__FRAME_PATH}/img/desktop.png" alt="">
                        <!-- <div class="plus-text">网站管理</div> -->
						<span class="tooltiptext tooltipright">网站管理<i class="Sharp_corners"></i></span>
                    </a>

                    
                </div>
                <div class="sidebar-collapse" style="width: 100%">
                    <li class="nav-header" style="list-style:none">
                        <div class="dropdown profile-element admin_open">
                    <span>
                        <a href="/" target="_blank">
                            <img alt="image" class="imgbox" src="{$site_logo}" onerror="javascript:this.src='{__ADMIN_PATH}images/admin_logo.png';" />
                        </a>
                    </span>
                        </div>
                        <div class="logo-element">独角鲸
                        </div>
                    </li>
					<!-- 搜索框 -->
                    <li class="searchBox">
                        <input class="inputBox" placeholder="搜索" oninput="searchtext()">
                        <div class="searchImgBox">
                            <img src="{__ADMIN_PATH}/images/search.png" alt="">
                        </div>
                        <div class="searchListBox" id="list">	<!-- 所搜结果 -->
							<!-- <a class="J_menuItem" href=""></a> -->
                        </div>
                    </li>
                    <ul class="nav nav-first" style="padding-left: 0;position: relative;" id="side-menu">
                        <!--  菜单  -->
                        <ul class="nav nav-first active-ul new-ul" id="you_ul">
                            {volist name="menuList" id="menu"}
                          <?php if (isset($menu['child']) && count($menu['child']) > 0 && $menu['is_show']==1) { ?>
                              <li>
                                  <a href="#"><i class="iconfont icon {$menu.icon}"></i> <span class="nav-label">{$menu.menu_name}</span><span class="fa arrow"></span></a>
                                  <ul class="nav nav-second-level">
                                      {volist name="menu.child" id="child"}
                                      <li>
                                        <?php if (isset($child['child']) && count($child['child']) > 0  && $child['is_show']==1) { ?>
                                            <a href="#"><i class="fa fa-{$child.icon}"></i>{$child.menu_name}<span class="fa arrow"></span></a>
                                            <ul class="nav nav-third-level">
                                                {volist name="child.child" id="song"}
                                              <?php if ( $song['is_show']==1) { ?>
                                                  <li><a class="J_menuItem" href="{$song.url}"><i class="fa fa-{$song.icon}"></i> {$song.menu_name}</a></li>
                                              <?php } ?>
                                                {/volist}
                                            </ul>
                                        <?php } elseif($child['is_show']==1) { ?>
                                            <a class="J_menuItem" href="{$child.url}"><i class="fa fa-{$child.icon}"></i>{$child.menu_name}</a>
                                        <?php } ?>
                                      </li>
                                      {/volist}
                                  </ul>
                              </li>
                          <?php } ?>
                            {/volist}
                        </ul>
                        {volist name="website_menu" id="firstUl"}
                            <ul class="nav nav-first new-ul" id="{$firstUl.id2}">
                            {volist name="firstUl.child" id="v"}
                            <li>
                                <a href="{$v.url}" class="J_menuItem"><img style="width: 16px;height: 16px;margin-right: 6px;" src="{$v.icon}" alt=""><span class="nav-label">{$v.pid}</span>{if condition="count($v['menu']) neq 0"}<span class="fa arrow"></span>{/if}</a>
                                {if condition="count($v['menu']) neq 0"}
                                <ul class="nav nav-third-level">
                                    {volist name="v.menu" id="son"}
                                    <li><a class="J_menuItem" href="{$son.url}"><i class="fa"></i>{$son.name}</a></li>
                                    {/volist}
                                </ul>
                                {/if}
                            </li>
                            {/volist}
                        </ul>
                        {/volist}

                    </ul>
                </div>
            </div>

        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class=" dashbard-1 ">
            <div class="pop" id="pop-box" style="z-index: 1000000;">
                <div class="pop-title">
                    <div><span>访问前台</span></div>
                    <div id="close"><img src="{__ADMIN_PATH}/images/X.png" alt="" class="close-img"></div>
                </div>
                <div class="pop-container">
                    <div class="no-setting">
                        <p>暂未设置前台访问地址</p>
                        <p>
                            <a href="/admin/setting.system_config/index/type/1/tab_id/92.html"  class="J_menuItem show" data-index="88">平台信息</a>
                            <span class="plat-msg">立即设置</span>
                        </p>                   
                    </div>
                    <div class="get-data" style="display: none;position: relative">
                        <ul id="list-box">
                            <li>
                               H5
                            </li>
                            <li style="width: 100px;">
                                微信小程序
                            </li>
                            <li>
                                app
                            </li>
                        </ul>
                        <div class="contain one">
                            <div class="hd"><div class="tip" onclick="jsCopy('h5')">复制链接</div></div>
                        </div>
                        <div class="contain one" style="opacity: 0;">
                            <div class="xcx"></div>
                        </div>
                        <div class="contain two" style="opacity: 0;">
                            <div class="and"><div class="title" style="left: 55px">Android</div><div class="tip" style="left: 60px" onclick="jsCopy('and')">复制链接</div></div>
                            <div class="ios"><div class="title">ios</div><div class="tip" style="left: 60px" onclick="jsCopy('ios')">复制链接</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row all-width content-tabs" @touchmove.prevent>
                <button class="roll-nav roll-left navbar-minimalize" style="margin: 0; border-radius: 7px"><i class="zyk zyk-outdent"></i></button>
                
                <nav class="page-tabs nav-width J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="{:Url('Index/main')}" style="padding: 3px 26px;">首页</a>
                    </div>
                </nav>

                <button class="roll-nav roll-right J_tabLeft"  style="right: 448px;"><i class="fa fa-backward"></i></button>
                <button class="roll-nav roll-right J_tabRight" style="right: 400px;"><i class="fa fa-forward"></i></button>

                <a href="javascript:void(0);" class="roll-nav roll-right J_tabReply" style="right: 304px"><i class="zyk zyk-rollback"><span class="tooltiptext tooltipbottom">返回<i class="Sharp_corners"></i></span></i> </a>
                <a href="javascript:void(0);" class="roll-nav roll-right J_tabRefresh" style="right: 256px"><i class="zyk zyk-sync"><span class="tooltiptext tooltipbottom">刷新<i class="Sharp_corners"></i></span></i> </a>
                <a href="/admin/system.clear/index.html" class="roll-nav roll-right J_tabClearCache J_menuItem" data-index="168" style="right: 208px"><i class="icon zyk zyk-clear" style="margin-right: 20px;margin-left: 13px;"><span class="tooltiptext tooltipbottom">清除缓存<i class="Sharp_corners" style="transform: translate(-33px,-6px);"></i></span></i></a>
                <a href="javascript:void(0);" class="roll-nav roll-right J_visit"><i class="icon zyk zyk-mobile" style="margin-right: 20px;margin-left: 13px;"><span class="tooltiptext tooltipbottom">访问前台<i class="Sharp_corners" style="transform: translate(-33px,-6px);"></i></span></i> </a>
                <a href="javascript:void(0);" class="roll-nav roll-right J_tabFullScreen"><i class="zyk zyk-fullscreen"><span class="tooltiptext tooltipbottom">全屏<i class="Sharp_corners"></i></span></i> </a>
                <a href="javascript:void(0);" class="roll-nav roll-right J_notice" data-toggle="dropdown" aria-expanded="true"><i class="zyk zyk-bell"><span class="tooltiptext tooltipbottom">消息<i class="Sharp_corners"></i></span></i> <span class="badge badge-danger" id="msgcount">0</span></a>
                <ul class="dropdown-menu dropdown-alerts dropdown-menu-right dropdown-menu-news">
                    <li>
                        <a class="J_menuItem" href="{:Url('order.store_order/index',array('status'=>1))}">
                            <div>
                                <i class="fa fa-building-o"></i> 待发货
                                <span class="pull-right text-muted small" id="ordernum">0个</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="J_menuItem" href="{:Url('store.store_product/index',array('type'=>5))}">
                            <div>
                                <i class="fa fa-pagelines"></i> 库存预警 <span class="pull-right text-muted small" id="inventory">0个</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="J_menuItem" href="{:Url('store.store_product/index',array('type'=>1))}">
                            <div>
                                <i class="fa fa-pagelines"></i> 待补货 <span class="pull-right text-muted small" id="replenishment">0个</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="J_menuItem" href="{:Url('store.store_product_reply/index')}">
                            <div>
                                <i class="fa fa-comments-o"></i> 新评论 <span class="pull-right text-muted small" id="commentnum">0个</span>
                            </div>
                        </a>
                    </li>
                    <!--<li class="divider"></li>
                    <li>
                        <a class="J_menuItem" href="{:Url('finance.user_extract/index')}">
                            <div>
                                <i class="fa fa-cny"></i> 申请提现 <span class="pull-right text-muted small" id="reflectnum">0个</span>
                            </div>
                        </a>
                    </li>-->
                </ul>
                <a href="javascript:void(0);" class="roll-nav roll-right J_tabSetting right-sidebar-toggle"><i class="zyk zyk-menu"><span class="tooltiptext tooltipbottom">更多<i class="Sharp_corners"></i></span></i></a>
                <div class="btn-group roll-nav roll-right" style="right: 352px;">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭<span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <!--<div class="roll-user-nav" style="width: 100px;">

                    <a data-toggle="dropdown" class="dropdown-toggle user-msgs" href="#">
                    <span class="clear">
                        <span class="block"><strong class="font-bold">{$_admin['real_name']}</strong></span>
                        <span class="text-muted text-xs block">{$role_name.role_name ? $role_name.role_name : '管理员'}<b class="caret"></b></span>
                    </span>

                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="J_menuItem admin_close" href="{:Url('setting.systemAdmin/adminInfo')}">个人资料</a>
                        </li>
                        <li><a class="admin_close" target="_blank" href="http://www.thisky.com/">联系我们</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{:Url('Login/logout')}">安全退出</a>
                        </li>
                    </ul>
                </div>-->
            </div>

            <!--内容展示模块-->
            <div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe_opensnsx_main" width="100%" height="100%" src="{:Url('Index/main')}" frameborder="0" data-id="{:Url('Index/main')}" seamless></iframe>
            </div>
            <!--底部版权-->
            <div class="footer" @touchmove.prevent>
                        本产品由独角鲸软件提供技术支持
<!--                <div id="message_box" class="message-box" style="display: none">-->
<!--                    <span id="close_msg" class="close-msg">隐藏>></span>-->
<!--                    <div id="message_content" ></div>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">
            <ul class="nav nav-tabs navs-3">
                <li class="active">
                    <a data-toggle="tab" href="#tab-1">
                        <i class="fa fa-bell"></i>通知
                    </a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#tab-2">
                        <i class="fa fa-gear"></i> 设置
                    </a>
                </li>

            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3><i class="fa fa-comments-o"></i> 最新通知</h3>
                        <small><i class="fa fa-tim"></i> 您当前有0条未读信息</small>
                    </div>
                    <div>
                        <!--<div class="sidebar-message">
                            <a href="#">
                                <div class="pull-left text-center">
                                    <img alt="image" class="img-circle message-avatar" src="http://ozwpnu2pa.bkt.clouddn.com/a1.jpg">
                                    <div class="m-t-xs">
                                        <i class="fa fa-star text-warning"></i> <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <div class="media-body">

                                    据天津日报报道：瑞海公司董事长于学伟，副董事长董社轩等10人在13日上午已被控制。 <br>
                                    <small class="text-muted">今天 4:21 <a class="J_menuItem admin_close" href="/admin/setting.system_admin/admininfo.html" data-index="0">【查看】</a></small>
                                </div>
                            </a>
                        </div>-->
                    </div>
                </div>
                <div id="tab-2" class="tab-pane ">
                    <div class="sidebar-title">
                        <h3><i class="fa fa-comments-o"></i> 提示</h3>
                        <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title">设置</div>
                        <div class="setings-item">
                            <span>收起左侧菜单</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                      <!--  <div class="setings-item">
                            <span>固定宽度</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>-->
                        <div class="setings-item">
                            <span>菜单点击刷新</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="refresh" class="onoffswitch-checkbox" id="refresh">
                                    <label class="onoffswitch-label" for="refresh">
                                        <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                      <!--  <div class="title">皮肤选择</div>
                        <div class="setings-item blue-skin nb">
                            <span class="skin-name ">
                                <a href="#" class="s-skin-1">
                                    默认皮肤
                                </a>
                            </span>
                        </div>
                        <div class="setings-item default-skin nb">
                            <span class="skin-name ">
                                <a href="#" class="s-skin-0">
                                    黑色主题
                                </a>
                            </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                            <span class="skin-name ">
                                <a href="#" class="s-skin-3">
                                    黄色/紫色主题
                                </a>
                            </span>
                        </div>-->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--右侧边栏结束-->
    </div>
    <!--vue调用不能删除-->
    <div id="vm"></div>
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <script src="{__FRAME_PATH}js/bootstrap.min.js"></script>
    <script src="{__FRAME_PATH}js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="{__FRAME_PATH}js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{__FRAME_PATH}js/plugins/layer/layer.min.js"></script>
    <script src="{__FRAME_PATH}js/hplus.min.js"></script>
    <script src="{__FRAME_PATH}js/contabs.min.js"></script>
    <script src="{__FRAME_PATH}js/plugins/pace/pace.min.js"></script>
    {include file="public/style"}
    <script src="{__ADMIN_PATH}js/index.js"></script>
    <script>
        $(function() {
            // 隐藏消息框
            var flag = true;
            $("#close_msg").click(function () {
                if (flag) {
                    $("#message_box").css({"right":"-300px"});
                    $(this).css({"left":"-64px"});
                    $(this).html("显示<<");
                    flag = false;
                }else{
                    $("#message_box").css({"right":"12px"});
                    $(this).css({"left":"0"});
                    $(this).html("隐藏>>");
                    flag = true;
                }
            })
            // 消息内容开始
            
            // 消息内容结束
            function getnotice() {
                $.getJSON("{:Url('Jnotice')}", function(res) {
                    var info = eval("(" + res + ")");
                    var data = info.data;
                    $('#msgcount').html(data.msgcount);
                    $('#ordernum').html(data.ordernum + '个');
                    $('#inventory').html(data.inventory + '个');
                    $('#replenishment').html(data.replenishment + '个');
                    $('#commentnum').html(data.commentnum + '个');
                    $('#reflectnum').html(data.reflectnum + '个');
                });
            }
            getnotice();
            setInterval(getnotice, 600000);
        });

        // 点击查询 框的交互
        $('.animated-circles').mouseenter(function() {
            $('.msg').css('display','block')
        })
        $('.animated-circles').mouseleave(function() {
            $('.msg').css('display','none')
        })

        // 访问前台的交互
        $('.J_visit').click(function() {
            $('#pop-box').css('display', 'block')
            // console.log($eb)
            // $eb.createModalFrame(this.innerText,'{:Url('pop')}')
        })
        $('#close').click(function() {
            $('#pop-box').css('display', 'none')
        })
        $('.show').click(function() {
            $('#pop-box').css('display', 'none')
        })

        $('#list-box>li').click(function() {
            let index = $(this).index();
            let arr = $('#list-box>li');
            for (let i = 0;i < arr.length;i++) {
                if (index == i) {
                    $(this).css({"border-bottom":"#02A7F0 4px solid"})
                    $('.contain').eq(i).css({'opacity':'1','z-index':'100'})
                } else {
                    $(arr[i]).css("border-bottom","none")
                    $('.contain').eq(i).css({'opacity':'0','z-index':'0'})
                }
            }
        })
        let Ur12;
        function jsCopy(type){ 
            $.ajax({
            url: '/admin/com.com_forum/get_platform_config',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                    if (type == 'h5') {
                        Url2 = res.data.platform_h5_url;
                    } else if (type == 'and') {
                        Url2 = res.data.platform_android_url;
                    } else if (type == 'ios') {
                        Url2 = res.data.platform_ios_url;
                    } 
                    let oInput = document.createElement('input');
                    oInput.value = Url2;
                    document.body.appendChild(oInput);
                    oInput.select(); // 选择对象
                    document.execCommand("Copy"); // 执行浏览器复制命令
                    oInput.className = 'oInput';
                    oInput.style.display='none';
                    // $eb.message('success', '复制成功')
                }
            })
        } 
        // 访问前台的数据
        // axios.get("{:Url('admin/com.comForum/get_platform_confg')}",function(res) {
        //     console.log('6.6', res)
        // })
        // .then()
        $.ajax({
            url: '/admin/com.com_forum/get_platform_config',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.data) {
                    $('.no-setting').css('display', 'none');
                    $('.get-data').css('display', 'block');
                    if (!res.data.platform_android_url) {
                        $('.and').empty()
                    } else if (!res.data.platform_ios_url_image) {
                        $('.ios').empty()
                    } else if (!res.data.platform_h5_url) {
                        $('.hd').empty()
                    }

                    if (res.data.platform_h5_url_image) {
                        var tag = '<img src="'+ res.data.platform_h5_url_image +'" alt="">';
                    } else {
                        var tag =  '<span class="support">'+ '暂不支持该平台' +'</span>'
                    }

                    if (res.data.platform_xcx_url) {
                        var tag1 = '<img src="'+ res.data.platform_xcx_url +'" alt="">';
                    } else {
                        var tag1 =  '<span class="support">'+ '暂不支持该平台' +'</span>'
                    }
                    if (res.data.platform_android_url_image) {
                        var tag2 = '<img src="'+ res.data.platform_android_url_image +'" alt="">';
                    } else {
                        var tag2 =  '<span class="support">'+ '暂不支持Android' +'</span>'
                    }
                    if (res.data.platform_ios_url_image) {
                        var tag3 = '<img src="'+ res.data.platform_ios_url_image +'" alt="">';
                    } else {
                        var tag3 =  '<span class="support">'+ '暂不支持ios' +'</span>'
                    }

                    $('.hd').append(tag)
                    $('.xcx').append(tag1)
                    $('.and').append(tag2)
                    $('.ios').append(tag3)
                }
            }
        })

		// 搜索框
        function searchtext(){ 
            var inputVal = document.querySelector(".inputBox").value
            var inBox = document.querySelector('.inputBox');
            var sList = document.querySelector('.searchListBox');
            clearTimeout();
			$('.searchListBox').empty()
			var item = null;
			item = document.createElement('a');
			//item.innerHTML = '加载中';
			//sList.appendChild(item);
			$('.searchListBox>a').css({
			        "text-align":"center",
			        "padding-left":"0"
			    })
			// input输入框有值的时候请求
            if(inputVal != '') {
				setTimeout(function(){
					$.ajax({
						url: '/admin/system.search/search_nav',
						data: {keyword:inputVal},
						type: 'GET',
						dataType: 'json',
						success: function(res) {
							// console.log(res,'res');
							sList.innerHTML = '';
							var len = res.count.length;
							// 有无搜索结果
							if(len == 0) {
								item = document.createElement('a');
								item.innerHTML = '暂无数据';
								sList.appendChild(item);
								$('.searchListBox>a').css({
									"text-align":"center",
									"padding-left":"0"
								})
							}else {
								// console.log(res.count,'res.count');
								let count = res.count;
								let countFilter = count.filter(function (x) {
								    return x.have_menu != 1;
								});
								console.log(countFilter,'countFilter');
								for(var i=0;i<countFilter.length;i++){
									item = document.createElement('a');
									item.innerHTML = countFilter[i].menu_name;
									// console.log(countFilter[i].params,typeof countFilter[i].params,'111');
									
									if(countFilter[i].params != "[]" && countFilter[i].params != "") {
										let params = JSON.parse(countFilter[i].params);
										// 用来保存所有的属性名称和值
										let props = "";
										// 开始遍历
										for(var p in params){ 
											// 方法
											if(typeof(params[p])=="function"){ 
												params[p]();
											}else{ 
												// p 为属性名称，params[p]为对应属性的值
												props+= "/" + p + "/" + params[p];
											} 
										}
										props = props.substr(1);
										// console.log(props,typeof props,'props');
										item.href = countFilter[i].url;
									}else {
										item.href = countFilter[i].url;
									}
									// console.log(item.href);
									sList.appendChild(item);
									$('.searchListBox a').addClass('J_menuItem');
								}
							}
						},
					})
				},500);
            }else {
				// $('.searchListBox').empty()
				setTimeout(function(){
					item =null;
					$('.searchListBox').empty();
					console.log('无内容')
					clearTimeout();
					return
				},500);
            }
            
        } 
		
		$(".inputBox").focus(function(event){
			console.log('inputBox聚焦’')
			searchtext();
			$(window).on('scrollstop', function(){
				console.log('scrollstop’')
			})
			event.stopPropagation();
		});  
		
		$(".inputBox").click(function(event){
			console.log('inputBox点击’')
			searchtext();
			$('.searchListBox').empty();
			event.stopPropagation();
		});  
		
		/* $(".inputBox").blur(function(){
			console.log(321)
			// if(document.querySelector(".inputBox").value != ''){
			// 	$(".searchListBox").hide();
			// }else {
			// 	$('.searchListBox').empty()
			// }
		 }); */
		
		$(document).click(function(){
		     console.log('离开input')
		     if(document.querySelector(".inputBox").value != ''){
				 console.log('离开hide')
		     	$(".searchListBox").empty();
		     }else {
				 console.log('离开empty')
		     	$('.searchListBox').empty()
		     }
		});
		

        
    </script>
    <script>
        $(function () {
            $('.nav-width').width($('.all-width').width()-540);
        })
    </script>
    <script>
        $(".plus-box").on("click",function () {
            $(".active-plus-box").removeClass("active-plus-box");
            $(this).addClass("active-plus-box");
        });
        $("#you_web").on("click",function () {
            $(".active-ul").removeClass("active-ul");
            $("#you_ul").addClass("active-ul");
        });
        $("#shop_our").on("click",function () {
            $(".active-ul").removeClass("active-ul");
            $("#shop_ul").addClass("active-ul");
        });
        $("#our_web").on("click",function () {
            $(".active-ul").removeClass("active-ul");
            $("#our_web_ul").addClass("active-ul");
        });
        $("#our_community").on("click",function () {
            $(".active-ul").removeClass("active-ul");
            $("#our_community_ul").addClass("active-ul");
        });
        $("#look_auth").on("click",function () {
            $(".active-ul").removeClass("active-ul");
            $("#auth_ul").addClass("active-ul");
        });
    </script>
</body>

</html>