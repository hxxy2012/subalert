<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->reminders()->with('subscription');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $reminders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $reminders
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'remind_type' => 'required|string',
            'remind_before_days' => 'required|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        $subscription = Auth::user()->subscriptions()->findOrFail($validated['subscription_id']);
        
        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['remind_at'] = $subscription->expire_at->subDays($validated['remind_before_days']);

        $reminder = Reminder::create($validated);

        return response()->json([
            'code' => 201,
            'message' => '提醒创建成功',
            'data' => $reminder->load('subscription')
        ], 201);
    }

    public function show(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => '无权访问'
            ], 403);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $reminder->load('subscription')
        ]);
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => '无权访问'
            ], 403);
        }

        $validated = $request->validate([
            'remind_type' => 'sometimes|string',
            'remind_before_days' => 'sometimes|integer|min:1|max:365',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['remind_before_days'])) {
            $validated['remind_at'] = $reminder->subscription->expire_at->subDays($validated['remind_before_days']);
        }

        $reminder->update($validated);

        return response()->json([
            'code' => 200,
            'message' => '提醒更新成功',
            'data' => $reminder->load('subscription')
        ]);
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => '无权访问'
            ], 403);
        }

        $reminder->delete();

        return response()->json([
            'code' => 200,
            'message' => '提醒删除成功'
        ]);
    }

    public function markAsRead(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            return response()->json([
                'code' => 403,
                'message' => '无权访问'
            ], 403);
        }

        $reminder->update([
            'status' => 'read',
            'read_at' => now()
        ]);

        return response()->json([
            'code' => 200,
            'message' => '提醒已标记为已读'
        ]);
    }
}