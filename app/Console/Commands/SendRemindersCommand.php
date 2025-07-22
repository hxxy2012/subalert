<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendRemindersCommand extends Command
{
    protected $signature = 'reminders:send';
    protected $description = '发送待发送的提醒';

    public function handle(ReminderService $reminderService)
    {
        $this->info('开始发送提醒...');

        $count = $reminderService->sendPendingReminders();

        $this->info("提醒发送完成，共发送 {$count} 条提醒");

        return 0;
    }
}