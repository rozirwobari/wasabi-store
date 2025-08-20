@extends('store.layout')

@section('css')
    <style>
        .btn-resend-custom {
            background: linear-gradient(135deg, #5cb4e7 0%, #5798be 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-resend-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px #5cb4e7b2;
            color: white;
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
                        <div id="orders" class="tab-content">
                            <div class="row">
                                <div class="col-sm-6 col-md-8">
                                    <h2 class="section-title">
                                        Data Player
                                    </h2>
                                </div>
                                <div class="col-6 col-md-4 text-end">
                                    <button type="submit" class="btn btn-primary-custom" onclick="AddSteam()">
                                        <i class="fa-brands fa-steam"></i> Tambah Steam
                                    </button>
                                </div>
                            </div>

                            <div class="recent-orders">
                                @if ($steamhexs->isEmpty())
                                    <h5 class="text-center">Belum Ada Steam Hex</h5>
                                @else
                                    @foreach ($steamhexs as $steamhex)
                                        <div class="order-item d-flex justify-content-between align-items-center">
                                            <div class="order-info">
                                                @if ($steamhex->status == 1)
                                                    <h5 id="player_name_{{ $steamhex->identifier }}">{{ $steamhex->name }}
                                                        <span class="badge bg-success">Active</span></h5>
                                                @else
                                                    <h5 id="player_name_{{ $steamhex->identifier }}">{{ $steamhex->name }}
                                                        <span class="badge bg-warning">Pending</span></h5>
                                                @endif
                                                <p>Steam Hex : <b>{{ $steamhex->identifier }}</b></p>
                                            </div>
                                            <div class="order-actions">
                                                @if ($steamhex->status == 0)
                                                    <button type="button" class="btn btn-resend-custom"
                                                        onclick="ResendToPlayer('{{ $steamhex->identifier }}')">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                    </button>
                                                @elseif ($steamhex->status == 1)
                                                    <button type="button" class="btn btn-primary-custom"
                                                        onclick="RefreshData('{{ $steamhex->identifier }}')">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger-custom"
                                                    onclick="HapusPlayerData('{{ $steamhex->identifier }}', '{{ $steamhex->name }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function ResendToPlayer(identifier) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('resendlinked') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                identifier: identifier,
            });
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    const respon = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (respon.success) {
                            Swal.fire({
                                title: `${respon.title}`,
                                text: `${respon.message}`,
                                icon: `${respon.type}`
                            });
                        }
                    } else {
                        Swal.fire({
                            title: `${respon.title}`,
                            text: `${respon.message}`,
                            icon: `${respon.type}`
                        });
                    }
                }
            };
            xhr.send(data);
        }

        function SavePlayerData(data) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('saveplayerdata') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                name: data.name,
                identifier: data.identifier,
            });

            Swal.fire({
                title: `Memproses...`,
                text: 'Sedang Linked Akun Ke Player',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    const respon = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (respon.success) {
                            location.reload(true);
                        } else {
                            Swal.fire({
                                title: "Warning",
                                text: `${respon.message}`,
                                icon: "warning"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: `${respon.message}`,
                            icon: "denger"
                        });
                    }
                }
            };
            xhr.send(data);
        }

        function AlertSaveSteam(data) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Steam Data",
                html: `Steam Hex: <b>${data.identifier}</b><br>Nama: <b>${data.name}</b>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya Tambah",
                cancelButtonText: "Tidak, Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    SavePlayerData(data)
                }
            });
        }

        function GetDataPlayer(identifier) {
            return new Promise((resolve, reject) => {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', `{{ url('getplayerdata') }}`, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                var data = JSON.stringify({
                    identifier: identifier,
                });

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            try {
                                const jsonResponse = JSON.parse(xhr.responseText);
                                resolve(jsonResponse);
                            } catch (e) {
                                reject("Invalid JSON response");
                            }
                        } else {
                            const jsonResponse = JSON.parse(xhr.responseText);
                            reject(`${jsonResponse.message}`);
                        }
                    }
                };

                xhr.onerror = function() {
                    reject("Network error");
                };

                xhr.send(data);
            });
        }


        function AddSteam() {
            Swal.fire({
                title: "Masukan Steam Hex",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Cari Data",
                showLoaderOnConfirm: true,
                preConfirm: async (dataInput) => {
                    if (dataInput != "") {
                        try {
                            const playerData = await GetDataPlayer(dataInput);
                            return playerData;
                        } catch (error) {
                            return Swal.showValidationMessage(`${error}`);
                        }
                    } else {
                        return Swal.showValidationMessage(`Input Tidak Boleh Kosong`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    AlertSaveSteam(result.value.data.data)
                }
            });
        }

        function RefreshData(steam) {
            Swal.fire({
                title: `Memproses...`,
                text: 'Sedang mengupdate data Player',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('updateplayerdata') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                identifier: steam,
            });
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    const respon = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (respon?.success) {
                            let PlayerData = respon.data
                            document.getElementById(`player_name_${steam}`).textContent = PlayerData.name;
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data Steam berhasil diupdate',
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal!',
                                text: 'Data Steam gagal diupdate',
                                showConfirmButton: true
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            // text: `Error : ${JSON.stringify(xhr.responseText)}`,
                            text: `Error : ${respon.message}`,
                            showConfirmButton: true
                        });
                    }
                }
            };
            xhr.send(data);
        }

        function HapusPlayerData(steam, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Kamu Yakin?",
                text: `Apakah Kamu Benar Benar Ingin Menghapus Data Player ${name}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Tidak, Batalkan!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', `{{ url('deleteplayerdata') }}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    var data = JSON.stringify({
                        identifier: steam,
                    });
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                const respon = JSON.parse(xhr.responseText);
                                if (!respon.success) {
                                    return Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal!',
                                        text: `${respon?.message ?? 'Data Steam gagal dihapus'}`,
                                        showConfirmButton: true
                                    });
                                }
                                Swal.fire({
                                    title: `Memproses...`,
                                    text: 'Sedang menghapus data Player',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    allowEnterKey: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                                location.reload(true);
                            }
                        }
                    };
                    xhr.send(data);
                }
            });
        }
    </script>
@endsection
