@extends('layouts.app')
@section('title', 'Quản lý quyền')

@section('content')
<h1 class="page-title">Quản lý quyền</h1>

<div class="row g-3 mb-4">
    @foreach($roles as $role)
        <div class="col-12 col-md-6">
            <div class="card h-100"><div class="card-body">
                <div class="fw-semibold fs-5">{{ $role->label }}</div>
                <div class="text-muted mb-2">Mã quyền: {{ $role->RoleID }} ({{ $role->RoleName }})</div>
                <div>{{ $role->users_count }} người dùng</div>
                <p class="small text-muted mb-0 mt-2">
                    @if($role->RoleName === 'Admin')
                        Quản lý người dùng, kiểm duyệt bài đăng và các chức năng quản trị.
                    @else
                        Đăng bài, mua bán, trao đổi, ký gửi, quyên góp, yêu thích và đánh giá.
                    @endif
                </p>
            </div></div>
        </div>
    @endforeach
</div>

<h2 class="h5 mb-3">Gán quyền cho người dùng</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Họ tên</th><th>Email</th><th>Quyền hiện tại</th><th style="min-width: 260px;">Đổi quyền</th></tr></thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->FullName }}</td>
                    <td>{{ $u->Email }}</td>
                    <td>{{ optional($u->role)->label }}</td>
                    <td>
                        @if($u->is(auth()->user()))
                            <span class="text-muted">Tài khoản của bạn</span>
                        @else
                            <form method="POST" action="{{ route('admin.roles.assign', $u) }}" class="d-flex gap-2"
                                  onsubmit="return confirm('Đổi quyền của người dùng này?')">
                                @csrf @method('PATCH')
                                <select name="RoleID" class="form-select form-select-sm" aria-label="Quyền của {{ $u->FullName }}">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->RoleID }}" @selected($u->RoleID === $role->RoleID)>{{ $role->label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links('pagination::bootstrap-5') }}</div>
@endsection
