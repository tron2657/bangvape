<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
    <link rel="stylesheet" href="./css/install.css?v=9.0" />
</head>
<body>
<div class="wrap">
    <?php require './templates/header.php';?>
    <section class="section">
        <div class="step">
            <ul>
                <li class="on"><em>1</em>检测环境</li>
                <li class="current"><em>2</em>创建数据</li>
                <li><em>3</em>完成安装</li>
            </ul>
        </div>
        <form id="J_install_form" action="index.php?step=4" method="post">
            <input type="hidden" name="force" value="0" />
            <div class="server">
                <table width="100%">
                    <tr>
                        <td class="td1" width="100">数据库信息</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tar">数据库服务器：</td>
                        <td><input type="text" name="dbhost" id="dbhost" value="127.0.0.1" class="input"></td>
                        <td><div id="J_install_tip_dbhost"><span class="gray">数据库服务器地址，一般为localhost</span></div></td>
                    </tr>
                    <tr>
                        <td class="tar">数据库端口：</td>
                        <td><input type="text" name="dbport" id="dbport" value="3306" class="input"></td>
                        <td><div id="J_install_tip_dbport"><span class="gray">数据库服务器端口，一般为3306</span></div></td>
                    </tr>
                    <tr>
                        <td class="tar">数据库用户名：</td>
                        <td><input type="text" name="dbuser" id="dbuser" value="root" class="input"></td>
                        <td><div id="J_install_tip_dbuser"></div></td>
                    </tr>
                    <tr>
                        <td class="tar">数据库密码：</td>
                        <td><input type="password" name="dbpw" id="dbpw" value="" class="input" autoComplete="off" onBlur="TestDbPwd(0)"></td>
                        <td><div id="J_install_tip_dbpw"></div></td>
                    </tr>
                    <tr>
                        <td class="tar">数据库名：</td>
                        <td><input type="text" name="dbname" id="dbname" value="" class="input" onBlur="TestDbPwd(0)"></td>
                        <td><div id="J_install_tip_dbname"><span class="gray">填写实际数据库名，如果账号有创建数据库权限，则会自动创建该名称数据库</span></div></td>
                    </tr>
                    <tr>
                        <td class="tar">数据库表前缀：</td>
                        <td><input type="text" name="dbprefix" id="dbprefix" value="osx_" class="input" Disabled></td>
                        <td><div id="J_install_tip_dbprefix"><span class="gray">建议使用默认</span></div></td>
                    </tr>
                    <tr>
                        <td class="tar">演示数据：</td>
                        <td colspan="2"><input style="width:18px;height:18px;" type="checkbox" id="demo" name="demo" value="demo" checked Disabled></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td class="td1" width="100">管理员信息</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tar">管理员帐号：</td>
                        <td><input type="text" name="manager" id="manager" value="admin" class="input"></td>
                        <td><div id="J_install_tip_manager"></div></td>
                    </tr>
                    <tr>
                        <td class="tar">管理员密码：</td>
                        <td><input type="password" name="manager_pwd" id="manager_pwd" class="input" autoComplete="off"></td>
                        <td><div id="J_install_tip_manager_pwd"></div></td>
                    </tr>
                    <tr>
                        <td class="tar">重复密码：</td>
                        <td><input type="password" name="manager_ckpwd" id="manager_ckpwd" class="input" autoComplete="off"></td>
                        <td><div id="J_install_tip_manager_ckpwd"></div></td>
                    </tr>

                </table>
                <table width="100%">
                    <tr>
                        <td class="td1" width="100">网站信息（想天社区账号信息）</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tar"></td>
                        <td><div id="qrcode" style="width:100px; height:100px;" /></td>
                    </tr>
                    <tr>
                        <td class="tar">站点号：</td>
                        <td>
                            <div class="verification-phone">
                                <input type="text" id="site_code" class="input" readonly><span id="is_site_code" class="after-verification">验证手机号后获取</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">网站域名：</td>
                        <td><input type="text" class="input"/></td>
                        <td><div id="J_install_tip_dbname"><span class="gray">填写网站域名（不用填写http或https）</span></div></td>
                    </tr>
                </table>
                <div id="J_response_tips" style="display:none;"></div>
            </div>
            <div class="bottom tac"> <a href="./index.php?step=2" class="btn">上一步</a>
                <button type="button" onClick="checkForm();" class="btn btn_submit J_install_btn">创建网站</button>
            </div>
        </form>
    </section>
    <div  style="width:0;height:0;overflow:hidden;"> <img src="./images/install/pop_loading.gif"> </div>
    <script src="./js/jquery.js?v=9.0"></script>
    <script src="./js/qrcode.min.js"></script>
    <script src="./js/validate.js?v=9.0"></script>
    <script src="./js/ajaxForm.js?v=9.0"></script>
    <script>

        function TestDbPwd(connect_db)
        {
            var dbHost = $('#dbhost').val();
            var dbUser = $('#dbuser').val();
            var dbPwd = $('#dbpw').val();
            var dbName = $('#dbname').val();
            var dbport = $('#dbport').val();
            var demo  =  $('#demo').val();
            data={'dbHost':dbHost,'dbUser':dbUser,'dbPwd':dbPwd,'dbName':dbName,'dbport':dbport,'demo':demo};
            var url =  "<?php echo $_SERVER['PHP_SELF']; ?>?step=3&testdbpwd=1";
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType:'JSON',
                beforeSend:function(){
                },
                success: function(msg){
                    if(msg == 1){

                        if(connect_db == 1)
                        {
                            $("#J_install_form").submit(); // ajax 验证通过后再提交表单
                        }
                        $('#J_install_tip_dbpw').html('');
                        $('#J_install_tip_dbname').html('');
                    }
                    else if(msg == -1)
                    {
                        $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">请在mysql配置文件修sql-mode或sql_mode为NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION</span>');
                    }
                    else if(msg == -2)
                    {
                        $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" style="">你的不是空数据库, 请更换一个数据库名字</span>');
                    }
                    else{
                        $('#dbpw').val("");
                        $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
                    }
                },
                complete:function(){
                },
                error:function(){
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
                    $('#dbpw').val("");
                }
            });
        }



        function checkForm()
        {
            manager = $.trim($('#manager').val());				//用户名表单
            manager_pwd = $.trim($('#manager_pwd').val());				//密码表单
            manager_ckpwd = $.trim($('#manager_ckpwd').val());		//密码提示区
            var site_code = $.trim($('#site_code').val());
            if(manager.length == 0 )
            {
                alert('管理员账号不能为空');
                return false;
            }
            if(manager_pwd.length < 6 )
            {
                alert('管理员密码必须6位数以上');
                return false;
            }
            if(manager_ckpwd !=  manager_pwd)
            {
                alert('两次密码不一致');
                return false;
            }
            // if(!site_code)
            // {
            //     alert('请先注册,获取站点号');
            //     return false;
            // }
            TestDbPwd(1);
        }

        // 二维码
        var letter = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",]
        var createKey = ""
        for (var i = 0; i < 6; i++) {
            var num = parseInt(26*Math.random())
            createKey = createKey+letter[num]
        }

        var qrcode = new QRCode("qrcode", {
            text: "https://h5.opensns.cn/#/pages/backstage/backstage?key="+createKey,
            width: 100,
            height: 100,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

         var dataform = {key:createKey};
        var getSiteCode = setInterval(function () {
            return;
            // $.ajax({
            //     url:"https://h5a.opensns.cn/auth/index/getSiteNumber",
            //     data:dataform,
            //     type:'post',
            //     dataType:'json',
            //     success:function(res){
            //         if(res.code == 200){
            //             var site_code_value=res.data.site_code_value;
            //             document.getElementById("site_code").value = res.data.site_code;
            //             document.getElementById("is_site_code").style.display = "none";
            //             clearInterval(getSiteCode);
            //             $.ajax({
            //                 url: "<?php echo $_SERVER['PHP_SELF']; ?>?step=6&code="+site_code_value,
            //                 data:{code:site_code_value},
            //                 type:"post",
            //                 dataType:"json",
            //             })
            //         }
            //     }
            // })
        },1000)
    </script>
</div>
<?php require './templates/footer.php';?>
</body>
</html>