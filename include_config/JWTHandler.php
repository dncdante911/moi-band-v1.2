<?php
/**
 * Файл: include_config/JWTHandler.php
 * Управление JWT токенами для API
 */

class JWTHandler {
    
    private $secret_key;
    private $algorithm = 'HS256';
    private $token_lifetime = 86400; // 24 часа
    
    public function __construct() {
        $this->secret_key = SITE_EMAIL . '_' . md5(SITE_NAME) . '_secret_key_2025';
    }
    
    /**
     * Создать JWT токен
     */
    public function generateToken($user_id, $username, $email) {
        $header = [
            'alg' => $this->algorithm,
            'typ' => 'JWT',
            'iat' => time()
        ];
        
        $payload = [
            'user_id' => $user_id,
            'username' => $username,
            'email' => $email,
            'iat' => time(),
            'exp' => time() + $this->token_lifetime,
            'nbf' => time()
        ];
        
        $header_encoded = $this->base64_url_encode(json_encode($header));
        $payload_encoded = $this->base64_url_encode(json_encode($payload));
        
        $signature = hash_hmac(
            'sha256',
            $header_encoded . '.' . $payload_encoded,
            $this->secret_key,
            true
        );
        
        $signature_encoded = $this->base64_url_encode($signature);
        
        return $header_encoded . '.' . $payload_encoded . '.' . $signature_encoded;
    }
    
    /**
     * Проверить и декодировать токен
     */
    public function verifyToken($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return [
                'valid' => false,
                'error' => 'Неверный формат токена'
            ];
        }
        
        list($header_encoded, $payload_encoded, $signature_encoded) = $parts;
        
        // Проверить подпись
        $signature = hash_hmac(
            'sha256',
            $header_encoded . '.' . $payload_encoded,
            $this->secret_key,
            true
        );
        
        $signature_check = $this->base64_url_encode($signature);
        
        if ($signature_check !== $signature_encoded) {
            return [
                'valid' => false,
                'error' => 'Неверная подпись токена'
            ];
        }
        
        // Декодировать payload
        $payload_json = $this->base64_url_decode($payload_encoded);
        $payload = json_decode($payload_json, true);
        
        if (!$payload) {
            return [
                'valid' => false,
                'error' => 'Не удалось декодировать payload'
            ];
        }
        
        // Проверить срок действия
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return [
                'valid' => false,
                'error' => 'Токен истёк'
            ];
        }
        
        // Проверить nbf (not before)
        if (isset($payload['nbf']) && $payload['nbf'] > time()) {
            return [
                'valid' => false,
                'error' => 'Токен ещё не действителен'
            ];
        }
        
        return [
            'valid' => true,
            'payload' => $payload
        ];
    }
    
    /**
     * Получить токен из заголовков
     */
    public function getTokenFromHeaders() {
        $headers = $this->getAuthorizationHeader();
        
        if (!$headers) {
            return null;
        }
        
        // Bearer token
        if (preg_match('/Bearer\s+(.+)/', $headers, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    /**
     * Получить Authorization заголовок
     */
    private function getAuthorizationHeader() {
        if (isset($_SERVER['Authorization'])) {
            return $_SERVER['Authorization'];
        }
        
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        }
        
        // Apache fix
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                return $headers['Authorization'];
            }
        }
        
        return null;
    }
    
    /**
     * Base64 URL кодирование
     */
    private function base64_url_encode($data) {
        $b64 = base64_encode($data);
        $url64 = strtr($b64, '+/', '-_');
        return rtrim($url64, '=');
    }
    
    /**
     * Base64 URL декодирование
     */
    private function base64_url_decode($data) {
        $url64 = strtr($data, '-_', '+/');
        $b64 = str_pad($url64, strlen($url64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($b64);
    }
    
    /**
     * Получить время жизни токена
     */
    public function getTokenLifetime() {
        return $this->token_lifetime;
    }
    
    /**
     * Установить время жизни токена
     */
    public function setTokenLifetime($seconds) {
        $this->token_lifetime = $seconds;
    }
}