@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>添加提醒</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reminders.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="subscription_id" class="form-label">选择订阅 <span class="text-danger">*</span></label>
                        <select class="form-select @error('subscription_id') is-invalid @enderror" 
                                id="subscription_id" name="subscription_id" required>
                            <option value="">请选择订阅</option>
                            @foreach($subscriptions as $subscription)
                                <option value="{{ $subscription->id }}" 
                                        {{ old('subscription_id', $subscriptionId) == $subscription->id ? 'selected' : '' }}>
                                    {{ $subscription->name }} - 到期时间：{{ $subscription->expire_at->format('Y-m-d') }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="remind_days" class="form-label">提前提醒天数 <span class="text-danger">*</span></label>
                        <select class="form-select @error('remind_days') is-invalid @enderror" 
                                id="remind_days" name="remind_days" required>
                            <option value="1" {{ old('remind_days') == '1' ? 'selected' : '' }}>1天</option>
                            <option value="3" {{ old('remind_days') == '3' ? 'selected' : '' }}>3天</option>
                            <option value="7" {{ old('remind_days', '7') == '7' ? 'selected' : '' }}>7天</option>
                            <option value="14" {{ old('remind_days') == '14' ? 'selected' : '' }}>14天</option>
                            <option value="30" {{ old('remind_days') == '30' ? 'selected' : '' }}>30天</option>
                        </select>
                        @error('remind_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">提醒方式 <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email" name="remind_type[]" value="email" 
                                           {{ in_array('email', old('remind_type', ['email'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email">
                                        <i class="bi bi-envelope"></i> 邮件提醒
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feishu" name="remind_type[]" value="feishu"
                                           {{ in_array('feishu', old('remind_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feishu">
                                        <i class="bi bi-chat-dots"></i> 飞书提醒
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="wechat" name="remind_type[]" value="wechat"
                                           {{ in_array('wechat', old('remind_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="wechat">
                                        <i class="bi bi-wechat"></i> 企业微信提醒
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="system" name="remind_type[]" value="system"
                                           {{ in_array('system', old('remind_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="system">
                                        <i class="bi bi-bell"></i> 站内消息
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('remind_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('reminders.index') }}" class="btn btn-secondary">取消</a>
                        <button type="submit" class="btn btn-primary">保存提醒</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection