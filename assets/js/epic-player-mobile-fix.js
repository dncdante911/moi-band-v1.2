/**
 * Файл: assets/js/epic-player-mobile-fix.js
 * ИСПРАВЛЕНИЯ ПЛЕЕРА ДЛЯ МОБИЛЕЙ
 * Добавить ПЕРЕД подключением epic-player.js
 * 
 * Подключение в HTML:
 * <script src="/assets/js/epic-player-mobile-fix.js"></script>
 * <script src="/assets/js/epic-player.js"></script>
 */

// ============================================
// ДЕТЕКТОР МОБИЛЬНОГО УСТРОЙСТВА
// ============================================

const isMobileDevice = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
        navigator.userAgent
    );
};

const isTablet = () => {
    return /iPad|Android/i.test(navigator.userAgent) && 
           window.matchMedia("(min-width: 600px)").matches;
};

const isMobile = isMobileDevice() && !isTablet();

console.log('📱 Device Type:', {
    isMobile: isMobile,
    isTablet: isTablet(),
    userAgent: navigator.userAgent.slice(0, 50)
});

// ============================================
// ХЕЛПЕР: БЕЗОПАСНЫЙ querySelector
// ============================================

const safeQuery = (selector, context = document) => {
    try {
        return context.querySelector(selector);
    } catch (e) {
        console.warn('⚠️ Query selector failed:', selector);
        return null;
    }
};

const safeQueryAll = (selector, context = document) => {
    try {
        return context.querySelectorAll(selector);
    } catch (e) {
        console.warn('⚠️ Query selector all failed:', selector);
        return [];
    }
};

// ============================================
// ПОЛИФИЛЛ: Для старых браузеров
// ============================================

if (!String.prototype.padStart) {
    String.prototype.padStart = function(targetLength, padString) {
        targetLength = Math.floor(targetLength) || 0;
        if (targetLength <= this.length) return String(this);
        
        padString = String(typeof padString !== 'undefined' ? padString : ' ');
        if (padString.length === 0) return String(this);
        
        const padLen = targetLength - this.length;
        const repeats = Math.ceil(padLen / padString.length);
        return padString.repeat(repeats).slice(0, padLen) + String(this);
    };
}

// ============================================
// РАСШИРЕНИЕ: EpicPlayerMobileHelper
// ============================================

class EpicPlayerMobileHelper {
    constructor() {
        this.viewport = this.detectViewport();
        this.init();
    }
    
    /**
     * Определить размер экрана
     */
    detectViewport() {
        const width = window.innerWidth;
        
        if (width < 480) return 'xs'; // Extra Small
        if (width < 768) return 'sm'; // Small
        if (width < 1024) return 'md'; // Medium
        if (width < 1440) return 'lg'; // Large
        return 'xl'; // Extra Large
    }
    
    /**
     * Инициализация
     */
    init() {
        console.log('🎸 Mobile Helper initialized:', this.viewport);
        
        // Слушаем изменение размера
        window.addEventListener('resize', () => this.onWindowResize());
        
        // Проверяем, что плеер готов
        document.addEventListener('DOMContentLoaded', () => this.ensurePlayerReady());
        
        // Оптимизация для мобилей
        if (isMobile) {
            this.optimizeForMobile();
        }
    }
    
    /**
     * При изменении размера окна
     */
    onWindowResize() {
        const newViewport = this.detectViewport();
        if (newViewport !== this.viewport) {
            console.log('📏 Viewport changed:', this.viewport, '→', newViewport);
            this.viewport = newViewport;
            this.adjustPlayer();
        }
    }
    
    /**
     * Гарантировать, что все элементы плеера готовы
     */
    ensurePlayerReady() {
        const player = safeQuery('#epic-player');
        if (!player) {
            console.warn('⚠️ Epic player not found!');
            return;
        }
        
        // Проверяем критические элементы
        const elements = {
            display: safeQuery('.player-display', player),
            info: safeQuery('.player-info', player),
            progress: safeQuery('.progress-container', player),
            controls: safeQuery('.player-controls', player),
            modes: safeQuery('.player-modes', player),
            queue: safeQuery('.queue-container', player),
            lyrics: safeQuery('.lyrics-container', player)
        };
        
        // Логируем состояние
        const missing = Object.entries(elements)
            .filter(([_, el]) => !el)
            .map(([key]) => key);
        
        if (missing.length > 0) {
            console.warn('⚠️ Missing elements:', missing);
        } else {
            console.log('✅ All player elements found');
        }
        
        // Показываем плеер
        if (player) {
            player.style.display = 'block';
        }
    }
    
    /**
     * Оптимизация для мобилей
     */
    optimizeForMobile() {
        console.log('🔧 Optimizing for mobile...');
        
        const player = safeQuery('#epic-player');
        if (!player) return;
        
        // Увеличиваем зоны касания на кнопках
        const buttons = safeQueryAll('.control-btn, .mode-btn', player);
        buttons.forEach(btn => {
            btn.style.minWidth = '44px';
            btn.style.minHeight = '44px';
        });
        
        // Плавный скролл
        if ('scrollBehavior' in document.documentElement.style) {
            document.documentElement.style.scrollBehavior = 'smooth';
        }
        
        // Отключаем некоторые эффекты для экономии батареи
        const animElements = safeQueryAll('[class*="animate"]', player);
        animElements.forEach(el => {
            el.style.animationPlayState = 'paused';
            setTimeout(() => {
                el.style.animationPlayState = 'running';
            }, 1000);
        });
    }
    
    /**
     * Адаптировать плеер к размеру экрана
     */
    adjustPlayer() {
        const player = safeQuery('#epic-player');
        if (!player) return;
        
        const display = safeQuery('.player-display', player);
        if (!display) return;
        
        // На xs экранах - вертикальный режим
        if (this.viewport === 'xs') {
            display.style.aspectRatio = '16 / 14';
            display.style.minHeight = '150px';
        }
        // На sm экранах - нормальный режим
        else if (this.viewport === 'sm') {
            display.style.aspectRatio = '16 / 12';
            display.style.minHeight = '180px';
        }
        // На больших - 16:9
        else {
            display.style.aspectRatio = '16 / 9';
            display.style.minHeight = '300px';
        }
    }
}

// ============================================
// ИНИЦИАЛИЗАЦИЯ
// ============================================

let mobileHelper = null;

document.addEventListener('DOMContentLoaded', () => {
    if (isMobile) {
        console.log('📱 Initializing mobile player...');
        mobileHelper = new EpicPlayerMobileHelper();
    }
});

// ============================================
// ЭКСПОРТ ДЛЯ ИСПОЛЬЗОВАНИЯ
// ============================================

window.EpicPlayerMobile = {
    isMobile,
    isTablet: isTablet(),
    viewport: () => mobileHelper?.viewport || 'unknown',
    helper: () => mobileHelper
};

console.log('✅ Epic Player Mobile Fix loaded');