@extends('dashboard.layout')

@section('title', 'Edit Produk')

@section('css')
    <style>
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(62, 76, 44, 0.25);
        }

        .form-control.is-valid {
            border-color: var(--primary-green);
        }

        .form-control.is-invalid {
            border-color: var(--primary-red);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), #4a5a37);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-red), #c23e49);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(171, 52, 62, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-green);
            border-color: var(--primary-green);
            transform: translateY(-2px);
        }

        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .image-upload-area:hover {
            border-color: var(--primary-green);
            background-color: var(--light-green);
        }

        .image-upload-area.dragover {
            border-color: var(--primary-gold);
            background-color: var(--light-gold);
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            margin: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .image-preview .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--primary-red);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-preview .remove-image:hover {
            transform: scale(1.1);
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

        .alert-success {
            background: var(--light-green);
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            border-radius: 10px;
        }

        .alert-danger {
            background: var(--light-red);
            border: 1px solid var(--primary-red);
            color: var(--primary-red);
            border-radius: 10px;
        }

        .price-input-group {
            position: relative;
        }

        .price-input-group::before {
            content: 'Rp';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-weight: 600;
            z-index: 3;
        }

        .price-input-group .form-control {
            padding-left: 40px;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

            .form-card {
                margin-bottom: 15px;
                padding: 20px;
            }

            .form-header {
                margin: -20px -20px 20px -20px;
                padding: 20px;
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

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .step-indicator .step {
            background: white;
            border: 3px solid #dee2e6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6c757d;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .step-indicator .step.active {
            border-color: var(--primary-green);
            background: var(--primary-green);
            color: white;
        }

        .step-indicator .step.completed {
            border-color: var(--primary-gold);
            background: var(--primary-gold);
            color: white;
        }

        .category-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .category-option {
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .category-option:hover {
            border-color: var(--primary-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-option.selected {
            border-color: var(--primary-green);
            background: var(--light-green);
        }

        .category-option i {
            font-size: 2rem;
            color: var(--primary-green);
            margin-bottom: 10px;
            display: block;
        }

        .tag-input {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            min-height: 50px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px;
            cursor: text;
            transition: all 0.3s ease;
        }

        .tag-input:focus-within {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(62, 76, 44, 0.25);
        }

        .tag {
            background: var(--primary-green);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tag .remove-tag {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tag .remove-tag:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .tag-input input {
            border: none;
            outline: none;
            flex: 1;
            min-width: 120px;
            padding: 5px;
        }

        .existing-images-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .image-item {
            position: relative;
            border: 2px solid #dee2e6;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }

        .image-item:hover {
            border-color: var(--primary-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-item .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-item:hover .image-overlay {
            opacity: 1;
        }

        .image-overlay .btn {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .image-index {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--primary-green);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .empty-slot {
            border: 2px dashed #dee2e6;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .empty-slot:hover {
            border-color: var(--primary-green);
            background: var(--light-green);
        }

        .empty-slot i {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .hidden-file-input {
            display: none;
        }

        .image-upload-box {
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fafafa;
        }

        .image-upload-box:hover {
            border-color: var(--primary-green);
            background-color: var(--light-green);
            transform: translateY(-2px);
        }

        .image-upload-box.dragover {
            border-color: var(--primary-gold);
            background-color: var(--light-gold);
        }

        .image-upload-box.has-image {
            border-color: var(--primary-green);
            border-style: solid;
            background: white;
            padding: 10px;
        }

        .image-upload-box .upload-icon {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .image-upload-box .upload-text {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .image-upload-box .image-number {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--primary-green);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 15;
        }

        .image-upload-box input[type="file"] {
            display: none;
        }

        .image-preview-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview-container img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .image-upload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .remove-image-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--primary-red);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            transform: scale(1.0);
        }

        .remove-image-btn:hover {
            transform: scale(1.1);
            background: #c23e49;
        }

        .set-main-btn {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .set-main-btn:hover {
            background: var(--primary-gold);
        }

        .main-image-indicator {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: var(--primary-gold);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .image-upload-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-9 col-md-8">
        <div data-aos="fade-up">
            <div class="container-fluid px-4">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Perhatian!</strong> Terdapat kesalahan dalam pengisian form:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('admin/UpdateSetting') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-step active" data-step="1">
                        <div class="form-card fade-in">
                            <div class="form-header">
                                <h4>Informasi Dasar User</h4>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="productName">Nama <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" value="{{ $user->name }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ $user->email }}" readonly>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
