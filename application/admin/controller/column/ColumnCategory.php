<?php


namespace app\admin\controller\column;

class ColumnCategory extends \app\admin\controller\AuthController
{
    public function index()
    {
        $this->assign("pid", $this->request->get("pid", 0));
        $this->assign("cate", \app\admin\model\column\ColumnCategory::getTierList());
        return $this->fetch();
    }
    public function category_list()
    {
        $where = \service\UtilService::getMore([["is_show", ""], ["pid", $this->request->param("pid", "")], ["cate_name", ""], ["page", 1], ["limit", 20], ["order", ""]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnCategory::CategoryList($where));
    }
    public function set_show()
    {
        $is_show = osx_input("is_show", 0, "intval");
        $id = osx_input("id", 0, "intval");
        if ($is_show === "" || $id === "") {
            \service\JsonService::fail("缺少参数");
        }
        $res = \app\admin\model\column\ColumnCategory::where(["id" => $id])->update(["is_show" => (array) $is_show]);
        if ($res !== false) {
            return \service\JsonService::successful($is_show == 1 ? "显示成功" : "隐藏成功");
        }
        return \service\JsonService::fail($is_show == 1 ? "显示失败" : "隐藏失败");
    }
    public function set_category()
    {
        $field = osx_input("field", "", "text");
        $id = osx_input("id", 0, "intval");
        $value = osx_input("value", "", "text");
        if ($field == "" || $id == "" || $value == "") {
            return \service\JsonService::fail("缺少参数");
        }
        if (\app\admin\model\column\ColumnCategory::where(["id" => $id])->update([$field => $value])) {
            return \service\JsonService::successful("保存成功");
        }
        return \service\JsonService::fail("保存失败");
    }
    public function create()
    {
        $field = [\service\FormBuilder::select("pid", "父级")->setOptions(function () {
            $list = \app\admin\model\column\ColumnCategory::getTierList();
            $menus = [["value" => 0, "label" => "顶级菜单"]];
            foreach ($list as $menu) {
                $menus[] = ["value" => $menu["id"], "label" => $menu["html"] . $menu["cate_name"]];
            }
            return $menus;
        })->filterable(1), \service\FormBuilder::input("cate_name", "分类名称"), \service\FormBuilder::frameImageOne("pic", "分类图标", \think\Url::build("admin/widget.images/index", ["fodder" => "pic"]))->icon("image"), \service\FormBuilder::number("sort", "排序"), \service\FormBuilder::radio("is_show", "状态", 1)->options([["label" => "显示", "value" => 1], ["label" => "隐藏", "value" => 0]])];
        $form = \service\FormBuilder::make_post_form("添加分类", $field, \think\Url::build("save"), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function upload()
    {
        $res = \service\UploadService::image("file", "store/category" . date("Ymd"));
        $thumbPath = \service\UploadService::thumb($res->dir);
        $fileInfo = $res->fileInfo->getinfo();
        \app\admin\model\system\SystemAttachment::attachmentAdd($res->fileInfo->getSaveName(), $fileInfo["size"], $fileInfo["type"], $res->dir, $thumbPath, 1);
        if ($res->status == 200) {
            return \service\JsonService::successful("图片上传成功!", ["name" => $res->fileInfo->getSaveName(), "url" => \service\UploadService::pathToUrl($thumbPath)]);
        }
        return \service\JsonService::fail($res->error);
    }
    public function save(\think\Request $request)
    {
        $data = \service\UtilService::postMore(["pid", "cate_name", ["pic", []], "sort", ["is_show", 0]], $request);
        if ($data["pid"] == "") {
            return \service\JsonService::fail("请先在分类管理中添加分类");
        }
        if (!$data["cate_name"]) {
            return \service\JsonService::fail("请输入分类名称");
        }
        if (count($data["pic"]) < 1) {
            return \service\JsonService::fail("请上传分类图标");
        }
        if ($data["sort"] < 0) {
            $data["sort"] = 0;
        }
        $data["pic"] = $data["pic"][0];
        $data["add_time"] = time();
        \app\admin\model\column\ColumnCategory::set($data);
        return \service\JsonService::successful("添加分类成功!");
    }
    public function edit()
    {
        $id = osx_input("id", 0, "intval");
        $c = \app\admin\model\column\ColumnCategory::get($id);
        if (!$c) {
            return \service\JsonService::fail("数据不存在!");
        }
        $field = [\service\FormBuilder::select("pid", "父级", (string) $c->getData("pid"))->setOptions(function () {
            $list = \app\admin\model\column\ColumnCategory::getTierList(\app\admin\model\column\ColumnCategory::where("id", "<>", $id));
            $menus = [["value" => 0, "label" => "顶级菜单"]];
            foreach ($list as $menu) {
                $menus[] = ["value" => $menu["id"], "label" => $menu["html"] . $menu["cate_name"]];
            }
            return $menus;
        })->filterable(1), \service\FormBuilder::input("cate_name", "分类名称", $c->getData("cate_name")), \service\FormBuilder::frameImageOne("pic", "分类图标", \think\Url::build("admin/widget.images/index", ["fodder" => "pic"]), $c->getData("pic"))->icon("image"), \service\FormBuilder::number("sort", "排序", $c->getData("sort")), \service\FormBuilder::radio("is_show", "状态", $c->getData("is_show"))->options([["label" => "显示", "value" => 1], ["label" => "隐藏", "value" => 0]])];
        $form = \service\FormBuilder::make_post_form("编辑分类", $field, \think\Url::build("update", ["id" => $id]), 2);
        $this->assign(compact("form"));
        return $this->fetch("public/form-builder");
    }
    public function update(\think\Request $request)
    {
        $id = osx_input("id", 0, "intval");
        $data = \service\UtilService::postMore(["pid", "cate_name", ["pic", []], "sort", ["is_show", 0]], $request);
        if ($data["pid"] == "") {
            return \service\JsonService::fail("请先在分类管理中添加分类");
        }
        if (!$data["cate_name"]) {
            return \service\JsonService::fail("请输入分类名称");
        }
        if (count($data["pic"]) < 1) {
            return \service\JsonService::fail("请上传分类图标");
        }
        if ($data["sort"] < 0) {
            $data["sort"] = 0;
        }
        $data["pic"] = $data["pic"][0];
        \app\admin\model\column\ColumnCategory::edit($data, $id);
        return \service\JsonService::successful("修改成功!");
    }
    public function delete($id)
    {
        $id = osx_input("id", 0, "intval");
        if (!\app\admin\model\column\ColumnCategory::delCategory($id)) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnCategory::getErrorInfo("删除失败,请稍候再试!"));
        }
        return \service\JsonService::successful("删除成功!");
    }
}

?>