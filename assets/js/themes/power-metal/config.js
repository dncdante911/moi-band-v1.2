/**
 * ФАЙЛ: assets/js/themes/power-metal/config.js
 * 
 * JS КОНФИГУРАЦИЯ ТЕМЫ POWER METAL
 * 
 * Содержит:
 * - Данные о теме
 * - Цвета для динамических элементов
 * - Конфигурация анимаций
 * - Переменные для JS скриптов
 */

const PowerMetalThemeConfig = {
    // ============ ИНФОРМАЦИЯ О ТЕМЕ ============
    name: 'power-metal',
    displayName: '⚔️ Power Metal',
    description: 'Эпический героический дизайн с золотыми акцентами',
    version: '1.0.0',
    
    // ============ ЦВЕТА ============
    colors: {
        primary: '#FFD700',      // Золотой
        secondary: '#FFA500',    // Оранжевый
        accent: '#FF6B1B',       // Ярко-оранжевый
        dark: '#0a0a0a',         // Темный фон
        darkPanel: '#1a1410',    // Темная панель
        lightText: '#e0e0e0',    // Светлый текст
        border: '#FFD700'        // Граница
    },
    
    // ============ ТЕНИ И ГЛОУ ============
    shadows: {
        glowGolden: '0 0 15px rgba(255, 215, 0, 0.6)',
        glowStrong: '0 0 25px rgba(255, 165, 0, 0.8)',
        shadowColor: '0 0 20px rgba(255, 215, 0, 0.3)',
        shadowDark: '0 10px 30px rgba(0, 0, 0, 0.7)'
    },
    
    // ============ ПЕРЕХОДЫ И АНИМАЦИИ ============
    transitions: {
        default: '0.3s ease',
        slow: '0.5s ease',
        fast: '0.15s ease'
    },
    
    // ============ ОТСТУПЫ ============
    spacing: {
        xs: '8px',
        sm: '12px',
        md: '20px',
        lg: '30px',
        xl: '50px'
    },
    
    // ============ ТОЧКИ БРЕЙКПОЙНТОВ ============
    breakpoints: {
        mobile: 480,
        tablet: 768,
        desktop: 1024,
        wide: 1200
    },
    
    // ============ ШРИФТЫ ============
    fonts: {
        heading: "'Cinzel Decorative', serif",
        body: "'Roboto', sans-serif"
    },
    
    // ============ ФОНОВЫЕ ПАТТЕРНЫ ============
    backgroundPatterns: {
        metallic: `
            repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 215, 0, 0.03) 2px, rgba(255, 215, 0, 0.03) 4px),
            radial-gradient(ellipse at 20% 50%, rgba(255, 165, 0, 0.15) 0%, transparent 50%),
            linear-gradient(135deg, #0a0a0a 0%, #1a1410 50%, #0a0a0a 100%)
        `,
        subtle: `
            repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255, 215, 0, 0.02) 35px, rgba(255, 215, 0, 0.02) 70px)
        `
    },
    
    // ============ ИКОНКИ И ЭМОДЗИ ============
    icons: {
        theme: '⚔️',
        power: '⚡',
        victory: '🏆',
        sword: '🗡️',
        fire: '🔥',
        star: '⭐'
    },
    
    // ============ СЕЛЕКТОРЫ ДЛЯ JS ============
    selectors: {
        header: '.site-header',
        footer: '.site-footer',
        logo: '.logo a',
        nav: '.main-nav',
        mainContent: '.main-content',
        pageContent: '.page-content',
        cards: '.card, .genre-card, .album-card, .influence-card',
        buttons: 'button, .btn, .control-btn',
        forms: 'input, textarea, select'
    },
    
    // ============ ДАННЫЕ АНИМАЦИЙ ============
    animations: {
        entrance: {
            duration: 600,
            easing: 'ease'
        },
        hover: {
            duration: 300,
            easing: 'ease'
        },
        glow: {
            duration: 2000,
            easing: 'ease-in-out'
        }
    },
    
    // ============ ФУНКЦИИ-ХЕЛПЕРЫ ============
    
    /**
     * Применить цвет к элементу
     */
    applyColor: function(element, colorType) {
        if (element) {
            element.style.color = this.colors[colorType] || this.colors.primary;
        }
    },
    
    /**
     * Применить глоу-эффект
     */
    applyGlow: function(element, glowType = 'glowGolden') {
        if (element) {
            element.style.boxShadow = this.shadows[glowType] || this.shadows.glowGolden;
            element.style.transition = `box-shadow ${this.transitions.default}`;
        }
    },
    
    /**
     * Проверить если мобильное устройство
     */
    isMobile: function() {
        return window.innerWidth <= this.breakpoints.tablet;
    },
    
    /**
     * Логировать информацию о теме
     */
    log: function(message) {
        console.log(`%c⚔️ [Power Metal] ${message}`, 'color: #FFD700; font-weight: bold;');
    }
};

// ============ ИНИЦИАЛИЗАЦИЯ ============

// Логируем загрузку конфига
PowerMetalThemeConfig.log('Конфиг загружен успешно');

// Экспортируем для других скриптов
window.PowerMetalThemeConfig = PowerMetalThemeConfig;

// ============ АВТОЗАПУСК ИНИЦИАЛИЗАЦИИ (опционально) ============

(function() {
    'use strict';
    
    // Применяем основные стили при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        PowerMetalThemeConfig.log('Инициализация завершена');
        
        // Применяем глоу к заголовкам
        document.querySelectorAll('h1, h2, h3').forEach(heading => {
            heading.style.textShadow = `0 0 15px ${PowerMetalThemeConfig.colors.primary}80`;
        });
        
        // Применяем стили к кнопкам
        document.querySelectorAll('button, .btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.boxShadow = PowerMetalThemeConfig.shadows.glowStrong;
            });
            button.addEventListener('mouseleave', function() {
                this.style.boxShadow = PowerMetalThemeConfig.shadows.shadowColor;
            });
        });
    });
})();

// ============ КОНЕЦ КОНФИГА ============