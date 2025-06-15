@extends('dashboard.layout')

@section("title", "Produk")

@section('content')
    <div class="container-fluid px-4 py-5">
        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kategori</h5>
                        <a class="btn btn-primary" href="{{ url('dashboards/tambahproduk') }}">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light p-2">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Action</th>
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
                                                @if (count($images) > 0 )
                                                <a href="{{ asset($images[0]) }}" data-lightbox="image-{{ $produk->id }}" data-title="{{ $produk->label }}">
                                                    <div style="width: 10vw; height: 10vw; overflow: hidden; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 10px">
                                                        <img src="{{ asset($images[0]) }}" alt="Wasabi Store Produk" style="width: 10vw; height: 10vw; object-fit: cover;">
                                                    </div>
                                                </a>
                                                @foreach ($images as $key => $image)
                                                    @if ($key > 0)
                                                        <div style="display: none">
                                                            <a href="{{ asset($image) }}" data-lightbox="image-{{ $produk->id }}" data-title="{{ $produk->label }}">
                                                                <div style="width: 10vw; height: 10vw; overflow: hidden; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 10px">
                                                                    <img src="{{ asset($image) }}" alt="Wasabi Store Produk" style="width: 10vw; height: 10vw; object-fit: cover;">
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @else
                                                    <p>Gambar Tidak Ada</p>
                                                @endif
                                            </td>
                                            <td>{{ $produk->label }}</td>
                                            <td>{{ $produk->kategoris && $produk->kategoris->label ? $produk->kategoris->label : 'Tidak Diketahui' }}</td>
                                            <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                            <td>{!! $produk->deskripsi !!}</td>
                                            <td width="150px" class="text-center">
                                                <button class="btn btn-sm btn-danger me-1"
                                                    onclick="HapusProduk({{ $produk->id }}, '{{ $produk->label }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <a class="btn btn-sm btn-warning me-1" href="{{ url('dashboards/editproduk/'.$produk->id) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada Produk Yang Tersedia.</td>
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
                    xhr.open('POST', `{{ url('dashboards/hapusproduk') }}`, true);
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
    </script>
@endsection
