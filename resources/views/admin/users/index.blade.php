@extends('layouts.app')
@section('title', 'Quản lý người dùng')

@section('content')
<h1 class="page-title">Quản lý người dùng</h1>

<form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
    <div class="col-12 col-md-5">
        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
               placeholder="Tìm theo mã, họ tên, email hoặc số điện thoại" aria-label="Tìm kiếm người dùng">
    </div>
    <div class="col-6 col-md-2">
        <select name="role" class="form-select" aria-label="Lọc theo quyền">
            <option value="">Tất cả quyền</option>
            @foreach($roles as $role)
                <option value="{{ $role->RoleID }}" @selected(request('role') === $role->RoleID)>{{ $role->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-2">
        <select name="status" class="form-select" aria-label="Lọc theo trạng thái">
            <option value="">Mọi trạng thái</option>
            <option value="1" @selected(request('status') === '1')>Hoạt động</option>
            <option value="0" @selected(request('status') === '0')>Đã khóa</option>
        </select>
    </div>
    <div class="col-12 col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Mã</th><th>Họ tên</th><th>Email</th><th>Số điện thoại</th>
                    <th>Quyền</th><th>Trạng thái</th><th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
                @php($isSelf = $u->is(auth()->user()))
                <tr>
                    <td>{{ $u->UserID }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @include('partials.avatar', ['user' => $u, 'size' => 32])
                            <span>{{ $u->FullName }}@if($isSelf) <span class="text-muted">(bạn)</span>@endif</span>
                        </div>
                    </td>
                    <td>{{ $u->Email }}</td>
                    <td>{{ $u->Phone }}</td>
                    <td>{{ optional($u->role)->label }}</td>
                    <td>
                        @if($u->Status)
                            <span class="badge text-bg-success">Hoạt động</span>
                        @else
                            <span class="badge text-bg-danger">Đã khóa</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-secondary">Xem</a>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                        @unless($isSelf)
                            <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="d-inline"
                                  onsubmit="return confirm('{{ $u->Status ? 'Khóa' : 'Mở khóa' }} tài khoản này?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $u->Status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    {{ $u->Status ? 'Khóa' : 'Mở khóa' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline"
                                  onsubmit="return confirm('Xóa vĩnh viễn tài khoản này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Không tìm thấy người dùng nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links('pagination::bootstrap-5') }}</div>
@endsection
