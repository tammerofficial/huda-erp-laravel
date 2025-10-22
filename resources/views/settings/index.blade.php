@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Settings</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- General Settings -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5>General Settings</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_email">Company Email</label>
                                    <input type="email" class="form-control @error('company_email') is-invalid @enderror" id="company_email" name="company_email" value="{{ old('company_email', $settings['company_email'] ?? '') }}">
                                    @error('company_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_phone">Company Phone</label>
                                    <input type="text" class="form-control @error('company_phone') is-invalid @enderror" id="company_phone" name="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}">
                                    @error('company_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address">Company Address</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror" id="company_address" name="company_address" rows="2">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Settings -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Business Settings</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency">
                                        <option value="KWD" {{ old('currency', $settings['currency'] ?? 'KWD') == 'KWD' ? 'selected' : '' }}>KWD - Kuwaiti Dinar</option>
                                        <option value="USD" {{ old('currency', $settings['currency'] ?? 'KWD') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ old('currency', $settings['currency'] ?? 'KWD') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="SAR" {{ old('currency', $settings['currency'] ?? 'KWD') == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_rate">Tax Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate'] ?? 0) }}">
                                    @error('tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_payment_terms">Default Payment Terms</label>
                                    <input type="text" class="form-control @error('default_payment_terms') is-invalid @enderror" id="default_payment_terms" name="default_payment_terms" value="{{ old('default_payment_terms', $settings['default_payment_terms'] ?? '') }}">
                                    @error('default_payment_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_credit_limit">Default Credit Limit</label>
                                    <input type="number" step="0.01" class="form-control @error('default_credit_limit') is-invalid @enderror" id="default_credit_limit" name="default_credit_limit" value="{{ old('default_credit_limit', $settings['default_credit_limit'] ?? 0) }}">
                                    @error('default_credit_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Settings -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Inventory Settings</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="low_stock_threshold">Low Stock Threshold</label>
                                    <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? 10) }}">
                                    @error('low_stock_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="auto_reorder">Auto Reorder</label>
                                    <select class="form-control @error('auto_reorder') is-invalid @enderror" id="auto_reorder" name="auto_reorder">
                                        <option value="0" {{ old('auto_reorder', $settings['auto_reorder'] ?? 0) == 0 ? 'selected' : '' }}>Disabled</option>
                                        <option value="1" {{ old('auto_reorder', $settings['auto_reorder'] ?? 0) == 1 ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                    @error('auto_reorder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Production Settings -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Production Settings</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_production_priority">Default Production Priority</label>
                                    <select class="form-control @error('default_production_priority') is-invalid @enderror" id="default_production_priority" name="default_production_priority">
                                        <option value="low" {{ old('default_production_priority', $settings['default_production_priority'] ?? 'normal') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('default_production_priority', $settings['default_production_priority'] ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('default_production_priority', $settings['default_production_priority'] ?? 'normal') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('default_production_priority', $settings['default_production_priority'] ?? 'normal') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('default_production_priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quality_check_required">Quality Check Required</label>
                                    <select class="form-control @error('quality_check_required') is-invalid @enderror" id="quality_check_required" name="quality_check_required">
                                        <option value="0" {{ old('quality_check_required', $settings['quality_check_required'] ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('quality_check_required', $settings['quality_check_required'] ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('quality_check_required')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email Settings -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Email Settings</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_host">SMTP Host</label>
                                    <input type="text" class="form-control @error('smtp_host') is-invalid @enderror" id="smtp_host" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}">
                                    @error('smtp_host')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_port">SMTP Port</label>
                                    <input type="number" class="form-control @error('smtp_port') is-invalid @enderror" id="smtp_port" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? 587) }}">
                                    @error('smtp_port')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_username">SMTP Username</label>
                                    <input type="text" class="form-control @error('smtp_username') is-invalid @enderror" id="smtp_username" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
                                    @error('smtp_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_password">SMTP Password</label>
                                    <input type="password" class="form-control @error('smtp_password') is-invalid @enderror" id="smtp_password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}">
                                    @error('smtp_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
