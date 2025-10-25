<?php
/**
 * Файл: api/v1/albums/detail.php
 * API Endpoint для получения деталей альбома со всеми треками
 * GET /api/v1/albums/detail.php?album_id=1
 */

// Временно понижаем уровень ошибок, чтобы мелкие Notice/Warning не портили JSON.
// Они будут логироваться в файл, но не выводиться на экран.

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

if (defined('DEBUG_MODE') && DEBUG_MODE) {
    ini_set('display_errors', 0); 
    // Ошибки по-прежнему будут логироваться, но не будут печататься в ответ
}

$logger = new APILogger();

try {
    // === ПРОВЕРКА МЕТОДА ===
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $logger->logError('/api/v1/albums/detail', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ID АЛЬБОМА ===
    $raw_album_id = $_GET['album_id'] ?? $_GET['id'] ?? 0;
    $album_id = intval($raw_album_id); 
    
    $logger->logRequest('/api/v1/albums/detail', 'GET', null, ['album_id' => $album_id]);
    
    // Валидация ID
    if ($album_id < 1) {
        $logger->logValidationError('/api/v1/albums/detail', 'Invalid album ID', ['raw_id' => $raw_album_id]);
        APIResponse::error('Invalid album ID', 400); 
    }
    
    // === ПОЛУЧЕНИЕ ДЕТАЛЕЙ АЛЬБОМА ===
    $stmt = $pdo->prepare("SELECT * FROM Albums WHERE id = :id LIMIT 1"); 
    $stmt->execute(['id' => $album_id]);
    $album = $stmt->fetch();
    
    if (!$album) {
        $logger->logError('/api/v1/albums/detail', 'Album not found', 404, ['album_id' => $album_id]);
        APIResponse::error('Album not found', 404);
    }
    
    // === ПОЛУЧЕНИЕ ТРЕКОВ ===
    // ИСПРАВЛЕНО: Сортировка по ID, т.к. trackNumber/track_number отсутствует
   // $stmt = $pdo->prepare("SELECT * FROM Track WHERE albumId = :album_id ORDER BY id ASC"); 
   //$stmt->execute(['album_id' => $album_id]);
   // $tracks = $stmt->fetchAll();
    
$stmt = $pdo->prepare("
    SELECT 
        id,
        title,
        description,
        coverImagePath,
        fullAudioPath,
        duration,
        views,
        createdAt
    FROM Track 
    WHERE albumId = :album_id 
    ORDER BY id ASC
"); 
$stmt->execute(['album_id' => $album_id]);
$tracks = $stmt->fetchAll();

$formatted_tracks = [];
$base_url = rtrim(defined('SITE_URL') ? SITE_URL : '', '/'); 

foreach ($tracks as $track) {
    // ИСПРАВЛЕНО: Используем fullAudioPath напрямую
    $audio_path = $track['fullAudioPath'] ?? '';
    $cover_path = $track['coverImagePath'] ?? '';
    
    // Проверяем формат fullAudioPath
    if (!empty($audio_path)) {
        // Если уже полный URL
        if (strpos($audio_path, 'http') === 0) {
            $full_audio_url = $audio_path;
        }
        // Если начинается с /
        elseif (strpos($audio_path, '/') === 0) {
            $full_audio_url = $base_url . $audio_path;
        }
        // Иначе добавляем путь
        else {
            $full_audio_url = $base_url . '/public/uploads/full/' . $audio_path;
        }
    } else {
        $full_audio_url = '';
    }
    
    $formatted_tracks[] = [
        'id' => (int)$track['id'],
        'title' => $track['title'],
        'description' => $track['description'] ?: '',
        'cover_image' => !empty($cover_path) ? 
            (strpos($cover_path, 'http') === 0 ? $cover_path : $base_url . $cover_path) : 
            null,
        'audio_url' => $full_audio_url,
        'duration' => (int)$track['duration'],
        'duration_formatted' => formatDuration((int)$track['duration']),
        'views' => (int)$track['views'],
        'created_at' => substr($track['createdAt'], 0, 10)
    ];
}
    
    $album['tracks'] = $formatted_tracks;
    
    // === РАСЧЁТ ОБЩЕЙ ПРОДОЛЖИТЕЛЬНОСТИ ===
    $total_duration = array_sum(array_column($tracks, 'duration'));
    $album['total_duration'] = (int)$total_duration;
    $album['total_duration_formatted'] = formatDuration($total_duration); 
    
    $logger->logResponse('/api/v1/albums/detail', 200, null);
    APIResponse::success($album, 200);
    
} catch (PDOException $e) {
    // В случае ошибки БД, включаем полный показ ошибок, чтобы видеть, что пошло не так
    error_reporting(E_ALL); 
    $logger->logException('/api/v1/albums/detail', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    error_reporting(E_ALL);
    $logger->logException('/api/v1/albums/detail', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}

/**
 * Форматировать длительность из секунд в MM:SS или HH:MM:SS
 */
function formatDuration($seconds) {
    $seconds = (int)$seconds;
    
    if ($seconds < 3600) {
        return gmdate('i:s', $seconds);
    }
    
    return gmdate('H:i:s', $seconds);
}

?>