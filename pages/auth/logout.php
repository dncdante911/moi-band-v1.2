<?php
/**
 * Файл: pages/auth/logout.php
 * Выход из системы
 */

require_once '../../include_config/config.php';
require_once '../../include_config/db_connect.php';
require_once '../../include_config/Auth.php';

$auth = new Auth($pdo);

// Выполнить выход
$auth->logout();

// Редирект на главную
header('Location: /');
exit;