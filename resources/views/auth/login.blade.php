@extends('store.layout')

@section('content')
    <div class="login-container">
        <div class="login-card m-5" data-aos="fade-up" data-aos-duration="1000">
            <div class="login-form-section">
                <h1 class="login-title" data-aos="fade-right" data-aos-delay="100">Selamat Datang!</h1>
                <p class="login-subtitle" data-aos="fade-right" data-aos-delay="200">Masuk ke akun Wasabi Garden Anda
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            id="password" placeholder="Password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        {{-- <a href="#" class="forgot-password">Lupa password?</a> --}}
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </form>

                <div class="signup-link" data-aos="fade-up" data-aos-delay="600">
                    Belum punya akun? <a href="{{ url('register') }}">Daftar sekarang</a>
                </div>
            </div>

            <div class="login-image-section">
                <div class="login-image-content" data-aos="zoom-in" data-aos-delay="700">
                    <i class="fas fa-leaf fa-5x mb-4" style="color: var(--gold);"></i>
                    <h2>Wasabi Garden</h2>
                    <p>Temukan pengalaman berbelanja unik di server FiveM semi-MMORPG kami</p>
                </div>
            </div>
        </div>
    </div>
@endsection
