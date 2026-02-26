<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\CheckPermission;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Localization;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => CheckPermission::class,
        ]);
        $middleware->web(append: [
            Localization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Illuminate\Http\Request $request) {
            return response()->view('404', [], 404);
        });
    })->create();
