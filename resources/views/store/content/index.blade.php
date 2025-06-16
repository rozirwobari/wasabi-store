@extends('store.layout')

@section('content')
    <div class="container py-5">
        <!-- Banner -->
        <div class="banner" data-aos="fade-up">
            <div class="banner-content">
                <h2 class="display-4">Koleksi Item Terbaru 2025</h2>
                <p class="lead">Jelajahi koleksi item unik dan spesial untuk meningkatkan gameplay-mu.</p>
                <a href="#produks" class="btn btn-lg btn-secondary mt-3">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Categories -->
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar" data-aos="fade-right">
                    <h4 class="filter-header">Kategori</h4>
                    <hr>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-2" id="realTimeSearch" placeholder="Cari Produk..." aria-label="Search products">
                        </div>
                        <small class="text-muted">Hasil diperbarui saat Kamu mengetik</small>
                    </div>

                    <!-- Categories -->
                    <div class="mb-4">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="javascript:void(0)" class="text-decoration-none category-link active" data-category="all">All Products</a>
                                <span class="badge bg-secondary rounded-pill">{{ count($produks) }}</span>
                            </li>
                            @foreach ($kategoris as $kategori)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="javascript:void(0)" class="text-decoration-none category-link" data-category="{{ $kategori->id }}">{{ $kategori->label }}</a>
                                    <span class="badge bg-secondary rounded-pill">{{ count($kategori->produk) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9" id="produks">
                <!-- Products Row -->
                <div class="row" id="products-container">
                    @foreach ($produks as $produk)
                    @php
                        $images = json_decode($produk->images);
                        $kategoriId = $produk->kategoris ? $produk->kategoris->id : '';
                    @endphp
                        <div class="col-md-4 mb-4 produk-card" 
                             data-category="{{ $kategoriId }}" 
                             data-product-name="{{ strtolower($produk->label) }}"
                             data-aos="fade-up" 
                             data-aos-delay="100">
                            <div class="card h-100">
                                @if ($images && count($images) > 0 && $images[0])
                                    <img src="{{ asset($images[0]) }}" class="card-img-top" alt="Product Image">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        <p class="text-muted">Tidak Ada Gambar</p>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <span class="category-badge">{{ $produk->kategoris && $produk->kategoris->label ? $produk->kategoris->label : 'Tidak Diketahui' }}</span>
                                    <a href="{{ url('produk-details/'.$produk->id) }}">
                                        <h5 class="card-title mt-2">{{ $produk->label }}</h5>
                                    </a>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="card-text text-muted small">Produk <b>{{ $produk->label }}</b> Hanya Bisa
                                        Digunakan Di Server Wasabi Garden (FiveM).</p>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-primary w-100" onclick="addToCart({{ $produk->id }})">
                                            <i class="fas fa-shopping-cart me-1"></i> Tambah Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get all product cards and category links
            const productCards = document.querySelectorAll('.produk-card');
            const categoryLinks = document.querySelectorAll('.category-link');
            const searchInput = document.getElementById('realTimeSearch');


            // Category filter functionality
            categoryLinks.forEach((link, index) => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all links
                    categoryLinks.forEach(l => l.classList.remove('active'));

                    // Add active class to clicked link
                    this.classList.add('active');

                    const selectedCategory = this.getAttribute('data-category');
                    
                    filterProducts(selectedCategory, searchInput.value);
                });
            });

            // Real-time search functionality
            searchInput.addEventListener('input', function() {
                const activeCategory = document.querySelector('.category-link.active');
                const categoryValue = activeCategory ? activeCategory.getAttribute('data-category') : 'all';
                
                filterProducts(categoryValue, this.value);
            });

            // Filter products function
            function filterProducts(category, searchTerm = '') {
                let visibleCount = 0;
                
                productCards.forEach((card, index) => {
                    const cardCategory = card.getAttribute('data-category');
                    const productName = card.getAttribute('data-product-name');
                    
                    let shouldShow = true;

                    // Check category filter
                    if (category !== 'all') {
                        // Convert both to string for comparison
                        if (String(cardCategory) !== String(category)) {
                            shouldShow = false;
                        }
                    }
                    
                    // Check search filter
                    if (searchTerm && searchTerm.trim() !== '') {
                        if (!productName.includes(searchTerm.toLowerCase().trim())) {
                            shouldShow = false;
                        }
                    }
                    
                    // Show or hide the product card
                    if (shouldShow) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Initial filter
            filterProducts('all', '');
        });

        function addToCart(produkid) {
            if (`{!! json_encode(Auth::check()) !!}` === 'false') {
                window.location.href = "{{ url('login') }}";
                return;
            }
            
            const cartCount = document.querySelector('.cart-count');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('addtocarts') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                produk_id: produkid,
                jumlah: 1,
            });
            xhr.send(data);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        if (xhr.responseText.trim() === '') {
                            return;
                        }

                        var response = JSON.parse(xhr.responseText);
                        if (cartCount) {
                            cartCount.textContent = response.data.length;
                            cartCount.style.transform = 'scale(1.3)';
                            setTimeout(() => {
                                cartCount.style.transform = 'scale(1)';
                            }, 300);
                        }
                        
                        Swal.fire({
                            title: "Tambah Produk",
                            text: "Berhasil Menambahkan produk ke keranjang!",
                            icon: "success"
                        });
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                    }
                }
            };
        }
    </script>
@endsection