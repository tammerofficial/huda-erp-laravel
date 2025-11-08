# ⚡ Quick Start Guide - 3 خطوات فقط!

## 🎯 الخطوات

### 1️⃣ نسخ الملفات

```bash
cp -r tammer-deployment/* /path/to/your/new-project/
cp -r tammer-deployment/.github /path/to/your/new-project/
```

### 2️⃣ إعداد ملف Config

```bash
cd /path/to/your/new-project
cp deploy.config.json.example deploy.config.json
```

**عدّل `deploy.config.json`:**

```json
{
  "app": {
    "name": "my-app",                    // ⬅️ غيّر هذا
    "url": "https://myapp.com"           // ⬅️ غيّر هذا
  },
  "database": {
    "database": "my_db",                  // ⬅️ غيّر هذا
    "username": "my_user",               // ⬅️ غيّر هذا
    "password": "my_password"           // ⬅️ غيّر هذا
  },
  "cpanel": {
    "host": "123.45.67.89",              // ⬅️ غيّر هذا
    "user": "my_user",                   // ⬅️ غيّر هذا
    "password": "cpanel_pass",           // ⬅️ غيّر هذا
    "repo_path": "/home/my_user/public_html"  // ⬅️ غيّر هذا
  },
  "github": {
    "secret_name": "MYAPP_CPANEL_SECRET"  // ⬅️ غيّر هذا
  }
}
```

### 3️⃣ تشغيل Installer

```bash
bash install.sh
```

**الـ Installer سيقوم بـ:**
- ✅ قراءة `deploy.config.json`
- ✅ إنشاء `.cpanel.yml` تلقائياً
- ✅ إنشاء `.github/workflows/deploy-cpanel.yml` تلقائياً
- ✅ إنشاء `GITHUB_SECRETS_INSTRUCTIONS.txt`

### 4️⃣ إضافة GitHub Secret

افتح `GITHUB_SECRETS_INSTRUCTIONS.txt` واتبع التعليمات.

### 5️⃣ النشر!

```bash
git push origin main
```

**انتهى! 🎉**

---

## 📝 ملاحظة

**ملف `deploy.config.json` واحد يحتوي على كل شيء:**
- ✅ APP_NAME, APP_URL
- ✅ Database credentials
- ✅ cPanel connection
- ✅ GitHub Secret name

**لا حاجة لتعديل ملفات متعددة - ملف واحد فقط!**
