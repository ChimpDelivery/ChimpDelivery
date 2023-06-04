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
     */
    protected function schedule(Schedule $schedule) : void
    {
        /////////////////////////////////////
        /// proxy, cloudflare
        /////////////////////////////////////
        $schedule->command('cloudflare:reload')
            ->daily()
            ->at('04.30')
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->environments([ 'staging', 'production' ]);

        ////////////////////////////////////
        /// encryption, key rotators (after backups)
        ////////////////////////////////////
        $schedule->command('key:generate --force')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01.45')
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->appendOutputTo(storage_path() . '/logs/schedule-key-rotating.log')
            ->onSuccess(fn() => $this->call('dashboard:update-dotenv-secret'));

        $schedule->command('dashboard:rotate-key --show')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('02.00')
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->appendOutputTo(storage_path() . '/logs/schedule-key-rotating.log')
            ->onSuccess(fn() => $this->call('dashboard:update-dotenv-secret'));

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
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->appendOutputTo(storage_path() . '/logs/horizon.log');

        /////////////////
        // backups
        ////////////////
        $schedule->command('backup:clean')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01:00')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->environments([ 'production' ]);

        $schedule->command('backup:run')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01:15')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('logging.log_mail_address'))
            ->environments([ 'production' ]);

        $schedule->command('backup:monitor')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('01.30')
            ->withoutOverlapping()
            ->emailOutputOnFailure(config('logging.log_mail_address'))
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
            ->emailOutputOnFailure(config('logging.log_mail_address'));

        $schedule->command('model:prune', [ '--model' => [ HealthCheckResultHistoryItem::class ] ])
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('03:45')
            ->emailOutputOnFailure(config('logging.log_mail_address'));

        $schedule->command('telescope:prune')
            ->timezone('Europe/Istanbul')
            ->daily()
            ->at('04.00')
            ->emailOutputOnFailure(config('logging.log_mail_address'));

        // Password reset tokens that have expired will still be present within your database.
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();

        // To properly prune stale cache tag entries.
        $schedule->command('cache:prune-stale-tags')->hourly();
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
