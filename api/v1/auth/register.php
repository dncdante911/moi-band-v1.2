<?php
/**
 * Файл: api/v1/auth/register.php
 * API Endpoint для регистрации пользователя
 * POST /api/v1/auth/register.php
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
        $logger->logError('/api/v1/auth/register', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['POST']);
    }
    
    // === ПОЛУЧИТЬ ДАННЫЕ ===
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $logger->logValidationError('/api/v1/auth/register', ['Invalid JSON']);
        APIResponse::error('Invalid JSON format', 400);
    }
    
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = trim($input['password'] ?? '');
    $password_confirm = trim($input['password_confirm'] ?? '');
    $display_name = trim($input['display_name'] ?? '') ?: $username;
    
    $logger->logRequest('/api/v1/auth/register', 'POST', null, [
        'username' => $username,
        'email' => $email,
        'password' => '***',
        'password_confirm' => '***'
    ]);
    
    // === ВАЛИДАЦИЯ ===
    $errors = [];
    
    // Username
    if (empty($username)) {
        $errors['username'] = 'Username требуется';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username должен быть минимум 3 символа';
    } elseif (strlen($username) > 100) {
        $errors['username'] = 'Username не может быть больше 100 символов';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $errors['username'] = 'Username может содержать только буквы, цифры, _, -';
    }
    
    // Email
    if (empty($email)) {
        $errors['email'] = 'Email требуется';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    } elseif (strlen($email) > 255) {
        $errors['email'] = 'Email не может быть больше 255 символов';
    }
    
    // Password
    if (empty($password)) {
        $errors['password'] = 'Пароль требуется';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Пароль должен быть минимум 8 символов';
    } elseif (strlen($password) > 255) {
        $errors['password'] = 'Пароль не может быть больше 255 символов';
    }
    
    // Password confirm
    if ($password !== $password_confirm) {
        $errors['password_confirm'] = 'Пароли не совпадают';
    }
    
    // Display name
    if (strlen($display_name) > 150) {
        $errors['display_name'] = 'Отображаемое имя не может быть больше 150 символов';
    }
    
    if (!empty($errors)) {
        $logger->logValidationError('/api/v1/auth/register', $errors);
        APIResponse::validationError($errors);
    }
    
    // === ПРОВЕРКА СУЩЕСТВОВАНИЯ ПОЛЬЗОВАТЕЛЯ ===
    $stmt = $pdo->prepare("SELECT id FROM Users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->fetch()) {
        $conflict_errors = [];
        
        $stmt = $pdo->prepare("SELECT id FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $conflict_errors['username'] = 'Этот username уже занят';
        }
        
        $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $conflict_errors['email'] = 'Этот email уже зарегистрирован';
        }
        
        $logger->logValidationError('/api/v1/auth/register', $conflict_errors);
        APIResponse::validationError($conflict_errors);
    }
    
    // === ХЭШИРОВАНИЕ ПАРОЛЯ ===
    $password_hash = password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 1
    ]);
    
    // === СОЗДАНИЕ ПОЛЬЗОВАТЕЛЯ ===
    try {
        $pdo->beginTransaction();
        
        // Создать пользователя
        $stmt = $pdo->prepare("
            INSERT INTO Users 
            (username, email, password_hash, display_name, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'offline', NOW(), NOW())
        ");
        
        $stmt->execute([
            $username,
            $email,
            $password_hash,
            $display_name
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Создать предпочтения пользователя
        $stmt = $pdo->prepare("
            INSERT INTO UserPreferences 
            (user_id, theme, language, notifications_enabled, email_notifications, created_at, updated_at)
            VALUES (?, 'dark', 'ru', 1, 0, NOW(), NOW())
        ");
        
        $stmt->execute([$user_id]);
        
        $pdo->commit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $logger->logException('/api/v1/auth/register', $e);
        APIResponse::serverError('Failed to create user', 'USER_CREATE_ERROR');
    }
    
    // === СОЗДАТЬ JWT ТОКЕН ===
    $token = $jwt->generateToken($user_id, $username, $email);
    
    // === УСПЕШНЫЙ ОТВЕТ ===
    $response = [
        'token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => $jwt->getTokenLifetime(),
        'user' => [
            'id' => (int)$user_id,
            'username' => $username,
            'email' => $email,
            'display_name' => $display_name,
            'avatar_path' => null
        ]
    ];
    
    $logger->logResponse('/api/v1/auth/register', 201, $user_id);
    APIResponse::success($response, 201, 'Registration successful');
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/auth/register', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/auth/register', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}