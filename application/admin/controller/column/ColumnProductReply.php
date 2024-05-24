<?php


namespace app\admin\controller\column;

class ColumnProductReply extends \app\admin\controller\AuthController
{
    use \traits\CurdControllerTrait;
    public function index()
    {
        $where = \service\UtilService::getMore([["is_reply", ""], ["comment", ""]], $this->request);
        $product_id = 0;
        $product_id = input("product_id");
        if ($product_id) {
            $where["product_id"] = $product_id;
        } else {
            $where["product_id"] = 0;
        }
        $this->assign("where", $where);
        $this->assign(\app\admin\model\column\ColumnProductReply::systemPage($where));
        return $this->fetch();
    }
    public function indexs()
    {
        $where = \service\UtilService::getMore([["is_reply", ""], ["comment", ""]], $this->request);
        $product_id = 0;
        $product_id = input("product_id");
        if ($product_id) {
            $where["product_id"] = $product_id;
        } else {
            $where["product_id"] = 0;
        }
        $this->assign("where", $where);
        $this->assign(\app\admin\model\column\ColumnProductReply::systemPage($where, $type = 1));
        return $this->fetch();
    }
    public function delete($id)
    {
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $data["is_del"] = 1;
        if (!\app\admin\model\column\ColumnProductReply::edit($data, $id)) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnProductReply::getErrorInfo("删除失败,请稍候再试!"));
        }
        $reply = \app\admin\model\column\ColumnProductReply::get($id);
        \app\commonapi\model\Gong::delaction("fashangpinpingjia", $reply["uid"], "商品评论被删除");
        $set = \app\osapi\model\com\MessageTemplate::getMessageSet(35);
        $time = time_format(time());
        $title = \app\admin\model\column\ColumnText::where("id", $reply["product_id"])->value("name");
        $length_title = mb_strlen($title, "UTF-8");
        $length_content = mb_strlen($reply["comment"], "UTF-8");
        if (7 < $length_title) {
            $title = mb_substr($title, 0, 7, "UTF-8") . "…";
        }
        if (7 < $length_content) {
            $reply["comment"] = mb_substr($reply["comment"], 0, 4, "UTF-8") . "…";
        }
        $template = str_replace("{年月日时分}", $time, $set["template"]);
        $template = str_replace("{评论内容}", $reply["comment"], $template);
        $template = str_replace("{商品名称}", $title, $template);
        if ($set["status"] == 1) {
            $message_id = \app\osapi\model\com\Message::sendMessage($reply["uid"], 0, $template, 1, $set["title"], 1, "index");
            $read_id = \app\osapi\model\com\MessageRead::createMessageRead($reply["uid"], $message_id, $set["popup"], 1);
        }
        if ($set["sms"] == 1 && $set["status"] == 1) {
            $account = db("user")->where("uid", $reply["uid"])->value("phone");
            $config = \app\admin\model\system\SystemConfig::getMore("cl_sms_sign,cl_sms_template");
            $template = "【" . $config["cl_sms_sign"] . "】" . $template;
            $sms = \app\osapi\lib\ChuanglanSmsApi::sendSMS($account, $template);
            $sms = json_decode($sms, true);
            if ($sms["code"] == 0) {
                $read_data["is_sms"] = 1;
                $read_data["sms_time"] = time();
                \app\osapi\model\com\MessageRead::where("id", $read_id)->update($read_data);
            }
        }
        return \service\JsonService::successful("删除成功!");
    }
    public function set_reply(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["id", "content"], $request);
        if (!$data["id"]) {
            return \service\JsonService::fail("参数错误");
        }
        if ($data["content"] == "") {
            return \service\JsonService::fail("请输入回复内容");
        }
        $save["merchant_reply_content"] = $data["content"];
        $save["merchant_reply_time"] = time();
        $save["is_reply"] = 2;
        $res = \app\admin\model\column\ColumnProductReply::edit($save, $data["id"]);
        if (!$res) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnProductReply::getErrorInfo("回复失败,请稍候再试!"));
        }
        return \service\JsonService::successful("回复成功!");
    }
    public function edit_reply(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["id", "content"], $request);
        if (!$data["id"]) {
            return \service\JsonService::fail("参数错误");
        }
        if ($data["content"] == "") {
            return \service\JsonService::fail("请输入回复内容");
        }
        $save["merchant_reply_content"] = $data["content"];
        $save["merchant_reply_time"] = time();
        $save["is_reply"] = 2;
        $res = \app\admin\model\column\ColumnProductReply::edit($save, $data["id"]);
        if (!$res) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnProductReply::getErrorInfo("回复失败,请稍候再试!"));
        }
        return \service\JsonService::successful("回复成功!");
    }
}

?>