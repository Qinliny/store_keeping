<?php


namespace app\admin\model;

use think\facade\Db;

class DataDb
{
    private static $table = "data";

    public static function saveData($data) {
        return Db::table(self::$table)->insert($data);
    }

    public static function getList($page, $limit) {
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->join('model_name c', 'a.modelId = c.id')
            ->join('size d', 'a.sizeId = d.id')
            ->field('a.*, b.name, c.model_name, d.size_height, d.size_width')
            ->paginate([
                'list_rows'  =>  $limit,
                'page'       =>  $page
            ])->each(function($obj){
                // 获取材料信息
                $materialList = json_decode($obj['tableList'], true);
                $cost = 0;
                // 计算成本
                foreach ($materialList as $key => $val) {
                    $cost += (float)$val['total'];
                }
                $costNum = $cost / 1000;
                $obj['cost'] = round($costNum,  3);
                // 计算利润
                $priceNum = $costNum + (($obj['profit'] / 100) * $costNum);
                $obj['price'] = round($priceNum, 3);
                return $obj;
            });
    }
}