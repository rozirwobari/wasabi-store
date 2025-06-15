@extends('dashboard.auth.layout')

@section('content')
    <div class="login-card">
        <div class="login-header">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1 class="login-title">Admin Portal</h1>
            <p class="login-subtitle">Masuk ke dashboard administrator</p>
        </div>

        <div class="login-body">
            <form action="{{ route('authadmin') }}" method="POST" class="login-form">
                @csrf
                <div class="form-floating">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                     @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <div class="remember-me">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Masuk Dashboard
                    <div class="loading"></div>
                </button>

                <div class="forgot-password">
                    <a href="#" onclick="forgotPassword()">Lupa password?</a>
                </div>
            </form>
        </div>
    </div>
@endsection
