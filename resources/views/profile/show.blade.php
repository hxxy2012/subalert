@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>个人资料</h5>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">编辑资料</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="头像" class="img-thumbnail rounded-circle" style="width: 120px; height: 120px;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; margin: 0 auto;">
                                <i class="bi bi-person text-white" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">昵称：</th>
                                <td>{{ $user->nickname }}</td>
                            </tr>
                            <tr>
                                <th>邮箱：</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>手机号：</th>
                                <td>{{ $user->phone ?? '未设置' }}</td>
                            </tr>
                            <tr>
                                <th>注册时间：</th>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>最后登录：</th>
                                <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '从未登录' }}</td>
                            </tr>
                            <tr>
                                <th>账户状态：</th>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $user->status === 'active' ? '正常' : '异常' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary">{{ $user->getActiveSubscriptionsCount() }}</h3>
                                <p class="card-text">活跃订阅</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success">¥{{ number_format($user->getTotalMonthlyExpense(), 2) }}</h3>
                                <p class="card-text">月度支出</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-warning">{{ $user->getExpiringSubscriptions(7)->count() }}</h3>
                                <p class="card-text">即将到期</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection