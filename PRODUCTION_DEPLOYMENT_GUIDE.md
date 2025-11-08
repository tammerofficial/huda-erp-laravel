# 🚀 دليل النشر والإنتاج الشامل - Huda ERP Laravel

## 📋 جدول المحتويات

1. [نظرة عامة](#نظرة-عامة)
2. [إعداد cPanel Deployment](#إعداد-cpanel-deployment)
3. [إعداد GitHub Actions CI/CD](#إعداد-github-actions-cicd)
4. [إعداد Tailwind CSS للإنتاج](#إعداد-tailwind-css-للإنتاج)
5. [إصلاح مشاكل .htaccess](#إصلاح-مشاكل-htaccess)
6. [إعداد قاعدة البيانات](#إعداد-قاعدة-البيانات)
7. [إعداد SSH للنشر التلقائي](#إعداد-ssh-للنشر-التلقائي)
8. [استكشاف الأخطاء](#استكشاف-الأخطاء)
9. [المراجع](#المراجع)

---

## 🎯 نظرة عامة

هذا الدليل يوثق جميع الخطوات والإعدادات المطلوبة لنشر مشروع Laravel على cPanel/Hostinger مع:
- ✅ النشر التلقائي عبر GitHub Actions
- ✅ إعداد Tailwind CSS للإنتاج (بدون CDN)
- ✅ إصلاح مشاكل .htaccess و DirectoryIndex
- ✅ إعداد قاعدة البيانات والـ Migrations
- ✅ إعداد SSH للنشر التلقائي

---

## 📦 إعداد cPanel Deployment

### 1. إنشاء ملف `.cpanel.yml`

الملف موجود في: `.cpanel.yml`

**الوظائف الرئيسية:**
- نسخ جميع الملفات إلى `public_html`
- إصلاح مسارات `index.php` (استبدال `../` بـ `./`)
- إنشاء/تحديث `.htaccess` مع DirectoryIndex
- إعداد `.env` بقيم الإنتاج
- تشغيل `composer install` و `npm run build`
- تشغيل Migrations و Seeders تلقائياً

**المتغيرات المهمة في `.env`:**
```env
APP_NAME=huda
APP_ENV=production
APP_DEBUG=true
APP_URL=https://workshop.hudaaljarallah.net

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshophudaalja_larafh29h
DB_USERNAME=workshophudaalja_fasdfw4w3
DB_PASSWORD="CRPw{4TgktmajdJ("
```

### 2. إعداد cPanel Git Repository

**الخطوات:**
1. اذهب إلى: `cPanel » Files » Git Version Control`
2. انقر `Create`
3. املأ البيانات:
   ```
   Repository Name: huda-erp-laravel
   Clone URL: https://github.com/tammerofficial/huda-erp-laravel.git
   Branch: main
   ```
4. انقر `Create`

**ملاحظة:** cPanel يضيف تلقائياً `post-receive hook` الذي يشغل `.cpanel.yml` عند push.

---

## ⚙️ إعداد GitHub Actions CI/CD

### 1. ملفات Workflow

**الملفات الموجودة:**
- `.github/workflows/ci.yml` - CI للاختبارات والبناء
- `.github/workflows/deploy-cpanel.yml` - النشر التلقائي إلى cPanel
- `.github/workflows/gitleaks.yml` - فحص التسريبات
- `.github/workflows/codeql.yml` - تحليل الأمان
- `.github/dependabot.yml` - تحديثات تلقائية للـ dependencies

### 2. إعداد Secrets في GitHub

**الخطوات:**
1. اذهب إلى: `Repository » Settings » Secrets and variables » Actions`
2. أضف Environment: `huda cpanel`
3. أضف Secret واحد: `WORKSHOP_HOSTINGER_CP`

**محتوى Secret (كل سطر بشكل key=value):**
```
CPANEL_HOST=72.61.166.241
CPANEL_USER=workshophudaalja
CPANEL_PASSWORD=Qweasd@2020@@
CPANEL_PORT=22
CPANEL_REPO_PATH=/home/workshophudaalja/public_html
```

### 3. تفعيل النشر التلقائي

**الآن النشر يعمل تلقائياً عند:**
- ✅ Push إلى `main` branch
- ✅ إنشاء Tag (مثل `v2.1.0`)

**Workflow يقوم بـ:**
1. تحليل Secret إلى متغيرات
2. الاتصال بالـ SSH
3. عمل `git pull` في cPanel repository
4. تشغيل `.cpanel.yml` تلقائياً

---

## 🎨 إعداد Tailwind CSS للإنتاج

### 1. التثبيت

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 2. ملف `tailwind.config.js`

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: { /* ... */ },
        gold: { /* ... */ }
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 3. ملف `postcss.config.js`

```javascript
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
};
```

### 4. ملف `resources/css/app.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
@layer base {
  html {
    font-family: 'Inter', system-ui, sans-serif;
  }
}
```

### 5. استخدام في Blade

**❌ خطأ (CDN - Development فقط):**
```blade
<script src="https://cdn.tailwindcss.com"></script>
```

**✅ صحيح (Production):**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### 6. البناء

```bash
npm run build
```

**النتيجة:** ملفات CSS/JS محلية ومحسّنة في `public/build/`

---

## 🔧 إصلاح مشاكل .htaccess

### المشكلة
عرض قائمة الملفات (Index of /) بدلاً من الداشبورد.

### الحل

**1. إضافة DirectoryIndex:**
```apache
DirectoryIndex index.php index.html
```

**2. إضافة RewriteBase:**
```apache
RewriteEngine On
RewriteBase /
```

**3. منع عرض الملفات:**
```apache
Options -Indexes
```

**4. حماية الملفات الحساسة:**
```apache
<FilesMatch "^(\.env|\.git|composer\.(json|lock)|package(-lock)?\.json|\.cpanel\.yml|\.md|\.sh|update_.*\.php)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
</FilesMatch>
```

### إصلاح مسارات index.php

**المشكلة:** Laravel يبحث عن `vendor` و `bootstrap` في `../` لكن كل شيء في `public_html`.

**الحل في `.cpanel.yml`:**
```bash
# Fix index.php paths since everything is in public_html
sed -i "s|__DIR__\.'/\.\./|__DIR__.'/|g" index.php
```

---

## 🗄️ إعداد قاعدة البيانات

### 1. إعداد .env

**القيم المطلوبة:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD="your_database_password"
```

### 2. التحقق من البيانات قبل Migrations

**في `.cpanel.yml`:**
```bash
# Check if database credentials are set before running migrations
DB_DB=$(grep -E '^DB_DATABASE=' .env | cut -d= -f2- | tr -d '"')
DB_USER=$(grep -E '^DB_USERNAME=' .env | cut -d= -f2- | tr -d '"')
DB_PASS=$(grep -E '^DB_PASSWORD=' .env | cut -d= -f2- | tr -d '"')

if [ -z "$DB_DB" ] || [ -z "$DB_USER" ] || [ -z "$DB_PASS" ] || [ "$DB_USER" = "root" ]; then
    echo "WARNING: Invalid DB credentials"
else
    php artisan migrate --force
    php artisan db:seed --class=Database\\Seeders\\RolesSeeder --force
fi
```

### 3. إصلاح مشكلة Roles Table

**المشكلة:** `Table 'roles' doesn't exist`

**الحل:**
1. تشغيل `php artisan config:clear` قبل migrations
2. التأكد من وجود migration: `2025_11_08_150058_create_permission_tables.php`
3. تشغيل RolesSeeder بعد migrations

---

## 🔐 إعداد SSH للنشر التلقائي

### 1. إنشاء SSH Key

```bash
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"
```

### 2. إضافة Public Key في cPanel

1. اذهب إلى: `cPanel » Security » SSH Access`
2. انقر `Manage SSH Keys`
3. انقر `Import Key`
4. الصق محتوى `id_rsa.pub`
5. انقر `Save`
6. انقر `Manage` بجانب المفتاح
7. انقر `Authorize`

### 3. استخدام Password Auth (بديل)

إذا كان المفتاح مشفّر (يطلب passphrase)، استخدم password auth:

**في GitHub Secrets:**
```
CPANEL_PASSWORD=your_password
```

**في Workflow:**
```yaml
- uses: appleboy/ssh-action@v1.0.3
  with:
    host: ${{ env.CPANEL_HOST }}
    username: ${{ env.CPANEL_USER }}
    password: ${{ env.CPANEL_PASSWORD }}
    port: ${{ env.CPANEL_PORT }}
    script: |
      cd ${{ env.CPANEL_REPO_PATH }}
      git pull origin main
```

---

## 🐛 استكشاف الأخطاء

### المشكلة: عرض قائمة الملفات

**الأسباب المحتملة:**
1. `.htaccess` غير موجود
2. `DirectoryIndex` غير موجود
3. `mod_rewrite` غير مفعّل

**الحل:**
```bash
# على السيرفر
cd ~/public_html
ls -la .htaccess
cat .htaccess | grep DirectoryIndex
```

### المشكلة: Tailwind CDN Warning

**السبب:** استخدام `<script src="https://cdn.tailwindcss.com"></script>`

**الحل:**
- استبدل بـ `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- تأكد من وجود `postcss.config.js`
- شغّل `npm run build`

### المشكلة: Vite Preload Warning

**السبب:** الملفات المبنية غير موجودة أو المسارات خاطئة

**الحل:**
```bash
# على السيرفر
cd ~/public_html
ls -la public/build/
npm run build
```

### المشكلة: Migration Fails

**الأسباب:**
1. بيانات قاعدة البيانات غير صحيحة
2. المستخدم ليس `root` (يجب أن يكون مستخدم قاعدة بيانات)
3. Config cache قديم

**الحل:**
```bash
php artisan config:clear
php artisan cache:clear
# تحقق من .env
cat .env | grep DB_
php artisan migrate --force
```

### المشكلة: GitHub Actions Fails

**الأسباب:**
1. Secrets غير موجودة
2. SSH connection فشل
3. المسارات خاطئة

**الحل:**
1. تحقق من Secrets في GitHub
2. اختبر SSH connection يدوياً
3. تحقق من logs في Actions tab

---

## 📚 المراجع

### الوثائق الرسمية

- [cPanel Git Deployment Guide](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)
- [Laravel Vite Documentation](https://laravel.com/docs/vite)
- [Tailwind CSS Installation](https://tailwindcss.com/docs/installation)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

### الملفات المهمة في المشروع

- `.cpanel.yml` - إعدادات النشر
- `.github/workflows/deploy-cpanel.yml` - Workflow النشر
- `tailwind.config.js` - إعدادات Tailwind
- `postcss.config.js` - إعدادات PostCSS
- `vite.config.js` - إعدادات Vite
- `resources/css/app.css` - ملف CSS الرئيسي

### الأوامر المفيدة

```bash
# على السيرفر
cd ~/public_html
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force
npm run build

# محلياً
git tag -a v2.1.0 -m "Release message"
git push origin v2.1.0
```

---

## ✅ Checklist النشر

قبل النشر، تأكد من:

- [ ] `.cpanel.yml` موجود ومحدث
- [ ] GitHub Secrets مضبوطة
- [ ] `.env` يحتوي على بيانات قاعدة البيانات الصحيحة
- [ ] Tailwind CSS مثبت (ليس CDN)
- [ ] `postcss.config.js` موجود
- [ ] `@vite()` مستخدم في Blade (ليس CDN)
- [ ] `.htaccess` يحتوي على DirectoryIndex
- [ ] SSH keys مضبوطة في cPanel
- [ ] cPanel Git Repository موجود
- [ ] Workflows تعمل في GitHub Actions

---

## 🎉 الخلاصة

تم إعداد نظام نشر كامل ومحسّن يتضمن:

1. **النشر التلقائي** - عند push أو tag
2. **Tailwind CSS للإنتاج** - بدون CDN
3. **إصلاح .htaccess** - DirectoryIndex و RewriteBase
4. **إعداد قاعدة البيانات** - Migrations و Seeders تلقائية
5. **SSH Authentication** - آمن ومضمون
6. **CI/CD Pipeline** - اختبارات وفحص أمان

**الإصدارات:**
- `v2.0.0` - الإصدار الرسمي الثاني
- `v2.1.0` - إضافة CI/CD و Security
- `v2.1.9` - إصلاح Tailwind CSS للإنتاج

---

**تم إنشاء هذا الدليل بواسطة:** Huda ERP Team  
**آخر تحديث:** 2025-11-08  
**الإصدار:** 2.1.9

