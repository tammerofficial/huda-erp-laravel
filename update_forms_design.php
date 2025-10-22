#!/usr/bin/env php
<?php

/**
 * Script to update all create/edit forms to use unified black & gold design
 * Converts blue colors to black/gray and standardizes button classes
 */

$replacements = [
    // Button classes - convert blue to btn-primary/secondary
    'bg-blue-600 text-white rounded-lg hover:bg-blue-700' => 'btn-primary',
    'bg-blue-600 hover:bg-blue-700 text-white' => 'btn-primary',
    'px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors' => 'btn-primary',
    'bg-blue-500 hover:bg-blue-600 text-white' => 'btn-primary',
    'bg-gray-500 hover:bg-gray-600 text-white' => 'btn-secondary',
    'px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors' => 'btn-secondary',
    
    // Focus rings - convert blue to gray-900
    'focus:ring-blue-500' => 'focus:ring-gray-900',
    'focus:ring-blue-600' => 'focus:ring-gray-900',
    'focus:ring-2 focus:ring-blue-500' => 'focus:ring-2 focus:ring-gray-900',
    'focus:ring-2 focus:ring-blue-600' => 'focus:ring-2 focus:ring-gray-900',
    
    // Checkbox colors
    'text-blue-600 focus:ring-blue-500' => 'text-gray-900 focus:ring-gray-900',
    'text-blue-600 focus:ring-blue-600' => 'text-gray-900 focus:ring-gray-900',
    
    // Padding standardization
    'px-3 py-2' => 'px-4 py-2',
    
    // Section headers - remove icons and make bold
    'text-lg font-semibold text-gray-900 mb-4' => 'text-lg font-bold text-gray-900 mb-6',
    
    // Remove colored icons from headers (multiple patterns)
    'flex items-center">\n                    <i class="fas fa-building mr-2 text-blue-600"></i>\n                    ' => '">',
    'flex items-center">\n                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>\n                    ' => '">',
    'flex items-center">\n                    <i class="fas fa-handshake mr-2 text-purple-600"></i>\n                    ' => '">',
    'flex items-center">\n                    <i class="fas fa-user mr-2 text-blue-600"></i>\n                    ' => '">',
    'flex items-center">\n                    <i class="fas fa-briefcase mr-2 text-green-600"></i>\n                    ' => '">',
    'flex items-center">\n                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>\n                    ' => '">',
];

$directories = [
    'resources/views/suppliers',
    'resources/views/customers',
    'resources/views/employees',
    'resources/views/products',
    'resources/views/orders',
    'resources/views/production',
    'resources/views/warehouses',
    'resources/views/accounting',
    'resources/views/users',
    'resources/views/purchases',
    'resources/views/invoices',
];

$filesUpdated = 0;
$totalReplacements = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    
    $files = array_merge(
        glob("$dir/create.blade.php"),
        glob("$dir/edit.blade.php")
    );
    
    foreach ($files as $file) {
        if (!file_exists($file)) {
            continue;
        }
        
        $content = file_get_contents($file);
        $originalContent = $content;
        $fileReplacements = 0;
        
        foreach ($replacements as $search => $replace) {
            $count = 0;
            $content = str_replace($search, $replace, $content, $count);
            $fileReplacements += $count;
        }
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            $filesUpdated++;
            $totalReplacements += $fileReplacements;
            echo "✓ Updated: $file ($fileReplacements replacements)\n";
        } else {
            echo "○ Skipped: $file (no changes needed)\n";
        }
    }
}

echo "\n";
echo "════════════════════════════════════════\n";
echo "✅ Update Complete!\n";
echo "════════════════════════════════════════\n";
echo "Files Updated: $filesUpdated\n";
echo "Total Replacements: $totalReplacements\n";
echo "════════════════════════════════════════\n";

