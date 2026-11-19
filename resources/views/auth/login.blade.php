@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
    @include('auth.book', ['initialMode' => 'login'])
@endsection
