<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    // Trang chủ tạm thời — các phân hệ sau (danh sách bài đăng...) sẽ thay thế
    public function index()
    {
        return view('home');
    }
}
