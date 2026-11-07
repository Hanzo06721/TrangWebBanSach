@extends('layouts.app')
@section('title', 'Bài đăng của tôi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="page-title mb-0">Bài đăng của tôi</h1><a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Đăng bài mới</a></div>
<div class="card">
<div class="table-responsive"><table class="table mb-0"><thead><tr><th>Tiêu đề</th><th>Loại</th><th>Giá</th><th>Trạng thái</th><th></th></tr></thead><tbody>
@forelse($posts as $post)
<tr><td><a href="{{ route('posts.show', $post) }}">{{ $post->Title }}</a></td><td>{{ $post->PostType }}</td><td>{{ $post->Price !== null ? number_format((float) $post->Price, 0, ',', '.') . ' đ' : '-' }}</td><td><span class="badge {{ $post->PostStatus === 'Đã duyệt' ? 'text-bg-success' : ($post->PostStatus === 'Bị từ chối' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ $post->PostStatus }}</span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('posts.edit', $post) }}">Sửa</a><form method="POST" action="{{ route('posts.destroy', $post) }}" class="d-inline" onsubmit="return confirm('Xóa bài đăng này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form></td></tr>
@empty
<tr><td colspan="5" class="text-center text-muted py-4">Bạn chưa có bài đăng.</td></tr>
@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $posts->links() }}</div>
@endsection
