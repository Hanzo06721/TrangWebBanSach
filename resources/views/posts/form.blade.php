@extends('layouts.app')
@section('title', $post->exists ? 'Sửa bài đăng' : 'Đăng bài mới')

@section('content')
<div class="auth-wrap" style="max-width: 760px;">
    <div class="card card-body">
        <h1 class="page-title">{{ $post->exists ? 'Sửa bài đăng' : 'Đăng bài mới' }}</h1>
        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
            @csrf @method($method)
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Sách</label>
                    @php
                        $bookValue = old('BookName');
                        if (!$bookValue && $post->exists && $post->books) {
                            $bookValue = $post->books->Title . ($post->books->AuthorName ? ' - '.$post->books->AuthorName : '');
                        }
                    @endphp
                    <input type="text" name="BookName" class="form-control @error('BookName') is-invalid @enderror"
                           value="{{ $bookValue }}"
                           maxlength="220"
                           placeholder="Tên sách - Tên tác giả"
                           required>
                    @error('BookID')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('BookName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Danh mục sách</label>
                    <select name="CategoryID" class="form-select @error('CategoryID') is-invalid @enderror" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->CategoryID }}"
                                @selected(old('CategoryID', $post->books->CategoryID ?? null) === $category->CategoryID)>
                                {{ $category->CategoryName }}
                            </option>
                        @endforeach
                    </select>
                    @error('CategoryID')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tiêu đề bài đăng</label>
                    <input class="form-control" name="Title" value="{{ old('Title', $post->Title) }}" maxlength="100" required>
                </div>
                <div class="col-md-4"><label class="form-label">Loại bài đăng</label><select class="form-select" name="PostType" required>@foreach(['Bán','Trao đổi','Ký gửi','Quyên góp'] as $type)<option value="{{ $type }}" @selected(old('PostType', $post->PostType) === $type)>{{ $type }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Tình trạng</label><select class="form-select" name="Condition" required>@foreach(['Mới','Như mới','Tốt','Ổn','Cũ'] as $condition)<option value="{{ $condition }}" @selected(old('Condition', $post->Condition) === $condition)>{{ $condition }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Giá (VNĐ)</label><input class="form-control" type="number" min="0" name="Price" value="{{ old('Price', $post->Price) }}"></div>
                <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" name="Description" rows="4" maxlength="500">{{ old('Description', $post->Description) }}</textarea></div>
                <div class="col-12">
                    <label class="form-label">Ảnh bài đăng (tối đa 4 ảnh, mỗi ảnh dưới 5 MB)</label>
                    <input class="form-control" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
                    @error('images.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                @if($post->exists && $post->images->isNotEmpty())
                <div class="col-12">
                    <label class="form-label">Ảnh hiện tại</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->images as $image)
                        <label class="border rounded p-1">
                            <img src="{{ asset('storage/'.$image->ImagePath) }}" alt="" style="width:100px;height:100px;object-fit:cover;">
                            <span class="d-block small text-center"><input type="checkbox" name="remove_images[]" value="{{ $image->ImageID }}"> Xóa</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="d-flex gap-2 mt-4"><button class="btn btn-primary">{{ $post->exists ? 'Cập nhật' : 'Gửi kiểm duyệt' }}</button><a class="btn btn-outline-secondary" href="{{ $post->exists ? route('posts.mine') : route('posts.index') }}">Hủy</a></div>
        </form>
    </div>
</div>
@endsection
