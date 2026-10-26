@extends('layouts.app')
@section('title', 'Quản lý sách')

@section('content')
<style>
    .bookcard
    {
        width: 30%;
        height: 30%;

    }
</style>


<h1 class="page-title">Quản lý sách</h1>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">


    <div class="d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.books.create') }}"
           class="btn btn-primary px-3 py-2">
            <i class="bi bi-plus-circle me-1"></i>
            Thêm sách
        </a>

        <a href="{{ route('admin.books.export') }}"
           class="btn btn-outline-success px-3 py-2">
            <i class="bi bi-file-earmark-excel me-1"></i>
            Xuất Excel
        </a>

        <form action="{{ route('admin.books.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="d-flex align-items-center flex-wrap gap-2">
            @csrf

            <input type="file"
                   name="file"
                   accept=".xlsx,.xls,.csv"
                   class="form-control"
                   style="max-width: 230px;"
                   required>

            <button type="submit" class="btn btn-outline-primary px-3 py-2">
                <i class="bi bi-file-earmark-arrow-up me-1"></i>
                Nhập Excel
            </button>
        </form>
    </div>
</div>

</div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.books.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Nhập mã hoặc tên tựa sách..."
                            value="{{ request('keyword') }}" />
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                        <div class="col-md-6">
                        <label class="form-label">Thể loại</label>
                        <select name="CategoryID" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->CategoryID }}" {{request('CategoryID') === $category->CategoryID ? 'selected': ''}}>
                                    {{ $category->CategoryName }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label">Thời điểm</label>
                        <select name="sort" class="form-select">
                            <option value="">Mặc định</option>
                            <option value="newest" {{ request('sort') ==='newest'?'selected':'' }}>
                                Mới nhất
                            </option>
                            <option value="oldest" {{ request('sort') ==='oldest'?'selected':''}}>Cũ nhất</option>
                        </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        <a href="{{route('admin.books.index')}}" class="btn btn-secondary">Làm mới</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="row g-3">
        @foreach($books as $book)
            <div class="col-md-4  col-lg-3">
                <a href="{{ route('admin.books.show', $book->BookID) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/books/'.$book->CoverImage) }}"
                        class="card-img-top"
                        alt="Ảnh bìa sách"
                        style="height:200px;object-fit:cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->Title }}</h5>
                        <p class="text-muted mb-2">Tác giả: {{ $book->AuthorName }}</p>
                        <p class="text-muted mb-2">ISBN: {{$book->ISBN }}</p>
                    </div>
                </a>
            </div>
            
            
        @endforeach
        <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
        </div>
    </div>
    
@endsection