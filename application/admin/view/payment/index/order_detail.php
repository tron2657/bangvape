<div>
    {php}dump($order){/php};
</div>
{extend name="public/container"}

{block name="content"}
<style>
html{
    background: #fff;
}
.gray-bg{
    background: #fff;
}
.wrapper-content{
    padding-left: 0;
    padding-bottom: 10px;
    margin: 0;
}
.information{
    width: 560px;
    border: 1px solid #e6e6e6;
    margin: 20px auto 0;
}
.f-information{
    background: #f2f2f2;
}
hr{
    margin: 0;
}
.title{
    height: 48px;
    color: #555555;
    font-size: 12px;
    line-height: 48px;
    padding-left: 12px;
    padding-right: 20px;
}
span{
    display: inline-block;
    float: right;
    text-align: right;
}
.fashionable{
    width: 560px;
    border: 1px solid #e6e6e6;
    margin: 25px auto;
}
.line{
    width: 100%;
    height: 1px;
    background: #e6e6e6;
}
.f-btn{
    display: none;
    width: 560px;
    margin: 10px auto;
    overflow: hidden;
}
.f-btns{
    float: right;
    width: 80px;
    height: 30px;
    font-size: 12px;
    line-height: 30px;
    margin-left: 10px;
    background: rgba(51, 153, 255, 1);
}
.f-cancel{
    background: #fff;
    color: #0092DC;
    border: 1px solid #0092DC;
}
.f-cancel:hover{
    color: #0092DC;
}
</style>
    <div class="information">
        <div class="f-information title">交易信息</div>
        <hr>
        <div class="title">交易单号：<span>{$order['order_id']}</span></div>
        <hr>
        <div class="title">业务单号：<span>{$order['unique']}</span></div>
        <hr>
        <div class="title">用户名: <span>{$order['user']['nickname']}</span></div>
        <hr>
        <div class="title">支付渠道: <span>
                {if condition="$order['pay_type'] eq 'weixin'"}
                    微信
                {elseif condition="$order['pay_type'] eq 'routine'"}
                    微信小程序
                {else/}
                    余额
                {/if}
            </span></div>
        <hr>
        <div class="title">第三方渠道单号: <span>{$order['call_back_order_id']}</span></div>
        <hr>
        <div class="title">支付金额: <span>{$order['amount']}</span></div>
        <hr>
        <div class="title">交易内容: <span>{$order['info']}</span></div>
        <hr>
        <div>
            {if condition="$order['status'] eq 2"}
                <div class="title">交易状态: <span>创建交易中</span></div>
            {/if}
            {if condition="$order['status'] eq 1"}
                <div class="title">交易状态: <span>交易成功</span></div>
            {/if}
            {if condition="$order['status'] eq 0"}
                <div class="title">交易状态: <span>交易关闭</span></div>
            {/if}  
            {if condition="$order['status'] eq -1"}
                <div class="title">交易状态: <span>交易失败</span></div>
            {/if}
        </div>
        <hr>
        <div class="title">订单创建时间: <span>{$order['create_time']}<span></div>
        <hr>
        <div class="title">订单完成时间: <span>{$order['pay_time']}</span></div>
    </div>
    <div class="fashionable">
        <div  class="title f-information">分账信息</div>
        <hr>
        <div  class="title">平台<span>
              {$order['bind_table_data']}
            </span></div>
    </div>
    <div class="line"></div>
    <div class="f-btn">
        <button type="button" class="layui-btn layui-btn-normal f-btns">确定</button>
        <button type="button" class="layui-btn layui-btn-normal f-btns f-cancel">取消</button>
    </div>
{/block}
