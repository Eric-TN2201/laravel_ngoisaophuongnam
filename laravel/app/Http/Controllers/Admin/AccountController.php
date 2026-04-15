<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AccountController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('admin.account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $user->name = $data['name'];
            $user->save();

            return redirect()->route('admin.account.edit')
                ->with('success', 'Thông tin tài khoản đã được cập nhật');
        } catch (Throwable $e) {
            Log::error('Update account failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Cập nhật thất bại, vui lòng thử lại');
        }
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
        }

        try {
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return redirect()->route('admin.account.edit')
                ->with('success', 'Mật khẩu đã được cập nhật');
        } catch (Throwable $e) {
            Log::error('Update password failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Cập nhật mật khẩu thất bại, vui lòng thử lại');
        }
    }
}
