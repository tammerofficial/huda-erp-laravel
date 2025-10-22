@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="qrScanner()">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">🔍 QR Code Scanner</h1>
        
        <!-- Video Stream -->
        <div class="mb-6 text-center">
            <video id="qr-video" class="w-full max-w-md mx-auto rounded-lg border-2 border-gray-300"></video>
        </div>
        
        <!-- Status -->
        <div x-show="scannedEmployee" class="bg-green-100 p-4 rounded-lg mb-4 text-center">
            <p class="text-lg font-semibold">Employee: <span x-text="scannedEmployee"></span></p>
            <p class="text-sm text-gray-600">Position: <span x-text="scannedPosition"></span></p>
        </div>
        
        <!-- Error Message -->
        <div x-show="errorMessage" class="bg-red-100 p-4 rounded-lg mb-4 text-center">
            <p class="text-lg text-red-600" x-text="errorMessage"></p>
        </div>
        
        <!-- Actions -->
        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
            <button @click="checkIn()" 
                    :disabled="!scannedEmployee" 
                    class="bg-green-600 text-white px-6 py-3 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                Check In
            </button>
            <button @click="checkOut()" 
                    :disabled="!scannedEmployee" 
                    class="bg-red-600 text-white px-6 py-3 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                Check Out
            </button>
        </div>
        
        <!-- Production Actions -->
        <div class="mt-6 grid grid-cols-2 gap-4 max-w-md mx-auto">
            <button @click="startProduction()" 
                    :disabled="!scannedEmployee" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                Start Production
            </button>
            <button @click="completeProduction()" 
                    :disabled="!scannedEmployee" 
                    class="bg-purple-600 text-white px-6 py-3 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                Complete Production
            </button>
        </div>
        
        <!-- Success Message -->
        <div x-show="successMessage" class="bg-green-100 p-4 rounded-lg mt-4 text-center">
            <p class="text-lg text-green-600" x-text="successMessage"></p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function qrScanner() {
    return {
        scannedEmployee: null,
        scannedPosition: null,
        employeeId: null,
        errorMessage: null,
        successMessage: null,
        scanner: null,
        
        init() {
            this.startScanner();
        },
        
        startScanner() {
            try {
                this.scanner = new Html5Qrcode("qr-video");
                this.scanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    (decodedText) => {
                        this.validateQR(decodedText);
                    },
                    (error) => {
                        // Ignore scanning errors
                    }
                );
            } catch (error) {
                this.errorMessage = "Camera access denied. Please allow camera access.";
            }
        },
        
        validateQR(code) {
            fetch('/api/qr/validate', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.scannedEmployee = data.employee.name;
                    this.scannedPosition = data.employee.position;
                    this.employeeId = data.employee.id;
                    this.errorMessage = null;
                } else {
                    this.errorMessage = "Invalid QR code";
                    this.scannedEmployee = null;
                    this.employeeId = null;
                }
            })
            .catch(error => {
                this.errorMessage = "Error validating QR code";
                this.scannedEmployee = null;
                this.employeeId = null;
            });
        },
        
        checkIn() {
            if (!this.employeeId) return;
            
            fetch('/api/qr/check-in', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ employee_id: this.employeeId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.successMessage = data.message + ' at ' + data.check_in_time;
                    this.errorMessage = null;
                } else {
                    this.errorMessage = data.error || 'Check-in failed';
                }
            })
            .catch(error => {
                this.errorMessage = 'Check-in failed';
            });
        },
        
        checkOut() {
            if (!this.employeeId) return;
            
            fetch('/api/qr/check-out', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ employee_id: this.employeeId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.successMessage = data.message + ' at ' + data.check_out_time;
                    this.errorMessage = null;
                } else {
                    this.errorMessage = data.error || 'Check-out failed';
                }
            })
            .catch(error => {
                this.errorMessage = 'Check-out failed';
            });
        },
        
        startProduction() {
            if (!this.employeeId) return;
            
            // For now, just show a message - in real implementation, you'd need to select stage and product
            this.successMessage = 'Production start feature coming soon';
        },
        
        completeProduction() {
            if (!this.employeeId) return;
            
            // For now, just show a message - in real implementation, you'd need to select log and pieces
            this.successMessage = 'Production complete feature coming soon';
        }
    }
}
</script>
@endsection
