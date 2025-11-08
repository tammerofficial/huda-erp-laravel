# 🔐 دليل إعداد GitHub Secrets

## ⚠️ ملاحظة مهمة

**لا يمكن إنشاء GitHub Secrets تلقائياً بدون GitHub API access.**  
يجب إضافتها يدوياً من واجهة GitHub.

## 📋 الخطوات اليدوية

### الطريقة 1: من واجهة GitHub (موصى بها)

1. **اذهب إلى Repository:**
   ```
   Repository » Settings » Secrets and variables » Actions
   ```

2. **أنشئ Environment (اختياري):**
   - انقر `New environment`
   - الاسم: `your-project-name cpanel`
   - انقر `Configure environment`

3. **أضف Secret:**
   - انقر `Add secret`
   - الاسم: `YOUR_PROJECT_CPANEL_SECRET` (يجب أن يطابق الاسم في workflow)
   - القيمة: (انسخ من الأسفل)

4. **محتوى Secret:**
   ```
   CPANEL_HOST=your_server_ip_or_domain
   CPANEL_USER=your_cpanel_username
   CPANEL_PASSWORD=your_cpanel_password
   CPANEL_PORT=22
   CPANEL_REPO_PATH=/home/your_username/public_html
   ```

5. **احفظ**

### الطريقة 2: استخدام GitHub CLI (أسرع)

إذا كان لديك `gh` CLI مثبت:

```bash
# تسجيل الدخول
gh auth login

# إضافة Secret (Environment)
gh secret set YOUR_PROJECT_CPANEL_SECRET \
  --env "your-project-name cpanel" \
  --body "$(cat <<EOF
CPANEL_HOST=your_server_ip
CPANEL_USER=your_cpanel_username
CPANEL_PASSWORD=your_cpanel_password
CPANEL_PORT=22
CPANEL_REPO_PATH=/home/your_username/public_html
EOF
)"
```

### الطريقة 3: استخدام GitHub API (متقدم)

```bash
# احصل على Personal Access Token من GitHub Settings
export GITHUB_TOKEN=your_personal_access_token
export REPO=your-username/your-repo
export ENV_NAME="your-project-name cpanel"
export SECRET_NAME="YOUR_PROJECT_CPANEL_SECRET"

# احصل على Environment ID
ENV_ID=$(gh api repos/$REPO/environments/$ENV_NAME --jq '.id')

# أنشئ Secret (يحتاج Public Key من GitHub)
# هذا معقد - استخدم الطريقة 1 أو 2 بدلاً منها
```

## 🔄 تحديث Secret

```bash
# باستخدام GitHub CLI
gh secret set YOUR_PROJECT_CPANEL_SECRET \
  --env "your-project-name cpanel" \
  --body "new_value"
```

## ✅ التحقق

بعد إضافة Secret، اختبر Workflow:

1. اذهب إلى: `Repository » Actions`
2. اختر: `Deploy to cPanel (via SSH)`
3. انقر `Run workflow`
4. تحقق من Logs

## 🎯 نصائح

1. **استخدم Environment Secrets** بدلاً من Repository Secrets للأمان الأفضل

2. **اسم Secret يجب أن يطابق** الاسم في `.github/workflows/deploy-cpanel.yml`

3. **لا تشارك Secrets** - احتفظ بها آمنة

4. **استخدم Password Auth** إذا كان SSH Key مشفّر (يطلب passphrase)

## 📝 مثال كامل

```bash
# 1. أنشئ Environment
gh api repos/tammerofficial/my-project/environments/my-project-cpanel \
  -X PUT \
  -f name="my-project cpanel"

# 2. أضف Secret (يحتاج Public Key - معقد)
# الأفضل: استخدم واجهة GitHub أو gh secret set
```

---

**الخلاصة:** استخدم **الطريقة 1** (واجهة GitHub) - الأسهل والأكثر أماناً.

