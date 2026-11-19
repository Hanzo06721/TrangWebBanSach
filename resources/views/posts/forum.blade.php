@extends('layouts.app')

@php
    $type = request('post_type');
    $forumTitle = match($type) {
        'Bán' => 'Thảo Luận Mua Bán Sách Cũ',
        'Trao đổi' => 'Góc Trao Đổi Tri Thức & Sách Hay',
        'Ký gửi' => 'Diễn Đàn Sách Ký Gửi Độc Quyền',
        'Quyên góp' => 'Góc Sách Quyên Góp & Lan Tỏa Yêu Thương',
        default => 'Diễn Đàn & Hội Quán Bạn Đọc'
    };
@endphp

@section('title', $forumTitle)

@push('styles')
    <link href="{{ asset('css/posts-book.css') }}" rel="stylesheet">
@endpush

@section('content')

<!-- ========================================================
     1. BANNER TIÊU ĐỀ DIỄN ĐÀN SANG TRỌNG
     ======================================================== -->
<section class="posts-header-banner">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="posts-header-tag">✨ HỘI QUÁN BẠN ĐỌC</span>
            <h1 class="posts-header-title">
                {{ $forumTitle }}
            </h1>
            <p class="posts-header-sub">
                Không gian giao lưu, trao đổi cảm nhận, kết nối bạn đọc và tìm kiếm những tác phẩm ý nghĩa trong cộng đồng.
            </p>
        </div>
        @auth
            @if(! auth()->user()->isAdmin())
                <div class="flex-shrink-0">
                    <a href="{{ route('posts.create') }}" class="btn-post-new">
                        <span>✍️</span> Tạo bài thảo luận
                    </a>
                </div>
            @endif
        @else
            <div class="flex-shrink-0">
                <a href="{{ route('register') }}" class="btn-post-new">
                    <span>✨</span> Tham gia hội quán
                </a>
            </div>
        @endauth
    </div>
</section>

<!-- ========================================================
     2. THANH CHUYỂN TABS CHỦ ĐỀ DIỄN ĐÀN
     ======================================================== -->
<nav class="post-type-tabs" aria-label="Bộ lọc chủ đề diễn đàn">
    <a href="{{ route('posts.forum') }}" 
       class="post-tab-item {{ empty($type) ? 'active' : '' }}">
        <span>💬</span> Tất cả thảo luận
    </a>
    <a href="{{ route('posts.forum', ['post_type' => 'Bán']) }}" 
       class="post-tab-item {{ $type === 'Bán' ? 'active' : '' }}">
        <span>🏷️</span> Thảo luận Mua bán
    </a>
    <a href="{{ route('posts.forum', ['post_type' => 'Trao đổi']) }}" 
       class="post-tab-item {{ $type === 'Trao đổi' ? 'active' : '' }}">
        <span>🔄</span> Góc Trao đổi
    </a>
    <a href="{{ route('posts.forum', ['post_type' => 'Ký gửi']) }}" 
       class="post-tab-item {{ $type === 'Ký gửi' ? 'active' : '' }}">
        <span>📦</span> Thảo luận Ký gửi
    </a>
    <a href="{{ route('posts.forum', ['post_type' => 'Quyên góp']) }}" 
       class="post-tab-item {{ $type === 'Quyên góp' ? 'active' : '' }}">
        <span>🎁</span> Góc Quyên góp
    </a>
</nav>

<!-- ========================================================
     3. TÌM KIẾM BÀI VIẾT DIỄN ĐÀN
     ======================================================== -->
<div class="filter-search-card mb-4">
    <form method="GET" action="{{ route('posts.forum') }}">
        @if($type)
            <input type="hidden" name="post_type" value="{{ $type }}">
        @endif
        <div class="search-input-group">
            <input class="search-input-field" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Tìm kiếm chủ đề, tiêu đề sách hoặc tác giả trong diễn đàn..." 
                   autocomplete="off">
            <button class="btn btn-primary px-4 fw-bold" type="submit" style="border-radius: 0.65rem;">
                🔍 Tìm kiếm
            </button>
            @if(request('q'))
                <a href="{{ route('posts.forum', $type ? ['post_type' => $type] : []) }}" 
                   class="btn btn-outline-secondary" style="border-radius: 0.65rem;">
                    Xóa
                </a>
            @endif
        </div>
    </form>
</div>

<!-- ========================================================
     4. FEED BÀI ĐĂNG CỘNG ĐỒNG (FORUM FEED)
     ======================================================== -->
<div class="forum-feed-container" id="forumFeed" data-next-page="{{ $posts->nextPageUrl() }}">
@forelse($posts as $post)
    @php
        $visibleComments = $post->comments->where('Status', 'Hiển thị')->whereNull('ParentCommentID');
        
        $ribbonClass = match($post->PostType) {
            'Bán' => 'ribbon-ban',
            'Trao đổi' => 'ribbon-traodoi',
            'Ký gửi' => 'ribbon-kygui',
            'Quyên góp' => 'ribbon-quyengop',
            default => 'ribbon-traodoi'
        };

        $pillColor = match($post->PostType) {
            'Bán' => 'var(--type-ban)',
            'Trao đổi' => 'var(--type-traodoi)',
            'Ký gửi' => 'var(--type-kygui)',
            'Quyên góp' => 'var(--type-quyengop)',
            default => 'var(--type-traodoi)'
        };
    @endphp

    <article class="forum-card-post">
        <!-- Hàng tác giả và thời gian -->
        <div class="forum-author-row">
            <div class="forum-author-left">
                <div class="forum-avatar-frame">
                    @include('partials.avatar', ['user' => $post->users, 'size' => 44])
                </div>
                <div>
                    <div class="forum-author-name">
                        {{ $post->users->FullName ?? 'Thành viên cộng đồng' }}
                    </div>
                    <div class="forum-post-meta">
                        <span>🕒 {{ optional($post->CreatedAt)->diffForHumans() ?? optional($post->CreatedAt)->format('d/m/Y H:i') }}</span>
                        @if($post->Condition)
                            <span>· Tình trạng: <strong>{{ $post->Condition }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Huy hiệu loại bài viết -->
            <span class="ribbon-type-badge {{ $ribbonClass }}" style="position: static; box-shadow: none;">
                {{ $post->PostType }}
            </span>
        </div>

        <!-- Tiêu đề bài thảo luận -->
        <h2 class="forum-post-title">
            <a href="{{ route('posts.show', $post) }}">
                {{ $post->Title }}
            </a>
        </h2>

        <!-- Mô tả bài đăng -->
        @if($post->Description)
            <div class="forum-post-desc">
                {{ $post->Description }}
            </div>
        @endif

        <!-- Chip tóm tắt thông tin sách -->
        @if($post->books)
            <div class="forum-book-chip">
                <div>
                    <div class="forum-book-chip-title">
                        📚 {{ $post->books->Title }}
                    </div>
                    <div class="forum-book-chip-author">
                        ✍️ {{ $post->books->AuthorName ?? 'Chưa rõ tác giả' }}
                        @if($post->books->category)
                            · <em>{{ $post->books->category->CategoryName }}</em>
                        @endif
                    </div>
                </div>
                <div>
                    @if($post->PostType === 'Bán')
                        <span class="book-price-highlight">
                            {{ number_format((float) $post->Price, 0, ',', '.') }} đ
                        </span>
                    @elseif($post->PostType === 'Quyên góp')
                        <span class="book-price-free">
                            🎁 Tặng miễn phí
                        </span>
                    @else
                        <span class="book-price-exchange">
                            🔄 Trao đổi
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Trưng bày ảnh sách -->
        @if($post->images->isNotEmpty())
            @if($post->images->count() === 1)
                <div class="forum-gallery-single">
                    <a href="{{ route('posts.show', $post) }}">
                        <img src="{{ asset('storage/' . $post->images->first()->ImagePath) }}" 
                             alt="{{ $post->Title }}" 
                             loading="lazy" 
                             onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
                    </a>
                </div>
            @else
                <div class="forum-gallery-grid">
                    @foreach($post->images->take(4) as $image)
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ asset('storage/' . $image->ImagePath) }}" 
                                 class="forum-gallery-img-thumb" 
                                 alt="{{ $post->Title }}" 
                                 loading="lazy" 
                                 onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
                        </a>
                    @endforeach
                </div>
            @endif
        @elseif($post->books && $post->books->CoverImage)
            <div class="forum-gallery-single">
                <a href="{{ route('posts.show', $post) }}">
                    <img src="{{ $post->books->cover_url }}" 
                         alt="{{ $post->Title }}" 
                         loading="lazy" 
                         onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
                </a>
            </div>
        @endif

        <!-- Thanh tương tác và hành động cuối bài viết -->
        <div class="forum-interaction-bar">
            <!-- Nhóm tương tác phản hồi -->
            <div class="forum-action-btn-group">
                <a href="{{ route('posts.show', $post) }}" class="btn-forum-action">
                    <span>💬</span>
                    <span>{{ $visibleComments->count() }} Bình luận</span>
                </a>

                @auth
                    <form method="POST" action="{{ route('posts.reactions.store', $post) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="ReactionType" value="❤️">
                        <button type="submit" class="btn-forum-action" style="cursor: pointer;">
                            <span>❤️</span>
                            <span>{{ $post->reactions->count() > 0 ? $post->reactions->count() : 'Thích' }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-forum-action" title="Đăng nhập để thả tim">
                        <span>❤️</span>
                        <span>{{ $post->reactions->count() > 0 ? $post->reactions->count() : 'Thích' }}</span>
                    </a>
                @endauth
            </div>

            <!-- Nhóm nút hành động chính (Mua/Đổi/Quyên góp) -->
            <div class="d-flex align-items-center gap-2">
                @if($post->PostType === 'Bán')
                    @auth
                        @if(! auth()->user()->isAdmin() && auth()->user()->UserID !== $post->UserID)
                            <form method="POST" action="{{ route('posts.purchase.store', $post) }}" class="d-inline">
                                @csrf
                                <button class="btn-card-action btn-buy" style="width: auto; padding: 0.4rem 1.15rem;">
                                    🛒 Mua ngay
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-card-action btn-guest-login" style="width: auto; padding: 0.4rem 1rem;">
                            🔐 Đăng nhập để mua
                        </a>
                    @endauth
                @elseif($post->PostType === 'Trao đổi')
                    @auth
                        <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-exchange" style="width: auto; padding: 0.4rem 1.15rem;">
                            🔄 Đổi sách
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-card-action btn-guest-login" style="width: auto; padding: 0.4rem 1rem;">
                            🔐 Đăng nhập để đổi
                        </a>
                    @endauth
                @elseif($post->PostType === 'Quyên góp')
                    @auth
                        <a href="{{ route('posts.show', $post) }}" class="btn-card-action btn-donate" style="width: auto; padding: 0.4rem 1.15rem;">
                            🎁 Nhận sách
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-card-action btn-guest-login" style="width: auto; padding: 0.4rem 1rem;">
                            🔐 Đăng nhập nhận sách
                        </a>
                    @endauth
                @endif

                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 20px;">
                    Chi tiết →
                </a>
            </div>
        </div>
    </article>
@empty
    <div class="forum-card-post text-center py-5">
        <div style="font-size: 3.5rem; margin-bottom: 1rem;">📖</div>
        <h3 class="h5 fw-bold text-dark">Chưa có bài thảo luận nào trong mục này</h3>
        <p class="text-muted small mb-3">Hãy là người đầu tiên khơi dậy cuộc thảo luận sôi nổi về những trang sách!</p>
        @auth
            <a href="{{ route('posts.create') }}" class="btn-post-new">
                <span>✍️</span> Tạo bài thảo luận ngay
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-post-new">
                <span>✨</span> Đăng nhập để tạo bài viết
            </a>
        @endauth
    </div>
@endforelse

<!-- ========================================================
     5. ĐIỂM CUỐI CUỘN TẢI THÊM (INFINITE SCROLL)
     ======================================================== -->
<div id="forumScrollSentinel" class="forum-scroll-sentinel py-4 text-center text-muted" aria-live="polite">
    @if($posts->hasMorePages())
        <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
        <span>Kéo xuống để tải thêm bài thảo luận...</span>
    @else
        <span class="small">✨ Bạn đã khám phá hết tất cả bài thảo luận.</span>
    @endif
</div>

</div>

<!-- Script tải thêm dữ liệu vô tận -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const feed = document.getElementById('forumFeed');
    const sentinel = document.getElementById('forumScrollSentinel');
    let loading = false;

    const loadNextPage = async () => {
        const nextPage = feed?.dataset.nextPage;
        if (!nextPage || loading) return;
        loading = true;
        sentinel.innerHTML = '<div class="spinner-border spinner-border-sm text-success me-2" role="status"></div><span>Đang tải thêm thảo luận...</span>';

        try {
            const response = await fetch(nextPage, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextFeed = nextDocument.getElementById('forumFeed');
            const articles = nextFeed?.querySelectorAll('.forum-card-post') ?? [];
            articles.forEach(article => feed.insertBefore(article.cloneNode(true), sentinel));
            feed.dataset.nextPage = nextFeed?.dataset.nextPage || '';
            sentinel.innerHTML = feed.dataset.nextPage 
                ? '<div class="spinner-border spinner-border-sm text-success me-2" role="status"></div><span>Kéo xuống để tải thêm bài thảo luận...</span>' 
                : '<span class="small">✨ Bạn đã khám phá hết tất cả bài thảo luận.</span>';
        } catch (error) {
            sentinel.innerHTML = '<span class="text-danger small">Không thể tải thêm. Vui lòng thử lại.</span>';
        } finally {
            loading = false;
        }
    };

    if ('IntersectionObserver' in window && feed && sentinel) {
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) loadNextPage();
        }, {rootMargin: '300px'});
        observer.observe(sentinel);
    }
});
</script>

@endsection
