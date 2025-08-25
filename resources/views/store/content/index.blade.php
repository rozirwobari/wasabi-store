@extends('store.layout')


@section('css')
    <style>
        /* CSS untuk Harga Diskon */

.price-container {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* Harga Diskon (harga utama) */
.price-discount {
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
}

/* Badge Diskon */
.discount-badge {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    color: white;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(255, 107, 53, 0.3);
    white-space: nowrap;
}

/* Variasi dengan background berbeda */
.discount-badge.sale {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.discount-badge.hot {
    background: linear-gradient(135deg, #ff4757, #ff3742);
    animation: pulse 2s infinite;
}

/* Animasi pulse untuk badge hot */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive untuk mobile */
@media (max-width: 576px) {
    .price-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .price-discount {
        font-size: 16px;
    }

    .price-original {
        font-size: 12px;
    }

    .discount-badge {
        font-size: 10px;
        padding: 3px 6px;
    }
}

/* Efek hover untuk container harga */
.price-container:hover .price-discount {
    color: #c0392b;
    transition: color 0.3s ease;
}

/* Style tambahan untuk persentase diskon besar */
.discount-badge.big-discount {
    background: linear-gradient(135deg, #8e44ad, #9b59b6);
    font-size: 12px;
    padding: 5px 10px;
    animation: bounce 3s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
    60% { transform: translateY(-3px); }
}
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="banner" data-aos="fade-up">
            <div class="banner-content">
                <h2 class="display-4">Koleksi Item Terbaru 2025</h2>
                <p class="lead">Jelajahi koleksi item unik dan spesial untuk meningkatkan gameplay-mu.</p>
                <a href="#produks" class="btn btn-lg btn-secondary mt-3">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar" data-aos="fade-right">
                    <h4 class="filter-header">Kategori</h4>
                    <hr>
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-2" id="realTimeSearch" placeholder="Cari Produk..." aria-label="Search products">
                        </div>
                        <small class="text-muted">Hasil diperbarui saat Kamu mengetik</small>
                    </div>

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
                                    <img src="{{ $images[0] ? asset($images[0]) : 'https://placehold.co/600x400' }}" class="card-img-top" alt="Product Image">
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
                                    {{-- <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="price subtle-fire">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                    </div> --}}

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="price-container">
                                            <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                            @isset($produk->is_promo)
                                                <span class="discount-badge">Promo</span>
                                            @endisset
                                        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.produk-card');
            const categoryLinks = document.querySelectorAll('.category-link');
            const searchInput = document.getElementById('realTimeSearch');
            categoryLinks.forEach((link, index) => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    categoryLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    const selectedCategory = this.getAttribute('data-category');
                    filterProducts(selectedCategory, searchInput.value);
                });
            });

            searchInput.addEventListener('input', function() {
                const activeCategory = document.querySelector('.category-link.active');
                const categoryValue = activeCategory ? activeCategory.getAttribute('data-category') : 'all';

                filterProducts(categoryValue, this.value);
            });

            function filterProducts(category, searchTerm = '') {
                let visibleCount = 0;
                productCards.forEach((card, index) => {
                    const cardCategory = card.getAttribute('data-category');
                    const productName = card.getAttribute('data-product-name');
                    let shouldShow = true;
                    if (category !== 'all') {
                        if (String(cardCategory) !== String(category)) {
                            shouldShow = false;
                        }
                    }
                    if (searchTerm && searchTerm.trim() !== '') {
                        if (!productName.includes(searchTerm.toLowerCase().trim())) {
                            shouldShow = false;
                        }
                    }
                    if (shouldShow) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
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
