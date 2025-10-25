<?php
/**
 * Файл: api/v1/tracks/list.php
 * API Endpoint для получения списка треков альбома
 * GET /api/v1/tracks/list.php?album_id=1&page=1&per_page=20
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
require_once '../../../include_config/APIResponse.php';
require_once '../../../include_config/APILogger.php';

$logger = new APILogger();

try {
    // === ПРОВЕРКА МЕТОДА ===
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $logger->logError('/api/v1/tracks/list', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
    $album_id = intval($_GET['album_id'] ?? 0);
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = min(100, max(1, intval($_GET['per_page'] ?? 20)));
    $offset = ($page - 1) * $per_page;
    
    $logger->logRequest('/api/v1/tracks/list', 'GET', null, [
        'album_id' => $album_id,
        'page' => $page,
        'per_page' => $per_page
    ]);
    
    // === ВАЛИДАЦИЯ ===
    if ($album_id < 1) {
        $logger->logValidationError('/api/v1/tracks/list', ['Invalid album ID']);
        APIResponse::validationError(['album_id' => 'Invalid album ID']);
    }
    
    // === ПРОВЕРИТЬ ЧТО АЛЬБОМ СУЩЕСТВУЕТ ===
    $stmt = $pdo->prepare("SELECT id, title FROM Albums WHERE id = ?");
    $stmt->execute([$album_id]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$album) {
        $logger->logError('/api/v1/tracks/list', 'Album not found', 404, null, ['album_id' => $album_id]);
        APIResponse::notFound('Album not found');
    }
    
    // === ПОЛУЧИТЬ ОБЩЕЕ КОЛИЧЕСТВО ТРЕКОВ ===
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM Track 
        WHERE albumId = ?
    ");
    $stmt->execute([$album_id]);
    $total = $stmt->fetch()['count'];
    
    // === ПОЛУЧИТЬ ТРЕКИ ===
    $stmt = $pdo->prepare("
        SELECT 
            id,
            title,
            description,
            coverImagePath,
            fullAudioPath,
            duration,
            views,
            createdAt,
            updatedAt
        FROM Track
        WHERE albumId = ?
        ORDER BY id ASC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->execute([$album_id, $per_page, $offset]);
    $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ФОРМАТИРОВАНИЕ ===
    define('FILE_BASE_PATH', '/var/www/www-root/data/www/moi-band.com.ua');
    $formatted_tracks = [];
    foreach ($tracks as $track) {
            $audioPath = ltrim($track['fullAudioPath'], '/');
    $fullAudioPath = FILE_BASE_PATH . '/' . $audioPath;
    $audioExists = file_exists($fullAudioPath);
    
    $videoExists = false;
    $videoUrl = null;
    if (!empty($track['videoPath'])) {
        $videoPath = ltrim($track['videoPath'], '/');
        $fullVideoPath = FILE_BASE_PATH . '/' . $videoPath;
        $videoExists = file_exists($fullVideoPath);
        if ($videoExists) {
            $videoUrl = $track['videoPath'];
        }
    }
        $formatted_tracks[] = [
            'id' => (int)$track['id'],
            'title' => $track['title'],
            'description' => $track['description'],
            'cover_image' => $track['coverImagePath'],
            'audio_url' => $track['fullAudioPath'],
            'duration' => (int)$track['duration'],
            'duration_formatted' => formatDuration((int)$track['duration']),
            'views' => (int)$track['views'],
            'created_at' => substr($track['createdAt'], 0, 10)
        ];
    }
    
    // === ОТВЕТ ===
    $response = [
        'album' => [
            'id' => (int)$album['id'],
            'title' => $album['title']
        ],
        'tracks' => $formatted_tracks
    ];
    
    $logger->logResponse('/api/v1/tracks/list', 200, null);
    APIResponse::paginated($response, $total, $page, $per_page, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/tracks/list', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/tracks/list', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}

/**
 * Форматировать длительность из секунд в MM:SS
 */
function formatDuration($seconds) {
    $seconds = (int)$seconds;
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    return sprintf('%02d:%02d', $minutes, $secs);
}