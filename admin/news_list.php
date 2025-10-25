<?php
// Файл: admin/news_list.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

$stmt = $pdo->query('SELECT * FROM news ORDER BY created_at DESC');
$news = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление новостями - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Панель управления</h1>

        <div class="admin-nav">
            <a href="index.php">Управление треками</a>
            <span class="admin-nav-separator">|</span>
            <a href="albums_list.php">Управление альбомами</a>
            <span class="admin-nav-separator">|</span>
            <a href="news_list.php" class="active">Управление новостями</a>
            <span class="admin-nav-separator">|</span>
            <a href="gallery_list.php">Управление галереей</a>
        </div>

        <a href="news_add.php" class="add-button">+ Добавить новость</a>

        <table class="track-table">
            <thead>
                <tr>
                    <th>Обложка</th>
                    <th>Заголовок</th>
                    <th>Категория</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($news)): ?>
                    <tr><td colspan="5">Новостей пока нет.</td></tr>
                <?php else: ?>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <?php if ($item['image']): ?>
                                <td><img src="../<?= htmlspecialchars(ltrim($item['image'], '/')) ?>" alt="Обложка" width="50"></td>
                            <?php else: ?>
                                <td>-</td>
                            <?php endif; ?>
                            <td><strong><?= htmlspecialchars($item['title']) ?></strong></td>
                            <td><span class="badge"><?= htmlspecialchars($item['category']) ?></span></td>
                            <td><?= date('d.m.Y H:i', strtotime($item['created_at'])) ?></td>
                            <td class="action-links">
                                <a href="news_edit.php?id=<?= (int)$item['id'] ?>">✏️ Редактировать</a>
                                <a href="news_delete.php?id=<?= (int)$item['id'] ?>" class="delete" onclick="return confirm('Вы уверены?');">🗑️ Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>