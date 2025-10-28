<?php
// Файл: admin/auth_check.php
// Централизованная проверка прав администратора

// Начинаем сессию, если она еще не начата
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Проверяем, есть ли сессия администратора
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    
    // Получаем текущий URI для перенаправления
    // Используем $_SERVER['REQUEST_URI'] для максимальной точности
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    
    // ИСПРАВЛЕНИЕ: Перенаправляем на роутер index.php с p=login
    header('Location: index.php?p=login&redirect=' . $redirect_url);
    exit; // !!! КРИТИЧЕСКИ ВАЖНО: НЕМЕДЛЕННЫЙ ВЫХОД ИЗ СКРИПТА !!!
}
// Если все проверки пройдены (есть сессия и права admin), скрипт продолжает выполнение.