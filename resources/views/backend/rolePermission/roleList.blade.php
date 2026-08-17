@extends('backend.layout')

@section('backend_title', 'Roles')

@section('backend_content')

    <div class="container-fluid p-4">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif 

        {{-- ================= HEADER ================= --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="mb-0">Roles</h4>
                <p class="text-muted small mb-0">Create and manage user roles</p>
            </div>

            @can('role-create')
                <a href="{{ route('dashboard.users.roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> New Role
                </a>
            @endcan
        </div>

        {{-- ================= ROLES TABLE ================= --}}
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Role Name</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th class="text-center pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-semibold">{{ $role->name }}</span>
                                    @if (in_array($role->name, ['Super Admin', 'Customer']))
                                        <span class="badge bg-warning text-warning-emphasis rounded-pill ms-2">
                                            Fixed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                        {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                    </span>
                                </td>
                                <td style="max-width: 350px;">
                                    @forelse ($role->permissions as $permission)
                                        <span class="badge bg-primary text-primary-secondary rounded-pill me-1 mb-1">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">No permission assigned</span>
                                    @endforelse
                                </td>
                                <td class="text-center pe-3">
                                    @if (!in_array($role->name, ['Super Admin', 'Customer']))
                                        @can('role-edit')
                                            <a href="{{ route('dashboard.users.roles.edit', $role->id) }}"
                                                class="btn btn-sm btn-outline-primary me-1">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('role-delete')
                                            <form action="{{ route('dashboard.users.roles.destroy', $role->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        @cannot('role-edit')
                                            @cannot('role-delete')
                                                <span class="text-muted small">No Access</span>
                                            @endcannot
                                        @endcannot
                                    @else
                                        <span class="text-muted small">No Action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
