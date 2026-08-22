@extends('backend.layout')
@section('backend_title', 'Profile')
@section('backend_content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>



    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ auth()->user()->user_image ? asset('upload/' . auth()->user()->user_image) : asset('backend/assets/img/avatars/1.png') }}"
                                alt="user-avatar" class="d-block rounded" height="100" width="100"
                                id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="user_image" class="account-file-input" hidden
                                        accept="image/png, image/jpeg, image/gif" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                @error('user_image')
                                    <p class="text-danger mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" placeholder="Enter your name"
                                    autofocus />
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" placeholder="john.doe@example.com" />
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phone">Phone Number</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="{{ old('phone', auth()->user()->phone) }}"
                                        placeholder="Enter your phone number" />
                                </div>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address', auth()->user()->address) }}"
                                    placeholder="Enter your address" />
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>

                    </div>
                    <!-- /Account -->
                </div>
            </form>

            <div class="card mb-4" id="password-section">
                <h4 class="card-header">Password Change</h4>

                @if (session('password_success'))
                    <div class="alert alert-success mx-3 mt-3">{{ session('password_success') }}</div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.profile.password.update') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                @error('currentPassword')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />

                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="newPassword_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="newPassword_confirmation"
                                    id="newPassword_confirmation"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <h5 class="card-header">Delete Account</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation"
                                id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">I confirm my account
                                deactivation</label>
                        </div>
                        <a href="{{ route('profile.destroy') }}" class="btn btn-danger deactivate-account">Deactivate Account</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('backend_js')
    <script>
        // Image preview before upload
        document.getElementById('upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('uploadedAvatar').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
