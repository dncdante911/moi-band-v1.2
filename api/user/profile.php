<?php
/**
 * Файл: api/user/profile.php
 * API для получения информации о профиле
 */

header('Content-Type: application/json');

require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';
require_once '../../include_config/Auth.php';

$auth = new Auth($pdo);

// === ПРОВЕРКА АВТОРИЗАЦИИ ===
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user = $auth->getCurrentUser();

// === ПОЛУЧИТЬ ПОЛНУЮ ИНФОРМАЦИЮ ===
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
$stmt->execute([$user['id']]);
$profile = $stmt->fetch();

if (!$profile) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

// === ПОЛУЧИТЬ СТАТИСТИКУ ===
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ChatMessages WHERE user_id = ?");
$stmt->execute([$user['id']]);
$messages_count = $stmt->fetch()['count'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) as count FROM RoomMessages WHERE user_id = ?
");
$stmt->execute([$user['id']]);
$room_messages_count = $stmt->fetch()['count'];

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Ratings WHERE user_id = ?");
$stmt->execute([$user['id']]);
$ratings_count = $stmt->fetch()['count'];

// === ВЕРНУТЬ РЕЗУЛЬТАТ ===
echo json_encode([
    'success' => true,
    'profile' => [
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
    ],
    'statistics' => [
        'messages' => (int)$messages_count,
        'room_messages' => (int)$room_messages_count,
        'ratings' => (int)$ratings_count,
    ]
]);