<?php
/**
 * Файл: include_config/config.php
 */

// === РЕЖИМ ОТЛАДКИ ===
// На production поменяйте на false!
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/php_errors.log');
}

// === САЙТ ===
define('SITE_NAME', 'Master of illusion');
define('SITE_URL', 'https://lovix.top');
define('SITE_EMAIL', 'contact@lovix.top');

// === API КЛЮЧИ ===
define('FOSSBILLING_API_URL', 'https://bill.sthost.pro');
define('FOSSBILLING_API_TOKEN', 'YPo9tN0V8Ih0pdHDAKJfBuyNA08CnaHN');

// === БЕЗОПАСНОСТЬ ===
define('SESSION_LIFETIME', 3600); // 1 час
define('SESSION_NAME', 'moi_session');
define('MAX_UPLOAD_SIZE', 104857600); // 100MB

// === ПУТИ ===
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/public/uploads');

// Инициализация сессии
if (!defined('SKIP_SESSION_START')) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
}