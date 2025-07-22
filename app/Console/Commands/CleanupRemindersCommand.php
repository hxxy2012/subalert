<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\ReminderLog;
use Illuminate\Console\Command;

class CleanupRemindersCommand extends Command
{
    protected $signature = 'reminders:cleanup {--days=30 : 清理多少天前的记录}';
    protected $description = '清理过期的提醒记录';

    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);

        $this->info("开始清理 {$days} 天前的提醒记录...");

        // 清理已读的提醒
        $reminderCount = Reminder::where('status', 'read')
            ->where('updated_at', '<', $date)
            ->delete();

        // 清理提醒日志
        $logCount = ReminderLog::where('created_at', '<', $date)->delete();

        $this->info("清理完成，删除了 {$reminderCount} 条提醒记录和 {$logCount} 条日志记录");

        return 0;
    }
}