@extends('dashboard.layout')

@section('title', 'Produk')

@section('css')
    <style>
        .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }

    </style>
@endsection

@section('content')
    <div class="col-lg-9 col-md-8">
        <div class="main-content" data-aos="fade-up">
            <div class="tab-content">
                <h2 class="section-title">
                    Orders Management
                </h2>

                <div class="stats-grid">
                    <div class="stat-card revenue">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">Rp {{ number_format($orders->sum('total'), 0, ',', '.') }}</div>
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
                </div>

                <div class="produk-table">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Cari Pembeli..." style="width: 350px;">
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Total Produk</th>
                                <th class="text-center">Total Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $item = json_decode($order->items);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $no++ }}</strong>
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
                                            @else
                                                <span class="status-badge status-inactive">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ \App\Helpers\WabiHelper::formatDate($order->created_at) }}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-secondary-custom m-1" title="Detail Order" href="{{ url('admin/show-orders/' . $order->no_invoice) }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            @if ($order->status == 3)
                                                <a class="btn btn-sm btn-secondary-custom m-1" title="Proses Kirim" href="{{ url('admin/editproduk/' . $order->id) }}">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada Orders.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        function HapusProduk(id, label) {
            Swal.fire({
                title: 'Hapus Produk',
                text: `Apakah Anda yakin ingin menghapus "${label}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('admin/hapusproduk') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        produk_id: id,
                    });
                    xhr.send(data);

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            Swal.fire({
                                title: data.title,
                                text: data.text,
                                icon: data.type
                            });
                            if (data.type == 'success') {
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    };
                }
            });
        }

        const searchInputs = document.querySelectorAll('input[placeholder*="Cari Pembeli"]');
        searchInputs.forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const table = this.closest('.produk-table').querySelector('table tbody');
                const rows = table.querySelectorAll('tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        const statusSelects = document.querySelectorAll('select');
        statusSelects.forEach(select => {
            select.addEventListener('change', function() {
                const filterValue = this.value.toLowerCase();
                const table = this.closest('.produk-table').querySelector('table tbody');
                const rows = table.querySelectorAll('tr');

                rows.forEach(row => {
                    if (filterValue === '' || filterValue === 'all status' || filterValue ===
                        'semua kategori' || filterValue === 'all roles') {
                        row.style.display = '';
                    } else {
                        const statusBadge = row.querySelector('.status-badge');
                        const categoryCell = row.cells[2];

                        let shouldShow = false;

                        if (statusBadge && statusBadge.textContent.toLowerCase().includes(
                                filterValue)) {
                            shouldShow = true;
                        }

                        if (categoryCell && categoryCell.textContent.toLowerCase().includes(
                                filterValue)) {
                            shouldShow = true;
                        }

                        row.style.display = shouldShow ? '' : 'none';
                    }
                });
            });
        });
    </script>
@endsection
