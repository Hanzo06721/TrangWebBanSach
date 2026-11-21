<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'Email' => ['required', 'email', 'exists:users,Email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'Email.required' => 'Vui lòng cung cấp email của bạn.',
            'Email.exists' => 'Email không tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->Email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'Email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã qua sử dụng.',
            ]);
        }

        // Hết hạn sau 60 phút
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->Email)->delete();

            return back()->withErrors([
                'Email' => 'Liên kết đặt lại mật khẩu này đã hết hạn. Vui lòng yêu cầu lại.',
            ]);
        }

        $user = User::where('Email', $request->Email)->first();
        if ($user) {
            $user->PasswordHash = Hash::make($request->password);
            $user->save();
        }

        DB::table('password_reset_tokens')->where('email', $request->Email)->delete();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay với mật khẩu mới.');
    }
}
