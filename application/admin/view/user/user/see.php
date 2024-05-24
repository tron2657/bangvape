{extend name="public/container"}
{block name="content"}
<style>
    .backlog-body{
        padding: 10px 15px;
        background-color: #f8f8f8;
        color: #999;
        border-radius: 2px;
        transition: all .3s;
        -webkit-transition: all .3s;
        overflow: hidden;
        max-height: 84px;
    }
    .backlog-body h3{
        margin-bottom: 10px;
    }
    .right-icon{
        position: absolute;
        right: 10px;
    }
    .backlog-body p cite {
        font-style: normal;
        font-size: 17px;
        font-weight: 300;
        color: #009688;
    }
    .layuiadmin-badge, .layuiadmin-btn-group, .layuiadmin-span-color {
        position: absolute;
        right: 15px;
    }
    .layuiadmin-badge {
        top: 50%;
        margin-top: -9px;
        color: #01AAED;
    }
    .info-content{
        display: flex;
        flex-wrap: wrap;
        padding: 0 15px;
    }
    .info-content .info-line{
        display: flex;
        width: 50%;
    }
    .info-content .info-line .title{
        color: #333;
        width: 30%;
    }
    .info-content .info-line .text{
        color: #999;
        width: 70%;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">会员详情</div>
                <div class="layui-card-body">
                    <div class="info-content">
                        <div class="info-line">
                            <div class="title">用户昵称</div>
                            <div class="text">{$userinfo.nickname}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">手机号码</div>
                            <div class="text">{$userinfo.phone}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">邮箱</div>
                            <div class="text">{$userinfo.email}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">用户姓名</div>
                            <div class="text"></div>
                        </div>
                        <div class="info-line">
                            <div class="title">性别</div>
                            <div class="text">{$userinfo.sex}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">生日</div>
                            <div class="text">{$userinfo.birthday}</div>
                        </div>
                        <div class="info-line" style="width: 100%">
                            <div class="title" style="width: 15%">一句话简介</div>
                            <div class="text">{$userinfo.signature}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">积分详情</div>
                <div class="layui-card-body">
                    <div class="info-content">
                        <div class="info-line">
                            <div class="title">{$userinfo.exp_name}</div>
                            <div class="text">{$userinfo.exp}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">{$userinfo.fly_name}</div>
                            <div class="text">{$userinfo.fly}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">{$userinfo.buy_name}</div>
                            <div class="text">{$userinfo.buy}</div>
                        </div>
                        <div class="info-line">
                            <div class="title">{$userinfo.gong_name}</div>
                            <div class="text">{$userinfo.gong}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">等级信息</div>
                <div class="layui-card-body">
                    <div class="info-content">
                        <!--<div class="info-line">
                            <div class="title">会员等级</div>
                            <div class="text"></div>
                        </div>-->
                        <div class="info-line">
                            <div class="title">用户等级</div>
                            <div class="text">{$userinfo.user_grade}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">认证信息</div>
                <div class="layui-card-body">
                    <div class="info-content">
                        {volist name="userinfo.certification" id="vo"}
                        <div class="info-line">
                            <div class="title">{$vo}</div>
                            <div class="text"></div>
                        </div>
                        {/volist}
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">社区信息</div>
                <div class="layui-card-body">
                    <div class="total-num-content" style="display: flex;">
                        <div class="total-box" style="display: flex;justify-content: center;width: 25%">
                            <div style="text-align: center">
                                <div class="title" style="color: #0ca6f2">帖子</div>
                                <div class="num" style="color: #333;font-weight: 600;font-size: 20px;margin-top: 10px">{$userinfo.post_count+$userinfo.news_count+$userinfo.video_count}</div>
                            </div>
                            <div style="margin-top: 25px;margin-left: 10px;">
                                <div style="display: flex;line-height: 16px">
                                    <div style="width: 50px">帖子:</div>
                                    <div>{$userinfo.post_count}</div>
                                </div>
                                <div style="display: flex;line-height: 16px">
                                    <div style="width: 50px">资讯:</div>
                                    <div>{$userinfo.news_count}</div>
                                </div>
                                <div style="display: flex;line-height: 16px">
                                    <div style="width: 50px">视频:</div>
                                    <div>{$userinfo.video_count}</div>
                                </div>
                            </div>
                        </div>
                        <div class="total-box" style="text-align: center;width: 20%">
                            <div class="title" style="color: #0ca6f2">关注</div>
                            <div class="num" style="color: #333;font-weight: 600;font-size: 20px;margin-top: 10px">{$userinfo.follow}</div>
                        </div>
                        <div class="total-box" style="text-align: center;width: 20%">
                            <div class="title" style="color: #0ca6f2">粉丝</div>
                            <div class="num" style="color: #333;font-weight: 600;font-size: 20px;margin-top: 10px">{$userinfo.fans}</div>
                        </div>
                        <div class="total-box" style="text-align: center;width: 20%">
                            <div class="title" style="color: #0ca6f2">收藏</div>
                            <div class="num" style="color: #333;font-weight: 600;font-size: 20px;margin-top: 10px">{$userinfo.is_collect}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">商城信息</div>
                <div class="layui-card-body">
                    <div class="layui-row layui-col-space15">
                    {volist name='headerList' id='vo'}
                    <div class="layui-col-xs3" style="margin-bottom: 10px ">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                {$vo.title}
                                <span class="layui-badge layuiadmin-badge {if isset($vo.class) && $vo.class}{$vo.class}{else}layui-bg-blue{/if}">{$vo.key}</span>
                            </div>
                            <div class="layui-card-body">
                                <p class="layuiadmin-big-font">{$vo.value}</p>
                            </div>
                        </div>
                    </div>
                    {/volist}
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12 layui-col-sm12 layui-col-lg12">
            <div class="layui-card">
                <div class="layui-card-header">其它信息</div>
                <div class="layui-card-body">
                    <div class="info-content">
                        <div class="info-line" style="width: 100%;">
                            <div class="title" style="width: 15%">用户状态</div>
                            <div class="text">{$userinfo._status}</div>
                        </div>
                        <div class="info-line" style="width: 100%;">
                            <div class="title" style="width: 15%">分销权限</div>
                            {if condition="$userinfo.is_seller eq '0'"}
                            <div class="text">未开启</div>
                            {elseif condition="$userinfo.is_seller eq '1'"/}
                            <div class="text">已开启</div>
                            {/if}
                        </div>
                        <div class="info-line" style="width: 100%;">
                            <div class="title" style="width: 15%">首次登陆时间</div>
                            <div class="text">{$userinfo.add_time}</div>
                        </div>
                        <div class="info-line" style="width: 100%;">
                            <div class="title" style="width: 15%">最后登陆时间</div>
                            <div class="text">{$userinfo.last_time}</div>
                        </div>
                        <div class="info-line" style="width: 100%;">
                            <div class="title" style="width: 15%">最后登陆IP</div>
                            <div class="text">{$userinfo.last_ip}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script>
    var count=<?=json_encode($count)?>,
        $uid=<?=$uid?>;
    require(['vue'],function(Vue) {
        new Vue({
            el: "#content",
            data: {
                limit:10,
                uid:$uid,
                orderList:[],
                integralList:[],
                SignList:[],
                CouponsList:[],
                balanceChangList:[],
                SpreadList:[],
                count:count,
                page:{
                    order_page:1,
                    integral_page:1,
                    sign_page:1,
                    copons_page:1,
                    balancechang_page:1,
                    spread_page:1,
                },
            },
            watch:{
                'page.order_page':function () {
                    this.getOneorderList();
                },
                'page.integral_page':function () {
                    this.getOneIntegralList();
                },
                'page.sign_page':function () {
                    this.getOneSignList();
                },
                'page.copons_page':function () {
                    this.getOneCouponsList();
                },
                'page.balancechang_page':function () {
                    this.getOneBalanceChangList();
                },
                'page.spread_page':function () {
                    this.getSpreadList();
                }
            },
            methods:{
                getSpreadList:function(){
                    this.request('getSpreadList',this.page.spread_page,'SpreadList');
                },
                getOneorderList:function () {
                    this.request('getOneorderList',this.page.order_page,'orderList');
                },
                getOneIntegralList:function () {
                    this.request('getOneIntegralList',this.page.integral_page,'integralList');
                },
                getOneSignList:function () {
                    this.request('getOneSignList',this.page.sign_page,'SignList');
                },
                getOneCouponsList:function () {
                    this.request('getOneCouponsList',this.page.copons_page,'CouponsList');
                },
                getOneBalanceChangList:function () {
                    this.request('getOneBalanceChangList',this.page.balancechang_page,'balanceChangList');
                },
                request:function (action,page,name) {
                    var that=this;
                    layList.baseGet(layList.U({a:action,p:{page:page,limit:this.limit,uid:this.uid}}),function (res) {
                        that.$set(that,name,res.data)
                    });
                }
            },
            mounted:function () {
                this.getOneorderList();
                this.getOneIntegralList();
                this.getOneSignList();
                this.getOneCouponsList();
                this.getOneBalanceChangList();
                this.getSpreadList();
                var that=this;
                layList.laypage.render({
                    elem: that.$refs.page_order
                    ,count:that.count.order_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.order_page=obj.curr;
                    }
                });
                layList.laypage.render({
                    elem: that.$refs.integral_page
                    ,count:that.count.integral_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.integral_page=obj.curr;
                    }
                });
                layList.laypage.render({
                    elem: that.$refs.Sign_page
                    ,count:that.count.sign_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.sign_page=obj.curr;
                    }
                });
                layList.laypage.render({
                    elem: that.$refs.copons_page
                    ,count:that.count.coupon_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.copons_page=obj.curr;
                    }
                });
                layList.laypage.render({
                    elem: that.$refs.balancechang_page
                    ,count:that.count.balanceChang_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.balancechang_page=obj.curr;
                    }
                });

                layList.laypage.render({
                    elem: that.$refs.spread_page
                    ,count:that.count.spread_count
                    ,limit:that.limit
                    ,theme: '#1E9FFF',
                    jump:function(obj){
                        that.page.spread_page=obj.curr;
                    }
                });
            }
        });
    });
</script>
{/block}