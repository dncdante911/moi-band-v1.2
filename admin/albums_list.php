<?php
// Файл: admin/albums_list.php - ОБНОВЛЕННАЯ ВЕРСИЯ

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

$stmt = $pdo->query('SELECT * FROM Albums ORDER BY releaseDate DESC');
$albums = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление альбомами - Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <style>
        .success-message {
            background-color: #38A169;
            color: #fff;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Панель управления</h1>

        <div class="admin-nav">
            <a href="index.php">Управление треками</a>
            <span class="admin-nav-separator">|</span>
            <a href="albums_list.php" class="active">Управление альбомами</a>
            <span class="admin-nav-separator">|</span>
            <a href="news_list.php">Управление новостями</a>
        </div>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="success-message">
                ✅ Альбом успешно удален вместе со всеми треками и файлами
            </div>
        <?php endif; ?>

        <a href="album_add.php" class="add-button">+ Добавить новый альбом</a>

        <table class="track-table">
            <thead>
                <tr>
                    <th>Обложка</th>
                    <th>Название альбома</th>
                    <th>Дата релиза</th>
                    <th>Треков</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($albums)): ?>
                    <tr><td colspan="5">Альбомов пока нет.</td></tr>
                <?php else: ?>
                    <?php foreach ($albums as $album): ?>
                        <?php
                        // Считаем количество треков в альбоме
                        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Track WHERE albumId = ?");
                        $stmt->execute([$album['id']]);
                        $track_count = $stmt->fetch()['count'];
                        ?>
                        <tr>
                            <td><img src="../<?= htmlspecialchars(ltrim($album['coverImagePath'], '/')) ?>" alt="Обложка" width="50"></td>
                            <td><strong><?= htmlspecialchars($album['title']) ?></strong></td>
                            <td><?= $album['releaseDate'] ? date('d.m.Y', strtotime($album['releaseDate'])) : 'Не указана' ?></td>
                            <td><span style="background: #FFD700; color: #000; padding: 5px 10px; border-radius: 4px; font-weight: bold;">🎵 <?= $track_count ?></span></td>
                            <td class="action-links">
                                <a href="album_view.php?id=<?= (int)$album['id'] ?>">📀 Посмотреть</a>
                                <a href="album_edit.php?id=<?= (int)$album['id'] ?>">✏️ Редактировать</a>
                                <a href="album_delete.php?id=<?= (int)$album['id'] ?>" class="delete" onclick="return confirm('⚠️ Вы уверены? Это удалит альбом и ВСЕ треки в нём!\n\nТреков в альбоме: <?= $track_count ?>');">🗑️ Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>