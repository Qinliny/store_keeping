<?php


namespace app\admin\controller;


use app\admin\model\ModelNameBb;
use app\admin\model\NameDb;
use app\admin\model\SizeDb;
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
            'limit' =>  $limit,
            'item'  =>  'name'
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

    // 编辑名称页面
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

    public function model() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        // 获取名称列表
        $nameList = NameDb::getNameList(1, 1000);
        // 获取列表
        $list = ModelNameBb::getModelNameList($page, $limit);
        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'model',
            'nameList'  =>  $nameList->items()
        ]);
        return view('index/model');
    }

    public function addModelName() {
        $param = request()->post();
        // 校验数据
        $this->validate($param, [
            'name'  =>  'require|integer',
            'model_name'    =>  'require|max:20'
        ], [
            'name.require'  =>  '请选择名称',
            'name.integer'  =>  '请选择名称',
            'model_name.require'    =>  '请输入型号名称',
            'model_name.max'        =>  '型号名称长度不能大于20'
        ]);
        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($param['name']);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }
        // 添加数据
        ModelNameBb::saveData($param['name'], $param['model_name']);
        successAjax("添加成功");
    }

    public function editModelName() {
        $param = request()->post();
        // 校验数据
        $this->validate($param, [
            'id'    =>  'require|integer',
            'name'  =>  'require|integer',
            'model_name'    =>  'require|max:20'
        ], [
            'id.require'  =>  '参数异常',
            'id.integer'  =>  '参数异常',
            'name.require'  =>  '请选择名称',
            'name.integer'  =>  '请选择名称',
            'model_name.require'    =>  '请输入型号名称',
            'model_name.max'        =>  '型号名称长度不能大于20'
        ]);
        // 查询数据是否存在
        $info = ModelNameBb::findModelNameById($param['id']);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($param['name']);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }
        ModelNameBb::updateModelNameById($param['id'], $param['name'], $param['model_name']);
        successAjax("修改成功");
    }

    public function size() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        // 获取名称列表
        $nameList = NameDb::getNameList(1, 1000);

        // 获取型号列表
        $modelNameList = ModelNameBb::getModelNameList(1, 1000);

        $list = SizeDb::getSizeList($page, $limit);

        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'size',
            'modelNameList' =>  $modelNameList->items(),
            'nameList'  =>  $nameList->items()
        ]);
        return view('index/size');
    }

    public function addSize() {
        $param = request()->post();
        // 校验数据
        $this->validate($param, [
            'nameId'  =>  'require|integer',
            'modelNameId'   =>  'require|integer',
            'size_width'    =>   'require|float',
            'size_height'   =>  'require|float',
            'size_number'   =>  'integer',
            'size_value'    =>  'float'
        ], [
            'nameId.require'  =>  '请选择名称',
            'nameId.integer'  =>  '请选择名称',
            'modelNameId.require'    =>  '请选择型号',
            'modelNameId.integer'    =>  '请选择型号',
            'size_width.require'    =>  '请输入规格宽度',
            'size_width.float'  =>  '规格宽度数值必须为整数或者小数',
            'size_height.require'    =>  '请输入规格长度',
            'size_height.float'  =>  '规格长度数值必须为整数或者小数',
            'size_number.integer'   =>  '主线直径数只能为整数',
            'size_value.float'      =>  '主线直径值必须为整数或者小数'
        ]);

        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($param['nameId']);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }

        // 查询型号是否存在
        $info = ModelNameBb::findModelNameById($param['modelNameId']);
        if (empty($info)) {
            failedAjax(__LINE__, "型号数据不存在");
        }

        // 添加数据
        $data = [
            'nameId'    =>  $param['nameId'],
            'modelId'   =>  $param['modelNameId'],
            'size_height'   =>  $param['size_height'],
            'size_width'    =>  $param['size_width'],
            'size_number'   =>  $param['size_number'],
            'size_value'    =>  $param['size_value']
        ];
        SizeDb::saveData($data);
        successAjax("添加成功");
    }

    public function material() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        // 获取名称列表
        $nameList = NameDb::getNameList(1, 1000);

        // 获取型号列表
        $modelNameList = ModelNameBb::getModelNameList(1, 1000);

        $list = SizeDb::getSizeList($page, $limit);

        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'size',
            'modelNameList' =>  $modelNameList->items(),
            'nameList'  =>  $nameList->items()
        ]);
        return view('index/material');
    }
}