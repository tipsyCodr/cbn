<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call(function () {
            $nextWednesday = \Carbon\Carbon::now()->next(\Carbon\Carbon::WEDNESDAY)->format('Y-m-d');
            if (!\App\Models\Meeting::where('date', $nextWednesday)->exists()) {
                \App\Models\Meeting::create([
                    'name' => 'Weekly Networking Session',
                    'date' => $nextWednesday,
                    'start_time' => '14:00:00',
                    'end_time' => '16:00:00',
                    'tyfcb' => '₹ 25,000',
                    'visitors' => '3',
                    'speakers' => 'Bob Johnson'
                ]);
            }
        })->daily();
    })
    ->create();
