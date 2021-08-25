<?php


namespace app\admin\middleware;


class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        if (empty(session('Admin'))) {
            if ($request->isPost()) {
                failedAjax(__LINE__, "请登录后再进行操作");
            } else {
                return redirect('/admin/login');
            }
        }
        return $next($request);
    }
}