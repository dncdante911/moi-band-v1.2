<?php
/**
 * Файл: api/v1/tracks/search.php
 * API Endpoint для поиска треков
 * GET /api/v1/tracks/search.php?q=название&page=1&per_page=20
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
        $logger->logError('/api/v1/tracks/search', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
    $query = trim($_GET['q'] ?? '');
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = min(100, max(1, intval($_GET['per_page'] ?? 20)));
    $offset = ($page - 1) * $per_page;
    
    $logger->logRequest('/api/v1/tracks/search', 'GET', null, [
        'query' => $query,
        'page' => $page,
        'per_page' => $per_page
    ]);
    
    // === ВАЛИДАЦИЯ ===
    if (empty($query)) {
        $logger->logValidationError('/api/v1/tracks/search', ['Query is required']);
        APIResponse::validationError(['q' => 'Search query is required']);
    }
    
    if (strlen($query) < 2) {
        $logger->logValidationError('/api/v1/tracks/search', ['Query too short']);
        APIResponse::validationError(['q' => 'Search query must be at least 2 characters']);
    }
    
    if (strlen($query) > 100) {
        $logger->logValidationError('/api/v1/tracks/search', ['Query too long']);
        APIResponse::validationError(['q' => 'Search query must not exceed 100 characters']);
    }
    
    // === ПОИСК ПО НАЗВАНИЮ ТРЕКА И ОПИСАНИЮ ===
    $search_term = '%' . addcslashes($query, '%_') . '%';
    
    // Получить общее количество результатов
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM Track 
        WHERE title LIKE ? OR description LIKE ?
    ");
    $stmt->execute([$search_term, $search_term]);
    $total = $stmt->fetch()['count'];
    
    // === ПОЛУЧИТЬ ТРЕКИ ===
    $stmt = $pdo->prepare("
        SELECT 
            t.id,
            t.title,
            t.description,
            t.coverImagePath,
            t.fullAudioPath,
            t.duration,
            t.views,
            t.albumId,
            a.title as album_title,
            t.createdAt
        FROM Track t
        LEFT JOIN Albums a ON t.albumId = a.id
        WHERE t.title LIKE ? OR t.description LIKE ?
        ORDER BY 
            CASE 
                WHEN t.title LIKE ? THEN 0 
                ELSE 1 
            END,
            t.views DESC,
            t.createdAt DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->execute([$search_term, $search_term, $search_term, $per_page, $offset]);
    $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ФОРМАТИРОВАНИЕ ===
    define('FILE_BASE_PATH', '/var/www/www-root/data/www/moi-band.com.ua');
    $formatted_tracks = [];
    foreach ($tracks as $track) {
         $audioPath = ltrim($track['fullAudioPath'], '/');
    $fullAudioPath = FILE_BASE_PATH . '/' . $audioPath;
    $audioExists = file_exists($fullAudioPath);
    
    $videoUrl = null;
    if (!empty($track['videoPath'])) {
        $videoPath = ltrim($track['videoPath'], '/');
        $fullVideoPath = FILE_BASE_PATH . '/' . $videoPath;
        if (file_exists($fullVideoPath)) {
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
            'album' => $track['albumId'] ? [
                'id' => (int)$track['albumId'],
                'title' => $track['album_title']
            ] : null,
            'created_at' => substr($track['createdAt'], 0, 10)
        ];
    }
    
    $logger->logResponse('/api/v1/tracks/search', 200, null);
    APIResponse::paginated($formatted_tracks, $total, $page, $per_page, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/tracks/search', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/tracks/search', $e);
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