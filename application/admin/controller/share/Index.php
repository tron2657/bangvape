<?php
/**
 * OpenSNS X
 * Copyright 2014-2020 http://www.thisky.com All rights reserved.
 * ----------------------------------------------------------------------
 * Author: 郑钟良(zzl@ourstu.com)
 * Date: 2019/11/29
 * Time: 13:51
 */

namespace app\admin\controller\share;


use app\admin\controller\AuthController;
use app\shareapi\model\InviteShare;
use service\FormBuilder;
use service\JsonService;
use service\UtilService;
use think\Request;
use think\Url;

class Index extends AuthController
{

    /**
     * 分销海报首页
     * @return mixed
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function hai_bao()
    {
        $where=UtilService::getMore([
            ['status',2],
        ]);
        $this->assign('status',$where['status']);
        return $this->fetch();
    }

    /**
     * 异步查找海报列表
     *
     * @return json
     */
    public function hai_bao_list(){
        $where=UtilService::getMore([
            ['page',1],
            ['limit',20],
            ['status',2],
        ]);
        return JsonService::successlayui(InviteShare::haiBaoList($where));
    }

    /**
     * 新增海报
     * @return mixed
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function createOne()
    {
        $this->assign('style','create');
        return $this->fetch();
    }

    /**
     * 新增海报保存
     * @param Request $request
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function saveOne(Request $request)
    {
        $data = UtilService::postMore([
            'title',
            'url',
            'sort',
            'status',
            'colour',
        ],$request);
        InviteShare::set($data);
        return JsonService::successful('添加成功');
    }

    /**
     * 编辑海报
     * @param $id
     * @return mixed|void
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function edit_one($id)
    {
        if(!$id) return $this->failed('数据不存在');
        $invite_share = InviteShare::get($id);
        if(!$invite_share) return JsonService::fail('数据不存在!');
        $this->assign('info',$invite_share);
        $this->assign('style','edit');
        return $this->fetch('create_one');
    }

    /**
     * 编辑保存
     * @param Request $request
     * @param $id
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function update_one(Request $request, $id)
    {
        $data = UtilService::postMore([
            'title',
            'url',
            'sort',
            'status',
            'colour',
        ],$request);
        InviteShare::edit($data,$id,'id');
        return JsonService::successful('编辑成功');
    }

    /**
     * 删除海报
     * @param $id
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function delete_one($id)
    {
        InviteShare::edit(['status'=>-1],$id,'id');
        return JsonService::successful('删除成功');
    }

    /**
     * 启用禁用海报
     * @param $id
     * @param $status
     * @author 郑钟良(zzl@ourstu.com)
     * @date 2019-7
     */
    public function change_status_one($id,$status=1)
    {
        InviteShare::edit(['status'=>$status],$id,'id');
        return JsonService::successful('设置成功');
    }
}