<?php


namespace app\admin\model;


use think\facade\Db;

class SizeDb
{
    private static $table = "size";

    public static function saveData($data) {
        return Db::table(self::$table)->insert([
            'nameId'    =>  $data['nameId'],
            'modelId'   =>  $data['modelId'],
            'size_height'   =>  $data['size_height'],
            'size_width'    =>  $data['size_width'],
            'size_number'   =>  $data['size_number'],
            'size_value'    =>  $data['size_value'],
            'create_time'   =>  thisTime()
        ]);
    }

    public static function getSizeList($page, $limit) {
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->join('model_name c', 'a.modelId = c.id')
            ->field('a.*, b.name, c.model_name')
            ->paginate([
                'list_rows' =>  $limit,
                'page'      =>  $page
            ]);
    }

    public static function getSizeInfoById($id) {
        return Db::table(self::$table)->alias('a')
            ->join('name b', 'a.nameId = b.id')
            ->join('model_name c', 'a.modelId = c.id')
            ->field('a.*, b.name, c.model_name')
            ->where('a.id', $id)
            ->find();
    }

    public static function updateSizeDataById($id, $data) {
        return Db::table(self::$table)->where('id', $id)->update([
            'nameId'    =>  $data['nameId'],
            'modelId'   =>  $data['modelId'],
            'size_height'   =>  $data['size_height'],
            'size_width'    =>  $data['size_width'],
            'size_number'   =>  $data['size_number'],
            'size_value'    =>  $data['size_value'],
            'create_time'   =>  thisTime()
        ]);
    }

    public static function getSizeListByNameIdAndModelId($nameId, $modelId) {
        return Db::table(self::$table)->where([
            'nameId'    =>  $nameId,
            'modelId'   =>  $modelId
        ])->select();
    }

    public static function deleteSizeById($sizeId) {
        return Db::transaction(function () use ($sizeId) {
            Db::table(self::$table)->where('id', $sizeId)->delete();
            Db::table('material')->where('sizeId', $sizeId)->delete();
            Db::table('data')->where('sizeId', $sizeId)->delete();
        });
    }
}