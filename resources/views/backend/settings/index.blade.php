@extends('backend.layout')

@section('backend_title', 'Site Settings')

@section('backend_content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Site Settings</h4>
            <p class="text-muted small mb-0">Manage your website's general, branding and SEO information.</p>
        </div>
    </div>



    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- Left column -->
            <div class="col-lg-8">

                <!-- General Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-info-circle text-primary me-2"></i>General Information</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Site Name</label>
                                <input type="text" name="site_name"
                                    class="form-control @error('site_name') is-invalid @enderror"
                                    value="{{ old('site_name', $setting->site_name) }}">
                                @error('site_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Contact Phone</label>
                                <input type="text" name="contact_phone"
                                    class="form-control @error('contact_phone') is-invalid @enderror"
                                    value="{{ old('contact_phone', $setting->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Contact Email</label>
                                <input type="email" name="contact_email"
                                    class="form-control @error('contact_email') is-invalid @enderror"
                                    value="{{ old('contact_email', $setting->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Contact Address</label>
                                <input type="text" name="contact_address"
                                    class="form-control @error('contact_address') is-invalid @enderror"
                                    value="{{ old('contact_address', $setting->contact_address) }}">
                                @error('contact_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO / Meta -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-search text-primary me-2"></i>SEO & Meta</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Meta Title</label>
                            <input type="text" name="meta_title"
                                class="form-control @error('meta_title') is-invalid @enderror"
                                value="{{ old('meta_title', $setting->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-medium">Meta Description</label>
                            <textarea name="meta_description" rows="3"
                                class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label small fw-medium">Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                value="{{ old('meta_keywords', $setting->meta_keywords) }}"
                                placeholder="comma, separated, keywords">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate keywords with commas.</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right column -->
            <div class="col-lg-4 position-sticky" style="top: 80px;">

                <!-- Branding -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-palette text-primary me-2"></i>Branding</h6>
                    </div>
                    <div class="card-body px-4 pb-4">

                        <!-- Logo -->
                        <div class="mb-4">
                            <label class="form-label small fw-medium d-block">Site Logo</label>
                            <div class="border rounded-3 p-3 text-center bg-light mb-2">
                                <img id="logoPreview"
                                    src="{{ $setting->site_logo ? asset($setting->site_logo) : 'https://via.placeholder.com/200x80?text=No+Logo' }}"
                                    alt="Site Logo" class="img-fluid" style="max-height: 70px;">
                            </div>
                            <input type="file" name="site_logo" accept="image/*"
                                class="form-control form-control-sm @error('site_logo') is-invalid @enderror"
                                onchange="previewImage(this, 'logoPreview')">
                            @error('site_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PNG, JPG, WEBP — max 2MB</div>
                        </div>

                        <hr>

                        <!-- Favicon -->
                        <div>
                            <label class="form-label small fw-medium d-block">Favicon</label>
                            <div class="border rounded-3 p-3 text-center bg-light mb-2">
                                <img id="faviconPreview"
                                    src="{{ $setting->site_favicon ? asset($setting->site_favicon) : 'https://via.placeholder.com/40?text=?' }}"
                                    alt="Favicon" class="img-fluid" style="max-height: 40px;">
                            </div>
                            <input type="file" name="site_favicon" accept="image/*"
                                class="form-control form-control-sm @error('site_favicon') is-invalid @enderror"
                                onchange="previewImage(this, 'faviconPreview')">
                            @error('site_favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PNG, ICO, JPG — max 512KB</div>
                        </div>

                    </div>
                </div>

                <!-- Save button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="bi bi-check2-circle me-1"></i> Save Settings
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>

@push('backend_js')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
