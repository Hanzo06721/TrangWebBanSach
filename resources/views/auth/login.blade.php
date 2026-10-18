@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
<div class="auth-wrap">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Đăng nhập</h1>
        <form method="POST" action="{{ route('login.store') }}" novalidate>
            @csrf
            @include('partials.field', ['name' => 'Email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
            @include('partials.field', ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'current-password'])
            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <p class="text-center mt-3 mb-0">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
    </div></div>
</div>
@endsection
