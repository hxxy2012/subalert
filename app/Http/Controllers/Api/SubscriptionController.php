<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->subscriptions();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $subscriptions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:video,music,software,communication,other',
            'price' => 'required|numeric|min:0',
            'cycle' => 'required|in:monthly,quarterly,yearly,custom',
            'expire_at' => 'required|date|after:today',
            'auto_renew' => 'boolean',
            'note' => 'nullable|string',
            'account_info' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['auto_renew'] = $request->boolean('auto_renew');

        $subscription = Subscription::create($validated);

        return response()->json([
            'code' => 200,
            'message' => '订阅创建成功',
            'data' => $subscription
        ]);
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $subscription->load('reminders')
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:video,music,software,communication,other',
            'price' => 'required|numeric|min:0',
            'cycle' => 'required|in:monthly,quarterly,yearly,custom',
            'expire_at' => 'required|date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:active,paused,cancelled,expired',
            'note' => 'nullable|string',
            'account_info' => 'nullable|string',
        ]);

        $validated['auto_renew'] = $request->boolean('auto_renew');

        $subscription->update($validated);

        return response()->json([
            'code' => 200,
            'message' => '订阅更新成功',
            'data' => $subscription
        ]);
    }

    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);

        $subscription->delete();

        return response()->json([
            'code' => 200,
            'message' => '订阅删除成功'
        ]);
    }
}