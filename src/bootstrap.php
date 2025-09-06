<?php

// =================================================================================================
//                                      Application Bootstrap
// =================================================================================================

// --- Load Config ---
require_once '../config/config.php';

// --- Load Helpers ---
require_once 'helpers/session_helper.php';

// --- Autoload Core Libraries ---
// This function will automatically load class files when a class is instantiated.
spl_autoload_register(function($className) {
    // Core\App -> src/Core/App.php
    $file = APP_ROOT . '/src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
