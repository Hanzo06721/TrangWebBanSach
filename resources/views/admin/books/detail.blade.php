@extends('layouts.app')

@section('title', 'Chi tiết sách')

@section('content')
    <div class="container">
        <h1 class="page-title mb-4">Chi tiết sách</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <h3>{{ $book->Title }}</h3>

                <div class="row g-3">
                    <div class="col md-4">
                        @if($book->CoverImage)
                            <img src="{{ asset('images/books/' . $book->CoverImage) }}" alt="Ảnh bìa sách"
                                style="max-width: 250px;">
                        @endif
                    </div>
                    <div class="col md-8">
                        <p><strong>Mã sách:</strong> {{ $book->BookID }}</p>
                        <p><strong>Tác giả:</strong> {{ $book->AuthorName }}</p>
                        <p><strong>ISBN:</strong> {{ $book->ISBN }}</p>
                        <p><strong>Nhà xuất bản:</strong> {{ $book->Publisher }}</p>
                        <p><strong>Mô tả:</strong> {{ $book->Description }}</p>
                    </div>

                </div>
                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>
                        Quay lại
                    </a>

                    <a href="{{ route('admin.books.edit', $book->BookID) }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square me-1"></i>
                        Sửa sách
                    </a>

                    <form action="{{ route('admin.books.destroy', $book->BookID) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa sách này không?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger px-4">
                            <i class="bi bi-trash me-1"></i>
                            Xóa sách
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection