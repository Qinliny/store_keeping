<?php
namespace app\admin\route;

use think\facade\Route;

Route::get("/", "IndexController/index");

Route::post("/addName", "IndexController/addName");
Route::post("/editName", "IndexController/editName");

Route::get("/model", "IndexController/model");
Route::post("/model/addModelName", "IndexController/addModelName");