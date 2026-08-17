@extends('backend.layout')

@section('backend_title', 'Edit Category')

@section('backend_content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Category</h4>
    <div class="card">
        <div class="card-body">
            {{-- $category was resolved from the {category:slug} route param --}}
            <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @include('backend.products.categories._form')
            </form>
        </div>
    </div>
</div>
@endsection
