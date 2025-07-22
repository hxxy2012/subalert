<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->reminders()->with('subscription');

        // 状态筛选
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 类型筛选
        if ($request->filled('type')) {
            $query->where('remind_type', 'like', '%' . $request->type . '%');
        }

        $reminders = $query->orderBy('remind_at', 'desc')->paginate(15);

        return view('reminders.index', compact('reminders'));
    }

    public function create(Request $request)
    {
        $subscriptionId = $request->get('subscription_id');
        $subscriptions = Auth::user()->subscriptions()
            ->where('status', 'active')
            ->get();

        return view('reminders.create', compact('subscriptions', 'subscriptionId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'remind_days' => 'required|integer|min:1|max:30',
            'remind_type' => 'required|array',
            'remind_type.*' => 'in:email,feishu,wechat,system',
        ]);

        $subscription = Auth::user()->subscriptions()->findOrFail($validated['subscription_id']);

        $reminder = Reminder::create([
            'user_id' => Auth::id(),
            'subscription_id' => $validated['subscription_id'],
            'remind_days' => $validated['remind_days'],
            'remind_type' => implode(',', $validated['remind_type']),
            'remind_at' => $subscription->expire_at->subDays($validated['remind_days']),
        ]);

        return redirect()->route('reminders.index')
            ->with('success', '提醒设置成功');
    }

    public function edit(Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        
        $subscriptions = Auth::user()->subscriptions()
            ->where('status', 'active')
            ->get();

        return view('reminders.edit', compact('reminder', 'subscriptions'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $validated = $request->validate([
            'remind_days' => 'required|integer|min:1|max:30',
            'remind_type' => 'required|array',
            'remind_type.*' => 'in:email,feishu,wechat,system',
            'is_active' => 'boolean',
        ]);

        $validated['remind_type'] = implode(',', $validated['remind_type']);
        $validated['is_active'] = $request->has('is_active');
        $validated['remind_at'] = $reminder->subscription->expire_at->subDays($validated['remind_days']);

        $reminder->update($validated);

        return redirect()->route('reminders.index')
            ->with('success', '提醒更新成功');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);

        $reminder->delete();

        return redirect()->route('reminders.index')
            ->with('success', '提醒删除成功');
    }

    public function markAsRead(Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $reminder->update(['status' => 'read']);

        return redirect()->back()->with('success', '提醒已标记为已读');
    }
}