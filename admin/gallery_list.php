<?php
// Файл: admin/gallery_list.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

$stmt = $pdo->query('SELECT * FROM gallery ORDER BY created_at DESC');
$images = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление галереей - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .gallery-item-card { background: #111; border: 1px solid #444; border-radius: 4px; overflow: hidden; }
        .gallery-item-image { width: 100%; height: 150px; object-fit: cover; }
        .gallery-item-info { padding: 10px; }
        .gallery-item-title { font-weight: bold; margin-bottom: 5px; }
        .gallery-item-actions { display: flex; gap: 10px; margin-top: 10px; }
        .gallery-item-actions a { padding: 5px 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Панель управления</h1>

        <div class="admin-nav">
            <a href="index.php">Управление треками</a>
            <span class="admin-nav-separator">|</span>
            <a href="albums_list.php">Управление альбомами</a>
            <span class="admin-nav-separator">|</span>
            <a href="news_list.php">Управление новостями</a>
            <span class="admin-nav-separator">|</span>
            <a href="gallery_list.php" class="active">Управление галереей</a>
        </div>

        <a href="gallery_add.php" class="add-button">+ Добавить фото</a>

        <div class="gallery-grid">
            <?php if (empty($images)): ?>
                <div style="grid-column: 1/-1; padding: 40px; text-align: center; color: #999;">
                    Фото в галерее отсутствуют
                </div>
            <?php else: ?>
                <?php foreach ($images as $img): ?>
                    <div class="gallery-item-card">
                        <img src="../<?= htmlspecialchars(ltrim($img['image_url'], '/')) ?>" alt="<?= htmlspecialchars($img['title']) ?>" class="gallery-item-image">
                        <div class="gallery-item-info">
                            <div class="gallery-item-title"><?= htmlspecialchars($img['title']) ?></div>
                            <div style="font-size: 0.9rem; color: #999;"><?= htmlspecialchars($img['category']) ?></div>
                            <div class="gallery-item-actions">
                                <a href="gallery_edit.php?id=<?= (int)$img['id'] ?>">✏️ Изменить</a>
                                <a href="gallery_delete.php?id=<?= (int)$img['id'] ?>" class="delete" onclick="return confirm('Удалить?');">🗑️ Удалить</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>