<?php
namespace app\admin\route;

use think\facade\Route;

Route::get('/', 'IndexController/index');

Route::post('/getModelList', 'IndexController/getModelList');
Route::post('/getSizeList', 'IndexController/getSizeList');
Route::post('/getMaterial', 'IndexController/getMaterial');

Route::post('/saveData', 'IndexController/saveData');