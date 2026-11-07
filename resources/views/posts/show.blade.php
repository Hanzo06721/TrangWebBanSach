@extends('layouts.app')
@section('title', $post->Title)

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-3">
            @if($post->images->isNotEmpty())
                @foreach($post->images as $image)<img src="{{ asset('storage/'.$image->ImagePath) }}" class="post-detail-img mb-2" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">@endforeach
            @elseif($post->books && $post->books->CoverImage)
                <img src="{{ asset('storage/'.$post->books->CoverImage) }}" class="post-detail-img" alt="{{ $post->Title }}" onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
            @else <img src="{{ asset('images/book-placeholder.svg') }}" class="post-detail-img" alt="Ảnh sách mặc định">@endif
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-body">
            <span class="badge text-bg-light align-self-start">{{ $post->PostType }}</span>
            <h1 class="h4 mt-2">{{ $post->Title }}</h1>
            <div class="fs-5 fw-bold text-primary">{{ $post->Price !== null ? number_format((float) $post->Price, 0, ',', '.') . ' đ' : 'Không áp dụng giá' }}</div>
            <dl class="info-list mt-3 mb-0"><dt>Sách</dt><dd>{{ $post->books->Title ?? '-' }}</dd><dt>Tác giả</dt><dd>{{ $post->books->AuthorName ?? '-' }}</dd><dt>Danh mục</dt><dd>{{ $post->books->category->CategoryName ?? '-' }}</dd><dt>Tình trạng</dt><dd>{{ $post->Condition }}</dd><dt>Mô tả</dt><dd>{{ $post->Description ?: 'Không có mô tả.' }}</dd></dl>
            @if(! auth()->check() || ! auth()->user()->isAdmin())
                @if($post->PostType === 'Bán' && $post->PostStatus === 'Đã duyệt')
                    @auth
                        @if(auth()->user()->UserID !== $post->UserID)
                            <form method="POST" action="{{ route('posts.purchase.store', $post) }}" class="mt-3">
                                @csrf
                                <button class="btn btn-primary w-100">Mua ngay</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-3">Đăng nhập để mua</a>
                    @endauth
                @endif
            @endif
            @auth
                @if(auth()->user()->isAdmin())
                    @if($post->PostStatus === 'Đã duyệt')
                        <div class="d-flex gap-2 mt-3">
                            <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-outline-warning btn-sm">Ẩn bài đăng</button>
                            </form>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Xóa bài đăng này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa bài đăng</button>
                            </form>
                        </div>
                    @elseif($post->PostStatus === 'Đã ẩn')
                        <div class="d-flex gap-2 mt-3">
                            <form method="POST" action="{{ route('admin.posts.approve', $post) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-outline-success btn-sm">Hiện lại</button>
                            </form>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Xóa bài đăng này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa bài đăng</button>
                            </form>
                        </div>
                    @endif
                @elseif(auth()->user()->UserID === $post->UserID)
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('posts.edit', $post) }}">Sửa</a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Xóa bài đăng này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Xóa</button>
                        </form>
                    </div>
                @endif
                @if(! auth()->user()->isAdmin() && $post->PostStatus === 'Đã duyệt')
                <button class="btn btn-outline-danger btn-sm mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#reportForm">Báo cáo bài đăng</button>
                <div class="collapse mt-2" id="reportForm"><form method="POST" action="{{ route('posts.reports.store', $post) }}">@csrf<textarea name="Reason" class="form-control mb-2" maxlength="500" required placeholder="Nêu lý do báo cáo"></textarea><button class="btn btn-danger btn-sm">Gửi báo cáo</button></form></div>
                @endif
            @endauth
        </div>
    </div>
</div>

@if($chatParticipant)
<section class="card mt-4 chat-box" id="postChat">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Liên hệ mua bán</strong>
        <span class="small text-success">Trực tuyến</span>
    </div>
    <div class="chat-messages" id="chatMessages"></div>
    <form class="chat-form border-top p-3" id="chatForm">
        @csrf
        <div class="d-flex gap-2">
            <input class="form-control" name="message" maxlength="1000" required placeholder="Nhập tin nhắn..." autocomplete="off">
            <button class="btn btn-primary" type="submit">Gửi</button>
        </div>
    </form>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('chatMessages');
    const form = document.getElementById('chatForm');
    if (!box || !form) return;

    const loadMessages = async () => {
        const response = await fetch(@json(route('posts.messages.index', $post)), {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        if (!response.ok) return;
        const data = await response.json();
        box.innerHTML = '';
        data.messages.forEach(item => {
            const row = document.createElement('div');
            row.className = 'chat-message-row ' + (item.mine ? 'mine' : 'their');
            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble';
            const name = document.createElement('div');
            name.className = 'chat-name';
            name.textContent = item.sender_name;
            const text = document.createElement('div');
            text.textContent = item.message;
            const time = document.createElement('div');
            time.className = 'chat-time';
            time.textContent = item.created_at;
            bubble.append(name, text, time);
            row.appendChild(bubble);
            box.appendChild(row);
        });
        box.scrollTop = box.scrollHeight;
    };

    form.addEventListener('submit', async event => {
        event.preventDefault();
        const input = form.elements.message;
        const message = input.value.trim();
        if (!message) return;
        const response = await fetch(@json(route('posts.messages.store', $post)), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({message})
        });
        if (response.ok) {
            input.value = '';
            loadMessages();
        }
    });

    loadMessages();
    setInterval(loadMessages, 3000);
});
</script>
@endif

@if($post->PostStatus === 'Đã duyệt')
<section class="card card-body mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Trao đổi</h2>
        <span class="small text-muted">{{ $post->comments->count() }} bình luận</span>
    </div>
    @auth
    <form method="POST" action="{{ route('posts.comments.store', $post) }}" enctype="multipart/form-data" class="forum-comment-form mb-3 mt-3">@csrf<textarea name="Content" class="form-control mb-2" maxlength="500" placeholder="Viết bình luận..."></textarea><input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control form-control-sm mb-2"><button class="btn btn-primary btn-sm">Gửi bình luận</button></form>
    @else
    <p class="small"><a href="{{ route('login') }}">Đăng nhập</a> để bình luận.</p>
    @endauth
    @forelse($post->comments as $comment)
    <div class="border-top py-3">
        <strong>{{ $comment->user->FullName ?? 'Người dùng' }}</strong>
        <small class="text-muted ms-1">{{ optional($comment->CreatedAt)->format('d/m/Y H:i') }}</small>
        <div>{{ $comment->Content }}</div>
        @if($comment->ImagePath)<img src="{{ asset('storage/'.$comment->ImagePath) }}" class="forum-comment-image mt-2" alt="Ảnh bình luận">@endif
        @auth
        <button type="button" class="btn btn-link btn-sm p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#reply-{{ $comment->CommentID }}">Phản hồi</button>
        <div class="collapse ms-4 mt-2" id="reply-{{ $comment->CommentID }}">
            <form method="POST" action="{{ route('posts.comments.store', $post) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ParentCommentID" value="{{ $comment->CommentID }}">
                <textarea name="Content" class="form-control mb-2" maxlength="500" placeholder="Viết phản hồi..."></textarea>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control form-control-sm mb-2">
                <button class="btn btn-primary btn-sm">Gửi phản hồi</button>
            </form>
        </div>
        @endauth
        @foreach($comment->replies->where('Status', 'Hiển thị') as $reply)
        <div class="forum-reply ms-4 mt-3">
            <strong>{{ $reply->user->FullName ?? 'Người dùng' }}</strong>
            <small class="text-muted ms-1">{{ optional($reply->CreatedAt)->format('d/m/Y H:i') }}</small>
            <div>{{ $reply->Content }}</div>
            @if($reply->ImagePath)<img src="{{ asset('storage/'.$reply->ImagePath) }}" class="forum-comment-image mt-2" alt="Ảnh phản hồi">@endif
            @if(auth()->check() && auth()->user()->isAdmin())
            <form method="POST" action="{{ route('posts.comments.destroy', $reply) }}" class="d-inline mt-1" onsubmit="return confirm('Xóa phản hồi này?')">
                @csrf @method('DELETE')
                <button class="btn btn-link btn-sm text-danger p-0">Xóa phản hồi</button>
            </form>
            @endif
        </div>
        @endforeach
        @if(auth()->check() && (auth()->user()->UserID === $comment->UserID || auth()->user()->isAdmin()))
        <form method="POST" action="{{ route('posts.comments.destroy', $comment) }}" class="d-inline mt-1" onsubmit="return confirm('Xóa bình luận?')">@csrf @method('DELETE')<button class="btn btn-link btn-sm text-danger p-0">Xóa</button></form>
        @endif
        @if(auth()->check() && auth()->user()->UserID === $comment->UserID)
        <button type="button" class="btn btn-link btn-sm p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->CommentID }}">Sửa</button>
        <div class="collapse mt-2" id="edit-comment-{{ $comment->CommentID }}">
            <form method="POST" action="{{ route('posts.comments.update', $comment) }}">
                @csrf @method('PUT')
                <textarea name="Content" class="form-control mb-2" maxlength="500" required>{{ $comment->Content }}</textarea>
                <button class="btn btn-sm btn-primary">Lưu bình luận</button>
            </form>
        </div>
        @endif
    </div>
    @empty <div class="text-muted small">Chưa có bình luận.</div>@endforelse
</section>
@endif
@endsection
