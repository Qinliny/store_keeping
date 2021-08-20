<?php


namespace app\home\controller;


use app\BaseController;

class IndexController extends BaseController
{
    public function index() {
        return view('index/index');
    }
}