@extends('layouts.app')

@php
    $type = request('post_type');
    $headerTitle = match($type) {
        'Bán' => 'Chợ Sách Cũ Giá Tốt',
        'Trao đổi' => 'Tủ Sách Trao Đổi Tri Thức',
        'Ký gửi' => 'Góc Sách Ký Gửi Độc Quyền',
        'Quyên góp' => 'Tủ Sách Quyên Góp & Chia Sẻ',
        default => 'Kho Sách Cộng Đồng'
    };

    $headerTag = match($type) {
        'Bán' => '🏷️ Sách Bán Giá Rẻ',
        'Trao đổi' => '🔄 Trao Đổi Sách Hay',
        'Ký gửi' => '📦 Hỗ Trợ Ký Gửi',
        'Quyên góp' => '🎁 Sách Tặng Miễn Phí',
        default => '📖 Đầy Đủ Thể Loại'
    };

    $headerDesc = match($type) {
        'Bán' => 'Tìm kiếm những cuốn sách hay với mức giá tiết kiệm từ cộng đồng độc giả.',
        'Trao đổi' => 'Đổi cuốn sách bạn đã đọc xong lấy một tác phẩm mới hấp dẫn từ bạn đọc khác.',
        'Ký gửi' => 'Những đầu sách được gửi gắm qua nền tảng, đảm bảo chất lượng và uy tín.',
        'Quyên góp' => 'Những cuốn sách được trao tặng miễn phí nhằm lan tỏa tri thức đến người cần.',
        default => 'Khám phá hàng ngàn tựa sách từ văn học, kinh tế, công nghệ đến kỹ năng sống.'
    };
@endphp

@section('title', $headerTitle)

@push('styles')
    <link href="{{ asset('css/posts-book.css') }}" rel="stylesheet">
@endpush

@section('content')

<!-- ========================================================
     1. BANNER TIÊU ĐỀ SANG TRỌNG
     ======================================================== -->
<section class="posts-header-banner">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="posts-header-tag">{{ $headerTag }}</span>
            <h1 class="posts-header-title">
                {{ $headerTitle }}
            </h1>
            <p class="posts-header-sub">
                {{ $headerDesc }}
            </p>
        </div>
        @auth
            @if(! auth()->user()->isAdmin())
                <div class="flex-shrink-0">
                    <a href="{{ route('posts.create') }}" class="btn-post-new">
                        <span>✍️</span> Đăng sách mới
                    </a>
                </div>
            @endif
        @else
            <div class="flex-shrink-0">
                <a href="{{ route('register') }}" class="btn-post-new">
                    <span>✨</span> Gia nhập cộng đồng
                </a>
            </div>
        @endauth
    </div>
</section>

<!-- ========================================================
     2. THANH CHUYỂN TABS PHÂN LOẠI NHANH
     ======================================================== -->
<nav class="post-type-tabs" aria-label="Bộ lọc loại bài đăng">
    <a href="{{ route('posts.index') }}" 
       class="post-tab-item {{ empty($type) ? 'active' : '' }}">
        <span>📚</span> Tất cả sách
    </a>
    <a href="{{ route('posts.index', ['post_type' => 'Bán']) }}" 
       class="post-tab-item {{ $type === 'Bán' ? 'active' : '' }}">
        <span>🏷️</span> Mua bán
    </a>
    <a href="{{ route('posts.index', ['post_type' => 'Trao đổi']) }}" 
       class="post-tab-item {{ $type === 'Trao đổi' ? 'active' : '' }}">
        <span>🔄</span> Trao đổi
    </a>
    <a href="{{ route('posts.index', ['post_type' => 'Ký gửi']) }}" 
       class="post-tab-item {{ $type === 'Ký gửi' ? 'active' : '' }}">
        <span>📦</span> Ký gửi
    </a>
    <a href="{{ route('posts.index', ['post_type' => 'Quyên góp']) }}" 
       class="post-tab-item {{ $type === 'Quyên góp' ? 'active' : '' }}">
        <span>🎁</span> Quyên góp (Tặng)
    </a>
</nav>

<!-- ========================================================
     3. THANH TÌM KIẾM & BỘ LỌC CHI TIẾT
     ======================================================== -->
<div class="filter-search-card">
    <form class="live-search-form" method="GET" action="{{ route('posts.index') }}">
        @if($type)
            <input type="hidden" name="post_type" value="{{ $type }}">
        @endif

        <div class="search-input-group">
            <input id="livePostSearch" 
                   class="search-input-field" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Tìm theo tên sách, tác giả hoặc tiêu đề bài đăng..." 
                   autocomplete="off">
            
            <button class="btn btn-primary px-4 fw-bold" type="submit" style="border-radius: 0.6rem;">
                Tìm kiếm
            </button>

            <button class="btn-filter-toggle" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#postFiltersDrawer" 
                    aria-expanded="false" 
                    aria-label="Mở bộ lọc chi tiết">
                <span>⚙️ Bộ lọc</span>
            </button>
        </div>

        <!-- Khung bộ lọc mở rộng -->
        <div class="collapse {{ (request('category') || request('condition') || request('sort') || request('min_price') || request('max_price')) ? 'show' : '' }}" id="postFiltersDrawer">
            <div class="filter-drawer-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <label class="form-label">Danh mục sách</label>
                        <select class="form-select live-filter" name="category">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->CategoryID }}" @selected(request('category') === $category->CategoryID)>
                                    {{ $category->CategoryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label">Tình trạng sách</label>
                        <select class="form-select live-filter" name="condition">
                            <option value="">Tất cả tình trạng</option>
                            @foreach(['Mới', 'Như mới', 'Tốt', 'Ổn', 'Cũ'] as $cond)
                                <option value="{{ $cond }}" @selected(request('condition') === $cond)>
                                    {{ $cond }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label">Sắp xếp theo</label>
                        <select class="form-select live-filter" name="sort">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label class="form-label">Khoảng giá (VNĐ)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" min="0" class="form-control" name="min_price" value="{{ request('min_price') }}" placeholder="Từ">
                            <span class="input-group-text">-</span>
                            <input type="number" min="0" class="form-control" name="max_price" value="{{ request('max_price') }}" placeholder="Đến">
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('posts.index', $type ? ['post_type' => $type] : []) }}" class="btn btn-outline-secondary btn-sm">
                            Xóa bộ lọc
                        </a>
                        <button class="btn btn-primary btn-sm px-3" type="submit">
                            Áp dụng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ========================================================
     4. LƯỚI THẺ SÁCH 3D (BOOK CARDS GRID)
     ======================================================== -->
<div class="row g-4" id="postResults">
@forelse($posts as $post)
    @php
        $primaryImg = $post->images->where('IsPrimary', true)->first() ?? $post->images->first();
        $coverSrc = $primaryImg 
            ? asset('storage/' . $primaryImg->ImagePath) 
            : ($post->books ? $post->books->cover_url : asset('images/book-placeholder.svg'));

        $ribbonClass = match($post->PostType) {
            'Bán' => 'ribbon-ban',
            'Trao đổi' => 'ribbon-traodoi',
            'Ký gửi' => 'ribbon-kygui',
            'Quyên góp' => 'ribbon-quyengop',
            default => 'ribbon-ban'
        };
    @endphp
    <div class="col-sm-6 col-md-4 col-lg-3">
        <article class="book-post-card">
            <!-- Khung ảnh bìa sách -->
            <div class="book-card-cover-wrap">
                <span class="ribbon-type-badge {{ $ribbonClass }}">
                    {{ $post->PostType }}
                </span>
                
                @if($post->Condition)
                    <span class="badge-condition-pill">
                        {{ $post->Condition }}
                    </span>
                @endif

                <a href="{{ route('posts.show', $post) }}" class="w-100 h-100 d-block">
                    <img src="{{ $coverSrc }}" 
                         alt="{{ $post->Title }}" 
                         loading="lazy" 
                         onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
                </a>
            </div>

            <!-- Nội dung thông tin sách -->
            <div class="book-card-info">
                <div>
                    <h2 class="book-post-heading" title="{{ $post->Title }}">
                        <a href="{{ route('posts.show', $post) }}">
                            {{ $post->Title }}
                        </a>
                    </h2>

                    <div class="book-meta-author">
                        <span>✍️</span>
                        <span>{{ $post->books->AuthorName ?? ($post->books->Title ?? 'Chưa rõ tác giả') }}</span>
                    </div>
                </div>

                <div>
                    <!-- Hàng hiển thị giá -->
                    <div class="book-card-price-row">
                        @if($post->PostType === 'Bán')
                            <span class="book-price-highlight">
                                {{ number_format((float) $post->Price, 0, ',', '.') }} đ
                            </span>
                        @elseif($post->PostType === 'Quyên góp')
                            <span class="book-price-free">
                                🎁 Miễn phí
                            </span>
                        @else
                            <span class="book-price-exchange">
                                🔄 {{ $post->PostType }}
                            </span>
                        @endif

                        @if($post->books && $post->books->category)
                            <small class="text-muted" style="font-size: 0.72rem;">
                                {{ $post->books->category->CategoryName }}
                            </small>
                        @endif
                    </div>

                    <!-- Nút hành động tương tác thông minh -->
                    @if($post->PostStatus === 'Đã duyệt')
                        @if($post->PostType === 'Bán')
                            @auth
                                @if(! auth()->user()->isAdmin() && auth()->user()->UserID !== $post->UserID)
                                    <form method="POST" action="{{ route('posts.purchase.store', $post) }}">
                                        @csrf
                                        <button class="btn-card-action btn-buy">
                                            🛒 Mua ngay
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-exchange">
                                        Xem chi tiết
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login">
                                    🔐 Đăng nhập để mua
                                </a>
                            @endauth
                        @elseif($post->PostType === 'Trao đổi')
                            @auth
                                <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-exchange">
                                    🔄 Xem & Trao đổi
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login">
                                    🔐 Đăng nhập để đổi
                                </a>
                            @endauth
                        @elseif($post->PostType === 'Quyên góp')
                            @auth
                                <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-donate">
                                    🎁 Nhận sách này
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login">
                                    🔐 Đăng nhập nhận sách
                                </a>
                            @endauth
                        @else
                            <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-exchange">
                                Xem chi tiết
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </article>
    </div>
@empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded border shadow-sm my-3">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">📖</div>
            <h3 class="h5 fw-bold text-dark">Chưa có bài đăng nào trong mục này</h3>
            <p class="text-muted small mb-3">Hãy thử thay đổi điều kiện lọc hoặc trở thành người đầu tiên đăng sách lên kệ!</p>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm px-3">
                    Đăng sách mới ngay
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">
                    Đăng nhập để đăng sách
                </a>
            @endauth
        </div>
    </div>
@endforelse
</div>

<!-- ========================================================
     5. PHÂN TRANG (PAGINATION)
     ======================================================== -->
<div class="d-flex justify-content-center mt-4 mb-5">
    {{ $posts->links() }}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.book-post-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            const tiltX = (y / rect.height) * -6;
            const tiltY = (x / rect.width) * 6;
            card.style.transform = `perspective(800px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-8px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
});
</script>
@endpush

@endsection
