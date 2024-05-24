{extend name="public/container"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/jquery.min.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}plug/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>

<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<!-- <link href="{__FRAME_PATH}js/plugins/bootstrap-table/extensions/reorder-rows/bootstrap-table-reorder-rows.css" rel="stylesheet"> -->

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    .createFollowCheck input[type="number"] {
        -moz-appearance: textfield;
    }
    .common-input{
        margin-left: 10px;
        width: 300px;
        margin-right: 5px;
    }
    tr{
        height: 50px;
    }
    td {
        padding: 0 15px!important;
    }
    a{
        color: #333!important;
    }
    .drag-background-class {
        background: #fff;
    }
    .draggable{
        cursor: move;
    }
    .filtered{
        cursor: move;
    }
    .change-class{
        background-color: #e5e5e5;
    }
</style>
{/block}
{block name="content"}
<div id="app" class="row" style="width: 100%;margin-left: 0;display: none;">
    <div class="col-sm-12" style="background-color: #fff">
        <div class="layui-tab layui-tab-brief" lay-filter="tab" style="margin-left: -15px;margin-top: -10px;">
            <ul class="layui-tab-title" style="background-color: white;top: 10px">
                <li lay-id="list">
                    <a href="{:Url('channel.index/config')}">基础设置</a>
                </li>
                <li lay-id="list" class="layui-this">
                    <a href="javascript:;" >频道设置</a>
                </li>
            </ul>
        </div>
        <form id="form" role="form" class="form-horizontal" style="margin-top: 40px">
            <div id="haha" class="col-sm-12" style="margin-bottom: 30px;">
                <a-alert message="提示" type="info" show-icon>
                    <p slot="description">1. 点击拖动可对频道进行排序<br />
                        2. 固定的频道，前端用户将无法对其进行排序和移除操作<br />
                        3、「保存」操作后，未来新注册用户的频道导航将默认为此处所有设置，已注册用户【推荐频道】中仅同步固定频道部分，不影响用户自定义设置<br />
                        4、「同步」操作后该频道将同步到所有用户的频道列表，「全部同步」操作后将此处所有设置同步到全体用户，覆盖用户原有的自定义设置<br />
                        <span style="color: red">（正式上线后不建议频繁修改频道导航，可能会影响用户正常使用）</span>
                    </p>
                </a-alert>
            </div>
            <div class="form-group">
                <div class="col-sm-12" style="padding: 0 30px;">
                    <div style="font-weight: 600;margin-bottom: 10px;">我的频道<span style="font-size: 12px;font-weight: 500;color: #999">（直接显示在频道导航栏，固定的栏目排序在前，未固定的栏目排序靠后）</span></div>
                    <table id="table_system" class="table table-bordered table-hover" lay-size="lg">
                        <thead>
                        <tr>
                            <th data-field="name" class="col-sm-6" style="padding: 0 15px;">
                                <div class="th-inner ">频道名称</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th data-field="default-follow" class="col-sm-2" data-align="center" style="text-align: center;">
                                <div class="th-inner">频道类型</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th data-field="operate" class="col-sm-4" data-align="center" style="text-align: center;">
                                <div class="th-inner">操作</div>
                                <div class="fht-cell"></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="dragTable" ref="dragTable">
                        <template v-if="systemList.length > 0">
                        <tr :class="[{filtered: item.fixed, draggable: !item.fixed},item.class]" v-for="(item, index) in systemList" v-if="item.default_open_status == 1" :key="item.id">
                            <!-- <td data-visible="false">{{item.id}}</td> -->
                            <td>{{item.title}}<span v-if="item.is_index">（首页）</span></td>
                            <td style="text-align: center;">
                                {{item.type === 1 ? '系统频道' : '自定义频道'}}
                            </td>
                            <td style="text-align: center;">
                                <a-button type="primary" size="small" @click="handleOpenStatus($event, item)" :disabled="!!item.is_index">{{item.default_open_status ? '取消推荐' : '推荐'}}</a-button>
                                <a-button type="primary" size="small" @click="handleFixed($event, item)" :disabled="!!item.is_index">{{item.fixed ? '取消固定' : '固定'}}</a-button>
                                <a-button type="primary" size="small" @click="setIndex($event, item)" v-if="item.fixed && !item.is_index">设为首页</a-button>
                                <a-button type="primary" size="small" @click="handleSyncOne($event, item)" v-if="!item.fixed">同步</a-button>
                            </td>
                        </tr>
                        </template>
                        </tbody>
                    </table>
                    <div style="font-weight: 600;margin-bottom: 10px;">更多频道<span style="font-size: 12px;font-weight: 500;color: #999">（不直接显示在频道导航栏）</span></div>
                    <table id="table_system" class="table table-bordered table-hover" lay-size="lg">
                        <thead>
                        <tr>
                            <th data-field="name" class="col-sm-6" style="padding: 0 15px;">
                                <div class="th-inner ">频道名称</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th data-field="default-follow" class="col-sm-2" data-align="center" style="text-align: center;">
                                <div class="th-inner">频道类型</div>
                                <div class="fht-cell"></div>
                            </th>
                            <th data-field="operate" class="col-sm-4" data-align="center" style="text-align: center;">
                                <div class="th-inner">操作</div>
                                <div class="fht-cell"></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="dragTable" ref="dragTable2">
                        <template v-if="systemList.length > 0">
                            <tr v-for="(item, index) in systemList" v-if="item.default_open_status == 0" :key="item.id">
                                <td>{{item.title}}</td>
                                <td style="text-align: center;">
                                    {{item.type === 1 ? '系统频道' : '自定义频道'}}
                                </td>
                                <td style="text-align: center;">
                                    <a-button type="primary" size="small" @click="handleOpenStatus($event, item)" v-if="item.status">{{item.default_open_status ? '取消推荐' : '推荐'}}</a-button>
                                    <a-button type="primary" size="small" @click="handleOpen($event, item)">{{item.status ? '关闭' : '开启'}}</a-button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        <hr/>
        <div class="" style="padding-left: 15px;margin: 20px 0">
            <div class="btn" id="save_btn" style="background-color: #0092DC;color: #fff;width: 120px;height: 38px;line-height: 38px;text-align: center;padding: 0;border: none">
                保存
            </div>
            <div class="btn" @click="handleSyncAll()" style="background-color: #0092DC;color: #fff;margin-left: 20px;width: 120px;height: 38px;line-height: 38px;text-align: center;padding: 0;border: none">
                全部同步
            </div>
        </div>
    </div>
</div>

<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__ADMIN_PATH}frame/js/plugins/iCheck/icheck.min.js"></script>
<link href="{__FRAME_PATH}css/plugins/iCheck/custom.css" rel="stylesheet">
{/block}
{block name="script"}
<script>
    require(['vue2', 'antdv', 'axios', 'sortable'], function(Vue2, antdv, axios, Sortable) {
        var app = new Vue({
            el: '#app',
            data() {
                return {
                    systemList: [],
                }
            },
            mounted() {
                $('#app').show();
                axios.get("{:Url('get_system_list')}").then(res => {
                    this.systemList = res.data.data;
                    this.systemList.forEach(item => item.default_open_status = !!item.default_open_status);
                    this.systemList.forEach(item => item.class = "");
                    this.systemList.forEach((item,index) => {
                        if(!item.default_open_status){
                            let item = this.systemList.splice(index, 1);
                            this.systemList.splice(this.systemList.length-1, 0, item[0])
                        }
                    });

                    console.log(this.systemList)
                    this.$nextTick(() => {
                        new Sortable(this.$refs.dragTable, {
                            animation: 150,
                            ghostClass: 'drag-background-class',
                            preventOnFilter: false,
                            draggable: '.draggable',
                            // 结束拖拽
                            onEnd: (/**Event*/evt) => {
                                var itemEl = evt.item;  // dragged HTMLElement
                                // evt.to;    // target list
                                // evt.from;  // previous list
                                // evt.oldIndex;  // element's old index within old parent
                                // evt.newIndex;  // element's new index within new parent
                                // evt.clone // the clone element
                                // evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                                var currRow = this.systemList.splice(evt.oldIndex, 1)[0];
                                this.systemList.splice(evt.newIndex, 0, currRow);
                            },
                        });
                        new Sortable(this.$refs.dragTable, {
                            animation: 150,
                            ghostClass: 'drag-background-class',
                            preventOnFilter: false,
                            draggable: '.filtered',
                            // 结束拖拽
                            onEnd: (/**Event*/evt) => {
                                var itemEl = evt.item;  // dragged HTMLElement
                                // evt.to;    // target list
                                // evt.from;  // previous list
                                // evt.oldIndex;  // element's old index within old parent
                                // evt.newIndex;  // element's new index within new parent
                                // evt.clone // the clone element
                                // evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                                var currRow = this.systemList.splice(evt.oldIndex, 1)[0];
                                this.systemList.splice(evt.newIndex, 0, currRow);
                            },
                        });
                        new Sortable(this.$refs.dragTable2, {
                            animation: 150,
                            ghostClass: 'drag-background-class',
                            preventOnFilter: false,
                            draggable: '.draggable',
                            // 结束拖拽
                            onEnd: (/**Event*/evt) => {
                                var itemEl = evt.item;  // dragged HTMLElement
                                // evt.to;    // target list
                                // evt.from;  // previous list
                                // evt.oldIndex;  // element's old index within old parent
                                // evt.newIndex;  // element's new index within new parent
                                // evt.clone // the clone element
                                // evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                                var currRow = this.systemList.splice(evt.oldIndex, 1)[0];
                                this.systemList.splice(evt.newIndex, 0, currRow);
                            },
                        });
                    })
                });
                var self = this;
                $(function () {
                    $('#save_btn').click(function () {
                        var list = {};
                        console.log(self.systemList)
                        list.system_sort = self.systemList.map(e => e.id).join(',');
                        list.open_ids = self.systemList.filter(e => e.default_open_status).map(e => e.id).join(',');
                        list.fixed_id = self.systemList.filter(e => e.fixed).map(e => e.id).join(',');
                        list.status_id = self.systemList.filter(e => e.status).map(e => e.id).join(',');
                        list.index_id = self.systemList.filter(e => e.is_index).map(e => e.id).join(',');
                        console.log('list', list)
                        $.ajax({
                            url:"{:Url('do_nav_set')}",
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
                })
            },
            methods: {
                // 固定/取消固定
                handleFixed(event, item) {
                    item.fixed = !item.fixed;
                    let insertIndex = this.systemList.filter(e => !!e.fixed).length;
                    this.systemList.forEach((e, i) => {
                        item.id === e.id && this.systemList.splice(i, 1);
                    })
                    item.class = "change-class";
                    setTimeout(()=>{
                        this.$forceUpdate();
                        item.class = ''
                    },500);
                    if (item.fixed) {
                        // 固定之后自动排序至非固定频道前面,并且开启默认关注
                        item.default_open_status = true;
                        this.systemList.splice(insertIndex - 1, 0, item);
                    } else {
                        // 取消固定，移至固定后面一位
                        this.systemList.splice(insertIndex, 0, item);
                    }
                },
                setIndex(event,item){
                    this.systemList.forEach((e, i) => {
                        if(e.is_index){
                            this.systemList[i].is_index = 0;
                        }
                    });
                    item.is_index = 1;
                    /*$.ajax({
                        url:"{:Url('set_index')}",
                        data:{
                            id:item.id
                        },
                        type:'post',
                        dataType:'json',
                        success:function(re){
                            if(re.code == 200){
                                item.is_index = 1;
                                $eb.message('success',re.msg);
                            }else{
                                $eb.message('error',re.msg);
                            }
                        }
                    })*/
                },
                handleOpenStatus(event,item){
                    if(item.default_open_status){
                        item.default_open_status = 0;
                    }else {
                        item.default_open_status = 1;
                    }
                },
                handleOpen(event,item){
                    if(item.status){
                        item.status = 0;
                    }else {
                        item.status = 1;
                    }
                },
                handleSyncOne(event,item){
                    var code = {title:"确认将「"+item.title+"」同步到用户吗？",text:"仅该频道同步到全部用户的频道列表，不影响用户对其他频道的自定义设置",type:'info',confirm:'确定',cancel:"取消"};
                    $eb.$swal('delete',function(res){
                        $.ajax({
                            url:"{:Url('follow_channel_one')}",
                            data:{
                                id:item.id
                            },
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
                    },code);
                },
                handleSyncAll(event,item){
                    var code = {title:"确认全部同步到用户吗",text:"全部同步后将覆盖所有用户自定义的频道设置，可能影响用户正常使用习惯",type:'info',confirm:'确定',cancel:"取消"};
                    $eb.$swal('delete',function(){
                        $.ajax({
                            url:"{:Url('follow_channel_all')}",
                            data:{},
                            type:'post',
                            dataType:'json',
                            success:function(re){
                                if(re.code == 200){
                                    //$eb.message('success',re.msg);
                                    $('#save_btn').click();
                                }else{
                                    $eb.message('error',re.msg);
                                }
                            }
                        })
                    },code);
                }
            }
        });
    })
</script>
{/block}