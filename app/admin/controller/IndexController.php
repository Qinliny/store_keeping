<?php


namespace app\admin\controller;


use app\admin\model\NameDb;
use think\facade\View;

class IndexController extends BaseController
{
    // 添加名称页面
    public function index() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        $list = NameDb::getNameList($page, $limit);
        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit
        ]);
        return view('index/index');
    }

    // 添加名称
    public function addName() {
        $name = request()->post('name');
        if (empty($name)) {
            failedAjax(__LINE__, "请输入名称");
        }
        // 判断名称是否存在
        $info = NameDb::findNameByName($name);
        if (!empty($info)) {
            failedAjax(__LINE__, "名称已存在");
        }
        // 添加名称
        NameDb::saveData($name);
        successAjax("添加名称成功");
    }

    public function editName() {
        $name = request()->post('name');
        $id = request()->post('id');
        if (empty($name)) {
            failedAjax(__LINE__, "请输入名称");
        }
        $queryInfo = NameDb::findNameById($id);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "数据不存在");
        }
        $queryInfo = NameDb::findNameByNameNotId($id, $name);

        if (!empty($queryInfo)) {
            failedAjax(__LINE__, "名称已存在");
        }
        NameDb::updateNameById($id, $name);
        successAjax("编辑名称成功");
    }
}