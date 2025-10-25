<?php
/**
 * Файл: api/user/online.php
 * API для получения списка онлайн пользователей
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

// === ПОЛУЧИТЬ ОНЛАЙН ПОЛЬЗОВАТЕЛЕЙ ===
// Считаем онлайн тех, кто был активен в последние 5 минут
$stmt = $pdo->prepare("
    SELECT id, username, avatar_path, status
    FROM Users
    WHERE status = 'online'
    AND last_seen > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    AND is_banned = FALSE
    ORDER BY username ASC
    LIMIT 50
");
$stmt->execute();
$users = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'count' => count($users),
    'users' => array_map(function($user) {
        return [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'avatar_path' => $user['avatar_path'],
            'status' => $user['status'],
            'online' => $user['status'] === 'online'
        ];
    }, $users)
]);