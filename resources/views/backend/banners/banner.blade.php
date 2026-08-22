@extends('backend.layout')
@section('backend_title', 'Banner List')
@section('backend_content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Banners &amp; Videos</h5>
            <a href="{{ route('dashboard.banners.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add New
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Video</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $banner->title ?? '—' }}</td>
                                <td>
                                    @if ($banner->image)
                                        <img src="{{ $banner->image_url }}" alt="banner" class="rounded" style="width:70px;height:45px;object-fit:cover;">
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($banner->video)
                                        <video src="{{ $banner->video_url }}" style="width:70px;height:45px;object-fit:cover;" class="rounded" muted></video>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">No Video</span>
                                    @endif
                                </td>
                                <td>{{ $banner->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('dashboard.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('dashboard.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this banner?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
</div>
@endsection