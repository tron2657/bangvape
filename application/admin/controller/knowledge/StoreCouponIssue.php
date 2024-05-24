<?php


namespace app\admin\controller\knowledge;

class StoreCouponIssue extends \app\admin\controller\AuthController
{
    use \traits\CurdControllerTrait;
    protected $bindModel = "app\\admin\\model\\ump\\StoreCouponIssue";
    public function index()
    {
        $where = \service\UtilService::getMore([["status", ""], ["coupon_title", ""]]);
        $this->assign(\app\admin\model\ump\StoreCouponIssue::stsypage($where));
        $this->assign("where", $where);
        return $this->fetch();
    }
    public function delete($id = "")
    {
        if (!$id) {
            return \service\JsonService::fail("参数有误!");
        }
        if (\app\admin\model\ump\StoreCouponIssue::edit(["is_del" => 1], $id, "id")) {
            return \service\JsonService::successful("删除成功!");
        }
        return \service\JsonService::fail("删除失败!");
    }
    public function edit($id = "")
    {
        if (!$id) {
            return \service\JsonService::fail("参数有误!");
        }
        $issueInfo = \app\admin\model\ump\StoreCouponIssue::get($id);
        if (-1 == $issueInfo["status"] || 1 == $issueInfo["is_del"]) {
            return $this->failed("状态错误,无法修改");
        }
        $f = [\service\FormBuilder::radio("status", "是否开启", $issueInfo["status"])->options([["label" => "开启", "value" => 1], ["label" => "关闭", "value" => 0]])];
        $form = \service\FormBuilder::make_post_form("状态修改", $f, \think\Url::build("change_field", ["id" => $id, "field" => "status"]));
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function issue_log($id = "")
    {
        if (!$id) {
            return \service\JsonService::fail("参数有误!");
        }
        $this->assign(\app\admin\model\ump\StoreCouponIssueUser::systemCouponIssuePage($id));
        return $this->fetch();
    }
}

?>