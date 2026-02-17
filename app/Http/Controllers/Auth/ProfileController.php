<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;


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
    $user = auth()->user();

    $request->validate([
        'name' => 'required',
        'username' => 'required',
        'email' => 'required|email',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    if ($request->hasFile('photo')) {

        // hapus foto lama
        if ($user->photo && File::exists(public_path('uploads/profile/' . $user->photo))) {
            File::delete(public_path('uploads/profile/' . $user->photo));
        }

        $file = $request->file('photo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);

        $user->photo = $filename;
    }

    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->no_hp = $request->no_hp;
    $user->save();

    return back()->with('success', 'Profile berhasil diupdate');
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
