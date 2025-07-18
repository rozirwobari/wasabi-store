@extends('store.layout')

@section('title', 'Keranjang Belanja')

@section('css')
    <style>
        /* Cart Page Specific Styles */
        .cart-item {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-item-img {
            border-radius: 8px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .cart-item-title {
            color: var(--green);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--red);
            font-weight: 600;
        }

        .cart-quantity-input {
            width: 60px !important;
            text-align: center !important;
            border: 1px solid #ddd !important;
            border-radius: 5px !important;
            padding: 5px !important;
        }

        .remove-item {
            color: #999;
            transition: all 0.3s;
        }

        .remove-item:hover {
            color: var(--red);
            transform: scale(1.1);
        }

        .cart-summary {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .summary-total {
            font-size: 1.25rem;
            font-weight: 700;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .btn-checkout {
            background-color: var(--green);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-checkout:hover {
            background-color: #4a5c35;
            color: var(--gold);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .continue-shopping {
            display: inline-block;
            margin-top: 15px;
            color: var(--green);
            font-weight: 500;
            transition: all 0.3s;
        }

        .continue-shopping:hover {
            color: var(--gold);
            transform: translateX(5px);
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }

        .empty-cart i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .cart-page-title {
            color: var(--green);
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        .cart-page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--gold);
        }

        .promo-code {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #eee;
        }

        /* Animation */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .loaded .fade-up {
            opacity: 1;
            transform: translateY(0);
        }

        .swal2-cancel {
            margin: 0.5vw;
        }

        .swal2-confirm {
            margin: 0.5vw;
        }

        .btn-add-address {
            border: 2px dashed #ddd;
            background: none;
            color: #666;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-add-address:hover {
            border-color: var(--green);
            color: var(--green);
            background-color: #f8f9fa;
        }

        .address-card {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .address-card:hover {
            border-color: var(--green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .address-card.selected {
            border-color: var(--green);
            background-color: #f8f9fa;
        }

        .address-card.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 15px;
            right: 15px;
            color: var(--green);
            font-size: 1.2rem;
        }

        .selected-address-display {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid var(--green);
        }

        .selected-address-display .address-name {
            font-size: 1rem;
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="cart-page-title" data-aos="fade-up">Keranjang Belanja</h2>
                <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-4">
            @if (count($carts) > 0)
                @php
                    $total = 0;
                @endphp
                <div class="col-lg-8 mb-4">
                    @foreach ($carts as $cart)
                        @php
                            $total += $cart->produk->harga * $cart->jumlah;
                        @endphp
                        <div class="cart-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
                            @php
                                $images = json_decode($cart->produk->images);
                            @endphp
                            <a href="{{ asset($images[0]) }}" data-lightbox="image-{{ $cart->produk->id }}"
                                data-title="{{ $cart->produk->label }}">
                                <img src="{{ asset($images[0]) }}" class="cart-item-img me-3" alt="Product Image">
                            </a>
                            @foreach ($images as $key => $image)
                                @if ($key > 0)
                                    <div style="display: none">
                                        <a href="{{ asset($image) }}" data-lightbox="image-{{ $cart->produk->id }}"
                                            data-title="{{ $cart->produk->label }}">
                                            <div
                                                style="width: 10vw; height: 10vw; overflow: hidden; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 10px">
                                                <img src="{{ asset($image) }}" alt="Wasabi Store Produk"
                                                    style="width: 10vw; height: 10vw; object-fit: cover;">
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                            <div class="flex-grow-1">
                                <h5 class="cart-item-title">{{ $cart->produk->label }}</h5>
                                <span class="cart-item-price">Rp
                                    {{ number_format($cart->produk->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="input-group me-3" style="width: 140px;">
                                    <button class="btn btn-outline-secondary qty-btn" type="button"
                                        id="kurangi-{{ $cart->id }}" onclick="kurangiJumlah({{ $cart->id }})"
                                        {{ $cart->jumlah <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="text" class="form-control text-center cart-quantity-input"
                                        id="quantityValue-{{ $cart->id }}" value="{{ $cart->jumlah }}" min="1"
                                        data-product-id="{{ $cart->id }}">
                                    <button class="btn btn-outline-secondary qty-btn" type="button"
                                        id="tambah-{{ $cart->id }}" onclick="tambahJumlah({{ $cart->id }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <a href="javascript:void(0);" onclick="DeleteCart({{ $cart->id }})"
                                    class="remove-item">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="cart-summary" data-aos="fade-left">
                        <!-- Selected Address Display -->
                        <div class="selected-address-display" id="selectedAddressDisplay" style="display: none;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="address-name" id="displayAddressName"></div>
                                    <div class="address-details" id="displayAddressDetails"></div>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2 w-100" id="tombolUbah">
                                <i class="fas fa-edit"></i> Ubah
                            </button>
                        </div>
                        <h4 class="mb-4">Ringkasan Pesanan</h4>

                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>Rp <span id="sub_total">{{ number_format($total, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="summary-item summary-total">
                            <span>Total</span>
                            <span>Rp <span id="total_harga">{{ number_format($total, 0, ',', '.') }}</span></span>
                        </div>
                        {{-- <a href="{{ url('checkout') }}" class="btn btn-checkout w-100">
                            Ke Pembayaran <i class="fas fa-arrow-right ms-2"></i>
                        </a> --}}
                        <button type="button" class="btn btn-checkout w-100" id="proceedCheckoutBtn">
                            Ke Pembayaran <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <a href="{{ url('/') }}" class="continue-shopping d-block text-center">
                            <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            @else
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="empty-cart">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <h3>Keranjang Belanja Kamu Kosong</h3>
                            <p class="text-muted">Sepertinya kamu belum menambahkan produk ke keranjang</p>
                            <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- Address Selection Modal -->
    <div class="modal fade" id="targetModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">
                        <i class="fa-solid fa-users-viewfinder"></i> Target Player
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Address List in Modal -->
                    <div id="modalAddressList">
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($dataPlayers as $dataPlayer)
                            @if ($dataPlayer->status == 1)
                                <div class="address-card" data-address-id="{{ $no++ }}">
                                    <div class="address-name">
                                        <b>{{ $dataPlayer->name }}</b>
                                    </div>
                                    <div class="address-details">
                                        Steam HEX : <b>{{ $dataPlayer->identifier }}</b>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Add New Address Button -->
                    <button class="btn-add-address mt-3" onclick="tambahAlamat()">
                        <i class="fas fa-plus me-2"></i>Tambah Player Baru
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmAddressBtn" disabled>
                        <i class="fas fa-check me-2"></i>Konfirmasi Player
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        let selectedAddressId = null;
        let selectedFixAddressId = null;
        const addressData = @json($dataPlayers);
        const proceedCheckoutBtn = document.getElementById('proceedCheckoutBtn');
        const targetModal = new bootstrap.Modal(document.getElementById('targetModal'));
        const addressCards = document.querySelectorAll('#modalAddressList .address-card');
        const confirmAddressBtn = document.getElementById('confirmAddressBtn');
        const tombolUbah = document.getElementById('tombolUbah');

        function GetJumlah(id) {
            var jumlah = document.getElementById('quantityValue-' + id).value;
            return parseInt(jumlah);
        }

        function tambahJumlah(id) {
            var currentQuantity = GetJumlah(id);
            var newQuantity = currentQuantity + 1;
            document.getElementById('quantityValue-' + id).value = newQuantity;
            document.getElementById('kurangi-' + id).disabled = false;
            UpdateCart(id, newQuantity);
        }

        function kurangiJumlah(id) {
            var currentQuantity = GetJumlah(id);
            var newQuantity = currentQuantity - 1;

            if (newQuantity < 1) {
                newQuantity = 1;
            }
            document.getElementById('quantityValue-' + id).value = newQuantity;

            if (newQuantity <= 1) {
                document.getElementById('kurangi-' + id).disabled = true;
            }
            UpdateCart(id, newQuantity);
        }

        function UpdateCart(id, jumlah) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('updatecarts') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            var data = JSON.stringify({
                cart_id: id,
                jumlah: jumlah,
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var totalHarga = 0;
                        var response = JSON.parse(xhr.responseText);
                        response.data.forEach(element => {
                            totalHarga = totalHarga + (element.harga * element.jumlah);
                        });
                        var formattedTotalHarga = totalHarga.toLocaleString('id-ID');
                        document.getElementById('sub_total').innerHTML = formattedTotalHarga;
                        document.getElementById('total_harga').innerHTML = formattedTotalHarga;
                    }
                }
            };

            xhr.send(data);
        }

        function updateQuantity(getjumlah, action) {
            var jumlah = document.getElementById('quantityValue').value;
            if (action == 'tambah') {
                jumlah = parseInt(jumlah) + getjumlah;
            } else if (action == 'kurang') {
                jumlah = parseInt(jumlah) - getjumlah;
            }
            return jumlah;
        }

        function DeleteCart(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Kamu Yakin?",
                text: "Apakah Kamu Yakin Ingin Menghapus Produk Ini Dari Keranjang?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya Hapus!",
                cancelButtonText: "Batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('deletecarts') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        cart_id: id,
                    });
                    xhr.send(data);
                    xhr.onreadystatechange = function() {
                        if (xhr.status === 200) {
                            Swal.fire({
                                title: "Hapus Produk",
                                text: "Berhasil Menghapus Produk Di Keranjang!",
                                icon: "success"
                            });
                            location.reload();
                        }
                    };
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('cart-quantity-input')) {
                    let jumlah = parseInt(e.target.value);
                    const productId = e.target.getAttribute('data-product-id');
                    if (isNaN(jumlah) || jumlah < 1) {
                        jumlah = 1;
                        e.target.value = 1;
                    }
                    const kurangiBtn = document.getElementById('kurangi-' + productId);
                    if (kurangiBtn) {
                        if (jumlah <= 1) {
                            kurangiBtn.disabled = true;
                        } else {
                            kurangiBtn.disabled = false;
                        }
                    }
                    UpdateCart(parseInt(productId), jumlah);
                }
            });
        });


        proceedCheckoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (selectedFixAddressId != null && (parseInt(selectedFixAddressId) == 0 || parseInt(selectedFixAddressId) > 0)) {
                const address = addressData[parseInt(selectedFixAddressId)];
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('checkout') }}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = `{{ csrf_token() }}`;
                form.appendChild(csrfInput);

                const addressInput = document.createElement('input');
                addressInput.type = 'hidden';
                addressInput.name = 'identifier';
                addressInput.value = address?.identifier;
                form.appendChild(addressInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                targetModal.show();
            }
        });

        addressCards.forEach(card => {
            card.addEventListener('click', function() {
                addressCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedAddressId = this.getAttribute('data-address-id');
                confirmAddressBtn.disabled = false;
            });
        });

        confirmAddressBtn.addEventListener('click', function() {
            if (selectedAddressId) {
                const address = addressData[parseInt(selectedAddressId)];
                targetModal.hide();
                document.getElementById('displayAddressName').innerHTML =
                    `Nama : <b>${address?.name ?? "Tidak Diketahui"}</b>`;
                document.getElementById('displayAddressDetails').innerHTML =
                    `Identifier : <b>${address?.identifier ?? "Identifier Tidak Diketahui"}</b>`;
                selectedAddressDisplay.style.display = 'block';
                proceedCheckoutBtn.innerHTML = 'Lanjutkan ke Pembayaran <i class="fas fa-arrow-right ms-2"></i>';
                selectedFixAddressId = parseInt(selectedAddressId);
                setTimeout(() => {
                    addressCards.forEach(c => c.classList.remove('selected'));
                    confirmAddressBtn.disabled = true;
                }, 300);
            }
        });

        tombolUbah.addEventListener('click', function(e) {
            e.preventDefault();
            targetModal.show();
            if (selectedAddressId) {
                const currentCard = document.querySelector(
                    `#modalAddressList .address-card[data-address-id="${selectedAddressId}"]`);
                if (currentCard) {
                    currentCard.classList.add('selected');
                    confirmAddressBtn.disabled = false;
                }
            }
        });

        function tambahAlamat() {
            window.location.href = `{{ url('dataplayers') }}`;
        }
    </script>
@endsection
