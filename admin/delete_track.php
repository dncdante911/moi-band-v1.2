<?php
// Файл: admin/delete_track.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

// 1. Проверяем, что ID был передан и это число
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Если ID нет, просто возвращаем на главную
    header('Location: index.php');
    exit;
}

$trackId = (int)$_GET['id'];

// 2. Находим трек в базе данных, чтобы получить пути к файлам
$stmt = $pdo->prepare("SELECT * FROM Track WHERE id = ?");
$stmt->execute([$trackId]);
$track = $stmt->fetch();

// Если трек с таким ID не найден, возвращаем на главную
if (!$track) {
    header('Location: index.php');
    exit;
}

// 3. Удаляем физические файлы с сервера
// Функция @unlink используется, чтобы подавить ошибку, если файла вдруг нет
if ($track['coverImagePath']) {
    @unlink('..' . $track['coverImagePath']);
}
if ($track['fullAudioPath']) {
    @unlink('..' . $track['fullAudioPath']);
}

// 4. Удаляем запись из базы данных
$stmt = $pdo->prepare("DELETE FROM Track WHERE id = ?");
$stmt->execute([$trackId]);

// 5. Возвращаем пользователя на главную страницу админки
header('Location: index.php');
exit;