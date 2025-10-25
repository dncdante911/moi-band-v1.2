<?php
/**
 * Файл: include_config/db_connect.php
 * * ВАЖНО: Этот файл использует ПРЯМЫЕ настройки без .env
 * После первого запуска перенесите данные в .env файл
 */

// !!! ШАГ 1: ПОДКЛЮЧАЕМ APIResponse, т.к. он в той же папке (__DIR__)
require_once __DIR__ . '/APIResponse.php'; 

// === НАСТРОЙКИ БД ===
$db_host = '127.0.0.1';
$db_name = 'moi-band';
$db_user = 'moi-band';
$db_pass = '0607Dm$157';
$db_charset = 'utf8mb4';

// === СОЗДАНИЕ DSN ===
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

// === ОПЦИИ PDO ===
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// === ПОДКЛЮЧЕНИЕ К БД ===
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
    // Проверка подключения
    $pdo->query('SELECT 1');
    
} catch (\PDOException $e) {
    
    // Определяем, является ли запрос API (для корректного JSON-ответа)
    // Используем strpos() для поиска '/api/' в URL
    $is_api_request = strpos($_SERVER['REQUEST_URI'], '/api/') !== false;
    
    // Устанавливаем код ошибки 500 для всех случаев сбоя БД
    http_response_code(500);

    if ($is_api_request) {
        // --- ДЛЯ МОБИЛЬНОГО ПРИЛОЖЕНИЯ (JSON) ---
        
        $message = 'Ошибка подключения к базе данных';
        
        // Используем DEBUG_MODE из config.php для вывода деталей
        $details = null;
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $details = $e->getMessage();
        }
        
        // Используем APIResponse::error для гарантированного JSON-вывода
        APIResponse::error($message, 500, $details);
        
    } else {
        // --- ДЛЯ САЙТА (HTML) ---
        
        // ВАШ ОРИГИНАЛЬНЫЙ HTML-вывод, адаптированный для использования DEBUG_MODE
        echo '<!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>❌ Ошибка подключения к БД</title>
            <style>
                body { font-family: sans-serif; background-color: #333; color: #fff; padding: 20px; }
                .error-container { max-width: 600px; margin: 50px auto; background: #222; padding: 30px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
                .error-details { background: #444; padding: 10px; margin: 15px 0; font-family: monospace; color: #ff9999; word-break: break-all; }
                .checklist { 
                    background: rgba(65,105,225,0.1);
                    border-left: 3px solid #4169E1;
                    padding: 15px;
                    margin: 20px 0;
                }
                .checklist li { margin: 8px 0; }
                .checklist strong { color: #FFD700; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>❌ Ошибка подключения к базе данных</h1>
                <p>Невозможно установить соединение с MySQL.</p>
                <div class="error-details">
                    ' . (defined('DEBUG_MODE') && DEBUG_MODE ? htmlspecialchars($e->getMessage()) : 'Детали скрыты. Проверьте настройки БД.') . '
                </div>
                <div class="checklist">
                    <strong>🔍 Проверьте:</strong>
                    <ul>
                        <li>✓ MySQL сервер запущен?</li>
                        <li>✓ Хост правильный: <code>127.0.0.1</code></li>
                        <li>✓ БД создана: <code>moi-band</code></li>
                        <li>✓ Пользователь: <code>moi-band</code></li>
                        <li>✓ Пароль правильный: <code>0607Dm$157</code></li>
                        <li>✓ Брандмауэр не блокирует порт 3306?</li>
                    </ul>
                </div>
                <p style="color: #aaa; font-size: 0.9rem;">
                    Если вы на Production - включите режим production в config.php и скройте эти ошибки от пользователей.
                </p>
            </div>
        </body>
        </html>';
    }
    
    // exit; вызывается внутри APIResponse::error(), но если это не API-запрос, 
    // нужно вызвать exit здесь, чтобы остановить выполнение скрипта
    exit; 
}