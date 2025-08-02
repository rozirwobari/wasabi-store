@extends('store.layout')

@section('title', 'My Orders - ' . $orders->no_invoice)

@section('css')
    <style>
        .btn-primary-pay {
            background: var(--green);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 166, 92, 0.3);
            color: var(--gold);
            background: #3e4c2cc7;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="container">
            <div class="row">
                @include('store.content.dashboard.sidebar')

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    <div class="main-content-order" data-aos="fade-up">
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-custom">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('orders') }}">My Orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $orders->no_invoice }}</li>
                            </ol>
                        </nav>

                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-2">Order #{{ $orders->no_invoice }}</h2>
                                    <p class="mb-0 text-muted">
                                        {{ \App\Helpers\WabiHelper::formatDate($orders->created_at) }}</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    @if ($orders->status >= 0 && $orders->status <= 1)
                                        <span class="order-status-badge status-pending">Pending</span>
                                    @elseif ($orders->status > 1 && $orders->status < 4)
                                        <span class="order-status-badge status-processing">Processing</span>
                                    @elseif ($orders->status == 4)
                                        <div class="order-status-badge status-shipped">Dikirim</div>
                                    @elseif ($orders->status == 5)
                                        <div class="order-status-badge status-completed">Diambil</div>
                                    @elseif ($orders->status == 404)
                                        <div class="order-status-badge status-canceled">Canceled</div>
                                    @else
                                        <span class="order-status-badge status-unknown">Unknown</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Order Items -->
                            <div class="col-lg-8">
                                <h4 class="section-title">Order Items</h4>
                                @php
                                    $items = json_decode($orders->items, false);
                                    $tgl_transaksi = json_decode($orders->tgl_transaksi, true);
                                    $total = array_sum(array_column($items, 'total'));
                                @endphp
                                @foreach ($items as $item)
                                    <div class="product-item">
                                        <div class="product-image">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div class="product-details">
                                            <h6 class="product-name"><a href="{{ url('produk-details/' . $item->id) }}"
                                                    target="_blank">{{ $item->name }}</a></h6>
                                            <p class="product-quantity">Jumlah:
                                                {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                            <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="product-price">Rp
                                                {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Order Timeline -->
                                <h4 class="section-title mt-4">Order Tracking</h4>
                                <div class="order-timeline">
                                    <div
                                        class="timeline-item {{ $orders->status >= 0 ? 'active' : ($orders->status > 0 ? 'completed' : '') }}">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pesanan Diterima</div>
                                            <div class="timeline-date">
                                                {{ \App\Helpers\WabiHelper::formatDate($orders->created_at) }}</div>
                                        </div>
                                    </div>

                                    @if ($orders->status == 404)
                                        <div class="timeline-item {{ $orders->status == 1 ? 'active' : 'error' }}">
                                            <div class="timeline-content">
                                                <div class="timeline-title">Pembayaran Gagal</div>
                                                <div class="timeline-date">
                                                    {{ ($value = $tgl_transaksi['404'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}
                                                </div>
                                                <div class="timeline-date">
                                                    {{ $orders->data_midtrans }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="timeline-item {{ $orders->status == 1 ? 'active' : ($orders->status == 1 ? 'completed' : '') }}">
                                            <div class="timeline-content">
                                                <div class="timeline-title">Pembayaran Berhasil</div>
                                                <div class="timeline-date">
                                                    {{ ($value = $tgl_transaksi['2'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    <div
                                        class="timeline-item {{ $orders->status == 2 ? 'active' : ($orders->status == 2 ? 'completed' : '') }}">
                                        <div class="timeline-content">
                                            <div class="timeline-title">Pesanan Diproses</div>
                                            <div class="timeline-date">
                                                {{ ($value = $tgl_transaksi['3'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</div>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ $orders->status == 3 ? 'active' : ($orders->status == 3 ? 'completed' : '') }}">
                                        <div class="timeline-content">
                                            @php
                                                $reason_pengiriman = json_decode($orders->reason_game, false);
                                                $reason_pengiriman_txt = "";
                                                if ($reason_pengiriman && isset($reason_pengiriman->pengiriman)) {
                                                    $reason_pengiriman_txt = $reason_pengiriman->pengiriman;
                                                }
                                            @endphp
                                            <div class="timeline-title">Pesanan Sampai</div>
                                            <div class="timeline-date">Pesanan Kamu Sudah Bisa Di Ambil Di Dalam Game <br>{{ ($value = $tgl_transaksi['4'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</div>
                                            <div class="timeline-date">System : {{ $reason_pengiriman_txt }}</div>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ $orders->status == 4 ? 'active' : ($orders->status == 4 ? 'completed' : '') }}">
                                        <div class="timeline-content">
                                            @php
                                                $reason_claim = json_decode($orders->reason_game, false);
                                                $reason_claim_array = [];
                                                if ($reason_claim && isset($reason_claim->claim_item)) {
                                                    $reason_claim_array = $reason_claim->claim_item;
                                                };
                                                $no = 1;
                                            @endphp
                                            <div class="timeline-title">Diambil</div>
                                            <div class="timeline-date">Pesanan Kamu Sudah Di Ambil Di Dalam Game <br>{{ ($value = $tgl_transaksi['5'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</div>
                                            <br>
                                            @foreach ($reason_claim_array as $data_claim)
                                                <div class="timeline-date m-1"><b>{{ $no++ }}</b>. {{ $data_claim }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary & Details -->
                            <div class="col-lg-4">
                                <!-- Order Summary -->
                                <h4 class="section-title">Order Summary</h4>
                                <div class="order-summary">
                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span>Rp <b>{{ number_format($total, 0, ',', '.') }}</b></span>
                                    </div>
                                </div>

                                <div class="address-card mt-4">
                                    <div class="address-title">
                                        <i class="fa-solid fa-user"></i>
                                        Player Data
                                    </div>
                                    <p class="mb-1">Nama : <strong>{{ $orders->playerData->name }}</strong></p>
                                    <p class="mb-1">Identifier : {{ $orders->identifier }}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    @if ($orders->status <= 1)
                                        <button class="btn btn-primary-pay w-100 mb-2" id="pay-button">
                                            <i class="fa-solid fa-file-invoice me-2"></i>Bayar Sekarang
                                        </button>
                                    @endif
                                    <button class="btn btn-primary-custom w-100 mb-2">
                                        <i class="fas fa-download me-2"></i>Download Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
        function UpdateDatas(result) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('api/midtrans/notification') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                data: result,
                no_invoice: `{{ $orders->no_invoice }}`,
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                    }
                }
            };
            xhr.send(data);
        }
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $orders->snap_token }}', {
                onSuccess: function(result) {
                    // UpdateDatas(result);
                    location.reload();
                },
                onPending: function(result) {
                    UpdateDatas(result);
                    location.reload();
                },
                onError: function(result) {
                    location.reload();
                }
            });
        };
    </script>
@endsection
