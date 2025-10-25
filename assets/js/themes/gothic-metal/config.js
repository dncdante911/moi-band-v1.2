/**
 * GOTHIC METAL - config.js
 * JS конфиг готической темы
 */

const GothicMetalThemeConfig = {
    name: 'gothic-metal',
    displayName: '🦇 Gothic Metal',
    description: 'Мрачный готический дизайн с фиолетовыми акцентами',
    version: '1.0.0',
    
    colors: {
        primary: '#9D00FF',
        secondary: '#6A0DAD',
        accent: '#D946EF',
        dark: '#0f0515',
        darkPanel: '#1a0f2e',
        lightText: '#d0d0e0',
        border: '#9D00FF'
    },
    
    shadows: {
        glowPurple: '0 0 15px rgba(157, 0, 255, 0.6)',
        glowStrong: '0 0 25px rgba(217, 70, 239, 0.8)',
        shadowColor: '0 0 20px rgba(157, 0, 255, 0.3)',
        shadowDark: '0 10px 30px rgba(0, 0, 0, 0.8)'
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
        gothic: `
            repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(157, 0, 255, 0.02) 2px, rgba(157, 0, 255, 0.02) 4px),
            radial-gradient(ellipse at 20% 50%, rgba(157, 0, 255, 0.1) 0%, transparent 50%),
            linear-gradient(135deg, #0f0515 0%, #1a0f2e 50%, #0f0515 100%)
        `
    },
    
    icons: {
        theme: '🦇',
        dark: '🌙',
        mystery: '🔮',
        bat: '🦇',
        skull: '💀',
        cross: '†'
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
            duration: 3000,
            easing: 'ease-in-out'
        }
    },
    
    applyColor: function(element, colorType) {
        if (element) {
            element.style.color = this.colors[colorType] || this.colors.primary;
        }
    },
    
    applyGlow: function(element, glowType = 'glowPurple') {
        if (element) {
            element.style.boxShadow = this.shadows[glowType] || this.shadows.glowPurple;
            element.style.transition = `box-shadow ${this.transitions.default}`;
        }
    },
    
    isMobile: function() {
        return window.innerWidth <= this.breakpoints.tablet;
    },
    
    log: function(message) {
        console.log(`%c🦇 [Gothic Metal] ${message}`, 'color: #9D00FF; font-weight: bold;');
    }
};

GothicMetalThemeConfig.log('Конфиг загружен успешно');

window.GothicMetalThemeConfig = GothicMetalThemeConfig;

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        GothicMetalThemeConfig.log('Инициализация завершена');
        
        document.querySelectorAll('h1, h2, h3').forEach(heading => {
            heading.style.textShadow = `0 0 15px ${GothicMetalThemeConfig.colors.primary}80`;
        });
        
        document.querySelectorAll('button, .btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.boxShadow = GothicMetalThemeConfig.shadows.glowStrong;
            });
            button.addEventListener('mouseleave', function() {
                this.style.boxShadow = GothicMetalThemeConfig.shadows.shadowColor;
            });
        });
    });
})();