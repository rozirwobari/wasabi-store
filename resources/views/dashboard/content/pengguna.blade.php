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
                    Users Management
                </h2>

                <div class="produk-table">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Cari User..." style="width: 350px;">
                        </div>
                        <div class="col-auto ms-auto">
                            <a class="btn btn-primary-custom" href="{{ url('admin/tambahpengguna') }}">
                                <i class="fas fa-plus me-2"></i>Tambah User
                            </a>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Tota Pembelian</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center">
                                            <strong>{{ $no++ }}</strong>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <strong>{{ $user->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>Rp {{ number_format($user->orders->whereBetween('status', [3, 6])->sum('total'), 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-secondary-custom me-1" title="Edit Produk"
                                                href="{{ url('admin/editpengguna/' . $user->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (Auth::user()->id != $user->id)
                                                <button class="btn btn-sm btn-danger-custom me-1" title="Hapus Produk"
                                                    onclick="HapusUsers({{ $user->id }}, '{{ $user->name }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada Pengguna Yang Tersedia.</td>
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
        function HapusUsers(id, label) {
            Swal.fire({
                title: 'Hapus User',
                text: `Apakah Anda yakin ingin menghapus "${label}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('admin/hapususer') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        user_id: id,
                    });
                    xhr.send(data);
                    xhr.onreadystatechange = function() {
                        console.log(JSON.stringify(xhr.responseText));
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

        // Search functionality
        const searchInputs = document.querySelectorAll('input[placeholder*="Cari User"]');
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
    </script>
@endsection
