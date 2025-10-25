<?php
/**
 * Файл: api/chat/send.php
 * API для отправки сообщения в чат
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

// === ПРОВЕРКА МЕТОДА ===
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не допущен']);
    exit;
}

// === ПОЛУЧИТЬ ДАННЫЕ ===
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Некорректный JSON']);
    exit;
}

$message = trim($input['message'] ?? '');
$room_id = intval($input['room_id'] ?? 0);
$user = $auth->getCurrentUser();

// === ВАЛИДАЦИЯ ===
if (empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Сообщение пусто']);
    exit;
}

if (strlen($message) < 1 || strlen($message) > 5000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Длина сообщения должна быть от 1 до 5000 символов']);
    exit;
}

if ($room_id < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Некорректный ID комнаты']);
    exit;
}

// === ПРОВЕРКА, ЧТО КОМНАТА СУЩЕСТВУЕТ ===
$stmt = $pdo->prepare("SELECT id FROM Rooms WHERE id = ?");
$stmt->execute([$room_id]);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Комната не найдена']);
    exit;
}

// === СОХРАНИТЬ СООБЩЕНИЕ В БД ===
try {
    $stmt = $pdo->prepare("
        INSERT INTO RoomMessages (room_id, user_id, message, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $room_id,
        $user['id'],
        $message
    ]);
    
    $message_id = $pdo->lastInsertId();
    
    // === ВЕРНУТЬ УСПЕХ ===
    echo json_encode([
        'success' => true,
        'message_id' => (int)$message_id,
        'message' => htmlspecialchars($message),
        'username' => $user['username'],
        'timestamp' => date('H:i'),
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка при сохранении: ' . $e->getMessage()
    ]);
}