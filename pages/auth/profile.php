<?php
/**
 * Файл: pages/auth/profile.php
 * Профиль пользователя
 */

$page_css = '/assets/css/auth.css';
require_once __DIR__ . '/../../include_config/config.php';
require_once __DIR__ . '/../../include_config/db_connect.php';
require_once __DIR__ . '/../../include_config/Auth.php';

$auth = new Auth($pdo);

// Проверка авторизации
if (!$auth->isLoggedIn()) {
    header('Location: /pages/auth/login.php');
    exit;
}

$user = $auth->getCurrentUser();
$errors = [];
$success = false;

// Обработка изменения данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // === ИЗМЕНИТЬ ПРОФИЛЬ ===
    if ($action === 'update_profile') {
        $display_name = trim($_POST['display_name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        
        if (empty($display_name)) {
            $errors[] = 'Введите отображаемое имя';
        }
        
        if (strlen($bio) > 500) {
            $errors[] = 'Описание слишком длинное (максимум 500 символов)';
        }
        
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE Users SET display_name = ?, bio = ? WHERE id = ?");
                $stmt->execute([$display_name, $bio, $user['id']]);
                
                $success = 'Профиль обновлен успешно!';
                $user = $auth->getCurrentUser(); // Обновить данные
                
            } catch (Exception $e) {
                $errors[] = 'Ошибка при обновлении профиля';
            }
        }
    }
    
    // === ИЗМЕНИТЬ ПАРОЛЬ ===
    else if ($action === 'change_password') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $new_password_confirm = $_POST['new_password_confirm'] ?? '';
        
        if (empty($old_password)) {
            $errors[] = 'Введите текущий пароль';
        }
        
        if (empty($new_password)) {
            $errors[] = 'Введите новый пароль';
        }
        
        if ($new_password !== $new_password_confirm) {
            $errors[] = 'Новые пароли не совпадают';
        }
        
        if (empty($errors)) {
            $result = $auth->changePassword($user['id'], $old_password, $new_password);
            
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $errors[] = $result['error'];
            }
        }
    }
    
    // === ИЗМЕНИТЬ ТЕМУ ===
    else if ($action === 'change_theme') {
        $theme = $_POST['theme'] ?? 'dark';
        
        if (!in_array($theme, ['dark', 'light', 'gothic', 'punk'])) {
            $theme = 'dark';
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE UserPreferences SET theme = ? WHERE user_id = ?");
            $stmt->execute([$theme, $user['id']]);
            
            $success = 'Тема изменена успешно!';
            $_COOKIE['user_theme'] = $theme;
            
        } catch (Exception $e) {
            $errors[] = 'Ошибка при изменении темы';
        }
    }
}

// Получить предпочтения
$stmt = $pdo->prepare("SELECT * FROM UserPreferences WHERE user_id = ?");
$stmt->execute([$user['id']]);
$prefs = $stmt->fetch();

require_once '../../include_config/header.php';
?>

<div class="container">
    <div class="profile-container">
        <h1>👤 Мой профиль</h1>
        
        <!-- Таб меню -->
        <div class="profile-tabs">
            <button class="tab-btn active" onclick="showTab('info')">Информация</button>
            <button class="tab-btn" onclick="showTab('security')">Безопасность</button>
            <button class="tab-btn" onclick="showTab('settings')">Настройки</button>
        </div>
        
        <?php if ($success): ?>
            <div class="success-message">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <p class="error-item">❌ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- ТАБ: ИНФОРМАЦИЯ -->
        <div id="info" class="tab-content active">
            <div class="profile-card">
                <h2>Основная информация</h2>
                
                <div class="profile-item">
                    <strong>Username:</strong> 
                    <span><?= htmlspecialchars($user['username']) ?></span>
                </div>
                
                <div class="profile-item">
                    <strong>Email:</strong> 
                    <span><?= htmlspecialchars($user['email']) ?></span>
                </div>
                
                <div class="profile-item">
                    <strong>Статус:</strong> 
                    <span class="status-badge status-<?= $user['status'] ?>">
                        🟢 <?= ucfirst($user['status']) ?>
                    </span>
                </div>
                
                <div class="profile-item">
                    <strong>Дата создания:</strong> 
                    <span><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></span>
                </div>
                
                <div class="profile-item">
                    <strong>Последний визит:</strong> 
                    <span><?= $user['last_seen'] ? date('d.m.Y H:i', strtotime($user['last_seen'])) : 'Никогда' ?></span>
                </div>
                
                <hr>
                
                <h3>Редактировать профиль</h3>
                
                <form method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="display_name">Отображаемое имя</label>
                        <input 
                            type="text" 
                            id="display_name" 
                            name="display_name" 
                            value="<?= htmlspecialchars($user['display_name']) ?>"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">О себе (макс. 500 символов)</label>
                        <textarea 
                            id="bio" 
                            name="bio" 
                            rows="4"
                            maxlength="500"
                        ><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">💾 Сохранить</button>
                </form>
            </div>
        </div>
        
        <!-- ТАБ: БЕЗОПАСНОСТЬ -->
        <div id="security" class="tab-content">
            <div class="profile-card">
                <h2>🔒 Безопасность</h2>
                
                <h3>Изменить пароль</h3>
                
                <form method="POST" class="profile-form">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="old_password">Текущий пароль</label>
                        <input 
                            type="password" 
                            id="old_password" 
                            name="old_password" 
                            placeholder="Введите текущий пароль"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Новый пароль</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            placeholder="Минимум 8 символов"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirm">Подтвердите новый пароль</label>
                        <input 
                            type="password" 
                            id="new_password_confirm" 
                            name="new_password_confirm" 
                            placeholder="Повторите новый пароль"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn-submit">🔄 Изменить пароль</button>
                </form>
            </div>
        </div>
        
        <!-- ТАБ: НАСТРОЙКИ -->
        <div id="settings" class="tab-content">
            <div class="profile-card">
                <h2>⚙️ Настройки</h2>
                
                <h3>Внешний вид</h3>
                
                <form method="POST" class="profile-form">
                    <input type="hidden" name="action" value="change_theme">
                    
                    <div class="form-group">
                        <label for="theme">Выберите тему</label>
                        <select id="theme" name="theme">
                            <option value="dark" <?= ($prefs['theme'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>
                                🌙 Тёмная (Power Metal)
                            </option>
                            <option value="light" <?= ($prefs['theme'] ?? 'dark') === 'light' ? 'selected' : '' ?>>
                                ☀️ Светлая
                            </option>
                            <option value="gothic" <?= ($prefs['theme'] ?? 'dark') === 'gothic' ? 'selected' : '' ?>>
                                🦇 Готическая (Gothic Metal)
                            </option>
                            <option value="punk" <?= ($prefs['theme'] ?? 'dark') === 'punk' ? 'selected' : '' ?>>
                                🤘 Панк (Punk Rock)
                            </option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">🎨 Применить тему</button>
                </form>
                
                <hr>
                
                <h3>Уведомления</h3>
                
                <div class="settings-item">
                    <label>
                        <input type="checkbox" 
                            <?= ($prefs['notifications_enabled'] ?? true) ? 'checked' : '' ?>
                            onchange="updateNotifications(this)"
                        >
                        Включить уведомления в чате
                    </label>
                </div>
                
                <div class="settings-item">
                    <label>
                        <input type="checkbox" 
                            <?= ($prefs['email_notifications'] ?? false) ? 'checked' : '' ?>
                            onchange="updateEmailNotifications(this)"
                        >
                        Письма о новых сообщениях
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Скрыть все табы
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Удалить активный класс с кнопок
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Показать нужный таб
    document.getElementById(tabName).classList.add('active');
    
    // Сделать нужную кнопку активной
    event.target.classList.add('active');
}

function updateNotifications(checkbox) {
    fetch('/api/user/notifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            notifications_enabled: checkbox.checked
        })
    });
}

function updateEmailNotifications(checkbox) {
    fetch('/api/user/notifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email_notifications: checkbox.checked
        })
    });
}
</script>

<?php require_once '../../include_config/footer.php'; ?>