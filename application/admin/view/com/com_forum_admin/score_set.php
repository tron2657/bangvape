{extend name="public/container"}
{block name="content"}
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<style>

</style>

<div class="layui-card">
    <div class="layui-tab">
    <ul class="layui-tab-title">
        <li class="layui-this">积分奖励设置</li>
        <li>积分奖励规则</li>
    </ul>
    <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                <div class="layui-row" style="padding: 10xp 20px;">
                <div class="layui-col-md6" style="line-height: 35px;padding-left: 10px;">
                    <span style="font-size:16px">版主积分奖励设置</span><span style="margin-left: 9px;color: #8a8c8e;">不添加积分类型和上限则超级版主前端无法奖励积分</span>
                </div>
                <div class="layui-col-md6" style="text-align: end;padding-right: 10px;">
                    <button class="layui-btn add-btn1" lay-event='del_ad'>添加</button>
                </div>
            </div>
            <div class="layui-card-body">
                <table class="layui-hide" id="forumSupList" lay-filter="List1"></table>
                <script type="text/html" id="act1">
                    <button class="layui-btn layui-btn-xs del-btn1" lay-event='del_ad'>
                        <i class="fa fa-warning"></i> 删除
                    </button>
                </script>
            </div>

            <div class="layui-row" style="padding: 10xp 20px;">
                <div class="layui-col-md6" style="line-height: 35px;padding-left: 10px;">
                    <span style="font-size:16px">超级版主积分奖励设置</span><span style="margin-left: 9px;color: #8a8c8e;">不添加积分类型和上限则超级版主前端无法奖励积分</span>
                </div>
                <div class="layui-col-md6" style="text-align: end;padding-right: 10px;">
                    <button class="layui-btn add-btn2" lay-event='del_ad2'>添加</button>
                </div>
            </div>
            <div class="layui-card-body">
                <table class="layui-hide" id="forumList" lay-filter="List2"></table>
                <script type="text/html" id="act2">
                    <button class="layui-btn layui-btn-xs del-btn2" lay-event='del_ad2'>
                        <i class="fa fa-warning"></i> 删除
                    </button>
                </script>
            </div>

            <div class="layui-row" style="padding: 10xp 20px;">
                <div class="layui-col-md6" style="line-height: 35px;padding-left: 10px;">
                    <span style="font-size:16px">管理员(系统管理员前台账号)奖励设置</span><span style="margin-left: 9px;color: #8a8c8e;">不添加积分类型和上限则超级版主前端无法奖励积分</span>
                </div>
                <div class="layui-col-md6" style="text-align: end;padding-right: 10px;">
                    <button class="layui-btn add-btn3" lay-event='del_ad3'>添加</button>
                </div>
            </div>
            <div class="layui-card-body">
                <table class="layui-hide" id="adminList" lay-filter="List3"></table>
                <script type="text/html" id="act3">
                    <button class="layui-btn layui-btn-xs del-btn3" lay-event='del_ad3'>
                        <i class="fa fa-warning"></i> 删除
                    </button>
                </script>
            </div>
        </div>
        <div class="layui-tab-item">
        <div class="form" style="margin: 20px">
            <div class="input-box" style="display: flex;align-items: flex-start;">
                <label for="" style="margin-bottom: 0;width: 78px;text-align: right">协议内容</label>
                <textarea type="text/plain" id="myEditor" style="width:700px;margin-left: 10px;">{$reward_points_rules}</textarea>
            </div>
            <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;margin: 40px 0 20px 116px;">
                保存
            </div>
        </div>
        </div>
    </div>
    </div>

</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/toast-js.js"></script>
<script>
    
    window.$list = <?php echo json_encode($score); ?>;
    window.$info = <?php echo json_encode($info); ?>;
    var dataType = {
        "exp": '经验值',
        "fly": '社区积分',
        "buy": '购物积分'
    }
    var idTable = ['#forumSupList', '#forumList', '#adminList']
    console.log($list)
    console.log($info)
    // 添加
    $('.add-btn1').on('click', function() {
        open(1)
    });
    $('.add-btn2').on('click', function() {
        open(2)
    });
    $('.add-btn3').on('click', function() {
        open(3)
    });
    // 弹出层
    function open(num) {
        layer.open({
            type: 1,
            title: '添加',
            area: ['400px', '200px'],
            shadeClose: true, //点击遮罩关闭
            content: '<div id="add_integral" style="margin: 30px 20px;"><div style="margin-bottom: 8px;"><span>积分：</span><div class="layui-input-inline"><select id="task_integral_type"class="form-control task_integral_type"style="height: 32px;width:100px"> ' + content() + '</select></div><div class="layui-input-inline"style="width: 220px;"><input type="number"id="task_integral"value="0"placeholder="请输入"class="layui-input task_integral"></div></div></div><div class="layui-form-item"><div class="grid-demo"style="text-align: end;padding:0 10px"><button class="layui-btn layui-btn-primary" onClick="layer.closeAll();" lay-event="del_ad">取消</button><button class="layui-btn add1" id="add" onClick="add(' + num + ')" lay-event="del_ad">添加</button></div></div>'
        });
    }

    function content() {
        console.log('内容')
        var str = ''
        for (var i = 0; i < $list.length; i++) {
            var b = '<option value="' + $list[i].flag + '">' + $list[i].name + '</option>'
            console.log(b, '内容1')
            str = str + b
        }
        console.log(str)
        return str
    }
var infodata = $info
    function add(num) {
        var list = {}
        list.id = num
        list.status = 1
        var info = infodata [num - 1].info ? infodata [num - 1].info : []
        var addData = {
            flag: $('#task_integral_type').val(),
            name: $('#task_integral_type option:selected').text(),
            num: $('#task_integral').val()
        }
        console.log(addData)
        if (info.length > 0) {
            var isData = true
            for (var i = 0; i < info.length; i++) {
                if (info[i].flag === addData.flag) {
                    info[i].num = addData.num
                    isData = false
                }
            }
            if (isData) {
                info.push(addData)
            }
        } else {
            info = [addData]
        }
        console.log(info)
        list.info = info
        $.ajax({
            url: "{:Url('edit_score')}",
            data: list,
            type: 'post',
            dataType: 'json',
            success: function(re) {
                console.log(re)
                if (re.code === 200) {
                    console.log(list.info)
                    parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                    layer.closeAll();
                }
            }
        })
    }
    var tabe = JSON.parse(JSON.stringify($info))
    layui.use(['layer', 'table'], function() {
        var layer = layui.layer;
        var table = layui.table;
        for (var i = 0; i < $info.length; i++) {
            table.render({
                elem: idTable[i],
                data: tabe[i].info ? tabe[i].info : [],
                cols: [
                    [{
                            field: 'name',
                            title: '积分类型'
                        },
                        {
                            field: 'num',
                            title: '每月上限'
                        },
                        {
                            field: 'right',
                            title: '操作',
                            align: 'center',
                            toolbar: '#act' + (i + 1),
                            width: '14%'
                        },
                    ]
                ]
            });
        // 删除
            table.on('tool(List' + (i + 1) + ')', function(obj) {
                var lists = {}
                if(obj.event==='del_ad') {
                    lists.id = 1
                    lists.info = $info[0].info
                } else if(obj.event==='del_ad2') {
                    lists.id = 2
                    lists.info = $info[1].info
                } else if(obj.event==='del_ad3') {
                    lists.id = 1
                    lists.info = $info[2].info
                }
                lists.status = 1
                console.log($info[2].info)
                
                console.log(lists, 'list数据')
                $eb.$swal('delete', function() {
                    for (var j = 0; j < lists.info.length; j++) {
                    if (lists.info[j].flag === obj.data.flag) {
                        lists.info.splice(j, 1)
                    }
                }
                lists.info = lists.info.length===0?null:lists.info
                    $.ajax({
                        url: "{:Url('edit_score')}",
                        data: lists,
                        type: 'post',
                        dataType: 'json',
                        success: function(res) {
                            if (res.code == 200) {
                                $eb.$swal('success', res.msg);
                                obj.del();
                            }
                        }
                    })
                })
            })
        }
    })
    var ue = UE.getEditor('myEditor',{
        autoHeightEnabled: false,
        initialFrameHeight: 400,
        wordCount: false,
        maximumWords: 100000
    });
     /**
   * 获取编辑器内的内容
   * */
  function getContent() {
    return (ue.getContent());
  }
    $('#save_btn').on('click',function(){
        var list = {};
        list.reward_points_rules = getContent();
        $.ajax({
        url:"{:Url('saveXieyi')}",
        data:list,
        type:'post',
        dataType:'json',
        success:function(re){
            if(re.code == 200){
            $eb.message('success',re.msg);
            }else{
            $eb.message('error',re.msg);
            }
        }
        })
    });
</script>
{/block}