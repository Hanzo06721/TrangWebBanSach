@extends('layouts.app')
@section('title', 'Đăng ký')

@section('content')
    @include('auth.book', ['initialMode' => 'register'])
@endsection
