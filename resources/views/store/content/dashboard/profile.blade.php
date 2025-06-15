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
                        <div id="profile" class="tab-content">
                            <h2 class="section-title">My Profile</h2>

                            <form class="profile-form" method="POST" action="{{ route('profileupdate') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control-dashboard w-100" id="name" name="name" value="{{ Auth::user()->name }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Steam Hex</label>
                                    <input type="text" class="form-control-dashboard w-100" id="steam_hex" name="steam_hex" value="{{ Auth::user()->steam_hex }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control-dashboard w-100" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                </div>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save me-2"></i>Simpan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('alert') && session('alert')['title'])
        <script>
            Swal.fire({
                title: "{{ session('alert')['title'] }}",
                text: "{{ session('alert')['message'] }}",
                icon: "{{ session('alert')['type'] }}"
            });
        </script>
    @endif
@endsection