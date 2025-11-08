# 🚀 Tammer Deployment

Laravel Deployment Kit جاهز للنشر التلقائي على cPanel مع Tailwind CSS و CI/CD.

## 📦 المميزات

- ✅ **cPanel Deployment** - `.cpanel.yml` جاهز
- ✅ **GitHub Actions CI/CD** - نشر تلقائي
- ✅ **Tailwind CSS** - Production-ready (بدون CDN)
- ✅ **Vite** - Build tool محسّن
- ✅ **PostCSS** - مع Autoprefixer
- ✅ **index.php Fix** - إصلاح تلقائي للمسارات
- ✅ **.htaccess** - DirectoryIndex و RewriteBase

## 🎯 الاستخدام

### الطريقة 1: استخدام Installer Script (موصى به)

```bash
# 1. انسخ الملفات إلى مشروعك الجديد
cp -r tammer-deployment/* /path/to/your/new-project/
cp -r tammer-deployment/.github /path/to/your/new-project/

# 2. انسخ ملف Config واملأه
cp deploy.config.json.example deploy.config.json
# عدّل deploy.config.json ببيانات مشروعك

# 3. شغّل Installer
cd /path/to/your/new-project
bash install.sh
```

**ملف `deploy.config.json` يحتوي على جميع المتغيرات:**
- ✅ APP_NAME, APP_URL
- ✅ Database credentials
- ✅ cPanel connection info
- ✅ GitHub Secret name

الـ Installer يقرأ `deploy.config.json` ويُعدّل جميع الملفات تلقائياً!

### الطريقة 2: يدوياً (ملف config واحد)

1. **انسخ ملف Config:**
   ```bash
   cp deploy.config.json.example deploy.config.json
   ```

2. **املأ `deploy.config.json` ببيانات مشروعك:**
   ```json
   {
     "app": {
       "name": "my-app",
       "url": "https://myapp.com"
     },
     "database": {
       "database": "my_db",
       "username": "my_user",
       "password": "my_password"
     },
     "cpanel": {
       "host": "123.45.67.89",
       "user": "my_user",
       "password": "cpanel_pass",
       "repo_path": "/home/my_user/public_html"
     },
     "github": {
       "secret_name": "MYAPP_CPANEL_SECRET"
     }
   }
   ```

3. **شغّل Installer:**
   ```bash
   bash install.sh
   ```

4. **أضف GitHub Secret (انظر `GITHUB_SECRETS_INSTRUCTIONS.txt`)

## 📁 هيكل الملفات

```
tammer-deployment/
├── .cpanel.yml.template          # Template للنشر (يُعدّل تلقائياً)
├── .github/
│   └── workflows/
│       └── deploy-cpanel.yml.template  # GitHub Actions template
├── tailwind.config.js           # ✅ جاهز
├── postcss.config.js             # ✅ جاهز
├── vite.config.js                # ✅ جاهز
├── deploy.config.json.example    # مثال لملف Config
├── deploy.config.json            # ⭐ ملف Config الرئيسي (املأه ببياناتك)
├── install.sh                    # Installer script (يقرأ deploy.config.json)
├── README.md                     # هذا الملف
└── setup-github-secrets.md      # دليل GitHub Secrets
```

## 🔧 التثبيت

### 1. نسخ الملفات

```bash
cp -r tammer-deployment/* /path/to/new-project/
cp -r tammer-deployment/.github /path/to/new-project/
```

### 2. إعداد ملف Config

```bash
cd /path/to/new-project
cp deploy.config.json.example deploy.config.json
# عدّل deploy.config.json ببيانات مشروعك
```

### 3. تشغيل Installer

```bash
bash install.sh
```

الـ Installer سيقوم بـ:
- ✅ قراءة `deploy.config.json`
- ✅ إنشاء `.cpanel.yml` مع جميع القيم
- ✅ إنشاء `.github/workflows/deploy-cpanel.yml`
- ✅ إنشاء `GITHUB_SECRETS_INSTRUCTIONS.txt`

### 4. إعداد GitHub Secrets

انظر `GITHUB_SECRETS_INSTRUCTIONS.txt` (تم إنشاؤه تلقائياً)

### 5. إعداد cPanel Git Repository

1. `cPanel » Files » Git Version Control`
2. `Create` → املأ البيانات
3. Done!

## ✅ Checklist

- [ ] نسخ الملفات إلى المشروع الجديد
- [ ] نسخ `deploy.config.json.example` إلى `deploy.config.json`
- [ ] ملء `deploy.config.json` ببيانات مشروعك
- [ ] تشغيل `bash install.sh`
- [ ] إضافة GitHub Secret (انظر `GITHUB_SECRETS_INSTRUCTIONS.txt`)
- [ ] إعداد cPanel Git Repository
- [ ] `npm install` (في المشروع الجديد)
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

