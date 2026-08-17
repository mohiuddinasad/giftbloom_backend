@extends('backend.layout')

@section('backend_title', 'Add Category')
@section('backend_content')
<div class="container-fluid">
    <h4 class="mb-3">Add Category</h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
                @include('backend.products.categories._form')
            </form>
        </div>
    </div>
</div>
@endsection
