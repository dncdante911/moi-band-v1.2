<?php
/**
 * Файл: api/player/lyrics.php
 * API для получения текста песни
 */

header('Content-Type: application/json');

require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';

$trackId = intval($_GET['track_id'] ?? 0);

if ($trackId < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Track ID required']);
    exit;
}

try {
    // Сначала проверяем в таблице SongLyrics
    $stmt = $pdo->prepare("
        SELECT lyrics FROM SongLyrics WHERE track_id = ?
    ");
    $stmt->execute([$trackId]);
    $result = $stmt->fetch();
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'lyrics' => $result['lyrics'],
            'source' => 'database'
        ]);
    } else {
        // Если текста нет, возвращаем пустой ответ
        echo json_encode([
            'success' => true,
            'lyrics' => null,
            'source' => 'none'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}