<!-- 
    Luxury Details Header Component
    
    Usage:
    @include('components.luxury-details-header', [
        'title' => 'Item Name',
        'subtitle' => 'Additional info or location',
        'icon' => 'fas fa-icon-name',
        'badge' => ['text' => 'Active', 'class' => 'bg-success'],
        'editRoute' => route('items.edit', $item),
        'backRoute' => route('items.index')
    ])
-->

<div class="luxury-card mb-4">
    <div class="p-4 border-bottom" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1" style="color: #d4af37; font-weight: 600;">
                    <i class="{{ $icon ?? 'fas fa-info-circle' }}"></i> {{ $title }}
                </h3>
                @if(isset($subtitle))
                <p class="mb-0" style="color: #ffffff; opacity: 0.9;">
                    {{ $subtitle }}
                    @if(isset($badge))
                        <span class="badge {{ $badge['class'] ?? 'bg-secondary' }} ms-2">{{ $badge['text'] }}</span>
                    @endif
                </p>
                @endif
            </div>
            <div>
                @if(isset($editRoute))
                <a href="{{ $editRoute }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endif
                @if(isset($backRoute))
                <a href="{{ $backRoute }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

