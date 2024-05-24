{extend name="public/modal-frame"}
{block name="head_top"}
<link href="{__ADMIN_PATH}module/wechat/news/css/style.css" type="text/css" rel="stylesheet">
<link href="{__FRAME_PATH}css/plugins/chosen/chosen.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{__ADMIN_PATH}plug/ueditor/ueditor.all.js"></script>
<script src="{__ADMIN_PATH}frame/js/ajaxfileupload.js"></script>
<script src="{__ADMIN_PATH}plug/validate/jquery.validate.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
    .upload-img-box {
        border: 1px solid #E5E6E7;
        padding: 5px 10px;
        margin-right: 5px;
        margin-top: 5px;
        border-radius: 3px;
        position: relative;
        width: 102px;
        height: 92px;
    }

    .upload-img-box-img {
        width: 80px;
        height: 80px;
    }

    .delete-btn {
        display: none;
        position: absolute;
        top: 5px;
        right: 10px;
        width: 80px;
        height: 80px;
        cursor: pointer;
        font-size: 20px;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .delete-btn img {
        width: 30px;
        height: 30px;
    }
     .input_label .checkbox_label{
         padding-top: 0;
         line-height: 30px;
         margin-right: 30px;
     }
    .input_label .checkbox_label input{
        margin-top: 7px;
        width: 16px;
        height: 16px;
        margin-left: -20px;
        position: absolute;
    }
    .image_list{
        margin-left: 135px;

    }
    .image_list img{
        width: 100px;
        height: 100px;
        margin-right: 10px;
        margin-top: 20px;
        border: 1px solid #f2f2f2;
        padding: 2px;
    }
</style>
{/block}
{block name="content"}
<div class="panel">
    <div class="panel-body">
        <form class="form-horizontal" id="signupForm">
            <input type="hidden" name="id" value="{$channel_post['id']}">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><span style="color: red;margin-right: 5px;">*</span>推送频道：</span>
                        <input class="layui-input" value="{$channel_post.channel_title}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><span style="color: red;margin-right: 5px;">*</span>推送时长：</span>
                        <select class="layui-select layui-input" name="post_long" id="post_long" value="{$channel_post.post_long}">
                            <option value="">请选择时长</option>
                            <option value="1">无限制</option>
                            <option value="2">24小时</option>
                            <option value="3">3天</option>
                            <option value="4">7天</option>
                            <option value="5">30天</option>
                            <option value="6">180天</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">排序权重：</span>
                        <input maxlength="3" placeholder="请输入内容（0-100内数字）" name="sort_num" class="layui-input"
                               id="false_view" value="{$channel_post.sort_num}" type="number">
                    </div>
                </div>
            </div>
            <div id="vue_block">
                {if condition="$un_show_image_select neq 1"}
                <div v-if="is_post==1||is_news==1" class="form-group input_label" @click="changeRadio">
                    <label style="    width: 120px;    margin-left: 15px;    padding: 5px 15px;    font-weight: 400;">图片形式：</label>
                    <label class="checkbox-inline checkbox_label">
                        <input type="radio" v-model="now_num" name="image_show_type" value="1"> 单图
                    </label>
                    <label class="checkbox-inline checkbox_label">
                        <input type="radio" v-model="now_num" name="image_show_type" value="2"> 双图
                    </label>
                    <label class="checkbox-inline checkbox_label">
                        <input type="radio" v-model="now_num" name="image_show_type" value="3"> 三图
                    </label>
                    <label class="checkbox-inline checkbox_label">
                        <input type="radio" v-model="now_num" name="image_show_type" value="4"> 无图
                    </label>
                    <div class="image_list">
                        <img v-for="(one_image,index) in image_list['image_456_456']" :key="index" :src="one_image"/>
                    </div>
                </div>
                {/if}
                <div class="form-group">
                    <label style="width: 120px;margin-left: 15px;padding: 5px 15px;font-weight: 400;">样式预览：</label>
                    <div v-if="is_post==1" class="view_block">
                        <div class="view_block_one">
                            <div class="v_top_user">
                                <img :src="post_info['user']['avatar']"/>
                                <div class="v_user_info">
                                    <div class="v_nickname">{{post_info['user']['nickname']}}</div>
                                    <span>{{post_info['post_time']}}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <div class="v_post_title">{{post_info['title']}}</div>
                                <div class="v_content">{{post_info['summary']}}</div>
                                <div v-show="now_num>0&&now_num<4" :class="[{'one_image':now_num==1},{'two_image':now_num==2},{'three_image':now_num==3}]">
                                    <img v-for="(one_image,index) in view_image_list" :key="index" :src="one_image"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="is_video==1" class="view_block">
                        <div class="view_block_one">
                            <div class="v_top_user">
                                <img :src="post_info['user']['avatar']"/>
                                <div class="v_user_info">
                                    <div class="v_nickname">{{post_info['user']['nickname']}}</div>
                                    <span>{{post_info['post_time']}}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <div class="v_post_title">{{post_info['title']}}</div>
                                <div class="v_content">{{post_info['summary']}}</div>
                                <div class="one_image">
                                    <img src="{__ADMIN_PATH}images/default_video.png"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="is_news==1" class="view_block">
                        <div v-show="now_num==1" class="view_block_one v_news">
                            <div class="v_one_image_left">
                                <div class="v_post_title_news v_news_one">{{post_info['title']}}</div>
                                <div class="v_top_user">
                                    <span class="v_nickname">{{post_info['user']['nickname']}}</span>
                                    <span>{{post_info['post_time']}}</span>
                                </div>
                            </div>
                            <div class="v_one_image">
                                <img v-for="(one_image,index) in view_image_list" :key="index" :src="one_image"/>
                            </div>
                        </div>
                        <div v-show="now_num>1" class="view_block_one v_news">
                            <div>
                                <div class="v_post_title_news">{{post_info['title']}}</div>
                                <div v-show="now_num>0&&now_num<4" :class="[{'one_image':now_num==1},{'two_image':now_num==2},{'three_image':now_num==3}]">
                                    <img v-for="(one_image,index) in view_image_list" :key="index" :src="one_image"/>
                                </div>
                            </div>
                            <div class="v_top_user">
                                <span class="v_nickname">{{post_info['user']['nickname']}}</span>
                                <span>{{post_info['post_time']}}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="is_weibo==1" class="view_block">
                        <div class="view_block_one">
                            <div class="v_top_user">
                                <img :src="post_info['user']['avatar']"/>
                                <div class="v_user_info">
                                    <div class="v_nickname">{{post_info['user']['nickname']}}</div>
                                    <span>{{post_info['post_time']}}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <div class="v_post_title">{{post_info['content']}}</div>
                                <div v-if="weibo_image_num==1" class="v_weibo_img_one">
                                    <img v-for="(one_image,index) in view_image_list" :key="index" :src="one_image"/>
                                </div>
                                <div v-if="weibo_image_num>1" class="v_weibo_img">
                                    <img v-for="(one_image,index) in view_image_list" :key="index" :src="one_image"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <style scoped>
                .view_block{
                    width: 400px;
                    padding: 15px;
                    background-color: #f8f8f8;
                    min-height: 150px;
                    margin-left: 132px;
                    margin-top: -30px;
                }
                .clearfix{
                    clear: both;
                }
                .view_block_one{
                    color: #848484;
                }
                .view_block_one .v_top_user>div{
                    float: left;
                }
                .view_block_one .v_top_user img{
                    display: block;
                    float: left;
                    width: 60px;
                    height: 60px;
                    border-radius: 100%;
                }
                .view_block_one .v_user_info{
                    margin-left: 10px;
                    line-height: 30px;
                }
                .v_nickname{
                    color: #4e4e4e;
                }
                .v_post_title{
                    line-height: 40px;
                    font-size: 15px;
                    font-weight: 500;
                    width: 100%;
                    overflow: hidden;
                    text-overflow:ellipsis;
                    white-space:nowrap;
                    color: #333;
                }
                .v_content{
                    line-height: 20px;
                    color: #848484;
                }
                .v_weibo_img,.v_weibo_img_one{
                    display: flex;
                    justify-content: flex-start;
                    flex-wrap: wrap;
                }
                .v_weibo_img img{
                    width: 100px;
                    height: 100px;
                    margin: 10px 10px;
                }

                .v_weibo_img_one img{
                    width: 365px;
                    max-height: 183px;
                    margin: 10px 0;
                }

                .one_image img{
                    width: 365px;
                    max-height: 183px;
                    margin: 10px 0;
                }
                .two_image,.three_image{
                    display: flex;
                    justify-content: space-between;
                    margin: 10px 0;
                }
                .two_image img{
                    width: 180px;
                    height: 180px;
                }
                .three_image img{
                    width: 115px;
                    height: 115px;
                }
                .v_news .v_one_image img{
                    width: 115px;
                    height: 115px;
                    float: left;
                    margin-left: 10px;
                }
                .v_news .two_image,.v_news .three_image{
                    display: flex;
                    justify-content: flex-start;
                    margin: 10px 0 20px;
                }
                .v_news .two_image img,.v_news .three_image img{
                    width: 116px;
                    height: 90px;
                    margin-right: 10px;
                }
                .v_news .v_post_title_news{
                    line-height: 25px;
                    font-size: 15px;
                    font-weight: 500;
                    overflow: hidden;
                    color: #333;
                    width: 300px;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    margin: 5px 0;
                }
                .v_news .v_one_image_left{
                    width: 245px;
                    float: left;
                }
                .v_news .v_one_image_left .v_news_one{
                    height: 75px;
                    -webkit-line-clamp: 3;
                    margin-bottom: 20px;
                    width: 245px;
                }
                .v_news .v_nickname{
                    margin-right: 20px;
                }

            </style>
        </form>
        <div class="form-actions" style="text-align: center">
            <button type="button" id="save_btn" class="btn btn-primary" style="padding: 10px 50px;margin: 20px 15px 44px;background-color: #0ca6f2;width: 95% ">提交</button>
        </div>
    </div>
</div>
{/block}
{block name="script"}

<script>
    $(function () {
        $('#post_long').val("{$channel_post['post_long']}");
        $('#un_do').on('click',function () {
            parent.layer.close(parent.layer.getFrameIndex(window.name));
        });
        $('#save_btn').on('click', function () {
            var data = $('#signupForm').serialize();
            $.ajax({
                url: "{:Url('do_edit_channel_post')}",
                data: data,
                type: 'post',
                dataType: 'json',
                success: function (re) {
                    if (re.code == 200) {
                        $eb.message('success', re.msg);
                        setTimeout(function (e) {
                            parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        }, 600)
                    } else {
                        $eb.message('error', re.msg);
                    }
                }
            })
        });
    })

    var post_id="{$post_detail['id']}";
    require(['vue','axios'],function(Vue,axios) {
        new Vue({
            el: "#vue_block",
            data: {
                is_post:0,
                is_weibo:0,
                is_news:0,
                is_video:0,
                weibo_image_num:0,
                now_num:"{$channel_post['image_show_type']}",
                post_info:{'user':{'avatar':'','account':''},'post_time':'','title':'','summary':'','content':''},
                image_list: [],
                view_image_list:[],
            },
            watch: {
            },
            methods: {
                updateShowImage:function(){
                    if(this.is_video==1) {
                        return true;
                    }
                    if(this.is_weibo==1){
                        if(this.weibo_image_num==1){
                            this.view_image_list=this.image_list.image_400_200;
                        }else{
                            this.view_image_list=this.image_list.image_456_456;
                        }
                        return true;
                    }
                    if(this.now_num==4){
                        this.view_image_list=[];
                        return true;
                    }
                    if(this.now_num<4){
                        var num=this.now_num;
                        if((this.is_news==1&&num==1)||(this.is_post==1&&num!=1)){
                            var images=this.image_list.image_456_456;
                        }else{
                            var images=this.image_list.image_400_200;
                        }
                        var view_image_list=[];
                        if(images.length<num){
                            num=images.length
                        }
                        for(var i=0;i<num;i++){
                            view_image_list.push(images[i]);
                        }
                        this.view_image_list=view_image_list;
                    }
                    return true;
                },
                setData:function(){
                    var that=this;
                    axios.get("{:Url('admin/channel.post/getPostInfo')}?post_id="+post_id).then((res)=>{
                        console.log(res.data.data);
                    var data=res.data.data;
                    that.post_info=data.post_detail;
                    that.is_post=data.is_post;
                    that.is_weibo=data.is_weibo;
                    that.is_news=data.is_news;
                    that.is_video=data.is_video;
                    that.weibo_image_num=data.weibo_image_num;

                    that.image_list=data.post_detail.images;
                    that.updateShowImage();
                });
                },
                changeRadio:function () {
                    this.updateShowImage();
                }
            },
            mounted:function () {
                this.setData();
            }
        })
    });
</script>
{/block}