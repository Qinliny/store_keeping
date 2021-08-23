<?php


namespace app\admin\model;


use think\facade\Db;

class MaterialDb
{
    private static $table = 'material';

    public static function saveMaterial($data) {
        return  Db::table(self::$table)->insert([
            'nameId'    =>  $data['nameId'],
            'modelId'   =>  $data['modelId'],
            'sizeId'    =>  $data['sizeId'],
            'material_name' =>  $data['material_name'],
            'material_price'    =>  $data['material_price'],
            'material_dosage'   =>  $data['material_dosage'],
            'total' =>  $data['total'],
            'create_time'   =>  thisTime()
        ]);
    }

    public static function getMaterialList($page, $limit) {
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->join('model_name c', 'a.modelId = c.id')
            ->join('size d', 'a.sizeId = d.id')
            ->field('a.*, b.name, c.model_name, d.size_height, d.size_width')
            ->paginate([
               'list_rows'  =>  $limit,
               'page'       =>  $page
            ]);
    }

    public static function getMaterialByCondition($condition) {
        return Db::table(self::$table)->where($condition)->select();
    }
}