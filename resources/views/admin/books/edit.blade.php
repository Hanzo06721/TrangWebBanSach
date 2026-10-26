@extends('layouts.app')
@section('title', 'Sửa thông tin sách')

@section('content')
<div class="container">
    <h1 class="page-title mb-4">Sửa thông tin sách</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-dange">
                    <strong>Vui lòng kiểm tra lại dữ liệu!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.books.update', ['book'=>$book->BookID]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.books.form')
            </form>
            
        </div>
    </div>
</div>
@endsection