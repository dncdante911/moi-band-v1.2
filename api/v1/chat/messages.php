<?php
/**
 * Файл: api/v1/chat/messages.php
 * API мост для получения сообщений чата с JWT авторизацией
 * GET /api/v1/chat/messages.php?room_id=9&limit=50&offset=0
 * 
 * Переводит JWT токен в сессию и вызывает существующий endpoint
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
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
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $logger->logError('/api/v1/chat/messages', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ТОКЕН И ПРОВЕРИТЬ АВТОРИЗАЦИЮ ===
    $token = $jwt->getTokenFromHeaders();
    
    if (!$token) {
        $logger->logUnauthorized('/api/v1/chat/messages', 'No token provided', null);
        APIResponse::unauthorized('No token provided');
    }
    
    $verification = $jwt->verifyToken($token);
    
    if (!$verification['valid']) {
        $logger->logUnauthorized('/api/v1/chat/messages', $verification['error'], null);
        APIResponse::unauthorized($verification['error']);
    }
    
    $user_id = $verification['payload']['user_id'];
    
    // === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
    $room_id = intval($_GET['room_id'] ?? 0);
    $limit = min(100, max(1, intval($_GET['limit'] ?? 50)));
    $offset = max(0, intval($_GET['offset'] ?? 0));
    
    $logger->logRequest('/api/v1/chat/messages', 'GET', $user_id, [
        'room_id' => $room_id,
        'limit' => $limit,
        'offset' => $offset
    ]);
    
    // === ВАЛИДАЦИЯ ===
    if ($room_id < 1) {
        $logger->logValidationError('/api/v1/chat/messages', ['Invalid room ID'], $user_id);
        APIResponse::validationError(['room_id' => 'Invalid room ID']);
    }
    
    // === ПРОВЕРИТЬ ЧТО КОМНАТА СУЩЕСТВУЕТ ===
    $stmt = $pdo->prepare("SELECT id, name FROM Rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$room) {
        $logger->logError('/api/v1/chat/messages', 'Room not found', 404, $user_id, ['room_id' => $room_id]);
        APIResponse::notFound('Room not found');
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
    $messages = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    // === ФОРМАТИРОВАНИЕ ===
    $formatted_messages = [];
    foreach ($messages as $msg) {
        $formatted_messages[] = [
            'id' => (int)$msg['id'],
            'user_id' => (int)$msg['user_id'],
            'username' => $msg['username'],
            'avatar_path' => $msg['avatar_path'],
            'message' => htmlspecialchars($msg['message']),
            'created_at' => $msg['created_at'],
            'timestamp' => date('H:i', strtotime($msg['created_at'])),
            'is_edited' => (bool)$msg['is_edited'],
            'is_own_message' => (int)$msg['user_id'] === $user_id
        ];
    }
    
    $response = [
        'room' => [
            'id' => (int)$room['id'],
            'name' => $room['name']
        ],
        'messages' => $formatted_messages,
        'total' => count($formatted_messages)
    ];
    
    $logger->logResponse('/api/v1/chat/messages', 200, $user_id);
    APIResponse::success($response, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/chat/messages', $e, $user_id ?? null);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/chat/messages', $e, $user_id ?? null);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}