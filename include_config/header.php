<?php 
/**
 * ФАЙЛ: include_config/header.php
 * АВАРИЙНАЯ ВЕРСИЯ - восстанавливаем ОСНОВные стили
 * БЕЗ системы тем
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php';  // ← ДОБАВЛЕНО: подключение БД
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(SITE_NAME) ?></title>
    
    <!-- ✅ ОСНОВНЫЕ СТИЛИ (базис) -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="stylesheet" href="/assets/css/mobile-universal.css">
    
    <!-- ✅ ОСТАЛЬНЫЕ СПЕЦИФИЧНЫЕ СТИЛИ -->
    <link rel="stylesheet" href="/assets/css/auth.css">
    <link rel="stylesheet" href="/assets/css/chat.css">
    <link rel="stylesheet" href="/assets/css/about.css">
    <link rel="stylesheet" href="/assets/css/post.css">
    <link rel="stylesheet" href="/assets/css/header-epic.css">
    <link rel="stylesheet" href="/assets/css/albums.css">
    <link rel="stylesheet" href="/assets/css/albums-epic.css">
    <link rel="stylesheet" href="/assets/css/epic-home.css">
    <link rel="stylesheet" href="/assets/css/visualizer.css">
    
    <!-- === ШРИФТЫ === -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- === БИБЛИОТЕКИ === -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    
    <!-- === ДИАГНОСТИКА ТЕМ === -->
    <script>
        console.log('='.repeat(50));
        console.log('🎨 ДИАГНОСТИКА ТЕМ MASTER OF ILLUSION');
        console.log('='.repeat(50));
        
        // 1. Проверяем localStorage
        const savedTheme = localStorage.getItem('site_bg_theme');
        console.log(`📁 Сохраненная тема: ${savedTheme || 'default'}`);
        
        // 2. Проверяем загруженные CSS
        const stylesheets = document.styleSheets;
        console.log(`\n📚 Всего загруженных CSS: ${stylesheets.length}`);
        
        let themeCSS = false;
        for (let i = 0; i < stylesheets.length; i++) {
            const href = stylesheets[i].href;
            if (href && href.includes('/assets/css/themes/')) {
                console.log(`✅ Найден CSS тема: ${href}`);
                themeCSS = true;
            }
        }
        
        if (!themeCSS && savedTheme !== 'default') {
            console.warn('⚠️ ОШИБКА: CSS тема НЕ загружена! Проверь пути!');
        }
        
        // 3. Проверяем CSS переменные
        console.log(`\n🎨 CSS переменные:`);
        const root = document.documentElement;
        const primaryColor = getComputedStyle(root).getPropertyValue('--primary-color');
        const secondaryColor = getComputedStyle(root).getPropertyValue('--secondary-color');
        
        console.log(`--primary-color: ${primaryColor.trim() || 'НЕ НАЙДЕНА'}`);
        console.log(`--secondary-color: ${secondaryColor.trim() || 'НЕ НАЙДЕНА'}`);
        
        // 4. Проверяем data-theme атрибут
        console.log(`\n🔍 data-theme на body: ${document.body.getAttribute('data-theme') || 'не установлен'}`);
        
        // 5. Проверяем ThemeManager
        if (window.ThemeManager) {
            console.log(`\n✅ ThemeManager загружен`);
            console.log(`Доступные темы:`, Object.keys(window.ThemeManager.getAvailableThemes()));
        } else {
            console.warn('⚠️ ThemeManager НЕ загружен!');
        }
        
        // 6. Проверяем кнопку переключения
        setTimeout(() => {
            const btn = document.querySelector('.bg-theme-btn');
            if (btn) {
                console.log(`\n✅ Кнопка переключения найдена`);
            } else {
                console.warn('⚠️ Кнопка переключения НЕ найдена!');
            }
        }, 500);
        
        console.log('='.repeat(50));
    </script>
    <script src="/assets/js/visualizer.js"></script>
</head>
<body>
    <!-- === ФОНОВЫЕ ЭЛЕМЕНТЫ === -->
    <div id="particles-js"></div>
    <video autoplay muted loop id="background-video">
        <source src="/assets/videos/background_video.mp4" type="video/mp4">
    </video>

    <!-- === ШАПКА САЙТА === -->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <a href="/"><?= htmlspecialchars(SITE_NAME) ?></a>
            </div>
            
            <!-- Бургер меню для мобильных -->
            <button class="hamburger-menu" id="hamburger" aria-label="Открыть меню" aria-expanded="false">☰</button>
            
            <!-- Навигация -->
            <nav class="main-nav" id="mainNav" aria-label="Главная навигация">
                <ul>
                    <li><a href="/" class="nav-link" data-ajax-link>🏠 Главная</a></li>
                    <li><a href="/pages/albums.php" class="nav-link" data-ajax-link>📀 Альбомы</a></li>
                    <li><a href="/pages/about.php" class="nav-link" data-ajax-link>ℹ️ О проекте</a></li>
                    <li><a href="/pages/news.php" class="nav-link" data-ajax-link>📰 Новости</a></li>
                    <li><a href="/pages/gallery.php" class="nav-link" data-ajax-link>🖼️ Галерея</a></li>
                    <li><a href="/pages/contact.php" class="nav-link" data-ajax-link>✉️ Контакты</a></li>
                    
                    <?php 
                    // Проверяем авторизацию
                    if (isset($_SESSION['user_id'])): 
                    ?>
                        <li><a href="/pages/chat.php" class="nav-link" data-ajax-link>💬 Чат</a></li>
                        <li><a href="/pages/auth/profile.php" class="nav-link" data-ajax-link>👤 Профиль</a></li>
                        <li><a href="/pages/auth/logout.php" class="nav-link" data-ajax-link>🚪 Выход</a></li>
                    <?php else: ?>
                        <li><a href="/pages/auth/login.php" class="nav-link" data-ajax-link>🔐 Вход</a></li>
                        <li><a href="/pages/auth/register.php" class="nav-link" data-ajax-link>✍️ Регистрация</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- === ОСНОВНОЙ КОНТЕНТ === -->
    <main class="main-content">