@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5>编辑个人资料</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="头像" class="img-thumbnail rounded-circle mb-3" style="width: 120px; height: 120px;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; margin: 0 auto;">
                                    <i class="bi bi-person text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div>
                                <label for="avatar" class="form-label">更换头像</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nickname" class="form-label">昵称</label>
                        <input type="text" class="form-control @error('nickname') is-invalid @enderror" 
                               id="nickname" name="nickname" value="{{ old('nickname', $user->nickname) }}" required>
                        @error('nickname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">手机号</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">取消</a>
                        <button type="submit" class="btn btn-primary">保存修改</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 修改密码 -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>修改密码</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">当前密码</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">新密码</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">确认新密码</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">修改密码</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection