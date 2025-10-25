/**
 * PUNK ROCK - config.js
 * JS конфиг киберпанк темы
 */

const PunkRockThemeConfig = {
    name: 'punk-rock',
    displayName: '🤘 Punk Rock',
    description: 'Энергичный киберпанк дизайн с неоновыми цветами',
    version: '1.0.0',
    
    colors: {
        primary: '#FF00FF',
        secondary: '#00FFFF',
        accent: '#FFFF00',
        dark: '#0a0a0a',
        darkPanel: '#1a1a1a',
        lightText: '#ffffff',
        border: '#FF00FF'
    },
    
    shadows: {
        glowNeon: '0 0 15px rgba(255, 0, 255, 0.8)',
        glowStrong: '0 0 25px rgba(0, 255, 255, 0.9), 0 0 35px rgba(255, 0, 255, 0.7)',
        shadowColor: '0 0 20px rgba(255, 0, 255, 0.4)',
        shadowDark: '0 10px 30px rgba(0, 0, 0, 0.9)'
    },
    
    transitions: {
        default: '0.3s ease',
        slow: '0.5s ease',
        fast: '0.15s ease'
    },
    
    spacing: {
        xs: '8px',
        sm: '12px',
        md: '20px',
        lg: '30px',
        xl: '50px'
    },
    
    breakpoints: {
        mobile: 480,
        tablet: 768,
        desktop: 1024,
        wide: 1200
    },
    
    fonts: {
        heading: "'Cinzel Decorative', serif",
        body: "'Roboto', sans-serif"
    },
    
    backgroundPatterns: {
        cyberpunk: `
            repeating-linear-gradient(0deg, rgba(255, 0, 255, 0.02) 0px, rgba(255, 0, 255, 0.02) 2px, transparent 2px, transparent 4px),
            linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%)
        `
    },
    
    icons: {
        theme: '🤘',
        punk: '🎸',
        neon: '💫',
        bolt: '⚡',
        fire: '🔥',
        star: '✨'
    },
    
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
    
    applyColor: function(element, colorType) {
        if (element) {
            element.style.color = this.colors[colorType] || this.colors.primary;
        }
    },
    
    applyGlow: function(element, glowType = 'glowNeon') {
        if (element) {
            element.style.boxShadow = this.shadows[glowType] || this.shadows.glowNeon;
            element.style.transition = `box-shadow ${this.transitions.default}`;
        }
    },
    
    isMobile: function() {
        return window.innerWidth <= this.breakpoints.tablet;
    },
    
    log: function(message) {
        console.log(`%c🤘 [Punk Rock] ${message}`, 'color: #FF00FF; font-weight: bold;');
    }
};

PunkRockThemeConfig.log('Конфиг загружен успешно');

window.PunkRockThemeConfig = PunkRockThemeConfig;

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        PunkRockThemeConfig.log('Инициализация завершена');
        
        document.querySelectorAll('h1, h2, h3').forEach(heading => {
            heading.style.textShadow = `0 0 20px ${PunkRockThemeConfig.colors.primary}80, 0 0 30px ${PunkRockThemeConfig.colors.secondary}60`;
        });
        
        document.querySelectorAll('button, .btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.boxShadow = PunkRockThemeConfig.shadows.glowStrong;
            });
            button.addEventListener('mouseleave', function() {
                this.style.boxShadow = PunkRockThemeConfig.shadows.shadowColor;
            });
        });
    });
})();