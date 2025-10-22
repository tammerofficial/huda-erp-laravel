<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for employee
     */
    public function generateForEmployee(Employee $employee)
    {
        // Generate unique code if not exists
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

        return $employee;
    }

    /**
     * Validate QR code
     */
    public function validateQRCode($code)
    {
        return Employee::where('qr_code', $code)
            ->where('qr_enabled', true)
            ->where('employment_status', 'active')
            ->with('user')
            ->first();
    }

    /**
     * Generate QR codes for all employees
     */
    public function generateForAllEmployees()
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $results = [];

        foreach ($employees as $employee) {
            try {
                $this->generateForEmployee($employee);
                $results[] = [
                    'employee' => $employee,
                    'status' => 'success',
                    'message' => 'QR code generated successfully'
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'employee' => $employee,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Get QR code image URL
     */
    public function getQRImageUrl(Employee $employee)
    {
        if (!$employee->qr_image_path) {
            $this->generateForEmployee($employee);
        }

        return Storage::url($employee->qr_image_path);
    }

    /**
     * Disable QR code for employee
     */
    public function disableQRCode(Employee $employee)
    {
        $employee->update(['qr_enabled' => false]);
        return $employee;
    }

    /**
     * Enable QR code for employee
     */
    public function enableQRCode(Employee $employee)
    {
        $employee->update(['qr_enabled' => true]);
        return $employee;
    }
}
