<?php
/**
 * Файл: api/v1/user/profile.php
 * API Endpoint для получения и обновления профиля пользователя
 * GET /api/v1/user/profile.php - получить профиль
 * PUT /api/v1/user/profile.php - обновить профиль
 * 
 * Требует JWT токен в Authorization заголовке
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

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
    if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'PUT'])) {
        $logger->logError('/api/v1/user/profile', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET', 'PUT']);
    }
    
    // === ПОЛУЧИТЬ ТОКЕН И ПРОВЕРИТЬ АВТОРИЗАЦИЮ ===
    $token = $jwt->getTokenFromHeaders();
    
    if (!$token) {
        $logger->logUnauthorized('/api/v1/user/profile', 'No token provided', null);
        APIResponse::unauthorized('No token provided');
    }
    
    $verification = $jwt->verifyToken($token);
    
    if (!$verification['valid']) {
        $logger->logUnauthorized('/api/v1/user/profile', $verification['error'], null);
        APIResponse::unauthorized($verification['error']);
    }
    
    $user_id = $verification['payload']['user_id'];
    
    $logger->logRequest('/api/v1/user/profile', $_SERVER['REQUEST_METHOD'], $user_id);
    
    // === GET: ПОЛУЧИТЬ ПРОФИЛЬ ===
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Получить полную информацию пользователя
        $stmt = $pdo->prepare("
            SELECT 
                u.id,
                u.username,
                u.email,
                u.display_name,
                u.avatar_path,
                u.bio,
                u.status,
                u.is_admin,
                u.created_at,
                u.last_seen,
                up.theme,
                up.notifications_enabled,
                up.email_notifications
            FROM Users u
            LEFT JOIN UserPreferences up ON u.id = up.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$profile) {
            $logger->logError('/api/v1/user/profile', 'User not found', 404, $user_id);
            APIResponse::notFound('User not found');
        }
        
        // Получить статистику
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM PlayHistory WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $plays_count = (int)$stmt->fetch()['count'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM RoomMessages WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $room_messages_count = (int)$stmt->fetch()['count'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Favorites WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $favorites_count = (int)$stmt->fetch()['count'];
        
        $response = [
            'id' => (int)$profile['id'],
            'username' => $profile['username'],
            'email' => $profile['email'],
            'display_name' => $profile['display_name'],
            'avatar_path' => $profile['avatar_path'],
            'bio' => $profile['bio'],
            'status' => $profile['status'],
            'is_admin' => (bool)$profile['is_admin'],
            'created_at' => $profile['created_at'],
            'last_seen' => $profile['last_seen'],
            'theme' => $profile['theme'] ?? 'dark',
            'notifications_enabled' => (bool)($profile['notifications_enabled'] ?? true),
            'email_notifications' => (bool)($profile['email_notifications'] ?? false),
            'statistics' => [
                'plays' => $plays_count,
                'room_messages' => $room_messages_count,
                'favorites' => $favorites_count
            ]
        ];
        
        $logger->logResponse('/api/v1/user/profile', 200, $user_id);
        APIResponse::success($response, 200);
    }
    
    // === PUT: ОБНОВИТЬ ПРОФИЛЬ ===
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $logger->logValidationError('/api/v1/user/profile', ['Invalid JSON'], $user_id);
            APIResponse::error('Invalid JSON format', 400);
        }
        
        // Получить текущие данные
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE id = ?");
        $stmt->execute([$user_id]);
        $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$current_user) {
            $logger->logError('/api/v1/user/profile', 'User not found', 404, $user_id);
            APIResponse::notFound('User not found');
        }
        
        // Валидация полей
        $errors = [];
        $updates = [];
        $params = [];
        
        // Display name
        if (isset($input['display_name'])) {
            $display_name = trim($input['display_name']);
            if (empty($display_name)) {
                $errors['display_name'] = 'Display name не может быть пустым';
            } elseif (strlen($display_name) > 150) {
                $errors['display_name'] = 'Display name не может быть больше 150 символов';
            } else {
                $updates[] = 'display_name = ?';
                $params[] = $display_name;
            }
        }
        
        // Bio
        if (isset($input['bio'])) {
            $bio = trim($input['bio']);
            if (strlen($bio) > 500) {
                $errors['bio'] = 'Bio не может быть больше 500 символов';
            } else {
                $updates[] = 'bio = ?';
                $params[] = $bio ?: null;
            }
        }
        
        // Theme
        if (isset($input['theme'])) {
            $theme = $input['theme'];
            $valid_themes = ['dark', 'light', 'gothic', 'punk'];
            if (!in_array($theme, $valid_themes)) {
                $errors['theme'] = 'Invalid theme';
            } else {
                // Обновить в UserPreferences
                $stmt = $pdo->prepare("
                    INSERT INTO UserPreferences (user_id, theme, updated_at)
                    VALUES (?, ?, NOW())
                    ON DUPLICATE KEY UPDATE theme = ?, updated_at = NOW()
                ");
                $stmt->execute([$user_id, $theme, $theme]);
            }
        }
        
        // Notifications
        if (isset($input['notifications_enabled'])) {
            $notif_enabled = (bool)$input['notifications_enabled'];
            $stmt = $pdo->prepare("
                INSERT INTO UserPreferences (user_id, notifications_enabled, updated_at)
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE notifications_enabled = ?, updated_at = NOW()
            ");
            $stmt->execute([$user_id, $notif_enabled ? 1 : 0, $notif_enabled ? 1 : 0]);
        }
        
        if (!empty($errors)) {
            $logger->logValidationError('/api/v1/user/profile', $errors, $user_id);
            APIResponse::validationError($errors);
        }
        
        // Обновить основные данные пользователя
        if (!empty($updates)) {
            $updates[] = 'updated_at = NOW()';
            $params[] = $user_id;
            
            $sql = "UPDATE Users SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
        
        // Получить обновленный профиль
        $stmt = $pdo->prepare("
            SELECT 
                u.id,
                u.username,
                u.email,
                u.display_name,
                u.avatar_path,
                u.bio,
                u.status,
                u.is_admin,
                u.created_at,
                u.last_seen,
                up.theme,
                up.notifications_enabled,
                up.email_notifications
            FROM Users u
            LEFT JOIN UserPreferences up ON u.id = up.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $updated_profile = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $response = [
            'id' => (int)$updated_profile['id'],
            'username' => $updated_profile['username'],
            'email' => $updated_profile['email'],
            'display_name' => $updated_profile['display_name'],
            'avatar_path' => $updated_profile['avatar_path'],
            'bio' => $updated_profile['bio'],
            'status' => $updated_profile['status'],
            'is_admin' => (bool)$updated_profile['is_admin'],
            'created_at' => $updated_profile['created_at'],
            'last_seen' => $updated_profile['last_seen'],
            'theme' => $updated_profile['theme'] ?? 'dark',
            'notifications_enabled' => (bool)($updated_profile['notifications_enabled'] ?? true),
            'email_notifications' => (bool)($updated_profile['email_notifications'] ?? false)
        ];
        
        $logger->logResponse('/api/v1/user/profile', 200, $user_id);
        APIResponse::success($response, 200, 'Profile updated successfully');
    }
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/user/profile', $e, $user_id ?? null);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/user/profile', $e, $user_id ?? null);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}