@extends('layouts.app')
@section('title', 'Trang chủ')

@push('styles')
<style>
    /* =========================================================
       HERO THEME & HIỆU ỨNG ĐỒNG BỘ MÀU SẮC TRANG ĐĂNG NHẬP
       ========================================================= */
    .home-hero-themed {
        position: relative;
        background: radial-gradient(circle at 70% 30%, #1e5946 0%, #154536 50%, #0d2f24 100%);
        border-radius: 1.25rem;
        padding: clamp(2.5rem, 5vw, 4.5rem) clamp(1.5rem, 4vw, 3.5rem);
        color: #fff;
        overflow: hidden;
        box-shadow: 
            0 20px 45px rgba(11, 41, 31, 0.35),
            inset 0 0 0 1px rgba(212, 175, 55, 0.25);
        margin-bottom: 2.5rem;
    }

    /* Viền chỉ vàng sang trọng đồng bộ phong cách bìa sách */
    .home-hero-themed::before {
        content: '';
        position: absolute;
        top: 12px;
        bottom: 12px;
        left: 12px;
        right: 12px;
        border: 1px solid rgba(212, 175, 55, 0.25);
        border-radius: 0.9rem;
        pointer-events: none;
    }

    /* Hiệu ứng bụi vàng / đốm sáng lơ lửng */
    .home-hero-themed::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(1.5px 1.5px at 40px 60px, #d4af37, rgba(0,0,0,0)),
            radial-gradient(2px 2px at 150px 180px, #fff4d1, rgba(0,0,0,0)),
            radial-gradient(1.5px 1.5px at 280px 120px, #f7e8ba, rgba(0,0,0,0)),
            radial-gradient(2px 2px at 420px 290px, #e5c158, rgba(0,0,0,0)),
            radial-gradient(1.5px 1.5px at 600px 90px, #fff, rgba(0,0,0,0)),
            radial-gradient(2px 2px at 750px 240px, #d4af37, rgba(0,0,0,0));
        background-repeat: repeat;
        background-size: 800px 400px;
        opacity: 0.25;
        animation: float-sparkles 40s linear infinite;
        pointer-events: none;
    }

    @keyframes float-sparkles {
        0% { transform: translate(0, 0); }
        50% { transform: translate(-30px, -20px); }
        100% { transform: translate(0, 0); }
    }

    .hero-gold-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(212, 175, 55, 0.15);
        border: 1px solid rgba(212, 175, 55, 0.45);
        color: #f7e8ba;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        margin-bottom: 1rem;
        backdrop-filter: blur(4px);
    }

    .hero-main-heading {
        font-size: clamp(2rem, 4.5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        color: #fffaf0;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
        margin-bottom: 1rem;
    }

    .hero-main-heading .gold-text {
        color: #f3ce63;
        text-shadow: 0 0 16px rgba(243, 206, 99, 0.35);
    }

    .hero-lead-text {
        font-size: 1.05rem;
        color: #d1e5dd;
        line-height: 1.6;
        max-width: 580px;
        margin-bottom: 1.75rem;
    }

    /* Thanh tìm kiếm phong cách trang giấy thư viện */
    .hero-search-paper {
        display: flex;
        background: #fdfbf7;
        border: 2px solid #d4af37;
        border-radius: 0.75rem;
        padding: 0.35rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        max-width: 600px;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .hero-search-paper:focus-within {
        border-color: #f1c953;
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        transform: translateY(-2px);
    }

    .hero-search-paper .form-control {
        border: none;
        background: transparent;
        color: #1a3026;
        font-size: 0.95rem;
        padding: 0.6rem 0.85rem;
        box-shadow: none;
    }

    .hero-search-paper .form-control::placeholder {
        color: #7d8c85;
    }

    .hero-search-paper .btn-search-submit {
        background: linear-gradient(135deg, #1d5a47, #134032);
        color: #f7e8ba;
        font-weight: 700;
        border: 1px solid rgba(212, 175, 55, 0.4);
        border-radius: 0.5rem;
        padding: 0 1.5rem;
        transition: all 0.25s ease;
    }

    .hero-search-paper .btn-search-submit:hover {
        background: linear-gradient(135deg, #257059, #174b3b);
        color: #fff;
        transform: scale(1.02);
    }

    /* Các nút phân loại dạng thẻ sách */
    .hero-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .hero-pill-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #e3ede8;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .hero-pill-link:hover {
        background: #d4af37;
        color: #0d3025;
        border-color: #d4af37;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.35);
    }

    /* =========================================================
       SÂN KHẤU 3 CUỐN SÁCH 3D TƯƠNG TÁC (MUA BÁN, TRAO ĐỔI, CHIA SẺ)
       ========================================================= */
    .books-3d-stage {
        perspective: 1400px;
        position: relative;
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Base style cho cả 3 cuốn sách */
    .realistic-book-card {
        position: absolute;
        width: 215px;
        height: 295px;
        border-radius: 6px 14px 14px 6px;
        padding: 1.5rem 1.25rem;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        transform-style: preserve-3d;
        transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
        user-select: none;
    }

    /* Khung viền mạ vàng trên bìa sách */
    .realistic-book-card .book-foil-border {
        position: absolute;
        top: 12px;
        bottom: 12px;
        left: 24px;
        right: 12px;
        border: 1.5px solid rgba(212, 175, 55, 0.7);
        border-radius: 4px;
        padding: 1rem 0.85rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        pointer-events: none;
    }

    /* Gáy sách nổi vân dày */
    .realistic-book-card::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 22px;
        background: linear-gradient(to right, rgba(0,0,0,0.55) 0%, rgba(255,255,255,0.15) 45%, rgba(0,0,0,0.4) 100%);
        border-radius: 6px 0 0 6px;
        border-right: 1px solid rgba(212, 175, 55, 0.35);
    }

    /* Hiệu ứng độ dày các trang giấy bên mép */
    .realistic-book-card::after {
        content: '';
        position: absolute;
        top: 4px;
        bottom: 4px;
        right: -6px;
        width: 6px;
        background: repeating-linear-gradient(to bottom, #ede5d7, #ede5d7 2px, #dfd6c6 2px, #dfd6c6 4px);
        border-radius: 0 4px 4px 0;
        box-shadow: 2px 0 5px rgba(0,0,0,0.25);
    }

    /* Dải ruy băng bookmark dập nổi */
    .book-ribbon-tag {
        position: absolute;
        top: -6px;
        right: 28px;
        width: 16px;
        height: 60px;
        clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%);
        box-shadow: 2px 4px 8px rgba(0,0,0,0.4);
        z-index: 5;
    }

    /* CUỐN 1: MUA - BÁN (Cuốn chính ở giữa - Bìa da xanh lục bảo) */
    .book-card-main {
        background: radial-gradient(circle at 40% 30%, #29775f 0%, #1a4f3e 50%, #0e3528 100%);
        left: 45%;
        top: 25px;
        transform: translateX(-50%) rotateY(-8deg) rotateZ(-3deg);
        z-index: 3;
        box-shadow: 
            -8px 0 18px rgba(0,0,0,0.4),
            6px 6px 0 #ede5d7,
            12px 12px 0 #dfd6c6,
            18px 25px 40px rgba(0,0,0,0.45);
    }
    .book-card-main .book-ribbon-tag {
        background: #a92323;
    }
    .book-card-main:hover {
        transform: translateX(-50%) translateY(-15px) rotateY(0deg) scale(1.05);
        z-index: 10;
        box-shadow: 
            -12px 0 24px rgba(0,0,0,0.45),
            8px 8px 0 #ede5d7,
            16px 16px 0 #dfd6c6,
            24px 35px 50px rgba(0,0,0,0.55);
    }

    /* CUỐN 2: TRAO - ĐỔI (Cuốn bên trái - Bìa da nâu vàng hổ phách) */
    .book-card-side {
        background: radial-gradient(circle at 40% 30%, #b88628 0%, #875c13 55%, #503306 100%);
        left: 2%;
        top: 40px;
        transform: rotateY(15deg) rotateZ(-12deg);
        z-index: 2;
        box-shadow: 
            -6px 0 15px rgba(0,0,0,0.35),
            4px 4px 0 #ede5d7,
            8px 8px 0 #dfd6c6,
            14px 20px 35px rgba(0,0,0,0.4);
    }
    .book-card-side .book-ribbon-tag {
        background: #174b3b;
    }
    .book-card-side:hover {
        transform: translateY(-14px) rotateY(0deg) rotateZ(-4deg) scale(1.06);
        z-index: 10;
        box-shadow: 
            -10px 0 20px rgba(0,0,0,0.4),
            6px 6px 0 #ede5d7,
            12px 12px 0 #dfd6c6,
            20px 30px 45px rgba(0,0,0,0.5);
    }

    /* CUỐN 3: CHIA - SẺ (Cuốn bên phải - Bìa da đỏ mận cổ điển) */
    .book-card-small {
        background: radial-gradient(circle at 40% 30%, #943a34 0%, #6e2420 55%, #42100d 100%);
        right: 4%;
        bottom: 10px;
        width: 190px;
        height: 260px;
        transform: rotateY(-18deg) rotateZ(10deg);
        z-index: 1;
        box-shadow: 
            -5px 0 12px rgba(0,0,0,0.3),
            4px 4px 0 #ede5d7,
            8px 8px 0 #dfd6c6,
            12px 18px 30px rgba(0,0,0,0.35);
    }
    .book-card-small .book-ribbon-tag {
        background: #d4af37;
    }
    .book-card-small:hover {
        transform: translateY(-14px) rotateY(0deg) rotateZ(3deg) scale(1.06);
        z-index: 10;
        box-shadow: 
            -10px 0 20px rgba(0,0,0,0.4),
            6px 6px 0 #ede5d7,
            12px 12px 0 #dfd6c6,
            20px 30px 45px rgba(0,0,0,0.5);
    }

    /* Nội dung dập chữ mạ kim trên 3 cuốn sách */
    .book-type-badge {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #f7e8ba;
        opacity: 0.9;
    }

    .book-action-word {
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1.1;
        color: #fff9e6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5), 0 0 10px rgba(212, 175, 55, 0.35);
        margin-top: 0.2rem;
    }

    .book-sub-hint {
        font-size: 0.7rem;
        color: #e5cf96;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    @media (max-width: 991.98px) {
        .books-3d-stage {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<section class="home-hero-themed" id="homeHero">
    <div class="row align-items-center g-4">
        
        <!-- CỘT TRÁI: TIÊU ĐỀ, TÌM KIẾM & NÚT ĐIỀU HƯỚNG -->
        <div class="col-lg-7">
            <span class="hero-gold-tag">
                ✦ Thị Trường Sách Cộng Đồng ✦
            </span>
            <h1 class="hero-main-heading">
                Tìm cuốn sách tiếp theo <br>
                <span class="gold-text">cho hành trình của bạn.</span>
            </h1>
            <p class="hero-lead-text">
                Mua sách chất lượng, trao đổi tri thức quý giá hoặc gửi gắm cuốn sách còn hay đến tay người thực sự cần đọc.
            </p>

            <!-- Thanh tìm kiếm trang giấy viền vàng mạ kim -->
            <form class="hero-search-paper" method="GET" action="{{ route('posts.index') }}">
                <input class="form-control" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Tìm tên sách, tác giả hoặc tiêu đề..." 
                       aria-label="Tìm kiếm sách">
                <button class="btn btn-search-submit" type="submit">
                    Tìm kiếm
                </button>
            </form>

            <!-- Lối tắt nhanh -->
            <div class="hero-pills">
                <a class="hero-pill-link" href="{{ route('posts.index', ['post_type' => 'Bán']) }}">
                    <span>🏷️</span> Mua sách
                </a>
                <a class="hero-pill-link" href="{{ route('posts.index', ['post_type' => 'Trao đổi']) }}">
                    <span>🔄</span> Trao đổi
                </a>
                <a class="hero-pill-link" href="{{ route('posts.index', ['post_type' => 'Ký gửi']) }}">
                    <span>📦</span> Ký gửi
                </a>
                <a class="hero-pill-link" href="{{ route('posts.index', ['post_type' => 'Quyên góp']) }}">
                    <span>🎁</span> Quyên góp
                </a>
                <a class="hero-pill-link" href="{{ route('posts.forum') }}">
                    <span>💬</span> Diễn đàn
                </a>
            </div>
        </div>

        <!-- CỘT PHẢI: 3 CUỐN SÁCH BÌA DA 3D TƯƠNG TÁC -->
        <div class="col-lg-5">
            <div class="books-3d-stage" id="booksStage" aria-hidden="true">
                
                <!-- Cuốn 2: TRAO ĐỔI (Bên trái, da nâu vàng) -->
                <div class="realistic-book-card book-card-side" 
                     onclick="window.location.href='{{ route('posts.index', ['post_type' => 'Trao đổi']) }}'"
                     title="Khám phá sách Trao Đổi">
                    <div class="book-ribbon-tag"></div>
                    <div class="book-foil-border">
                        <div class="book-type-badge">Tri Thức</div>
                        <div>
                            <div class="book-action-word">Trao Đổi</div>
                            <div class="book-sub-hint">Đổi sách cùng đọc &rarr;</div>
                        </div>
                    </div>
                </div>

                <!-- Cuốn 1: MUA BÁN (Cuốn chính ở giữa, da xanh ngọc lục bảo) -->
                <div class="realistic-book-card book-card-main" 
                     onclick="window.location.href='{{ route('posts.index', ['post_type' => 'Bán']) }}'"
                     title="Khám phá sách Mua Bán">
                    <div class="book-ribbon-tag"></div>
                    <div class="book-foil-border">
                        <div class="book-type-badge">Tiết Kiệm</div>
                        <div>
                            <div class="book-action-word">Mua Bán</div>
                            <div class="book-sub-hint">Sách hay giá tốt &rarr;</div>
                        </div>
                    </div>
                </div>

                <!-- Cuốn 3: CHIA SẺ (Bên phải, da đỏ mận) -->
                <div class="realistic-book-card book-card-small" 
                     onclick="window.location.href='{{ route('posts.index', ['post_type' => 'Quyên góp']) }}'"
                     title="Khám phá sách Quyên Góp / Ký Gửi">
                    <div class="book-ribbon-tag"></div>
                    <div class="book-foil-border">
                        <div class="book-type-badge">Lan Tỏa</div>
                        <div>
                            <div class="book-action-word">Chia Sẻ</div>
                            <div class="book-sub-hint">Ký gửi & Tặng &rarr;</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    // Hiệu ứng tương tác nghiêng 3D nhẹ theo chuột trên sân khấu sách
    const hero = document.getElementById('homeHero');
    const stage = document.getElementById('booksStage');

    if (hero && stage) {
        hero.addEventListener('mousemove', function (e) {
            const rect = hero.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            stage.style.transform = `rotateY(${x * 12}deg) rotateX(${-y * 10}deg)`;
        });

        hero.addEventListener('mouseleave', function () {
            stage.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });
    }
</script>
@endpush
