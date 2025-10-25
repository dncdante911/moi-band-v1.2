<?php
/**
 * Файл: api/v1/chat/rooms.php
 * API Endpoint для получения списка комнат чата
 * GET /api/v1/chat/rooms.php
 * 
 * Требует JWT токен в Authorization заголовке
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
        $logger->logError('/api/v1/chat/rooms', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ТОКЕН И ПРОВЕРИТЬ АВТОРИЗАЦИЮ ===
    $token = $jwt->getTokenFromHeaders();
    
    if (!$token) {
        $logger->logUnauthorized('/api/v1/chat/rooms', 'No token provided', null);
        APIResponse::unauthorized('No token provided');
    }
    
    $verification = $jwt->verifyToken($token);
    
    if (!$verification['valid']) {
        $logger->logUnauthorized('/api/v1/chat/rooms', $verification['error'], null);
        APIResponse::unauthorized($verification['error']);
    }
    
    $user_id = $verification['payload']['user_id'];
    
    $logger->logRequest('/api/v1/chat/rooms', 'GET', $user_id);
    
    // === ПОЛУЧИТЬ ВСЕ КОМНАТЫ ===
    $stmt = $pdo->query("
        SELECT 
            id,
            name,
            slug,
            description,
            icon,
            is_private,
            created_by,
            created_at
        FROM Rooms
        ORDER BY name ASC
    ");
    
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ПОЛУЧИТЬ КОЛИЧЕСТВО СООБЩЕНИЙ В КАЖДОЙ КОМНАТЕ ===
    $formatted_rooms = [];
    foreach ($rooms as $room) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM RoomMessages 
            WHERE room_id = ? AND is_deleted = FALSE
        ");
        $stmt->execute([$room['id']]);
        $messages_count = (int)$stmt->fetch()['count'];
        
        // Получить последнее сообщение
        $stmt = $pdo->prepare("
            SELECT rm.message, rm.created_at, u.username
            FROM RoomMessages rm
            JOIN Users u ON rm.user_id = u.id
            WHERE rm.room_id = ? AND rm.is_deleted = FALSE
            ORDER BY rm.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$room['id']]);
        $last_message = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $formatted_rooms[] = [
            'id' => (int)$room['id'],
            'name' => $room['name'],
            'slug' => $room['slug'],
            'description' => $room['description'],
            'icon' => $room['icon'],
            'is_private' => (bool)$room['is_private'],
            'messages_count' => $messages_count,
            'last_message' => $last_message ? [
                'text' => $last_message['message'],
                'username' => $last_message['username'],
                'created_at' => $last_message['created_at']
            ] : null
        ];
    }
    
    $logger->logResponse('/api/v1/chat/rooms', 200, $user_id);
    APIResponse::success($formatted_rooms, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/chat/rooms', $e, $user_id ?? null);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/chat/rooms', $e, $user_id ?? null);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}