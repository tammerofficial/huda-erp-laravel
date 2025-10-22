<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\AttendanceRecord;
use App\Models\ProductionLog;
use App\Models\ProductionStage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRController extends Controller
{
    /**
     * Validate QR code
     */
    public function validate(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string'
        ]);

        $employee = Employee::where('qr_code', $validated['code'])
            ->where('qr_enabled', true)
            ->where('employment_status', 'active')
            ->first();

        if (!$employee) {
            return response()->json(['error' => 'Invalid QR code'], 404);
        }

        return response()->json([
            'success' => true,
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->user->name,
                'position' => $employee->position,
                'department' => $employee->department
            ]
        ]);
    }

    /**
     * Check in via QR
     */
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date'
        ]);

        $date = $validated['date'] ?? now()->format('Y-m-d');
        $checkInTime = now()->format('H:i');

        $record = AttendanceRecord::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $date,
            ],
            [
                'check_in' => $checkInTime,
                'status' => 'present',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'check_in_time' => $checkInTime
        ]);
    }

    /**
     * Check out via QR
     */
    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date'
        ]);

        $date = $validated['date'] ?? now()->format('Y-m-d');
        $checkOutTime = now()->format('H:i');

        $record = AttendanceRecord::where('employee_id', $validated['employee_id'])
            ->where('date', $date)
            ->first();

        if (!$record) {
            return response()->json(['error' => 'لم يتم العثور على سجل حضور'], 404);
        }

        $record->update(['check_out' => $checkOutTime]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح',
            'check_out_time' => $checkOutTime
        ]);
    }

    /**
     * Start production stage via QR
     */
    public function startStage(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'production_stage_id' => 'required|exists:production_stages,id',
            'product_id' => 'required|exists:products,id',
            'rate_per_piece' => 'nullable|numeric|min:0'
        ]);

        $log = ProductionLog::create([
            'employee_id' => $validated['employee_id'],
            'production_stage_id' => $validated['production_stage_id'],
            'product_id' => $validated['product_id'],
            'rate_per_piece' => $validated['rate_per_piece'],
            'start_time' => now(),
            'quality_status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم بدء المرحلة بنجاح',
            'log_id' => $log->id
        ]);
    }

    /**
     * Complete production stage via QR
     */
    public function completeStage(Request $request)
    {
        $validated = $request->validate([
            'log_id' => 'required|exists:production_logs,id',
            'pieces_completed' => 'required|integer|min:1'
        ]);

        $log = ProductionLog::findOrFail($validated['log_id']);
        $log->update([
            'pieces_completed' => $validated['pieces_completed'],
            'end_time' => now(),
            'quality_status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إكمال المرحلة بنجاح',
            'earnings' => $log->earnings
        ]);
    }

    /**
     * Generate QR code for employee
     */
    public function generateQR(Employee $employee)
    {
        if (!$employee->qr_code) {
            $code = 'EMP-' . str_pad($employee->id, 6, '0', STR_PAD_LEFT) . '-' . uniqid();
            $employee->qr_code = $code;
            $employee->save();
        }

        // Generate QR image
        $qrImage = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($employee->qr_code);

        // Save image
        $path = 'qr-codes/employees/' . $employee->id . '.png';
        Storage::disk('public')->put($path, $qrImage);
        
        $employee->qr_image_path = $path;
        $employee->save();

        return response()->json([
            'success' => true,
            'qr_code' => $employee->qr_code,
            'qr_image_url' => Storage::url($path)
        ]);
    }
}
