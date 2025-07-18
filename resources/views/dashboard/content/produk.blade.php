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
                    Products Management
                    <a class="btn btn-primary-custom" href="{{ url('admin/tambahproduk') }}">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </a>
                </h2>

                <div class="produk-table">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto" style="width: 15vw;">
                            <select class="form-select">
                                <option>Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option>{{ $kategori->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto ms-auto">
                            <input type="text" class="form-control" placeholder="Cari Produk..." style="width: 15vw;">
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Image</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($produks) > 0)
                                @foreach ($produks as $produk)
                                    @php
                                        $images = json_decode($produk->images);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="product-image-wrapper">
                                                <a href="{{ $images && $images[0] ? asset($images[0]) : 'https://placehold.co/600x400' }}" data-lightbox="image-{{ $produk->id }}" data-title="{{ $produk->label }}">
                                                    <img src="{{ $images && $images[0] ? asset($images[0]) : 'https://placehold.co/600x400' }}" alt="Premium Wasabi Seeds" class="product-image">
                                                </a>
                                                @foreach ($images as $key => $image)
                                                    @if ($key > 0)
                                                        <div style="display: none">
                                                            <a href="{{ asset($image) }}"
                                                                data-lightbox="image-{{ $produk->id }}"
                                                                data-title="{{ $produk->label }}">
                                                                <div
                                                                    style="width: 10vw; height: 10vw; overflow: hidden; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 10px">
                                                                    <img src="{{ asset($image) }}" alt="Wasabi Store Produk"
                                                                        style="width: 10vw; height: 10vw; object-fit: cover;">
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td style="width: 16vw">
                                            <div class="product-info">
                                                <strong>{{ $produk->label }}</strong>
                                                <small class="text-muted d-block">Item/aksesoris premium berkualitas tinggi untuk senjata dan desain karakter kamu</small>
                                            </div>
                                        </td>
                                        <td>{{ $produk->kategoris && $produk->kategoris->label ? $produk->kategoris->label : 'Tidak Diketahui' }}
                                        </td>
                                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                        <td class="text-center" style="width: 8vw">
                                            <a class="btn btn-sm btn-secondary-custom me-1" title="Edit Product"
                                                href="{{ url('admin/editproduk/' . $produk->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger-custom me-1" title="View Details"
                                                onclick="HapusProduk({{ $produk->id }}, '{{ $produk->label }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada Produk Yang Tersedia.</td>
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

        const searchInputs = document.querySelectorAll('input[placeholder*="Cari Produk"]');
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
