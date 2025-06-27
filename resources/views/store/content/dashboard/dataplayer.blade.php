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
                                                <h6>{{ $steamhex->name }}</h6>
                                                <p>Steam Hex : {{ $steamhex->name }}</p>
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
        // function GetDataPlayer(identifiers) {
        //     return new Promise((resolve, reject) => {
        //         const xhr = new XMLHttpRequest();
        //         xhr.open('POST', 'http://208.76.40.92/api/getdataplayer', true);
        //         xhr.setRequestHeader('Content-Type', 'application/json');
        //         xhr.onload = function() {
        //             if (xhr.status === 200) {
        //                 resolve(JSON.parse(xhr.responseText));
        //             } else {
        //                 reject(new Error('Request failed'));
        //             }
        //         };
        //         xhr.send(JSON.stringify({
        //             identifier: identifiers
        //         }));
        //     });
        // }

        function GetDataPlayer(identifiers) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `http://208.76.40.92/api/getdataplayer`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            var data = JSON.stringify({
                identifier: identifiers,
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        return JSON.parse(xhr.responseText);
                    } else {
                        return false;
                    }
                }
            };

            xhr.send(data);
        }

        function SavePlayerData(data) {

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
                text: `Steam Hex : ${data.identifier}\nNama : ${data.name}`,
                icon: "inform",
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

        function AddSteam() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', `http://api.wasabistore.my.id/api/getdataplayer`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            var data = JSON.stringify({
                identifier: "steam:11000010bfcda0d",
            });
            xhr.send(data);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log(`Haisl aku ${JSON.parse(xhr.responseText)}`);
                    } else {
                        console.log(`Hasil Error`);
                    }
                }
            };            
        }
        // function AddSteam() {
        //     Swal.fire({
        //         title: "Masukan Steam Hex",
        //         input: "text",
        //         inputAttributes: {
        //             autocapitalize: "off"
        //         },
        //         showCancelButton: true,
        //         confirmButtonText: "Cari Data",
        //         showLoaderOnConfirm: true,
        //         preConfirm: async (identifier) => {
        //             if (identifier != "") {
        //                 try {
        //                     const playerData = await GetDataPlayer(identifier);
        //                     AlertSaveSteam(playerData)
        //                 } catch (error) {
        //                     Swal.showValidationMessage(`Request failed: ${error}`);
        //                 }
        //             } else {
        //                 Swal.showValidationMessage(`Input Tidak Boleh Kosong`);
        //             }
        //         },
        //         allowOutsideClick: () => !Swal.isLoading()
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             Swal.fire({
        //                 title: `${result.value.login}'s avatar`,
        //                 imageUrl: result.value.avatar_url
        //             });
        //         }
        //     });
        // }
    </script>
@endsection
