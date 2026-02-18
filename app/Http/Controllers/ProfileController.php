<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Tampilkan form profile sesuai role
    public function edit(Request $request): View
    {
        $user = $request->user();

        return match($user->role) {
            'admin' => view('admin.profile.edit', compact('user')),
            'user'  => view('user.profile.edit', compact('user')),
            default => abort(403, 'Kamu tidak punya akses ke halaman ini'),
        };
    }

    // Update profile info
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle foto profile
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile'), $filename);

            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('uploads/profile/'.$user->photo))) {
                @unlink(public_path('uploads/profile/'.$user->photo));
            }

            $data['photo'] = $filename;
        }

        // Reset email verification jika email berubah
        if ($user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        // Redirect ke halaman profil sesuai role
        $route = $user->role === 'admin' ? 'admin.profile.edit' : 'user.profile.edit';
        return redirect()->route($route)->with('success', 'Profile berhasil diperbarui');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        $route = $user->role === 'admin' ? 'admin.profile.edit' : 'user.profile.edit';
        return redirect()->route($route)->with('success', 'Password berhasil diperbarui');
    }

    // Hapus akun
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus foto jika ada
        if ($user->photo && file_exists(public_path('uploads/profile/'.$user->photo))) {
            @unlink(public_path('uploads/profile/'.$user->photo));
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus');
    }
}