<?php


namespace app\home\controller;


use app\admin\model\DataDb;
use app\admin\model\MaterialDb;
use app\admin\model\ModelNameBb;
use app\admin\model\NameDb;
use app\admin\model\SizeDb;
use app\BaseController;

class IndexController extends BaseController
{
    public function index() {
        $nameList = NameDb::getNameList(1, 1000);
        $modelList = [];
        if (!empty($nameList->items())) {
            $modelList = ModelNameBb::getModelListByNameId($nameList[0]['id']);
        }
        return view('index/index', ['nameList'=>$nameList->items(), 'modelList'=>$modelList]);
    }

    public function getModelList() {
        $nameId = request()->post('nameId');

        // 名称列表
        $nameInfo = NameDb::findNameById($nameId);
        if (empty($nameInfo)) {
            failedAjax(__LINE__, "获取失败, 名称不存在");
        }

        $modelList = ModelNameBb::getModelListByNameId($nameId);
        successAjax("获取成功", $modelList);
    }

    public function getSizeList() {
        $nameId = request()->post('nameId');
        $modelId = request()->post('modelId');

        // 名称
        $nameInfo = NameDb::findNameById($nameId);
        if (empty($nameInfo)) {
            failedAjax(__LINE__, "获取失败, 名称不存在");
        }

        // 型号
        $modelInfo = ModelNameBb::findModelNameById($modelId);
        if (empty($modelInfo)) {
            failedAjax(__LINE__, "获取失败, 型号不存在");
        }

        $sizeList = SizeDb::getSizeListByNameIdAndModelId($nameId, $modelId);
        successAjax("获取成功", $sizeList);
    }

    public function getMaterial() {
        $param = request()->post();

        $condition = [
            'nameId'    =>  $param['nameId'],
            'modelId'   =>  $param['modelId'],
            'sizeId'   =>  $param['sizeId'],
        ];

        $list = MaterialDb::getMaterialByCondition($condition);
        successAjax("获取成功", $list);
    }

    public function saveData() {
        $param = request()->post();
        $installData = [
            'nameId'    =>  $param['nameId'],
            'modelId'   =>  $param['modelId'],
            'sizeId'    =>  $param['sizeId'],
            'profit'    =>  $param['profit'],
            'tableList' =>  json_encode($param['tableList']),
            'create_time'   =>  thisTime()
        ];
        DataDb::saveData($installData);
        successAjax("保存成功");
    }
}