@extends('layouts.customer')
@section('content')
<div class="container" style="max-width: 400px; margin-top: 60px;">
    <h2 class="mb-4 fw-bold text-center">Đăng nhập</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Nhớ tôi</label>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}" class="text-decoration-none">Chưa có tài khoản? Đăng ký</a>
        </div>
    </form>
</div>
@endsection 