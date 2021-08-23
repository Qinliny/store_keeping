<?php
namespace app\admin\route;

use think\facade\Route;

Route::get("/", "IndexController/index");

Route::post("/addName", "IndexController/addName");
Route::post("/editName", "IndexController/editName");

// 型号管理
Route::get("/model", "IndexController/model");
Route::post("/model/addModelName", "IndexController/addModelName");
Route::post("/model/editModelName", "IndexController/editModelName");
Route::post('model/getModelList', "IndexController/getModelList");

// 规格管理 editSize
Route::get("/size", "IndexController/size");
Route::post("/size/addSize", "IndexController/addSize");
Route::post("/size/getSizeInfo", "IndexController/getSizeInfo");
Route::post("/size/editSize", "IndexController/editSize");
Route::post("/size/getSizeList", "IndexController/getSizeList");

// 材料管理
Route::get("/material", "IndexController/material");
Route::post("/material/addMaterial", "IndexController/addMaterial");