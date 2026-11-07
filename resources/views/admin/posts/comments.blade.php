@extends('layouts.app')
@section('title', 'Kiểm duyệt bình luận')

@section('content')
<h1 class="page-title">Kiểm duyệt bình luận</h1>
<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Tất cả trạng thái</option>
        @foreach(['Hiển thị','Đã ẩn'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
        @endforeach
    </select>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Bài đăng</th><th>Người bình luận</th><th>Nội dung</th><th>Thời gian</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse($comments as $comment)
            <tr>
                <td>
                    @if($comment->post)<a href="{{ route('posts.show', $comment->post) }}">{{ $comment->post->Title }}</a>@else - @endif
                </td>
                <td>{{ $comment->user->FullName ?? '-' }}</td>
                <td>{{ $comment->Content }}</td>
                <td>{{ optional($comment->CreatedAt)->format('d/m/Y H:i') }}</td>
                <td>{{ $comment->Status }}</td>
                <td class="text-end">
                    @if($comment->Status === 'Hiển thị')
                        <form method="POST" action="{{ route('admin.comments.hide', $comment) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-warning">Ẩn</button></form>
                    @endif
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="d-inline" onsubmit="return confirm('Xóa bình luận vi phạm này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Không có bình luận nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $comments->links() }}</div>
@endsection
