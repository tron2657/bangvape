{extend name="public/container"}
{block name='head_top'}
<style>
    .layui-form-item .special-label i{display: inline-block;width: 18px;height: 18px;font-size: 18px;color: #fff;}
    .layui-form-item .label-box p{line-height: inherit;}
    .m-t-5{margin-top:5px;}
    #app .layui-barrage-box{margin-bottom: 10px;margin-top: 10px;margin-left: 10px;border: 1px solid #0092DC;border-radius: 5px;cursor: pointer;position: relative;}
    #app .layui-barrage-box.border-color{border-color: #0bb20c;}
    #app .layui-barrage-box .del-text{position: absolute;top: 0;left: 0;background-color: rgba(0,0,0,0.5);color: #ffffff;width: 92%;text-align: center;}
    #app .layui-barrage-box p{padding:5px 5px; }
    #app .layui-empty-text{text-align: center;font-size: 18px;}
    #app .layui-empty-text p{padding: 10px 10px;}
</style>
{/block}
{block name="content"}
<div class="layui-fluid" style="background: #fff">
    <div class="layui-row layui-col-space15" id="app">
        <div class="layui-col-md12">
            <div class="layui-card" v-cloak="">
                <div class="layui-card-header">添加会员</div>
                <div class="layui-card-body" style="padding: 10px 150px;">
                    <form action="" class="layui-form">
                        <div class="layui-form-item m-t-5" v-cloak="">
                        <div class="layui-form-item submit">
                            <label class="layui-form-label">会员名</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" style="width: 50%" v-model="formData.title" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                            </div>
                        </div>
                            <div class="layui-form-item submit">
                            <label class="layui-form-label">有效时间</label>
                            <div class="layui-input-block">
                                <input type="radio" name="vip_day" value="30" title="月" v-model="formData.vip_day" lay-filter="vip_day"  >
                                <input type="radio" name="vip_day" value="90" title="季" v-model="formData.vip_day" lay-filter="vip_day">
                                <input type="radio" name="vip_day" value="365" title="年" v-model="formData.vip_day" lay-filter="vip_day" >
                                <input type="radio" name="vip_day" value="-1" title="永久" v-model="formData.vip_day" lay-filter="vip_day">
                            </div>
                        </div>
                            <div class="layui-form-item submit">
                            <label class="layui-form-label">会员原价</label>
                            <div class="layui-input-block">
                                <input type="number" name="original_price" style="width: 50%" v-model="formData.original_price" autocomplete="off"  class="layui-input">
                            </div>
                        </div>
                            <div class="layui-form-item submit">
                            <label class="layui-form-label">会员优惠后价格</label>
                            <div class="layui-input-block">
                                <input type="number" name="price" style="width: 50%" v-model="formData.price" autocomplete="off"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item submit">
                            <label class="layui-form-label">排    序</label>
                            <div class="layui-input-block">
       
                                <input type="number" name="sort" style="width: 50%" v-model="formData.sort" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item submit">
                            <label class="layui-form-label">关联商品</label>
                            <div class="layui-input-block">
                            <div @click="open_product_select"   class="img_content" v-show="formData.product_id>0">
                                <img   class="upload-img-box-img" alt="" :src="formData.product_cover">
                                <div id="delete-btn" @click.stop="formData.product_id=0" v-if="selectImg.delbtn.show"  class="delete-btn"></div>
                            </div>
                            <div @click="open_product_select"  v-show="formData.product_id==0" class="img_content">
                                <img    class="upload-img-box-img" alt="" >
                                
                            </div>
                            <input type="hidden" v-model="formData.product_id">
                               
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label">是否免费</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_free" value="1" title="是" v-model="formData.is_free" lay-filter="is_free" >
                                <input type="radio" name="is_free" value="0" title="否" v-model="formData.is_free" lay-filter="is_free">
                            </div>
                        </div>
                            <div class="layui-form-item submit" v-show="free || formData.free_day>0">
                                <label class="layui-form-label">免费使用时间</label>
                                <div class="layui-input-block">
                                    <input type="number" name="free_day" style="width: 50%" v-model="formData.free_day" autocomplete="off"  class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                            <label class="layui-form-label">是否永久</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_permanent" value="1" title="是" v-model="formData.is_permanent" lay-filter="is_permanent" >
                                <input type="radio" name="is_permanent" value="0" title="否" v-model="formData.is_permanent" lay-filter="is_permanent">
                            </div>
                        </div>
                            <div class="layui-form-item">
                            <label class="layui-form-label">是否发布</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_publish" value="1" title="是" v-model="formData.is_publish" lay-filter="is_publish">
                                <input type="radio" name="is_publish" value="0" title="否" v-model="formData.is_publish" lay-filter="is_publish">
                            </div>
                        </div>
                        <div class="layui-form-item submit">
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-normal" type="button" @click="save">{{id ? '立即修改':'立即提交'}}</button>
                                <button class="layui-btn layui-btn-primary clone" type="button" @click="clone_form">清空</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="{__ADMIN_PATH}js/request.js"></script>
<script type="text/javascript" src="{__ADMIN_PATH}js/layuiList.js"></script>
<script type="text/javascript" src="{__MODULE_PATH}widget/OssUpload.js"></script>
{/block}
{block name='script'}
<script>
 
 function createFrame(title, src, opt) {
  opt === undefined && (opt = {});
  return layer.open({
    type: 2,
    title: title,
    area: [(opt.w || 700) + 'px', (opt.h || 650) + 'px'],
    fixed: false, //不固定
    maxmin: true,
    moveOut: false,//true  可以拖出窗外  false 只能在窗内拖
    anim: 5,//出场动画 isOutAnim bool 关闭动画
    offset: 'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
    shade: 0,//遮罩
    resize: true,//是否允许拉伸
    content: src,//内容
    move: '.layui-layer-title'
  });
}

 
    var id={$id};
    var membership=<?=isset($membership) ? $membership : []?>;
    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
                formData:{
                    title:membership.title || '',
                    vip_day:membership.vip_day || 30,
                    free_day:membership.free_day || 0,
                    original_price:membership.original_price || 0,
                    price:membership.price || 0,
                    sort:membership.sorts || 0,
                    is_permanent:membership.is_permanent || 0,
                    is_publish:membership.is_publish || 0,
                    is_free:membership.is_free || 0,
                    product_id:membership.product_id,
                    product_cover:membership.product_cover,
                 
                },
                selectImg:{
                     
                    delbtn:{
                        show:true
                    }
                },
                free:false
            },
            watch: {
                'formData.vip_day': function (v) {
                    this.$nextTick(function () {
                        layList.form.render();
                    });
                },
                // 'formData.product_id':function(){
                //     if(this.formData.product_id && this.formData.product_id>0)
                //     {
                //         this.selectImg.delbtn.show=true;
                    
                //     }
                //     else{
                //         this.selectImg.delbtn.show=false;
                       
                    
                //     }
                // }
            },
           
            methods:{
                clone_form: function () {
                    var that = this;
                    if (parseInt(id) == 0) {
                        parent.layer.closeAll();
                    }
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

                    parent.layer.close(index); //再执行关闭
                },
                init:function(){
                    var that=this;
                    window.changeField=function(element,data){
          
                      console.log(data);
                        that.formData.product_cover=data.image;
                        that.formData.product_id=data.id;
                        that.selectImg.delbtn.show=true;
                        // $img.attr("src",data.image);
                        // $btnDel.css("display","block");
                        // $("#product_id").val(data.id);
                    }

                },
                open_product_select:function(){
                  
                    createFrame('选择商品', '{:Url('widget.stroe_product/index')}?fodder=image&trial_product=product-img');
                },
                save:function () {
                    var that=this;
                    if(!that.formData.title) return layList.msg('请输入会员标题');
                    if(that.formData.vip_day<0 && !that.formData.is_permanent) return layList.msg('会员有效时间有误');
                    if(that.formData.free_day<=0 && that.formData.is_free) return layList.msg('免费会员有效时间有误');
                    if(Number(that.formData.original_price)<0) return layList.msg('请输入会员原价');
                    if(Number(that.formData.price) <0) return layList.msg('请输入会员优惠后价格');
                    layList.loadFFF();
                    layList.basePost(layList.U({a:'save_sytem_vip',q:{id:id}}),that.formData,function (res) {
                        layList.loadClear();
                        if(parseInt(id) == 0) {
                            layList.layer.confirm('添加成功,您要继续添加会员设置吗?', {
                                btn: ['继续添加', '立即提交'] //按钮
                            }, function () {
                                window.location.reload();
                            }, function () {
                                parent.layer.closeAll();
                            });
                        }else{
                            layList.msg('修改成功',function () {
                                parent.layer.closeAll();
                            })
                        }
                    },function (res) {
                        layList.msg(res.msg);
                        layList.loadClear();
                    });
                }
            },
            mounted:function () {
                var that=this;
                this.init();
                this.$nextTick(function () {
                    layList.form.render();

                });
                layList.form.on('radio(is_permanent)',function (data) {
                    that.formData.is_permanent=data.value;
                });
                layList.form.on('radio(vip_day)',function (data) {
                    that.formData.vip_day=data.value;
                    that.formData.is_free=0;
                    that.free=false;
                });
                layList.form.on('radio(is_publish)',function (data) {
                    that.formData.is_publish=data.value;
                });
                layList.form.on('radio(is_free)',function (data) {
                    that.formData.is_free=data.value;
                    if(data.value==1) {
                        that.free=true;
                        that.formData.vip_day= 0;
                    }else{
                        that.free=false;
                        that.formData.vip_day=30;
                    }
                    layList.form.render();
                });
            }
        })
    })
</script>

<style>
 .img_content {
    position: relative;
    height: 80px;
    width: 80px;
    display: inline-block;
    text-align: center;
    border: 1px dashed #c0ccda;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    font-family: Ionicons;
    box-shadow: 2px 2px 5px rgba(0,0,0,.1);
    margin-right: 4px;
    box-sizing: border-box;
    background-image: url(/public/system/images/image.png);
    background-size: 20px 20px;
    background-position: 50% 50%;
    background-repeat: no-repeat;
}
.delete-btn {
    position: absolute;
    top: 0px;
    right: 0px;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 20px;
    align-items: center;
    justify-content: center;
    background-image: url(/public/system/images/delete.png);
    background-size: 20px 20px;
    background-position: 50% 50%;
    background-repeat: no-repeat;
}
</style>
{/block}