@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>添加订阅</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('subscriptions.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">订阅名称 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">服务类型 <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">请选择类型</option>
                                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>视频</option>
                                    <option value="music" {{ old('type') == 'music' ? 'selected' : '' }}>音乐</option>
                                    <option value="software" {{ old('type') == 'software' ? 'selected' : '' }}>软件</option>
                                    <option value="communication" {{ old('type') == 'communication' ? 'selected' : '' }}>通讯</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>其他</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">订阅价格 <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">¥</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cycle" class="form-label">订阅周期 <span class="text-danger">*</span></label>
                                <select class="form-select @error('cycle') is-invalid @enderror" id="cycle" name="cycle" required>
                                    <option value="">请选择周期</option>
                                    <option value="monthly" {{ old('cycle') == 'monthly' ? 'selected' : '' }}>月付</option>
                                    <option value="quarterly" {{ old('cycle') == 'quarterly' ? 'selected' : '' }}>季付</option>
                                    <option value="yearly" {{ old('cycle') == 'yearly' ? 'selected' : '' }}>年付</option>
                                    <option value="custom" {{ old('cycle') == 'custom' ? 'selected' : '' }}>自定义</option>
                                </select>
                                @error('cycle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="expire_at" class="form-label">到期时间 <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('expire_at') is-invalid @enderror" 
                               id="expire_at" name="expire_at" value="{{ old('expire_at') }}" 
                               min="{{ now()->format('Y-m-d') }}" required>
                        @error('expire_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon" class="form-label">服务图标</label>
                        <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" accept="image/*">
                        <small class="form-text text-muted">支持 JPEG、PNG、JPG、GIF 格式，最大 2MB</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="account_info" class="form-label">账户信息</label>
                        <input type="text" class="form-control @error('account_info') is-invalid @enderror" 
                               id="account_info" name="account_info" value="{{ old('account_info') }}" 
                               placeholder="例如：用户名、邮箱等">
                        @error('account_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">备注信息</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="3">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_renew" name="auto_renew" 
                                   {{ old('auto_renew') ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_renew">
                                自动续费
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">取消</a>
                        <button type="submit" class="btn btn-primary">保存订阅</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection