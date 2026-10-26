@extends('layouts.app')
@section('title', 'Quản lý danh mục sách')

@section('content')
    <style>
        .xem {
            background-color: #FDAAAA;
            color: #7C0E44;
            font-weight: bold;
            padding: 10px;
        }
        .xem:hover
        {
            background-color: #e77878;
            color: #54082d;
            font-weight: bold;
            padding: 10px;
        }

        .sua {
            background-color: #FFEA88;
            color: #021A54;
            font-weight: bold;
            padding: 10px;
        }
        .sua:hover 
        {
            background-color: #f7dc64;
            color: #010f2e;
            font-weight: bold;
            padding: 10px;
        }
    </style>
    <h1 class="page-title">Quản lý danh mục sách</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.categories.create')}}" class="btn btn-primary">
            <i class="bi bi-plus-circle">Thêm danh mục</i>
        </a>

    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.categories.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Nhập mã hoặc tên danh mục..."
                            value="{{ request('keyword') }}" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{request('status') === '1' ? 'selected' : ''}}>Hoạt động</option>
                            <option value="0" {{request('status') === '0' ? 'selected' : ''}}>Ngừng hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        <a href="{{route('admin.categories.index')}}" class="btn btn-secondary">Làm mới</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Danh sách danh mục</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>STT</th>
                            <th>Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="text-center">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    {{ $category->CategoryID }}
                                </td>
                                <td>
                                    {{ $category->CategoryName }}
                                </td>
                                <td>
                                    {{$category->Description ?: '' }}
                                </td>
                                <td class="text-center">
                                    @if($category->Status)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Ngừng hoạt động</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $category->CreatedAt ? $category->CreatedAt->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('admin.categories.edit',['category'=> $category->CategoryID ])}}"
                                        class="btn btn-sm sua">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category->CategoryID) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá danh mục này không?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm xem">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p>Không tìm thấy mục nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection