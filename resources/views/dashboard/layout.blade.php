<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wasabi Store - @yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('WabiHome/lib/lightbox2/css/lightbox.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --primary-green: #3e4c2c;
            --primary-red: #ab343e;
            --primary-gold: #e7a65c;
            --light-green: rgba(62, 76, 44, 0.1);
            --light-red: rgba(171, 52, 62, 0.1);
            --light-gold: rgba(231, 166, 92, 0.1);
            --dark-bg: #1a1a1a;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-green), #2d3a1f);
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar.collapsed .sidebar-header h3,
        .sidebar.collapsed .sidebar-header .subtitle,
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            position: relative;
        }

        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .sidebar-header .subtitle {
            color: var(--primary-gold);
            font-size: 0.9rem;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 15px 20px;
            border-radius: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            transition: width 0.3s ease;
            z-index: 1;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white !important;
            border-left-color: var(--primary-gold);
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .nav-link span {
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .toggle-btn {
            background: var(--primary-green);
            border: none;
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .toggle-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .toggle-btn:hover::before {
            width: 100px;
            height: 100px;
        }

        .toggle-btn:hover {
            background: var(--primary-red);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
        }

        .stats-card {
            border: none;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-card.green {
            background: linear-gradient(135deg, var(--primary-green), #4a5a37);
            color: white;
        }

        .stats-card.red {
            background: linear-gradient(135deg, var(--primary-red), #c23e49);
            color: white;
        }

        .stats-card.gold {
            background: linear-gradient(135deg, var(--primary-gold), #f2b76d);
            color: white;
        }

        .stats-card.white {
            background: linear-gradient(135deg, white, #f8f9fa);
            color: var(--primary-green);
            border: 1px solid rgba(62, 76, 44, 0.1);
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(10deg);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .table-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-green), #4a5a37);
            color: white;
            padding: 25px;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), #4a5a37);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-red), #c23e49);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--primary-gold), #f2b76d);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 166, 92, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--primary-red), #c23e49);
            border: none;
            border-radius: 10px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
        }

        .badge-success {
            background: var(--primary-green) !important;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-warning {
            background: var(--primary-gold) !important;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-danger {
            background: var(--primary-red) !important;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .progress {
            height: 12px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-green), #4a5a37);
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-gold), #f2b76d);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(231, 166, 92, 0.3);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        .notification-badge {
            background: var(--primary-red);
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 0.75rem;
            position: absolute;
            top: -8px;
            right: -8px;
            animation: pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(171, 52, 62, 0.4);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .activity-item {
            padding: 20px;
            border-left: 4px solid var(--primary-gold);
            margin-bottom: 15px;
            background: white;
            border-radius: 0 15px 15px 0;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--light-gold), transparent);
            transition: width 0.3s ease;
        }

        .activity-item:hover::before {
            width: 100%;
        }

        .activity-item:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .activity-time {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-green);
            transition: all 0.2s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .top-navbar {
                padding: 10px 15px;
            }

            .stats-card {
                margin-bottom: 15px;
                padding: 20px;
            }

            .stats-number {
                font-size: 2rem;
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    @yield('css')
</head>

<body>
    @include('dashboard.sidebar')
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center glass-effect">
            <div class="d-flex align-items-center">
                <button class="toggle-btn" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="ms-3 mb-0 text-dark">@yield('title')</h4>
            </div>
            <div class="d-flex align-items-center">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="ms-2 text-dark fw-bold">Admin User</span>
            </div>
        </div>
        @yield('content')
    </div>
    <script src="{{ asset('WabiHome/lib/lightbox2/js/lightbox-plus-jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Enhanced Toggle Sidebar
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileBackdrop = document.getElementById('mobileBackdrop');

        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                mobileBackdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        });

        @if (session()->has('alert'))
            Swal.fire({
                title: "{{ session('alert')['title'] }}",
                text: "{{ session('alert')['text'] }}",
                icon: "{{ session('alert')['type'] }}"
            });
        @endif
    </script>
    @yield('scripts')
</body>

</html>
