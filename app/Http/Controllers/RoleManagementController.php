<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission as SpatiePermission;

class RoleManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions']);

        // Smart Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('last_login')) {
            $days = $request->last_login;
            $query->where('last_login_at', '>=', now()->subDays($days));
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Only show management team (exclude staff and production_staff)
        $query->whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['staff', 'production_staff']);
        });

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $roles = SpatieRole::whereNotIn('name', ['staff', 'production_staff'])->get();
        $permissions = SpatiePermission::all();
        
        return view('roles.index', compact('users', 'roles', 'permissions'));
    }

    public function create()
    {
        $roles = SpatieRole::whereNotIn('name', ['staff', 'production_staff'])->get();
        $permissions = SpatiePermission::all();
        return view('roles.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            // Assign roles
            $user->assignRole($request->roles);

            // Assign permissions
            if ($request->permissions) {
                $user->givePermissionTo($request->permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'User created successfully with assigned roles and permissions.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions']);
        return view('roles.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = SpatieRole::whereNotIn('name', ['staff', 'production_staff'])->get();
        $permissions = SpatiePermission::all();
        $user->load(['roles', 'permissions']);
        
        return view('roles.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Sync roles
            $user->syncRoles($request->roles);

            // Sync permissions
            if ($request->permissions) {
                $user->syncPermissions($request->permissions);
            } else {
                $user->permissions()->detach();
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // Prevent deletion of super admin
        if ($user->hasRole('super_admin')) {
            return back()->with('error', 'Cannot delete super admin user.');
        }

        $user->delete();
        return redirect()->route('roles.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password123')]);
        return back()->with('success', 'Password reset to default: password123');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_role',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required_if:action,assign_role|exists:roles,name',
        ]);

        $users = User::whereIn('id', $request->user_ids);

        switch ($request->action) {
            case 'activate':
                $users->update(['is_active' => true]);
                $message = 'Users activated successfully.';
                break;
            case 'deactivate':
                $users->update(['is_active' => false]);
                $message = 'Users deactivated successfully.';
                break;
            case 'delete':
                $users->whereDoesntHave('roles', function($q) {
                    $q->where('name', 'super_admin');
                })->delete();
                $message = 'Users deleted successfully.';
                break;
            case 'assign_role':
                $users->get()->each(function($user) use ($request) {
                    $user->assignRole($request->role);
                });
                $message = 'Role assigned to users successfully.';
                break;
        }

        return back()->with('success', $message);
    }
}
