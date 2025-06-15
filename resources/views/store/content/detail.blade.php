@extends('store.layout')

@section('content')
    <div class="product-detail-container">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" data-aos="fade-down">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $produk->label }}</li>
                </ol>
            </nav>
            @php
                $images = json_decode($produk->images)
            @endphp
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-lg-6">
                    <div class="product-gallery" data-aos="fade-right">
                        @if (count($images) > 0 )
                            <div class="main-image-container">
                                <img src="{{ asset($images[0]) }}" alt="{{ $produk->label }}" class="main-image" id="mainImage">
                            </div>
                            <div class="thumbnail-container">
                                @for ($no = 0; $no < count($images); $no++)
                                    <div class="thumbnail active" onclick="changeImage({{ $no }})">
                                        <img src="{{ asset($images[$no]) }}" alt="View 1">
                                    </div>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info" data-aos="fade-left">
                        <h1 class="product-title">{{ $produk->label }}</h1>

                        <div class="product-price">
                            <span class="current-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        </div>

                        <div class="quantity-selector">
                            <span class="quantity-label">Quantity:</span>
                            <div class="quantity-input">
                                <button class="quantity-btn" onclick="decreaseQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="quantity-value" id="quantityValue">1</span>
                                <button class="quantity-btn" onclick="increaseQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            {{-- <span class="text-muted">Stock: 12 items</span> --}}
                        </div>

                        <div class="action-buttons">
                            <button class="btn-add-to-cart" onclick="addToCart({{ $produk->id }})">
                                <i class="fas fa-shopping-cart me-2"></i>Tambah Keranjang
                            </button>
                        </div>

                        <div class="product-meta">
                            <p><strong>Kategori:</strong> {{ $produk->kategoris->label }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs" data-aos="fade-up">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <div class="nav-link active" style="color: #3e4c2c;">
                            Deskripsi
                        </div>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        {!! $produk->deskripsi !!}
                        <br>
                        <br>
                        <small>Noted: <i>Produk Ini Hanya Berlaku Untuk Item Di Dalam Server Wasabi Garden <b><a href="https://fivem.net/" style="color: black">(FiveM)</a></b></i></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function GetJumlah() {
            var jumlah = document.getElementById('quantityValue').textContent;
            return parseInt(jumlah);
        }

        function increaseQuantity() {
            var quantity = GetJumlah();
            if (quantity < 12) {
                document.getElementById('quantityValue').textContent = updateQuantity(1, 'tambah');
            }
        }

        function decreaseQuantity() {
            var quantity = GetJumlah();
            if (quantity > 1) {
                document.getElementById('quantityValue').textContent = updateQuantity(1, 'kurang');
            }
        }

        // Add to cart
        function addToCart(produkid) {
            if (`{!! json_encode(Auth::check()) !!}` === 'false') {
                window.location.href = "{{ url('login') }}";
                return;
            }
            const cartCount = document.querySelector('.cart-count');
            let currentCount = parseInt(cartCount.textContent);


            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('addtocarts') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var quantity = GetJumlah();
            var data = JSON.stringify({
                produk_id: produkid,
                jumlah: (quantity || 1),
            });
            xhr.send(data);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        if (xhr.responseText.trim() === '') {
                            console.error('Response kosong dari server');
                            return;
                        }

                        var response = JSON.parse(xhr.responseText);
                        cartCount.textContent = response.data.length;
                        cartCount.style.transform = 'scale(1.3)';
                        setTimeout(() => {
                            cartCount.style.transform = 'scale(1)';
                        }, 300);
                        Swal.fire({
                            title: "Tambah Produk",
                            text: "Berhasil Menambahkan produk ke keranjang!",
                            icon: "success"
                        });
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        console.error('Response text:', xhr.responseText);
                    }
                }
            };
        }

        function updateQuantity(getjumlah, action) {
            var jumlah = document.getElementById('quantityValue').textContent;
            if (action == 'tambah') {
                jumlah = parseInt(jumlah) + getjumlah;
            } else if (action == 'kurang') {
                jumlah = parseInt(jumlah) - getjumlah;
            }
            return jumlah;
        }


        // Product Images
        const productImages = @json($images);

        // Change main image
        function changeImage(index) {
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail');

            mainImage.src = `{{ asset('${productImages[index]}') }}`;

            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }
    </script>
@endsection
