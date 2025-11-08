<?php
// Test file to verify PHP and server configuration
echo "<h1>PHP is working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";
echo "<p>Index.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'Yes' : 'No') . "</p>";
echo "<p>.htaccess exists: " . (file_exists(__DIR__ . '/.htaccess') ? 'Yes' : 'No') . "</p>";

// Check mod_rewrite
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p>mod_rewrite enabled: " . (in_array('mod_rewrite', $modules) ? 'Yes' : 'No') . "</p>";
} else {
    echo "<p>Cannot check Apache modules (not running as Apache module)</p>";
}
?>

