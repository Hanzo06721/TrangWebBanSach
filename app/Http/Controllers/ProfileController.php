<?php

namespace App\Http\Controllers;

use App\Support\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', ['user' => $request->user()->load('role')]);
    }

    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'FullName' => ['required', 'string', 'max:100'],
            'Phone'    => ['required', 'regex:/^0[0-9]{9}$/'],
            'Address'  => ['nullable', 'string', 'max:255'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        $request->user()->update($data);

        return redirect()->route('profile.show')->with('success', 'Đã cập nhật thông tin cá nhân.');
    }

    /* ---------- Đổi mật khẩu ---------- */

    public function passwordForm()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        $request->user()->update(['PasswordHash' => Hash::make($request->input('password'))]);

        return redirect()->route('profile.show')->with('success', 'Đổi mật khẩu thành công.');
    }

    /* ---------- Ảnh đại diện ---------- */

    public function avatarForm(Request $request)
    {
        return view('profile.avatar', ['user' => $request->user()]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        $user = $request->user();
        $this->deleteOldAvatar($user->ProfilePicture);

        // lưu vào storage/app/public/avatars -> cột ProfilePicture = 'avatars/xxxx.jpg'
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['ProfilePicture' => $path]);

        return redirect()->route('profile.show')->with('success', 'Đã cập nhật ảnh đại diện.');
    }

    public function destroyAvatar(Request $request)
    {
        $user = $request->user();
        $this->deleteOldAvatar($user->ProfilePicture);
        $user->update(['ProfilePicture' => null]);

        return redirect()->route('profile.show')->with('success', 'Đã xóa ảnh đại diện.');
    }

    private function deleteOldAvatar(?string $path): void
    {
        if ($path && str_starts_with($path, 'avatars/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
