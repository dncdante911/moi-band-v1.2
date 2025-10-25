<?php
/**
 * Файл: api/v1/news/detail.php
 * API Endpoint для получения деталей новости
 * GET /api/v1/news/detail.php?id=1
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
        $logger->logError('/api/v1/news/detail', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ID НОВОСТИ ===
    $news_id = intval($_GET['id'] ?? 0);
    
    $logger->logRequest('/api/v1/news/detail', 'GET', null, ['news_id' => $news_id]);
    
    if ($news_id < 1) {
        $logger->logValidationError('/api/v1/news/detail', ['Invalid news ID']);
        APIResponse::validationError(['id' => 'Invalid news ID']);
    }
    
    // === ПОЛУЧИТЬ НОВОСТЬ ===
    $stmt = $pdo->prepare("
        SELECT 
            id,
            title,
            content,
            category,
            image,
            created_at,
            updated_at
        FROM news
        WHERE id = ?
    ");
    
    $stmt->execute([$news_id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$news) {
        $logger->logError('/api/v1/news/detail', 'News not found', 404, null, ['news_id' => $news_id]);
        APIResponse::notFound('News not found');
    }
    
    // === ФОРМАТИРОВАНИЕ ===
    $response = [
        'id' => (int)$news['id'],
        'title' => $news['title'],
        'content' => $news['content'],
        'category' => $news['category'],
        'image' => $news['image'],
        'created_at' => substr($news['created_at'], 0, 10),
        'created_at_full' => $news['created_at'],
        'created_at_time' => substr($news['created_at'], 11, 5),
        'updated_at' => substr($news['updated_at'], 0, 10)
    ];
    
    // === ПОЛУЧИТЬ СОСЕДНИЕ НОВОСТИ ===
    // Предыдущая новость
    $stmt = $pdo->prepare("
        SELECT id, title FROM news
        WHERE created_at < ? AND category = ?
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$news['created_at'], $news['category']]);
    $prev_news = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Следующая новость
    $stmt = $pdo->prepare("
        SELECT id, title FROM news
        WHERE created_at > ? AND category = ?
        ORDER BY created_at ASC
        LIMIT 1
    ");
    $stmt->execute([$news['created_at'], $news['category']]);
    $next_news = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $response['navigation'] = [
        'prev' => $prev_news ? [
            'id' => (int)$prev_news['id'],
            'title' => $prev_news['title']
        ] : null,
        'next' => $next_news ? [
            'id' => (int)$next_news['id'],
            'title' => $next_news['title']
        ] : null
    ];
    
    $logger->logResponse('/api/v1/news/detail', 200, null);
    APIResponse::success($response, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/news/detail', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/news/detail', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}