
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <meta name="browsermode" content="application"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 禁止百度转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <title>物流信息</title>
    <link rel="stylesheet" type="text/css" href="{__PUBLIC_PATH}static/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="{__PUBLIC_PATH}wap/first/opensnsx/font/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="{__PUBLIC_PATH}wap/first/opensnsx/css/style.css?2"/>
    <script type="text/javascript" src="{__PUBLIC_PATH}static/js/media.js"></script>
    <script type="text/javascript" src="{__PUBLIC_PATH}static/plug/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="{__PUBLIC_PATH}wap/first/opensnsx/js/common.js"></script>
</head>
<body>

<div class="user-order-logistics" style="overflow: hidden;">
    <section>
        <div style="color:#666;font-size: 18px;margin: 20px;padding-left: 15px;border-bottom: 1px solid #eee;padding-bottom: 10px;">
            {$order.delivery_name}:{$order.delivery_id}
        </div>
        <div style="font-size: 16px">
          <?php if(!$express){ ?>
              <div>
                  <img src="{__PUBLIC_PATH}wap/first/opensnsx/images/empty_address.png">
                  <p>暂无查询记录</p>
              </div>
          <?php }else{ ?>
              <ul>
                  {volist name="express.Traces" id="vo"}
                  <li>
                      <div style="display: flex;align-items: center;margin: 20px 35px 20px 35px">
                          <span>{$vo.AcceptTime}</span>
                          <p style="width: 500px;margin-left: 20px;">{$vo.AcceptStation}</p>
                      </div>
                  </li>
                  {/volist}
              </ul>
          <?php } ?>
        </div>
        <div style="margin-top: 30px;color: #999;font-size: 12px;text-align: right;margin-right: 20px;">
            以上为平台最新获取的快递信息，如有问题请查看快递公司信息
        </div>
    </section>
</div>
</body>
</html>
