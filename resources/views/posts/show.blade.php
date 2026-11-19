@extends('layouts.app')

@section('title', $post->Title)

@push('styles')
    <link href="{{ asset('css/posts-book.css') }}" rel="stylesheet">
@endpush

@section('content')

@php
    $ribbonClass = match($post->PostType) {
        'Bán' => 'ribbon-ban',
        'Trao đổi' => 'ribbon-traodoi',
        'Ký gửi' => 'ribbon-kygui',
        'Quyên góp' => 'ribbon-quyengop',
        default => 'ribbon-ban'
    };

    $primaryImg = $post->images->where('IsPrimary', true)->first() ?? $post->images->first();
    $mainCover = $primaryImg 
        ? asset('storage/'.$primaryImg->ImagePath) 
        : ($post->books ? $post->books->cover_url : asset('images/book-placeholder.svg'));
@endphp

<!-- ========================================================
     1. THANH ĐIỀU HƯỚNG BREADCRUMB
     ======================================================== -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Bài đăng sách</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->Title, 40) }}</li>
    </ol>
</nav>

<!-- ========================================================
     2. KHU VỰC CHI TIẾT SÁCH 3D
     ======================================================== -->
<div class="row g-4">
    <!-- Cột trái: Trưng bày bìa sách 3D & Gallery -->
    <div class="col-lg-6">
        <div class="book-detail-stage h-100 d-flex flex-column justify-content-between">
            <div class="book-detail-cover-show position-relative mb-3">
                <span class="ribbon-type-badge {{ $ribbonClass }}">
                    {{ $post->PostType }}
                </span>

                @if($post->Condition)
                    <span class="badge-condition-pill">
                        Tình trạng: {{ $post->Condition }}
                    </span>
                @endif

                <img id="mainBookImage" 
                     src="{{ $mainCover }}" 
                     alt="{{ $post->Title }}" 
                     onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
            </div>

            <!-- Gallery ảnh sách nhỏ nếu có nhiều hơn 1 ảnh -->
            @if($post->images->count() > 1)
                <div class="d-flex gap-2 justify-content-center overflow-auto py-2">
                    @foreach($post->images as $image)
                        <img src="{{ asset('storage/'.$image->ImagePath) }}" 
                             alt="Ảnh minh họa" 
                             class="rounded border thumbnail-picker" 
                             style="width: 70px; height: 85px; object-fit: cover; cursor: pointer; transition: all 0.2s;"
                             onclick="document.getElementById('mainBookImage').src = this.src"
                             onerror="this.onerror=null;this.src='{{ asset('images/book-placeholder.svg') }}'">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Cột phải: Thông số chi tiết & Thao tác Mua / Đổi / Tặng -->
    <div class="col-lg-6">
        <div class="book-detail-stage h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge" style="background: rgba(21, 69, 54, 0.1); color: #154536; font-size: 0.85rem; padding: 0.4rem 0.8rem; border-radius: 20px;">
                    📌 {{ $post->PostType }}
                </span>
                <span class="small text-muted">
                    Đăng lúc: {{ optional($post->CreatedAt)->format('d/m/Y H:i') }}
                </span>
            </div>

            <h1 class="h3 fw-bold text-dark mb-3" style="line-height: 1.35;">
                {{ $post->Title }}
            </h1>

            <!-- Hộp giá tiền / Chế độ trao đổi nổi bật -->
            <div class="p-3 rounded-3 mb-4" style="background: #fdfaf2; border: 1.5px solid #ded3be;">
                <div class="small text-muted text-uppercase fw-bold mb-1" style="letter-spacing: 0.8px;">
                    Giá & Hình thức
                </div>
                @if($post->PostType === 'Bán')
                    <div class="fs-3 fw-bold text-success">
                        {{ $post->Price !== null ? number_format((float) $post->Price, 0, ',', '.') . ' VNĐ' : 'Liên hệ người bán' }}
                    </div>
                @elseif($post->PostType === 'Quyên góp')
                    <div class="fs-3 fw-bold text-primary">
                        🎁 Tặng Miễn Phí (Quyên góp)
                    </div>
                @else
                    <div class="fs-4 fw-bold" style="color: #b46914;">
                        🔄 Trao Đổi Tri Thức
                    </div>
                @endif
            </div>

            <!-- Bảng thông số cuốn sách -->
            <dl class="row book-spec-table mb-4">
                <dt class="col-sm-4">Tên tác phẩm:</dt>
                <dd class="col-sm-8">{{ $post->books->Title ?? '-' }}</dd>

                <dt class="col-sm-4">Tác giả:</dt>
                <dd class="col-sm-8">{{ $post->books->AuthorName ?? 'Chưa rõ' }}</dd>

                <dt class="col-sm-4">Thể loại:</dt>
                <dd class="col-sm-8">{{ $post->books->category->CategoryName ?? 'Chung' }}</dd>

                <dt class="col-sm-4">Tình trạng sách:</dt>
                <dd class="col-sm-8">
                    <span class="badge bg-light text-dark border">
                        {{ $post->Condition ?? 'Tốt' }}
                    </span>
                </dd>

                <dt class="col-sm-4">Người đăng:</dt>
                <dd class="col-sm-8">
                    <strong>{{ $post->users->FullName ?? 'Người dùng' }}</strong>
                </dd>

                <dt class="col-sm-4">Mô tả chi tiết:</dt>
                <dd class="col-sm-8" style="font-weight: 400; color: #495057; white-space: pre-line;">
                    {{ $post->Description ?: 'Chưa có mô tả thêm từ người đăng.' }}
                </dd>
            </dl>

            <!-- KHU VỰC HÀNH ĐỘNG (GUEST VS AUTH) -->
            @if(! auth()->check() || ! auth()->user()->isAdmin())
                @if($post->PostStatus === 'Đã duyệt')
                    @auth
                        @if(auth()->user()->UserID !== $post->UserID)
                            @if($post->PostType === 'Bán')
                                <form method="POST" action="{{ route('posts.purchase.store', $post) }}" class="mt-3">
                                    @csrf
                                    <button class="btn btn-primary w-100 py-3 fw-bold fs-6" style="border-radius: 30px;">
                                        🛒 Mua ngay ({{ number_format((float) $post->Price, 0, ',', '.') }} đ)
                                    </button>
                                </form>
                            @elseif($post->PostType === 'Trao đổi')
                                <a href="#postChat" class="btn btn-primary w-100 py-3 fw-bold fs-6" style="border-radius: 30px;">
                                    🔄 Nhắn tin thỏa thuận trao đổi sách
                                </a>
                            @elseif($post->PostType === 'Quyên góp')
                                <a href="#postChat" class="btn btn-success w-100 py-3 fw-bold fs-6" style="border-radius: 30px;">
                                    🎁 Liên hệ nhận sách quyên góp
                                </a>
                            @elseif($post->PostType === 'Ký gửi')
                                <a href="#postChat" class="btn btn-warning w-100 py-3 fw-bold fs-6 text-dark" style="border-radius: 30px;">
                                    📦 Liên hệ mua / nhận sách ký gửi
                                </a>
                            @endif
                        @else
                            <div class="alert alert-secondary py-2 small mb-0 text-center rounded-3">
                                📌 Đây là bài đăng của chính bạn. Bạn có thể chỉnh sửa hoặc quản lý bài viết ở bên dưới.
                            </div>
                        @endif
                    @else
                        {{-- Chế độ Khách (Guest) - Đăng nhập trước khi tương tác --}}
                        <div class="mt-3 p-3 rounded-3" style="background: #faf6ed; border: 1.5px solid #d4af37;">
                            @if($post->PostType === 'Bán')
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login py-3 w-100 fs-6">
                                    🔐 Đăng nhập để mua cuốn sách này
                                </a>
                            @elseif($post->PostType === 'Trao đổi')
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login py-3 w-100 fs-6">
                                    🔐 Đăng nhập để gửi yêu cầu trao đổi sách
                                </a>
                            @elseif($post->PostType === 'Quyên góp')
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login py-3 w-100 fs-6">
                                    🔐 Đăng nhập để nhận sách quyên góp
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-card-action btn-guest-login py-3 w-100 fs-6">
                                    🔐 Đăng nhập để tương tác bài viết
                                </a>
                            @endif
                            
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="fw-bold text-success">Đăng ký thành viên ngay</a>
                                </small>
                            </div>
                        </div>
                    @endauth
                @endif
            @endif

            <!-- Quyền Admin & Chủ bài đăng -->
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="border-top pt-3 mt-4">
                        <div class="small fw-bold text-muted mb-2">QUẢN TRỊ VIÊN:</div>
                        <div class="d-flex gap-2">
                            @if($post->PostStatus === 'Đã duyệt')
                                <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline-warning btn-sm">Ẩn bài đăng</button>
                                </form>
                            @elseif($post->PostStatus === 'Đã ẩn')
                                <form method="POST" action="{{ route('admin.posts.approve', $post) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline-success btn-sm">Hiện lại</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Xóa bài đăng này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa bài đăng</button>
                            </form>
                        </div>
                    </div>
                @elseif(auth()->user()->UserID === $post->UserID)
                    <div class="border-top pt-3 mt-4">
                        <div class="small fw-bold text-muted mb-2">TÙY CHỌN BÀI VIẾT:</div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-primary btn-sm px-3" href="{{ route('posts.edit', $post) }}">✏️ Sửa bài viết</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Xóa bài đăng này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm px-3">🗑️ Xóa</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if(! auth()->user()->isAdmin() && $post->PostStatus === 'Đã duyệt' && auth()->user()->UserID !== $post->UserID)
                    <button class="btn btn-link btn-sm text-danger text-decoration-none mt-3 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#reportForm">
                        🚩 Báo cáo vi phạm bài đăng này
                    </button>
                    <div class="collapse mt-2" id="reportForm">
                        <form method="POST" action="{{ route('posts.reports.store', $post) }}">
                            @csrf
                            <textarea name="Reason" class="form-control mb-2" maxlength="500" required placeholder="Nêu rõ lý do vi phạm..."></textarea>
                            <button class="btn btn-danger btn-sm">Gửi báo cáo</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- ========================================================
     3. HỘP NHẮN TIN TRỰC TIẾP (CHAT BOX)
     ======================================================== -->
@if($chatParticipant)
<section class="card mt-4 chat-box" id="postChat" style="border-radius: 1rem; overflow: hidden; border: 1.5px solid #ded4c2;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: #154536; color: #fff;">
        <div>
            <strong>💬 Nhắn tin trao đổi trực tiếp</strong>
            <span class="small d-block text-warning">Trò chuyện với người đăng về tác phẩm này</span>
        </div>
        <span class="badge bg-success">Trực tuyến</span>
    </div>
    <div class="chat-messages" id="chatMessages"></div>
    <form class="chat-form border-top p-3 bg-white" id="chatForm">
        @csrf
        <div class="d-flex gap-2">
            <input class="form-control" name="message" maxlength="1000" required placeholder="Nhập tin nhắn..." autocomplete="off" style="border-radius: 20px;">
            <button class="btn btn-primary px-4" type="submit" style="border-radius: 20px;">Gửi</button>
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

<!-- ========================================================
     4. BÌNH LUẬN & THẢO LUẬN CỘNG ĐỒNG
     ======================================================== -->
@if($post->PostStatus === 'Đã duyệt')
<section class="card card-body mt-4" style="border-radius: 1rem; border: 1.5px solid #ded4c2; background: #fff;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0 fw-bold text-dark">
            💬 Bình luận & Hỏi đáp về sách
        </h2>
        <span class="badge bg-secondary">{{ $post->comments->count() }} bình luận</span>
    </div>

    @auth
        <form method="POST" action="{{ route('posts.comments.store', $post) }}" enctype="multipart/form-data" class="forum-comment-form mb-4">
            @csrf
            <textarea name="Content" class="form-control mb-2" maxlength="500" placeholder="Viết câu hỏi hoặc nhận xét của bạn về cuốn sách này..." rows="3" style="border-radius: 0.6rem;"></textarea>
            <div class="d-flex justify-content-between align-items-center">
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control form-control-sm" style="max-width: 250px;">
                <button class="btn btn-primary btn-sm px-4 fw-bold" style="border-radius: 20px;">Gửi bình luận</button>
            </div>
        </form>
    @else
        <div class="alert alert-light border py-3 text-center mb-4 rounded-3">
            <span>Bạn muốn trao đổi thêm? </span>
            <a href="{{ route('login') }}" class="fw-bold text-success">Đăng nhập</a> để tham gia bình luận cùng mọi người.
        </div>
    @endauth

    <div class="comment-thread-list">
        @forelse($post->comments as $comment)
            <div class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <strong class="text-success">{{ $comment->user->FullName ?? 'Người dùng' }}</strong>
                        <small class="text-muted ms-2">{{ optional($comment->CreatedAt)->format('d/m/Y H:i') }}</small>
                    </div>
                    @if(auth()->check() && (auth()->user()->UserID === $comment->UserID || auth()->user()->isAdmin()))
                        <form method="POST" action="{{ route('posts.comments.destroy', $comment) }}" class="d-inline" onsubmit="return confirm('Xóa bình luận?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-link btn-sm text-danger p-0 text-decoration-none">Xóa</button>
                        </form>
                    @endif
                </div>

                <div class="text-dark">{{ $comment->Content }}</div>
                
                @if($comment->ImagePath)
                    <img src="{{ asset('storage/'.$comment->ImagePath) }}" class="forum-comment-image mt-2" alt="Ảnh bình luận">
                @endif

                @auth
                    <button type="button" class="btn btn-link btn-sm p-0 mt-1 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#reply-{{ $comment->CommentID }}">
                        ↩️ Phản hồi
                    </button>
                    <div class="collapse ms-4 mt-2" id="reply-{{ $comment->CommentID }}">
                        <form method="POST" action="{{ route('posts.comments.store', $post) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ParentCommentID" value="{{ $comment->CommentID }}">
                            <textarea name="Content" class="form-control mb-2" maxlength="500" placeholder="Viết câu trả lời..."></textarea>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control form-control-sm mb-2">
                            <button class="btn btn-primary btn-sm">Gửi phản hồi</button>
                        </form>
                    </div>
                @endauth

                <!-- Phản hồi cấp 2 -->
                @foreach($comment->replies->where('Status', 'Hiển thị') as $reply)
                    <div class="forum-reply ms-4 mt-3 p-2 bg-light rounded">
                        <div class="d-flex justify-content-between">
                            <strong class="text-dark">{{ $reply->user->FullName ?? 'Người dùng' }}</strong>
                            <small class="text-muted">{{ optional($reply->CreatedAt)->format('d/m/Y H:i') }}</small>
                        </div>
                        <div>{{ $reply->Content }}</div>
                        @if($reply->ImagePath)
                            <img src="{{ asset('storage/'.$reply->ImagePath) }}" class="forum-comment-image mt-2" alt="Ảnh phản hồi">
                        @endif
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('posts.comments.destroy', $reply) }}" class="d-inline mt-1" onsubmit="return confirm('Xóa phản hồi này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0 text-decoration-none">Xóa</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-3 text-muted">
                Chưa có bình luận nào. Hãy là người đầu tiên để lại ý kiến!
            </div>
        @endforelse
    </div>
</section>
@endif

@endsection
