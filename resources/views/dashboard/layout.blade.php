<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wasabi Garden - Admin Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    {{-- Lightbox --}}
    <link href="{{ asset('WabiHome/lib/lightbox2/css/lightbox.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --green: #3e4c2c;
            --red: #ab343e;
            --gold: #e7a65c;
            --dark-gray: #2a2a2a;
            --light-gray: #f7f7f7;
            --blue: #2196f3;
            --purple: #9c27b0;
            --orange: #ff9800;
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            min-height: 100vh;
        }


        /* Dashboard Specific Styles */
        .dashboard-container {
            padding: 30px 0;
        }

        .sidebar {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 30px;
            position: sticky;
            top: 20px;
        }

        .admin-profile {
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            margin-bottom: 25px;
        }

        .admin-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red) 0%, #d63a46 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .admin-avatar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        .admin-name {
            font-weight: 700;
            color: var(--green);
            margin-bottom: 5px;
        }

        .admin-role {
            color: var(--red);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-menu a i {
            width: 20px;
            margin-right: 10px;
            color: var(--gold);
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(171, 52, 62, 0.1);
            color: var(--red);
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background-color: rgba(171, 52, 62, 0.15);
        }

        .main-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            min-height: 600px;
        }

        .section-title {
            font-weight: 700;
            color: var(--green);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(231, 166, 92, 0.2);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card.revenue {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(76, 175, 80, 0.15) 100%);
        }

        .stat-card.orders {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.05) 0%, rgba(33, 150, 243, 0.15) 100%);
        }

        .stat-card.users {
            background: linear-gradient(135deg, rgba(156, 39, 176, 0.05) 0%, rgba(156, 39, 176, 0.15) 100%);
        }

        .stat-card.products {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.05) 0%, rgba(255, 152, 0, 0.15) 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .stat-card.revenue .stat-icon {
            background: #4caf50;
        }

        .stat-card.orders .stat-icon {
            background: var(--blue);
        }

        .stat-card.users .stat-icon {
            background: var(--purple);
        }

        .stat-card.products .stat-icon {
            background: var(--orange);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .stat-change.positive {
            color: #4caf50;
        }

        .stat-change.negative {
            color: var(--red);
        }

        .data-table {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 20px;
            overflow-x: auto;
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: var(--green);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: rgba(231, 166, 92, 0.05);
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
        }

        .status-active {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4caf50;
        }

        .status-inactive {
            background-color: rgba(158, 158, 158, 0.2);
            color: #9e9e9e;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ff9800;
        }

        .status-completed {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4caf50;
        }

        .status-processing {
            background-color: rgba(33, 150, 243, 0.2);
            color: #2196f3;
        }

        .status-shipped {
            background-color: rgba(156, 39, 176, 0.2);
            color: #9c27b0;
        }

        .status-canceled {
            background-color: rgba(244, 67, 54, 0.2);
            color: #f44336;
        }

        .status-unknown {
            background-color: rgba(175, 76, 76, 0.2);
            color: #c0c0c0;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--red) 0%, #d63a46 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
            color: white;
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, var(--gold) 0%, #d4965a 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 166, 92, 0.3);
            color: white;
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, var(--red) 0%, #d63a46 100%);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
            color: white;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--green);
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 0.2rem rgba(62, 76, 44, 0.1);
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        /* Product Image Styles */
        .product-image-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .product-image-wrapper:hover {
            border-color: var(--gold);
            transform: scale(1.1);
        }

        .product-image {
            width: 250px;
            height: 250px;
            object-fit: cover;
            object-position: center;
            transition: all 0.3s;
            transform: scale(0.24);
            transform-origin: center;
        }

        .product-info strong {
            color: var(--green);
            font-weight: 600;
        }

        .product-info small {
            font-size: 0.75rem;
            line-height: 1.2;
        }

        /* Stock Badge Styles */
        .stock-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
            display: inline-block;
        }

        .stock-good {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4caf50;
        }

        .stock-medium {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ff9800;
        }

        .stock-low {
            background-color: rgba(255, 152, 0, 0.2);
            color: #ff9800;
        }

        .stock-out {
            background-color: rgba(244, 67, 54, 0.2);
            color: #f44336;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action-btn {
            background: white;
            border: 2px solid var(--gold);
            color: var(--green);
            padding: 15px 20px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
            text-align: center;
            font-weight: 600;
        }

        .quick-action-btn:hover {
            background: var(--gold);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(231, 166, 92, 0.3);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        .form-header {
            color: #3e4c2c;
            padding: 25px 30px;
            margin: -30px -30px 0px -30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-header h4 {
            margin: 0;
            font-weight: 600;
            display: flex;
            color: #3e4c2c;
            align-items: center;
        }

        .form-header i {
            margin-right: 10px;
            font-size: 1.2em;
        }
    </style>
    @yield('css')
</head>

<body>


    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <div class="container">
            <div class="row">
                @include('dashboard.sidebar')
                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- lightbox --}}
    <script src="{{ asset('WabiHome/lib/lightbox2/js/lightbox-plus-jquery.js') }}"></script>
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

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Quick action buttons functionality
        const quickActionBtns = document.querySelectorAll('.quick-action-btn');
        quickActionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.textContent.trim();
                const originalColor = this.style.backgroundColor;
                this.style.backgroundColor = '#4caf50';
                this.style.color = 'white';

                setTimeout(() => {
                    this.style.backgroundColor = originalColor;
                    this.style.color = '';
                    alert(`${action} executed successfully!`);
                }, 1000);
            });
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
