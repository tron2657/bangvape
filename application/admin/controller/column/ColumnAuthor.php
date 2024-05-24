<?php


namespace app\admin\controller\column;

class ColumnAuthor extends \app\admin\controller\AuthController
{
    public function index()
    {
        return $this->fetch();
    }
    public function author_list()
    {
        $where = \service\UtilService::getMore([["nickname", ""], ["page", 1], ["limit", 20], ["order", ""]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnAuthor::AuthorList($where));
    }
    public function create()
    {
        $field = [\service\FormBuilder::input("nickname", "作者昵称"), \service\FormBuilder::input("label", "作者标签")->placeholder("多个标签用英文,隔开"), \service\FormBuilder::text("summary", "作者简介"), \service\FormBuilder::frameImageOne("avatar", "头像", \think\Url::build("admin/widget.images/index", ["fodder" => "avatar"]))->icon("image")];
        $form = \service\FormBuilder::make_post_form("添加作者", $field, \think\Url::build("save"), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function save(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["nickname", "label", ["avatar", []], "summary"], $request);
        if ($data["nickname"] == "") {
            return \service\JsonService::fail("请填写昵称");
        }
        if ($data["label"] == "") {
            return \service\JsonService::fail("请填写标签");
        }
        if ($data["summary"] == "") {
            return \service\JsonService::fail("请填写简介");
        }
        if (count($data["avatar"]) < 1) {
            return \service\JsonService::fail("请上传头像");
        }
        $data["avatar"] = $data["avatar"][0];
        $data["create_time"] = time();
        $data["status"] = 1;
        \app\admin\model\column\ColumnAuthor::set($data);
        return \service\JsonService::successful("添加作者成功!");
    }
    public function edit()
    {
        $id = osx_input("id", 0, "intval");
        $c = \app\admin\model\column\ColumnAuthor::get($id);
        if (!$c) {
            return \service\JsonService::fail("数据不存在!");
        }
        $field = [\service\FormBuilder::input("nickname", "作者昵称", $c->getData("nickname")), \service\FormBuilder::input("label", "作者标签", $c->getData("label"))->placeholder("多个标签用英文,隔开"), \service\FormBuilder::text("summary", "作者简介", $c->getData("summary")), \service\FormBuilder::frameImageOne("avatar", "头像", \think\Url::build("admin/widget.images/index", ["fodder" => "avatar"]), $c->getData("avatar"))->icon("image")];
        $form = \service\FormBuilder::make_post_form("编辑作者", $field, \think\Url::build("update", ["id" => $id]), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request)
    {
        $id = osx_input("id", 0, "intval");
        $data = \service\UtilService::postMore(["nickname", "label", ["avatar", []], "summary"], $request);
        if ($data["nickname"] == "") {
            return \service\JsonService::fail("请填写昵称");
        }
        if ($data["label"] == "") {
            return \service\JsonService::fail("请填写标签");
        }
        if ($data["summary"] == "") {
            return \service\JsonService::fail("请填写简介");
        }
        if (count($data["avatar"]) < 1) {
            return \service\JsonService::fail("请上传头像");
        }
        $data["avatar"] = $data["avatar"][0];
        \app\admin\model\column\ColumnAuthor::edit($data, $id);
        return \service\JsonService::successful("修改成功!");
    }
    public function delete()
    {
        $id = osx_input("id", 0, "intval");
        if (\app\admin\model\column\ColumnAuthor::delAuthor($id) === false) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnAuthor::getErrorInfo("删除失败,请稍候再试!"));
        }
        return \service\JsonService::successful("删除成功!");
    }
}

?>