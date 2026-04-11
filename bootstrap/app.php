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

                // Avoid Artisan storage:link: Filesystem::link() calls exec() without leading \,
                // which breaks under Illuminate\Filesystem; shared hosts often restrict exec/symlink.
                $steps['storage:link'] = (function (): string {
                    $links = config('filesystems.links') ?: [];
                    $lines = [];
                    foreach ($links as $link => $target) {
                        if (file_exists($link) && ! is_link($link)) {
                            $lines[] = "Skip {$link}: path exists and is not a symlink";

                            continue;
                        }
                        if (is_link($link)) {
                            @unlink($link);
                        }
                        if (! function_exists('symlink')) {
                            $lines[] = 'symlink() unavailable; create public/storage → storage/app/public in hosting panel';

                            continue;
                        }
                        try {
                            if (@\symlink($target, $link)) {
                                $lines[] = "Linked {$link} → {$target}";
                            } else {
                                $lines[] = "Failed {$link} (permissions or open_basedir)";
                            }
                        } catch (\Throwable $e) {
                            $lines[] = $e->getMessage();
                        }
                    }

                    return $lines !== [] ? implode("\n", $lines) : 'No links configured';
                })();

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
