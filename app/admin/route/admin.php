<?php
namespace app\admin\route;

use think\facade\Route;

Route::get("/", "IndexController/index")->middleware(\app\admin\middleware\CheckLogin::class);

Route::post("/addName", "IndexController/addName")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/editName", "IndexController/editName")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/delName", "IndexController/delName")->middleware(\app\admin\middleware\CheckLogin::class);

// 型号管理
Route::get("/model", "IndexController/model")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/model/addModelName", "IndexController/addModelName")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/model/editModelName", "IndexController/editModelName")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post('/model/getModelList', "IndexController/getModelList")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post('/model/deleteModel', "IndexController/deleteModel")->middleware(\app\admin\middleware\CheckLogin::class);
// 规格管理
Route::get("/size", "IndexController/size")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/size/addSize", "IndexController/addSize")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/size/getSizeInfo", "IndexController/getSizeInfo")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/size/editSize", "IndexController/editSize")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/size/getSizeList", "IndexController/getSizeList")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/size/deleteSize", "IndexController/deleteSize")->middleware(\app\admin\middleware\CheckLogin::class);

// 材料管理
Route::get("/material", "IndexController/material")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/material/addMaterial", "IndexController/addMaterial")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/material/getMaterialInfo", "IndexController/getMaterialInfo")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/material/editMaterialInfo", "IndexController/editMaterialInfo")->middleware(\app\admin\middleware\CheckLogin::class);
Route::post("/material/deleteMaterial", "IndexController/deleteMaterial")->middleware(\app\admin\middleware\CheckLogin::class);

// 数据管理
Route::get("/data", "IndexController/dataList")->middleware(\app\admin\middleware\CheckLogin::class);

Route::get("/login", "IndexController/login");
Route::post("/login/checkLogin", "IndexController/checkLogin");
Route::get("/outlogin", "IndexController/outlogin")->middleware(\app\admin\middleware\CheckLogin::class);
// 登录验证码
Route::get("/verify", "IndexController/verify");
