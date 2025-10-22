@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📱 QR Scanner</h1>
        <div class="flex gap-4">
            <a href="{{ route('attendance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Attendance
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- QR Scanner -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Scan QR Code</h2>
            
            <div id="scanner-container" class="relative">
                <video id="video" width="100%" height="300" class="border rounded-lg"></video>
                <canvas id="canvas" class="hidden"></canvas>
                
                <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-64 h-64 border-4 border-blue-500 rounded-lg flex items-center justify-center">
                            <div class="text-blue-500 text-6xl">📱</div>
                        </div>
                        <p class="mt-4 text-gray-600">Position QR code within the frame</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button id="start-scanner" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mr-2">
                    Start Scanner
                </button>
                <button id="stop-scanner" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" disabled>
                    Stop Scanner
                </button>
            </div>
        </div>

        <!-- Employee Actions -->
        <div class="space-y-6">
            <!-- Employee Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Employee Information</h3>
                <div id="employee-info" class="text-center text-gray-500">
                    <p>Scan QR code to see employee information</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button id="check-in-btn" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700" disabled>
                        ✅ Check In
                    </button>
                    <button id="check-out-btn" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" disabled>
                        ❌ Check Out
                    </button>
                    <button id="start-production-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" disabled>
                        🏭 Start Production
                    </button>
                    <button id="complete-production-btn" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700" disabled>
                        ✅ Complete Production
                    </button>
                </div>
            </div>

            <!-- Production Stage Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Production Stage</h3>
                <div class="space-y-3">
                    <select id="production-stage-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                        <option value="">Select Production Stage</option>
                    </select>
                    <select id="product-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                        <option value="">Select Product</option>
                    </select>
                    <input type="number" id="pieces-completed" placeholder="Pieces Completed" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>
            </div>

            <!-- Status Messages -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Status</h3>
                <div id="status-messages" class="space-y-2">
                    <p class="text-gray-500">Ready to scan QR code</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
let currentEmployee = null;
let scanner = null;

// Initialize scanner
document.getElementById('start-scanner').addEventListener('click', function() {
    startScanner();
});

document.getElementById('stop-scanner').addEventListener('click', function() {
    stopScanner();
});

function startScanner() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const overlay = document.getElementById('scanner-overlay');
    
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'environment' 
        } 
    })
    .then(function(stream) {
        video.srcObject = stream;
        video.play();
        overlay.style.display = 'none';
        
        // Start QR code detection
        startQRDetection();
        
        document.getElementById('start-scanner').disabled = true;
        document.getElementById('stop-scanner').disabled = false;
    })
    .catch(function(err) {
        console.error('Error accessing camera:', err);
        addStatusMessage('Error accessing camera. Please check permissions.', 'error');
    });
}

function stopScanner() {
    const video = document.getElementById('video');
    const stream = video.srcObject;
    
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
    
    document.getElementById('start-scanner').disabled = false;
    document.getElementById('stop-scanner').disabled = true;
    document.getElementById('scanner-overlay').style.display = 'flex';
}

function startQRDetection() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    
    function detectQR() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            
            // Simple QR detection (in real implementation, use a proper QR library)
            // For now, we'll simulate with a button click
        }
        
        if (document.getElementById('stop-scanner').disabled === false) {
            requestAnimationFrame(detectQR);
        }
    }
    
    detectQR();
}

// Simulate QR code scanning (for demo purposes)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !document.getElementById('stop-scanner').disabled) {
        simulateQRScan();
    }
});

function simulateQRScan() {
    // Simulate scanning a QR code
    const mockEmployee = {
        id: 1,
        name: 'John Doe',
        position: 'Tailor',
        department: 'Production',
        qr_code: 'EMP-000001-abc123'
    };
    
    handleQRScan(mockEmployee.qr_code);
}

function handleQRScan(qrCode) {
    // Validate QR code
    fetch('/api/qr/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ code: qrCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentEmployee = data.employee;
            displayEmployeeInfo(data.employee);
            enableActions();
            addStatusMessage('QR code validated successfully!', 'success');
        } else {
            addStatusMessage('Invalid QR code. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error validating QR code:', error);
        addStatusMessage('Error validating QR code.', 'error');
    });
}

function displayEmployeeInfo(employee) {
    const employeeInfo = document.getElementById('employee-info');
    employeeInfo.innerHTML = `
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900">${employee.name}</div>
            <div class="text-sm text-gray-600">${employee.position}</div>
            <div class="text-sm text-gray-500">${employee.department}</div>
        </div>
    `;
}

function enableActions() {
    document.getElementById('check-in-btn').disabled = false;
    document.getElementById('check-out-btn').disabled = false;
    document.getElementById('start-production-btn').disabled = false;
    document.getElementById('complete-production-btn').disabled = false;
    document.getElementById('production-stage-select').disabled = false;
    document.getElementById('product-select').disabled = false;
    document.getElementById('pieces-completed').disabled = false;
    
    // Load production stages and products
    loadProductionStages();
    loadProducts();
}

function loadProductionStages() {
    // In real implementation, load from API
    const select = document.getElementById('production-stage-select');
    select.innerHTML = `
        <option value="">Select Production Stage</option>
        <option value="1">Cutting - Order #1001</option>
        <option value="2">Sewing - Order #1002</option>
        <option value="3">Embroidery - Order #1003</option>
        <option value="4">Ironing - Order #1004</option>
    `;
}

function loadProducts() {
    // In real implementation, load from API
    const select = document.getElementById('product-select');
    select.innerHTML = `
        <option value="">Select Product</option>
        <option value="1">Dress - DR001</option>
        <option value="2">Shirt - SH001</option>
        <option value="3">Pants - PA001</option>
    `;
}

// Action handlers
document.getElementById('check-in-btn').addEventListener('click', function() {
    if (!currentEmployee) return;
    
    fetch('/api/qr/check-in', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ employee_id: currentEmployee.id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addStatusMessage(`Checked in at ${data.check_in_time}`, 'success');
        } else {
            addStatusMessage('Error checking in', 'error');
        }
    });
});

document.getElementById('check-out-btn').addEventListener('click', function() {
    if (!currentEmployee) return;
    
    fetch('/api/qr/check-out', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ employee_id: currentEmployee.id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addStatusMessage(`Checked out at ${data.check_out_time}`, 'success');
        } else {
            addStatusMessage('Error checking out', 'error');
        }
    });
});

document.getElementById('start-production-btn').addEventListener('click', function() {
    if (!currentEmployee) return;
    
    const stageId = document.getElementById('production-stage-select').value;
    const productId = document.getElementById('product-select').value;
    
    if (!stageId || !productId) {
        addStatusMessage('Please select production stage and product', 'error');
        return;
    }
    
    fetch('/api/qr/start-stage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            employee_id: currentEmployee.id,
            production_stage_id: stageId,
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addStatusMessage(`Production started. Log ID: ${data.log_id}`, 'success');
        } else {
            addStatusMessage('Error starting production', 'error');
        }
    });
});

document.getElementById('complete-production-btn').addEventListener('click', function() {
    if (!currentEmployee) return;
    
    const pieces = document.getElementById('pieces-completed').value;
    if (!pieces || pieces < 1) {
        addStatusMessage('Please enter pieces completed', 'error');
        return;
    }
    
    // In real implementation, you would need to track the current log ID
    const logId = 1; // This should be tracked from the start production response
    
    fetch('/api/qr/complete-stage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            log_id: logId,
            pieces_completed: pieces
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addStatusMessage(`Production completed. Earnings: ${data.earnings} KWD`, 'success');
        } else {
            addStatusMessage('Error completing production', 'error');
        }
    });
});

function addStatusMessage(message, type) {
    const statusMessages = document.getElementById('status-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `p-2 rounded ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    messageDiv.textContent = message;
    statusMessages.appendChild(messageDiv);
    
    // Remove old messages
    const messages = statusMessages.querySelectorAll('div');
    if (messages.length > 5) {
        messages[0].remove();
    }
}

// Add CSRF token to meta tag if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '{{ csrf_token() }}';
    document.head.appendChild(meta);
}
</script>
@endsection