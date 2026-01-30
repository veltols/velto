<?php
/**
 * storage_link.php
 * Hostinger-friendly version: copies storage files instead of symlink
 */

// ----- CONFIG -----
$storagePath = '/home/u494123079/storage/app/public'; // <-- Replace 'username' with your Hostinger username
$publicPath  = __DIR__ . '/storage';               // Will create storage inside public_html/dev

// ----- FUNCTION TO COPY FOLDER -----
function copyFolder($src, $dst) {
    if (!is_dir($src)) {
        echo "❌ Storage source folder does not exist: $src";
        exit;
    }

    @mkdir($dst, 0755, true); // create destination folder

    $dir = opendir($src);
    while(false !== ($file = readdir($dir))) {
        if ($file != '.' && $file != '..') {
            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;
            if (is_dir($srcFile)) {
                copyFolder($srcFile, $dstFile);
            } else {
                copy($srcFile, $dstFile);
            }
        }
    }
    closedir($dir);
}

// ----- CHECK IF ALREADY EXISTS -----
if (file_exists($publicPath)) {
    echo "⚠️ Storage folder already exists at: $publicPath<br>";
    echo "Delete the folder first if you want to copy fresh files.";
    exit;
}

// ----- COPY FILES -----
copyFolder($storagePath, $publicPath);
echo "✅ Storage files copied successfully to: $publicPath";
?>
