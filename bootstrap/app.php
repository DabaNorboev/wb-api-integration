<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('fetch:incomes')->dailyAt('02:00');
        $schedule->command('fetch:incomes')->dailyAt('14:00');

        $schedule->command('fetch:orders')->dailyAt('02:30');
        $schedule->command('fetch:orders')->dailyAt('14:30');

        $schedule->command('fetch:sales')->dailyAt('03:00');
        $schedule->command('fetch:sales')->dailyAt('15:00');

        $schedule->command('fetch:stocks')->dailyAt('03:30');
        $schedule->command('fetch:stocks')->dailyAt('15:30');
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
