<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SendRemindersCommand::class,
        Commands\CleanupRemindersCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // 每小时检查并发送提醒
        $schedule->command('reminders:send')->hourly();
        
        // 每天凌晨清理过期记录
        $schedule->command('reminders:cleanup')->dailyAt('01:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}