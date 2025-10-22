# دليل الأزرار الموحدة - Huda ERP
## Unified Button Guide

تم توحيد تصميم جميع الأزرار في النظام لضمان التناسق والجودة.

---

## 🎨 أنواع الأزرار المتاحة

### 1. Primary Button (الزر الأساسي)
**الاستخدام:** الإجراءات الرئيسية والمهمة
**اللون:** أسود مع نص ذهبي (Black & Gold)

```html
<a href="#" class="btn-primary">
    <i class="fas fa-edit"></i>
    Edit
</a>

<button class="btn-primary">
    <i class="fas fa-save"></i>
    Save
</button>
```

**مثال الاستخدام:**
- Edit Customer
- Save Changes
- Create New
- Confirm Action

---

### 2. Secondary Button (الزر الثانوي)
**الاستخدام:** الإجراءات الثانوية أو الإلغاء
**اللون:** رمادي فاتح مع نص داكن

```html
<a href="#" class="btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Back
</a>

<button class="btn-secondary">
    <i class="fas fa-times"></i>
    Cancel
</button>
```

**مثال الاستخدام:**
- Back to List
- Cancel
- Close
- Skip

---

### 3. Success Button (زر النجاح)
**الاستخدام:** الإجراءات الإيجابية والنجاح
**اللون:** أخضر

```html
<a href="#" class="btn-success">
    <i class="fas fa-check"></i>
    Confirm
</a>

<button class="btn-success">
    <i class="fas fa-plus"></i>
    Add New
</button>
```

**مثال الاستخدام:**
- Confirm Order
- Approve
- Mark as Complete
- Add Item

---

### 4. Danger Button (زر الخطر)
**الاستخدام:** الإجراءات الخطرة التي تحتاج تأكيد
**اللون:** أحمر

```html
<button class="btn-danger">
    <i class="fas fa-trash"></i>
    Delete
</button>

<a href="#" class="btn-danger">
    <i class="fas fa-ban"></i>
    Deactivate
</a>
```

**مثال الاستخدام:**
- Delete Record
- Remove Item
- Cancel Order
- Deactivate Account

---

### 5. Warning Button (زر التحذير)
**الاستخدام:** الإجراءات التي تحتاج انتباه
**اللون:** برتقالي/أصفر

```html
<button class="btn-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Warning
</button>
```

**مثال الاستخدام:**
- Mark as Pending
- Hold Order
- Request Review

---

### 6. Info Button (زر المعلومات)
**الاستخدام:** عرض المعلومات أو التفاصيل
**اللون:** أزرق

```html
<a href="#" class="btn-info">
    <i class="fas fa-eye"></i>
    View Details
</a>
```

**مثال الاستخدام:**
- View Details
- Show Info
- Read More

---

## 📏 أحجام الأزرار

### حجم صغير (Small)
```html
<button class="btn-primary btn-sm">
    <i class="fas fa-plus"></i>
    Add
</button>
```

### حجم عادي (Default)
```html
<button class="btn-primary">
    <i class="fas fa-save"></i>
    Save
</button>
```

### حجم كبير (Large)
```html
<button class="btn-primary btn-lg">
    <i class="fas fa-download"></i>
    Download Report
</button>
```

---

## 🔧 الميزات

### ✅ Gradient Effect
جميع الأزرار تستخدم تدرجات لونية احترافية

### ✅ Hover Effect
تأثير hover سلس مع تغيير اللون والظل

### ✅ Transform Effect
ارتفاع بسيط عند hover للإشارة للتفاعل

### ✅ Shadow
ظلال ناعمة تعطي عمق للتصميم

### ✅ Icon Support
دعم كامل للأيقونات مع spacing مناسب

### ✅ Responsive
تصميم متجاوب على جميع الشاشات

---

## 📝 قواعد الاستخدام

### ❌ لا تفعل:
```html
<!-- لا تستخدم أنماط inline -->
<button style="background: blue; color: white;">Bad Button</button>

<!-- لا تستخدم classes متعددة متضاربة -->
<button class="btn-primary btn-danger">Confusing</button>

<!-- لا تستخدم Tailwind classes مع btn classes -->
<button class="btn-primary bg-red-500">Wrong</button>
```

### ✅ افعل:
```html
<!-- استخدم class واحد مناسب -->
<button class="btn-primary">Good Button</button>

<!-- أضف حجم إذا لزم الأمر -->
<button class="btn-primary btn-sm">Small Button</button>

<!-- استخدم أيقونات مع spacing صحيح -->
<button class="btn-primary">
    <i class="fas fa-save"></i>
    Save
</button>
```

---

## 🎯 أمثلة عملية

### صفحة Index
```html
<!-- Header Actions -->
<div class="flex space-x-3">
    <a href="{{ route('items.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i>
        Add New Item
    </a>
</div>

<!-- Table Actions -->
<a href="{{ route('items.show', $item) }}" class="btn-info btn-sm">
    <i class="fas fa-eye"></i>
</a>
<a href="{{ route('items.edit', $item) }}" class="btn-warning btn-sm">
    <i class="fas fa-edit"></i>
</a>
<button @click="deleteItem({{ $item->id }})" class="btn-danger btn-sm">
    <i class="fas fa-trash"></i>
</button>
```

### صفحة Create/Edit
```html
<!-- Form Actions -->
<div class="flex justify-end space-x-3">
    <a href="{{ route('items.index') }}" class="btn-secondary">
        <i class="fas fa-times"></i>
        Cancel
    </a>
    <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i>
        Save Changes
    </button>
</div>
```

### صفحة Show
```html
<!-- Header Actions -->
<div class="flex space-x-3">
    <a href="{{ route('items.edit', $item) }}" class="btn-primary">
        <i class="fas fa-edit"></i>
        Edit
    </a>
    <a href="{{ route('items.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
```

---

## 🎨 نظام الألوان

```css
Primary:   #1a1a1a → #2d2d2d (Black gradient) + #d4af37 (Gold text)
Secondary: #e5e7eb (Light gray) + #1f2937 (Dark text)
Success:   #10b981 → #059669 (Green gradient)
Danger:    #ef4444 → #dc2626 (Red gradient)
Warning:   #f59e0b → #d97706 (Orange gradient)
Info:      #3b82f6 → #2563eb (Blue gradient)
```

---

## 📊 التوافقية

- ✅ جميع المتصفحات الحديثة
- ✅ أجهزة Mobile و Tablet
- ✅ RTL Support
- ✅ Accessibility Ready
- ✅ Print Friendly

---

## 🔄 التحديثات المستقبلية

لإضافة نوع زر جديد، قم بتحديث ملف:
`public/css/luxury-style.css`

واتبع نفس البنية:
```css
.btn-custom,
a.btn-custom {
    background: linear-gradient(135deg, #color1 0%, #color2 100%);
    color: white;
    /* ... نفس الخصائص الأخرى */
}

.btn-custom:hover,
a.btn-custom:hover {
    /* ... hover effects */
}
```

---

## 📞 للدعم

عند وجود مشكلة في الأزرار:
1. تأكد من تضمين `luxury-style.css`
2. تحقق من استخدام class صحيح
3. لا تخلط Tailwind classes مع Button classes
4. استخدم Developer Tools للتحقق

---

**آخر تحديث:** {{ now()->format('Y-m-d') }}
**الإصدار:** 1.0
**الحالة:** Production Ready ✅

