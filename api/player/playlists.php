<?php
/**
 * Файл: api/player/playlists.php
 * API для управления плейлистами
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

$user = $auth->getCurrentUser();
$method = $_SERVER['REQUEST_METHOD'];

try {
    
    // === GET: Получить все плейлисты пользователя ===
    if ($method === 'GET') {
        $stmt = $pdo->prepare("
            SELECT id, title, description, coverImagePath, isPublic, createdAt
            FROM Playlists
            WHERE user_id = ?
            ORDER BY createdAt DESC
        ");
        $stmt->execute([$user['id']]);
        $playlists = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'playlists' => array_map(function($p) {
                return [
                    'id' => (int)$p['id'],
                    'title' => htmlspecialchars($p['title']),
                    'description' => htmlspecialchars($p['description'] ?? ''),
                    'coverImagePath' => $p['coverImagePath'],
                    'isPublic' => (bool)$p['isPublic'],
                    'createdAt' => $p['createdAt']
                ];
            }, $playlists)
        ]);
    }
    
    // === POST: Создать новый плейлист ===
    else if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $isPublic = $data['isPublic'] ?? false;
        
        if (empty($title)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Title required']);
            exit;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO Playlists (user_id, title, description, isPublic)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user['id'], $title, $description, $isPublic ? 1 : 0]);
        
        $playlistId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'playlist_id' => (int)$playlistId,
            'message' => 'Плейлист создан'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}