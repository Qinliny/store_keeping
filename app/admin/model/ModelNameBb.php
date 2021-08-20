<?php


namespace app\admin\model;


use think\facade\Db;

class ModelNameBb
{
    private static $table = "model_name";

    public static function saveData($nameId, $model_name) {
        return Db::table(self::$table)->insert([
           'nameId' =>  $nameId,
           'model_name' =>  $model_name,
           'create_time'    =>  thisTime()
        ]);
    }

    public static function findModelNameById($id) {
        return Db::table(self::$table)->where('id', $id)->find();
    }

    public static function getModelNameList($page, $limit) {
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->field('b.name, a.*')
            ->paginate([
                'list_rows' =>  $limit,
                'page'      =>  $page
            ]);
    }
}