<?php


namespace app\admin\controller\column;

require "vod-sdk-v5/autoload.php";
class ColumnList extends \app\admin\controller\AuthController
{
    use \traits\CurdControllerTrait;
    public function index()
    {
        $type = osx_input("type", "");
        $type_tab = osx_input("type_tab", 1);
        $is_column = osx_input("is_column", 1, "intval");
        $is_show = osx_input("is_show", "");
        $status = osx_input("status", 1, "intval");
        $this->assign("cate", \app\admin\model\column\ColumnCategory::getTierList());
        if ($is_column == 1) {
            $onsale = \app\admin\model\column\ColumnText::where(["is_show" => 1, "status" => 1, "is_column" => 1])->count();
            $forsale = \app\admin\model\column\ColumnText::where(["is_show" => 0, "status" => 1, "is_column" => 1])->count();
            $warehouse = \app\admin\model\column\ColumnText::where(["status" => 1, "is_column" => 1])->count();
            $recycle = \app\admin\model\column\ColumnText::where(["status" => -1, "is_column" => 1])->count();
        } else {
            $onsale = \app\admin\model\column\ColumnText::where(["is_show" => 1, "status" => 1, "is_column" => 0, "type" => $type])->count();
            $forsale = \app\admin\model\column\ColumnText::where(["is_show" => 0, "status" => 1, "is_column" => 0, "type" => $type])->count();
            $warehouse = \app\admin\model\column\ColumnText::where(["status" => 1, "is_column" => 0, "type" => $type])->count();
            $recycle = \app\admin\model\column\ColumnText::where(["status" => -1, "is_column" => 0, "type" => $type])->count();
        }
        $this->assign(compact("is_column", "onsale", "forsale", "warehouse", "recycle", "type", "is_show", "status", "type_tab"));
        return $this->fetch();
    }
    public function content()
    {
        $pid = osx_input("pid", "");
        $this->assign("pid", $pid);
        return $this->fetch();
    }
    public function select_column()
    {
        return $this->fetch();
    }
    public function issue_log()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return \service\JsonService::fail("参数有误.");
        }
        $data = \app\admin\model\column\ColumnText::systemCouponIssuePage($id);
        $list = $data["data"];
        $this->assign(compact("list"));
        $this->assign("id", $id);
        return $this->fetch();
    }
    public function get_issue_log()
    {
        $page = osx_input("post.page", 0, "intval");
        $id = osx_input("post.id", 0, "intval");
        $data = \app\admin\model\column\ColumnText::systemCouponIssuePage($id, $page, 10);
        $this->assign(["list" => $data["data"], "count" => $data["count"], "total" => $data["total"], "page" => $page, "id" => $id]);
        $res["html"] = $this->fetch("_issue_log");
        $res["status"] = 1;
        return \service\JsonService::success($res);
    }
    public function get_author_list()
    {
        $nickname = osx_input("nickname", "", "text");
        $users = db("column_author")->where("nickname|id", "like", "%" . $nickname . "%")->where("status", 1)->limit(10)->select();
        $data = [];
        if ($users) {
            foreach ($users as $v) {
                if ($v) {
                    $data[] = ["value" => $v["id"], "name" => $v["nickname"]];
                }
            }
        }
        return \service\JsonService::successlayui(count($users), $data, "成功");
    }
    public function get_class_list()
    {
        $data = db("column_class")->where("status", 1)->select();
        return \service\JsonService::success($data);
    }
    public function get_category_list()
    {
        $data = db("column_category")->where("is_show", 1)->where("pid", "<>", 0)->select();
        return \service\JsonService::success($data);
    }
    public function get_column_list()
    {
        $data = \app\admin\model\column\ColumnText::where("status", 1)->where("is_column", 1)->select();
        return \service\JsonService::success($data);
    }
    public function product_list()
    {
        $where = \service\UtilService::getMore([["page", 1], ["limit", 20], ["store_name", ""], ["order", ""], ["pid", ""]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnText::ProductList($where));
    }
    public function column_list()
    {
        $where = \service\UtilService::getMore([["type", ""], ["is_column", 1], ["is_show", ""], ["status", 1], ["page", 1], ["limit", 20], ["store_name", ""], ["cate_id", ""], ["order", ""], ["author_name", ""], ["is_free", ""]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnText::ColumnList($where));
    }
    public function free_list()
    {
        $where = \service\UtilService::getMore(["id", ["data", ""], ["page", 1], ["limit", 20]]);
        return \service\JsonService::successlayui(\app\admin\model\column\ColumnUserBuy::FreeList($where));
    }
    public function set_show()
    {
        $is_show = osx_input("is_show", 0, "intval");
        $id = osx_input("id", 0, "intval");
        ($is_show === "" || $id === "") && \service\JsonService::fail("缺少参数");
        $res = \app\admin\model\column\ColumnText::where(["id" => $id])->update(["is_show" => (array) $is_show]);
        if ($res) {
            return \service\JsonService::successful($is_show == 1 ? "上架成功" : "下架成功");
        }
        return \service\JsonService::fail($is_show == 1 ? "上架失败" : "下架失败");
    }
    public function set_trial()
    {
        $is_trial = osx_input("is_trial", 0, "intval");
        $id = osx_input("id", 0, "intval");
        ($is_trial === "" || $id === "") && \service\JsonService::fail("缺少参数");
        $res = \app\admin\model\column\ColumnText::where(["id" => $id])->update(["is_trial" => (array) $is_trial]);
        if ($res) {
            return \service\JsonService::successful("设置成功");
        }
        return \service\JsonService::fail("设置失败");
    }
    public function set_product()
    {
        $field = osx_input("field", "", "text");
        $id = osx_input("id", 0, "intval");
        $value = osx_input("value", "", "text");
        $field == "" || $id == "" || $value == "" && \service\JsonService::fail("缺少参数");
        if (\app\admin\model\column\ColumnText::where(["id" => $id])->update([$field => $value])) {
            return \service\JsonService::successful("保存成功");
        }
        return \service\JsonService::fail("保存失败");
    }
    public function product_show()
    {
        $post = \service\UtilService::postMore([["ids", []]]);
        if (empty($post["ids"])) {
            return \service\JsonService::fail("请选择需要上架的产品");
        }
        $res = \app\admin\model\column\ColumnText::where("id", "in", $post["ids"])->update(["is_show" => 1]);
        if ($res) {
            return \service\JsonService::successful("上架成功");
        }
        return \service\JsonService::fail("上架失败");
    }
    public function create()
    {
        $this->assign("style", "create");
        return $this->fetch();
    }
    public function create_text()
    {
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $this->assign("column", $column);
        $this->assign("style", "create");
        return $this->fetch();
    }
    public function create_audio()
    {
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $this->assign("column", $column);
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        $this->assign("switch", $switch);
        $this->assign("style", "create");
        return $this->fetch();
    }
    public function create_video()
    {
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $this->assign("column", $column);
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        $this->assign("switch", $switch);
        $this->assign("style", "create");
        return $this->fetch();
    }
    public function upload()
    {
        $res = \service\UploadService::image("file", "store/product/" . date("Ymd"));
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
        $data = \service\UtilService::postMore([["category_id", ""], ["author_id", 1], ["pid", 0], ["name", ""], ["info", ""], ["introduction", ""], ["type", ""], ["m_type", ""], ["keyword", ""], ["media_url", ""], ["image", []], ["images", []], ["is_free", 0], ["is_column", 0], ["price", 0], ["cost_price", 0], ["ot_price", 0], ["strip_num", 0], ["sort", 0], ["sales", 0], ["ficti_sales", 0], ["score", 0], ["is_show", 0], ["is_trial", 0], ["read_count", 1], ["is_type", 1], ["cid", ""], ["is_column", 1]], $request);
        $data["content"] = osx_input("post.content", "", "html");
        if ($data["category_id"] == "") {
            return \service\JsonService::fail("请选择分类");
        }
        if (!$data["name"]) {
            return \service\JsonService::fail("请输入名称");
        }
        if (count($data["image"]) < 1) {
            return \service\JsonService::fail("请上传图片");
        }
        if (count($data["images"]) < 1) {
            return \service\JsonService::fail("请上传轮播图");
        }
        if ($data["price"] == "" || $data["price"] < 0) {
            return \service\JsonService::fail("请输入售价");
        }
        if ($data["ot_price"] == "" || $data["ot_price"] < 0) {
            return \service\JsonService::fail("请输入市场价");
        }
        if (!isset($data["strip_num"]) || !is_numeric($data["strip_num"]) || floatval($data["strip_num"]) != $data["strip_num"]) {
            return \service\JsonService::fail("请填写正确的剥比值");
        }
        if (bccomp($data["price"], $data["strip_num"]) < 0) {
            return \service\JsonService::fail("剥比不能超过售价");
        }
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        if ($data["type"] == 1) {
            $data["m_type"] = 0;
        } else {
            if ($switch == 1) {
                $data["m_type"] = 1;
            } else {
                $data["m_type"] = 0;
            }
            $data["media_url"] = $data["info"];
        }
        $data["image"] = $data["image"][0];
        $data["image"] = \app\admin\model\column\ColumnText::checkImages($data["image"]);
        $data["images"] = explode(",", $data["images"][0]);
        $data["images"] = \app\admin\model\column\ColumnText::checkImages($data["images"]);
        $data["images"] = json_encode($data["images"]);
        $data["create_time"] = time();
        $data["status"] = 1;
        $res = \app\admin\model\column\ColumnText::insertGetId($data);
        if ($res !== false) {
            if ($data["cid"] != "") {
                $cid = explode(",", $data["cid"]);
                $map["cid"] = $value;
                $map["pid"] = $res;
                $map["sort"] = 0;
                $map["status"] = 1;
                $map["create_time"] = time();
                \app\admin\model\column\ColumnClassProduct::insert($map);
                unset($value);
            }
            return \service\JsonService::successful("添加成功!");
        }
        return \service\JsonService::fail("添加失败!");
    }
    public function edit_content()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\ColumnText::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $this->assign(["content" => \app\admin\model\column\ColumnText::where("id", $id)->value("description"), "field" => "description", "action" => \think\Url::build("change_field", ["id" => $id, "field" => "description"])]);
        return $this->fetch("public/edit_content");
    }
    public function edit()
    {
        $id = osx_input("id", 0, "intval");
        $info = \app\admin\model\column\ColumnText::get($id);
        $info["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $info["author_id"])->value("nickname");
        $info["images"] = json_decode($info["images"], true);
        $info["cid"] = \app\admin\model\column\ColumnClassProduct::where("pid", $info["id"])->where("status", 1)->column("cid");
        $info["cid"] = implode(",", $info["cid"]);
        $this->assign("info", $info);
        $this->assign("style", "edit");
        return $this->fetch("create");
    }
    public function edit_text()
    {
        $id = osx_input("id", 0, "intval");
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $info = \app\admin\model\column\ColumnText::get($id);
        $info["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $info["author_id"])->value("nickname");
        $info["images"] = json_decode($info["images"], true);
        $info["cid"] = \app\admin\model\column\ColumnClassProduct::where("pid", $info["id"])->where("status", 1)->column("cid");
        $info["cid"] = implode(",", $info["cid"]);
        if ($info["pid"]) {
            $pid = explode(",", $info["pid"]);
            $pid = array_unique($pid);
            $pid_info = [];
            $pid_arr = [];
            foreach ($pid as $k => $value) {
                if ($value) {
                    $ct = \app\admin\model\column\ColumnText::get($value);
                    if ($ct) {
                        $ct["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $ct["author_id"])->value("nickname");
                        $pid_info[] = $ct;
                        $pid_arr[] = $value;
                    }
                }
            }
            unset($value);
            $info["pid_info"] = $pid_info;
            $info["pid"] = implode(",", $pid_arr);
        } else {
            $info["pid_info"] = "";
        }
        $this->assign("info", $info);
        $this->assign("style", "edit");
        $this->assign("column", $column);
        return $this->fetch("create_text");
    }
    public function edit_audio()
    {
        $id = osx_input("id", 0, "intval");
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $info = \app\admin\model\column\ColumnText::get($id);
        $info["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $info["author_id"])->value("nickname");
        $info["images"] = json_decode($info["images"], true);
        $info["cid"] = \app\admin\model\column\ColumnClassProduct::where("pid", $info["id"])->where("status", 1)->column("cid");
        $info["cid"] = implode(",", $info["cid"]);
        if ($info["pid"]) {
            $pid = explode(",", $info["pid"]);
            $pid = array_unique($pid);
            $pid_info = [];
            $pid_arr = [];
            foreach ($pid as $k => $value) {
                if ($value) {
                    $ct = \app\admin\model\column\ColumnText::get($value);
                    if ($ct) {
                        $ct["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $ct["author_id"])->value("nickname");
                        $pid_info[] = $ct;
                        $pid_arr[] = $value;
                    }
                }
            }
            unset($value);
            $info["pid_info"] = $pid_info;
            $info["pid"] = implode(",", $pid_arr);
        } else {
            $info["pid_info"] = "";
        }
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        $this->assign("switch", $switch);
        $this->assign("info", $info);
        $this->assign("style", "edit");
        $this->assign("column", $column);
        return $this->fetch("create_audio");
    }
    public function edit_video()
    {
        $id = osx_input("id", 0, "intval");
        $column_id = osx_input("column_id", 0);
        $column = db("column_text")->where("id", $column_id)->where("is_column", 1)->where("status", 1)->where("is_show", 1)->find();
        if (empty($column)) {
            $column = [];
        }
        if (!empty($column) && $column["is_free"] == 1) {
            $column["price"] = "免费";
        }
        $info = \app\admin\model\column\ColumnText::get($id);
        $info["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $info["author_id"])->value("nickname");
        $info["images"] = json_decode($info["images"], true);
        $info["cid"] = \app\admin\model\column\ColumnClassProduct::where("pid", $info["id"])->where("status", 1)->column("cid");
        $info["cid"] = implode(",", $info["cid"]);
        if ($info["pid"]) {
            $pid = explode(",", $info["pid"]);
            $pid = array_unique($pid);
            $pid_info = [];
            $pid_arr = [];
            foreach ($pid as $k => $value) {
                if ($value) {
                    $ct = \app\admin\model\column\ColumnText::get($value);
                    if ($ct) {
                        $ct["nickname"] = \app\admin\model\column\ColumnAuthor::where("id", $ct["author_id"])->value("nickname");
                        $pid_info[] = $ct;
                        $pid_arr[] = $value;
                    }
                }
            }
            unset($value);
            $info["pid_info"] = $pid_info;
            $info["pid"] = implode(",", $pid_arr);
        } else {
            $info["pid_info"] = "";
        }
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        $this->assign("switch", $switch);
        $this->assign("info", $info);
        $this->assign("style", "edit");
        $this->assign("column", $column);
        return $this->fetch("create_video");
    }
    public function update(\think\Request $request)
    {
        $id = osx_input("id", 0, "intval");
        $data = \service\UtilService::postMore([["category_id", ""], "author_id", ["pid", 0], "name", "info", ["introduction", ""], ["type", ""], ["m_type", ""], ["keyword", ""], ["media_url", ""], ["image", []], ["images", ""], ["is_free", 0], ["is_column", 0], ["price", 0], ["cost_price", 0], ["ot_price", 0], ["strip_num", 0], ["sort", 0], ["sales", 0], ["ficti_sales", 0], ["score", 0], ["is_show", 0], ["is_trial", 0], ["read_count", 1], ["is_type", 1], ["cid", ""], ["is_column", 1]], $request);
        $data["content"] = $_POST["content"];
        if ($data["category_id"] == "") {
            return \service\JsonService::fail("请选择分类");
        }
        if (!$data["name"]) {
            return \service\JsonService::fail("请输入名称");
        }
        if (count($data["image"]) < 1) {
            return \service\JsonService::fail("请上传图片");
        }
        if ($data["images"] == "") {
            return \service\JsonService::fail("请上传轮播图");
        }
        if ($data["price"] == "" || $data["price"] < 0) {
            return \service\JsonService::fail("请输入售价");
        }
        if ($data["ot_price"] == "" || $data["ot_price"] < 0) {
            return \service\JsonService::fail("请输入市场价");
        }
        if (!isset($data["strip_num"]) || !is_numeric($data["strip_num"]) || floatval($data["strip_num"]) != $data["strip_num"]) {
            return \service\JsonService::fail("请填写正确的剥比值");
        }
        if (bccomp($data["price"], $data["strip_num"]) < 0) {
            return \service\JsonService::fail("剥比不能超过售价");
        }
        $data["image"] = $data["image"][0];
        $data["image"] = \app\admin\model\column\ColumnText::checkImages($data["image"]);
        $data["images"] = explode(",", $data["images"]);
        $data["images"] = \app\admin\model\column\ColumnText::checkImages($data["images"]);
        $data["images"] = json_encode($data["images"]);
        $getIfYun = $this::ifYunUpload();
        $switch = $getIfYun["switch"];
        if ($data["type"] == 1) {
            $data["m_type"] = 0;
        } else {
            if ($switch == 1) {
                $data["m_type"] = 1;
            } else {
                $data["m_type"] = 0;
            }
            $data["media_url"] = $data["info"];
        }
        $res = \app\admin\model\column\ColumnText::edit($data, $id);
        if ($res !== false) {
            if ($data["cid"] != "") {
                \app\admin\model\column\ColumnClassProduct::where("pid", $id)->delete();
                $cid = explode(",", $data["cid"]);
                $map["cid"] = $value;
                $map["pid"] = $id;
                $map["sort"] = 0;
                $map["status"] = 1;
                $map["create_time"] = time();
                \app\admin\model\column\ColumnClassProduct::insert($map);
                unset($value);
            }
            return \service\JsonService::successful("修改成功!");
        }
        return \service\JsonService::fail("修改失败!");
    }
    public function delete()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return $this->failed("数据不存在");
        }
        if (!\app\admin\model\column\ColumnText::be(["id" => $id])) {
            return $this->failed("产品数据不存在");
        }
        if (\app\admin\model\column\ColumnText::be(["id" => $id, "status" => -1])) {
            $data["status"] = 1;
            if (!\app\admin\model\column\ColumnText::edit($data, $id)) {
                return \service\JsonService::fail(\app\admin\model\column\ColumnText::getErrorInfo("恢复失败,请稍候再试!"));
            }
            return \service\JsonService::successful("成功恢复产品!");
        }
        $data["status"] = -1;
        $data["is_show"] = 0;
        if (!\app\admin\model\column\ColumnText::edit($data, $id)) {
            return \service\JsonService::fail(\app\admin\model\column\ColumnText::getErrorInfo("删除失败,请稍候再试!"));
        }
        return \service\JsonService::successful("成功移到回收站!");
    }
    public function collect()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\ColumnText::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $this->assign(\app\admin\model\column\StoreProductRelation::getCollect($id));
        return $this->fetch();
    }
    public function like()
    {
        $id = osx_input("id", 0, "intval");
        if (!$id) {
            return $this->failed("数据不存在");
        }
        $product = \app\admin\model\column\ColumnText::get($id);
        if (!$product) {
            return \service\JsonService::fail("数据不存在!");
        }
        $this->assign(\app\admin\model\column\StoreProductRelation::getLike($id));
        return $this->fetch();
    }
    public function edit_product_price(\think\Request $request)
    {
        $data = \service\UtilService::postMore([["id", 0], ["price", 0]], $request);
        if (!$data["id"]) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\admin\model\column\ColumnText::edit(["price" => $data["price"]], $data["id"]);
        if ($res) {
            return \service\JsonService::successful("修改成功");
        }
        return \service\JsonService::fail("修改失败");
    }
    public function edit_product_stock(\think\Request $request)
    {
        $data = \service\UtilService::postMore([["id", 0], ["stock", 0]], $request);
        if (!$data["id"]) {
            return \service\JsonService::fail("参数错误");
        }
        $res = \app\admin\model\column\ColumnText::edit(["stock" => $data["stock"]], $data["id"]);
        if ($res) {
            return \service\JsonService::successful("修改成功");
        }
        return \service\JsonService::fail("修改失败");
    }
    public function recommendProduct()
    {
        $data = \service\UtilService::getMore(["id", ["recommend_sell", 1]]);
        \app\admin\model\column\ColumnText::edit(["recommend_sell" => $data["recommend_sell"]], $data["id"], "id");
        return \service\JsonService::successful("设置成功");
    }
    public function ifYunUpload()
    {
        $getTencentConfig = \app\admin\model\system\SystemConfig::getMore(["tencent_video_is_open", "tencent_video_secret_id", "tencent_video_secret_key", "tencent_video_procedure", "tencent_video_save_key"]);
        $string["switch"] = $getTencentConfig["tencent_video_is_open"];
        $string["sid"] = $getTencentConfig["tencent_video_secret_id"];
        $string["skey"] = $getTencentConfig["tencent_video_secret_key"];
        $string["pkey"] = $getTencentConfig["tencent_video_save_key"];
        return $string;
    }
    public function mediaUpload()
    {
        $postSaveTypePath = input("post.path");
        $resPath = \service\UploadService::file("file", $postSaveTypePath . "-tencentTemp");
        if (!strpos(PUBILC_PATH, "public")) {
            $resPath->dir = str_replace("public/", "", $resPath->dir);
        }
        $onlyPath = $resPath->uploadPath;
        $onlyFileName = basename($resPath->dir);
        $uploadFilePath = $resPath->dir;
        $newPath = getcwd() . $uploadFilePath;
        if (file_exists($newPath)) {
            $getYunUpload = $this::yunUpload($newPath);
            $mediaFieldID = $getYunUpload["fileID"];
            $mediaPlayURL = $getYunUpload["mediaURL"];
            if ($mediaFieldID) {
                $del = $this->deleteMediaFile($onlyPath, $onlyFileName);
                if ($del == 1) {
                    $res = ["status" => 1, "msg" => "上传成功！", "src" => $mediaPlayURL];
                } else {
                    $res = ["status" => 1, "msg" => "上传成功！请手动删除服务器临时文件！", "src" => $mediaPlayURL];
                }
                return json_encode($res);
            }
            $mediaError = $getYunUpload["e"];
            $res = ["status" => -1, "msg" => $mediaError];
            return json_encode($res);
        }
        $res = ["status" => -1, "msg" => "上传失败！文件不存在！"];
        return json_encode($res);
    }
    public function yunUpload()
    {
        $filePath = osx_input("filePath", "", "text");
        $getYunConfig = $this::ifYunUpload();
        $client = new \Vod\VodUploadClient($getYunConfig["sid"], $getYunConfig["skey"]);
        $request = new \Vod\Model\VodUploadRequest();
        $request->MediaFilePath = $filePath;
        try {
            $rsp = $client->upload("ap-guangzhou", $request);
            $fileID = $rsp->FileId;
            $mediaURL = $rsp->MediaUrl;
            if ($fileID) {
                $result = ["fileID" => $fileID, "mediaURL" => $mediaURL];
            }
            return $result;
        } catch (Exception $e) {
            $result = ["e" => "上传失败.[" . $e . "]"];
            return $result;
        }
    }
    private function deleteMediaFile($filePath, $fileName)
    {
        $getWholePath = $_SERVER["DOCUMENT_ROOT"] . "/" . $filePath;
        $correctPath = str_replace("\\", "/", $getWholePath);
        if (is_dir($correctPath)) {
            $handle = opendir($correctPath);
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != ".." && $item != $fileName) {
                    $items = $correctPath . "/" . $item;
                    unlink($items);
                }
            }
            closedir($handle);
            return 1;
        }
        return 0;
    }
}

?>