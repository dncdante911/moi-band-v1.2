<?php
// Файл: index.php

require_once 'include_config/config.php';
require_once 'include_config/db_connect.php';
require_once 'include_config/header.php';

// Определение запрашиваемой страницы
$page = $_GET['p'] ?? 'home'; // По умолчанию 'home'
$param = $_GET['id'] ?? null;
$pagePath = 'pages/' . $page . '.php';

// --- НОВОЕ: ВАЛИДАЦИЯ И ОПРЕДЕЛЕНИЕ ТИПА ЗАПРОСА ---

// Проверяем, является ли это AJAX-запросом для загрузки только контента
$isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Если это AJAX-запрос, загружаем только контент страницы
if ($isAjaxRequest) {
    if (file_exists($pagePath)) {
        require_once $pagePath;
    } else {
        // Ошибка 404 для AJAX
        http_response_code(404);
        echo '<div class="error-container"><h2>404</h2><p>Страница не найдена</p></div>';
    }
    exit; // Важно: завершаем скрипт, чтобы не выводить header/footer
}

// -------------------------------------------------------------------
?>

<main id="content-container" class="main-content">
    <?php 
    // Загружаем контент страницы для первого (полного) запроса
    if (file_exists($pagePath)) {
        require_once $pagePath;
    } else {
        // Обработка 404 для полного запроса
        require_once 'pages/404.php'; // Предполагаем наличие файла 404.php
    }
    ?>
</main>

<?php 
require_once 'include_config/footer.php';
// В footer.php теперь будет расположен плеер и подключены скрипты
?>