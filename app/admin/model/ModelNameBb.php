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
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->field('b.name, a.*')
            ->where('a.id', $id)
            ->find();
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

    public static function updateModelNameById($id, $nameId, $modelName) {
        return Db::table(self::$table)->where('id', $id)->update([
            'nameId'    =>  $nameId,
            'model_name'    =>  $modelName
        ]);
    }

    public static function getModelListByNameId($nameId) {
        return Db::table(self::$table)->where('nameId', $nameId)->select();
    }

    public static function deleteModelById($modelId) {
        return Db::transaction(function () use ($modelId) {
            Db::table('model_name')->where('id', $modelId)->delete();
            Db::table('size')->where('modelId', $modelId)->delete();
            Db::table('material')->where('modelId', $modelId)->delete();
            Db::table('data')->where('modelId', $modelId)->delete();
        });
    }
}