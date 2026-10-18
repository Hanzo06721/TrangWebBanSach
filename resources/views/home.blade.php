@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
<section class="hero">
    <h1>Sách đã đọc xong, trao lại cho người cần đọc.</h1>
    <p class="lead mt-3 mb-4" style="max-width: 52ch;">
        Mua bán, trao đổi, ký gửi và quyên góp sách trực tuyến.
    </p>
    @guest
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Tạo tài khoản</a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Đăng nhập</a>
    @else
        <p class="mb-3">Xin chào <strong>{{ auth()->user()->FullName }}</strong>.</p>
        <a href="{{ route('profile.show') }}" class="btn btn-primary">Xem hồ sơ cá nhân</a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Quản lý người dùng</a>
        @endif
    @endguest
</section>
@endsection
