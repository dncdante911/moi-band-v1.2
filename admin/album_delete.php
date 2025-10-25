<?php
// Файл: admin/album_delete.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

// === ПРОВЕРКА ID ===
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: albums_list.php');
    exit;
}

$albumId = (int)$_GET['id'];

// === ПОЛУЧИТЬ ИНФОРМАЦИЮ ОБ АЛЬБОМЕ ===
$stmt = $pdo->prepare("SELECT * FROM Albums WHERE id = ?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

if (!$album) {
    header('Location: albums_list.php');
    exit;
}

// === ПОЛУЧИТЬ ВСЕ ТРЕКИ АЛЬБОМА ===
$stmt = $pdo->prepare("SELECT * FROM Track WHERE albumId = ?");
$stmt->execute([$albumId]);
$tracks = $stmt->fetchAll();

try {
    // === УДАЛИТЬ ВСЕ ФИЗИЧЕСКИЕ ФАЙЛЫ ТРЕКОВ ===
    foreach ($tracks as $track) {
        // Удалить обложку трека
        if ($track['coverImagePath']) {
            @unlink('..' . $track['coverImagePath']);
        }
        
        // Удалить аудиофайл
        if ($track['fullAudioPath']) {
            @unlink('..' . $track['fullAudioPath']);
        }
        
        // Удалить видео если есть
        if ($track['videoPath']) {
            @unlink('..' . $track['videoPath']);
        }
    }
    
    // === УДАЛИТЬ ОБЛОЖКУ АЛЬБОМА ===
    if ($album['coverImagePath']) {
        @unlink('..' . $album['coverImagePath']);
    }
    
    // === УДАЛИТЬ ВСЕ ТРЕКИ ИЗ БД (каскадное удаление удалит тексты песен и прочее) ===
    $stmt = $pdo->prepare("DELETE FROM Track WHERE albumId = ?");
    $stmt->execute([$albumId]);
    
    // === УДАЛИТЬ САМ АЛЬБОМ ===
    $stmt = $pdo->prepare("DELETE FROM Albums WHERE id = ?");
    $stmt->execute([$albumId]);
    
    // === РЕДИРЕКТ С УВЕДОМЛЕНИЕМ ===
    header('Location: albums_list.php?deleted=1');
    exit;
    
} catch (Exception $e) {
    // Если произошла ошибка - показываем ошибку
    http_response_code(500);
    echo '<!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>❌ Ошибка удаления</title>
        <link rel="stylesheet" href="../assets/css/admin_style.css">
    </head>
    <body>
        <div class="container">
            <h1>❌ Ошибка при удалении альбома</h1>
            <p>Ошибка БД: ' . htmlspecialchars($e->getMessage()) . '</p>
            <a href="album_view.php?id=' . $albumId . '">← Вернуться</a>
        </div>
    </body>
    </html>';
    exit;
}