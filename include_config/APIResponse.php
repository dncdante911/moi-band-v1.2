<?php
/**
 * Файл: include_config/APIResponse.php
 * Стандартные API ответы в JSON формате
 */

class APIResponse {
    
    const VERSION = '1.0';
    
    /**
     * Успешный ответ
     */
    public static function success($data = null, $code = 200, $message = 'Success') {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Ошибка
     */
    public static function error($error, $code = 400, $details = null) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => $code,
            'error' => $error,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        if ($details !== null) {
            $response['details'] = $details;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Ошибка валидации
     */
    public static function validationError($errors) {
        http_response_code(422);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 422,
            'error' => 'Validation failed',
            'validation_errors' => $errors,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Не авторизован
     */
    public static function unauthorized($message = 'Unauthorized') {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 401,
            'error' => $message,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Доступ запрещен
     */
    public static function forbidden($message = 'Forbidden') {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 403,
            'error' => $message,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Не найдено
     */
    public static function notFound($message = 'Not found') {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 404,
            'error' => $message,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Метод не допущен
     */
    public static function methodNotAllowed($allowed_methods = []) {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 405,
            'error' => 'Method not allowed',
            'allowed_methods' => $allowed_methods,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Rate limit превышен
     */
    public static function rateLimitExceeded($retry_after = null) {
        http_response_code(429);
        header('Content-Type: application/json; charset=utf-8');
        
        if ($retry_after) {
            header("Retry-After: {$retry_after}");
        }
        
        $response = [
            'success' => false,
            'code' => 429,
            'error' => 'Too many requests',
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        if ($retry_after) {
            $response['retry_after'] = $retry_after;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Ошибка сервера
     */
    public static function serverError($message = 'Internal server error', $error_code = null) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'code' => 500,
            'error' => $message,
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        if ($error_code) {
            $response['error_code'] = $error_code;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Пагинированный ответ
     */
    public static function paginated($data, $total, $page, $per_page, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        
        $total_pages = ceil($total / $per_page);
        
        $response = [
            'success' => true,
            'code' => $code,
            'data' => $data,
            'pagination' => [
                'total' => (int)$total,
                'page' => (int)$page,
                'per_page' => (int)$per_page,
                'total_pages' => (int)$total_pages,
                'has_next' => $page < $total_pages,
                'has_prev' => $page > 1
            ],
            'timestamp' => date('c'),
            'version' => self::VERSION
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}