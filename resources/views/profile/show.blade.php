@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@section('content')
<h1 class="page-title">Hồ sơ cá nhân</h1>
<div class="card"><div class="card-body p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        @include('partials.avatar', ['user' => $user, 'size' => 72])
        <div>
            <div class="fs-5 fw-semibold">{{ $user->FullName }}</div>
            <span class="badge text-bg-secondary">{{ optional($user->role)->label }}</span>
        </div>
    </div>
    <dl class="row info-list mb-4">
        <dt class="col-sm-3">Mã người dùng</dt><dd class="col-sm-9">{{ $user->UserID }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $user->Email }}</dd>
        <dt class="col-sm-3">Số điện thoại</dt><dd class="col-sm-9">{{ $user->Phone }}</dd>
        <dt class="col-sm-3">Địa chỉ</dt><dd class="col-sm-9">{{ $user->Address ?: 'Chưa cập nhật' }}</dd>
        <dt class="col-sm-3">Ngày tạo</dt><dd class="col-sm-9">{{ optional($user->CreatedAt)->format('d/m/Y H:i') }}</dd>
    </dl>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
        <a href="{{ route('profile.avatar') }}" class="btn btn-outline-primary">Đổi ảnh đại diện</a>
        <a href="{{ route('profile.password') }}" class="btn btn-outline-primary">Đổi mật khẩu</a>
    </div>
</div></div>
@endsection
