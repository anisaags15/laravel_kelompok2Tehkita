<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group-custom">
            <label class="input-label-custom">Email Address</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" value="{{ old('email') }}" class="input-box-custom @error('email') is-invalid @enderror" placeholder="email@gmail.com" required autofocus>
            </div>
            @error('email') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="input-group-custom">
            <label class="input-label-custom">Password</label>
            <div class="input-wrapper">
                <i class="bi bi-shield-lock-fill"></i>
                <input type="password" name="password" class="input-box-custom @error('password') is-invalid @enderror" placeholder="••••••••" required>
            </div>
            @error('password') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
            <label style="font-size: 11px; color: #6b7280;"><input type="checkbox" name="remember"> Ingat saya</label>
            <a href="{{ route('password.request') }}" class="auth-link" style="font-size: 11px;">Lupa Sandi?</a>
        </div>

        <button type="submit" class="btn-tea-primary">Masuk Sekarang</button>
        
        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar</a>
        </div>
    </form>
</x-guest-layout>