<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'totalSubscriptions' => $user->getActiveSubscriptionsCount(),
            'monthlyExpense' => $user->getTotalMonthlyExpense(),
            'expiringSubscriptions' => $user->getExpiringSubscriptions(7),
            'recentSubscriptions' => $user->subscriptions()
                ->latest()
                ->take(5)
                ->get(),
            'reminderCount' => $user->reminders()
                ->where('status', 'pending')
                ->count(),
        ];

        return view('dashboard', $data);
    }
}