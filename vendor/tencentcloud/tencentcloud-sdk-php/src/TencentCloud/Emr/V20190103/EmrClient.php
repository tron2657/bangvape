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

namespace TencentCloud\Emr\V20190103;
use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Emr\V20190103\Models as Models;

/**
* @method Models\CreateInstanceResponse CreateInstance(Models\CreateInstanceRequest $req) 创建EMR实例
* @method Models\DescribeInstancesResponse DescribeInstances(Models\DescribeInstancesRequest $req) 查询EMR实例
* @method Models\InquiryPriceCreateInstanceResponse InquiryPriceCreateInstance(Models\InquiryPriceCreateInstanceRequest $req) 创建实例询价
* @method Models\InquiryPriceRenewInstanceResponse InquiryPriceRenewInstance(Models\InquiryPriceRenewInstanceRequest $req) 续费询价。
* @method Models\InquiryPriceScaleOutInstanceResponse InquiryPriceScaleOutInstance(Models\InquiryPriceScaleOutInstanceRequest $req) 扩容询价. 当扩容时候，请通过该接口查询价格。
* @method Models\InquiryPriceUpdateInstanceResponse InquiryPriceUpdateInstance(Models\InquiryPriceUpdateInstanceRequest $req) 变配询价
* @method Models\ScaleOutInstanceResponse ScaleOutInstance(Models\ScaleOutInstanceRequest $req) 实例扩容
* @method Models\TerminateInstanceResponse TerminateInstance(Models\TerminateInstanceRequest $req) 销毁EMR实例
* @method Models\TerminateTasksResponse TerminateTasks(Models\TerminateTasksRequest $req) 缩容Task节点
 */

class EmrClient extends AbstractClient
{
    protected $endpoint = "emr.tencentcloudapi.com";

    protected $version = "2019-01-03";

    function __construct($credential, $region, $profile=null)
    {
        parent::__construct($this->endpoint, $this->version, $credential, $region, $profile);
    }

    public function returnResponse($action, $response)
    {
        $respClass = "TencentCloud"."\\".ucfirst("emr")."\\"."V20190103\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
