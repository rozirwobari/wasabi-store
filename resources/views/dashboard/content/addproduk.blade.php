@extends('dashboard.layout')

@section('title', 'Tambah Produk')

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

        .form-header {
            background: linear-gradient(135deg, var(--primary-green), #4a5a37);
            color: white;
            padding: 25px 30px;
            margin: -30px -30px 30px -30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-header h4 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .form-header i {
            margin-right: 10px;
            font-size: 1.2em;
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

            .image-upload-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
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

        .remove-image-btn:hover {
            transform: scale(1.1);
            background: #c23e49;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <!-- Alert Section -->
        <div id="alertContainer"></div>

        <!-- Form Container -->
        <!-- Update form tag -->
        <form id="productForm" method="POST" action="{{ url('dashboards/saveproduk') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Step 1: Basic Information -->
            <div class="form-step active" data-step="1">
                <div class="form-card fade-in">
                    <div class="form-header">
                        <h4><i class="fas fa-info-circle"></i>Informasi Dasar Produk</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="produk_name">
                                    Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="productName" name="produk_name"
                                    placeholder="Masukkan nama produk" required value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="productCategory">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="productCategory" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ old('category_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="productPrice">
                                    Harga Jual <span class="text-danger">*</span>
                                </label>
                                <div class="price-input-group">
                                    <input type="number" class="form-control" id="productPrice" name="harga"
                                        placeholder="0" min="0" step="0.01" required value="{{ old('price') }}">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="productDescription">
                            Deskripsi Produk <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="productDescription" name="deskripsi" rows="4"
                            placeholder="Jelaskan produk secara detail..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 3: Images -->
            <div class="form-step" data-step="3">
                <div class="form-card fade-in">
                    <div class="form-header">
                        <h4><i class="fas fa-images"></i>Gambar Produk</h4>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Upload Gambar Produk <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload maksimal 5 gambar. Gambar pertama akan menjadi gambar utama produk.
                            Format: JPG, PNG, WebP. Maksimal 5MB per file.
                        </p>

                        <!-- Hidden inputs for images -->
                        <input type="file" name="images[]" id="hiddenImageInput1" style="display: none;"
                            accept="image/*">
                        <input type="file" name="images[]" id="hiddenImageInput2" style="display: none;"
                            accept="image/*">
                        <input type="file" name="images[]" id="hiddenImageInput3" style="display: none;"
                            accept="image/*">
                        <input type="file" name="images[]" id="hiddenImageInput4" style="display: none;"
                            accept="image/*">
                        <input type="file" name="images[]" id="hiddenImageInput5" style="display: none;"
                            accept="image/*">

                        <!-- Hidden input for main image index -->
                        <input type="hidden" name="main_image_index" id="mainImageIndexInput" value="0">

                        <div class="image-upload-grid" id="imageUploadGrid">
                            <!-- Image upload boxes will be generated here -->
                        </div>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback d-block" id="imageUploadError" style="display: none !important;">
                            Minimal 1 gambar produk harus diupload
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-step">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <footer class="mt-5 py-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 AdminPanel. Semua hak dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">Versi 2.1.0 | <a href="#"
                            class="text-decoration-none">Dokumentasi</a></p>
                </div>
            </div>
        </footer>
    </div>
@endsection


@section('scripts')
    <script>
        // Multi-step form functionality
        let currentStep = 1;
        const totalSteps = 4;
        const formSteps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step');
        const stepText = document.querySelector('.step-text');

        const stepTexts = {
            1: 'Langkah 1: Informasi Dasar Produk',
            2: 'Langkah 2: Harga & Stok',
            3: 'Langkah 3: Gambar Produk',
            4: 'Langkah 4: Informasi Tambahan'
        };

        function showStep(step) {
            // Hide all steps
            formSteps.forEach(formStep => {
                formStep.classList.remove('active');
            });

            // Show current step
            const currentFormStep = document.querySelector(`[data-step="${step}"]`);
            if (currentFormStep) {
                currentFormStep.classList.add('active');
            }

            // Update step indicators
            stepIndicators.forEach((indicator, index) => {
                const stepNumber = index + 1;
                indicator.classList.remove('active', 'completed');

                if (stepNumber < step) {
                    indicator.classList.add('completed');
                    indicator.innerHTML = '<i class="fas fa-check"></i>';
                } else if (stepNumber === step) {
                    indicator.classList.add('active');
                    indicator.innerHTML = stepNumber;
                } else {
                    indicator.innerHTML = stepNumber;
                }
            });

            // Update step text
            // stepText.textContent = stepTexts[step];

            // Scroll to top
            document.querySelector('.form-card').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Image upload system
        const MAX_IMAGES = 5;
        let uploadedImages = [];
        let mainImageIndex = 0;

        // Initialize image upload grid
        function initImageUpload() {
            console.log("Log masuk Sini");
            const grid = document.getElementById('imageUploadGrid');
            grid.innerHTML = '';

            for (let i = 0; i < MAX_IMAGES; i++) {
                const uploadBox = createUploadBox(i);
                grid.appendChild(uploadBox);
            }
        }

        function createUploadBox(index) {
            const box = document.createElement('div');
            box.className = 'image-upload-box';
            box.dataset.index = index;

            box.innerHTML = `
                <div class="image-number">${index + 1}</div>
                <div class="upload-content">
                    <i class="fas fa-plus upload-icon"></i>
                    <div class="upload-text">
                        ${index === 0 ? 'Gambar Utama' : 'Tambah Gambar'}
                    </div>
                </div>
                <input type="file" accept="image/*" style="display: none;">
            `;

            const fileInput = box.querySelector('input[type="file"]');

            // Click handler - hanya untuk upload box kosong
            function handleBoxClick(e) {
                e.stopPropagation();
                // Hanya buka file explorer jika tidak ada gambar DAN box tidak memiliki class 'has-image'
                if (!uploadedImages[index] && !box.classList.contains('has-image')) {
                    fileInput.click();
                }
            }

            box.addEventListener('click', handleBoxClick);

            // File input change handler
            function handleFileChange(e) {
                const file = e.target.files[0];
                if (file) {
                    handleImageUpload(file, index);
                }
            }

            fileInput.addEventListener('change', handleFileChange);

            // Drag and drop handlers - hanya untuk box kosong
            function handleDragOver(e) {
                e.preventDefault();
                if (!uploadedImages[index] && !box.classList.contains('has-image')) {
                    box.classList.add('dragover');
                }
            }

            function handleDragLeave() {
                box.classList.remove('dragover');
            }

            function handleDrop(e) {
                e.preventDefault();
                box.classList.remove('dragover');
                if (!uploadedImages[index] && !box.classList.contains('has-image')) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleImageUpload(files[0], index);
                    }
                }
            }

            box.addEventListener('dragover', handleDragOver);
            box.addEventListener('dragleave', handleDragLeave);
            box.addEventListener('drop', handleDrop);

            return box;
        }

        function handleImageUpload(file, index) {
            // Validate file
            if (!file.type.startsWith('image/')) {
                showAlert('File harus berupa gambar', 'warning');
                return;
            }

            if (file.size > 5 * 1024 * 1024) { // 5MB
                showAlert('Ukuran file maksimal 5MB', 'warning');
                return;
            }

            // Set file to hidden input
            const hiddenInput = document.getElementById(`hiddenImageInput${index + 1}`);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            hiddenInput.files = dataTransfer.files;

            // Read file for preview
            const reader = new FileReader();
            reader.onload = (e) => {
                uploadedImages[index] = {
                    file: file,
                    url: e.target.result,
                    name: file.name
                };

                displayImage(index);

                // Set as main image if it's the first one
                if (index === 0) {
                    mainImageIndex = 0;
                    updateMainImageIndicators();
                }
            };
            reader.readAsDataURL(file);
        }

        function displayImage(index) {
            const box = document.querySelector(`[data-index="${index}"]`);
            const image = uploadedImages[index];

            if (!image) return;

            box.classList.add('has-image');
            box.innerHTML = `
                <div class="image-number">${index + 1}</div>
                <div class="image-preview-container">
                    <img src="${image.url}" alt="${image.name}">
                    <button type="button" class="remove-image-btn" onclick="removeImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                    ${index === mainImageIndex ? '<div class="main-image-indicator">Utama</div>' : ''}
                    ${index !== 0 ? `<button type="button" class="set-main-btn" onclick="setMainImage(${index})">Set Utama</button>` : ''}
                </div>
            `;
        }

        function removeImage(index) {
            uploadedImages[index] = null;
            
            // Clear hidden input
            const hiddenInput = document.getElementById(`hiddenImageInput${index + 1}`);
            hiddenInput.value = '';

            // Reset main image if removed
            if (index === mainImageIndex) {
                // Find the first available image
                mainImageIndex = uploadedImages.findIndex(img => img !== null);
                if (mainImageIndex === -1) mainImageIndex = 0;
            }

            // Update main image index input
            document.getElementById('mainImageIndexInput').value = mainImageIndex;

            // Recreate the upload box completely
            const box = document.querySelector(`[data-index="${index}"]`);
            const newBox = createUploadBox(index);
            box.parentNode.replaceChild(newBox, box);

            updateMainImageIndicators();
        }

        function setMainImage(index) {
            if (uploadedImages[index]) {
                mainImageIndex = index;
                document.getElementById('mainImageIndexInput').value = mainImageIndex;
                updateMainImageIndicators();
            }
        }

        function updateMainImageIndicators() {
            // Update all image displays to show/hide main image indicator
            uploadedImages.forEach((image, index) => {
                if (image) {
                    displayImage(index);
                }
            });
        }

        // Next step buttons
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });
        });

        // Previous step buttons
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Form validation
        function validateCurrentStep() {
            const currentFormStep = document.querySelector(`[data-step="${currentStep}"]`);
            const requiredFields = currentFormStep.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            // Special validation for step 3 (images)
            if (currentStep === 3) {
                const hasImages = uploadedImages.some(img => img !== null);
                const errorDiv = document.getElementById('imageUploadError');

                if (!hasImages) {
                    errorDiv.style.display = 'block';
                    showAlert('Minimal 1 gambar produk harus diupload', 'danger');
                    isValid = false;
                } else {
                    errorDiv.style.display = 'none';
                }
            }

            return isValid;
        }

        function removeTag(value, element) {
            const index = tags.indexOf(value);
            if (index > -1) {
                tags.splice(index, 1);
            }
            element.parentElement.remove();
        }

        // Auto-generate SKU
        document.getElementById('productName').addEventListener('input', (e) => {
            const name = e.target.value;
            const sku = generateSKU(name);
            document.getElementById('productSKU').value = sku;
        });

        function generateSKU(name) {
            const prefix = 'PRD';
            const namePart = name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 3);
            const randomPart = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return `${prefix}-${namePart}${randomPart}`;
        }

        // Alert system
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();

            const alertHTML = `
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${getAlertIcon(type)} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            alertContainer.insertAdjacentHTML('beforeend', alertHTML);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    const alert = new bootstrap.Alert(alertElement);
                    alert.close();
                }
            }, 5000);
        }

        function getAlertIcon(type) {
            const icons = {
                success: 'check-circle',
                danger: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };
            return icons[type] || 'info-circle';
        }

        // Form submission
        document.getElementById('productForm').addEventListener('submit', (e) => {
            // Validate that at least one image is uploaded
            const hasImages = uploadedImages.some(img => img !== null);
            
            if (!hasImages) {
                e.preventDefault();
                showAlert('Minimal 1 gambar produk harus diupload', 'danger');
                return false;
            }

            // Update main image index
            document.getElementById('mainImageIndexInput').value = mainImageIndex;
            
            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });

        // Save draft
        // document.getElementById('saveDraft').addEventListener('click', () => {
        //     const saveBtn = document.getElementById('saveDraft');
        //     const originalText = saveBtn.innerHTML;
        //     saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan Draft...';
        //     saveBtn.disabled = true;

        //     setTimeout(() => {
        //         showAlert('Draft berhasil disimpan!', 'info');
        //         saveBtn.innerHTML = originalText;
        //         saveBtn.disabled = false;
        //     }, 1500);
        // });

        // Real-time validation
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', () => {
                if (field.value.trim()) {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                } else {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                }
            });
        });

        // Initialize form
        showStep(1);
        initImageUpload();

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Fade in animation observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('fade-in');
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Observe form cards
        document.querySelectorAll('.form-card').forEach(el => {
            observer.observe(el);
        });
    </script>
@endsection
