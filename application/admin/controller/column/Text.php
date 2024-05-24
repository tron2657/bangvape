<?php


namespace app\admin\controller\column;

class Text extends \app\admin\controller\AuthController
{
    public function index()
    {
        $this->assign("cate", \app\admin\model\column\TextUser::getTextUser());
        return $this->fetch();
    }
    public function category_list()
    {
        $data = \app\admin\model\column\TextUser::getTextUser();
        return \service\JsonService::successlayui(count($data), $data);
    }
    public function create()
    {
        $field = [\service\FormBuilder::input("nickname", "作者昵称"), \service\FormBuilder::frameImageOne("avatar", "用户头像", \think\Url::build("admin/widget.images/index", ["fodder" => "avatar"]))->icon("image"), \service\FormBuilder::input("level", "作者标签"), \service\FormBuilder::input("signature", "作者描述")];
        $form = \service\FormBuilder::make_post_form("新增作家信息", $field, \think\Url::build("save"), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function save(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["nickname", "avatar", "level", "signature"], $request);
        if (!$data["nickname"]) {
            return \service\JsonService::fail("请编辑作者昵称");
        }
        if (!$data["avatar"]) {
            return \service\JsonService::fail("请编辑作者头像");
        }
        if (!$data["level"]) {
            return \service\JsonService::fail("请编辑作者标签");
        }
        if (!$data["signature"]) {
            return \service\JsonService::fail("请编辑作者描述");
        }
        $res = db("text_user")->insert($data);
        if (!$res) {
            return \service\JsonService::successful("添加失败!");
        }
        return \service\JsonService::successful("添加成功!");
    }
    public function edit()
    {
        $id = osx_input("id", 0, "intval");
        $c = \app\admin\model\column\TextUser::get($id);
        if (!$c) {
            return \service\JsonService::fail("数据不存在!");
        }
        $field = [\service\FormBuilder::input("nickname", "作者昵称", $c->getData("nickname")), \service\FormBuilder::frameImageOne("avatar", "用户头像", \think\Url::build("admin/widget.images/index", ["fodder" => "avatar"]), $c->getData("avatar"))->icon("image"), \service\FormBuilder::input("level", "作者标签", $c->getData("level")), \service\FormBuilder::textarea("signature", "作者描述", $c->getData("signature"))];
        $form = \service\FormBuilder::make_post_form("编辑作家信息", $field, \think\Url::build("update", ["id" => $id]), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request)
    {
        $id = osx_input("id", 0, "intval");
        $data = \service\UtilService::postMore(["nickname", "avatar", "level", "signature"], $request);
        if (!$data["nickname"]) {
            return \service\JsonService::fail("请编辑作者昵称");
        }
        if (!$data["avatar"]) {
            return \service\JsonService::fail("请编辑作者头像");
        }
        if (!$data["level"]) {
            return \service\JsonService::fail("请编辑作者标签");
        }
        if (!$data["signature"]) {
            return \service\JsonService::fail("请编辑作者描述");
        }
        $res = db("text_user")->where("aid", $id)->update($data);
        if (!$res) {
            return \service\JsonService::successful("修改失败!");
        }
        return \service\JsonService::successful("修改成功!");
    }
    public function delete()
    {
        $id = osx_input("id", 0, "intval");
        $res = db("text_user")->where("aid", $id)->delete();
        if ($res) {
            return \service\JsonService::successful("删除失败");
        }
        return \service\JsonService::successful("删除成功!");
    }
}

?>