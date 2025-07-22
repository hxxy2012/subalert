@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>提醒管理</h1>
    <a href="{{ route('reminders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> 添加提醒
    </a>
</div>

<!-- 筛选 -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reminders.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待发送</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>已发送</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>已读</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>发送失败</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">全部类型</option>
                        <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>邮件</option>
                        <option value="feishu" {{ request('type') == 'feishu' ? 'selected' : '' }}>飞书</option>
                        <option value="wechat" {{ request('type') == 'wechat' ? 'selected' : '' }}>企业微信</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>站内消息</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary">筛选</button>
                    <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary">重置</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 提醒列表 -->
<div class="card">
    <div class="card-body">
        @if($reminders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>订阅名称</th>
                            <th>提醒时间</th>
                            <th>提醒方式</th>
                            <th>状态</th>
                            <th>发送时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reminders as $reminder)
                            <tr>
                                <td>
                                    <strong>{{ $reminder->subscription->name }}</strong>
                                    <br>
                                    <small class="text-muted">到期：{{ $reminder->subscription->expire_at->format('Y-m-d') }}</small>
                                </td>
                                <td>{{ $reminder->remind_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @foreach($reminder->remind_type_array as $type)
                                        <span class="badge bg-secondary me-1">
                                            @switch($type)
                                                @case('email') 邮件 @break
                                                @case('feishu') 飞书 @break
                                                @case('wechat') 企业微信 @break
                                                @case('system') 站内消息 @break
                                                @default {{ $type }}
                                            @endswitch
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @switch($reminder->status)
                                        @case('pending')
                                            <span class="badge bg-warning">待发送</span>
                                            @break
                                        @case('sent')
                                            <span class="badge bg-success">已发送</span>
                                            @break
                                        @case('read')
                                            <span class="badge bg-info">已读</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">发送失败</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $reminder->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    {{ $reminder->sent_at ? $reminder->sent_at->format('Y-m-d H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('reminders.edit', $reminder) }}" class="btn btn-outline-primary">编辑</a>
                                        @if($reminder->status === 'sent')
                                            <form method="POST" action="{{ route('reminders.read', $reminder) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">标记已读</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('reminders.destroy', $reminder) }}" class="d-inline"
                                              onsubmit="return confirm('确定要删除这个提醒吗？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">删除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- 分页 -->
            <div class="d-flex justify-content-center">
                {{ $reminders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center">
                <h5>暂无提醒记录</h5>
                <p class="text-muted">开始添加您的第一个提醒吧！</p>
                <a href="{{ route('reminders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> 添加提醒
                </a>
            </div>
        @endif
    </div>
</div>
@endsection