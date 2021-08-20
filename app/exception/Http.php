<?php
namespace app\exception;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class Http extends Handle
{
    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            failedAjax(422, $e->getError());
        }

        // 请求异常
        if ($e instanceof HttpException && $request->isAjax()) {
            failedAjax($e->getStatusCode(), $e->getMessage());
        } else {
            abort(500, "服务器出现异常");
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}