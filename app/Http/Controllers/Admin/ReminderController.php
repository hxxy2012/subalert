<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\ReminderLog;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Reminder::with(['user', 'subscription']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('remind_type', 'like', '%' . $request->type . '%');
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nickname', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhereHas('subscription', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $reminders = $query->orderBy('remind_at', 'desc')->paginate(20);

        return view('admin.reminders.index', compact('reminders'));
    }

    public function show(Reminder $reminder)
    {
        $reminder->load(['user', 'subscription', 'logs']);
        
        return view('admin.reminders.show', compact('reminder'));
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return redirect()->route('admin.reminders.index')
            ->with('success', '提醒删除成功');
    }
}