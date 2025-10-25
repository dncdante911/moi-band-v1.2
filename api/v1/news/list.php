<?php
/**
 * Файл: api/v1/news/list.php
 * API Endpoint для получения списка новостей
 * GET /api/v1/news/list.php?page=1&per_page=10&category=release
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
        $logger->logError('/api/v1/news/list', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = min(100, max(1, intval($_GET['per_page'] ?? 10)));
    $category = trim($_GET['category'] ?? '');
    $offset = ($page - 1) * $per_page;
    
    $logger->logRequest('/api/v1/news/list', 'GET', null, [
        'page' => $page,
        'per_page' => $per_page,
        'category' => $category
    ]);
    
    // === ВАЛИДНЫЕ КАТЕГОРИИ ===
    $valid_categories = ['release', 'event', 'update'];
    
    // === ПОСТРОИТЬ QUERY ===
    $where = '1=1';
    $params = [];
    
    if (!empty($category) && in_array($category, $valid_categories)) {
        $where .= ' AND category = ?';
        $params[] = $category;
    }
    
    // === ПОЛУЧИТЬ ОБЩЕЕ КОЛИЧЕСТВО ===
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM news WHERE " . $where);
    $stmt->execute($params);
    $total = $stmt->fetch()['count'];
    
    // === ПОЛУЧИТЬ НОВОСТИ ===
    $query = "
        SELECT 
            id,
            title,
            content,
            category,
            image,
            created_at,
            updated_at
        FROM news
        WHERE " . $where . "
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ";
    
    $stmt = $pdo->prepare($query);
    $exec_params = array_merge($params, [$per_page, $offset]);
    $stmt->execute($exec_params);
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ФОРМАТИРОВАНИЕ ===
    $formatted_news = [];
    foreach ($news as $item) {
        $content_preview = substr($item['content'], 0, 200);
        if (strlen($item['content']) > 200) {
            $content_preview .= '...';
        }
        
        $formatted_news[] = [
            'id' => (int)$item['id'],
            'title' => $item['title'],
            'content_preview' => htmlspecialchars($content_preview),
            'category' => $item['category'],
            'image' => $item['image'],
            'created_at' => substr($item['created_at'], 0, 10),
            'created_at_time' => substr($item['created_at'], 11, 5)
        ];
    }
    
    $logger->logResponse('/api/v1/news/list', 200, null);
    APIResponse::paginated($formatted_news, $total, $page, $per_page, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/news/list', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/news/list', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}