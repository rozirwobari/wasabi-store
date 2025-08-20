@extends('store.layout')

@section('css')
    <style>
        .status-canceled {
            background-color: rgba(175, 76, 76, 0.2);
            color: #f44336;
        }
        .status-unknown {
            background-color: rgba(158, 158, 158, 0.2);
            color: #000000;
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
                    <div class="main-content" data-aos="fade-up">
                        <!-- Dashboard Tab -->
                        <div id="dashboard" class="tab-content active">
                            <h2 class="section-title">Dashboard Overview</h2>

                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="stat-value">{{ count($orders) }}</div>
                                    <div class="stat-label">Total Orders</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="stat-value">{{ $orders->where('status', 0)->count() }}</div>
                                    <div class="stat-label">Pending Delivery</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="stat-value">{{ $orders->where('status', '>=', 2)->count() }}</div>
                                    <div class="stat-label">Proses Order</div>
                                </div>
                            </div>

                            <h3 class="section-title">Pesanan Terbaru</h3>
                            <div class="recent-orders">
                                @if ($orders->isEmpty())
                                    <h5 class="text-center">Belum Ada Pesanan</h5>
                                @else
                                    @foreach ($orders->take(3) as $order)
                                        <div class="order-item">
                                            <div class="order-info">
                                                <h6><a href="{{ url('order-details/'.$order->no_invoice) }}">Order #{{ $order->no_invoice }}</a></h6>
                                                <p>Tgl Pesanan : {{ \App\Helpers\WabiHelper::formatDate($order->created_at) }}</p>
                                                <p>Total: Rp <b>{{ number_format($order->total, 0, ',', '.') }}</b></p>
                                            </div>
                                            @if ($order->status >= 0 && $order->status <= 1)
                                                <span class="order-status-badge status-pending">Pending</span>
                                            @elseif ($order->status > 1 && $order->status < 4)
                                                <span class="order-status-badge status-processing">Processing</span>
                                            @elseif ($order->status == 4)
                                                <div class="order-status-badge status-shipped">Dikirim</div>
                                            @elseif ($order->status == 5)
                                                <div class="order-status-badge status-completed">Diambil</div>
                                            @elseif ($order->status == 404)
                                                <div class="order-status-badge status-canceled">Canceled</div>
                                            @else
                                                <span class="order-status-badge status-unknown">Unknown</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="text-center mt-4">
                                        <a href="{{ url('orders') }}" class="btn btn-primary-custom">Selengkapnya</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
