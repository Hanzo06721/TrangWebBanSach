@extends('layouts.app')
@section('title', 'Đổi mật khẩu')

@section('content')
<div class="auth-wrap">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Đổi mật khẩu</h1>
        <form method="POST" action="{{ route('profile.password.update') }}" novalidate>
            @csrf @method('PUT')
            @include('partials.field', ['name' => 'current_password', 'label' => 'Mật khẩu hiện tại', 'type' => 'password', 'autocomplete' => 'current-password'])
            @include('partials.field', ['name' => 'password', 'label' => 'Mật khẩu mới', 'type' => 'password', 'autocomplete' => 'new-password', 'hint' => 'Tối thiểu 6 ký tự, khác mật khẩu hiện tại.'])
            @include('partials.field', ['name' => 'password_confirmation', 'label' => 'Nhập lại mật khẩu mới', 'type' => 'password', 'autocomplete' => 'new-password'])
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div></div>
</div>
@endsection
