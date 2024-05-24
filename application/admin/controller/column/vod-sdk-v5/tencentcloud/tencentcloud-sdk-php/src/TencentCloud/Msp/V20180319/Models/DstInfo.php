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
namespace TencentCloud\Msp\V20180319\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getRegion() 获取迁移目的地域
 * @method void setRegion(string $Region) 设置迁移目的地域
 * @method string getIp() 获取迁移目的Ip
 * @method void setIp(string $Ip) 设置迁移目的Ip
 * @method string getPort() 获取迁移目的端口
 * @method void setPort(string $Port) 设置迁移目的端口
 * @method string getInstanceId() 获取迁移目的实例Id
 * @method void setInstanceId(string $InstanceId) 设置迁移目的实例Id
 */

/**
 *迁移目的信息
 */
class DstInfo extends AbstractModel
{
    /**
     * @var string 迁移目的地域
     */
    public $Region;

    /**
     * @var string 迁移目的Ip
     */
    public $Ip;

    /**
     * @var string 迁移目的端口
     */
    public $Port;

    /**
     * @var string 迁移目的实例Id
     */
    public $InstanceId;
    /**
     * @param string $Region 迁移目的地域
     * @param string $Ip 迁移目的Ip
     * @param string $Port 迁移目的端口
     * @param string $InstanceId 迁移目的实例Id
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Region",$param) and $param["Region"] !== null) {
            $this->Region = $param["Region"];
        }

        if (array_key_exists("Ip",$param) and $param["Ip"] !== null) {
            $this->Ip = $param["Ip"];
        }

        if (array_key_exists("Port",$param) and $param["Port"] !== null) {
            $this->Port = $param["Port"];
        }

        if (array_key_exists("InstanceId",$param) and $param["InstanceId"] !== null) {
            $this->InstanceId = $param["InstanceId"];
        }
    }
}
