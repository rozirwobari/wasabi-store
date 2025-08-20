@extends('dashboard.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="col-lg-9 col-md-8">
        <div class="main-content" data-aos="fade-up">
            <div id="dashboard" class="tab-content active">
                <h2 class="section-title">
                    Admin Dashboard Overview
                </h2>
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card revenue">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        {{-- <div class="stat-value">Rp {{ number_format($orders->sum('total'), 0, ',', '.') }}</div> --}}
                        <div class="stat-value">Rp {{ number_format($orders->whereBetween('status', [3, 6])->sum('total'), 0, ',', '.') }}</div>
                        <div class="stat-label">Total Pendapatan</div>
                        <div class="stat-change {{ ($persetasiPendapatan >= 0) ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ ($persetasiPendapatan >= 0) ? 'up' : 'down' }}"></i>
                            {{ ($persetasiPendapatan > 0) ? '+' : '' }}{{ $persetasiPendapatan }}% from last month
                        </div>
                    </div>

                    <div class="stat-card orders">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-value">{{ number_format($totalTerjual, 0, ',', '.') }}</div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-change {{ ($persetasiOrders >= 0) ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ ($persetasiOrders >= 0) ? 'up' : 'down' }}"></i>
                            {{ ($persetasiOrders > 0) ? '+' : '' }}{{ $persetasiOrders }}% from last month
                        </div>
                    </div>
                    <div class="stat-card products">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value">{{ number_format(count($produks), 0, ',', '.') }}</div>
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-change" style="color: grey">
                            <i class="fa-solid fa-quote-left"></i> Produk dari semua kategori
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <h3 class="section-title">Orders Terbaru</h3>
                <div class="data-table">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Total Produk</th>
                                <th class="text-center">Total Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($orders->slice(0, 5) as $order)
                                    @php
                                        $item = json_decode($order->items);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $loop->iteration }}</strong>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <strong>{{ $order->user->name }}</strong>
                                                <small class="text-muted d-block">Terimakasih Telah Membeli...</small>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ number_format(count($item), 0, ',', '.') }} Produk</td>
                                        <td class="text-center">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->status >= 0 && $order->status <= 1)
                                                <span class="status-badge status-pending">Pending</span>
                                            @elseif ($order->status > 1 && $order->status < 4)
                                                <span class="status-badge status-processing">Processing</span>
                                            @elseif ($order->status == 4)
                                                <div class="status-badge status-shipped">Dikirim</div>
                                            @elseif ($order->status == 5)
                                                <div class="status-badge status-completed">Diambil</div>
                                            @elseif ($order->status == 404)
                                                <div class="status-badge status-canceled">Batal</div>
                                            @elseif ($order->status == 4001)
                                                <div class="status-badge status-canceled">Expired</div>
                                            @else
                                                <span class="status-badge status-inactive">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ \App\Helpers\WabiHelper::formatDate($order->created_at) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada Orders.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        <a class="btn btn-sm btn-secondary-custom m-1" title="[L3] Lihat Lebih Lengkap" href="{{ url('admin/orders') }}">
                            Lebih Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
