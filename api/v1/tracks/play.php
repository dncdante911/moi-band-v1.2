<?php
/**
 * Файл: api/v1/tracks/play.php
 * API Endpoint для отметки прослушивания трека
 * POST /api/v1/tracks/play.php
 * 
 * Требует JWT токен в Authorization заголовке
 * Добавляет запись в PlayHistory
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
        $logger->logError('/api/v1/tracks/play', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['POST']);
    }
    
    // === ПОЛУЧИТЬ ДАННЫЕ ===
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $logger->logValidationError('/api/v1/tracks/play', ['Invalid JSON']);
        APIResponse::error('Invalid JSON format', 400);
    }
    
    $track_id = intval($input['track_id'] ?? 0);
    
    $logger->logRequest('/api/v1/tracks/play', 'POST', null, ['track_id' => $track_id]);
    
    // === ВАЛИДАЦИЯ ===
    if ($track_id < 1) {
        $logger->logValidationError('/api/v1/tracks/play', ['Invalid track ID']);
        APIResponse::validationError(['track_id' => 'Invalid track ID']);
    }
    
    // === ПРОВЕРИТЬ ЧТО ТРЕК СУЩЕСТВУЕТ ===
    $stmt = $pdo->prepare("
        SELECT 
            t.id,
            t.title,
            t.description,
            t.coverImagePath,
            t.fullAudioPath,
            t.duration,
            t.views,
            a.id as album_id,
            a.title as album_title
        FROM Track t
        LEFT JOIN Albums a ON t.albumId = a.id
        WHERE t.id = ?
    ");
    
    $stmt->execute([$track_id]);
    $track = $stmt->fetch(PDO::FETCH_ASSOC);
    define('FILE_BASE_PATH', '/var/www/www-root/data/www/moi-band.com.ua');

// Проверяем файлы
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
    if (!$track) {
        $logger->logError('/api/v1/tracks/play', 'Track not found', 404, null, ['track_id' => $track_id]);
        APIResponse::notFound('Track not found');
    }
    
    // === ПОЛУЧИТЬ ПОЛЬЗОВАТЕЛЯ ИЗ ТОКЕНА (опционально) ===
    $user_id = null;
    $token = $jwt->getTokenFromHeaders();
    
    if ($token) {
        $verification = $jwt->verifyToken($token);
        if ($verification['valid']) {
            $user_id = $verification['payload']['user_id'];
        } else {
            $logger->logUnauthorized('/api/v1/tracks/play', $verification['error'], null);
        }
    }
    
    // === ДОБАВИТЬ ЗАПИСЬ В PlayHistory ===
    if ($user_id) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO PlayHistory (user_id, track_id, playedAt)
                VALUES (?, ?, NOW())
            ");
            $stmt->execute([$user_id, $track_id]);
            
            $logger->logResponse('/api/v1/tracks/play', 200, $user_id);
        } catch (PDOException $e) {
            // Продолжаем даже если не удалось добавить в историю
            error_log("Failed to add play history: " . $e->getMessage());
            $logger->logException('/api/v1/tracks/play', $e, $user_id);
        }
    } else {
        // Логирование для неавторизованных пользователей
        $logger->logResponse('/api/v1/tracks/play', 200, null);
    }
    
    // === УВЕЛИЧИТЬ СЧЁТЧИК ПРОСМОТРОВ ===
    try {
        $stmt = $pdo->prepare("UPDATE Track SET views = views + 1 WHERE id = ?");
        $stmt->execute([$track_id]);
    } catch (PDOException $e) {
        error_log("Failed to update views: " . $e->getMessage());
    }
    
    // === ПОЛУЧИТЬ ОБНОВЛЕННЫЕ ДАННЫЕ ТРЕКА ===
    $stmt = $pdo->prepare("SELECT views FROM Track WHERE id = ?");
    $stmt->execute([$track_id]);
    $updated_track = $stmt->fetch();
    
    // === ОТВЕТ ===
    $response = [
        'id' => (int)$track['id'],
        'title' => $track['title'],
        'description' => $track['description'],
        'cover_image' => $track['coverImagePath'],
        'audio_url' => $track['fullAudioPath'],
        'duration' => (int)$track['duration'],
        'duration_formatted' => formatDuration((int)$track['duration']),
        'views' => (int)$updated_track['views'],
        'album' => $track['album_id'] ? [
            'id' => (int)$track['album_id'],
            'title' => $track['album_title']
        ] : null,
        'played_by_user' => $user_id ? true : false
    ];
    
    APIResponse::success($response, 200, 'Track play recorded');
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/tracks/play', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/tracks/play', $e);
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