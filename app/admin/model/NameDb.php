<?php
namespace app\admin\model;


use think\facade\Db;

class NameDb
{
    private static $table = "name";

    /**
     * 添加名称
     * @string $name         名称
     * @return int|string
     */
    public static function saveData($name, $factor) {
        return Db::table(self::$table)->insert([
            'name'  =>  $name,
            'factor'    =>   $factor,
            'create_time'   =>  thisTime()
        ]);
    }

    /**
     * 根据名称查询
     * @param $name
     * @return array|Db|\think\Model|null
     */
    public static function findNameByName($name) {
        return Db::table(self::$table)->where('name', $name)->find();
    }

    /**
     * 获取名称列表
     * @param $page
     * @param $limit
     * @return \think\Paginator
     */
    public static function getNameList($page, $limit) {
        return Db::table(self::$table)->paginate([
            'list_rows' =>  $limit,
            'page'      =>  $page
        ]);
    }

    public static function findNameById($id) {
        return Db::table(self::$table)->where('id', $id)->find();
    }

    public static function findNameByNameNotId($id, $name) {
        return Db::table(self::$table)->where([
            ['id', '<>', $id],
            ['name', '=', $name]
        ])->find();
    }

    public static function updateNameById($id, $name, $factor) {
        return Db::table(self::$table)->where('id', $id)->update(['name'=>$name, 'factor'=>$factor]);
    }

    public static function deleteNameById($id) {
        return Db::transaction(function () use ($id) {
            Db::table(self::$table)->where('id', $id)->delete();
            Db::table('model_name')->where('nameId', $id)->delete();
            Db::table('size')->where('nameId', $id)->delete();
            Db::table('material')->where('nameId', $id)->delete();
            Db::table('data')->where('nameId', $id)->delete();
        });
    }
}