@php
    $currentRoute = request()->routeIs('register') ? 'register' : (request()->routeIs('password.request') ? 'forgot' : 'login');
    $activeMode = old('_mode', $initialMode ?? $currentRoute);
    if ($errors->has('FullName') || $errors->has('Phone') || $errors->has('password_confirmation')) {
        $activeMode = 'register';
    } elseif (request()->routeIs('password.request') || session('_mode') === 'forgot' || ($errors->has('Email') && old('_mode') === 'forgot')) {
        $activeMode = 'forgot';
    }
    // Nếu có lỗi validation hoặc session thông báo, tự động mở sẵn cuốn sách
    $isOpen = $errors->any() || session('status') || session('error') || session('success');
@endphp

@push('styles')
    <link href="{{ asset('css/book-auth.css') }}" rel="stylesheet">
@endpush

<div class="book-scene">
    <div class="interactive-book {{ $isOpen ? 'is-open' : 'is-closed' }}" id="authBook">
        
        <!-- ==========================================
             1. BÌA SÁCH KHI ĐÓNG (Closed Book Cover)
             ========================================== -->
        <div class="book-cover-wrap" id="bookCover" title="Nhấp để mở sách">
            <div class="book-ribbon"></div>
            <div class="cover-inner-border">
                <div class="cover-badge-icon">📖</div>
                
                <div>
                    <h1 class="cover-main-title">Trao Đổi Sách</h1>
                    <p class="cover-tagline">Cánh Cổng Tri Thức & Kết Nối Độc Giả</p>
                    <div class="cover-ornament">✦ ✦ ✦</div>
                </div>

                <div>
                    <p class="cover-footer-note">Cộng Đồng Yêu Sách Việt Nam</p>
                    <div class="cover-open-trigger">
                        <span>📖 Nhấp vào bìa để mở sách</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             2. NỘI DUNG 2 TRANG KHI SÁCH MỞ (Open Spread)
             ========================================== -->
        <div class="book-inside-spread" id="bookSpread">
            
            <!-- TRANG TRÁI (Trang chào mừng & Điều hướng) -->
            <div class="book-page book-page-left">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="book-badge">Cộng đồng đọc sách</span>
                    <span class="corner-ornament">❦</span>
                </div>

                <div>
                    <h2 class="left-page-title" id="leftTitle">
                        @if($activeMode === 'register')
                            Gia Nhập Cùng Chúng Tôi
                        @elseif($activeMode === 'forgot')
                            Khôi Phục Mật Khẩu
                        @else
                            Chào Mừng Trở Lại!
                        @endif
                    </h2>
                    <p class="left-page-desc" id="leftDesc">
                        @if($activeMode === 'register')
                            Mở tài khoản để đăng bán, trao đổi sách cũ hoặc tìm kiếm những tựa sách yêu thích giá rẻ.
                        @elseif($activeMode === 'forgot')
                            Đừng lo lắng, hãy nhập email tài khoản của bạn để nhận liên kết thiết lập lại mật khẩu mới an toàn.
                        @else
                            Đăng nhập vào tài khoản của bạn để quản lý bài đăng, kết nối và trao đổi sách cùng mọi người.
                        @endif
                    </p>

                    <div class="book-quote-card">
                        <span>“Một cuốn sách hay trên giá sách là một người bạn dù năm tháng trôi qua vẫn luôn đợi ta mở ra.”</span>
                        <span class="book-quote-author">— Khuyết danh</span>
                    </div>

                    <!-- Nút chuyển Tab Đăng nhập / Đăng ký -->
                    <div class="book-tab-switch">
                        <button type="button" 
                                class="book-tab-btn {{ $activeMode === 'login' ? 'active' : '' }}" 
                                id="btnTabLogin" 
                                onclick="switchAuthMode('login')">
                            Đăng nhập
                        </button>
                        <button type="button" 
                                class="book-tab-btn {{ $activeMode === 'register' ? 'active' : '' }}" 
                                id="btnTabRegister" 
                                onclick="switchAuthMode('register')">
                            Đăng ký
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn-close-book" id="btnCloseBook" title="Gấp sách lại">
                        <span>✕ Khép sách lại</span>
                    </button>
                    <span class="page-number">Trang I</span>
                </div>
            </div>

            <!-- RÃNH GÁY SÁCH Ở GIỮA -->
            <div class="book-gutter-shadow"></div>

            <!-- TRANG PHẢI (Form Đăng nhập, Đăng ký hoặc Quên mật khẩu) -->
            <div class="book-page book-page-right">
                <div class="d-flex justify-content-end">
                    <span class="corner-ornament">❦</span>
                </div>

                <div class="right-page-body">
                    
                    {{-- Thông báo chung --}}
                    @if (session('status'))
                        <div class="alert alert-success py-2 small mb-3">
                            {!! session('status') !!}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success py-2 small mb-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger py-2 small mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- ==============================================
                         FORM 1: ĐĂNG NHẬP
                         ============================================== -->
                    <div class="auth-pane {{ $activeMode === 'login' ? 'active' : '' }}" id="paneLogin">
                        <div class="right-form-header">
                            <h2>Đăng Nhập</h2>
                            <p>Nhập thông tin tài khoản để truy cập hệ thống</p>
                        </div>

                        <form method="POST" action="{{ route('login.store') }}" novalidate>
                            @csrf
                            <input type="hidden" name="_mode" value="login">

                            <div class="mb-3">
                                <label for="login_email" class="form-label">Email tài khoản</label>
                                <input type="email" 
                                       id="login_email" 
                                       name="Email" 
                                       value="{{ old('Email') }}" 
                                       class="form-control @if($activeMode === 'login') @error('Email') is-invalid @enderror @endif" 
                                       placeholder="ví dụ: ban@gmail.com"
                                       autocomplete="email" 
                                       required>
                                @if($activeMode === 'login')
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="login_password" class="form-label mb-0">Mật khẩu</label>
                                    <a href="javascript:void(0)" onclick="switchAuthMode('forgot')" class="small text-muted text-decoration-none">
                                        Quên mật khẩu?
                                    </a>
                                </div>
                                <div class="input-group">
                                    <input type="password" 
                                           id="login_password" 
                                           name="password" 
                                           class="form-control @if($activeMode === 'login') @error('password') is-invalid @enderror @endif" 
                                           placeholder="••••••••"
                                           autocomplete="current-password" 
                                           required>
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-toggle-password" 
                                            data-target="login_password" 
                                            tabindex="-1"
                                            title="Hiện/Ẩn mật khẩu">
                                        <span class="eye-icon">👁️</span>
                                    </button>
                                </div>
                                @if($activeMode === 'login')
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                                Đăng nhập
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="small text-muted">Chưa có tài khoản? </span>
                            <a href="javascript:void(0)" onclick="switchAuthMode('register')" class="small fw-bold">
                                Đăng ký ngay
                            </a>
                        </div>
                    </div>

                    <!-- ==============================================
                         FORM 2: ĐĂNG KÝ
                         ============================================== -->
                    <div class="auth-pane {{ $activeMode === 'register' ? 'active' : '' }}" id="paneRegister">
                        <div class="right-form-header">
                            <h2>Tạo Tài Khoản</h2>
                            <p>Điền thông tin để đăng ký thành viên mới</p>
                        </div>

                        <form method="POST" action="{{ route('register.store') }}" novalidate>
                            @csrf
                            <input type="hidden" name="_mode" value="register">

                            <div class="mb-2">
                                <label for="reg_fullname" class="form-label">Họ và tên</label>
                                <input type="text" 
                                       id="reg_fullname" 
                                       name="FullName" 
                                       value="{{ old('FullName') }}" 
                                       class="form-control @error('FullName') is-invalid @enderror" 
                                       placeholder="Nguyễn Văn A"
                                       autocomplete="name" 
                                       required>
                                @error('FullName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="reg_email" class="form-label">Email</label>
                                <input type="email" 
                                       id="reg_email" 
                                       name="Email" 
                                       value="{{ old('Email') }}" 
                                       class="form-control @if($activeMode === 'register') @error('Email') is-invalid @enderror @endif" 
                                       placeholder="ban@gmail.com"
                                       autocomplete="email" 
                                       required>
                                @if($activeMode === 'register')
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <div class="mb-2">
                                <label for="reg_phone" class="form-label">Số điện thoại</label>
                                <input type="tel" 
                                       id="reg_phone" 
                                       name="Phone" 
                                       value="{{ old('Phone') }}" 
                                       class="form-control @error('Phone') is-invalid @enderror" 
                                       placeholder="0901234567"
                                       autocomplete="tel" 
                                       required>
                                @error('Phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label for="reg_password" class="form-label">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               id="reg_password" 
                                               name="password" 
                                               class="form-control @if($activeMode === 'register') @error('password') is-invalid @enderror @endif" 
                                               placeholder="Tối thiểu 6 ký tự"
                                               autocomplete="new-password" 
                                               required>
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-toggle-password" 
                                                data-target="reg_password" 
                                                tabindex="-1"
                                                title="Hiện/Ẩn mật khẩu">
                                            <span class="eye-icon">👁️</span>
                                        </button>
                                    </div>
                                    @if($activeMode === 'register')
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="reg_password_confirmation" class="form-label">Nhập lại</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               id="reg_password_confirmation" 
                                               name="password_confirmation" 
                                               class="form-control" 
                                               placeholder="Xác nhận lại"
                                               autocomplete="new-password" 
                                               required>
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-toggle-password" 
                                                data-target="reg_password_confirmation" 
                                                tabindex="-1"
                                                title="Hiện/Ẩn mật khẩu">
                                            <span class="eye-icon">👁️</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                                Tạo tài khoản
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="small text-muted">Đã có tài khoản? </span>
                            <a href="javascript:void(0)" onclick="switchAuthMode('login')" class="small fw-bold">
                                Đăng nhập ngay
                            </a>
                        </div>
                    </div>

                    <!-- ==============================================
                         FORM 3: QUÊN MẬT KHẨU
                         ============================================== -->
                    <div class="auth-pane {{ $activeMode === 'forgot' ? 'active' : '' }}" id="paneForgot">
                        <div class="right-form-header">
                            <h2>Khôi Phục Mật Khẩu</h2>
                            <p>Nhập email để nhận liên kết đặt lại mật khẩu mới</p>
                        </div>

                        <form method="POST" action="{{ route('password.email') }}" novalidate>
                            @csrf
                            <input type="hidden" name="_mode" value="forgot">

                            <div class="mb-3">
                                <label for="forgot_email" class="form-label">Email tài khoản</label>
                                <input type="email" 
                                       id="forgot_email" 
                                       name="Email" 
                                       value="{{ old('Email') }}" 
                                       class="form-control @if($activeMode === 'forgot') @error('Email') is-invalid @enderror @endif" 
                                       placeholder="ban@gmail.com"
                                       autocomplete="email" 
                                       required>
                                @if($activeMode === 'forgot')
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                                Gửi Liên Kết Đặt Lại Mật Khẩu
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="javascript:void(0)" onclick="switchAuthMode('login')" class="small text-muted text-decoration-none">
                                ← Quay lại trang Đăng nhập
                            </a>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end align-items-center mt-2">
                    <span class="page-number">Trang II</span>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    const authBook = document.getElementById('authBook');
    const bookCover = document.getElementById('bookCover');
    const btnCloseBook = document.getElementById('btnCloseBook');
    const paneLogin = document.getElementById('paneLogin');
    const paneRegister = document.getElementById('paneRegister');
    const paneForgot = document.getElementById('paneForgot');
    const btnTabLogin = document.getElementById('btnTabLogin');
    const btnTabRegister = document.getElementById('btnTabRegister');
    const leftTitle = document.getElementById('leftTitle');
    const leftDesc = document.getElementById('leftDesc');

    // Mở sách khi người dùng click vào bìa
    if (bookCover) {
        bookCover.addEventListener('click', function () {
            authBook.classList.remove('is-closed');
            authBook.classList.add('is-open');
        });
    }

    // Đóng sách lại khi người dùng bấm nút Khép sách
    if (btnCloseBook) {
        btnCloseBook.addEventListener('click', function (e) {
            e.stopPropagation();
            authBook.classList.remove('is-open');
            authBook.classList.add('is-closed');
        });
    }

    // Chuyển đổi qua lại giữa Đăng nhập, Đăng ký và Quên mật khẩu trên trang sách
    function switchAuthMode(mode) {
        paneLogin.classList.remove('active');
        paneRegister.classList.remove('active');
        paneForgot.classList.remove('active');
        btnTabLogin.classList.remove('active');
        btnTabRegister.classList.remove('active');

        if (mode === 'register') {
            paneRegister.classList.add('active');
            btnTabRegister.classList.add('active');
            if (leftTitle) leftTitle.textContent = 'Gia Nhập Cùng Chúng Tôi';
            if (leftDesc) leftDesc.textContent = 'Mở tài khoản để đăng bán, trao đổi sách cũ hoặc tìm kiếm những tựa sách yêu thích giá rẻ.';
            window.history.replaceState(null, '', '{{ route('register') }}');
            document.title = 'Đăng ký - Trao Đổi Sách';
        } else if (mode === 'forgot') {
            paneForgot.classList.add('active');
            if (leftTitle) leftTitle.textContent = 'Khôi Phục Mật Khẩu';
            if (leftDesc) leftDesc.textContent = 'Đừng lo lắng, hãy nhập email tài khoản của bạn để nhận liên kết thiết lập lại mật khẩu mới an toàn.';
            window.history.replaceState(null, '', '{{ route('password.request') }}');
            document.title = 'Khôi phục mật khẩu - Trao Đổi Sách';
        } else {
            paneLogin.classList.add('active');
            btnTabLogin.classList.add('active');
            if (leftTitle) leftTitle.textContent = 'Chào Mừng Trở Lại!';
            if (leftDesc) leftDesc.textContent = 'Đăng nhập vào tài khoản của bạn để quản lý bài đăng, kết nối và trao đổi sách cùng mọi người.';
            window.history.replaceState(null, '', '{{ route('login') }}');
            document.title = 'Đăng nhập - Trao Đổi Sách';
        }
    }
</script>
@endpush
