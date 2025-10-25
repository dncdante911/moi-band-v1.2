<?php
/**
 * Файл: api/v1/gallery/list.php
 * API Endpoint для получения списка фотографий галереи
 * GET /api/v1/gallery/list.php?page=1&per_page=12&category=studio
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
        $logger->logError('/api/v1/gallery/list', 'Method not allowed', 405);
        APIResponse::methodNotAllowed(['GET']);
    }
    
    // === ПОЛУЧИТЬ ПАРАМЕТРЫ ===
    $page = max(1, intval($_GET['page'] ?? 1));
    $per_page = min(100, max(1, intval($_GET['per_page'] ?? 12)));
    $category = trim($_GET['category'] ?? '');
    $offset = ($page - 1) * $per_page;
    
    $logger->logRequest('/api/v1/gallery/list', 'GET', null, [
        'page' => $page,
        'per_page' => $per_page,
        'category' => $category
    ]);
    
    // === ВАЛИДНЫЕ КАТЕГОРИИ ===
    $valid_categories = ['studio', 'concert', 'event', 'promo'];
    
    // === ПОСТРОИТЬ QUERY ===
    $where = '1=1';
    $params = [];
    
    if (!empty($category) && in_array($category, $valid_categories)) {
        $where .= ' AND category = ?';
        $params[] = $category;
    }
    
    // === ПОЛУЧИТЬ ОБЩЕЕ КОЛИЧЕСТВО ===
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM gallery WHERE " . $where);
    $stmt->execute($params);
    $total = $stmt->fetch()['count'];
    
    // === ПОЛУЧИТЬ ФОТОГРАФИИ ===
    $query = "
        SELECT 
            id,
            title,
            category,
            image_url,
            created_at,
            updated_at
        FROM gallery
        WHERE " . $where . "
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ";
    
    $stmt = $pdo->prepare($query);
    $exec_params = array_merge($params, [$per_page, $offset]);
    $stmt->execute($exec_params);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // === ФОРМАТИРОВАНИЕ ===
    $formatted_images = [];
    foreach ($images as $image) {
        $formatted_images[] = [
            'id' => (int)$image['id'],
            'title' => $image['title'],
            'category' => $image['category'],
            'image_url' => $image['image_url'],
            'thumbnail_url' => $image['image_url'], // На клиенте может быть своя логика для thumbnail
            'created_at' => substr($image['created_at'], 0, 10)
        ];
    }
    
    $logger->logResponse('/api/v1/gallery/list', 200, null);
    
    // === ОТВЕТ С ИНФОРМАЦИЕЙ О КАТЕГОРИЯХ ===
    $response_data = [
        'images' => $formatted_images,
        'categories' => [
            'studio' => 'Студия',
            'concert' => 'Концерты',
            'event' => 'События',
            'promo' => 'Промо'
        ]
    ];
    
    APIResponse::paginated($response_data, $total, $page, $per_page, 200);
    
} catch (PDOException $e) {
    $logger->logException('/api/v1/gallery/list', $e);
    APIResponse::serverError('Database error', 'DB_ERROR');
    
} catch (Exception $e) {
    $logger->logException('/api/v1/gallery/list', $e);
    APIResponse::serverError($e->getMessage(), 'UNKNOWN_ERROR');
}