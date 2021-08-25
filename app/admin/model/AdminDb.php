<?php


namespace app\admin\model;


use think\facade\Db;

class AdminDb
{
    private static $table = "admin";

    public static function findAdminByUserName($username) {
        return Db::table(self::$table)->where('username', $username)->find();
    }
}