# 🛒 WooCommerce Integration - دليل التشغيل الكامل

## التاريخ: 26 أكتوبر 2025
## الحالة: ✅ جاهز للتشغيل

---

## 📋 نظرة عامة

نظام Huda ERP متكامل بالكامل مع WooCommerce لاستيراد الطلبات تلقائياً وحساب التكاليف والأرباح.

---

## ⚙️ خطوات التفعيل

### 1️⃣ إعداد WooCommerce Store

**في موقع WooCommerce الخاص بك:**

1. اذهب إلى: `WooCommerce → Settings → Advanced → REST API`
2. اضغط على: `Add Key`
3. املأ البيانات:
   - **Description:** Huda ERP Integration
   - **User:** اختر مستخدم Admin
   - **Permissions:** Read/Write
4. احفظ وانسخ:
   - ✅ Consumer Key (يبدأ بـ `ck_`)
   - ✅ Consumer Secret (يبدأ بـ `cs_`)

---

### 2️⃣ تحديث ملف .env

افتح ملف `.env` في مجلد المشروع وحدّث هذه القيم:

```env
# WooCommerce Integration
WOOCOMMERCE_STORE_URL=https://your-store.com/
WOOCOMMERCE_CONSUMER_KEY=ck_your_actual_key_here
WOOCOMMERCE_CONSUMER_SECRET=cs_your_actual_secret_here
```

**⚠️ مهم:**
- URL لازم ينتهي بـ `/`
- استخدم الـ Keys الحقيقية من موقعك
- احفظ الملف بعد التعديل

---

### 3️⃣ اختبار الاتصال

شغّل هذا الأمر لاختبار الاتصال:

```bash
cd /Users/yousefgamal/Desktop/myproject/hudaalpinejs/huda-erp-laravel
php artisan woocommerce:sync
```

**النتيجة المتوقعة:**
```
Starting WooCommerce sync...
Syncing customers...
✓ Synced customer: ahmed@example.com
✓ Synced customer: sara@example.com
✅ Synced 2 customers

Syncing products...
✓ Synced product: Classic Abaya
✓ Synced product: Premium Hijab
✅ Synced 5 products

Syncing orders...
✓ Synced order: #1234 (On-Hold)
✅ Synced 3 on-hold orders

WooCommerce sync completed successfully!
```

---

## 🔄 التشغيل التلقائي

### تفعيل Laravel Scheduler

**الطريقة 1: في Development (للتجربة)**

شغّل هذا الأمر في Terminal منفصل:

```bash
cd /Users/yousefgamal/Desktop/myproject/hudaalpinejs/huda-erp-laravel
php artisan schedule:work
```

هذا الأمر هيشغل الـ Scheduler ويعمل sync كل 5 دقائق تلقائياً.

---

**الطريقة 2: في Production (للسيرفر الحقيقي)**

أضف هذا السطر في Crontab:

```bash
* * * * * cd /path/to/huda-erp-laravel && php artisan schedule:run >> /dev/null 2>&1
```

لإضافته:
```bash
crontab -e
```

---

## 📊 كيف يعمل النظام؟

### 1. استيراد الطلبات

```
WooCommerce Order (On-Hold)
    ↓
يتم استيراد الطلب كل 5 دقائق
    ↓
يتم إنشاء:
  - ✅ Customer (العميل)
  - ✅ Products (المنتجات)
  - ✅ Order (الطلب)
  - ✅ Order Items (تفاصيل الطلب)
```

### 2. حساب التكاليف التلقائي

```
Order تم استيراده
    ↓
حساب تلقائي:
  - ✅ Material Cost (تكلفة المواد من BOM)
  - ✅ Labor Cost (تكلفة العمالة)
  - ✅ Overhead Cost (تكلفة التشغيل)
  - ✅ Shipping Cost (تكلفة الشحن حسب البلد)
    ↓
  - ✅ Profit Margin (هامش الربح %)
```

### 3. التتبع التسويقي

يتم استخراج بيانات التسويق:
- ✅ **UTM Source** (Google, Facebook, Instagram)
- ✅ **UTM Medium** (CPC, Email, Social)
- ✅ **UTM Campaign** (Ramadan2025, Summer Sale)
- ✅ **Referrer** (من أين جاء العميل)

---

## 🎯 حالات الطلبات

### في WooCommerce:
- **On-Hold** → يتم استيراده للـ ERP

### في ERP System:
- **On-Hold** → في انتظار المعالجة
- **In-Production** → قيد التصنيع
- **Completed** → مكتمل (يتم إنشاء قيود محاسبية تلقائياً)

---

## 💰 حساب تكلفة الشحن

### الكويت:
```
2 KWD (سعر ثابت)
```

### دول الخليج (GCC):
```
7 KWD (قاعدة)
+ 2 KWD لكل كيلو إضافي فوق 2 كيلو
```

**مثال:**
- طلب وزنه 3.5 كيلو
- التكلفة = 7 + (1.5 × 2) = 10 KWD

### دولي (International):
```
15 KWD (قاعدة)
+ 5 KWD لكل كيلو
```

---

## 📈 التقارير المتاحة

### 1. Profitability Report
```
الرابط: /reports/profitability

يعرض:
- إجمالي الإيرادات
- إجمالي التكاليف
- صافي الربح
- هامش الربح %
- أكثر الطلبات ربحية
- تحليل القنوات التسويقية
```

### 2. Order Cost Breakdown
```
الرابط: /orders/{id}/cost-breakdown

يعرض:
- تكلفة المواد
- تكلفة العمالة
- تكلفة التشغيل
- تكلفة الشحن
- الإيرادات
- الربح الصافي
- بيانات UTM Analytics
```

---

## 🛠️ الأوامر المتاحة

### 1. Sync WooCommerce (يدوي)
```bash
php artisan woocommerce:sync
```

### 2. Recalculate Product Costs (لكل المنتجات)
```bash
php artisan products:recalculate-costs
```

### 3. Recalculate Specific Products
```bash
php artisan products:recalculate-costs --product=1 --product=5 --product=10
```

### 4. Run Scheduler (Development)
```bash
php artisan schedule:work
```

### 5. Check Scheduler
```bash
php artisan schedule:list
```

---

## 🔍 استكشاف الأخطاء

### المشكلة: "Failed to fetch orders from WooCommerce"

**الحل:**
1. تأكد من صحة الـ URL في `.env`
2. تأكد من صحة Consumer Key & Secret
3. تأكد أن WooCommerce REST API مفعّل
4. تأكد من صلاحيات الـ API Key (Read/Write)

---

### المشكلة: "No orders synced"

**الحل:**
- النظام يستورد فقط الطلبات بحالة **"On-Hold"**
- تأكد أن فيه طلبات on-hold في WooCommerce
- جرّب تغيير حالة order لـ "On-Hold" يدوياً

---

### المشكلة: "SSL Certificate Verification Failed"

**الحل:**
- موجود في الكود: `'verify' => false`
- لكن في Production استخدم SSL صحيح

---

## 📝 ملاحظات مهمة

1. ✅ **Automatic Sync** يحدث كل 5 دقائق
2. ✅ **Delay 2 seconds** بين كل عملية لتجنب Rate Limiting
3. ✅ **Per Page = 20** يستورد 20 سجل في المرة
4. ✅ **Logging** كل العمليات مسجلة في `storage/logs/laravel.log`
5. ✅ **Duplicate Prevention** لا يتم تكرار الطلبات (بناءً على woo_id)

---

## 🎓 سيناريو عملي كامل

### الخطوات:

1. **عميل يشتري من الموقع:**
   - يطلب عبايتين من WooCommerce
   - يدفع عبر KNET
   - العنوان: الكويت

2. **الطلب يصل للـ WooCommerce:**
   - Status: Processing
   - تغيير يدوي: On-Hold (للاستيراد)

3. **بعد 5 دقائق - Automatic Sync:**
   ```
   ✓ Customer imported: Ahmed Ali
   ✓ Product imported: Classic Abaya (x2)
   ✓ Order imported: #WOO-1234
   ```

4. **حساب تلقائي:**
   ```
   Material Cost: 30 KWD
   Labor Cost: 10 KWD
   Overhead Cost: 5 KWD
   Shipping: 2 KWD (Kuwait)
   ---
   Total Cost: 47 KWD
   Revenue: 90 KWD
   Profit: 43 KWD (47.8%)
   ```

5. **في ERP Dashboard:**
   - الطلب يظهر بحالة "On-Hold"
   - التكاليف محسوبة
   - Analytics متوفرة

6. **عند اكتمال التصنيع:**
   - تغيير الحالة لـ "Completed"
   - يتم إنشاء قيود محاسبية تلقائياً

---

## 🚀 البدء السريع (Quick Start)

```bash
# 1. تحديث .env بمعلومات WooCommerce
nano .env

# 2. اختبار الاتصال
php artisan woocommerce:sync

# 3. تشغيل الـ Scheduler (للتطوير)
php artisan schedule:work

# 4. فتح المتصفح
# http://127.0.0.1:8000/orders
```

---

## 📞 الدعم

في حالة وجود مشاكل:

1. **تحقق من Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **تحقق من الـ .env:**
   ```bash
   cat .env | grep WOO
   ```

3. **اختبار يدوي:**
   ```bash
   php artisan woocommerce:sync
   ```

---

**✅ النظام جاهز للعمل!** 🎉

عند تحديث بيانات WooCommerce الصحيحة، سيبدأ النظام باستيراد الطلبات تلقائياً كل 5 دقائق.

