@extends('store.layout')

@section('content')
    <div class="register-container">
        <div class="register-card m-5" data-aos="fade-up" data-aos-duration="1000">
            <div class="register-form-section">
                <h1 class="register-title" data-aos="fade-right" data-aos-delay="100">Bergabung dengan Kami!</h1>
                <p class="register-subtitle" data-aos="fade-right" data-aos-delay="200">Buat akun Wasabi Garden dan nikmati
                    berbagai keuntungan</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group">
                        {{-- <i class="fas fa-user input-icon"></i> --}}
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        {{-- <i class="fas fa-user input-icon"></i> --}}
                        <i class="fa-brands fa-steam input-icon"></i>
                        <input type="text" class="form-control @error('steam_hex') is-invalid @enderror" id="steam_hex" name="steam_hex" placeholder="Steam Hex (steam:1100001588f2cf8)" value="{{ old('steam_hex') }}" required>
                        @error('steam_hex')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-register w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>

                <div class="success-checkmark" id="successCheckmark">
                    <svg viewBox="0 0 60 60">
                        <circle class="checkmark-circle" cx="30" cy="30" r="28" />
                        <path class="checkmark-check" d="M15 30 L25 40 L45 20" />
                    </svg>
                </div>

                <div class="login-link" data-aos="fade-up" data-aos-delay="600">
                    Sudah punya akun? <a href="{{ url('login') }}">Masuk di sini</a>
                </div>
            </div>

            <div class="register-image-section">
                <div class="register-image-content" data-aos="zoom-in" data-aos-delay="700">
                    <i class="fas fa-leaf fa-4x mb-4" style="color: white;"></i>
                    <h2>Keuntungan Member</h2>
                    <p>Bergabunglah dengan komunitas Wasabi Garden dan nikmati berbagai keuntungan eksklusif</p>
                    <ul class="benefits-list" data-aos="fade-up" data-aos-delay="800">
                        <li><i class="fas fa-check"></i> Diskon khusus member hingga 20%</li>
                        <li><i class="fas fa-gift"></i> Bonus poin setiap pembelian</li>
                        <li><i class="fas fa-shipping-fast"></i> Gratis ongkir untuk member</li>
                        <li><i class="fas fa-star"></i> Akses early sale eksklusif</li>
                        <li><i class="fas fa-headset"></i> Customer support prioritas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
