@extends('store.layout')

@section('title', 'Keranjang Belanja')

@section('css')
    <style>
        /* Invoice Specific Styles */
        .invoice-container {
            flex: 1;
            padding: 40px 0;
            background: linear-gradient(135deg, rgba(62, 76, 44, 0.02) 0%, rgba(231, 166, 92, 0.02) 100%);
        }

        .invoice-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .invoice-header {
            background: linear-gradient(135deg, var(--green) 0%, #4a5c35 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(231,166,92,0.1)" stroke-width="0.5"/><circle cx="50" cy="50" r="30" fill="none" stroke="rgba(231,166,92,0.1)" stroke-width="0.5"/></svg>') center/30% no-repeat;
            animation: rotate 30s infinite linear;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .invoice-header-content {
            position: relative;
            z-index: 1;
        }

        .invoice-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
        }

        .invoice-subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .invoice-body {
            padding: 40px;
        }

        .invoice-info {
            background: rgba(231, 166, 92, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 5px solid var(--gold);
        }

        .invoice-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: var(--green);
            font-weight: 600;
            font-size: 1rem;
        }

        .billing-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .billing-card {
            background: rgba(62, 76, 44, 0.02);
            border-radius: 15px;
            padding: 25px;
            border: 2px solid rgba(62, 76, 44, 0.1);
            transition: all 0.3s;
        }

        .billing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .billing-title {
            color: var(--green);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .billing-title i {
            color: var(--gold);
        }

        .billing-details p {
            margin-bottom: 5px;
            color: #495057;
        }

        .items-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--green) 0%, #4a5c35 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 20px 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .table tbody td {
            padding: 20px 15px;
            vertical-align: middle;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(231, 166, 92, 0.05);
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .item-name {
            font-weight: 600;
            color: var(--green);
            margin-bottom: 5px;
        }

        .item-description {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .price-text {
            font-weight: 700;
            color: var(--green);
            font-size: 1.1rem;
        }

        .summary-section {
            background: linear-gradient(135deg, rgba(62, 76, 44, 0.05) 0%, rgba(231, 166, 92, 0.05) 100%);
            border-radius: 15px;
            padding: 30px;
            border: 2px solid rgba(231, 166, 92, 0.2);
        }

        .summary-label {
            font-weight: 600;
            color: #495057;
        }

        .summary-value {
            font-weight: 700;
            color: var(--green);
        }

        .total-row .summary-label,
        .total-row .summary-value {
            font-size: 1.3rem;
            color: var(--green);
        }

        .payment-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-paid {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 2px solid rgba(40, 167, 69, 0.2);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 2px solid rgba(255, 193, 7, 0.2);
        }

        .status-failed {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 2px solid rgba(220, 53, 69, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 30px 0;
        }

        .btn-action {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-action:hover::before {
            left: 100%;
        }

        .btn-download {
            background: linear-gradient(135deg, var(--green) 0%, #4a5c35 100%);
            color: white;
            border: none;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(62, 76, 44, 0.3);
            color: white;
        }

        .btn-print {
            background: linear-gradient(135deg, var(--gold) 0%, #d4954f 100%);
            color: var(--green);
            border: none;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 166, 92, 0.3);
            color: var(--green);
        }

        .btn-email {
            background: transparent;
            color: var(--green);
            border: 2px solid var(--green);
        }

        .btn-email:hover {
            background: var(--green);
            color: white;
            transform: translateY(-2px);
        }

        .product-details h6 {
            margin: 0;
            color: var(--green);
            font-weight: 600;
        }

        .product-details small {
            color: #6c757d;
        }

        .invoice-table {
            margin-bottom: 40px;
        }

        .invoice-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th {
            background: rgba(62, 76, 44, 0.05);
            color: var(--green);
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid rgba(62, 76, 44, 0.1);
        }

        .invoice-table td {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .invoice-table tr:hover {
            background: rgba(231, 166, 92, 0.03);
        }

        .product-info-invoice {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: rgba(231, 166, 92, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 1.5rem;
        }

        .product-details h6 {
            margin: 0;
            color: var(--green);
            font-weight: 600;
        }

        .product-details small {
            color: #6c757d;
        }

        .invoice-summary {
            /* border-top: 2px solid rgba(62, 76, 44, 0.1); */
            /* padding-top: 30px;
                margin-top: 30px; */
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px 0;
        }

        .summary-row.total {
            /* border-top: 2px solid rgba(62, 76, 44, 0.1); */
            padding-top: 20px;
            margin-top: 20px;
        }

        .summary-row.total .summary-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--green);
        }

        .summary-row.total .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gold);
        }

        .summary-label {
            color: #6c757d;
            font-weight: 600;
        }

        .summary-value {
            color: var(--green);
            font-weight: 600;
        }

        .invoice-footer {
            background: rgba(62, 76, 44, 0.05);
            padding: 30px 40px;
            border-top: 2px solid rgba(62, 76, 44, 0.1);
        }

        .payment-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            color: var(--green);
            font-size: 1.5rem;
        }

        /* Style for Bayar Button */
        .btn-bayar {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--green) 0%, #4a5c35 100%);
            color: white;
            border: none;
        }

        .btn-bayar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(62, 76, 44, 0.3);
            color: white;
        }

        .btn-bayar i {
            font-size: 1.2rem;
        }

        /* Optionally you can add a loading state or spinner */
        .btn-bayar:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-bayar:focus {
            outline: none;
        }

        /* Style for Back to Home Button */
        .btn-home {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            text-decoration: none;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            color: white;
        }

        .btn-home i {
            font-size: 1.2rem;
        }

        /* Optionally you can add a loading state or spinner */
        .btn-home:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-home:focus {
            outline: none;
        }


        /* Print Styles */
        @media print {

            .navbar,
            .footer,
            .action-buttons {
                display: none !important;
            }

            .invoice-container {
                padding: 0;
            }

            .invoice-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
@endsection

@section('content')
    <div class="invoice-container">
        <div class="container">
            <div class="invoice-card" data-aos="fade-up" data-aos-duration="1000">
                <!-- Invoice Header -->
                <div class="invoice-header">
                    <div class="invoice-header-content">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="invoice-logo">Wasabi Garden</div>
                                <p class="invoice-subtitle">Server FiveM semi-mmorpg premium experience</p>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <h1 class="invoice-title">INVOICE</h1>
                                <div class="payment-status status-paid">
                                    <i class="fas fa-check-circle"></i>
                                    Paid
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Body -->
                <div class="invoice-body">
                    <!-- Invoice Info -->
                    <div class="invoice-info" data-aos="fade-up" data-aos-delay="100">
                        <div class="invoice-number">#WG-2025-001337</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Tanggal Invoice</span>
                                <span class="info-value">06 Juni 2025</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Dibayarkan Kepada</span>
                                <span class="info-value">Wasabi Project</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tagihan Kepada</span>
                                <span class="info-value">Jamal Kusuma M</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="info-value">Belum Dibayar</span>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-table">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Item</th>
                                    <th style="width: 15%;">Harga</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 15%;">Diskon</th>
                                    <th style="width: 15%; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $items = json_decode($orders->items, false);
                                    $total = array_sum(array_column($items, 'total'))
                                @endphp
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="product-info-invoice">
                                                <div class="product-image">
                                                    <i class="fas fa-gem"></i>
                                                </div>
                                                <div class="product-details">
                                                    <h6>{{ $item->name }}</h6>
                                                    {{-- <small>Akses VIP 30 Hari</small> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>0%</td>
                                        <td style="text-align: right;">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-light border-start border-2 border-warning">
                                <h6 class="alert-heading mb-2">
                                    <i class="fas fa-info-circle me-2"></i>Catatan Pembayaran
                                </h6>
                                <p class="mb-0 small">Harap lakukan pembayaran sebelum tanggal jatuh tempo untuk
                                    menghindari pembatalan otomatis.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-row total">
                                <span class="summary-label">Total Pembayaran</span>
                                <span class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="action-buttons d-flex justify-content-between">
                                <a href="{{ url('/') }}" class="btn-action btn-home">
                                    <i class="fas fa-home me-2"></i> Back to Home
                                </a>
                                <button id="pay-button" class="btn-action btn-bayar">
                                    <i class="fas fa-credit-card me-2"></i> Bayar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $orders->snap_token }}', {
                onSuccess: function(result) {
                    alert("Payment success!");
                },
                onPending: function(result) {
                    alert("Waiting for payment approval.");
                },
                onError: function(result) {
                    alert("Payment failed.");
                }
            });
        };
    </script>
@endsection
