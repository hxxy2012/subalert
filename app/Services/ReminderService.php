<?php

namespace App\Services;

use App\Models\Reminder;
use App\Models\ReminderLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReminderService
{
    public function sendPendingReminders()
    {
        $reminders = Reminder::with(['user', 'subscription'])
            ->where('status', 'pending')
            ->where('is_active', true)
            ->where('remind_at', '<=', now())
            ->get();

        foreach ($reminders as $reminder) {
            $this->sendReminder($reminder);
        }

        return $reminders->count();
    }

    public function sendReminder(Reminder $reminder)
    {
        $types = explode(',', $reminder->remind_type);
        $success = false;

        foreach ($types as $type) {
            try {
                switch ($type) {
                    case 'email':
                        $this->sendEmailReminder($reminder);
                        break;
                    case 'feishu':
                        $this->sendFeishuReminder($reminder);
                        break;
                    case 'wechat':
                        $this->sendWechatReminder($reminder);
                        break;
                    case 'system':
                        $this->sendSystemReminder($reminder);
                        break;
                }

                $this->logReminder($reminder, $type, 'success');
                $success = true;

            } catch (\Exception $e) {
                $this->logReminder($reminder, $type, 'failed', $e->getMessage());
                Log::error('提醒发送失败', [
                    'reminder_id' => $reminder->id,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($success) {
            $reminder->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }
    }

    protected function sendEmailReminder(Reminder $reminder)
    {
        $data = [
            'user' => $reminder->user,
            'subscription' => $reminder->subscription,
            'daysLeft' => $reminder->subscription->getDaysUntilExpiry(),
        ];

        Mail::send('emails.reminder', $data, function ($message) use ($reminder) {
            $message->to($reminder->user->email)
                ->subject('订阅到期提醒 - ' . $reminder->subscription->name);
        });
    }

    protected function sendFeishuReminder(Reminder $reminder)
    {
        $webhookUrl = config('app.feishu_webhook_url');
        
        if (!$webhookUrl) {
            throw new \Exception('飞书 Webhook URL 未配置');
        }

        $message = $this->buildReminderMessage($reminder);

        $response = Http::post($webhookUrl, [
            'msg_type' => 'text',
            'content' => [
                'text' => $message,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('飞书消息发送失败: ' . $response->body());
        }
    }

    protected function sendWechatReminder(Reminder $reminder)
    {
        $webhookUrl = config('app.wechat_webhook_url');
        
        if (!$webhookUrl) {
            throw new \Exception('企业微信 Webhook URL 未配置');
        }

        $message = $this->buildReminderMessage($reminder);

        $response = Http::post($webhookUrl, [
            'msgtype' => 'text',
            'text' => [
                'content' => $message,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('企业微信消息发送失败: ' . $response->body());
        }
    }

    protected function sendSystemReminder(Reminder $reminder)
    {
        // 这里可以实现站内消息功能
        // 暂时记录日志
        Log::info('系统提醒', [
            'user_id' => $reminder->user_id,
            'subscription' => $reminder->subscription->name,
            'expire_at' => $reminder->subscription->expire_at,
        ]);
    }

    protected function buildReminderMessage(Reminder $reminder)
    {
        $subscription = $reminder->subscription;
        $daysLeft = $subscription->getDaysUntilExpiry();

        return "【订阅到期提醒】\n" .
               "订阅服务：{$subscription->name}\n" .
               "到期时间：{$subscription->expire_at->format('Y-m-d')}\n" .
               "剩余天数：{$daysLeft}天\n" .
               "订阅价格：¥{$subscription->price}\n" .
               "请及时续费以免影响使用。";
    }

    protected function logReminder(Reminder $reminder, $type, $status, $errorMessage = null)
    {
        ReminderLog::create([
            'reminder_id' => $reminder->id,
            'type' => $type,
            'content' => $this->buildReminderMessage($reminder),
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }
}