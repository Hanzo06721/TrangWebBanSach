<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\ValidationMessages;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Danh sách + tìm kiếm + lọc + phân trang */
    public function index(Request $request)
    {
        $query = User::with('role')->latest('CreatedAt');

        if ($keyword = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($keyword) {
                $q->where('FullName', 'like', "%{$keyword}%")
                  ->orWhere('Email', 'like', "%{$keyword}%")
                  ->orWhere('Phone', 'like', "%{$keyword}%")
                  ->orWhere('UserID', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('RoleID', $request->input('role'));
        }

        if (in_array($request->input('status'), ['0', '1'], true)) {
            $query->where('Status', $request->input('status') === '1');
        }

        return view('admin.users.index', [
            'users' => $query->paginate(10)->withQueryString(),
            'roles' => Role::orderBy('RoleID')->get(),
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user->load('role')]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user'  => $user,
            'roles' => Role::orderBy('RoleID')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->merge(['Email' => Str::lower(trim((string) $request->input('Email')))]);

        $data = $request->validate([
            'FullName' => ['required', 'string', 'max:100'],
            'Email'    => ['required', 'email', 'max:100', Rule::unique('Users', 'Email')->ignore($user->UserID, 'UserID')],
            'Phone'    => ['required', 'regex:/^0[0-9]{9}$/'],
            'Address'  => ['nullable', 'string', 'max:255'],
            'RoleID'   => ['required', Rule::exists('Roles', 'RoleID')],
            'Status'   => ['required', 'in:0,1'],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        $data['Status'] = $data['Status'] === '1';

        // Admin không được tự đổi quyền / tự khóa chính mình
        if ($user->is($request->user())) {
            unset($data['RoleID'], $data['Status']);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', 'Đã cập nhật thông tin người dùng.');
    }

    /** Khóa / mở khóa tài khoản */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
        }

        $user->update(['Status' => ! $user->Status]);

        return back()->with('success', $user->Status
            ? "Đã mở khóa tài khoản {$user->FullName}."
            : "Đã khóa tài khoản {$user->FullName}.");
    }

    /** Xóa tài khoản theo quy định: không xóa chính mình, không xóa Admin, không xóa tài khoản đã phát sinh dữ liệu */
    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể xóa tài khoản Quản trị viên. Hãy hạ quyền xuống Người dùng trước.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            // Vi phạm khóa ngoại: user đã có bài đăng / giao dịch / đánh giá...
            return back()->with('error', 'Tài khoản đã phát sinh dữ liệu (bài đăng, giao dịch...) nên không thể xóa. Hãy khóa tài khoản thay thế.');
        }

        if ($user->ProfilePicture && str_starts_with($user->ProfilePicture, 'avatars/')) {
            Storage::disk('public')->delete($user->ProfilePicture);
        }

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản.');
    }
}
