@extends('layouts.app')
@section('title', 'Chỉnh sửa người dùng')

@section('content')
@php($isSelf = $user->is(auth()->user()))
<div class="auth-wrap" style="max-width: 560px;">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Chỉnh sửa người dùng {{ $user->UserID }}</h1>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
            @csrf @method('PUT')
            @include('partials.field', ['name' => 'FullName', 'label' => 'Họ tên', 'value' => $user->FullName])
            @include('partials.field', ['name' => 'Email', 'label' => 'Email', 'type' => 'email', 'value' => $user->Email])
            @include('partials.field', ['name' => 'Phone', 'label' => 'Số điện thoại', 'type' => 'tel', 'value' => $user->Phone])
            @include('partials.field', ['name' => 'Address', 'label' => 'Địa chỉ', 'value' => $user->Address])

            <div class="mb-3">
                <label for="RoleID" class="form-label">Quyền</label>
                <select id="RoleID" name="RoleID" class="form-select @error('RoleID') is-invalid @enderror" @disabled($isSelf)>
                    @foreach($roles as $role)
                        <option value="{{ $role->RoleID }}" @selected(old('RoleID', $user->RoleID) === $role->RoleID)>{{ $role->label }}</option>
                    @endforeach
                </select>
                @error('RoleID')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($isSelf)<div class="form-text">Bạn không thể tự đổi quyền của chính mình.</div>@endif
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label">Trạng thái</label>
                <select id="Status" name="Status" class="form-select @error('Status') is-invalid @enderror" @disabled($isSelf)>
                    <option value="1" @selected((string) old('Status', (int) $user->Status) === '1')>Hoạt động</option>
                    <option value="0" @selected((string) old('Status', (int) $user->Status) === '0')>Đã khóa</option>
                </select>
                @error('Status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if($isSelf)
                {{-- select bị disabled sẽ không được gửi đi; gửi giá trị hiện tại để qua bước kiểm tra dữ liệu --}}
                <input type="hidden" name="RoleID" value="{{ $user->RoleID }}">
                <input type="hidden" name="Status" value="{{ (int) $user->Status }}">
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div></div>
</div>
@endsection
