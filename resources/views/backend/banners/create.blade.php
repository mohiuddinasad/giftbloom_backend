@extends('backend.layout')
@section('backend_title', 'Add Banner')
@section('backend_content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Add New Banner</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Title <span class="text-muted">(optional)</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Banner Image</label>
                        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">JPG, PNG, WEBP — max 2MB</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Banner Video</label>
                        <input type="file" name="video" accept="video/*" class="form-control @error('video') is-invalid @enderror">
                        <small class="text-muted">MP4, MOV — max 20MB</small>
                        @error('video') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Banner</button>
                    <a href="{{ route('dashboard.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection