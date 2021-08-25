<?php


namespace app\admin\controller;


use think\App;
use think\facade\View;

class BaseController extends \app\BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        if (session('Admin')) {
            View::assign('userInfo', session('Admin'));
        }
    }
}