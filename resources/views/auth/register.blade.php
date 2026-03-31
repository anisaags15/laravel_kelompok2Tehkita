<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf
        
        <div class="input-group-custom">
            <label class="input-label-custom">Nama Lengkap</label>
            <div class="input-wrapper">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="name" value="{{ old('name') }}" class="input-box-custom @error('name') is-invalid @enderror" placeholder="Nama" required autocomplete="off">
            </div>
            @error('name') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Username</label>
            <div class="input-wrapper">
                <i class="bi bi-at"></i>
                <input type="text" name="username" value="{{ old('username') }}" class="input-box-custom @error('username') is-invalid @enderror" placeholder="Username" required autocomplete="off">
            </div>
            @error('username') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Email</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope-at-fill"></i>
                <input type="email" name="email" value="{{ old('email') }}" class="input-box-custom @error('email') is-invalid @enderror" placeholder="email@tehkita.com" required autocomplete="off">
            </div>
            @error('email') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">No. HP</label>
            <div class="input-wrapper">
                <i class="bi bi-telephone-fill"></i>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="input-box-custom @error('no_hp') is-invalid @enderror" placeholder="08..." required autocomplete="off">
            </div>
            @error('no_hp') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Pilih Outlet</label>
            <div class="input-wrapper">
                <i class="bi bi-shop"></i>
                <select name="outlet_id" class="input-box-custom @error('outlet_id') is-invalid @enderror" style="padding-left: 38px;">
                    <option value="">-- Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->nama_outlet }}</option>
                    @endforeach
                </select>
            </div>
            @error('outlet_id') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Password</label>
            <div class="input-wrapper">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" class="input-box-custom @error('password') is-invalid @enderror" placeholder="••••" required autocomplete="new-password">
            </div>
            @error('password') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Konfirmasi</label>
            <div class="input-wrapper">
                <i class="bi bi-check-circle-fill"></i>
                <input type="password" name="password_confirmation" class="input-box-custom" placeholder="••••" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-tea-primary">Daftar Akun</button>

        <div class="auth-footer">
            Sudah ada akun? <a href="{{ route('login') }}" class="auth-link">Login</a>
        </div>
    </form>
</x-guest-layout>