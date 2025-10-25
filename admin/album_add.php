<?php
// Файл: admin/album_add.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

$errors = [];
$title = '';
$description = '';
$releaseDate = '';

// Обрабатываем отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $releaseDate = trim($_POST['releaseDate'] ?? '');
    $coverImagePath = null;

    // Валидация
    if (empty($title)) {
        $errors[] = 'Название альбома не может быть пустым.';
    }
    if (empty($_FILES['cover']['name']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Необходимо загрузить обложку.';
    }

    // Обработка загрузки обложки
    if (empty($errors)) {
        $uploadDir = '../public/uploads/album_covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileExtension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $uploadPath)) {
            $coverImagePath = '/public/uploads/album_covers/' . $fileName;

            // Сохраняем в базу
            $sql = "INSERT INTO Albums (title, description, releaseDate, coverImagePath) VALUES (:title, :description, :releaseDate, :coverImagePath)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':releaseDate' => !empty($releaseDate) ? $releaseDate : null,
                ':coverImagePath' => $coverImagePath
            ]);

            header('Location: albums_list.php');
            exit;
        } else {
            $errors[] = 'Не удалось загрузить обложку.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить альбом - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="container form-container">
        <div class="breadcrumbs">
            <a href="albums_list.php">Управление альбомами</a> &raquo;
            <span>Добавить альбом</span>
        </div>

        <h1>Добавить новый альбом</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <strong>Ошибки:</strong>
                <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <form action="album_add.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Название альбома</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Описание альбома</label>
                <textarea id="description" name="description" rows="5"><?= htmlspecialchars($description) ?></textarea>
            </div>
            <div class="form-group">
                <label for="releaseDate">Дата релиза</label>
                <input type="date" id="releaseDate" name="releaseDate" value="<?= htmlspecialchars($releaseDate) ?>">
            </div>
            <div class="form-group">
                <label for="cover">Обложка альбома (JPG, PNG)</label>
                <input type="file" id="cover" name="cover" accept="image/jpeg, image/png" required>
            </div>
            <button type="submit" class="submit-button">Сохранить альбом</button>
        </form>
    </div>
</body>
</html>