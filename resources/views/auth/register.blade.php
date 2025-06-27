@extends('layouts.customer')
@section('content')
<div class="container" style="max-width: 400px; margin-top: 60px;">
    <h2 class="mb-4 fw-bold text-center">Đăng ký</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
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
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">Đã có tài khoản? Đăng nhập</a>
        </div>
    </form>
</div>
@endsection 