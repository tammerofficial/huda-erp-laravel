@extends('layouts.app')

@section('title', 'تفاصيل المادة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل المادة: {{ $material->name }}</h3>
                    <div>
                        <a href="{{ route('materials.edit', $material) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('materials.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($material->image_url)
                                <img src="{{ Storage::url($material->image_url) }}" alt="{{ $material->name }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                                    <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">اسم المادة:</label>
                                        <p class="form-control-plaintext">{{ $material->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">SKU:</label>
                                        <p class="form-control-plaintext">{{ $material->sku }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">الوحدة:</label>
                                        <p class="form-control-plaintext">{{ $material->unit }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">تكلفة الوحدة:</label>
                                        <p class="form-control-plaintext">{{ number_format($material->unit_cost, 2) }} د.ك</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">الفئة:</label>
                                        <p class="form-control-plaintext">{{ $material->category ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">المورد:</label>
                                        <p class="form-control-plaintext">{{ $material->supplier->name ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">اللون:</label>
                                        <p class="form-control-plaintext">{{ $material->color ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">الحجم:</label>
                                        <p class="form-control-plaintext">{{ $material->size ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">حد إعادة الطلب:</label>
                                        <p class="form-control-plaintext">{{ $material->reorder_level }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">الحد الأقصى للمخزون:</label>
                                        <p class="form-control-plaintext">{{ $material->max_stock ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">الحالة:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $material->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $material->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">تاريخ الإنشاء:</label>
                                        <p class="form-control-plaintext">{{ $material->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($material->description)
                            <div class="form-group">
                                <label class="font-weight-bold">الوصف:</label>
                                <p class="form-control-plaintext">{{ $material->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">المخزون</h4>
                </div>
                <div class="card-body">
                    @if($material->inventories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>المستودع</th>
                                        <th>الكمية</th>
                                        <th>حد إعادة الطلب</th>
                                        <th>الحد الأقصى</th>
                                        <th>آخر تحديث</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->inventories as $inventory)
                                    <tr>
                                        <td>{{ $inventory->warehouse->name }}</td>
                                        <td>
                                            <span class="badge {{ $inventory->quantity <= $inventory->reorder_level ? 'badge-warning' : 'badge-success' }}">
                                                {{ $inventory->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ $inventory->reorder_level }}</td>
                                        <td>{{ $inventory->max_level ?? 'غير محدد' }}</td>
                                        <td>{{ $inventory->last_updated ? $inventory->last_updated->format('Y-m-d H:i') : 'غير محدد' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">لا يوجد مخزون لهذه المادة</p>
                    @endif
                </div>
            </div>

            <!-- BOM Items Section -->
            @if($material->bomItems->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">استخدام المادة في قوائم المواد</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>الوحدة</th>
                                    <th>تكلفة الوحدة</th>
                                    <th>التكلفة الإجمالية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($material->bomItems as $bomItem)
                                <tr>
                                    <td>{{ $bomItem->billOfMaterial->product->name }}</td>
                                    <td>{{ $bomItem->quantity }}</td>
                                    <td>{{ $bomItem->unit }}</td>
                                    <td>{{ number_format($bomItem->unit_cost, 2) }} د.ك</td>
                                    <td>{{ number_format($bomItem->total_cost, 2) }} د.ك</td>
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
</div>
@endsection
