@extends('layouts.app')

@section('title', 'Stage Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Stage: {{ $stage->stage_name }}</h3>
                    <div>
                        <a href="{{ route('production.show', $stage->productionOrder) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Production Order
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Stage Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Stage Name:</strong></td>
                                    <td>{{ $stage->stage_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Production Order:</strong></td>
                                    <td>{{ $stage->productionOrder->production_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Product:</strong></td>
                                    <td>{{ $stage->productionOrder->product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $stage->status == 'completed' ? 'success' : ($stage->status == 'in-progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($stage->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sequence Order:</strong></td>
                                    <td>{{ $stage->sequence_order }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Timing Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Start Time:</strong></td>
                                    <td>{{ $stage->start_time ? $stage->start_time->format('Y-m-d H:i') : 'Not Started' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Time:</strong></td>
                                    <td>{{ $stage->end_time ? $stage->end_time->format('Y-m-d H:i') : 'Not Completed' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>{{ $stage->duration_minutes ?? 'Not Calculated' }} minutes</td>
                                </tr>
                                <tr>
                                    <td><strong>Employee:</strong></td>
                                    <td>{{ $stage->employee ? $stage->employee->user->name : 'Not Assigned' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($stage->notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <p>{{ $stage->notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($stage->quality_checks)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Quality Checks</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Check Item</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stage->quality_checks as $check)
                                        <tr>
                                            <td>{{ $check['item'] ?? 'Unknown' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $check['status'] == 'pass' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($check['status'] ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td>{{ $check['notes'] ?? 'No notes' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stage Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Stage Actions</h4>
                </div>
                <div class="card-body">
                    @if($stage->status == 'pending')
                        <form action="{{ route('production.start-stage', $stage) }}" method="POST" class="d-inline">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Assign Employee</label>
                                        <select name="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-play"></i> Start Stage
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @elseif($stage->status == 'in-progress')
                        <form action="{{ route('production.complete-stage', $stage) }}" method="POST" class="d-inline" onsubmit="return confirm('Complete this stage?')">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quality_checks">Quality Checks (JSON)</label>
                                        <textarea name="quality_checks" class="form-control" rows="3" placeholder='[{"item": "Check 1", "status": "pass", "notes": "Good"}]'></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stage_notes">Stage Notes</label>
                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Complete Stage
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-muted">This stage is {{ $stage->status }}. No actions available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
