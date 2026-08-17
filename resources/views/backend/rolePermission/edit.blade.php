@extends('backend.layout')

@section('backend_title', 'Edit Role')

@section('backend_content')

<div class="container-fluid p-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('dashboard.users.roles.index') }}" class="btn btn-light btn-sm border">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0">Edit Role — {{ $role->name }}</h4>
    </div>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dashboard.users.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-12">

                {{-- Role Name --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold">Role Name</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}"
                               class="form-control">
                    </div>
                </div>

                {{-- Permissions grouped by category --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold mb-3">Permissions</label>

                        @php
                            $grouped = $permissions->groupBy(function ($p) {
                                return ucfirst(explode('-', $p->name)[0]);
                            });
                        @endphp

                        @foreach ($grouped as $category => $items)
                            <div class="border rounded-3 p-3 mb-3 permission-group">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold small text-uppercase text-muted">{{ $category }}</span>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input select-all-toggle" type="checkbox"
                                               data-group="{{ $category }}">
                                        <label class="form-check-label small text-muted">Select all</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($items as $permission)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input perm-checkbox group-{{ $category }}"
                                                       type="checkbox" name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       id="perm-{{ $permission->id }}"
                                                       @checked(in_array($permission->name, $rolePermissions))>
                                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Update Role
                    </button>
                    <a href="{{ route('dashboard.users.roles.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection

@push('backend_js')
<script>
    document.querySelectorAll('.select-all-toggle').forEach(function (toggle) {
        // Page load hoবার shomoy already-checked gula check kore "select all" ta o auto-check kore dey
        const group = toggle.dataset.group;
        const checkboxes = document.querySelectorAll('.group-' + group);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        toggle.checked = allChecked && checkboxes.length > 0;

        toggle.addEventListener('change', function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = toggle.checked;
            });
        });
    });
</script>
@endpush
