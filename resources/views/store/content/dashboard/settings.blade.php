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
                        <div id="settings" class="tab-content">
                            <h2 class="section-title">Account Settings</h2>

                            <form action="{{ url('changepassword') }}" method="post">
                                @csrf
                                <div class="profile-form">
                                    <h5 class="mb-3">Ubah Password</h5>
                                    <div class="form-group">
                                        <label class="form-label">Password Lama</label>
                                        <input type="password" name="password" class="form-control-dashboard w-100 @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" name="new_password" class="form-control-dashboard w-100 @error('new_password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="confirm_password" class="form-control-dashboard w-100 @error('confirm_password') is-invalid @enderror">
                                        @error('confirm_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button class="btn btn-primary-custom mb-4">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection