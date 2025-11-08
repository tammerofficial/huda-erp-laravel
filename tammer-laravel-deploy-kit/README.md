# 🚀 Tammer Laravel Deploy Kit

Template جاهز للنشر التلقائي لمشاريع Laravel على cPanel/Hostinger.

## 📦 المحتويات

- ✅ `.cpanel.yml` - إعدادات النشر التلقائي
- ✅ `.github/workflows/deploy-cpanel.yml` - GitHub Actions workflow
- ✅ `tailwind.config.js` - إعدادات Tailwind CSS
- ✅ `postcss.config.js` - إعدادات PostCSS
- ✅ `vite.config.js` - إعدادات Vite
- ✅ `setup-github-secrets.md` - دليل إعداد GitHub Secrets

## 🎯 الاستخدام السريع

### الخطوة 1: نسخ الملفات

```bash
# انسخ جميع الملفات إلى مشروعك الجديد
cp -r tammer-laravel-deploy-kit/* /path/to/your/new-project/
cp -r tammer-laravel-deploy-kit/.github /path/to/your/new-project/
```

### الخطوة 2: تحديث 3 متغيرات فقط

#### 1. في `.cpanel.yml`:

**السطر 102:** `APP_NAME`
```yaml
APP_NAME=your_app_name
```

**السطر 110:** `APP_URL`
```yaml
APP_URL=https://your-domain.com
```

**السطور 140-144:** بيانات قاعدة البيانات
```yaml
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD="your_database_password"
```

#### 2. في `.github/workflows/deploy-cpanel.yml`:

**السطر 25:** اسم Secret في GitHub
```yaml
${{ secrets.YOUR_PROJECT_CPANEL_SECRET }}
```

### الخطوة 3: إعداد GitHub Secrets

انظر `setup-github-secrets.md` للتفاصيل الكاملة.

**باختصار:**
1. اذهب إلى: `Repository » Settings » Secrets and variables » Actions`
2. أضف Environment: `your-project-name cpanel`
3. أضف Secret: `YOUR_PROJECT_CPANEL_SECRET`
4. محتوى Secret:
```
CPANEL_HOST=your_server_ip
CPANEL_USER=your_cpanel_username
CPANEL_PASSWORD=your_cpanel_password
CPANEL_PORT=22
CPANEL_REPO_PATH=/home/your_username/public_html
```

### الخطوة 4: إعداد cPanel Git Repository

1. اذهب إلى: `cPanel » Files » Git Version Control`
2. انقر `Create`
3. املأ:
   - Repository Name: `your-project-name`
   - Clone URL: `https://github.com/your-username/your-repo.git`
   - Branch: `main`
4. انقر `Create`

### الخطوة 5: النشر!

```bash
git push origin main
```

أو أنشئ tag:
```bash
git tag -a v1.0.0 -m "Initial release"
git push origin v1.0.0
```

## ✅ ما يتم تلقائياً

- ✅ نسخ جميع الملفات إلى `public_html`
- ✅ إصلاح مسارات `index.php`
- ✅ إنشاء/تحديث `.htaccess` مع DirectoryIndex
- ✅ إعداد `.env` بقيم الإنتاج
- ✅ `composer install`
- ✅ `npm run build` (Tailwind CSS)
- ✅ `php artisan migrate --force`
- ✅ Clear & Cache جميع الـ caches

## 📝 ملاحظات مهمة

1. **Tailwind CSS:** تأكد من وجود `@vite(['resources/css/app.css', 'resources/js/app.js'])` في Blade layouts (ليس CDN)

2. **قاعدة البيانات:** يجب أن تكون بيانات DB صحيحة في `.cpanel.yml` قبل النشر

3. **SSH:** يمكن استخدام SSH Key أو Password (Password أسهل)

4. **المسارات:** كل شيء يُنسخ إلى `public_html` مباشرة (ليس في مجلد فرعي)

## 🐛 استكشاف الأخطاء

### المشكلة: عرض قائمة الملفات

**الحل:** تحقق من `.htaccess` يحتوي على `DirectoryIndex index.php`

### المشكلة: Tailwind CDN Warning

**الحل:** تأكد من استخدام `@vite()` وليس `<script src="https://cdn.tailwindcss.com"></script>`

### المشكلة: Migration Fails

**الحل:** تحقق من بيانات DB في `.cpanel.yml` (السطور 140-144)

## 📚 المراجع

- [cPanel Git Deployment Guide](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)
- [Laravel Vite Documentation](https://laravel.com/docs/vite)
- [Tailwind CSS Installation](https://tailwindcss.com/docs/installation)

---

**تم إنشاء بواسطة:** Tammer Team  
**الإصدار:** 1.0.0  
**آخر تحديث:** 2025-11-08

