/**
 * ФАЙЛ: assets/js/theme-manager-pro.js
 * ФИНАЛЬНАЯ ИСПРАВЛЕННАЯ ВЕРСИЯ - РАБОТАЕТ С ПЕРЕМЕННЫМИ
 */

(function() {
    'use strict';

    const STORAGE_KEY = 'site_bg_theme';
    
    const BG_THEMES = {
        'default': {
            name: 'Default',
            icon: '🌙',
            bg: {
                color: '#0a0a0a',
                image: 'none'
            },
            css: null
        },
        'power-metal': {
            name: 'Power Metal ⚔️',
            icon: '⚔️',
            bg: {
                color: '#0a0a0a',
                image: `
                    repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 215, 0, 0.03) 2px, rgba(255, 215, 0, 0.03) 4px),
                    radial-gradient(ellipse at 20% 50%, rgba(255, 165, 0, 0.15) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 80%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                    linear-gradient(135deg, #0a0a0a 0%, #1a1410 50%, #0a0a0a 100%)
                `
            },
            css: '/assets/css/themes/power-metal/main.css',
            dataTheme: 'power-metal'
        },
        'gothic-metal': {
            name: 'Gothic Metal 🦇',
            icon: '🦇',
            bg: {
                color: '#0f0515',
                image: `
                    repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(107, 44, 107, 0.05) 3px, rgba(107, 44, 107, 0.05) 6px),
                    radial-gradient(ellipse at 20% 30%, rgba(157, 0, 255, 0.12) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 70%, rgba(106, 13, 173, 0.08) 0%, transparent 50%),
                    linear-gradient(135deg, #0f0515 0%, #1a0f2e 50%, #0f0515 100%)
                `
            },
            css: '/assets/css/themes/gothic-metal/main.css',
            dataTheme: 'gothic-metal'
        },
        'punk-rock': {
            name: 'Punk Rock 🤘',
            icon: '🤘',
            bg: {
                color: '#0a0a0a',
                image: `
                    repeating-linear-gradient(0deg, rgba(255, 0, 255, 0.02) 0px, rgba(255, 0, 255, 0.02) 2px, transparent 2px, transparent 4px),
                    repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(0, 255, 255, 0.02) 3px, rgba(0, 255, 255, 0.02) 6px),
                    radial-gradient(ellipse at 30% 40%, rgba(255, 0, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(ellipse at 70% 60%, rgba(0, 255, 255, 0.08) 0%, transparent 50%),
                    linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)
                `
            },
            css: '/assets/css/themes/punk-rock/main.css',
            dataTheme: 'punk-rock'
        },
        'literary-dark': {
            name: 'Literary Dark 📚',
            icon: '📚',
            bg: {
                color: '#1a1714',
                image: `
                    repeating-linear-gradient(90deg, transparent, transparent 1px, rgba(212, 165, 116, 0.02) 1px, rgba(212, 165, 116, 0.02) 2px),
                    repeating-linear-gradient(0deg, transparent, transparent 1px, rgba(139, 115, 85, 0.02) 1px, rgba(139, 115, 85, 0.02) 2px),
                    radial-gradient(ellipse 700px 400px at 15% 40%, rgba(212, 165, 116, 0.1) 0%, rgba(139, 115, 85, 0.05) 30%, transparent 70%),
                    radial-gradient(ellipse 600px 350px at 80% 60%, rgba(212, 165, 116, 0.08) 0%, transparent 60%),
                    linear-gradient(130deg, #1a1714 0%, #251f19 50%, #1a1714 100%)
                `
            },
            css: '/assets/css/themes/literary-dark/main.css',
            dataTheme: 'literary-dark'
        }
    };

    function getSavedTheme() {
        return localStorage.getItem(STORAGE_KEY) || 'default';
    }

    function loadThemeCSS(themeName) {
        const theme = BG_THEMES[themeName];
        if (!theme || !theme.css) {
            const oldLink = document.querySelector('link[data-theme-css]');
            if (oldLink) oldLink.remove();
            return;
        }

        const oldLink = document.querySelector('link[data-theme-css]');
        if (oldLink) oldLink.remove();

        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = theme.css;
        link.setAttribute('data-theme-css', 'true');
        document.head.appendChild(link);
    }

    function applyBackground(themeName) {
        const theme = BG_THEMES[themeName];
        if (!theme) return;

        const body = document.body;
        const html = document.documentElement;
        
        // Применяем фон
        body.style.backgroundColor = theme.bg.color;
        body.style.backgroundImage = theme.bg.image;
        body.style.backgroundAttachment = 'fixed';
        body.style.backgroundSize = 'cover';
        body.style.backgroundPosition = 'center';

        // ВАЖНО: Устанавливаем data-theme на ОБА элемента
        if (theme.dataTheme) {
            html.setAttribute('data-theme', theme.dataTheme);
            body.setAttribute('data-theme', theme.dataTheme);
            loadThemeCSS(themeName);
        } else {
            html.removeAttribute('data-theme');
            body.removeAttribute('data-theme');
            loadThemeCSS(themeName);
        }

        localStorage.setItem(STORAGE_KEY, themeName);
    }

    function createThemeSwitcher() {
        if (document.querySelector('.bg-theme-switcher')) return;

        const container = document.createElement('div');
        container.className = 'bg-theme-switcher';

        const button = document.createElement('button');
        button.className = 'bg-theme-btn';
        button.innerHTML = '🎨';
        button.title = 'Выбрать тему';
        button.setAttribute('aria-label', 'Выбрать тему');

        const menu = document.createElement('div');
        menu.className = 'bg-theme-menu';

        let html = '';
        const currentTheme = getSavedTheme();
        
        for (const [key, theme] of Object.entries(BG_THEMES)) {
            const activeClass = key === currentTheme ? 'active' : '';
            html += `<button class="bg-menu-item ${activeClass}" data-theme="${key}" title="${theme.name}">${theme.icon} ${theme.name}</button>`;
        }
        
        menu.innerHTML = html;

        container.appendChild(button);
        container.appendChild(menu);
        document.body.appendChild(container);

        addStyles();

        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            menu.classList.toggle('active');
            button.classList.toggle('active');
        });

        menu.querySelectorAll('.bg-menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const themeName = item.dataset.theme;
                applyBackground(themeName);
                
                menu.querySelectorAll('.bg-menu-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                
                menu.classList.remove('active');
                button.classList.remove('active');
            });
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                menu.classList.remove('active');
                button.classList.remove('active');
            }
        });
    }

    function addStyles() {
        if (document.querySelector('style[data-bg-theme-switcher]')) return;

        const style = document.createElement('style');
        style.setAttribute('data-bg-theme-switcher', 'true');
        style.textContent = `
            .bg-theme-switcher {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 9999;
                font-family: Arial, sans-serif;
            }

            .bg-theme-btn {
                width: 55px;
                height: 55px;
                border-radius: 50%;
                background: linear-gradient(135deg, #FFD700, #FF8C00);
                border: 3px solid #FFD700;
                color: #000;
                font-size: 28px;
                cursor: pointer;
                box-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            }

            .bg-theme-btn:hover,
            .bg-theme-btn.active {
                transform: scale(1.15);
                box-shadow: 0 0 30px rgba(255, 215, 0, 0.9);
            }

            .bg-theme-menu {
                position: absolute;
                bottom: 65px;
                right: 0;
                background: rgba(15, 15, 15, 0.98);
                border: 2px solid #FFD700;
                border-radius: 10px;
                padding: 8px;
                min-width: 200px;
                display: none;
                flex-direction: column;
                gap: 4px;
                box-shadow: 0 0 30px rgba(255, 215, 0, 0.4);
                animation: slideUp 0.2s ease;
            }

            .bg-theme-menu.active {
                display: flex;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .bg-menu-item {
                width: 100%;
                padding: 12px 15px;
                background: transparent;
                border: 1.5px solid #555;
                color: #ddd;
                text-align: left;
                cursor: pointer;
                border-radius: 5px;
                font-size: 15px;
                transition: all 0.15s ease;
                font-weight: 500;
            }

            .bg-menu-item:hover {
                background: rgba(255, 215, 0, 0.12);
                color: #FFD700;
                border-color: #FFD700;
                transform: translateX(5px);
            }

            .bg-menu-item.active {
                background: rgba(255, 215, 0, 0.2);
                color: #FFD700;
                border-color: #FFD700;
                font-weight: bold;
            }

            @media (max-width: 768px) {
                .bg-theme-switcher {
                    bottom: 20px;
                    right: 20px;
                }

                .bg-theme-btn {
                    width: 48px;
                    height: 48px;
                    font-size: 24px;
                }

                .bg-theme-menu {
                    bottom: 55px;
                    min-width: 170px;
                }

                .bg-menu-item {
                    padding: 10px 12px;
                    font-size: 14px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    function init() {
        createThemeSwitcher();
        const savedTheme = getSavedTheme();
        applyBackground(savedTheme);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.ThemeManager = {
        setTheme: applyBackground,
        getTheme: getSavedTheme,
        getAvailableThemes: () => BG_THEMES
    };

})();

console.log('%c🎸 Master of Illusion - All 4 Themes Ready!', 'color: #FFD700; font-size: 14px; font-weight: bold;');