@extends('layouts.app')
@section('title', 'Kiểm duyệt bài đăng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="page-title mb-0">Kiểm duyệt bài đăng</h1></div>
<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
        @foreach(['Chờ duyệt','Đã duyệt','Đã ẩn','Bị từ chối'] as $status)<option value="{{ $status }}" @selected(request('status', 'Chờ duyệt') === $status)>{{ $status }}</option>@endforeach
    </select>
</form>
<div class="card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Bài đăng</th><th>Người đăng</th><th>Loại</th><th>Trạng thái</th><th></th></tr></thead><tbody>
@forelse($posts as $post)
<tr><td><a href="{{ route('posts.show', $post) }}">{{ $post->Title }}</a></td><td>{{ $post->users->FullName ?? '-' }}</td><td>{{ $post->PostType }}</td><td>{{ $post->PostStatus }}</td><td class="text-end">
@if($post->PostStatus === 'Chờ duyệt')
    <form method="POST" action="{{ route('admin.posts.approve', $post) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-success">Duyệt</button></form>
    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#reject-post-{{ $post->PostID }}">Từ chối</button>
@endif
@if($post->PostStatus === 'Đã duyệt')
    <form method="POST" action="{{ route('admin.posts.hide', $post) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning">Ẩn bài đăng</button></form>
    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="d-inline" onsubmit="return confirm('Xóa bài đăng đã duyệt này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa bài đăng</button></form>
@elseif($post->PostStatus === 'Đã ẩn')
    <form method="POST" action="{{ route('admin.posts.approve', $post) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-success">Hiện lại</button></form>
    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="d-inline" onsubmit="return confirm('Xóa bài đăng này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa bài đăng</button></form>
@endif
</td></tr>
@if($post->PostStatus === 'Chờ duyệt')
<tr><td colspan="5" class="border-0 pt-0">
    <div class="collapse" id="reject-post-{{ $post->PostID }}">
        <form method="POST" action="{{ route('admin.posts.reject', $post) }}" class="card card-body bg-light">
            @csrf @method('PATCH')
            <label class="form-label">Lý do từ chối</label>
            <textarea name="Reason" class="form-control mb-2" maxlength="500" required placeholder="Nhập lý do bài đăng vi phạm"></textarea>
            <button class="btn btn-sm btn-danger align-self-start">Xác nhận từ chối</button>
        </form>
    </div>
</td></tr>
@endif
@empty<tr><td colspan="5" class="text-center text-muted py-4">Không có bài đăng trong trạng thái này.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $posts->links() }}</div>
@endsection
