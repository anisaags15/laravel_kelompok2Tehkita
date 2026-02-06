<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'no_hp'    => 'nullable|string|max:20',
        ]);

        $user = auth()->user();

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'no_hp'    => $request->no_hp,
        ]);

        // 🔥 PINDAH KE DASHBOARD ADMIN
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Profile berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Password berhasil diperbarui');
    }
}
