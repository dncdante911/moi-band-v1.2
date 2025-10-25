<?php
/**
 * Файл: pages/auth/register.php
 * Страница регистрации
 */

$page_css = '/assets/css/auth.css';
require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';
require_once '../../include_config/Auth.php';

$auth = new Auth($pdo);

// Если уже авторизован - редирект
if ($auth->isLoggedIn()) {
    header('Location: /');
    exit;
}

$errors = [];
$success = false;
$username = '';
$email = '';
$display_name = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $display_name = trim($_POST['display_name'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');
    $agree_terms = isset($_POST['agree_terms']);
    
    // Валидация
    if (empty($username)) {
        $errors[] = 'Введите username';
    }
    
    if (empty($email)) {
        $errors[] = 'Введите email';
    }
    
    if (empty($password)) {
        $errors[] = 'Введите пароль';
    }
    
    if ($password !== $password_confirm) {
        $errors[] = 'Пароли не совпадают';
    }
    
    if (!$agree_terms) {
        $errors[] = 'Вы должны согласиться с условиями использования';
    }
    
    if (empty($errors)) {
        $result = $auth->register($username, $email, $password, $display_name);
        
        if ($result['success']) {
            $success = true;
            // Автоматический вход
            $auth->setSession($result['user_id']);
            $auth->updateStatus($result['user_id'], 'online');
            
            // Редирект на главную через 2 секунды
            header('Refresh: 2; url=/');
        } else {
            $errors = array_merge($errors, $result['errors'] ?? []);
        }
    }
}

require_once '../../include_config/header.php';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h1>✍️ Создать аккаунт</h1>
            <p class="auth-subtitle">Присоединитесь к нашему сообществу Metal-фанов</p>
            
            <?php if ($success): ?>
                <div class="success-message">
                    ✅ Регистрация успешна! Добро пожаловать! 🎸
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
                    <label for="username">Username *</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="3-100 символов, буквы, цифры, _ -"
                        value="<?= htmlspecialchars($username) ?>"
                        required
                        autofocus
                    >
                    <small>Используется для входа и отображения в чате</small>
                </div>
                
                <div class="form-group">
                    <label for="display_name">Отображаемое имя</label>
                    <input 
                        type="text" 
                        id="display_name" 
                        name="display_name" 
                        placeholder="Ваше имя (опционально)"
                        value="<?= htmlspecialchars($display_name) ?>"
                    >
                    <small>Как вас будут видеть другие пользователи</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="your@email.com"
                        value="<?= htmlspecialchars($email) ?>"
                        required
                    >
                    <small>Используется для восстановления пароля</small>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Минимум 8 символов"
                        required
                    >
                    <small>Используйте букв, цифры и спецсимволы</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Подтвердите пароль *</label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        placeholder="Повторите пароль"
                        required
                    >
                </div>
                
                <div class="form-checkbox">
                    <label>
                        <input type="checkbox" name="agree_terms" required>
                        Я согласен с <a href="/pages/terms.php" target="_blank">условиями использования</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-submit">🎸 Зарегистрироваться</button>
            </form>
            
            <div class="auth-footer">
                <p>Уже есть аккаунт? <a href="/pages/auth/login.php">Войти</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../include_config/footer.php'; ?>