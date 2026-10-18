@extends('layouts.app')
@section('title', 'Ảnh đại diện')

@section('content')
<div class="auth-wrap">
    <div class="card"><div class="card-body p-4">
        <h1 class="page-title">Ảnh đại diện</h1>
        <div class="mb-3 text-center">@include('partials.avatar', ['user' => $user, 'size' => 120])</div>
        <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="mb-3">
                <label for="avatar" class="form-label">Chọn ảnh mới</label>
                <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp"
                       class="form-control @error('avatar') is-invalid @enderror">
                @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">JPG, PNG hoặc WEBP, tối đa 2MB.</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Cập nhật ảnh</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
        @if($user->avatar_url)
            <form method="POST" action="{{ route('profile.avatar.destroy') }}" class="mt-3"
                  onsubmit="return confirm('Xóa ảnh đại diện hiện tại?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-link text-danger p-0">Xóa ảnh đại diện</button>
            </form>
        @endif
    </div></div>
</div>
@endsection
