@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>仪表盘</h1>
        <p>欢迎回来，{{ Auth::user()->nickname }}！</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">总订阅数</h5>
                <h2>{{ $totalSubscriptions }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">月度支出</h5>
                <h2>¥{{ number_format($monthlyExpense, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">即将到期</h5>
                <h2>{{ $expiringSubscriptions->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">待处理提醒</h5>
                <h2>{{ $reminderCount }}</h2>
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
                                <small class="text-muted">{{ $subscription->expire_at->format('Y-m-d') }} 到期</small>
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
                <h5>最近添加的订阅</h5>
            </div>
            <div class="card-body">
                @if($recentSubscriptions->count() > 0)
                    @foreach($recentSubscriptions as $subscription)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $subscription->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $subscription->created_at->format('Y-m-d') }} 添加</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">¥{{ $subscription->price }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">暂无订阅记录</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <h5>快速操作</h5>
                <a href="{{ route('subscriptions.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> 添加订阅
                </a>
                <a href="{{ route('reminders.create') }}" class="btn btn-success me-2">
                    <i class="bi bi-bell"></i> 设置提醒
                </a>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-info">
                    <i class="bi bi-list"></i> 查看所有订阅
                </a>
            </div>
        </div>
    </div>
</div>
@endsection