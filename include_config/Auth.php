<?php
class Auth {
    private $pdo;
    private $session_lifetime = 3600;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function register($username, $email, $password, $display_name = null) {
        $errors = [];
        
        // Валидация
        if (strlen($username) < 3 || strlen($username) > 100) {
            $errors[] = 'Username должен быть от 3 до 100 символов';
        }
        
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
            $errors[] = 'Username может содержать только буквы, цифры, подчеркивание и дефис';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Некорректный email';
        }
        
        if (strlen($password) < 8) {
            $errors[] = 'Пароль должен быть минимум 8 символов';
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Проверка существования
        $stmt = $this->pdo->prepare("SELECT id FROM Users WHERE username = ? OR email = ?");
        $stmt->execute([strtolower($username), strtolower($email)]);
        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Username или email уже зарегистрированы']];
        }
        
        // Создание пользователя
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $verification_token = bin2hex(random_bytes(32));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO Users (username, email, password_hash, display_name, verification_token, verification_expires) 
            VALUES (?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 24 HOUR))
        ");
        $stmt->execute([
            strtolower($username), 
            strtolower($email), 
            $hash,
            $display_name ?? $username,
            $verification_token
        ]);
        
        $user_id = $this->pdo->lastInsertId();
        
        // Создать запись предпочтений
        $stmt = $this->pdo->prepare("INSERT INTO UserPreferences (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'user_id' => $user_id, 'verification_token' => $verification_token];
    }
    
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("
            SELECT id, username, password_hash, is_banned, is_verified 
            FROM Users 
            WHERE (username = ? OR email = ?) AND is_banned = FALSE
        ");
        $stmt->execute([strtolower($username), strtolower($username)]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Неверные учетные данные'];
        }
        
        if (!$user['is_verified']) {
            return ['success' => false, 'error' => 'Email не подтвержден'];
        }
        
        $this->setSession($user['id']);
        $this->updateLastSeen($user['id']);
        $this->updateStatus($user['id'], 'online');
        
        return ['success' => true, 'user_id' => $user['id']];
    }
    
    public function setSession($user_id) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login_time'] = time();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               (time() - $_SESSION['login_time']) < SESSION_LIFETIME;
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) return null;
        
        $stmt = $this->pdo->prepare("
            SELECT id, username, display_name, email, avatar_path, is_admin, status 
            FROM Users 
            WHERE id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->updateStatus($_SESSION['user_id'], 'offline');
        }
        session_destroy();
    }
    
    public function updateStatus($user_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE Users SET status = ? WHERE id = ?");
        $stmt->execute([$status, $user_id]);
    }
    
    public function updateLastSeen($user_id) {
        $stmt = $this->pdo->prepare("UPDATE Users SET last_seen = NOW() WHERE id = ?");
        $stmt->execute([$user_id]);
    }
    
    public function changePassword($user_id, $old_password, $new_password) {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM Users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($old_password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Неверный старый пароль'];
        }
        
        if (strlen($new_password) < 8) {
            return ['success' => false, 'error' => 'Новый пароль должен быть минимум 8 символов'];
        }
        
        $new_hash = password_hash($new_password, PASSWORD_ARGON2ID);
        $stmt = $this->pdo->prepare("UPDATE Users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$new_hash, $user_id]);
        
        return ['success' => true];
    }
}