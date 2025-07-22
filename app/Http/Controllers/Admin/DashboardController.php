<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->middleware('admin');
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $data = [
            'userStats' => $this->statisticsService->getUserStatistics(),
            'subscriptionStats' => $this->statisticsService->getSubscriptionStatistics(),
            'reminderStats' => $this->statisticsService->getReminderStatistics(),
            'expiringSubscriptions' => $this->statisticsService->getExpiringSubscriptions(7),
            'subscriptionTypes' => $this->statisticsService->getSubscriptionsByType(),
        ];

        return view('admin.dashboard', $data);
    }
}