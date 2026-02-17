<x-guest-layout>

<form method="POST" action="{{ route('register') }}">
@csrf


<!-- Nama -->
<div class="mt-4">
<x-input-label value="Nama" />
<x-text-input name="name"
type="text"
:value="old('name')"
required
class="block mt-1 w-full"/>
<x-input-error :messages="$errors->get('name')" />
</div>


<!-- Email -->
<div class="mt-4">
<x-input-label value="Email" />
<x-text-input name="email"
type="email"
:value="old('email')"
required
class="block mt-1 w-full"/>
<x-input-error :messages="$errors->get('email')" />
</div>


<!-- Username -->
<div class="mt-4">
<x-input-label value="Username" />
<x-text-input name="username"
type="text"
:value="old('username')"
required
class="block mt-1 w-full"/>
<x-input-error :messages="$errors->get('username')" />
</div>


<!-- No HP -->
<div class="mt-4">
<x-input-label value="No HP" />
<x-text-input name="no_hp"
type="text"
:value="old('no_hp')"
required
class="block mt-1 w-full"/>
<x-input-error :messages="$errors->get('no_hp')" />
</div>


<!-- Outlet Dropdown -->
<div class="mt-4">

<x-input-label value="Pilih Outlet" />

<select name="outlet_id"
class="block mt-1 w-full border-gray-300 rounded">

<option value="">-- Pilih Outlet --</option>

@foreach($outlets as $outlet)

<option value="{{ $outlet->id }}">

{{ $outlet->nama_outlet }}

</option>

@endforeach

</select>

<x-input-error :messages="$errors->get('outlet_id')" />

</div>


<!-- Password -->
<div class="mt-4">
<x-input-label value="Password" />
<x-text-input name="password"
type="password"
required
class="block mt-1 w-full"/>
<x-input-error :messages="$errors->get('password')" />
</div>


<!-- Confirm -->
<div class="mt-4">
<x-input-label value="Confirm Password" />
<x-text-input name="password_confirmation"
type="password"
required
class="block mt-1 w-full"/>
</div>


<div class="flex items-center justify-end mt-4">

<a href="{{ route('login') }}">
Sudah punya akun?
</a>


<x-primary-button class="ml-4">
Register
</x-primary-button>

</div>


</form>

</x-guest-layout>
