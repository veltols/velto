<?php
// storage_link.php

// Absolute paths (adjust according to your Hostinger account)
$target = __DIR__ . '/../storage/app/public';  // Path to your storage folder
$link   = __DIR__ . '/storage';                // Path where link should be created in public folder

// Check if link already exists
if (file_exists($link)) {
    echo "Storage link already exists or a folder named 'storage' exists.";
    exit;
}

// Try creating symlink
if (function_exists('symlink')) {
    try {
        symlink($target, $link);
        echo "✅ Storage link created successfully!";
    } catch (Exception $e) {
        echo "❌ Failed to create symlink: " . $e->getMessage();
    }
} else {
    echo "❌ Symlink function is disabled on this hosting plan.";
    echo "<br>Fallback: You can manually copy contents of 'storage/app/public' to 'public/storage'";
}
?>
