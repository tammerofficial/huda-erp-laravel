# 🚀 Tammer Laravel Skeleton

Laravel Skeleton جاهز للنشر التلقائي على cPanel مع Tailwind CSS و CI/CD.

## 📦 المميزات

- ✅ **cPanel Deployment** - `.cpanel.yml` جاهز
- ✅ **GitHub Actions CI/CD** - نشر تلقائي
- ✅ **Tailwind CSS** - Production-ready (بدون CDN)
- ✅ **Vite** - Build tool محسّن
- ✅ **PostCSS** - مع Autoprefixer
- ✅ **index.php Fix** - إصلاح تلقائي للمسارات
- ✅ **.htaccess** - DirectoryIndex و RewriteBase

## 🎯 الاستخدام

### الطريقة 1: استخدام Installer Script

```bash
# 1. انسخ الملفات إلى مشروعك الجديد
cp -r tammer-laravel-skeleton/* /path/to/your/new-project/
cp -r tammer-laravel-skeleton/.github /path/to/your/new-project/

# 2. شغّل Installer
cd /path/to/your/new-project
chmod +x install.sh
./install.sh
```

الـ Installer سيسألك عن:
- APP_NAME
- APP_URL
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- GitHub Secret name

### الطريقة 2: يدوياً (3 متغيرات فقط)

#### 1. في `.cpanel.yml.template` (أعد تسميته إلى `.cpanel.yml`):

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

#### 2. في `.github/workflows/deploy-cpanel.yml.template` (أعد تسميته):

**السطر 25:** اسم Secret
```yaml
${{ secrets.YOUR_PROJECT_CPANEL_SECRET }}
```

#### 3. أضف GitHub Secret (انظر `setup-github-secrets.md`)

## 📁 هيكل الملفات

```
tammer-laravel-skeleton/
├── .cpanel.yml.template          # Template للنشر (يُنسخ ويُعدّل)
├── .github/
│   └── workflows/
│       └── deploy-cpanel.yml.template  # GitHub Actions template
├── tailwind.config.js           # ✅ جاهز
├── postcss.config.js             # ✅ جاهز
├── vite.config.js                # ✅ جاهز
├── package.json                  # ✅ جاهز
├── composer.json                 # ✅ جاهز
├── install.sh                    # Installer script
├── README.md                     # هذا الملف
└── setup-github-secrets.md      # دليل GitHub Secrets
```

## 🔧 التثبيت

### 1. نسخ الملفات

```bash
cp -r tammer-laravel-skeleton/* /path/to/new-project/
cp -r tammer-laravel-skeleton/.github /path/to/new-project/
```

### 2. تشغيل Installer

```bash
cd /path/to/new-project
chmod +x install.sh
./install.sh
```

### 3. إعداد GitHub Secrets

انظر `setup-github-secrets.md`

### 4. إعداد cPanel Git Repository

1. `cPanel » Files » Git Version Control`
2. `Create` → املأ البيانات
3. Done!

## ✅ Checklist

- [ ] نسخ الملفات إلى المشروع الجديد
- [ ] تشغيل `install.sh` أو تعديل يدوي
- [ ] تحديث `.cpanel.yml` (APP_NAME, APP_URL, DB credentials)
- [ ] تحديث `.github/workflows/deploy-cpanel.yml` (Secret name)
- [ ] إضافة GitHub Secret
- [ ] إعداد cPanel Git Repository
- [ ] `npm install` (إذا لم يفعله installer)
- [ ] `git push origin main` → نشر تلقائي! 🎉

## 🎨 Tailwind CSS

**مضبوط بشكل صحيح:**
- ✅ `tailwind.config.js` - مسارات صحيحة
- ✅ `postcss.config.js` - PostCSS + Autoprefixer
- ✅ `vite.config.js` - Vite integration
- ✅ `package.json` - جميع dependencies

**في Blade:**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**❌ لا تستخدم:**
```blade
<script src="https://cdn.tailwindcss.com"></script>
```

## 📚 المراجع

- [PRODUCTION_DEPLOYMENT_GUIDE.md](../PRODUCTION_DEPLOYMENT_GUIDE.md) - دليل شامل
- [setup-github-secrets.md](setup-github-secrets.md) - إعداد GitHub Secrets
- [cPanel Git Deployment](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)

---

**تم إنشاء بواسطة:** Tammer Team  
**الإصدار:** 1.0.0  
**آخر تحديث:** 2025-11-08

