@extends('backend.layout')
@section('backend_title', 'Edit Category')
@section('backend_content')
@include('backend.products._styles')

<div class="cp-wrap">
    <div class="cp-header">
        <div>
            <div class="cp-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a> /
                <a href="{{ route('dashboard.categories.index') }}">Categories</a> / Edit
            </div>
            <h1>Edit Category</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="cp-alert cp-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        <div class="cp-card">
            @include('backend.products.categories._form')
        </div>
    </form>
</div>
@endsection
