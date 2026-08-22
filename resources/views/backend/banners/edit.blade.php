@extends('backend.layout')
@section('backend_title', 'Edit Banner')
@section('backend_content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Edit Banner</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-control @error('title') is-invalid @enderror">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Banner Image</label>
                        @if ($banner->image)
                            <div class="mb-2">
                                <img src="{{ $banner->image_url }}" class="rounded" style="width:120px;height:75px;object-fit:cover;">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Banner Video</label>
                        @if ($banner->video)
                            <div class="mb-2">
                                <video src="{{ $banner->video_url }}" style="width:120px;height:75px;object-fit:cover;" class="rounded" controls></video>
                            </div>
                        @endif
                        <input type="file" name="video" accept="video/*" class="form-control @error('video') is-invalid @enderror">
                        <small class="text-muted">Leave empty to keep current video</small>
                        @error('video') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                    <a href="{{ route('dashboard.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection