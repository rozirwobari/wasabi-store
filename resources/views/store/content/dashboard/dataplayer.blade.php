@extends('store.layout')

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
                                        <div class="order-item">
                                            <div class="order-info">
                                                <h5>{{ $steamhex->name }}</h5>
                                                <p>Steam Hex : <b>{{ $steamhex->identifier }}</b></p>
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
        function SavePlayerData(data) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `{{ url('saveplayerdata') }}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            var data = JSON.stringify({
                name: data.name,
                identifier: data.identifier,
            });
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const respon = JSON.parse(xhr.responseText);
                        if (respon.success) {
                            location.reload(true);
                        } else {
                            Swal.fire({
                                title: "Warning",
                                text: `${respon.message}`,
                                icon: "warning"
                            });
                        }
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

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            try {
                                const jsonResponse = JSON.parse(xhr.responseText);
                                resolve(jsonResponse);
                            } catch (e) {
                                reject("Invalid JSON response");
                            }
                        } else {
                            reject(`Request failed with status ${xhr.status}`);
                        }
                    }
                };

                xhr.onerror = function () {
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
                            return Swal.showValidationMessage(`Request failed: ${error}`);
                        }
                    } else {
                        return Swal.showValidationMessage(`Input Tidak Boleh Kosong`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(`hasilnya ${JSON.stringify(result)}`);
                    AlertSaveSteam(result.value.data.data)
                }
            });
        }
    </script>
@endsection
