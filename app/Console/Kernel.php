<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Spatie\Health\Models\HealthCheckResultHistoryItem;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;
use Spatie\Health\Commands\DispatchQueueCheckJobsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /////////////////
        // queue, horizon
        ////////////////
        $schedule->command('horizon:snapshot')
            ->timezone('Europe/Istanbul')
            ->everyFiveMinutes();

        /////////////////
        // backups
        ////////////////
        $schedule->command('backup:clean')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01:00')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('mail.from.address'))
            ->environments([ 'production' ]);

        $schedule->command('backup:run')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01:30')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('mail.from.address'))
            ->environments([ 'production' ]);

        $schedule->command('backup:monitor')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('03:00')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('mail.from.address'))
            ->environments([ 'production' ]);

        /////////////////////////
        // health checks
        ////////////////////////
        $schedule->command(RunHealthChecksCommand::class)
            ->timezone('Europe/Istanbul')
            ->everyMinute();

        $schedule->command(ScheduleCheckHeartbeatCommand::class)
            ->timezone('Europe/Istanbul')
            ->everyMinute();

        $schedule->command(DispatchQueueCheckJobsCommand::class)
            ->timezone('Europe/Istanbul')
            ->everyMinute();

        //////////////////
        // pruning
        /////////////////
        $schedule->command('sanctum:prune-expired --hours=24')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('03.30')
            ->emailOutputOnFailure(config('mail.from.address'));

        $schedule->command('model:prune', ['--model' => [ HealthCheckResultHistoryItem::class ]])
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('03:45')
            ->emailOutputOnFailure(config('mail.from.address'));

        $schedule->command('telescope:prune')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('04.00')
            ->emailOutputOnFailure(config('mail.from.address'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
