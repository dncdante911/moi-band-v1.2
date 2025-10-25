<?php
// Файл: admin/news_delete.php

require_once '../include_config/config.php';
require_once '../include_config/db_connect.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: news_list.php');
    exit;
}

$postId = (int)$_GET['id'];

// Находим пост, чтобы получить путь к картинке для удаления
$stmt = $pdo->prepare("SELECT imageUrl FROM Posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if ($post && !empty($post['imageUrl'])) {
    // Если у поста была картинка, удаляем ее с сервера
    @unlink('..' . $post['imageUrl']);
}

// Удаляем саму запись из базы данных
$stmt = $pdo->prepare("DELETE FROM Posts WHERE id = ?");
$stmt->execute([$postId]);

// Возвращаемся на страницу со списком новостей
header('Location: news_list.php');
exit;