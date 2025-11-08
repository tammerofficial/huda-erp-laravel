# 🚀 دليل إعداد Automatic Deployment في cPanel

## 📋 المتطلبات

- ✅ ملف `.cpanel.yml` موجود في root المشروع
- ✅ Repository على GitHub: `https://github.com/tammerofficial/huda-erp-laravel.git`
- ✅ حساب cPanel مع صلاحيات Git Version Control

---

## 🔧 الطريقة 1: إعداد cPanel Git Repository (موصى بها)

### الخطوة 1: إنشاء Repository في cPanel

1. سجل دخول إلى **cPanel**
2. اذهب إلى: **Files » Git Version Control**
3. انقر على **Create**
4. املأ البيانات التالية:
   ```
   Repository Name: huda-erp-laravel
   Clone URL: https://github.com/tammerofficial/huda-erp-laravel.git
   Repository Path: (اتركه افتراضي)
   Branch: main
   ```
5. انقر **Create**

### الخطوة 2: تفعيل Automatic Deployment

بعد إنشاء الـ repository، cPanel يضيف تلقائياً **post-receive hook** الذي يشغل `.cpanel.yml` عند push.

**للـ Push Deployment التلقائي:**
- عند push إلى GitHub، اذهب إلى cPanel
- في Git Version Control، انقر **Update from Remote**
- ثم انقر **Deploy HEAD Commit**

---

## ⚡ الطريقة 2: Fully Automatic Deployment (مع Webhook)

لتفعيل deployment تلقائياً 100% عند push إلى GitHub:

### الخطوة 1: إنشاء Webhook Script

أنشئ ملف `webhook.php` في `public_html`:

```php
<?php
// webhook.php - GitHub Webhook Handler
$secret = 'YOUR_SECRET_KEY_HERE'; // اختر مفتاح سري
$hookSecret = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if ($hookSecret) {
    $payload = file_get_contents('php://input');
    $signature = hash_hmac('sha256', $payload, $secret);
    
    if (hash_equals('sha256=' . $signature, $hookSecret)) {
        // تنفيذ deployment
        $output = shell_exec('cd ~/repositories/huda-erp-laravel && git pull origin main 2>&1');
        file_put_contents('/tmp/webhook.log', date('Y-m-d H:i:s') . "\n" . $output . "\n\n", FILE_APPEND);
        
        // تشغيل deployment
        shell_exec('cd ~/repositories/huda-erp-laravel && git checkout main && git pull origin main');
        
        http_response_code(200);
        echo "Deployment triggered";
    } else {
        http_response_code(403);
        echo "Invalid signature";
    }
} else {
    http_response_code(400);
    echo "Missing signature";
}
?>
```

### الخطوة 2: إعداد GitHub Webhook

1. اذهب إلى GitHub Repository: `https://github.com/tammerofficial/huda-erp-laravel`
2. Settings » Webhooks » Add webhook
3. املأ:
   ```
   Payload URL: https://workshop.hudaaljarallah.net/webhook.php
   Content type: application/json
   Secret: YOUR_SECRET_KEY_HERE (نفس المفتاح في webhook.php)
   Events: Just the push event
   ```
4. انقر **Add webhook**

---

## 🔄 الطريقة 3: استخدام Cron Job (بديل)

إذا لم تعمل Webhooks، استخدم Cron Job:

1. في cPanel، اذهب إلى: **Advanced » Cron Jobs**
2. أضف Cron Job:
   ```
   Command: cd ~/repositories/huda-erp-laravel && git pull origin main && git checkout main
   Minute: */5 (كل 5 دقائق)
   Hour: *
   Day: *
   Month: *
   Weekday: *
   ```

---

## ✅ التحقق من العمل

بعد الإعداد:

1. **اختبر Push:**
   ```bash
   git add .
   git commit -m "Test deployment"
   git push origin main
   ```

2. **تحقق في cPanel:**
   - اذهب إلى Git Version Control
   - تحقق من آخر commit
   - انقر "Deploy HEAD Commit"

3. **تحقق من الموقع:**
   - افتح: `https://workshop.hudaaljarallah.net`
   - يجب أن يعمل الموقع بشكل صحيح

---

## 🐛 استكشاف الأخطاء

### المشكلة: Deployment لا يعمل تلقائياً

**الحل:**
- تأكد من وجود `.cpanel.yml` في root المشروع
- تحقق من صلاحيات الملفات: `chmod 644 .cpanel.yml`
- تحقق من logs في cPanel

### المشكلة: خطأ في قاعدة البيانات

**الحل:**
- تأكد من تحديث `.env` ببيانات قاعدة البيانات
- شغل migrations يدوياً: `php artisan migrate --force`

### المشكلة: `.htaccess` لا يعمل

**الحل:**
- تحقق من وجود `.htaccess` في `public_html`
- تحقق من صلاحيات: `chmod 644 .htaccess`
- تحقق من أن mod_rewrite مفعل في Apache

---

## 📚 مراجع

- [cPanel Git Deployment Guide](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)
- [Git Hooks Documentation](https://git-scm.com/docs/githooks)

---

**تم إنشاء هذا الدليل بواسطة:** Huda ERP Team  
**آخر تحديث:** 2025-01-XX

