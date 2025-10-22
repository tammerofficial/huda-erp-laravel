<!-- 
    Info Item Component
    
    Usage:
    @include('components.info-item', [
        'label' => 'Field Name',
        'value' => 'Field Value',
        'icon' => 'fas fa-icon' // optional
    ])
-->

<div class="info-item">
    @if(isset($icon))
    <label class="text-muted mb-1"><i class="{{ $icon }} me-1"></i> {{ $label }}</label>
    @else
    <label class="text-muted mb-1">{{ $label }}</label>
    @endif
    <p class="mb-0 fw-semibold">{{ $value ?? 'Not specified' }}</p>
</div>

<style>
.info-item {
    padding: 12px;
    border-radius: 8px;
    background-color: #fafafa;
    transition: all 0.3s ease;
}

.info-item:hover {
    background-color: #f5f5f5;
    transform: translateY(-2px);
}

.info-item label {
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item p {
    font-size: 1rem;
}
</style>

