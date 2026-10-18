@extends('layouts.app')
@section('title', 'Chỉnh sửa thông tin')

@section('content')
<div class="auth-wrap" style="max-width: 560px;">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Chỉnh sửa thông tin</h1>
        <form method="POST" action="{{ route('profile.update') }}" novalidate>
            @csrf @method('PUT')
            @include('partials.field', ['name' => 'FullName', 'label' => 'Họ tên', 'value' => $user->FullName])
            @include('partials.field', ['name' => 'Email', 'label' => 'Email', 'value' => $user->Email, 'disabled' => true, 'hint' => 'Email dùng để đăng nhập nên không thể thay đổi.'])
            @include('partials.field', ['name' => 'Phone', 'label' => 'Số điện thoại', 'type' => 'tel', 'value' => $user->Phone])
            @include('partials.field', ['name' => 'Address', 'label' => 'Địa chỉ', 'value' => $user->Address])
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div></div>
</div>
@endsection
