<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Email'    => ['required', 'email', 'max:100'],
            'password' => ['required', 'string'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        $user = User::with('role')->where('Email', Str::lower(trim($data['Email'])))->first();

        // 1. Tài khoản tồn tại? 2. Mật khẩu đúng? (cùng một thông báo để không lộ email nào đã đăng ký)
        if (! $user || ! $this->passwordMatches($data['password'], $user->PasswordHash)) {
            return back()->withInput($request->only('Email'))
                ->withErrors(['Email' => 'Email hoặc mật khẩu không chính xác.']);
        }

        // 3. Tài khoản có đang bị khóa?
        if (! $user->Status) {
            return back()->withInput($request->only('Email'))
                ->withErrors(['Email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
        }

        // 4. Tạo phiên đăng nhập
        Auth::login($user);
        $request->session()->regenerate();

        // 5. Điều hướng theo quyền
        $default = $user->isAdmin() ? route('admin.users.index') : route('home');

        return redirect()->intended($default)->with('success', 'Xin chào ' . $user->FullName . '!');
    }

    public function destroy(Request $request)
    {
        Auth::logout();                                  // hủy phiên đăng nhập
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất.');
    }

    private function passwordMatches(string $plain, ?string $hash): bool
    {
        if (! $hash) {
            return false;
        }
        try {
            return Hash::check($plain, $hash);
        } catch (RuntimeException $e) {
            // PasswordHash không phải bcrypt (vd: dữ liệu mẫu 'hash_admin') -> coi như sai
            return false;
        }
    }
}
