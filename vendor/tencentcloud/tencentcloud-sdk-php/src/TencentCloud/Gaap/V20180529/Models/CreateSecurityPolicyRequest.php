<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Gaap\V20180529\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getProxyId() 获取加速通道ID
 * @method void setProxyId(string $ProxyId) 设置加速通道ID
 * @method string getDefaultAction() 获取默认策略：ACCEPT或DROP
 * @method void setDefaultAction(string $DefaultAction) 设置默认策略：ACCEPT或DROP
 */

/**
 *CreateSecurityPolicy请求参数结构体
 */
class CreateSecurityPolicyRequest extends AbstractModel
{
    /**
     * @var string 加速通道ID
     */
    public $ProxyId;

    /**
     * @var string 默认策略：ACCEPT或DROP
     */
    public $DefaultAction;
    /**
     * @param string $ProxyId 加速通道ID
     * @param string $DefaultAction 默认策略：ACCEPT或DROP
     */
    function __construct()
    {

    }
    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("ProxyId",$param) and $param["ProxyId"] !== null) {
            $this->ProxyId = $param["ProxyId"];
        }

        if (array_key_exists("DefaultAction",$param) and $param["DefaultAction"] !== null) {
            $this->DefaultAction = $param["DefaultAction"];
        }
    }
}
