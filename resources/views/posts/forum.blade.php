@extends('layouts.app')
@section('title', 'Diễn đàn sách')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h1 class="page-title mb-0">Diễn đàn trao đổi sách</h1><div class="text-muted small">Mọi bài đăng đã duyệt theo dạng bài viết cộng đồng</div></div>
    @auth
        @if(! auth()->user()->isAdmin())
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Tạo bài đăng</a>
        @endif
    @endauth
</div>

<div class="forum-feed mx-auto" id="forumFeed" data-next-page="{{ $posts->nextPageUrl() }}">
@forelse($posts as $post)
    @php
        $visibleComments = $post->comments->where('Status', 'Hiển thị')->whereNull('ParentCommentID');
    @endphp
    <article class="card forum-post mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                @include('partials.avatar', ['user' => $post->users, 'size' => 42])
                <div class="ms-2">
                    <strong>{{ $post->users->FullName ?? 'Người dùng' }}</strong>
                    <div class="small text-muted">{{ optional($post->CreatedAt)->format('d/m/Y H:i') }} · {{ $post->PostType }}</div>
                </div>
            </div>
            <h2 class="h5"><a href="{{ route('posts.show', $post) }}" class="text-decoration-none">{{ $post->Title }}</a></h2>
            @if($post->Description)<p class="mb-2">{{ $post->Description }}</p>@endif
            @if($post->images->isNotEmpty())
                <div class="forum-post-images">
                    @foreach($post->images->take(4) as $image)
                        <a href="{{ route('posts.show', $post) }}"><img src="{{ asset('storage/'.$image->ImagePath) }}" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'"></a>
                    @endforeach
                </div>
            @elseif($post->books && $post->books->CoverImage)
                <a href="{{ route('posts.show', $post) }}"><img src="{{ asset('storage/'.$post->books->CoverImage) }}" class="forum-post-cover" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'"></a>
            @else
                <a href="{{ route('posts.show', $post) }}"><img src="{{ asset('images/book-placeholder.svg') }}" class="forum-post-cover" alt="Ảnh sách mặc định"></a>
            @endif
            <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mt-3">
                <span>{{ $visibleComments->count() }} bình luận</span>
                <span>{{ $post->Price !== null ? number_format((float) $post->Price, 0, ',', '.') . ' đ' : 'Không áp dụng giá' }}</span>
            </div>
            @if($post->PostType === 'Bán')
                @auth
                    @if(! auth()->user()->isAdmin() && auth()->user()->UserID !== $post->UserID)
                        <form method="POST" action="{{ route('posts.purchase.store', $post) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-primary btn-sm mt-2">Mua ngay</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm mt-2">Đăng nhập để mua</a>
                @endauth
            @endif
            <a href="{{ route('posts.show', $post) }}" class="btn btn-link btn-sm text-decoration-none">Bình luận · Phản hồi</a>
        </div>
    </article>
@empty
    <div class="card card-body text-center text-muted">Chưa có bài đăng trong diễn đàn.</div>
@endforelse
<div id="forumScrollSentinel" class="forum-scroll-sentinel py-3 text-center text-muted" aria-live="polite">
    {{ $posts->hasMorePages() ? 'Kéo xuống để tải thêm...' : 'Bạn đã xem hết bài đăng.' }}
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const feed = document.getElementById('forumFeed');
    const sentinel = document.getElementById('forumScrollSentinel');
    let loading = false;

    const loadNextPage = async () => {
        const nextPage = feed?.dataset.nextPage;
        if (!nextPage || loading) return;
        loading = true;
        sentinel.textContent = 'Đang tải thêm bài đăng...';

        try {
            const response = await fetch(nextPage, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextFeed = nextDocument.getElementById('forumFeed');
            const articles = nextFeed?.querySelectorAll('.forum-post') ?? [];
            articles.forEach(article => feed.insertBefore(article.cloneNode(true), sentinel));
            feed.dataset.nextPage = nextFeed?.dataset.nextPage || '';
            sentinel.textContent = feed.dataset.nextPage ? 'Kéo xuống để tải thêm...' : 'Bạn đã xem hết bài đăng.';
        } catch (error) {
            sentinel.textContent = 'Không thể tải thêm. Kéo xuống để thử lại.';
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
