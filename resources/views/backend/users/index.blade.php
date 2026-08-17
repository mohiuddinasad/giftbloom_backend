@extends('backend.layout')

@section('backend_title', 'Users')

@section('backend_content')

<div class="container-fluid p-4">



    {{-- ================= HEADER ================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0">Users</h4>
            <p class="text-muted small mb-0">Manage users and their roles</p>
        </div>

        @canany(['role-create', 'role-edit', 'role-delete'])
            <a href="{{ route('dashboard.users.roles.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-shield-lock"></i> Manage Roles
            </a>
        @endcanany
    </div>

    {{-- ================= SEARCH BAR ================= --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="position-relative">
                <input
                    type="text"
                    id="user-search-input"
                    class="form-control ps-5"
                    placeholder="Name or email to search..."
                    autocomplete="off"
                >
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <span id="search-spinner" class="spinner-border spinner-border-sm text-primary position-absolute top-50 end-0 translate-middle-y me-3 d-none"></span>
            </div>
        </div>
    </div>

    {{-- ================= USERS TABLE ================= --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Assign Role</th>
                        <th class="text-center pe-3">Action</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <span class="spinner-border spinner-border-sm me-2"></span> Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('backend_css')
<style>
    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e7edff;
        color: #3b5bdb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
</style>
@endpush

@push('backend_js')
<script>
(function () {
    // ================= SERVER DATA JS E PASS KORLAM =================
    const SEARCH_URL          = "{{ route('dashboard.users.search') }}";
    const ASSIGN_URL_TEMPLATE = "{{ route('dashboard.users.assign-role', ':id') }}";
    const DESTROY_URL_TEMPLATE = "{{ route('dashboard.users.destroy', ':id') }}";
    const CSRF_TOKEN           = "{{ csrf_token() }}";

    const CAN_ASSIGN_ROLE   = @json(auth()->user()->can('role-assign'));
    const CAN_DELETE_USER   = @json(auth()->user()->can('user-delete'));
    const IS_SUPER_ADMIN    = @json(auth()->user()->hasRole('Super Admin'));
    const CURRENT_USER_ID   = {{ auth()->id() }};
    const ROLES = @json($roles->map(fn ($r) => ['name' => $r->name]));

    const tbody   = document.getElementById('user-table-body');
    const input   = document.getElementById('user-search-input');
    const spinner = document.getElementById('search-spinner');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.innerText = str ?? '';
        return div.innerHTML;
    }

    function buildRoleOptions(user) {
        return ROLES
            .filter(r => r.name !== 'Super Admin' || IS_SUPER_ADMIN)
            .map(r => {
                const selected = user.roles.includes(r.name) ? 'selected' : '';
                return `<option value="${escapeHtml(r.name)}" ${selected}>${escapeHtml(r.name)}</option>`;
            })
            .join('');
    }

    function buildRow(user) {
        const rolesBadges = user.roles.length
            ? user.roles.map(r => `<span class="badge bg-primary text-primary-secondary rounded-pill me-1">${escapeHtml(r)}</span>`).join('')
            : '<span class="text-muted small">No role</span>';

        let assignCell;
        if (user.is_super_admin) {
            assignCell = '<span class="text-muted small">Super Admin (fixed)</span>';
        } else if (CAN_ASSIGN_ROLE) {
            const assignUrl = ASSIGN_URL_TEMPLATE.replace(':id', user.id);
            assignCell = `
                <form action="${assignUrl}" method="POST" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <input type="hidden" name="_method" value="PUT">
                    <select name="role" class="form-select form-select-sm" style="min-width: 140px;">
                        ${buildRoleOptions(user)}
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                        Assign
                    </button>
                </form>`;
        } else {
            assignCell = '<span class="text-muted small">No Access</span>';
        }

        let actionCell;
        if (!CAN_DELETE_USER) {
            actionCell = '<span class="text-muted small">No Access</span>';
        } else if (user.is_super_admin || user.id === CURRENT_USER_ID) {
            actionCell = '<span class="text-muted small">—</span>';
        } else {
            const destroyUrl = DESTROY_URL_TEMPLATE.replace(':id', user.id);
            actionCell = `
                <form action="${destroyUrl}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>`;
        }

        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle">${escapeHtml(user.name.charAt(0).toUpperCase())}</div>
                        <p class="fw-semibold m-0">${escapeHtml(user.name)}</p>
                    </div>
                </td>
                <td class="text-muted">${escapeHtml(user.email)}</td>
                <td>${rolesBadges}</td>
                <td>${assignCell}</td>
                <td class="text-center">${actionCell}</td>
            </tr>`;
    }

    function renderUsers(users) {
        if (!users.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                </tr>`;
            return;
        }
        tbody.innerHTML = users.map(buildRow).join('');
    }

    function loadUsers(query) {
        spinner.classList.remove('d-none');

        fetch(`${SEARCH_URL}?query=${encodeURIComponent(query)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => renderUsers(data.users))
            .catch(() => {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger py-4">Something went wrong, please try again.</td>
                    </tr>`;
            })
            .finally(() => spinner.classList.add('d-none'));
    }

    loadUsers('');

    // Search input e type korলে debounce diye AJAX call hoবে
    let debounceTimer;
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value;
        debounceTimer = setTimeout(() => loadUsers(query), 400);
    });
})();
</script>
@endpush
