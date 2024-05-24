<?php

namespace PHPMailer;
use app\admin\model\system\SystemConfig;

class SendEmail
{
    public static $Host = 'smtp.163.com'; //smtp服务器
    private static $From = '13309406026@163.com'; //发送者的邮件地址
    private static $FromName = ''; //发送邮件的用户昵称
    private static $Username = '13309406026@163.com'; //登录到邮箱的用户名
    private static $Password = ''; //第三方登录的授权码，在邮箱里面设置

    /**
     * @desc 发送普通邮件
     * @param $title 邮件标题
     * @param $message 邮件正文
     * @param $emailAddress 邮件地址
     * @return bool|string 返回是否发送成功
     */
    public static function SendEmail($title=1,$message=1,$emailAddress='')
    {
        $config=SystemConfig::getMore('email_host,email_from,email_name,email_user,email_password');
        $mail = new PHPMailer();
        $mail->SMTPSecure = 'ssl';
        //3.设置属性，告诉我们的服务器，谁跟谁发送邮件
        //$mail -> SMTPDebug = 2;
        $mail -> IsSMTP();			//告诉服务器使用smtp协议发送
        $mail -> SMTPAuth = true;		//开启SMTP授权
        $mail -> Host = $config['email_host'];	//告诉我们的服务器使用的smtp服务器发送
        $mail -> From = $config['email_from'];	//发送者的邮件地址
        $mail -> FromName = $config['email_name'];		//发送邮件的用户昵称
        $mail -> Username = $config['email_user'];	//登录到邮箱的用户名
        $mail -> Password = $config['email_password'];	    //第三方登录的授权码，在邮箱里面设置
        //编辑发送的邮件内容
        $mail -> IsHTML(true);		    //发送的内容使用html编写
        $mail -> CharSet = 'utf-8';		//设置发送内容的编码
        $mail -> Subject = $title;//设置邮件的标题
        $mail -> Port = 465;//端口
        $mail -> MsgHTML($message);	//发送的邮件内容主体
        $mail -> AddAddress($emailAddress);    //收人的邮件地址
        $mail -> Debugoutput = 'html';// 支持HTML格式
        //调用send方法，执行发送
        $result = $mail -> Send();
        if($result){
           return true;
        }else{
            return false;
        }
    }

}