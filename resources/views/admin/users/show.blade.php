@extends('layouts.app')
@section('title', 'Chi tiết người dùng')

@section('content')
@php($isSelf = $user->is(auth()->user()))
<h1 class="page-title">Chi tiết người dùng</h1>
<div class="card"><div class="card-body p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        @include('partials.avatar', ['user' => $user, 'size' => 72])
        <div>
            <div class="fs-5 fw-semibold">{{ $user->FullName }}</div>
            @if($user->Status)
                <span class="badge text-bg-success">Hoạt động</span>
            @else
                <span class="badge text-bg-danger">Đã khóa</span>
            @endif
        </div>
    </div>
    <dl class="row info-list mb-4">
        <dt class="col-sm-3">Mã người dùng</dt><dd class="col-sm-9">{{ $user->UserID }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $user->Email }}</dd>
        <dt class="col-sm-3">Số điện thoại</dt><dd class="col-sm-9">{{ $user->Phone }}</dd>
        <dt class="col-sm-3">Địa chỉ</dt><dd class="col-sm-9">{{ $user->Address ?: 'Chưa cập nhật' }}</dd>
        <dt class="col-sm-3">Quyền</dt><dd class="col-sm-9">{{ optional($user->role)->label }}</dd>
        <dt class="col-sm-3">Ngày tạo</dt><dd class="col-sm-9">{{ optional($user->CreatedAt)->format('d/m/Y H:i') }}</dd>
    </dl>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Chỉnh sửa</a>
        @unless($isSelf)
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                  onsubmit="return confirm('{{ $user->Status ? 'Khóa' : 'Mở khóa' }} tài khoản này?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $user->Status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                    {{ $user->Status ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                </button>
            </form>
        @endunless
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
    </div>
</div></div>
@endsection
