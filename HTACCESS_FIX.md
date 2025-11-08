# 🔧 حل مشكلة 404 - ملف .htaccess

## المشكلة
الموقع يعرض خطأ 404: `GET https://workshop.hudaaljarallah.net/ 404 (Not Found)`

## الحل السريع (يدوي)

### الخطوة 1: رفع ملف `.htaccess`
1. افتح **cPanel File Manager**
2. اذهب إلى `/home/workshophudaalja/public_html`
3. تأكد من تفعيل **"Show Hidden Files"** في إعدادات File Manager
4. أنشئ ملف جديد باسم `.htaccess` (يبدأ بنقطة)
5. انسخ المحتوى التالي والصقه:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Ensure DirectoryIndex prefers PHP front controller
DirectoryIndex index.php index.html

# Protect sensitive files
<FilesMatch "^(\.env|\.git|composer\.(json|lock)|package(-lock)?\.json|\.cpanel\.yml|\.md|\.sh|update_.*\.php|artisan)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
</FilesMatch>
```

6. احفظ الملف
7. اضبط الصلاحيات على **644**

### الخطوة 2: التحقق من mod_rewrite
1. في cPanel، اذهب إلى **Apache Modules** أو **Select PHP Version**
2. تأكد من تفعيل **mod_rewrite**
3. إذا لم يكن مفعّلاً، فعّله

### الخطوة 3: اختبار الموقع
1. ارفع ملف `test.php` إلى `public_html` (موجود في `public/test.php`)
2. افتح `https://workshop.hudaaljarallah.net/test.php`
3. يجب أن ترى معلومات عن PHP والإعدادات
4. احذف `test.php` بعد الاختبار

### الخطوة 4: التحقق من index.php
تأكد من وجود `index.php` في `public_html` وأنه يحتوي على:
```php
<?php
// ... Laravel bootstrap code ...
```

## الحل التلقائي (Git)

إذا كنت تريد رفع الملفات تلقائياً:

```bash
git add public/.htaccess public/index.php public/test.php
git commit -m "Fix: Add .htaccess and update index.php for cPanel"
git push origin main
```

بعد الـ push، سيتم النشر تلقائياً عبر GitHub Actions.

## التحقق من المشاكل الشائعة

### 1. ملف .htaccess غير موجود
- ✅ تأكد من رفع الملف يدوياً أو عبر Git
- ✅ تأكد من أن اسم الملف يبدأ بنقطة: `.htaccess`

### 2. mod_rewrite غير مفعّل
- ✅ في cPanel: **Select PHP Version** → **Extensions** → فعّل `mod_rewrite`
- ✅ أو اتصل بالدعم الفني

### 3. المسارات غير صحيحة
- ✅ تأكد من أن `index.php` موجود في `public_html`
- ✅ تأكد من أن `vendor/` و `bootstrap/` موجودان في نفس المجلد

### 4. صلاحيات الملفات
```bash
chmod 644 .htaccess
chmod 644 index.php
chmod -R 755 public_html
```

## اختبار سريع

بعد رفع `.htaccess`:
1. افتح `https://workshop.hudaaljarallah.net/`
2. إذا ظهرت صفحة Laravel → ✅ تم الحل
3. إذا ظهر 404 → تحقق من الخطوات أعلاه

## ملاحظات مهمة

- ⚠️ ملف `.htaccess` حساس جداً - أي خطأ صغير قد يكسر الموقع
- ⚠️ تأكد من نسخ المحتوى بالكامل بدون تعديل
- ⚠️ بعد التعديل، امسح الكاش: `php artisan config:clear && php artisan cache:clear`

