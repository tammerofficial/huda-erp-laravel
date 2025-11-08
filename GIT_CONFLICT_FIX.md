# 🔧 حل مشكلة Git Conflict على السيرفر

## المشكلة
```
error: Your local changes to the following files would be overwritten by merge:
public/.htaccess
Please commit your changes or stash them before you merge.
```

## الحل السريع (عبر SSH)

### الطريقة 1: Stash التغييرات المحلية
```bash
cd /home/workshophudaalja/repo  # أو المسار الصحيح للمستودع
git stash
git pull origin main
```

### الطريقة 2: Reset التغييرات المحلية (أسرع)
```bash
cd /home/workshophudaalja/repo
git reset --hard HEAD
git clean -fd
git pull origin main
```

### الطريقة 3: Force Pull (الأقوى)
```bash
cd /home/workshophudaalja/repo
git fetch origin
git reset --hard origin/main
```

## الحل عبر cPanel File Manager

1. افتح **cPanel Terminal** أو **SSH Access**
2. اذهب إلى مجلد المستودع:
   ```bash
   cd /home/workshophudaalja/repo
   ```
3. تحقق من التغييرات:
   ```bash
   git status
   ```
4. احذف التغييرات المحلية:
   ```bash
   git reset --hard HEAD
   git pull origin main
   ```

## الحل التلقائي (GitHub Actions)

تم تحديث workflow في `.github/workflows/deploy-cpanel.yml` لحل التعارضات تلقائياً.

**بعد التحديث:**
- ارفع التغييرات إلى Git
- GitHub Actions ستحل التعارضات تلقائياً

## ملاحظات مهمة

⚠️ **تحذير**: `git reset --hard` سيمحو جميع التغييرات المحلية غير المحفوظة!

✅ **الأفضل**: استخدم `git stash` إذا كنت تريد الاحتفاظ بالتغييرات المحلية

## التحقق من الحل

بعد حل التعارض:
```bash
git status
# يجب أن يظهر: "Your branch is up to date with 'origin/main'"
```

## إذا استمرت المشكلة

1. تحقق من صلاحيات Git:
   ```bash
   ls -la .git
   ```

2. تحقق من إعدادات Git:
   ```bash
   git config --list
   ```

3. اتصل بالدعم الفني إذا كانت المشكلة مستمرة

