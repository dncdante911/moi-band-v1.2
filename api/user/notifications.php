<?php
/**
 * Файл: api/user/notifications.php
 * API для управления уведомлениями
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$user = $auth->getCurrentUser();
$data = json_decode(file_get_contents('php://input'), true);

try {
    $updates = [];
    
    if (isset($data['notifications_enabled'])) {
        $updates['notifications_enabled'] = (bool)$data['notifications_enabled'];
    }
    
    if (isset($data['email_notifications'])) {
        $updates['email_notifications'] = (bool)$data['email_notifications'];
    }
    
    if (isset($data['theme']) && in_array($data['theme'], ['dark', 'light', 'gothic', 'punk'])) {
        $updates['theme'] = $data['theme'];
    }
    
    if (isset($data['language']) && in_array($data['language'], ['ru', 'en'])) {
        $updates['language'] = $data['language'];
    }
    
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid updates provided']);
        exit;
    }
    
    // Построить SQL запрос
    $set_parts = [];
    $params = [];
    
    foreach ($updates as $key => $value) {
        $set_parts[] = "$key = ?";
        $params[] = $value;
    }
    
    $params[] = $user['id'];
    
    $sql = "UPDATE UserPreferences SET " . implode(', ', $set_parts) . " WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode([
        'success' => true,
        'message' => 'Preferences updated',
        'updates' => $updates
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}