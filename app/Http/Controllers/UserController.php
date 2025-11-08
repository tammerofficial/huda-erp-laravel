<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('employee')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,manager,accountant,production_staff,purchasing_agent',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(),
        ]);

        // Assign role using Spatie Permission
        $user->assignRole($request->role);

        // Create employee record if role is production_staff
        if ($request->role === 'production_staff') {
            Employee::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad(Employee::count() + 1, 3, '0', STR_PAD_LEFT),
                'phone' => $request->phone,
                'address' => $request->address,
                'position' => $request->position ?? 'Production Staff',
                'department' => $request->department ?? 'Production',
                'qr_code' => 'QR' . $user->id . time(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('employee');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('employee');
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,manager,accountant,production_staff,purchasing_agent',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        
        // Update role using Spatie Permission
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'لا يمكن حذف حسابك الخاص');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()->with('success', "تم $status المستخدم بنجاح");
    }
}
