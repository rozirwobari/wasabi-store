@extends('dashboard.layout')

@section('title', 'Produk')

@section('css')
    <style>
        .order-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 20px 0;
        }

        .order-progress::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0e0e0;
            color: #999;
            font-size: 1.2rem;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .progress-step.completed .step-icon {
            background: #4caf50;
            color: white;
        }

        .progress-step.gagal .step-icon {
            background: #af4c4c;
            color: white;
        }

        .progress-step.active .step-icon {
            background: var(--gold);
            color: white;
            animation: pulse 2s infinite;
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

        .step-content h6 {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: var(--green);
        }

        .step-content small {
            font-size: 0.8rem;
        }

        .order-summary {
            padding: 10px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .summary-row.total {
            padding-top: 10px;
            font-size: 1.1rem;
            color: var(--green);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--green) 0%, #4a5d35 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
            padding: 15px 20px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        @media (max-width: 768px) {
            .order-progress {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-progress::before {
                display: none;
            }

            .progress-step {
                flex-direction: row;
                align-items: center;
                text-align: left;
                margin-bottom: 20px;
                width: 100%;
            }

            .step-icon {
                margin-right: 15px;
                margin-bottom: 0;
            }

            .step-content {
                text-align: left;
            }
        }
    </style>
@endsection

@section('content')
@php
    $items = json_decode($orders->items, false);
    $playerData = json_decode($orders->playerdata, false);
    $tgl_transaksi = json_decode($orders->tgl_transaksi, true);
    $total = array_sum(array_column($items, 'total'));
@endphp
    <div class="col-lg-9 col-md-8">
        <div id="order-view" class="tab-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">
                    <i class="fas fa-arrow-left me-2 text-primary" style="cursor: pointer;" onclick="goBackToOrders()"></i>
                    Order Details #{{ $orders->no_invoice }}
                </h2>
            </div>

            <!-- Order Status Progress -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Tracking</h5>
                    <div class="order-progress">
                        <div class="progress-step completed">
                            <div class="step-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="step-content {{ $orders->status == 0 ? 'active' : ($orders->status > 0 ? 'completed' : '') }}">
                                <h6>Pesanan Diterima</h6>
                                <small class="text-muted">{{ \App\Helpers\WabiHelper::formatDate($orders->created_at) }}</small>
                            </div>
                        </div>

                        @if ($orders->status == 404)
                            <div class="progress-step">
                                <div class="step-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="step-content {{ $orders->status == 1 ? 'active' : ($orders->status > 1 ? 'gagal' : '') }}">
                                    <h6>Pembayaran Gagal</h6>
                                    <small class="text-muted">{{ ($value = $tgl_transaksi['404'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</small>
                                </div>
                            </div>
                        @else
                            <div class="progress-step">
                                <div class="step-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="step-content {{ $orders->status == 1 ? 'active' : ($orders->status > 1 ? 'completed' : '') }}">
                                    <h6>Pembayaran Berhasil</h6>
                                    <small class="text-muted">{{ ($value = $tgl_transaksi['2'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</small>
                                </div>
                            </div>
                        @endif

                        <div class="progress-step {{ $orders->status <= 6 ? $orders->status == 2 ? 'active' : ($orders->status > 2 ? 'completed' : '') : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="step-content">
                                <h6>Pesanan Diproses</h6>
                                <small class="text-muted">{{ ($value = $tgl_transaksi['3'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</small>
                            </div>
                        </div>
                        <div class="progress-step {{ $orders->status <= 6 ? $orders->status == 3 ? 'active' : ($orders->status > 3 ? 'completed' : '') : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="step-content">
                                <h6>Pesanan Sampai</h6>
                                <small class="text-muted">{{ ($value = $tgl_transaksi['4'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</small>
                            </div>
                        </div>
                        <div class="progress-step {{ $orders->status <= 6 ? $orders->status == 4 ? 'active' : ($orders->status > 4 ? 'completed' : '') : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-content">
                                <h6>Diambil</h6>
                                <small class="text-muted">{{ ($value = $tgl_transaksi['5'] ?? null) ? \App\Helpers\WabiHelper::formatDate(date('Y-m-d H:i:s', $value)) : '' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Order Information -->
                <div class="col-lg-8">
                    <!-- Customer Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Target Player
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nama:</strong> {{ $playerData->name }}</p>
                                    <p class="mb-1"><strong>Steam Hex:</strong> {{ $playerData->identifier }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Produk</th>
                                            <th class="border-0 text-center">Jumlah</th>
                                            <th class="border-0 text-center">Harga</th>
                                            <th class="border-0 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = json_decode($orders->items)
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="badge bg-light text-dark">{{ $item->jumlah }}</span>
                                                </td>
                                                <td class="text-center py-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td class="text-center py-3"><strong>Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span>Rp 235.000</span>
                                </div>
                                <hr>
                                <div class="summary-row total">
                                    <span><strong>Total:</strong></span>
                                    <span><strong>Rp 265.000</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    {{-- <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Payment Method:</strong> Bank Transfer</p>
                            <p class="mb-2"><strong>Payment Status:</strong>
                                <span class="status-badge status-completed">Paid</span>
                            </p>
                            <p class="mb-2"><strong>Transaction ID:</strong> TXN-123456789</p>
                            <p class="mb-2"><strong>Payment Date:</strong> 28 May 2025, 10:45 AM</p>
                            <p class="mb-0"><strong>Bank:</strong> Bank Mandiri</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function goBackToOrders() {
            window.location.href = `{{ url('admin/orders') }}`;
        }
    </script>
@endsection
