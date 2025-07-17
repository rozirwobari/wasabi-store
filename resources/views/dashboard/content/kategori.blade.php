@extends('dashboard.layout')

@section('title', 'Kategori')

@section('content')
    <div class="col-lg-9 col-md-8">
        <div class="main-content" data-aos="fade-up">
            <div class="tab-content">
                <h2 class="section-title">
                    Kategori Management
                </h2>

                <div class="produk-table">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Cari Kategori..." style="width: 350px;">
                        </div>
                        <div class="col-auto ms-auto">
                            <button class="btn btn-primary-custom" onclick="TambahKategori()">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                    </button>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Total Produk</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($kategoris) > 0)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($kategoris as $kategori)
                                    <tr>
                                        <td class="text-center">
                                            {{ $no++ }}
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <strong>{{ $kategori->label }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge status-active">{{ count($kategori->produk) }} Produk</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary-custom me-1" title="Edit Product" onclick="EditKategori({{ $kategori->id }}, '{{ $kategori->label }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger-custom me-1" title="View Details"
                                                onclick="HapusKategori({{ $kategori->id }}, '{{ $kategori->label }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada Kategori Yang Tersedia.</td>
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
        function HapusKategori(id, label) {
            Swal.fire({
                title: 'Hapus Kategori',
                text: `Apakah Anda yakin ingin menghapus kategori "${label}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('admin/hapuskategori') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        kategori_id: id,
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

        function TambahKategori() {
            Swal.fire({
                title: "Masukan Nama Kategori",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Tambah",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    console.log(login);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('admin/tambahkategori') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        kategori_name: login,
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
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }

        function EditKategori(id, label) {
            Swal.fire({
                title: `Kamu Merubah Kategori "${label}"`,
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Simpan",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    console.log(login);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('admin/editkategori') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        kategori_id: id,
                        kategori_name: login,
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
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }

        // Search functionality
        const searchInputs = document.querySelectorAll('input[placeholder*="Cari Kategori"]');
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
