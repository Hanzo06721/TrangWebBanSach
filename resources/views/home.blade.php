@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
<section class="home-hero">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="home-eyebrow">Thị trường sách cộng đồng</span>
            <h1>Tìm cuốn sách tiếp theo cho hành trình của bạn.</h1>
            <p class="home-lead">Mua sách chất lượng, trao đổi tri thức hoặc gửi lại cuốn sách còn hay cho người cần đọc.</p>
            <form class="home-search" method="GET" action="{{ route('posts.index') }}">
                <input class="form-control" name="q" value="" placeholder="Tìm tên sách, tác giả hoặc tiêu đề..." aria-label="Tìm kiếm sách">
                <button class="btn btn-primary px-4" type="submit">Tìm kiếm</button>
            </form>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a class="btn btn-light btn-sm" href="{{ route('posts.index', ['post_type' => 'Bán']) }}">Mua sách</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('posts.index', ['post_type' => 'Trao đổi']) }}">Trao đổi</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('posts.index', ['post_type' => 'Quyên góp']) }}">Quyên góp</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="home-book-stage" aria-hidden="true">
                <div class="home-book-card home-book-main">
                    <span>Mua</span><strong>Bán</strong>
                </div>
                <div class="home-book-card home-book-side">
                    <span>Trao</span><strong>Đổi</strong>
                </div>
                <div class="home-book-card home-book-small">
                    <span>Chia</span><strong>Sẻ</strong>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
