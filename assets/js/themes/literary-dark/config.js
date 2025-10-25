/**
 * LITERARY DARK - config.js
 * JS конфиг классической литературной темы
 */

const LiteraryDarkThemeConfig = {
    name: 'literary-dark',
    displayName: '📚 Literary Dark',
    description: 'Классический литературный дизайн с коричневыми тонами',
    version: '1.0.0',
    
    colors: {
        primary: '#D4A574',
        secondary: '#8B7355',
        accent: '#C7A868',
        dark: '#1a1714',
        darkPanel: '#251f19',
        lightText: '#e8e4d8',
        border: '#D4A574'
    },
    
    shadows: {
        glowWarm: '0 0 15px rgba(212, 165, 116, 0.5)',
        glowStrong: '0 0 25px rgba(212, 165, 116, 0.6)',
        shadowColor: '0 0 20px rgba(212, 165, 116, 0.25)',
        shadowDark: '0 10px 30px rgba(0, 0, 0, 0.7)'
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
        literary: `
            repeating-linear-gradient(90deg, transparent, transparent 1px, rgba(212, 165, 116, 0.02) 1px, rgba(212, 165, 116, 0.02) 2px),
            repeating-linear-gradient(0deg, transparent, transparent 1px, rgba(139, 115, 85, 0.02) 1px, rgba(139, 115, 85, 0.02) 2px),
            linear-gradient(130deg, #1a1714 0%, #251f19 50%, #1a1714 100%)
        `
    },
    
    icons: {
        theme: '📚',
        book: '📖',
        quill: '🖋️',
        lamp: '🕯️',
        scroll: '📜',
        star: '⭐'
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
            duration: 2500,
            easing: 'ease-in-out'
        }
    },
    
    applyColor: function(element, colorType) {
        if (element) {
            element.style.color = this.colors[colorType] || this.colors.primary;
        }
    },
    
    applyGlow: function(element, glowType = 'glowWarm') {
        if (element) {
            element.style.boxShadow = this.shadows[glowType] || this.shadows.glowWarm;
            element.style.transition = `box-shadow ${this.transitions.default}`;
        }
    },
    
    isMobile: function() {
        return window.innerWidth <= this.breakpoints.tablet;
    },
    
    log: function(message) {
        console.log(`%c📚 [Literary Dark] ${message}`, 'color: #D4A574; font-weight: bold;');
    }
};

LiteraryDarkThemeConfig.log('Конфиг загружен успешно');

window.LiteraryDarkThemeConfig = LiteraryDarkThemeConfig;

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        LiteraryDarkThemeConfig.log('Инициализация завершена');
        
        document.querySelectorAll('h1, h2, h3').forEach(heading => {
            heading.style.textShadow = `0 0 15px ${LiteraryDarkThemeConfig.colors.primary}60`;
        });
        
        document.querySelectorAll('button, .btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.boxShadow = LiteraryDarkThemeConfig.shadows.glowStrong;
            });
            button.addEventListener('mouseleave', function() {
                this.style.boxShadow = LiteraryDarkThemeConfig.shadows.shadowColor;
            });
        });
    });
})();