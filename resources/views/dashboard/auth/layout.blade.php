<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #3e4c2c;
            --primary-red: #ab343e;
            --primary-gold: #e7a65c;
            --gradient-bg: linear-gradient(135deg, #3e4c2c 0%, #2d3922 50%, #1e2618 100%);
            --card-shadow: 0 20px 40px rgba(62, 76, 44, 0.3);
            --input-focus: rgba(231, 166, 92, 0.3);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Animation */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(231, 166, 92, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(171, 52, 62, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            position: relative;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2d3922 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold) 0%, var(--primary-red) 100%);
        }

        .admin-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-gold) 0%, #f4b56a 100%);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            color: white;
            box-shadow: 0 10px 30px rgba(231, 166, 92, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-floating {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px 15px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            height: 60px;
        }

        .form-control:focus {
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 0.2rem var(--input-focus);
            background: white;
            transform: translateY(-2px);
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
        }

        .form-control:focus ~ label,
        .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-gold);
        }

        .input-group-text {
            background: var(--primary-green);
            border: 2px solid var(--primary-green);
            color: white;
            border-radius: 12px 0 0 12px;
            width: 55px;
            justify-content: center;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            padding: 5px;
            border-radius: 4px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-gold);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-red) 0%, #c13b47 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(171, 52, 62, 0.4);
            background: linear-gradient(135deg, #c13b47 0%, var(--primary-red) 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background: var(--primary-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .forgot-password a:hover {
            color: var(--primary-gold);
        }

        .forgot-password a:hover::after {
            width: 100%;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #dee2e6;
            border-radius: 4px;
            margin-right: 10px;
        }

        .form-check-input:checked {
            background-color: var(--primary-gold);
            border-color: var(--primary-gold);
        }

        .form-check-label {
            color: #6c757d;
            font-weight: 500;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
            
            .admin-icon {
                width: 70px;
                height: 70px;
                font-size: 30px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading .loading {
            display: inline-block;
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="login-container">
        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Input Animation Enhancement
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.style.transform = 'translateY(0)';
            });
        });

        // Keyboard Accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                const focusedElement = document.activeElement;
                if (focusedElement.tagName === 'INPUT') {
                    e.preventDefault();
                    document.getElementById('loginForm').dispatchEvent(new Event('submit'));
                }
            }
        });
    </script>
</body>
</html>