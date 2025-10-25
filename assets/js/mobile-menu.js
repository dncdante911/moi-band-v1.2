/**
 * Файл: assets/js/mobile-menu.js
 * 
 * Управление мобильным меню
 */

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mainNav = document.getElementById('mainNav');
    
    if (!hamburger || !mainNav) return;
    
    // Переключение меню
    hamburger.addEventListener('click', function() {
        mainNav.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
    
    // Закрыть меню при клике на ссылку
    const navLinks = mainNav.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            mainNav.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
    
    // Закрыть меню при клике вне его
    document.addEventListener('click', function(e) {
        if (!hamburger.contains(e.target) && !mainNav.contains(e.target)) {
            mainNav.classList.remove('active');
            hamburger.classList.remove('active');
        }
    });
});