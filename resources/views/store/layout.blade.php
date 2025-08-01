<!DOCTYPE html>
<html lang="en">

@php
    $deskripsi_website = "Wasabi Garden adalah server FiveM semi-mmorpg yang menghadirkan pengalaman imersif dengan menggabungkan era tua dan elemen fantasi dalam sebuah desa unik, di mana pemain dapat berinteraksi dalam ekosistem ekonomi, eksplorasi, dan komunitas yang dinamis, menciptakan dunia yang berkembang melalui perdagangan, crafting, serta peran yang mereka jalani."
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Wasabi Store')</title>

    <meta name="description" content="{{ $deskripsi_website }}">
    <meta name="keywords" content="wasabi, wasabi garden, garden, fivem, gta 5, gta v, roleplay">
    <meta name="author" content="Rozir Wobari">
    <meta name="robots" content="index, follow">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/wasabi.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/wasabi.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/wasabi.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/wasabi.png') }}">

    <!-- Open Graph untuk social media (Facebook, WhatsApp, dll) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Wasabi Store')">
    <meta property="og:description" content="{{ $deskripsi_website }}">
    <meta property="og:image" content="{{ asset('images/logo/wasabi.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="Wasabi Store">
    <meta property="og:locale" content="id_ID">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('WabiHome/css/style.css') }}">
    <script src="https://kit.fontawesome.com/111b8c6336.js" crossorigin="anonymous"></script>
    <link href="{{ asset('WabiHome/lib/lightbox2/css/lightbox.min.css') }}" rel="stylesheet" />
</head>

@yield('css')

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">WASABI <span style="color: white;">GARDEN</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                </ul>
                <div class="d-flex align-items-center">
                    @if(Auth::check())
                        <div class="ms-auto d-flex align-items-center">
                            <!-- Enhanced Cart Button -->
                            <a href="{{ url('cart') }}" class="cart-button">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count">{{ count($carts) }}</span>
                            </a>
                            <!-- Enhanced Profile Dropdown -->
                            <div class="profile-dropdown dropdown">
                                <a href="#" class="profile-toggle dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <div class="profile-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="profile-name">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li class="dropdown-header-section">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small style="opacity: 0.8;">{{ Auth::user()->email }}</small>
                                    </li>
                                    <li><a class="dropdown-item {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                    <li><a class="dropdown-item {{ request()->is('profile') ? 'active' : '' }}" href="{{ url('profile') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
                                    <li><a class="dropdown-item {{ (request()->is('dataplayers') || request()->is('dataplayers/*')) ? 'active' : '' }}" href="{{ url('dataplayers') }}"><i class="fa-solid fa-layer-group"></i> Alamat Player Data</a></li>
                                    <li><a class="dropdown-item {{ (request()->is('orders') || request()->is('order-details/*')) ? 'active' : '' }}" href="{{ url('orders') }}"><i class="fa-solid fa-box"></i> Orders</a></li>
                                    <li><a class="dropdown-item {{ request()->is('settings') ? 'active' : '' }}" href="{{ url('settings') }}"><i class="fa-solid fa-cog"></i> Settings</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ url('logout') }}" method="post">
                                            @csrf
                                            <button class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav me-auto">
                                </ul>
                                <div class="d-flex align-items-center">
                                    <div class="auth-buttons">
                                        <a href="{{ url('login') }}" class="nav-auth-btn login-btn">
                                            <i class="fas fa-sign-in-alt"></i>Login
                                        </a>
                                        <a href="{{ url('register') }}" class="nav-auth-btn register-btn">
                                            <i class="fas fa-user-plus"></i>Daftar
                                        </a>
                                    </div>
                                </div>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    @yield("content")

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mb-4 mb-md-0">
                    <h5 class="footer-logo fw-bold">Wasabi <span>Garden</span></h5>
                    <p class="small" style="color: #aaa;">Server FiveM semi-mmorpg yang menghadirkan pengalaman imersif dengan menggabungkan era tua dan elemen fantasi dalam sebuah desa unik.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-8 mb-6 mb-md-0">
                    <h5>Newsletter</h5>
                    <p class="small" style="color: #aaa;">Berlangganan Newsletter kami untuk mendapatkan pembaruan dan
                        penawaran terbaru.</p>
                    <form>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email Kamu" aria-label="Email Kamu">
                            <button class="btn" type="submit" style="background-color: #3e4c2c; color: white;">Berlangganan</button>
                        </div>
                    </form>
                    <p class="small text-muted mt-2">We respect your privacy and will never share your information.</p>
                </div>
            </div>

            <hr class="my-4" style="background-color: rgba(255, 255, 255, 0.1);">

            <div class="row align-items-center">
                <div class="col-md-6 small">
                    <p class="mb-md-0">© 2025 Wasabi Garden. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <img src="" alt="Payment Methods" class="img-fluid"
                        style="max-width: 250px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="{{ asset('WabiHome/js/script.js') }}"></script>

    <script src="{{ asset('WabiHome/lib/lightbox2/js/lightbox-plus-jquery.js') }}"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Animate stat cards on hover
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "{!! json_encode(trim(strip_tags(isset($title) ? $title : 'Wasabi Store'))) !!}",
        "description": "{!! json_encode(trim(strip_tags(isset($deskripsi_website) ? $deskripsi_website : 'Deskripsi default'))) !!}",
        "url": "{!! json_encode(url('/')) !!}",
        "mainEntity": {
            "@type": "Organization",
            "name": "Wasabi Garden",
            "url": "{!! json_encode(url('/')) !!}",
            "logo": "{!! json_encode(asset('images/home/logo/wasabi.png')) !!}"
        }
    }
    </script>

    @section('scripts')
        @if (session('alert') && session('alert')['title'])
            <script>
                Swal.fire({
                    title: "{{ session('alert')['title'] }}",
                    text: "{{ session('alert')['message'] }}",
                    icon: "{{ session('alert')['type'] }}"
                });
            </script>
        @endif
    @endsection
    @yield('scripts')
</body>

</html>
