# ⚡ Quick Start Guide

## 🚀 للمشاريع الجديدة - 3 خطوات فقط!

### الخطوة 1: نسخ الملفات

```bash
# انسخ Skeleton إلى مشروعك الجديد
cp -r tammer-laravel-skeleton/* /path/to/your/new-project/
cp -r tammer-laravel-skeleton/.github /path/to/your/new-project/
```

### الخطوة 2: تشغيل Installer

```bash
cd /path/to/your/new-project
bash install.sh  # أو ./install.sh على Linux/Mac
```

الـ Installer سيسألك:
- ✅ APP_NAME
- ✅ APP_URL
- ✅ DB_DATABASE
- ✅ DB_USERNAME
- ✅ DB_PASSWORD
- ✅ GitHub Secret name

### الخطوة 3: إضافة GitHub Secret

1. اذهب إلى: `Repository » Settings » Secrets and variables » Actions`
2. أضف Environment: `your-project-name cpanel`
3. أضف Secret بالاسم الذي أدخلته في Installer
4. محتوى Secret:
```
CPANEL_HOST=your_server_ip
CPANEL_USER=your_cpanel_username
CPANEL_PASSWORD=your_cpanel_password
CPANEL_PORT=22
CPANEL_REPO_PATH=/home/your_username/public_html
```

### ✅ انتهى!

```bash
git push origin main
```

النشر التلقائي سيعمل! 🎉

---

## 📝 بديل: التعديل اليدوي

إذا لم تريد استخدام Installer:

### 1. أعد تسمية Templates

```bash
mv .cpanel.yml.template .cpanel.yml
mv .github/workflows/deploy-cpanel.yml.template .github/workflows/deploy-cpanel.yml
```

### 2. عدل 3 متغيرات في `.cpanel.yml`:

- السطر 102: `APP_NAME=your_app_name`
- السطر 110: `APP_URL=https://your-domain.com`
- السطور 140-144: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### 3. عدل Secret name في `.github/workflows/deploy-cpanel.yml`:

- السطر 25: `${{ secrets.YOUR_PROJECT_CPANEL_SECRET }}`

---

**هذا كل شيء!** 🚀

