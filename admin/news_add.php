<?php
// Файл: admin/news_add.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? 'update';
    $image = '';

    if (empty($title) || empty($content)) {
        $errors[] = 'Заполни все поля!';
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = '../assets/uploads/news/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = '/assets/uploads/news/' . $image_name;
        } else {
            $errors[] = 'Ошибка при загрузке изображения';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO news (title, content, category, image, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$title, $content, $category, $image])) {
            $success = true;
        } else {
            $errors[] = 'Ошибка при сохранении в БД';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить новость - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .form-container { max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #FFD700; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; 
            padding: 10px; 
            background-color: #111; 
            border: 1px solid #444; 
            color: #e0e0e0; 
            border-radius: 4px; 
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .form-group textarea { resize: vertical; min-height: 200px; }
        .submit-button { 
            padding: 12px 30px; 
            background-color: #2b6cb0; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            font-size: 1rem; 
            cursor: pointer;
            font-weight: bold;
        }
        .submit-button:hover { background-color: #3182ce; }
        .errors { 
            background-color: #c53030; 
            color: #fff; 
            padding: 15px; 
            border-radius: 4px; 
            margin-bottom: 20px;
        }
        .success { 
            background-color: #38A169; 
            color: #fff; 
            padding: 15px; 
            border-radius: 4px; 
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h1>➕ Добавить новость</h1>
        
        <?php if ($success): ?>
            <div class="success">
                ✅ Новость успешно добавлена! <a href="news_list.php" style="color: #fff; text-decoration: underline;">К списку новостей</a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <div>❌ <?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">📝 Заголовок *</label>
                <input type="text" id="title" name="title" required placeholder="Название новости">
            </div>
            
            <div class="form-group">
                <label for="category">🏷️ Категория *</label>
                <select id="category" name="category" required>
                    <option value="update">Обновление</option>
                    <option value="release">Релиз</option>
                    <option value="event">Событие</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">✍️ Содержание *</label>
                <textarea id="content" name="content" required placeholder="Текст новости"></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">🖼️ Изображение</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="submit-button">✅ Добавить новость</button>
            <a href="news_list.php" style="margin-left: 15px; color: #FFD700; text-decoration: none;">← Назад</a>
        </form>
    </div>
</body>
</html>