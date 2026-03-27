<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="input-group-custom">
            <label class="input-label-custom">Nama Lengkap</label>
            <div class="input-wrapper">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="name" class="input-box-custom" placeholder="Nama" required>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Username</label>
            <div class="input-wrapper">
                <i class="bi bi-at"></i>
                <input type="text" name="username" class="input-box-custom" placeholder="Username" required>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Email</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope-at-fill"></i>
                <input type="email" name="email" class="input-box-custom" placeholder="email@tehkita.com" required>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">No. HP</label>
            <div class="input-wrapper">
                <i class="bi bi-telephone-fill"></i>
                <input type="text" name="no_hp" class="input-box-custom" placeholder="08..." required>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Pilih Outlet</label>
            <div class="input-wrapper">
                <i class="bi bi-shop"></i>
                <select name="outlet_id" class="input-box-custom" style="padding-left: 38px;">
                    <option value="">-- Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->nama_outlet }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Password</label>
            <div class="input-wrapper">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" class="input-box-custom" placeholder="••••" required>
            </div>
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Konfirmasi</label>
            <div class="input-wrapper">
                <i class="bi bi-check-circle-fill"></i>
                <input type="password" name="password_confirmation" class="input-box-custom" placeholder="••••" required>
            </div>
        </div>

        <button type="submit" class="btn-tea-primary">Daftar Akun</button>

        <div class="auth-footer">
            Sudah ada akun? <a href="{{ route('login') }}" class="auth-link">Login</a>
        </div>
    </form>
</x-guest-layout>