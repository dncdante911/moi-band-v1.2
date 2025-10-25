<?php
/**
 * Файл: include_config/APILogger.php
 * Логирование всех API запросов и ошибок
 */

class APILogger {
    
    private $log_dir = '/var/www/www-root/data/www/moi-band.com.ua/logs/';
    private $request_id;
    private $log_file;
    
    public function __construct() {
        $this->request_id = uniqid('req_', true);
        $this->ensureLogDirectory();
        $this->log_file = $this->log_dir . 'api_' . date('Y-m-d') . '.log';
    }
    
    /**
     * Убедиться что директория для логов существует
     */
    private function ensureLogDirectory() {
        if (!is_dir($this->log_dir)) {
            @mkdir($this->log_dir, 0777, true);
        }
        
        // Проверить права доступа
        if (!is_writable($this->log_dir)) {
            @chmod($this->log_dir, 0777);
        }
    }
    
    /**
     * Логировать успешный запрос
     */
    public function logRequest($endpoint, $method, $user_id = null, $data = []) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'REQUEST',
            'level' => 'INFO',
            'method' => $method,
            'endpoint' => $endpoint,
            'user_id' => $user_id,
            'ip_address' => $this->getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'data' => $this->sanitizeData($data),
            'memory_usage' => memory_get_usage(true),
            'response_time' => 0 // Обновится при логировании ответа
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать успешный ответ
     */
    public function logResponse($endpoint, $code, $user_id = null, $response_time = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'RESPONSE',
            'level' => $this->getLogLevel($code),
            'endpoint' => $endpoint,
            'status_code' => $code,
            'user_id' => $user_id,
            'ip_address' => $this->getClientIP(),
            'response_time_ms' => $response_time,
            'memory_usage' => memory_get_usage(true)
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать ошибку
     */
    public function logError($endpoint, $error, $code = 500, $user_id = null, $context = []) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'ERROR',
            'level' => 'ERROR',
            'endpoint' => $endpoint,
            'error' => $error,
            'error_code' => $code,
            'user_id' => $user_id,
            'ip_address' => $this->getClientIP(),
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
            'context' => $context,
            'memory_usage' => memory_get_usage(true),
            'trace' => $this->getStackTrace()
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать валидационную ошибку
     */
    public function logValidationError($endpoint, $errors, $user_id = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'VALIDATION_ERROR',
            'level' => 'WARNING',
            'endpoint' => $endpoint,
            'errors' => $errors,
            'user_id' => $user_id,
            'ip_address' => $this->getClientIP(),
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN'
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать попытку несанкционированного доступа
     */
    public function logUnauthorized($endpoint, $reason, $user_id = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'UNAUTHORIZED',
            'level' => 'WARNING',
            'endpoint' => $endpoint,
            'reason' => $reason,
            'user_id' => $user_id,
            'ip_address' => $this->getClientIP(),
            'headers' => $this->getRelevantHeaders()
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать rate limit превышение
     */
    public function logRateLimit($endpoint, $ip, $user_id = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'RATE_LIMIT',
            'level' => 'WARNING',
            'endpoint' => $endpoint,
            'ip_address' => $ip,
            'user_id' => $user_id
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Логировать исключение
     */
    public function logException($endpoint, Exception $exception, $user_id = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->request_id,
            'type' => 'EXCEPTION',
            'level' => 'ERROR',
            'endpoint' => $endpoint,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => $user_id,
            'trace' => $exception->getTraceAsString()
        ];
        
        $this->write($log_entry);
    }
    
    /**
     * Получить ID текущего запроса
     */
    public function getRequestID() {
        return $this->request_id;
    }
    
    /**
     * Записать в лог файл
     */
    private function write($entry) {
        try {
            $json = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            file_put_contents($this->log_file, $json . PHP_EOL, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            error_log("Failed to write API log: " . $e->getMessage());
        }
    }
    
    /**
     * Получить IP адрес клиента
     */
    private function getClientIP() {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        
        if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        
        if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }
        
        if (!empty($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
    
    /**
     * Определить уровень логирования по статусу
     */
    private function getLogLevel($code) {
        if ($code >= 200 && $code < 300) {
            return 'INFO';
        } elseif ($code >= 300 && $code < 400) {
            return 'INFO';
        } elseif ($code >= 400 && $code < 500) {
            return 'WARNING';
        } else {
            return 'ERROR';
        }
    }
    
    /**
     * Получить релевантные заголовки
     */
    private function getRelevantHeaders() {
        $relevant = ['Authorization', 'Content-Type', 'Accept', 'User-Agent'];
        $headers = [];
        
        foreach ($relevant as $header) {
            $key = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
            if (!empty($_SERVER[$key])) {
                $value = $_SERVER[$key];
                // Скрыть токены
                if ($header === 'Authorization') {
                    $value = 'Bearer ' . substr($value, 0, 20) . '...';
                }
                $headers[$header] = $value;
            }
        }
        
        return $headers;
    }
    
    /**
     * Получить стек вызовов
     */
    private function getStackTrace() {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $formatted = [];
        
        foreach ($trace as $index => $call) {
            $formatted[] = [
                'file' => $call['file'] ?? 'Unknown',
                'line' => $call['line'] ?? 'Unknown',
                'function' => $call['function'] ?? 'Unknown',
                'class' => $call['class'] ?? null
            ];
        }
        
        return $formatted;
    }
    
    /**
     * Очистить чувствительные данные перед логированием
     */
    private function sanitizeData($data) {
        $sensitive = ['password', 'token', 'secret', 'api_key', 'credit_card'];
        
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                $key_lower = strtolower($key);
                
                foreach ($sensitive as $sensitive_key) {
                    if (strpos($key_lower, $sensitive_key) !== false) {
                        $value = '***REDACTED***';
                        break;
                    }
                }
            }
        }
        
        return $data;
    }
}