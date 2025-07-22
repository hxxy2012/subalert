@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>用户管理</h1>
</div>

<!-- 搜索和筛选 -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="搜索用户昵称或邮箱" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>正常</option>
                        <option value="frozen" {{ request('status') == 'frozen' ? 'selected' : '' }}>冻结</option>
                        <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>已删除</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">搜索</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">重置</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 用户列表 -->
<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>用户信息</th>
                            <th>订阅数量</th>
                            <th>注册时间</th>
                            <th>最后登录</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $user->nickname }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $user->subscriptions_count }}</span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '从未登录' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $user->status === 'active' ? '正常' : '异常' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">查看</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">编辑</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                              onsubmit="return confirm('确定要删除这个用户吗？')">
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
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center">
                <h5>暂无用户数据</h5>
                <p class="text-muted">还没有用户注册</p>
            </div>
        @endif
    </div>
</div>
@endsection