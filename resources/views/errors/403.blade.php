@extends('layouts.app')
@section('title', 'Không có quyền truy cập')

@section('content')
<div class="text-center py-5">
    <h1 class="page-title">403 – Không có quyền truy cập</h1>
    <p class="mb-4">{{ $exception->getMessage() ?: 'Bạn không có quyền truy cập trang này.' }}</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Về trang chủ</a>
</div>
@endsection
