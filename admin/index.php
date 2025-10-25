<?php
// Файл: admin/index.php
require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';
$stmt = $pdo->query('SELECT * FROM Track ORDER BY createdAt DESC');
$tracks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - <?= htmlspecialchars(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="container">
        <h1>Панель управления</h1>

<div class="admin-nav">
    <a href="index.php" class="active">Управление треками</a>
    <span class="admin-nav-separator">|</span>
    <a href="albums_list.php">Управление альбомами</a>
    <span class="admin-nav-separator">|</span>
    <a href="news_list.php">Управление новостями</a>
       <a href="news_list.php">Управление новостями</a>
       <span class="admin-nav-separator">|</span>
   <a href="gallery_list.php">Управление галереей</a>
</div>

        <a href="add_track.php" class="add-button">+ Добавить новый трек</a>

        <table class="track-table">
            <thead>
                <tr>
                    <th>Обложка</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($tracks as $track): ?>
    <tr>
        <td><img src="../<?= htmlspecialchars(ltrim($track['coverImagePath'], '/')) ?>" alt="Обложка" width="50"></td>
        <td><?= htmlspecialchars($track['title']) ?></td>
        <td class="action-links">
            <a href="edit_track.php?id=<?= (int)$track['id'] ?>">Редактировать</a>
            <a href="delete_track.php?id=<?= (int)$track['id'] ?>" class="delete" onclick="return confirm('Вы уверены?');">Удалить</a>
        </td>
    </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>