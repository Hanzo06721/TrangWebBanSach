<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang chủ') - Trao Đổi Sách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Trao Đổi Sách</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Quản trị</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Quản lý danh mục</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.books.index') }}">Quản lý sách</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.posts.index') }}">Kiểm duyệt bài đăng</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.post-reports.index') }}">Báo cáo bài đăng</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.comments.index') }}">Kiểm duyệt bình luận</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Quản lý người dùng</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">Quản lý quyền</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Bài đăng</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('posts.forum') }}">Diễn đàn</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('posts.create') }}">Đăng bài</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('posts.mine') }}">Bài của tôi</a></li>
                    @endif
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Bài đăng</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.forum') }}">Diễn đàn</a></li>
                @endauth
            </ul>
            <ul class="navbar-nav align-items-md-center">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                    <li class="nav-item ms-md-2"><a class="btn btn-light btn-sm" href="{{ route('register') }}">Đăng ký</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @include('partials.avatar', ['user' => auth()->user(), 'size' => 30])
                            <span>{{ auth()->user()->FullName }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.password') }}">Đổi mật khẩu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @include('partials.alerts')
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <strong>Trao Đổi Sách</strong>
                <div class="small">Mua bán, trao đổi và chia sẻ sách trong cộng đồng.</div>
            </div>
            <div class="d-flex flex-wrap gap-3 small">
                @guest
                    <a href="{{ route('posts.index') }}">Bài đăng</a>
                    <a href="{{ route('posts.forum') }}">Diễn đàn</a>
                    <a href="{{ route('login') }}">Đăng nhập</a>
                @else
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}">Người dùng</a>
                        <a href="{{ route('admin.posts.index') }}">Kiểm duyệt</a>
                    @else
                        <a href="{{ route('posts.index') }}">Bài đăng</a>
                        <a href="{{ route('posts.forum') }}">Diễn đàn</a>
                        <a href="{{ route('posts.mine') }}">Bài của tôi</a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle hiện / ẩn mật khẩu toàn hệ thống
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-toggle-password');
    if (!btn) return;
    e.preventDefault();
    const targetId = btn.dataset.target;
    let input = targetId ? document.getElementById(targetId) : null;
    if (!input) {
        input = btn.closest('.input-group')?.querySelector('input');
    }
    if (!input) return;

    const icon = btn.querySelector('.eye-icon') || btn;
    if (input.type === 'password') {
        input.type = 'text';
        if (icon.classList.contains('eye-icon')) icon.textContent = '🙈';
        btn.setAttribute('title', 'Ẩn mật khẩu');
    } else {
        input.type = 'password';
        if (icon.classList.contains('eye-icon')) icon.textContent = '👁️';
        btn.setAttribute('title', 'Hiện mật khẩu');
    }
});
</script>
@stack('scripts')
</body>
</html>
