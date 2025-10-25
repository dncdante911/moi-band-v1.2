<?php
/**
 * Файл: api/v1/auth/login.php
 * API Endpoint для входа пользователя
 * POST /api/v1/auth/login.php
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../../../include_config/config.php';
require_once '../../../include_config/db_connect.php';
require_once '../../../include_config/JWTHandler.php';
require_once '../../../include_config/APIResponse.php';
require_once '../../../include_config/APILogger.php';

$logger = new APILogger();
$jwt = new JWTHandler();

try {
    // === ПРОВЕРКА МЕТОДА ===
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $logger->logError('/api/v1/auth/login', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['POST']);
    }
    
    // === ПОЛУЧИТЬ ДАННЫЕ ===
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $logger->logValidationError('/api/v1/auth/login', ['Invalid JSON']);
        APIResponse::error('Invalid JSON format', 400);
    }
    
    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');
    
    $logger->logRequest('/api/v1/auth/login', 'POST', null, [
        'username' => $username,
        'password' => '***'
    ]);
    
    // === ВАЛИДАЦИЯ ===
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = 'Username или email требуется';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Пароль требуется';
    }
    
    if (!empty($errors)) {
        $logger->logValidationError('/api/v1/auth/login', $errors);
        APIResponse::validationError($errors);
    }
    
    // === ПОИСК ПОЛЬЗОВАТЕЛЯ ===
    $stmt = $pdo->prepare("
        SELECT id, username, email, password_hash, display_name, avatar_path, is_banned
        FROM Users 
        WHERE (username = ? OR email = ?)
        AND is_banned = 0
    ");
    
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $logger->logUnauthorized('/api/v1/auth/login', 'User not found', null);
        APIResponse::unauthorized('Неверный username/email или пароль');
    }
    
    // === ПРОВЕРКА ПАРОЛЯ ===
    if (!password_verify($password, $user['password_hash'])) {
        $logger->logUnauthorized('/api/v1/auth/login', 'Invalid password', $user['id']);
        APIResponse::unauthorized('Неверный username/email или пароль');
    }
    
    // === СОЗДАТЬ JWT ТОКЕН ===
    $token = $jwt->generateToken(
        $user['id'],
        $user['username'],
        $user['email']
    );
    
    // === ОБНОВИТЬ СТАТУС ПОЛЬЗОВАТЕЛЯ ===
    $stmt = $pdo->prepare("
        UPDATE Users 
        SET status = 'online', last_seen = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$user['id']]);
    
    // === УСПЕШНЫЙ ОТВЕТ ===
    $response = [
        'token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => $jwt->getTokenLifetime(),
        'user' => [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'display_name' => $user['display_name'],
            'avatar_path' => $user['avatar_path']
        ]
    ];
    
    $logger->logResponse('/api/v1/auth/login', 200, $user['id']);
    APIResponse::success($response, 200, 'Login successful');
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/auth/login', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/auth/login', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}