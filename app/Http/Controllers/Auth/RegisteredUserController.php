<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    public function create()
    {
        $outlets = Outlet::all();

        return view('auth.register', compact('outlets'));
    }


    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'username' => 'required|string|max:255|unique:users',

            'no_hp' => 'required|string|max:20',

            'outlet_id' => 'required|exists:outlets,id',

            'password' => ['required', 'confirmed', Rules\Password::defaults()],

        ]);


        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'username' => $request->username,

            'no_hp' => $request->no_hp,

            'outlet_id' => $request->outlet_id,

            'role' => 'user',

            'password' => Hash::make($request->password),

        ]);


        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');

    }

}
