<?php
/**
 * Файл: pages/auth/login.php
 * Страница входа пользователя
 */

$page_css = '/assets/css/auth.css';
require_once __DIR__ . '/../../include_config/config.php';
require_once __DIR__ . '/../../include_config/db_connect.php';
require_once __DIR__ . '/../../include_config/Auth.php';

$auth = new Auth($pdo);

// Если уже авторизован - редирект
if ($auth->isLoggedIn()) {
    header('Location: /');
    exit;
}

$errors = [];
$success = false;

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username)) {
        $errors[] = 'Введите username или email';
    }
    
    if (empty($password)) {
        $errors[] = 'Введите пароль';
    }
    
    if (empty($errors)) {
        $result = $auth->login($username, $password);
        
        if ($result['success']) {
            $success = true;
            // Редирект на главную через 1 секунду
            header('Refresh: 1; url=/');
        } else {
            $errors[] = $result['error'] ?? 'Ошибка входа';
        }
    }
}

require_once '../../include_config/header.php';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h1>🔐 Вход в аккаунт</h1>
            <p class="auth-subtitle">Добро пожаловать в Master of Illusion</p>
            
            <?php if ($success): ?>
                <div class="success-message">
                    ✅ Вход успешен! Перенаправление...
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p class="error-item">❌ <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="username">Username или Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Введите username или email"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Введите пароль"
                        required
                    >
                </div>
                
                <div class="form-remember">
                    <label>
                        <input type="checkbox" name="remember"> Запомнить меня
                    </label>
                    <a href="/pages/auth/forgot-password.php">Забыли пароль?</a>
                </div>
                
                <button type="submit" class="btn-submit">📤 Войти</button>
            </form>
            
            <div class="auth-footer">
                <p>Нет аккаунта? <a href="/pages/auth/register.php">Зарегистрируйтесь</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../include_config/footer.php'; ?>