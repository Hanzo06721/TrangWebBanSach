<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->merge(['Email' => Str::lower(trim((string) $request->input('Email')))]);

        $data = $request->validate([
            'FullName' => ['required', 'string', 'max:100'],
            'Email'    => ['required', 'email', 'max:100', Rule::unique('Users', 'Email')],
            'Phone'    => ['required', 'regex:/^0[0-9]{9}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        User::create([
            'UserID'       => User::generateId(),
            'Email'        => $data['Email'],
            'FullName'     => $data['FullName'],
            'PasswordHash' => Hash::make($data['password']),
            'RoleID'       => Role::where('RoleName', 'User')->value('RoleID') ?? 'R002', // quyền mặc định: Người dùng
            'Phone'        => $data['Phone'],
            'Status'       => true,
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy đăng nhập để tiếp tục.');
    }
}
