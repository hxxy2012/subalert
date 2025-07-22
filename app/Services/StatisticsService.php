<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Reminder;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function getUserStatistics($userId = null)
    {
        $query = $userId ? User::where('id', $userId) : User::query();

        return [
            'total_users' => $query->count(),
            'active_users' => $query->where('status', 'active')->count(),
            'new_users_today' => $query->whereDate('created_at', today())->count(),
            'new_users_this_month' => $query->whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function getSubscriptionStatistics($userId = null)
    {
        $query = $userId ? Subscription::where('user_id', $userId) : Subscription::query();

        return [
            'total_subscriptions' => $query->count(),
            'active_subscriptions' => $query->where('status', 'active')->count(),
            'expired_subscriptions' => $query->where('status', 'expired')->count(),
            'total_monthly_revenue' => $query->where('status', 'active')
                ->where('cycle', 'monthly')
                ->sum('price'),
            'average_subscription_price' => $query->where('status', 'active')->avg('price'),
        ];
    }

    public function getSubscriptionsByType($userId = null)
    {
        $query = $userId ? Subscription::where('user_id', $userId) : Subscription::query();

        return $query->select('type', DB::raw('count(*) as count'), DB::raw('sum(price) as total_price'))
            ->where('status', 'active')
            ->groupBy('type')
            ->get();
    }

    public function getMonthlyExpenseReport($userId, $year = null)
    {
        $year = $year ?? now()->year;

        $subscriptions = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->get();

        $monthlyExpenses = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyExpenses[$month] = 0;

            foreach ($subscriptions as $subscription) {
                switch ($subscription->cycle) {
                    case 'monthly':
                        $monthlyExpenses[$month] += $subscription->price;
                        break;
                    case 'quarterly':
                        if ($month % 3 === 1) {
                            $monthlyExpenses[$month] += $subscription->price;
                        }
                        break;
                    case 'yearly':
                        if ($month === 1) {
                            $monthlyExpenses[$month] += $subscription->price;
                        }
                        break;
                }
            }
        }

        return $monthlyExpenses;
    }

    public function getExpiringSubscriptions($days = 7, $userId = null)
    {
        $query = $userId ? Subscription::where('user_id', $userId) : Subscription::query();

        return $query->where('status', 'active')
            ->whereBetween('expire_at', [now(), now()->addDays($days)])
            ->with('user')
            ->get();
    }

    public function getReminderStatistics($userId = null)
    {
        $query = $userId ? Reminder::where('user_id', $userId) : Reminder::query();

        return [
            'total_reminders' => $query->count(),
            'pending_reminders' => $query->where('status', 'pending')->count(),
            'sent_reminders' => $query->where('status', 'sent')->count(),
            'read_reminders' => $query->where('status', 'read')->count(),
            'failed_reminders' => $query->where('status', 'failed')->count(),
        ];
    }

    public function getDashboardData($userId)
    {
        $user = User::find($userId);

        return [
            'subscriptions' => $this->getSubscriptionStatistics($userId),
            'monthly_expense' => $user->getTotalMonthlyExpense(),
            'expiring_soon' => $this->getExpiringSubscriptions(7, $userId)->count(),
            'reminders' => $this->getReminderStatistics($userId),
            'subscription_types' => $this->getSubscriptionsByType($userId),
            'monthly_expenses' => $this->getMonthlyExpenseReport($userId),
        ];
    }
}