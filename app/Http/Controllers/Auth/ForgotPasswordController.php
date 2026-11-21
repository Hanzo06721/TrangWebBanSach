<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.login', ['initialMode' => 'forgot']);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'Email' => ['required', 'email', 'exists:users,Email'],
        ], [
            'Email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'Email.email' => 'Địa chỉ email không đúng định dạng.',
            'Email.exists' => 'Email này chưa được đăng ký trong hệ thống.',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->Email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $request->Email,
        ]);

        // Ghi lại link vào log hệ thống để tra cứu
        Log::info("Password reset requested for {$request->Email}: {$resetUrl}");

        $statusMsg = "Đã tạo liên kết đặt lại mật khẩu!";
        if (config('app.debug') || app()->environment('local')) {
            $statusMsg .= " (Thử nghiệm: Bạn có thể nhấp trực tiếp vào đây để đổi: <a href='{$resetUrl}' class='fw-bold text-decoration-underline text-success'>Đặt lại mật khẩu</a>)";
        } else {
            $statusMsg .= " Vui lòng kiểm tra email của bạn để tiếp tục.";
        }

        return back()->with([
            'status' => $statusMsg,
            '_mode' => 'forgot',
        ]);
    }
}
