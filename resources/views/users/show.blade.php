@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User: {{ $user->name }}</h3>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>User Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $user->phone ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $user->address ?? 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Activity Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated At:</strong></td>
                                    <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $user->createdBy ? $user->createdBy->name : 'System' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->employee)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Employee Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Employee ID:</strong></td>
                                    <td>{{ $user->employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Position:</strong></td>
                                    <td>{{ $user->employee->position }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td>{{ $user->employee->department }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Salary:</strong></td>
                                    <td>{{ $user->employee->salary ? number_format($user->employee->salary, 2) . ' KWD' : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hire Date:</strong></td>
                                    <td>{{ $user->employee->hire_date ? $user->employee->hire_date->format('Y-m-d') : 'Not Set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Employment Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $user->employee->employment_status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->employee->employment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
