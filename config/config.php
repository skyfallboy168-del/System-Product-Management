<?php

// =================================================================================================
//                                      Application Configuration
// =================================================================================================

// --- Error Reporting ---
// Set to E_ALL for development, and 0 for production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Database Configuration ---
define('DB_HOST', '127.0.0.1'); // Or 'localhost'
define('DB_PORT', '3306');
define('DB_NAME', 'biz_master_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Your MySQL root password, often empty in XAMPP
define('DB_CHARSET', 'utf8mb4');

// --- Application Paths ---
// No trailing slash
define('APP_ROOT', dirname(__DIR__));
define('URL_ROOT', 'http://localhost/biz-master'); // Change this to your domain in production

// --- Application Information ---
define('APP_NAME', 'BizMaster Pro');
define('APP_VERSION', '1.0.0');

// --- Session ---
session_start();
