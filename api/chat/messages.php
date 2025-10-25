<?php
/**
 * Файл: api/chat/messages.php
 * API для получения сообщений из комнаты
 */

header('Content-Type: application/json');

require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';
require_once '../../include_config/Auth.php';

$auth = new Auth($pdo);

// === ПРОВЕРКА АВТОРИЗАЦИИ ===
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

// === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
$room_id = intval($_GET['room_id'] ?? 0);
$limit = intval($_GET['limit'] ?? 50);
$offset = intval($_GET['offset'] ?? 0);

if ($limit < 1 || $limit > 100) $limit = 50;
if ($offset < 0) $offset = 0;

if ($room_id < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Некорректный ID комнаты']);
    exit;
}

// === ПРОВЕРКА, ЧТО КОМНАТА СУЩЕСТВУЕТ ===
$stmt = $pdo->prepare("SELECT id, name FROM Rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Комната не найдена']);
    exit;
}

// === ПОЛУЧИТЬ СООБЩЕНИЯ ===
$stmt = $pdo->prepare("
    SELECT 
        rm.id,
        rm.user_id,
        rm.message,
        rm.created_at,
        rm.is_edited,
        u.username,
        u.avatar_path
    FROM RoomMessages rm
    JOIN Users u ON rm.user_id = u.id
    WHERE rm.room_id = ? AND rm.is_deleted = FALSE
    ORDER BY rm.created_at DESC
    LIMIT ? OFFSET ?
");

$stmt->execute([$room_id, $limit, $offset]);
$messages = array_reverse($stmt->fetchAll());

// === ВЕРНУТЬ РЕЗУЛЬТАТ ===
echo json_encode([
    'success' => true,
    'room' => [
        'id' => (int)$room['id'],
        'name' => $room['name']
    ],
    'messages' => array_map(function($msg) {
        return [
            'id' => (int)$msg['id'],
            'user_id' => (int)$msg['user_id'],
            'username' => $msg['username'],
            'avatar_path' => $msg['avatar_path'],
            'message' => htmlspecialchars($msg['message']),
            'created_at' => $msg['created_at'],
            'timestamp' => date('H:i', strtotime($msg['created_at'])),
            'is_edited' => (bool)$msg['is_edited']
        ];
    }, $messages),
    'total' => count($messages)
]);