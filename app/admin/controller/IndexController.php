<?php


namespace app\admin\controller;


use app\admin\model\DataDb;
use app\admin\model\MaterialDb;
use app\admin\model\ModelNameBb;
use app\admin\model\NameDb;
use app\admin\model\SizeDb;
use app\admin\model\AdminDb;
use think\captcha\facade\Captcha;
use think\facade\Session;
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
        $param = request()->post();
        $this->validate($param, [
            'name'      =>  'require|max:20',
            'factor'    =>  'require|float'
        ], [
            'name.require'  =>  '请输入名称',
            'name.max'      =>  '名称长度不能超过20',
            'factor.require'    =>  '请输入系数',
            'factor.float'      =>  '系数必须为整数或者小数',
        ]);
        // 判断名称是否存在
        $info = NameDb::findNameByName($param);
        if (!empty($info)) {
            failedAjax(__LINE__, "名称已存在");
        }
        // 添加名称
        NameDb::saveData($param['name'], $param['factor']);
        successAjax("添加名称成功");
    }

    // 编辑名称
    public function editName() {
        $param = request()->post();
        $this->validate($param, [
            'id'        =>  'require|integer',
            'name'      =>  'require|max:20',
            'factor'    =>  'require|float'
        ], [
            'id.require'    =>  '参数异常',
            'id.integer'    =>  '参数异常',
            'name.require'  =>  '请输入名称',
            'name.max'      =>  '名称长度不能超过20',
            'factor.require'    =>  '请输入系数',
            'factor.float'      =>  '系数必须为整数或者小数',
        ]);
        $id = $param['id'];
        $name = $param['name'];
        $factor = $param['factor'];
        $queryInfo = NameDb::findNameById($id);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "数据不存在");
        }
        $queryInfo = NameDb::findNameByNameNotId($id, $name);

        if (!empty($queryInfo)) {
            failedAjax(__LINE__, "名称已存在");
        }
        NameDb::updateNameById($id, $name, $factor);
        successAjax("编辑名称成功");
    }

    public function delName() {
        $id = request()->post('id');
        $queryInfo = NameDb::findNameById($id);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "数据不存在");
        }
        NameDb::deleteNameById($id);
        successAjax("删除成功");
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

    public function getModelList() {
        $nameId = request()->post('nameId');
        // 查询数据是否存在
        $info = NameDb::findNameById($nameId);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        $modelList = ModelNameBb::getModelListByNameId($nameId);
        $modelId = null;
        if (!empty($modelList)) {
            $modelId = $modelList[0]['id'];
        }
        $sizeList = SizeDb::getSizeListByNameIdAndModelId($nameId, $modelId);
        successAjax("获取成功", ['modelList'=>$modelList, 'sizeList'=>$sizeList]);
    }

    public function deleteModel() {
        $modelId = request()->post('modelId');
        // 查询数据是否存在
        $info = ModelNameBb::findModelNameById($modelId);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        ModelNameBb::deleteModelById($modelId);
        successAjax("删除成功");
    }

    public function size() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        // 获取名称列表
        $nameList = NameDb::getNameList(1, 1000);

        $nameId = null;
        if (!empty($nameList->items())) {
            $nameId = $nameList->items()[0]['id'];
        }

        // 获取型号列表
        $modelNameList = empty($nameId) ? [] : ModelNameBb::getModelListByNameId($nameId);

        $list = SizeDb::getSizeList($page, $limit);
        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'size',
            'modelNameList' =>  $modelNameList,
            'nameId'    =>  $nameId,
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
            'size_value'    =>  'float',
            'nullLine_number'   =>  'integer',
            'nullLine_value'    =>  'float'
        ], [
            'nameId.require'  =>  '请选择名称',
            'nameId.integer'  =>  '请选择名称',
            'modelNameId.require'   =>  '请选择型号',
            'modelNameId.integer'   =>  '请选择型号',
            'size_width.require'    =>  '请输入规格宽度',
            'size_width.float'      =>  '规格宽度数值必须为整数或者小数',
            'size_height.require'   =>  '请输入规格长度',
            'size_height.float'     =>  '规格长度数值必须为整数或者小数',
            'size_number.integer'   =>  '主线直径数只能为整数',
            'size_value.float'      =>  '主线直径值必须为整数或者小数',
            'nullLine_number.integer'  =>  '零线直径数量只能为整数',
            'nullLine_value.float'     =>  '零线直径值必须为整数或者小数'
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
            'size_value'    =>  $param['size_value'],
            'nullLine_number'   =>  $param['nullLine_number'],
            'nullLine_value'    =>  $param['nullLine_value']
        ];
        SizeDb::saveData($data);
        successAjax("添加成功");
    }

    public function getSizeInfo() {
        $sizeId = request()->post('sizeId');
        $info = SizeDb::getSizeInfoById($sizeId);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        successAjax("获取成功", $info);
    }

    public function getSizeList() {
        $nameId =  request()->post('nameId');
        $modelId = request()->post('modelId');

        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($nameId);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }

        // 查询型号是否存在
        $info = ModelNameBb::findModelNameById($modelId);
        if (empty($info)) {
            failedAjax(__LINE__, "型号数据不存在");
        }

        $sizeList = SizeDb::getSizeListByNameIdAndModelId($nameId, $modelId);
        successAjax("获取成功", ['sizeList'=>$sizeList]);
    }

    public function editSize() {
        $param = request()->post();
        // 校验数据
        $this->validate($param, [
            'id'        =>  'require|integer',
            'nameId'  =>  'require|integer',
            'modelNameId'   =>  'require|integer',
            'size_width'    =>   'require|float',
            'size_height'   =>  'require|float',
            'size_number'   =>  'integer',
            'size_value'    =>  'float',
            'nullLine_number'   =>  'integer',
            'nullLine_value'    =>  'float'
        ], [
            'id.require'  =>  '数据异常',
            'id.integer'  =>  '数据异常',
            'nameId.require'  =>  '请选择名称',
            'nameId.integer'  =>  '请选择名称',
            'modelNameId.require'    =>  '请选择型号',
            'modelNameId.integer'    =>  '请选择型号',
            'size_width.require'    =>  '请输入规格宽度',
            'size_width.float'  =>  '规格宽度数值必须为整数或者小数',
            'size_height.require'    =>  '请输入规格长度',
            'size_height.float'  =>  '规格长度数值必须为整数或者小数',
            'size_number.integer'   =>  '主线直径数只能为整数',
            'size_value.float'      =>  '主线直径值必须为整数或者小数',
            'nullLine_number.integer'  =>  '零线直径数量只能为整数',
            'nullLine_value.float'     =>  '零线直径值必须为整数或者小数'
        ]);

        // 判断数据是否存在
        $sizeInfo = SizeDb::getSizeInfoById($param['id']);
        if (empty($sizeInfo)) {
            failedAjax(__LINE__, "数据不存在");
        }

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
            'size_value'    =>  $param['size_value'],
            'nullLine_number'   =>  $param['nullLine_number'],
            'nullLine_value'    =>  $param['nullLine_value']
        ];
        SizeDb::updateSizeDataById($param['id'], $data);
        successAjax("修改成功");
    }

    public function deleteSize() {
        $sizeId = request()->post('sizeId');
        // 判断数据是否存在
        $sizeInfo = SizeDb::getSizeInfoById($sizeId);
        if (empty($sizeInfo)) {
            failedAjax(__LINE__, "数据不存在");
        }
        SizeDb::deleteSizeById($sizeId);
        successAjax("删除成功");
    }

    public function material() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        // 获取名称列表
        $nameList = NameDb::getNameList(1, 1000);
        $nameId = $modelId = null;
        if (!empty($nameList->items())) {
            $nameId = $nameList->items()[0]['id'];
        }
        // 获取型号列表
        $modelNameList = ModelNameBb::getModelListByNameId($nameId);
        if (!empty($modelNameList->toArray())) {
            $modelId = $modelNameList[0]['id'];
        }
        // 获取规格
        $sizeList = SizeDb::getSizeListByNameIdAndModelId($nameId, $modelId);

        $list = MaterialDb::getMaterialList($page, $limit);
        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'material',
            'modelNameList' =>  $modelNameList,
            'nameList'  =>  $nameList->items(),
            'sizeList'  =>  $sizeList
        ]);
        return view('index/material');
    }

    public function addMaterial() {
        $param = request()->post();
        $this->validate($param, [
            'nameId'    =>  'require|integer',
            'modelId'   =>  'require|integer',
            'sizeId'    =>  'require|integer',
            'material_name' =>  'require|max:20',
            'material_price'    =>  'require|float',
            'material_dosage'    =>  'require|float',
        ], [
            'nameId.require'    =>  "请选择名称",
            'nameId.integer'    =>  "请选择名称",
            'modelId.require'    =>  "请选择型号",
            'modelId.integer'    =>  "请选择型号",
            'sizeId.require'    =>  "请选择规格",
            'sizeId.integer'    =>  "请选择规格",
            'material_name.require' =>  '请输入材料名称',
            'material_name.max' =>  '材料名称长度不能超过20',
            'material_price.require'    =>  '请输入材料单价',
            'material_price.float'  =>  '材料单价必须为整数或者小数',
            'material_dosage.require'   =>  '请输入材料用量',
            'material_dosage.float' =>  '材料用量必须为整数或者小数'
        ]);

        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($param['nameId']);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }

        // 查询型号是否存在
        $info = ModelNameBb::findModelNameById($param['modelId']);
        if (empty($info)) {
            failedAjax(__LINE__, "型号数据不存在");
        }

        // 判断数据是否存在
        $sizeInfo = SizeDb::getSizeInfoById($param['sizeId']);
        if (empty($sizeInfo)) {
            failedAjax(__LINE__, "规格数据不存在");
        }

        MaterialDb::saveMaterial($param);
        successAjax("添加成功");
    }

    public function getMaterialInfo() {
        $id = request()->post('id');
        // 判断数据是否存在
        $info = MaterialDb::findMaterialInfoByCondition(['id'=>$id]);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        successAjax("获取成功", $info);
    }

    public function editMaterialInfo() {
        $param = request()->param();

        $this->validate($param, [
            'id'        =>  'require|integer',
            'nameId'    =>  'require|integer',
            'modelId'   =>  'require|integer',
            'sizeId'    =>  'require|integer',
            'material_name' =>  'require|max:20',
            'material_price'    =>  'require|float',
            'material_dosage'    =>  'require|float',
        ], [
            'id.require'  =>  '数据异常',
            'id.integer'  =>  '数据异常',
            'nameId.require'    =>  "请选择名称",
            'nameId.integer'    =>  "请选择名称",
            'modelId.require'   =>  "请选择型号",
            'modelId.integer'   =>  "请选择型号",
            'sizeId.require'    =>  "请选择规格",
            'sizeId.integer'    =>  "请选择规格",
            'material_name.require' =>  '请输入材料名称',
            'material_name.max'     =>  '材料名称长度不能超过20',
            'material_price.require'=>  '请输入材料单价',
            'material_price.float'  =>  '材料单价必须为整数或者小数',
            'material_dosage.require'   =>  '请输入材料用量',
            'material_dosage.float'     =>  '材料用量必须为整数或者小数'
        ]);

        // 判断数据是否存在
        $info = MaterialDb::findMaterialInfoByCondition(['id'=>$param['id']]);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }

        // 查询名称是否存在
        $queryInfo = NameDb::findNameById($param['nameId']);
        if (empty($queryInfo)) {
            failedAjax(__LINE__, "名称不存在");
        }

        // 查询型号是否存在
        $info = ModelNameBb::findModelNameById($param['modelId']);
        if (empty($info)) {
            failedAjax(__LINE__, "型号数据不存在");
        }

        // 判断数据是否存在
        $sizeInfo = SizeDb::getSizeInfoById($param['sizeId']);
        if (empty($sizeInfo)) {
            failedAjax(__LINE__, "规格数据不存在");
        }

        // 修改数据
        MaterialDb::updateMaterialById($param['id'], [
            'nameId'    =>  $param['nameId'],
            'modelId'   =>  $param['modelId'],
            'sizeId'    =>  $param['sizeId'],
            'material_name' =>  $param['material_name'],
            'material_price'    =>  $param['material_price'],
            'material_dosage'   =>  $param['material_dosage'],
            'total' =>  round((float)$param['material_price'] * (float)$param['material_dosage'], 3),
        ]);

        successAjax("修改成功");
    }

    public function deleteMaterial() {
        $id = request()->post('id');
        // 判断数据是否存在
        $info = MaterialDb::findMaterialInfoByCondition(['id'=>$id]);
        if (empty($info)) {
            failedAjax(__LINE__, "数据不存在");
        }
        MaterialDb::deleteMaterialById($id);
        successAjax("删除成功");
    }

    public function dataList() {
        $param = request()->get();
        $page = isset($param['page']) && $param['page'] > 0 ? $param['page'] : 1;
        $limit = isset($param['limit']) && $param['limit'] > 0 ? $param['limit'] : 15;
        $list = DataDb::getList($page, $limit);
        View::assign([
            'list'  =>  $list->items(),
            'count' =>  $list->total(),
            'page'  =>  $page,
            'limit' =>  $limit,
            'item'  =>  'data'
        ]);
        return view('/index/data');
    }

    public function login() {
        return view('index/login');
    }

    public function checkLogin() {
        $param = request()->post();

        // 校验数据
        $this->validate($param, [
            'username'  =>  'require',
            'password'  =>  'require',
            'verifyCode'    =>  'require'
        ], [
            'username.require'  =>  '请输入用户名！',
            'password.require'  =>  '请输入密码！',
            'verifyCode'        =>  '请输入验证码！'
        ]);

        // 校验验证码
        if (!captcha_check($param['verifyCode'])) failedAjax(__LINE__, "验证码不正确！");

        $adminInfo = AdminDb::findAdminByUserName($param['username']);
        if (empty($adminInfo)) failedAjax(__LINE__, "用户名不正确！");

        if (!password_verify($param['password'], $adminInfo['password']))
        {
            failedAjax(__LINE__, "密码不正确！");
        }

        $info = [
            'adminId'   =>  $adminInfo['id'],
            'adminUserName' =>  $adminInfo['username']
        ];

        Session::set('Admin', $info);
        return;
    }

    // 验证码
    public function verify() {
        return Captcha::create();
    }

    public function outlogin() {
        session("Admin", null);
        return redirect("/admin/login");
    }
}