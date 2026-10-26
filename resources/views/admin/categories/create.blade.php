@extends('layouts.app')
@section('title', 'Thêm danh mục sách')

@section('content')
<div class="container">
    <h1 class="page-title mb-4">Thêm danh mục sách</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại dữ liệu!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                @include('admin.categories.form')
            </form>
            
        </div>
    </div>
</div>
@endsection