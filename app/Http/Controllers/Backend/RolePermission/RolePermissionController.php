<?php

namespace App\Http\Controllers\Backend\RolePermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // Ei role gula "fixed" - UI theke edit/delete kora jabe na
    protected array $fixedRoles = ['Super Admin', 'Customer'];

    // ===================================
    // USER LIST (dashboard/users)
    // ===================================
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::whereNotIn('name', ['Customer'])->get(); // dropdown-e Customer dekhabo na

        return view('backend.users.index', compact('users', 'roles'));
    }

    // ===================================
    // ROLE LIST (dashboard/users/roles)
    // ===================================
    public function roleIndex()
    {
        $roles = Role::withCount('users')->get();

        return view('backend.rolePermission.roleList', compact('roles'));
    }

    // ===================================
    // ROLE CREATE
    // ===================================
    public function createRole()
    {
        $permissions = Permission::all();

        return view('backend.rolePermission.create', compact('permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        // Fixed role naam diye keu notun role banate parbe na
        if (in_array($request->name, $this->fixedRoles)) {
            abort(403, 'Ei naam use kora jabe na.');
        }

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('dashboard.users.roles.index')->with('success', 'Role created successfully.');
    }

    // ===================================
    // ROLE EDIT (permission update)
    // ===================================
    public function editRole(Role $role)
    {
        if (in_array($role->name, $this->fixedRoles)) {
            abort(403, 'Ei role edit kora jabe na.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('backend.rolePermission.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updateRole(Request $request, Role $role)
    {
        if (in_array($role->name, $this->fixedRoles)) {
            return back()->with('error', 'You cannot edit this role.');
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('dashboard.users.roles.index')->with('success', 'Role updated successfully.');
    }

    // ===================================
    // ROLE DELETE
    // ===================================
    public function destroyRole(Role $role)
    {
        if (in_array($role->name, $this->fixedRoles)) {
            return back()->with('error', 'You cannot delete this role.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'You cannot delete this role because it is assigned to users.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    // ===================================
    // USER-KE ROLE ASSIGN
    // ===================================
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Nijer role nije change korte parbe na (Super Admin chara)
        if (auth()->id() === $user->id && ! auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'You cannot change your own role.');
        }

        // Shudhu Super Admin e onno kau ke "Super Admin" banate parbe
        if ($request->role === 'Super Admin' && ! auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Only Super Admin can assign the Super Admin role.');
        }

        // Target user already Super Admin hole, ar Super Admin chara keu change korte parbe na
        if ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'You cannot change the role of a Super Admin.');
        }

        // Last remaining Super Admin ke onno role deya jabe na
        if ($user->hasRole('Super Admin') && $request->role !== 'Super Admin') {
            $superAdminCount = User::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'The last Super Admin cannot have their role changed.');
            }
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role assigned successfully.');
    }

    // ===================================
    // USER DELETE
    // ===================================
    public function destroyUser(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'You cannot delete a Super Admin account.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User delete successful.');
    }

    public function searchUsers(Request $request)
    {
        $query = trim($request->get('query', ''));

        $users = User::with('roles')
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->get();

        $authUser = auth()->user();

        $data = $users->map(function ($user) use ($authUser) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'is_super_admin' => $user->hasRole('Super Admin'),
                'is_self' => $authUser->id === $user->id,
            ];
        });

        return response()->json(['users' => $data]);
    }
}
