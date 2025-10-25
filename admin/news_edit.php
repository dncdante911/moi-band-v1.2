<?php
// Файл: admin/news_edit.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: news_list.php');
    exit;
}
$postId = (int)$_GET['id'];
$errors = [];

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title)) $errors[] = 'Заголовок не может быть пустым.';
    if (empty($content)) $errors[] = 'Текст новости не может быть пустым.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT imageUrl FROM Posts WHERE id = ?");
        $stmt->execute([$postId]);
        $currentPost = $stmt->fetch();
        $imageUrl = $currentPost['imageUrl'];

        // Если загружен новый файл, заменяем старый
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/posts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Если старый файл был, удаляем его
                if ($imageUrl) {
                    @unlink('..' . $imageUrl);
                }
                $imageUrl = '/public/uploads/posts/' . $fileName;
            } else {
                $errors[] = 'Не удалось загрузить новое изображение.';
            }
        }

        // Если ошибок с файлом не было, обновляем запись
        if (empty($errors)) {
            $sql = "UPDATE Posts SET title = :title, content = :content, imageUrl = :imageUrl WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':imageUrl' => $imageUrl,
                ':id' => $postId
            ]);

            header('Location: news_list.php');
            exit;
        }
    }
}

// Загружаем данные поста для отображения в форме
$stmt = $pdo->prepare("SELECT * FROM Posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: news_list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать новость - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style> .form-container { max-width: 800px; } .form-group { margin-bottom: 20px; } .form-group label { display: block; margin-bottom: 5px; font-weight: bold; } .form-group input, .form-group textarea { width: 100%; padding: 10px; background-color: #4a5568; border: 1px solid #718096; color: #e2e8f0; border-radius: 5px; box-sizing: border-box; } .form-group input[type="file"] { padding: 5px; } .submit-button { padding: 12px 25px; background-color: #2b6cb0; color: #ffffff; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; } .errors { background-color: #c53030; color: #ffffff; padding: 15px; border-radius: 5px; margin-bottom: 20px; } .current-file { font-size: 0.9rem; color: #a0aec0; margin-top: 5px; } </style>
</head>
<body>
    <div class="container form-container">
        <div class="breadcrumbs">
            <a href="news_list.php">Управление новостями</a> &raquo;
            <span>Редактировать новость</span>
        </div>
        <h1>Редактировать новость</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form action="news_edit.php?id=<?= $postId ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Текст</label>
                <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Изображение (оставить пустым, чтобы не менять)</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif">
                <?php if ($post['imageUrl']): ?>
                    <div class="current-file">Текущее изображение: <img src="../<?= htmlspecialchars($post['imageUrl']) ?>" alt="" height="50"></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="submit-button">Сохранить изменения</button>
        </form>
    </div>
</body>
</html>