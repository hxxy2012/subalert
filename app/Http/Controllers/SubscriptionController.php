<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->subscriptions();

        // 搜索
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 类型筛选
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 状态筛选
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 排序
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $subscriptions = $query->paginate(15);

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('subscriptions.create');
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
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['auto_renew'] = $request->has('auto_renew');

        // 处理图标上传
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('icons', 'public');
        }

        $subscription = Subscription::create($validated);

        // 创建默认提醒
        Reminder::create([
            'user_id' => Auth::id(),
            'subscription_id' => $subscription->id,
            'remind_days' => 7,
            'remind_type' => 'email',
            'remind_at' => $subscription->expire_at->subDays(7),
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', '订阅添加成功');
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        
        return view('subscriptions.edit', compact('subscription'));
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
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['auto_renew'] = $request->has('auto_renew');

        // 处理图标上传
        if ($request->hasFile('icon')) {
            // 删除旧图标
            if ($subscription->icon) {
                Storage::disk('public')->delete($subscription->icon);
            }
            $validated['icon'] = $request->file('icon')->store('icons', 'public');
        }

        $subscription->update($validated);

        // 更新提醒时间
        $subscription->reminders()->update([
            'remind_at' => $subscription->expire_at->subDays(7),
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', '订阅更新成功');
    }

    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);

        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', '订阅删除成功');
    }

    public function renew(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $request->validate([
            'expire_at' => 'required|date|after:today',
        ]);

        $subscription->update([
            'expire_at' => $request->expire_at,
            'status' => 'active',
        ]);

        // 更新提醒
        $subscription->reminders()->update([
            'remind_at' => $subscription->expire_at->subDays(7),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', '续费成功');
    }
}