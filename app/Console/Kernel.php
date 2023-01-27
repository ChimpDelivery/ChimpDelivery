<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Spatie\Health\Models\HealthCheckResultHistoryItem;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule) : void
    {
        ////////////////////////////////////
        /// key rotators (after backups)
        ////////////////////////////////////
        $schedule->command('dashboard:rotate-key --show')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('02.00')
            ->emailOutputOnFailure(config('mail.from.address'))
            ->appendOutputTo(storage_path() . '/logs/schedule-key-rotating.log')
            ->environments([ 'staging', 'production' ]);

        ///////////////////////
        // queue, horizon
        //////////////////////
        $schedule->command('horizon:snapshot')
            ->timezone('Europe/Istanbul')
            ->everyFiveMinutes();

        $schedule->command('dashboard:restart-horizon')
            ->timezone('Europe/Istanbul')
            ->hourly()
            ->at(5)
            ->emailOutputOnFailure(config('mail.from.address'))
            ->appendOutputTo(storage_path() . '/logs/horizon.log');

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

        /////////////////////
        // health checks
        ////////////////////
        $schedule->command(RunHealthChecksCommand::class)
            ->timezone('Europe/Istanbul')
            ->everyMinute();

        $schedule->command(ScheduleCheckHeartbeatCommand::class)
            ->timezone('Europe/Istanbul')
            ->everyMinute();

        ///////////////
        // pruning
        //////////////
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
     */
    protected function commands() : void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
