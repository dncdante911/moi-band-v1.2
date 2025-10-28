<?php
// Файл: admin/dashboard.php
// Здесь $tracks уже загружен в admin/index.php

if (!empty($errors)) {
     echo '<div class="error-box"><p>' . implode('</p><p>', $errors) . '</p></div>';
}

?>

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