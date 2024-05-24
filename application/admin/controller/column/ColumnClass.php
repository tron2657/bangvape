<?php


namespace app\admin\controller\column;

class ColumnClass extends \app\admin\controller\AuthController
{
    public function index()
    {
        return $this->fetch();
    }
    public function class_list()
    {
        $where = \service\UtilService::getMore([["name", ""], ["page", 1], ["limit", 20], ["order", ""]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnClass::ClassList($where));
    }
    public function content()
    {
        $id = osx_input("id", "");
        $this->assign("pid", $id);
        return $this->fetch();
    }
    public function content_list()
    {
        $where = \service\UtilService::getMore(["pid", ["page", 1], ["limit", 20]]);
        $info = \app\admin\model\column\ColumnClass::get($where["pid"]);
        $product = db("column_class_product")->where("cid", $where["pid"])->where("status", 1)->where("pid", ">", 0)->page($where["page"], $where["limit"])->order("sort desc")->field("id,sort,pid")->select();
        $column_text_info = \app\admin\model\column\ColumnText::get($value["pid"]);
        if (!$column_text_info) {
            unset($product[$key]);
        } else {
            $value["info"] = $column_text_info;
            $nickname = \app\admin\model\column\ColumnAuthor::where("id", $value["info"]["author_id"])->value("nickname");
            $value["info"]["nickname"] = $nickname ? $nickname : "";
        }
        unset($value);
        $info["product"] = $product;
        $info["product_count"] = db("column_class_product")->where("cid", $where["pid"])->where("pid", ">", 0)->where("status", 1)->count();
        return \service\JsonService::successlayui($info);
    }
    public function create()
    {
        $field = [\service\FormBuilder::input("name", "栏目名称")->required("栏目名称必填"), \service\FormBuilder::input("sort", "栏目排序")->placeholder("填写字段（0-100数值）")->required("栏目排序必填"), \service\FormBuilder::text("summary", "栏目简介")->required("栏目简介必填"), \service\FormBuilder::radio("type", "栏目样式", 1)->options([["value" => 1, "label" => "纵向单列"], ["value" => 2, "label" => "纵向双列"]])->col(\service\FormBuilder::col(12))->required("栏目样式必选"), \service\FormBuilder::input("num", "显示行数")->required("显示行数必填")];
        $form = \service\FormBuilder::make_post_form("添加栏目", $field, \think\Url::build("save"), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function save(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["name", "sort", "summary", "type", "num"], $request);
        if ($data["name"] == "") {
            return \service\JsonService::fail("请填写栏目名称");
        }
        if ($data["sort"] == "") {
            return \service\JsonService::fail("请填写栏目排序");
        }
        if ($data["summary"] == "") {
            return \service\JsonService::fail("请填写栏目简介");
        }
        if ($data["type"] == "") {
            return \service\JsonService::fail("请选择栏目样式");
        }
        if ($data["num"] == "") {
            return \service\JsonService::fail("请填写显示行数");
        }
        $data["create_time"] = time();
        $data["status"] = 1;
        \app\admin\model\column\ColumnClass::set($data);
        return \service\JsonService::successful("添加栏目成功!");
    }
    public function edit()
    {
        $id = osx_input("id", 0, "intval");
        $c = \app\admin\model\column\ColumnClass::get($id);
        if (!$c) {
            return \service\JsonService::fail("数据不存在!");
        }
        $field = [\service\FormBuilder::input("name", "栏目名称", $c->getData("name"))->required("栏目名称必填"), \service\FormBuilder::input("sort", "栏目排序", $c->getData("sort"))->placeholder("填写字段（0-100数值）")->required("栏目排序必填"), \service\FormBuilder::text("summary", "栏目简介", $c->getData("summary"))->required("栏目简介必填"), \service\FormBuilder::radio("type", "栏目样式", $c->getData("type"))->options([["value" => 1, "label" => "纵向单列"], ["value" => 2, "label" => "纵向双列"]])->col(\service\FormBuilder::col(12))->required("栏目样式必选"), \service\FormBuilder::input("num", "显示行数", $c->getData("num"))->required("显示行数必填")];
        $form = \service\FormBuilder::make_post_form("编辑栏目", $field, \think\Url::build("update", ["id" => $id]), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request)
    {
        $id = osx_input("id", 0, "intval");
        $data = \service\UtilService::postMore(["name", "sort", "summary", "type", "num"], $request);
        if ($data["name"] == "") {
            return \service\JsonService::fail("请填写栏目名称");
        }
        if ($data["sort"] == "") {
            return \service\JsonService::fail("请填写栏目排序");
        }
        if ($data["summary"] == "") {
            return \service\JsonService::fail("请填写栏目简介");
        }
        if ($data["type"] == "") {
            return \service\JsonService::fail("请选择栏目样式");
        }
        if ($data["num"] == "") {
            return \service\JsonService::fail("请填写显示行数");
        }
        \app\admin\model\column\ColumnClass::edit($data, $id);
        return \service\JsonService::successful("修改成功!");
    }
    public function delete()
    {
        $id = osx_input("id", 0, "intval");
        if (\app\admin\model\column\ColumnClass::delAuthor($id) === false) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnClass::getErrorInfo("删除失败,请稍候再试!"));
        }
        return \service\JsonService::successful("删除成功!");
    }
    public function set_status()
    {
        $status = osx_input("is_show", 0, "intval");
        $id = osx_input("id", 0, "intval");
        if ($status === "" || $id === "") {
            \service\JsonService::fail("缺少参数");
        }
        $res = \app\admin\model\column\ColumnClass::where(["id" => $id])->update(["status" => (array) $status]);
        if ($res !== false) {
            return \service\JsonService::successful($status == 1 ? "显示成功" : "隐藏成功");
        }
        return \service\JsonService::fail($status == 1 ? "显示失败" : "隐藏失败");
    }
    public function sort(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["id", "sort"], $request);
        $res = \app\admin\model\column\ColumnClassProduct::where("id", $data["id"])->update(["sort" => $data["sort"]]);
        if ($res !== false) {
            return \service\JsonService::successful("修改成功");
        }
        return \service\JsonService::fail("修改失败");
    }
}

?>