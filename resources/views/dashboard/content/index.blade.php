@extends('dashboard.layout')

@section("title", "Dashboard")

@section('content')
    <div class="container-fluid px-4 py-5">
        <!-- Stats Cards -->
        <div class="row fade-in">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card green">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">2,847</div>
                    <div class="stats-label">Total Pengguna</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card red">
                    <div class="stats-icon">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <div class="stats-number">1,234</div>
                    <div class="stats-label">Pesanan Diproses</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card gold">
                    <div class="stats-icon">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </div>
                    <div class="stats-number">Rp 45.2M</div>
                    <div class="stats-label">Pesanan Berhasil</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card white">
                    <div class="stats-icon">

                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stats-number">Rp 4.5jt</div>
                    <div class="stats-label">Pendapatan</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8">
                <div class="chart-card">
                    <h5 class="mb-4">Grafik Penjualan</h5>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="chart-card">
                    <h5 class="mb-4">Aktivitas Terbaru</h5>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="fw-bold">Pengguna baru mendaftar</div>
                            <div class="activity-time">2 menit yang lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="fw-bold">Pesanan #1234 selesai</div>
                            <div class="activity-time">5 menit yang lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="fw-bold">Produk baru ditambahkan</div>
                            <div class="activity-time">10 menit yang lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="fw-bold">Pembayaran berhasil</div>
                            <div class="activity-time">15 menit yang lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-header">
                        <h5 class="mb-0">Pesanan Terbaru</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Produk</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#ORD-001</strong></td>
                                    <td>Ahmad Wijaya</td>
                                    <td>Laptop Gaming</td>
                                    <td><strong>Rp 15.500.000</strong></td>
                                    <td><span class="badge badge-success">Selesai</span></td>
                                    <td>07 Jun 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
