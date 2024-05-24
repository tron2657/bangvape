{extend name="public/container"}

{block name="content"}
<style>
.gray-bg{
    background: #fff;
}
.title{
    font-size: 14px;
    color: #333;
    letter-spacing: normal;
    margin: 20px 0 20px 20px;
}
.wrapper-content{
    margin: 0;
    padding: 0;
}
.tab{
    width: 560px;
    margin: 0 auto;
    border-color: #e6e6e6;
}
.f-top{
    height: 48px;
    text-align: center;
    line-height: 48px;
}
.f-times{
    width: 165px;
}
.f-content{
    width: 241px;
}
.f-people{
    width: 154px;
}
.f-tab{
    height: 39px;
    text-align: center;
    line-height: 39px;
    font-size: 12px;
    color: #555555;
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
.f-line{
    width: 100%;
    height: 1px;
    background: #e6e6e6;
    margin-top: 20px;
    margin-bottom: 20px;
}
.fb-bg{
    background-color: #F5F5F5;
}
</style>
    <div>
        <div class="title">交易单号：{$order_id}</div>
        <div class="tab">
            <table border="1" bordercolor='#e6e6e6'>
                <tr class='fb-bg'>
                    <th class="f-times f-top">变更时间</th>
                    <th class="f-content f-top">变更内容</th>
                    <th class="f-people f-top">操作人</th>
                </tr>
                {volist name='list' id='vo'}
                    <tr class="f-form">
                        <td class="f-times f-tab">{$vo['create_time']}</td>
                        <td class="f-content f-tab">{$vo['info']}</td>
                        <td class="f-people f-tab">{$vo['name']}</td>
                    </tr>
                {/volist}
            </table>
        </div>
    </div>
    <div class="f-line"></div>
    <div class="f-btn">
        <button type="button" class="layui-btn layui-btn-normal f-btns">确定</button>
        <button type="button" class="layui-btn layui-btn-normal f-btns f-cancel">取消</button>
    </div>
{/block}

