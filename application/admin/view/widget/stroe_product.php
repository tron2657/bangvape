{extend name="public/container"} {block name="content"}
<style>
  body {
    background-color: #fff !important;
  }
</style>
<div class="layui-fluid" style="background: #fff; margin-top: -10px">
  <div class="layui-row layui-col-space15" id="app">
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-body">
          <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">
              <div class="layui-inline">
                <label class="layui-form-label">所有分类</label>
                <div class="layui-input-block">
                  <select name="cate_id">
                    <option value=" ">全部</option>
                    {volist name='typearray' id='vo'}
                    <option value="{$vo.id}">{$vo.html}{$vo.cate_name}</option>
                    {/volist}
                  </select>
                </div>
              </div>
              <div class="layui-inline">
                <label class="layui-form-label">产品名称</label>
                <div class="layui-input-block">
                  <input type="text" name="store_name" class="layui-input" placeholder="请输入产品名称,关键字,编号" />
                </div>
              </div>

              <div class="layui-inline">
                <div class="layui-input-inline">
                  <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                    <i class="layui-icon layui-icon-search"></i>搜索
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--产品列表-->
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-body">
          <div class="layui-btn-container"></div>
          <table class="layui-hide" id="List" lay-filter="List"></table>
          <!--图片-->
          <script type="text/html" id="image">
            <img style="cursor: pointer" lay-event="open_image" src="{{d.image}}" />
          </script>


          <!--产品名称-->
          <script type="text/html" id="store_name">
            <h4>{{d.store_name}}</h4>
            <p>价格:<font color="red">{{d.price}}</font>
            </p>
            {{# if(d.cate_name!=''){ }}
              <p>分类:{{d.cate_name}}</p>
              {{# } }}
                <!--<p>访客量:{{d.visitor}}</p>-->
                <p>浏览量:{{d.browse}}</p>
          </script>

          <!--单选-->
          <script type="text/html" id="select">
            <!-- <input type="radio" name="select" lay-event='select'/> -->

          <button class="layui-btn layui-btn-xs layui-btn-normal" lay-event='select'>
            选择
          </button>
          </script>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>

<script>
  var parentinputname = '{$Request.param.fodder}'; //父级input name
  var trial_product = '{$Request.param.trial_product}';

  var type = 1;
  //实例化form
  layList.form.render();
  //加载列表
  var table = layList.tableList(
    "List",
    "{:Url('product_ist',['type'=>$type])}",
    function() {
      var join = new Array();
      join = [
        // {
        //   field: "id",
        //   title: "ID",
        //   sort: true,
        //   event: "id",
        //   width: "7%"
        // },
        {
          field: "image",
          title: "图片",
          templet: "#image",
          width: "10%"
        },
        {
          field: "store_name",
          title: "产品名称",
          templet: "#store_name",
       
        },
        // {
        //   field: "ficti",
        //   title: "虚拟销量",
        //   edit: "ficti",
        //   width: "10%"
        // },
        {
          field: "stock",
          title: "库存",
          edit: "stock",
        
        },
        {
          field: "id",
          title: "操作",
          sort: false,
          event: "id",
      
          templet: "#select",
        },
      ];
      return join;
    }
  );

  //点击事件绑定
  layList.tool(function(event, data, obj) {
 
    switch (event) {
      case 'select':

        console.log(data);
        if (parent.$f) {
          parent.$f.changeField(trial_product, data);
          parent.$f.closeModal();
        }
        else{
          parent.changeField(trial_product, data);
          var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);

        }

        break;
    }
  })

  //下拉框
  $(document).click(function(e) {
    $(".layui-nav-child").hide();
  });


  //查询
  layList.search("search", function(where) {
    layList.reload(where, true);
  });


  layui.use(['layer'], function() {

    var layer = layui.layer;
    //点击选择图片
    $('img').on('click', function(e) {
      var parentNode = $(this).parent();
      parentNode.toggleClass('on');

    });




  });
  //非组件修改样式
  if (!parent.$f) {
    $('.main-top').hide();
    $('.main').css('margin', '0px');
    $('.foot-tool').css('bottom', '20px');
  }
</script>
{/block}