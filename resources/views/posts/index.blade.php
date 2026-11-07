@extends('layouts.app')
@section('title', 'Danh sách bài đăng')

@section('content')
<div class="page-title d-flex justify-content-between align-items-center">
    <span>Bài đăng sách</span>
    @auth
        @if(! auth()->user()->isAdmin())
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Đăng bài mới</a>
        @endif
    @endauth
</div>

<form class="card card-body mb-4 live-search-form" method="GET">
    <div class="d-flex gap-2 align-items-center">
        <input id="livePostSearch" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tìm sách theo tên, tác giả hoặc tiêu đề..." autocomplete="off">
        <button class="btn btn-outline-primary px-3" type="button" data-bs-toggle="collapse" data-bs-target="#postFilters" aria-expanded="false" aria-label="Bộ lọc">
            <span aria-hidden="true">&#9776;</span>
        </button>
    </div>
    <div class="collapse mt-3" id="postFilters">
        <div class="row g-2">
            <div class="col-md-3"><select class="form-select live-filter" name="post_type"><option value="">Loại bài đăng</option>@foreach(['Bán','Trao đổi','Ký gửi','Quyên góp'] as $type)<option value="{{ $type }}" @selected(request('post_type') === $type)>{{ $type }}</option>@endforeach</select></div>
            <div class="col-md-3"><select class="form-select live-filter" name="condition"><option value="">Tình trạng</option>@foreach(['Mới','Như mới','Tốt','Ổn','Cũ'] as $condition)<option value="{{ $condition }}" @selected(request('condition') === $condition)>{{ $condition }}</option>@endforeach</select></div>
            <div class="col-md-3"><select class="form-select live-filter" name="category"><option value="">Danh mục</option>@foreach($categories as $category)<option value="{{ $category->CategoryID }}" @selected(request('category') === $category->CategoryID)>{{ $category->CategoryName }}</option>@endforeach</select></div>
            <div class="col-md-3"><select class="form-select live-filter" name="sort"><option value="newest" @selected(request('sort', 'newest') === 'newest')>Mới nhất</option><option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option><option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option><option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option></select></div>
            <div class="col-md-3"><input type="number" min="0" class="form-control live-filter" name="min_price" value="{{ request('min_price') }}" placeholder="Giá từ"></div>
            <div class="col-md-3"><input type="number" min="0" class="form-control live-filter" name="max_price" value="{{ request('max_price') }}" placeholder="Giá đến"></div>
            <div class="col-md-3 ms-auto"><button class="btn btn-primary w-100 live-filter-submit" type="button">Áp dụng bộ lọc</button></div>
        </div>
    </div>
</form>

<div class="row g-3" id="postResults">
@forelse($posts as $post)
    <div class="col-sm-6 col-lg-4">
        <article class="card h-100">
            @if($post->images->first())
                <img src="{{ asset('storage/' . $post->images->first()->ImagePath) }}" class="card-img-top post-cover" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
            @elseif($post->books && $post->books->CoverImage)
                <img src="{{ asset('storage/' . $post->books->CoverImage) }}" class="card-img-top post-cover" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
            @else
                <img src="{{ asset('images/book-placeholder.svg') }}" class="card-img-top post-cover" alt="Ảnh sách mặc định">
            @endif
            <div class="card-body">
                <span class="badge text-bg-light mb-2">{{ $post->PostType }}</span>
                <h2 class="h6"><a class="text-decoration-none" href="{{ route('posts.show', $post) }}">{{ $post->Title }}</a></h2>
                <p class="small text-muted mb-1">{{ $post->books->Title ?? '' }} · {{ $post->books->AuthorName ?? '' }}</p>
                <div class="fw-semibold">{{ $post->Price !== null ? number_format((float) $post->Price, 0, ',', '.') . ' đ' : 'Không áp dụng giá' }}</div>
                <div class="small text-muted mt-2">Tình trạng: {{ $post->Condition }}</div>
                @if($post->PostType === 'Bán' && $post->PostStatus === 'Đã duyệt')
                    <div class="d-flex gap-2 mt-3">
                        @auth
                            @if(! auth()->user()->isAdmin() && auth()->user()->UserID !== $post->UserID)
                                <form method="POST" action="{{ route('posts.purchase.store', $post) }}" class="w-100">
                                    @csrf
                                    <button class="btn btn-primary btn-sm w-100">Mua ngay</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">Đăng nhập để mua</a>
                        @endauth
                    </div>
                @endif
            </div>
        </article>
    </div>
@empty
    <div class="col-12"><div class="alert alert-light">Không tìm thấy bài đăng phù hợp.</div></div>
@endforelse
</div>
<div class="mt-4">{{ $posts->links() }}</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.live-search-form');
    const search = document.getElementById('livePostSearch');
    const results = document.getElementById('postResults');
    let timer;
    const currentUrl = new URL(window.location.href);

    const searchLive = () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const params = new URLSearchParams();
            params.set('q', search.value);
            ['post_type', 'condition', 'category', 'sort', 'min_price', 'max_price'].forEach(name => {
                const appliedValue = currentUrl.searchParams.get(name);
                if (appliedValue) params.set(name, appliedValue);
            });
            params.delete('page');
            history.replaceState(null, '', form.action + '?' + params.toString());
            const response = await fetch(form.action + '?' + params.toString(), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const html = await response.text();
            const documentNode = new DOMParser().parseFromString(html, 'text/html');
            const nextResults = documentNode.getElementById('postResults');
            if (nextResults && results) results.innerHTML = nextResults.innerHTML;
        }, 300);
    };

    search.addEventListener('input', searchLive);
    document.querySelector('.live-filter-submit')?.addEventListener('click', () => {
        clearTimeout(timer);
        const params = new URLSearchParams(new FormData(form));
        params.delete('page');
        window.location.href = form.action + '?' + params.toString();
    });
});
</script>
@endsection
