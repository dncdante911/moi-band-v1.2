<?php
/**
 * Скрипт для создания администратора
 * Использование: http://ваш-сайт.com/setup/create-admin.php
 */

require_once '../include_config/env.php';
require_once '../include_config/db_connect.php';

// ===== КОНФИГУРАЦИЯ =====
$admin_username = 'admin';
$admin_email = 'admin@lovix.top';
$admin_password = 'admin123';  // ИЗМЕНИТЕ ЭТО!
$admin_display_name = 'Administrator';

// ===== ПРОВЕРКА ЗАПРОСА =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Создание администратора</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #1a1a1a;
                color: #e0e0e0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #2a2a2a;
                padding: 40px;
                border-radius: 8px;
                border: 2px solid #FFD700;
                max-width: 500px;
                box-shadow: 0 0 20px rgba(255,215,0,0.3);
            }
            h1 {
                color: #FFD700;
                margin-top: 0;
                text-align: center;
            }
            .form-group {
                margin-bottom: 20px;
            }
            label {
                display: block;
                margin-bottom: 8px;
                color: #FFD700;
                font-weight: bold;
            }
            input, textarea {
                width: 100%;
                padding: 10px;
                background: #111;
                border: 1px solid #FFD700;
                color: #e0e0e0;
                border-radius: 4px;
                box-sizing: border-box;
                font-family: inherit;
            }
            input:focus {
                outline: none;
                box-shadow: 0 0 10px rgba(255,215,0,0.5);
            }
            button {
                width: 100%;
                padding: 12px;
                background: #FFD700;
                color: #000;
                border: none;
                border-radius: 4px;
                font-weight: bold;
                cursor: pointer;
                font-size: 1rem;
                transition: all 0.3s;
            }
            button:hover {
                background: #FFE135;
                box-shadow: 0 0 15px rgba(255,215,0,0.6);
            }
            .warning {
                background: #8B0000;
                border: 1px solid #ff3333;
                color: #fff;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
            }
            .info {
                background: rgba(65,105,225,0.2);
                border: 1px solid #4169E1;
                color: #63b3ed;
                padding: 15px;
                border-radius: 4px;
                margin-top: 20px;
                font-size: 0.9rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>⚙️ Создание администратора</h1>
            
            <div class="warning">
                ⚠️ ВАЖНО: После создания удалите этот скрипт!<br>
                Изменяйте пароль после первого входа.
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($admin_username) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin_email) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" value="<?= htmlspecialchars($admin_password) ?>" required>
                    <small style="color: #aaa;">Минимум 8 символов</small>
                </div>
                
                <div class="form-group">
                    <label for="display_name">Отображаемое имя:</label>
                    <input type="text" id="display_name" name="display_name" value="<?= htmlspecialchars($admin_display_name) ?>" required>
                </div>
                
                <button type="submit">✅ Создать администратора</button>
            </form>
            
            <div class="info">
                📝 Данные будут сохранены в базе данных. Используйте эти учетные данные для первого входа.
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// ===== ОБРАБОТКА ФОРМЫ =====
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$display_name = trim($_POST['display_name'] ?? '');

$errors = [];

// Валидация
if (strlen($username) < 3) {
    $errors[] = 'Username должен быть минимум 3 символа';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email';
}

if (strlen($password) < 8) {
    $errors[] = 'Пароль должен быть минимум 8 символов';
}

if (strlen($display_name) < 2) {
    $errors[] = 'Отображаемое имя должно быть минимум 2 символа';
}

// Если есть ошибки
if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Ошибка</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #1a1a1a;
                color: #e0e0e0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #2a2a2a;
                padding: 40px;
                border-radius: 8px;
                border: 2px solid #8B0000;
                max-width: 500px;
            }
            h1 {
                color: #ff6666;
            }
            ul {
                list-style: none;
                padding: 0;
            }
            li {
                padding: 8px;
                background: rgba(139,0,0,0.2);
                border-left: 3px solid #ff3333;
                margin-bottom: 8px;
            }
            a {
                color: #FFD700;
                text-decoration: none;
                margin-top: 20px;
                display: block;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>❌ Ошибки валидации</h1>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="?">← Вернуться</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// ===== СОЗДАНИЕ АДМИНИСТРАТОРА =====
try {
    // Проверить, не существует ли уже пользователь
    $stmt = $pdo->prepare("SELECT id FROM Users WHERE username = ? OR email = ?");
    $stmt->execute([strtolower($username), strtolower($email)]);
    
    if ($stmt->fetch()) {
        throw new Exception('Пользователь с таким username или email уже существует!');
    }
    
    // Хешировать пароль
    $password_hash = password_hash($password, PASSWORD_ARGON2ID);
    
    // Создать администратора
    $stmt = $pdo->prepare("
        INSERT INTO Users (
            username, 
            email, 
            password_hash, 
            display_name, 
            is_admin, 
            is_verified,
            status
        ) VALUES (?, ?, ?, ?, TRUE, TRUE, 'online')
    ");
    
    $stmt->execute([
        strtolower($username),
        strtolower($email),
        $password_hash,
        $display_name
    ]);
    
    $admin_id = $pdo->lastInsertId();
    
    // Создать предпочтения администратора
    $stmt = $pdo->prepare("
        INSERT INTO UserPreferences (user_id, theme) VALUES (?, 'dark')
    ");
    $stmt->execute([$admin_id]);
    
    // Успех!
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>✅ Успех</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #1a1a1a;
                color: #e0e0e0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #2a2a2a;
                padding: 40px;
                border-radius: 8px;
                border: 2px solid #4ade80;
                max-width: 500px;
                text-align: center;
            }
            h1 {
                color: #4ade80;
                font-size: 2rem;
            }
            .success-box {
                background: rgba(74,222,128,0.1);
                border: 1px solid #4ade80;
                padding: 20px;
                border-radius: 4px;
                margin: 20px 0;
            }
            .credentials {
                background: #111;
                border: 1px solid #FFD700;
                padding: 15px;
                border-radius: 4px;
                text-align: left;
                margin: 20px 0;
                font-family: monospace;
            }
            .credential-line {
                margin: 8px 0;
            }
            .label {
                color: #FFD700;
                font-weight: bold;
            }
            .warning {
                background: #8B0000;
                border: 1px solid #ff3333;
                color: #fff;
                padding: 15px;
                border-radius: 4px;
                margin: 20px 0;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 30px;
                background: #FFD700;
                color: #000;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                transition: all 0.3s;
            }
            a:hover {
                background: #FFE135;
                box-shadow: 0 0 15px rgba(255,215,0,0.6);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>✅ Администратор создан успешно!</h1>
            
            <div class="success-box">
                🎉 Аккаунт администратора готов к использованию
            </div>
            
            <div class="credentials">
                <div class="credential-line">
                    <span class="label">Username:</span> <?= htmlspecialchars($username) ?>
                </div>
                <div class="credential-line">
                    <span class="label">Email:</span> <?= htmlspecialchars($email) ?>
                </div>
                <div class="credential-line">
                    <span class="label">Пароль:</span> ••••••••
                </div>
            </div>
            
            <div class="warning">
                ⚠️ ВАЖНО:<br>
                1. Удалите этот скрипт (setup/create-admin.php)<br>
                2. Измените пароль после первого входа<br>
                3. Никому не передавайте эти данные
            </div>
            
            <a href="/pages/auth/login.php">→ Перейти к входу</a>
        </div>
    </body>
    </html>
    <?php
    
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>❌ Ошибка</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #1a1a1a;
                color: #e0e0e0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #2a2a2a;
                padding: 40px;
                border-radius: 8px;
                border: 2px solid #8B0000;
                max-width: 500px;
            }
            h1 {
                color: #ff6666;
            }
            .error-box {
                background: rgba(139,0,0,0.2);
                border: 1px solid #ff3333;
                padding: 15px;
                border-radius: 4px;
                margin: 20px 0;
            }
            a {
                color: #FFD700;
                text-decoration: none;
                margin-top: 20px;
                display: block;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>❌ Произошла ошибка</h1>
            <div class="error-box">
                <?= htmlspecialchars($e->getMessage()) ?>
            </div>
            <a href="?">← Вернуться</a>
        </div>
    </body>
    </html>
    <?php
}