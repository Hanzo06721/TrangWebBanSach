@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu')

@push('styles')
    <link href="{{ asset('css/book-auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="book-scene">
    <div class="interactive-book is-open" style="max-width: 520px; width: 100%;">
        <div class="book-inside-spread" style="min-height: auto;">
            <div class="book-page book-page-right w-100" style="border-radius: 8px; border: 1.5px solid #ded4c2; padding: 2rem 2.25rem;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="book-badge">Khôi phục bảo mật</span>
                    <span class="corner-ornament">❦</span>
                </div>

                <div class="right-form-header">
                    <h2>Đặt Lại Mật Khẩu</h2>
                    <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success py-2 small mb-3">
                        {!! session('status') !!}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="reset_email" class="form-label">Email tài khoản</label>
                        <input type="email" 
                               id="reset_email" 
                               name="Email" 
                               value="{{ old('Email', $email) }}" 
                               class="form-control @error('Email') is-invalid @enderror" 
                               readonly
                               required>
                        @error('Email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reset_password" class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" 
                                   id="reset_password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Tối thiểu 6 ký tự"
                                   autocomplete="new-password" 
                                   required>
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-toggle-password" 
                                    data-target="reset_password" 
                                    tabindex="-1"
                                    title="Hiện/Ẩn mật khẩu">
                                <span class="eye-icon">👁️</span>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reset_password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" 
                                   id="reset_password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Xác nhận lại mật khẩu mới"
                                   autocomplete="new-password" 
                                   required>
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-toggle-password" 
                                    data-target="reset_password_confirmation" 
                                    tabindex="-1"
                                    title="Hiện/Ẩn mật khẩu">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                        Lưu Mật Khẩu Mới
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="small text-muted text-decoration-none">
                        ← Quay lại trang Đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
