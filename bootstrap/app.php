<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Bộ lọc trung gian (Middleware) kiểm soát quyền truy cập
        $middleware->alias([
            'role'   => \App\Http\Middleware\EnsureUserHasRole::class,   // role:Admin
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,  // tài khoản chưa bị khóa
        ]);

        // Chưa đăng nhập -> về trang đăng nhập; đã đăng nhập mà vào /login -> về trang chủ
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('home'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
