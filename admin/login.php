<?php
/**
 * Файл: admin/login.php
 * Страница входа ТОЛЬКО для администратора
 */

$page_css = '../assets/css/admin_style.css'; // Используем стили админки

// Подключаем конфигурацию из корня
require_once __DIR__ . '/../include_config/config.php';
require_once __DIR__ . '/../include_config/db_connect.php';
require_once __DIR__ . '/../include_config/Auth.php';

$auth = new Auth($pdo);

// Проверка: Если уже авторизован И имеет права админа, перенаправляем в админку
if ($auth->isLoggedIn() && ($_SESSION['is_admin'] ?? false) === true) {
    header('Location: index.php'); // Перенаправляем на главную админки
    exit;
}

$errors = [];
$success = false;
$redirect_to = $_GET['redirect'] ?? 'index.php'; // Целевой адрес для редиректа

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $redirect_to = $_POST['redirect_to'] ?? 'index.php';
    
    // ... (стандартная валидация)
    if (empty($username) || empty($password)) {
        $errors[] = 'Введите логин и пароль';
    }
    
    if (empty($errors)) {
        $result = $auth->login($username, $password);
        
        if ($result['success']) {
            // КРИТИЧЕСКАЯ ПРОВЕРКА: Проверяем, что пользователь - АДМИН
            if (($_SESSION['is_admin'] ?? false) === true) {
                $success = true;
                // Редирект в админку
                header('Refresh: 1; url=' . $redirect_to); 
                exit;
            } else {
                // Если вошел, но не админ - сбрасываем сессию и выводим ошибку
                $auth->logout(); // Сбрасываем сессию
                $errors[] = '❌ Доступ запрещен. Учетная запись не является административной.';
            }
        } else {
            $errors[] = $result['error'] ?? 'Неверный логин или пароль';
        }
    }
}

// Используем очень простую HTML-разметку, чтобы не зависеть от header/footer сайта
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход для администратора</title>
    <link rel="stylesheet" href="<?= $page_css ?>">
    <style>
        .auth-container { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background-color: #1a1a1a; 
        }
        .auth-card { 
            width: 100%; 
            max-width: 350px; 
            padding: 30px; 
            background: #2b2b2b; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); 
        }
        .form-group label { color: #ccc; }
        .btn-submit { background-color: #d9534f; border-color: #d43f3a; }
        .btn-submit:hover { background-color: #c9302c; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>🔒 Вход в админку</h1>
            
            <?php if ($success): ?>
                <div class="success-message">
                    ✅ Вход успешен! Перенаправление...
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p class="error-item"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php" class="auth-form">
                <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($redirect_to) ?>">
                
                <div class="form-group">
                    <label for="username">Username или Email</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-submit">🔑 Войти в админку</button>
            </form>
            
            <div class="auth-footer" style="margin-top: 15px;">
                <p><a href="/" style="color: #aaa;">← Вернуться на сайт</a></p>
            </div>
        </div>
    </div>
</body>
</html>