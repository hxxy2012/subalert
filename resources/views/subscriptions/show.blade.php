@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ $subscription->name }}</h5>
                <div>
                    <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-primary btn-sm">编辑</a>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary btn-sm">返回</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($subscription->icon)
                            <div class="text-center mb-3">
                                <img src="{{ Storage::url($subscription->icon) }}" alt="图标" class="img-thumbnail" style="max-width: 100px;">
                            </div>
                        @endif
                        
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">订阅名称：</th>
                                <td>{{ $subscription->name }}</td>
                            </tr>
                            <tr>
                                <th>服务类型：</th>
                                <td><span class="badge bg-secondary">{{ $subscription->type_display }}</span></td>
                            </tr>
                            <tr>
                                <th>订阅价格：</th>
                                <td class="text-success"><strong>¥{{ $subscription->price }}</strong></td>
                            </tr>
                            <tr>
                                <th>订阅周期：</th>
                                <td><span class="badge bg-info">{{ $subscription->cycle_display }}</span></td>
                            </tr>
                            <tr>
                                <th>到期时间：</th>
                                <td>
                                    {{ $subscription->expire_at->format('Y-m-d') }}
                                    @if($subscription->isExpiring(7))
                                        <span class="badge bg-warning ms-2">即将到期</span>
                                    @elseif($subscription->isExpired())
                                        <span class="badge bg-danger ms-2">已过期</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>订阅状态：</th>
                                <td>
                                    <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'expired' ? 'danger' : 'warning') }}">
                                        {{ $subscription->status_display }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>自动续费：</th>
                                <td>
                                    @if($subscription->auto_renew)
                                        <span class="badge bg-success">是</span>
                                    @else
                                        <span class="badge bg-secondary">否</span>
                                    @endif
                                </td>
                            </tr>
                            @if($subscription->account_info)
                                <tr>
                                    <th>账户信息：</th>
                                    <td>{{ $subscription->account_info }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>创建时间：</th>
                                <td>{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>更新时间：</th>
                                <td>{{ $subscription->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                        
                        @if($subscription->note)
                            <div class="mt-3">
                                <h6>备注信息：</h6>
                                <p class="text-muted">{{ $subscription->note }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h6>操作</h6>
                        <div class="d-grid gap-2">
                            @if($subscription->isExpiring(30) || $subscription->isExpired())
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#renewModal">
                                    <i class="bi bi-arrow-repeat"></i> 续费
                                </button>
                            @endif
                            
                            <a href="{{ route('reminders.create', ['subscription_id' => $subscription->id]) }}" class="btn btn-info">
                                <i class="bi bi-bell-fill"></i> 添加提醒
                            </a>
                            
                            <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-primary">
                                <i class="bi bi-pencil-fill"></i> 编辑订阅
                            </a>
                            
                            <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}" 
                                  onsubmit="return confirm('确定要删除这个订阅吗？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash-fill"></i> 删除订阅
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>相关提醒</h6>
            </div>
            <div class="card-body">
                @if($subscription->reminders->count() > 0)
                    @foreach($subscription->reminders as $reminder)
                        <div class="mb-2 p-2 border rounded">
                            <div class="d-flex justify-content-between">
                                <small>提前{{ $reminder->remind_days }}天</small>
                                <span class="badge bg-{{ $reminder->status === 'pending' ? 'warning' : ($reminder->status === 'sent' ? 'success' : 'secondary') }}">
                                    {{ $reminder->status }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">{{ $reminder->remind_at->format('Y-m-d H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">暂无提醒设置</p>
                    <a href="{{ route('reminders.create', ['subscription_id' => $subscription->id]) }}" class="btn btn-sm btn-primary">
                        添加提醒
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 续费模态框 -->
@if($subscription->isExpiring(30) || $subscription->isExpired())
    <div class="modal fade" id="renewModal" tabindex="-1">
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
                            <label for="expire_at" class="form-label">新的到期时间</label>
                            <input type="date" class="form-control" id="expire_at" name="expire_at" 
                                   required min="{{ now()->format('Y-m-d') }}"
                                   value="{{ $subscription->expire_at->addYear()->format('Y-m-d') }}">
                        </div>
                        <div class="alert alert-info">
                            <small>当前到期时间：{{ $subscription->expire_at->format('Y-m-d') }}</small>
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
@endsection