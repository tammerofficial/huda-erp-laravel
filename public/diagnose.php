<?php
/**
 * Laravel Deployment Diagnostic Tool
 * Upload this file to public_html to check deployment status
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Deployment Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .check { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .path { color: #666; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Laravel Deployment Diagnostic</h1>
        
        <?php
        $basePath = __DIR__;
        $checks = [];
        
        // Check current directory
        echo '<div class="check info">';
        echo '<strong>📁 Current Directory:</strong><br>';
        echo '<code>' . htmlspecialchars($basePath) . '</code>';
        echo '</div>';
        
        // Check index.php
        $indexExists = file_exists($basePath . '/index.php');
        $checks['index.php'] = $indexExists;
        echo '<div class="check ' . ($indexExists ? 'success' : 'error') . '">';
        echo '<strong>📄 index.php:</strong> ' . ($indexExists ? '✅ موجود' : '❌ غير موجود');
        if ($indexExists) {
            echo '<br><span class="path">' . htmlspecialchars($basePath . '/index.php') . '</span>';
        }
        echo '</div>';
        
        // Check .htaccess
        $htaccessExists = file_exists($basePath . '/.htaccess');
        $checks['.htaccess'] = $htaccessExists;
        echo '<div class="check ' . ($htaccessExists ? 'success' : 'error') . '">';
        echo '<strong>⚙️ .htaccess:</strong> ' . ($htaccessExists ? '✅ موجود' : '❌ غير موجود');
        if ($htaccessExists) {
            echo '<br><span class="path">' . htmlspecialchars($basePath . '/.htaccess') . '</span>';
        }
        echo '</div>';
        
        // Check vendor
        $vendorExists = file_exists($basePath . '/vendor/autoload.php');
        $checks['vendor'] = $vendorExists;
        echo '<div class="check ' . ($vendorExists ? 'success' : 'error') . '">';
        echo '<strong>📦 vendor/autoload.php:</strong> ' . ($vendorExists ? '✅ موجود' : '❌ غير موجود');
        if (!$vendorExists) {
            echo '<br><span class="path">المسار المتوقع: ' . htmlspecialchars($basePath . '/vendor/autoload.php') . '</span>';
            echo '<br><strong>الحل:</strong> قم بتشغيل <code>composer install</code> في مجلد public_html';
        } else {
            echo '<br><span class="path">' . htmlspecialchars($basePath . '/vendor/autoload.php') . '</span>';
        }
        echo '</div>';
        
        // Check bootstrap
        $bootstrapExists = file_exists($basePath . '/bootstrap/app.php');
        $checks['bootstrap'] = $bootstrapExists;
        echo '<div class="check ' . ($bootstrapExists ? 'success' : 'error') . '">';
        echo '<strong>🚀 bootstrap/app.php:</strong> ' . ($bootstrapExists ? '✅ موجود' : '❌ غير موجود');
        if (!$bootstrapExists) {
            echo '<br><span class="path">المسار المتوقع: ' . htmlspecialchars($basePath . '/bootstrap/app.php') . '</span>';
        } else {
            echo '<br><span class="path">' . htmlspecialchars($basePath . '/bootstrap/app.php') . '</span>';
        }
        echo '</div>';
        
        // Check app directory
        $appExists = is_dir($basePath . '/app');
        $checks['app'] = $appExists;
        echo '<div class="check ' . ($appExists ? 'success' : 'error') . '">';
        echo '<strong>📁 app/:</strong> ' . ($appExists ? '✅ موجود' : '❌ غير موجود');
        echo '</div>';
        
        // Check storage
        $storageExists = is_dir($basePath . '/storage');
        $checks['storage'] = $storageExists;
        $storageWritable = $storageExists && is_writable($basePath . '/storage');
        echo '<div class="check ' . ($storageWritable ? 'success' : ($storageExists ? 'warning' : 'error')) . '">';
        echo '<strong>💾 storage/:</strong> ';
        if (!$storageExists) {
            echo '❌ غير موجود';
        } elseif (!$storageWritable) {
            echo '⚠️ موجود لكن غير قابل للكتابة';
            echo '<br><strong>الحل:</strong> قم بتشغيل <code>chmod -R 775 storage</code>';
        } else {
            echo '✅ موجود وقابل للكتابة';
        }
        echo '</div>';
        
        // Check .env
        $envExists = file_exists($basePath . '/.env');
        $checks['.env'] = $envExists;
        echo '<div class="check ' . ($envExists ? 'success' : 'warning') . '">';
        echo '<strong>⚙️ .env:</strong> ' . ($envExists ? '✅ موجود' : '⚠️ غير موجود');
        if (!$envExists) {
            echo '<br><strong>الحل:</strong> قم بنسخ <code>.env.example</code> إلى <code>.env</code> وتعديل الإعدادات';
        }
        echo '</div>';
        
        // Check PHP version
        $phpVersion = phpversion();
        echo '<div class="check info">';
        echo '<strong>🐘 PHP Version:</strong> ' . htmlspecialchars($phpVersion);
        if (version_compare($phpVersion, '8.1', '<')) {
            echo ' ⚠️ Laravel يحتاج PHP 8.1 أو أحدث';
        }
        echo '</div>';
        
        // Check mod_rewrite
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            $modRewrite = in_array('mod_rewrite', $modules);
            echo '<div class="check ' . ($modRewrite ? 'success' : 'error') . '">';
            echo '<strong>🔄 mod_rewrite:</strong> ' . ($modRewrite ? '✅ مفعّل' : '❌ غير مفعّل');
            if (!$modRewrite) {
                echo '<br><strong>الحل:</strong> قم بتفعيل mod_rewrite في cPanel';
            }
            echo '</div>';
        }
        
        // Summary
        $allPassed = !in_array(false, $checks);
        echo '<div class="check ' . ($allPassed ? 'success' : 'error') . '" style="margin-top: 20px; font-size: 1.2em; font-weight: bold;">';
        if ($allPassed) {
            echo '✅ جميع الفحوصات نجحت! الموقع يجب أن يعمل بشكل صحيح.';
        } else {
            echo '❌ بعض الفحوصات فشلت. يرجى إصلاح المشاكل المذكورة أعلاه.';
        }
        echo '</div>';
        
        // List all files in current directory
        echo '<div class="check info" style="margin-top: 20px;">';
        echo '<strong>📋 الملفات والمجلدات في المجلد الحالي:</strong><br>';
        $files = scandir($basePath);
        echo '<ul style="margin: 10px 0; padding-right: 20px;">';
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $path = $basePath . '/' . $file;
                $isDir = is_dir($path);
                $icon = $isDir ? '📁' : '📄';
                echo '<li>' . $icon . ' <code>' . htmlspecialchars($file) . '</code></li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        ?>
        
        <div class="check info" style="margin-top: 20px;">
            <strong>💡 ملاحظات:</strong>
            <ul style="margin: 10px 0; padding-right: 20px;">
                <li>بعد إصلاح المشاكل، احذف هذا الملف (<code>diagnose.php</code>) من السيرفر</li>
                <li>تأكد من تشغيل <code>composer install</code> في مجلد public_html</li>
                <li>تأكد من صلاحيات الملفات: ملفات 644، مجلدات 755</li>
                <li>تأكد من صلاحيات storage و bootstrap/cache: 775</li>
            </ul>
        </div>
    </div>
</body>
</html>

