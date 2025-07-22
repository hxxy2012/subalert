<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $data = [
            'dashboard' => $this->statisticsService->getDashboardData($userId),
            'subscription_types' => $this->statisticsService->getSubscriptionsByType($userId),
            'monthly_expenses' => $this->statisticsService->getMonthlyExpenseReport($userId),
            'expiring_soon' => $this->statisticsService->getExpiringSubscriptions(7, $userId),
        ];

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function dashboard()
    {
        $userId = Auth::id();
        $data = $this->statisticsService->getDashboardData($userId);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function subscriptions()
    {
        $userId = Auth::id();
        $data = $this->statisticsService->getSubscriptionStatistics($userId);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function reminders()
    {
        $userId = Auth::id();
        $data = $this->statisticsService->getReminderStatistics($userId);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function monthlyExpenses(Request $request)
    {
        $userId = Auth::id();
        $year = $request->get('year', now()->year);
        $data = $this->statisticsService->getMonthlyExpenseReport($userId, $year);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function expiringSubscriptions(Request $request)
    {
        $userId = Auth::id();
        $days = $request->get('days', 7);
        $data = $this->statisticsService->getExpiringSubscriptions($days, $userId);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ]);
    }
}