@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>订阅管理</h1>
    <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> 添加订阅
    </a>
</div>

<!-- 搜索和筛选 -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('subscriptions.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="搜索订阅名称" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">全部类型</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>视频</option>
                        <option value="music" {{ request('type') == 'music' ? 'selected' : '' }}>音乐</option>
                        <option value="software" {{ request('type') == 'software' ? 'selected' : '' }}>软件</option>
                        <option value="communication" {{ request('type') == 'communication' ? 'selected' : '' }}>通讯</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>其他</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>正常</option>
                        <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>暂停</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>已取消</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>已过期</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>创建时间</option>
                        <option value="expire_at" {{ request('sort') == 'expire_at' ? 'selected' : '' }}>到期时间</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>价格</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>名称</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="order" class="form-select">
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>降序</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>升序</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">搜索</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-secondary">重置</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 订阅列表 -->
<div class="row">
    @forelse($subscriptions as $subscription)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">{{ $subscription->name }}</h5>
                        <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'expired' ? 'danger' : 'warning') }}">
                            {{ $subscription->status_display }}
                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $subscription->type_display }}</span>
                        <span class="badge bg-info">{{ $subscription->cycle_display }}</span>
                    </div>
                    
                    <p class="card-text">
                        <strong>价格：</strong>¥{{ $subscription->price }}<br>
                        <strong>到期时间：</strong>{{ $subscription->expire_at->format('Y-m-d') }}<br>
                        @if($subscription->isExpiring(7))
                            <span class="text-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                {{ $subscription->getDaysUntilExpiry() }}天后到期
                            </span>
                        @elseif($subscription->isExpired())
                            <span class="text-danger">
                                <i class="bi bi-x-circle"></i> 已过期
                            </span>
                        @endif
                    </p>
                    
                    @if($subscription->note)
                        <p class="card-text"><small class="text-muted">{{ Str::limit($subscription->note, 50) }}</small></p>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-outline-primary btn-sm">查看</a>
                        <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-outline-success btn-sm">编辑</a>
                        @if($subscription->isExpiring(30) || $subscription->isExpired())
                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#renewModal{{ $subscription->id }}">
                                续费
                            </button>
                        @endif
                        <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}" class="d-inline" 
                              onsubmit="return confirm('确定要删除这个订阅吗？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">删除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 续费模态框 -->
        @if($subscription->isExpiring(30) || $subscription->isExpired())
            <div class="modal fade" id="renewModal{{ $subscription->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('subscriptions.renew', $subscription) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">续费 - {{ $subscription->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="expire_at{{ $subscription->id }}" class="form-label">新的到期时间</label>
                                    <input type="date" class="form-control" id="expire_at{{ $subscription->id }}" 
                                           name="expire_at" required min="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">确认续费</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5>暂无订阅记录</h5>
                    <p class="text-muted">开始添加您的第一个订阅吧！</p>
                    <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> 添加订阅
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- 分页 -->
<div class="d-flex justify-content-center">
    {{ $subscriptions->appends(request()->query())->links() }}
</div>
@endsection