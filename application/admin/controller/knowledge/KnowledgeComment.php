<?php


namespace app\admin\controller\knowledge;

class KnowledgeComment extends \app\admin\controller\AuthController
{
    use \traits\CurdControllerTrait;
    public function index()
    {
        $where = \service\UtilService::getMore([["is_reply", ""], ["comment", ""]], $this->request);
        $product_id = input("product_id");
        if ($product_id) {
            $where["product_id"] = $product_id;
        } else {
            $where["product_id"] = 0;
        }
        $this->assign("where", $where);
        $this->assign("year", getMonth("y"));
        $this->assign(\app\admin\model\column\ColumnProductReply::systemPage($where, 1));
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
    public function order_list(\think\Request $request)
    {
        $data = \service\UtilService::getMore(["name", ["is_del", 0], "data", ["page", 1], ["limit", 20]], $request);
        $map["is_del"] = $data["is_del"];
        if ($data["name"]) {
            $map["comment"] = ["like", "%" . $data["name"] . "%"];
        }
        if ($data["data"]) {
            $map["add_time"] = \app\admin\model\com\VisitAudit::timeRange($data["data"]);
        }
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnProductReply::order_list($map, $data["page"], $data["limit"], "add_time desc"));
    }
    public function reply()
    {
        $params = \service\UtilService::getMore([["id", ""], ["merchant_reply_content", ""], ["is_post", 0]], $this->request);
        if ($params["is_post"] == 1) {
            $data["merchant_reply_content"] = $params["merchant_reply_content"];
            $data["merchant_reply_time"] = time();
            $data["is_reply"] = 2;
            $res = \app\admin\model\column\ColumnProductReply::edit($data, $params["id"]);
            if ($res !== false) {
                return \service\JsonService::successful("回复成功!");
            }
            return \service\JsonService::fail(\app\admin\model\column\ColumnProductReply::getErrorInfo("回复失败,请稍候再试!"));
        }
        $rep = db("store_product_reply")->where("id", $params["id"])->field("id,merchant_reply_content")->find();
        $reply_content = empty($rep) ? "" : $rep["merchant_reply_content"];
        $field = [\service\FormBuilder::textarea("merchant_reply_content", "回复内容", $reply_content), \service\FormBuilder::hidden("id", $params["id"]), \service\FormBuilder::hidden("is_post", 1)];
        $form = \service\FormBuilder::make_post_form("填写回复内容", $field, \think\Url::build("reply"), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
}

?>