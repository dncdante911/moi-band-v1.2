<?php
/**
 * Файл: api/v1/chat/send.php
 * API мост для отправки сообщения в чат с JWT авторизацией
 * POST /api/v1/chat/send.php
 * 
 * Переводит JWT токен в сессию и вызывает существующий endpoint
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $logger->logError('/api/v1/chat/send', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['POST']);
    }
    
    // === ПОЛУЧИТЬ ТОКЕН И ПРОВЕРИТЬ АВТОРИЗАЦИЮ ===
    $token = $jwt->getTokenFromHeaders();
    
    if (!$token) {
        $logger->logUnauthorized('/api/v1/chat/send', 'No token provided', null);
        APIResponse::unauthorized('No token provided');
    }
    
    $verification = $jwt->verifyToken($token);
    
    if (!$verification['valid']) {
        $logger->logUnauthorized('/api/v1/chat/send', $verification['error'], null);
        APIResponse::unauthorized($verification['error']);
    }
    
    $user_id = $verification['payload']['user_id'];
    
    // === ПОЛУЧИТЬ ДАННЫЕ ===
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $logger->logValidationError('/api/v1/chat/send', ['Invalid JSON'], $user_id);
        APIResponse::error('Invalid JSON format', 400);
    }
    
    $message = trim($input['message'] ?? '');
    $room_id = intval($input['room_id'] ?? 0);
    
    $logger->logRequest('/api/v1/chat/send', 'POST', $user_id, [
        'room_id' => $room_id,
        'message_length' => strlen($message)
    ]);
    
    // === ВАЛИДАЦИЯ ===
    $errors = [];
    
    if (empty($message)) {
        $errors['message'] = 'Сообщение не может быть пустым';
    } elseif (strlen($message) < 1) {
        $errors['message'] = 'Сообщение не может быть пустым';
    } elseif (strlen($message) > 5000) {
        $errors['message'] = 'Сообщение не может быть больше 5000 символов';
    }
    
    if ($room_id < 1) {
        $errors['room_id'] = 'Некорректный ID комнаты';
    }
    
    if (!empty($errors)) {
        $logger->logValidationError('/api/v1/chat/send', $errors, $user_id);
        APIResponse::validationError($errors);
    }
    
    // === ПРОВЕРИТЬ ЧТО КОМНАТА СУЩЕСТВУЕТ ===
    $stmt = $pdo->prepare("SELECT id FROM Rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    if (!$stmt->fetch()) {
        $logger->logError('/api/v1/chat/send', 'Room not found', 404, $user_id, ['room_id' => $room_id]);
        APIResponse::notFound('Room not found');
    }
    
    // === СОХРАНИТЬ СООБЩЕНИЕ В БД ===
    $stmt = $pdo->prepare("
        INSERT INTO RoomMessages (room_id, user_id, message, created_at, is_deleted)
        VALUES (?, ?, ?, NOW(), FALSE)
    ");
    
    $stmt->execute([
        $room_id,
        $user_id,
        $message
    ]);
    
    $message_id = $pdo->lastInsertId();
    
    // === ПОЛУЧИТЬ ДАННЫЕ ОТПРАВЛЕННОГО СООБЩЕНИЯ ===
    $stmt = $pdo->prepare("
        SELECT rm.id, rm.created_at, u.username, u.avatar_path
        FROM RoomMessages rm
        JOIN Users u ON rm.user_id = u.id
        WHERE rm.id = ?
    ");
    $stmt->execute([$message_id]);
    $sent_message = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $response = [
        'id' => (int)$sent_message['id'],
        'message' => htmlspecialchars($message),
        'username' => $sent_message['username'],
        'avatar_path' => $sent_message['avatar_path'],
        'created_at' => $sent_message['created_at'],
        'timestamp' => date('H:i', strtotime($sent_message['created_at']))
    ];
    
    $logger->logResponse('/api/v1/chat/send', 201, $user_id);
    APIResponse::success($response, 201, 'Message sent successfully');
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/chat/send', $e, $user_id ?? null);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/chat/send', $e, $user_id ?? null);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}