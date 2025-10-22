@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">🔍 تفاصيل فحص الجودة #{{ $qualityCheck->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('quality-checks.edit', $qualityCheck) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    تعديل
                </a>
                <a href="{{ route('quality-checks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    العودة للقائمة
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">معلومات الفحص</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">رقم الفحص</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $qualityCheck->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">المنتج</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $qualityCheck->product->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">طلب الإنتاج</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <a href="{{ route('productions.show', $qualityCheck->productionOrder) }}" class="text-blue-600 hover:text-blue-800">
                                    #{{ $qualityCheck->productionOrder->id }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">المفتش</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $qualityCheck->inspector->user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">تاريخ الفحص</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $qualityCheck->inspection_date->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">الكمية المفحوصة</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $qualityCheck->items_checked }} قطعة</p>
                        </div>
                    </div>
                </div>

                <!-- Quality Results -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">نتائج الفحص</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $qualityCheck->items_passed }}</div>
                            <div class="text-sm text-gray-600">الكمية المنجزة</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">{{ $qualityCheck->items_failed }}</div>
                            <div class="text-sm text-gray-600">الكمية المرفوضة</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">
                                {{ $qualityCheck->items_checked > 0 ? round(($qualityCheck->items_passed / $qualityCheck->items_checked) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-600">معدل النجاح</div>
                        </div>
                    </div>
                </div>

                <!-- Defects -->
                @if($qualityCheck->defects && count($qualityCheck->defects) > 0)
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">العيوب المكتشفة</h2>
                    
                    <div class="space-y-2">
                        @foreach($qualityCheck->defects as $defect)
                        <div class="flex items-center space-x-2">
                            <span class="text-red-600">❌</span>
                            <span class="text-gray-900">{{ $defect }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($qualityCheck->notes)
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">ملاحظات</h2>
                    <p class="text-gray-900">{{ $qualityCheck->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Status Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">حالة الفحص</h2>
                    
                    <div class="text-center">
                        @if($qualityCheck->status === 'passed')
                        <div class="text-6xl text-green-600 mb-4">✅</div>
                        <div class="text-2xl font-bold text-green-600 mb-2">تمت الموافقة</div>
                        <div class="text-sm text-gray-600">المنتج جاهز للتسليم</div>
                        @else
                        <div class="text-6xl text-red-600 mb-4">❌</div>
                        <div class="text-2xl font-bold text-red-600 mb-2">فشل الفحص</div>
                        <div class="text-sm text-gray-600">يحتاج إعادة إنتاج</div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-2">
                                <span>تاريخ الإنشاء:</span>
                                <span>{{ $qualityCheck->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>آخر تحديث:</span>
                                <span>{{ $qualityCheck->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
