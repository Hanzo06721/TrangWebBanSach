<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Quản lý quyền: xem các quyền và gán quyền cho người dùng */
class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount('users')->orderBy('RoleID')->get(),
            'users' => User::with('role')->orderBy('RoleID')->orderBy('FullName')->paginate(10),
        ]);
    }

    public function assign(Request $request, User $user)
    {
        $data = $request->validate([
            'RoleID' => ['required', Rule::exists('Roles', 'RoleID')],
        ], ValidationMessages::messages(), ValidationMessages::attributes());

        if ($user->is($request->user())) {
            return back()->with('error', 'Bạn không thể tự thay đổi quyền của chính mình.');
        }

        $user->update(['RoleID' => $data['RoleID']]);

        return back()->with('success', "Đã cập nhật quyền cho {$user->FullName}.");
    }
}
