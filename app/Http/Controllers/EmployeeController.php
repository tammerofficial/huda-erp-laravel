<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        // Get all users who don't have an employee record yet
        $users = User::whereDoesntHave('employee')->get();
        return view('employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees',
            'employee_id' => 'required|string|unique:employees',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'employment_status' => 'required|in:active,inactive,terminated',
            'skills' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'employment_status' => 'required|in:active,inactive,terminated',
            'skills' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }

    public function generateQR(Employee $employee)
    {
        $employee->update(['qr_code' => 'QR' . $employee->id . time()]);
        return redirect()->back()->with('success', 'QR code generated successfully');
    }
}
