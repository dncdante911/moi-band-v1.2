<?php
/**
 * Файл: api/v1/albums/list.php
 * API Endpoint для получения списка альбомов
 * GET /api/v1/albums/list.php?page=1&per_page=10
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
        $logger->logError('/api/v1/albums/list', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    $logger->logRequest('/api/v1/albums/list', 'GET');
    
    // === ПАГИНАЦИЯ ===
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = min(100, max(1, intval($_GET['per_page'] ?? 10)));
    $offset = ($page - 1) * $per_page;
    
    // === ПОЛУЧИТЬ ОБЩЕЕ КОЛИЧЕСТВО ===
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Albums");
    $total = $stmt->fetch()['count'];
    
    // === ПОЛУЧИТЬ АЛЬБОМЫ ===
    $stmt = $pdo->prepare("
        SELECT 
            id,
            title,
            description,
            coverImagePath,
            releaseDate,
            createdAt,
            updatedAt
        FROM Albums
        ORDER BY releaseDate DESC, createdAt DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->execute([$per_page, $offset]);
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ПОЛУЧИТЬ КОЛИЧЕСТВО ТРЕКОВ ДЛЯ КАЖДОГО АЛЬБОМА ===
    foreach ($albums as &$album) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Track WHERE albumId = ?");
        $stmt->execute([$album['id']]);
        $album['track_count'] = (int)$stmt->fetch()['count'];
        
        $album['id'] = (int)$album['id'];
        $album['releaseDate'] = $album['releaseDate'] ? substr($album['releaseDate'], 0, 10) : null;
    }
    
    $logger->logResponse('/api/v1/albums/list', 200, null);
    APIResponse::paginated($albums, $total, $page, $per_page, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/albums/list', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/albums/list', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}