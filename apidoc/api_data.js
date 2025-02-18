define({ "api": [
  {
    "type": "get",
    "url": "/ebapi/card_api/card_list",
    "title": "礼品卡列表",
    "name": "card_list",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>1 Page.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>20 Limit.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "cash_price",
            "description": "<p>价格.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "vip_price",
            "description": "<p>会员价格.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "thrift",
            "description": "<p>预计节省.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/card_status_list",
    "title": "我的卡-兑换记录",
    "name": "card_status_list",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "card_id",
            "description": "<p>商品ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/confirm_order",
    "title": "购买流程2.确认订单",
    "name": "confirm_order",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "cartId",
            "description": "<p>cartId</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/create_order_new",
    "title": "购买流程4.创建订单",
    "name": "create_order_new",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "midkey",
            "defaultValue": "OSA2SFsRCH2w+s1TvIUSnNkTCFxfhucbVPFvOhLb962Gu2AT0Wa7WVwPk6wgaKDS",
            "description": "<p>midkey.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "mark",
            "description": "<p>mark</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/details",
    "title": "礼品卡详情",
    "name": "details",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Id.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "number",
            "description": "<p>Number.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "number",
            "description": "<p>数量.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "amount",
            "description": "<p>金额.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/exchange_confirm_order",
    "title": "兑换流程2.确认订单",
    "name": "exchange_confirm_order",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "cartId",
            "description": "<p>cartId</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/exchange_create_order_new",
    "title": "兑换流程3.创建订单",
    "name": "exchange_create_order_new",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "midkey",
            "description": "<p>midkey</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "addressId",
            "defaultValue": "1",
            "description": "<p>addressId</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "mark",
            "description": "<p>mark</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/exchange_now",
    "title": "兑换流程1.立即兑换",
    "name": "exchange_now",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "card_id",
            "description": "<p>card_id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "uniqueId",
            "defaultValue": "c657ae41",
            "description": "<p>规格id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/get_midkey",
    "title": "购买流程3.获取key(正式环境无需)",
    "name": "get_midkey",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key",
            "defaultValue": "OSA2SFsRCH2w+s1TvIUSnNkTCFxfhucbVPFvOhLb962Gu2AT0Wa7WVwPk6wgaKDS",
            "description": "<p>key.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/get_page_code",
    "title": "获取小程序二维码",
    "name": "get_page_code",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "url",
            "defaultValue": "/packageA/activity/detail?id=4",
            "description": "<p>url.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/my_card_list",
    "title": "我的卡",
    "name": "my_card_list",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/my_card_list_more",
    "title": "我的卡-查看更多",
    "name": "my_card_list_more",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "product_id",
            "description": "<p>商品ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/notify",
    "title": "支付回调",
    "name": "notify",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "uni",
            "description": "<p>uni.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "paytype",
            "defaultValue": "routine",
            "description": "<p>paytype.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bill_type",
            "defaultValue": "pay_product",
            "description": "<p>bill_type.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/now_buy",
    "title": "购买流程1.点击购买",
    "name": "now_buy",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "productId",
            "description": "<p>productId.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "cartNum",
            "description": "<p>cartNum.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/pay_order_new",
    "title": "购买流程5.支付订单",
    "name": "pay_order_new",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "uni",
            "description": "<p>uni.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "paytype",
            "defaultValue": "routine",
            "description": "<p>paytype.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bill_type",
            "defaultValue": "pay_product",
            "description": "<p>bill_type.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/payment_success_test",
    "title": "购买流程6.支付成功测试（正式环境无需）",
    "name": "payment_success_test",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderId",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "paytype",
            "defaultValue": "routine",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "formId",
            "defaultValue": "pay_product",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/recieve_card",
    "title": "接收礼品卡1.接收礼品卡",
    "name": "recieve_card",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "recieve_code",
            "description": "<p>礼品卡code</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/recieve_card_confirm",
    "title": "接收礼品卡2.确认接收赠送",
    "name": "recieve_card_confirm",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "recieve_code",
            "description": "<p>礼品卡code</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/send_history",
    "title": "收送记录",
    "name": "send_history",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "status",
            "description": "<p>状态（send recieve）</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "get",
    "url": "/ebapi/card_api/use_card_tobalance",
    "title": "我的卡-使用1-转入余额",
    "name": "use_card_tobalance",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "id",
            "description": "<p>礼品卡id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/use_send_cancel",
    "title": "我的卡-撤销赠送",
    "name": "use_send_cancel",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "id",
            "description": "<p>礼品卡id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/use_send_card",
    "title": "我的卡-使用2-赠送",
    "name": "use_send_card",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "id",
            "description": "<p>礼品卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "strng",
            "optional": false,
            "field": "message",
            "description": "<p>祝福语</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/card_api/valid_id_card",
    "title": "兑换流程1.验证身份证",
    "name": "valid_id_card",
    "group": "Card",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "idcard",
            "description": "<p>idcard</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CardApi.php",
    "groupTitle": "Card"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/getOrderStatusNum",
    "title": "订单状态数据",
    "name": "getOrderStatusNum",
    "group": "UserApi",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "jygwgYXhr/iyNmrSDq7hOXVv3cMlwBwzOyDEh0MJJlolQNiYmVw7Mc2/BQGKcadlqzYGXc8klg+wZWKQ6hr+Mw==",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "UserApi"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/cate/1.html",
    "title": "认证详情",
    "group": "certification",
    "name": "cate",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>认证类别id</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.satisfy_status",
            "description": "<p>是否可申请 false 不能申请；true 可以申请</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.cateconditions",
            "description": "<p>申请条件</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.cateconditions.satisfy",
            "description": "<p>申请条件满足情况</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.cateprivileges",
            "description": "<p>认证特权</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"msg\":\"ok\",\"data\":{\"name\":\"\\u6d4b\\u8bd5\\u4e2a\\u4eba\\u8ba4\\u8bc1\",\"desc\":\"\\u6d4b\\u8bd5\\u4e2a\\u4eba\\u8ba4\\u8bc1\",\"icon\":\"http:\\/\\/shop.com\\/public\\/uploads\\/attach\\/2019\\/03\\/28\\/5c9ccca1c78cd.gif\",\"id\":1,\"status\":1,\"satisfy_status\":false,\"cateconditions\":[{\"id\":1,\"cate_id\":1,\"condition_id\":6,\"condition_value\":11,\"create_time\":\"2019-11-26 22:22:36\",\"update_time\":\"2019-11-26 22:23:17\",\"condition\":{\"id\":6,\"name\":\"rztj6\",\"desc\":\"\\u8fd130\\u5929\\u53d1\\u5e16\\u6570\\u2265\",\"sort\":0,\"status\":1,\"create_time\":\"1970-01-01 08:00:00\",\"update_time\":\"1970-01-01 08:00:00\",\"satisfy\":{\"status\":false,\"value\":0}}},{\"id\":2,\"cate_id\":1,\"condition_id\":2,\"condition_value\":0,\"create_time\":\"2019-11-26 22:23:17\",\"update_time\":\"2019-11-26 22:23:17\",\"condition\":{\"id\":2,\"name\":\"rztj2\",\"desc\":\"\\u7ed1\\u5b9a\\u624b\\u673a\",\"sort\":0,\"status\":1,\"create_time\":\"1970-01-01 08:00:00\",\"update_time\":\"1970-01-01 08:00:00\",\"satisfy\":{\"status\":true,\"value\":\"18747755950\"}}}],\"cateprivileges\":[{\"id\":1,\"cate_id\":1,\"privilege_id\":4,\"create_time\":\"2019-11-26 21:51:28\",\"update_time\":\"1970-01-01 08:00:00\",\"built_in\":0,\"privilege\":[{\"id\":4,\"name\":\"\\u4e13\\u5c5e\\u5ba2\\u670d\",\"desc\":\"\\u4e13\\u4eba\\u5bf9\\u63a5\\uff0c\\u4f18\\u5148\\u89e3\\u51b3\",\"icon\":\"\",\"sort\":0,\"status\":1,\"create_time\":\"1970-01-01 08:00:00\",\"update_time\":\"1970-01-01 08:00:00\",\"built_in\":0}]}]},\"count\":0}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/cate_datum_list/cate_id/1.html",
    "title": "类别资料项列表",
    "group": "certification",
    "name": "cate_datum_list",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "cate_id",
            "description": "<p>类别id</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page_num",
            "description": "<p>每页数量</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\"cate_id\":1,\"page\":\"1\",\"page_num\":\"100\"}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"msg\":\"ok\",\"data\":[{\"field\":\"zsxm\",\"name\":\"\\u771f\\u5b9e\\u59d3\\u540d\",\"input_tips\":\"\",\"form_type\":\"text\",\"setting\":\"\"},{\"field\":\"sfzh\",\"name\":\"\\u8eab\\u4efd\\u8bc1\\u53f7\",\"input_tips\":\"\",\"form_type\":\"text\",\"setting\":\"\"},{\"field\":\"scsfzzm\",\"name\":\"\\u4e0a\\u4f20\\u8eab\\u4efd\\u8bc1\\u6b63\\u9762\\u56fe\\u7247\",\"input_tips\":\"\\u6ce8\\u610f\\u53cd\\u5149\\uff0c\\u4fdd\\u8bc1\\u8eab\\u4efd\\u8bc1\\u5185\\u5bb9\\u6e05\\u6670\\u53ef\\u89c1\",\"form_type\":\"file\",\"setting\":\"\"},{\"field\":\"scsfzfm\",\"name\":\"\\u4e0a\\u4f20\\u8eab\\u4efd\\u8bc1\\u53cd\\u9762\\u56fe\\u7247\",\"input_tips\":\"\\u6ce8\\u610f\\u53cd\\u5149\\uff0c\\u4fdd\\u8bc1\\u8eab\\u4efd\\u8bc1\\u5185\\u5bb9\\u6e05\\u6670\\u53ef\\u89c1\",\"form_type\":\"file\",\"setting\":\"\"},{\"field\":\"xl\",\"name\":\"\\u5b66\\u5386\",\"input_tips\":\"\",\"form_type\":\"select\",\"setting\":\"\\u6587\\u76f2\\n\\u5c0f\\u5b66\\n\\u521d\\u4e2d\\n\\u9ad8\\u4e2d(\\u804c\\u9ad8\\u3001\\u4e2d\\u4e13)\\n\\u5927\\u4e13(\\u9ad8\\u804c)\\n\\u672c\\u79d1\\n\\u7855\\u58eb\\u7814\\u7a76\\u751f\\n\\u535a\\u58eb\\u7814\\u7a76\\u751f\\n\\u4fdd\\u5bc6\"}],\"count\":0}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/cate_list.html",
    "title": "类别列表",
    "group": "certification",
    "name": "cate_list",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page_num",
            "description": "<p>每页数量</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\"page\":\"1\",\"page_num\":\"20\"}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.status",
            "description": "<p>认证状态 snull 去认证；0 审核中；1 已认证 -1 已驳回；</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.entity_id",
            "description": "<p>认证id 已经认证过的有此id；</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"msg\":\"ok\",\"data\":[{\"name\":\"\\u6d4b\\u8bd5\\u4e2a\\u4eba\\u8ba4\\u8bc1\",\"desc\":\"\\u6d4b\\u8bd5\\u4e2a\\u4eba\\u8ba4\\u8bc1\",\"icon\":\"http:\\/\\/shop.com\\/public\\/uploads\\/attach\\/2019\\/03\\/28\\/5c9ccca1c78cd.gif\",\"id\":1,\"status\":1}],\"count\":0}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "post",
    "url": "/commonapi/Certification/entity_post/cate_id/1.html",
    "title": "填表资料项提交(认证接口)",
    "group": "certification",
    "name": "entity_post",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "cate_id",
            "description": "<p>类别id</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\"zsxm\":1,\"sfzh\":\"1\"}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/faq/id/1.html",
    "title": "问题详情",
    "group": "certification",
    "name": "faq",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/faq/id/1.html",
    "title": "问题详情",
    "group": "certification",
    "name": "faq",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/User.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/faq_list.html",
    "title": "问题列表",
    "group": "certification",
    "name": "faq_list",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page_num",
            "description": "<p>每页数量</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\"page\":\"1\",\"page_num\":\"20\"}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/faq_list.html",
    "title": "问题列表",
    "group": "certification",
    "name": "faq_list",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "page_num",
            "description": "<p>每页数量</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\"page\":\"1\",\"page_num\":\"20\"}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/User.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/icon.html",
    "title": "认证标识显示",
    "group": "certification",
    "name": "icon",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/is_certification.html",
    "title": "是否开启认证",
    "group": "certification",
    "name": "is_certification",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/new_msg.html",
    "title": "结果提示",
    "group": "certification",
    "name": "new_msg",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "get",
    "url": "/commonapi/Certification/new_msg_read/id/1.html",
    "title": "结果提示阅读回传",
    "group": "certification",
    "name": "new_msg_read",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "info",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "result",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/commonapi/controller/Certification.php",
    "groupTitle": "certification"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/bind_mobile",
    "title": "花间一壶酒活动-绑定手机",
    "name": "bind_mobile",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "iv",
            "description": "<p>iv</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "encryptedData",
            "description": "<p>encryptedData</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/check_code",
    "title": "花间一壶酒活动6.确认核销页面",
    "name": "check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Active.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/check_code",
    "title": "花间一壶酒活动6.确认核销页面",
    "name": "check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/auth_api/confirm_order_exchange",
    "title": "花间一壶酒活动-兑酒流程4.兑换确认",
    "name": "confirm_order_exchange",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cartId",
            "description": "<p>活动id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/AuthApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/auth_api/create_order_exchange",
    "title": "花间一壶酒活动-兑酒流程5.立即兑换",
    "name": "create_order_exchange",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "midkey",
            "description": "<p>midkey</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/AuthApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/store_api/details",
    "title": "花间一壶酒活动-兑酒流程2.商品详情页",
    "name": "details",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>商品id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "couponId",
            "description": "<p>优惠券Id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/StoreApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/enroll",
    "title": "花间一壶酒活动4.参加报名",
    "name": "enroll",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "event_id",
            "description": "<p>活动id</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "zsxm",
            "description": "<p>真实姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "wx",
            "description": "<p>微信</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "sjh",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "sfzh",
            "description": "<p>身份证号</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/enroll_event",
    "title": "花间一壶酒活动3.活动报名页",
    "name": "enroll_event",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>活动id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/get_check_code",
    "title": "花间一壶酒活动5.获取核销码",
    "name": "get_check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "event_id",
            "description": "<p>活动id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Active.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/get_check_code",
    "title": "花间一壶酒活动5.获取核销码",
    "name": "get_check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "event_id",
            "description": "<p>活动id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/get_event",
    "title": "花间一壶酒活动2.获取活动详情页",
    "name": "get_event",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>活动id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/get_event_list_huajian",
    "title": "花间一壶酒活动1.活动列表",
    "name": "get_event_list_huajian",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "district",
            "description": "<p>地区</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/store_api/get_product_list",
    "title": "花间一壶酒活动-兑酒流程1.商品列表",
    "name": "get_product_list",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "couponId",
            "description": "<p>优惠券Id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/StoreApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/get_recieve_order_list",
    "title": "花间一壶酒活动-领酒流程1.我的领取列表",
    "name": "get_recieve_order_list",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态 1待领取 2已领取 -1已过期</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/coupons_api/get_use_coupons",
    "title": "花间一壶酒活动-兑酒流程.优惠券列表",
    "name": "get_use_coupons",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "types",
            "description": "<p>类型0所有 1未使用 2已使用 3已过期</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/CouponsApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/auth_api/now_exchange",
    "title": "花间一壶酒活动-兑酒流程3.立即兑换",
    "name": "now_exchange",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "productId",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "uniqueId",
            "description": "<p>规格ID(73:235eb9b5)</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "couponId",
            "description": "<p>优惠券id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/AuthApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/recieve_order_detail",
    "title": "花间一壶酒活动-领酒流程2.待领取详情",
    "name": "recieve_order_detail",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "uni",
            "description": "<p>订单id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/login/setCode",
    "title": "花间一壶酒活动-绑定手机 获取session_key",
    "name": "setCode",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>code</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/Login.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/sure_check_code",
    "title": "花间一壶酒活动7.核销",
    "name": "sure_check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Active.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/osapi/event/sure_check_code",
    "title": "花间一壶酒活动7.核销",
    "name": "sure_check_code",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/osapi/controller/Event.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/check_code",
    "title": "花间一壶酒活动-领酒流程3.审核人员确认核销页面",
    "name": "审核人员确认核销页面",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/get_check_list",
    "title": "花间一壶酒活动-领酒流程3.核销记录",
    "name": "核销记录",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "limit",
            "description": "<p>每页条数</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/inspect_code",
    "title": "花间一壶酒活动-领酒流程3.检查核销码是否有用",
    "name": "检查核销码是否有用",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  },
  {
    "type": "post",
    "url": "/ebapi/user_api/sure_check_code",
    "title": "花间一壶酒活动-领酒流程4.领酒核销",
    "name": "领酒核销",
    "group": "花间一壶酒活动",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Access-Token",
            "defaultValue": "4bb116d3-d820-47ce-a04b-b9860f8792a2",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "content-type",
            "defaultValue": "application/x-www-form-urlencoded;charset=utf-8",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Platform-Token",
            "defaultValue": "pzydD7vpAgcr9cJZ7Pz48z8MmUkxYjhQ9FTIIa9kpgU5hWS2A4dkxVy9msJ5YmPzyb3qZX65+5WrVGKrLw4C31d6QgH6/dh+quEQPdcg9a4=",
            "description": ""
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "KF",
            "defaultValue": "cp",
            "description": ""
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "varchar",
            "optional": false,
            "field": "code",
            "description": "<p>核销码</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "application/ebapi/controller/UserApi.php",
    "groupTitle": "花间一壶酒活动"
  }
] });
