@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>管理仪表盘</h1>
        <p>欢迎回来，{{ Auth::guard('admin')->user()->nickname }}！</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">总用户数</h5>
                <h2>{{ $userStats['total_users'] }}</h2>
                <small>活跃用户：{{ $userStats['active_users'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">总订阅数</h5>
                <h2>{{ $subscriptionStats['total_subscriptions'] }}</h2>
                <small>活跃订阅：{{ $subscriptionStats['active_subscriptions'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">待发送提醒</h5>
                <h2>{{ $reminderStats['pending_reminders'] }}</h2>
                <small>已发送：{{ $reminderStats['sent_reminders'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">今日新用户</h5>
                <h2>{{ $userStats['new_users_today'] }}</h2>
                <small>本月：{{ $userStats['new_users_this_month'] }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>即将到期的订阅</h5>
            </div>
            <div class="card-body">
                @if($expiringSubscriptions->count() > 0)
                    @foreach($expiringSubscriptions as $subscription)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $subscription->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $subscription->user->nickname }} - {{ $subscription->expire_at->format('Y-m-d') }}</small>
                            </div>
                            <div>
                                <span class="badge bg-warning">{{ $subscription->getDaysUntilExpiry() }}天</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">暂无即将到期的订阅</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>订阅类型分布</h5>
            </div>
            <div class="card-body">
                @if($subscriptionTypes->count() > 0)
                    @foreach($subscriptionTypes as $type)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $type->type }}</strong>
                            </div>
                            <div>
                                <span class="badge bg-primary">{{ $type->count }}</span>
                                <span class="badge bg-success">¥{{ number_format($type->total_price, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">暂无数据</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection