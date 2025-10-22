<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Services\QRCodeService;
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
            // New fields
            'nationality' => 'nullable|string|max:100',
            'civil_id' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:20',
            'passport_expiry' => 'nullable|date',
            'blood_type' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'probation_end_date' => 'nullable|date',
            'work_schedule' => 'nullable|string|max:100',
            'vacation_days_entitled' => 'nullable|integer|min:0',
            'vacation_days_used' => 'nullable|integer|min:0',
            'sick_days_used' => 'nullable|integer|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_card_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_card_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visa_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contract_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'medical_certificate' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
            'other_documents' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->all();
        
        // Handle file uploads
        $fileFields = ['profile_photo', 'id_card_front', 'id_card_back', 'passport_photo', 'visa_photo', 'contract_document', 'medical_certificate', 'other_documents'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('employee_documents', $filename, 'public');
                $data[$field] = $path;
            }
        }

        $employee = Employee::create($data);
        
        // Create birthday event if birth_date is provided
        if ($employee->birth_date) {
            $employee->events()->create([
                'title' => $employee->user->name . "'s Birthday",
                'description' => 'Employee Birthday',
                'event_date' => $employee->birth_date,
                'event_type' => 'birthday',
                'color' => '#ff6b6b',
                'is_recurring' => true,
                'recurring_type' => 'yearly',
                'created_by' => auth()->id(),
            ]);
        }

        // Create anniversary event if hire_date is provided
        if ($employee->hire_date) {
            $employee->events()->create([
                'title' => $employee->user->name . "'s Work Anniversary",
                'description' => 'Work Anniversary',
                'event_date' => $employee->hire_date,
                'event_type' => 'anniversary',
                'color' => '#4ecdc4',
                'is_recurring' => true,
                'recurring_type' => 'yearly',
                'created_by' => auth()->id(),
            ]);
        }

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
        try {
            $qrService = app(QRCodeService::class);
            $qrService->generateForEmployee($employee);
            
            return redirect()->back()->with('success', 'QR code generated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate QR code: ' . $e->getMessage());
        }
    }

    public function generateAllQR()
    {
        try {
            $qrService = app(QRCodeService::class);
            $results = $qrService->generateForAllEmployees();
            
            $successCount = collect($results)->where('status', 'success')->count();
            $errorCount = collect($results)->where('status', 'error')->count();
            
            return redirect()->back()->with('success', 
                "QR codes generated: {$successCount} success, {$errorCount} errors");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate QR codes: ' . $e->getMessage());
        }
    }

    public function toggleQR(Employee $employee)
    {
        $employee->update(['qr_enabled' => !$employee->qr_enabled]);
        
        $status = $employee->qr_enabled ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "QR code {$status} successfully");
    }
}
