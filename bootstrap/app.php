<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            /*
             * Registered outside the `web` middleware group so session / CSRF
             * middleware never run (PreventRequestForgery still calls session on GET responses).
             */
            Route::get('/setup/install', function () {
                $secret = config('app.setup_secret');
                $allowed = app()->environment('local')
                    || (is_string($secret) && $secret !== '' && hash_equals($secret, (string) request('key', '')));
                abort_unless($allowed, 404);

                $steps = [];

                Artisan::call('migrate', ['--force' => true]);
                $steps['migrate'] = trim(Artisan::output()) ?: 'OK';

                Artisan::call('storage:link', ['--force' => true]);
                $steps['storage:link'] = trim(Artisan::output()) ?: 'OK';

                Artisan::call('db:seed', ['--force' => true]);
                $steps['db:seed'] = trim(Artisan::output()) ?: 'OK';

                return response()->json([
                    'ok' => true,
                    'steps' => $steps,
                ], 200, [], JSON_PRETTY_PRINT);
            })->name('setup.install');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
