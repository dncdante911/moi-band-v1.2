<?php
/**
 * Файл: api/player/add-to-playlist.php
 * API для добавления трека в плейлист
 */

header('Content-Type: application/json');

require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';
require_once '../../include_config/Auth.php';

$auth = new Auth($pdo);

// === ПРОВЕРКА АВТОРИЗАЦИИ ===
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST only']);
    exit;
}

$user = $auth->getCurrentUser();
$data = json_decode(file_get_contents('php://input'), true);

$trackId = intval($data['track_id'] ?? 0);
$playlistId = intval($data['playlist_id'] ?? 0);

if ($trackId < 1 || $playlistId < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid IDs']);
    exit;
}

try {
    // Проверяем, что плейлист принадлежит пользователю
    $stmt = $pdo->prepare("SELECT id FROM Playlists WHERE id = ? AND user_id = ?");
    $stmt->execute([$playlistId, $user['id']]);
    
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Access denied']);
        exit;
    }
    
    // Получаем максимальную позицию
    $stmt = $pdo->prepare("
        SELECT MAX(position) as maxPos FROM PlaylistTracks WHERE playlist_id = ?
    ");
    $stmt->execute([$playlistId]);
    $result = $stmt->fetch();
    $nextPos = ($result['maxPos'] ?? 0) + 1;
    
    // Добавляем трек
    $stmt = $pdo->prepare("
        INSERT INTO PlaylistTracks (playlist_id, track_id, position)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE position = position
    ");
    $stmt->execute([$playlistId, $trackId, $nextPos]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Трек добавлен в плейлист'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}