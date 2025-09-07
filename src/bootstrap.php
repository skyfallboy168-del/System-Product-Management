<?php

// Define App Root - this is the most reliable place to define it.
define('APP_ROOT', dirname(__DIR__));

// Load Config
require_once APP_ROOT . '/config/config.php';

// Load Helpers
require_once APP_ROOT . '/src/helpers/session_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    $file = APP_ROOT . '/src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
