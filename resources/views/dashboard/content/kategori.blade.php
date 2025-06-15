@extends('dashboard.layout')

@section("title", "Kategori")

@section('content')
    <div class="container-fluid px-4 py-5">
        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kategori</h5>
                        <button class="btn btn-primary" onclick="TambahKategori()">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light p-2">
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($kategoris) > 0)
                                    @foreach ($kategoris as $kategori)
                                        <tr>
                                            <td>{{ $kategori->label }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger me-1"
                                                    onclick="HapusKategori({{ $kategori->id }}, '{{ $kategori->label }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning me-1"
                                                    onclick="EditKategori({{ $kategori->id }}, '{{ $kategori->label }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada kategori yang tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
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
                    xhr.open('POST', `{{ url('dashboards/hapuskategori') }}`, true);
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
                    xhr.open('POST', `{{ url('dashboards/tambahkategori') }}`, true);
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
                    xhr.open('POST', `{{ url('dashboards/editkategori') }}`, true);
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
    </script>
@endsection
