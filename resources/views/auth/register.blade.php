@extends('layouts.app')
@section('title', 'Đăng ký')

@section('content')
<div class="auth-wrap">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Đăng ký tài khoản</h1>
        <form method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf
            @include('partials.field', ['name' => 'FullName', 'label' => 'Họ tên', 'autocomplete' => 'name'])
            @include('partials.field', ['name' => 'Email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
            @include('partials.field', ['name' => 'Phone', 'label' => 'Số điện thoại', 'type' => 'tel', 'autocomplete' => 'tel', 'placeholder' => '0901234567'])
            @include('partials.field', ['name' => 'password', 'label' => 'Mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password', 'hint' => 'Tối thiểu 6 ký tự.'])
            @include('partials.field', ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu', 'type' => 'password', 'autocomplete' => 'new-password'])
            <button type="submit" class="btn btn-primary w-100">Tạo tài khoản</button>
        </form>
        <p class="text-center mt-3 mb-0">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
    </div></div>
</div>
@endsection
