/**
 * SIMPLE AMBILIGHT v2.0
 * Только фон - никаких кнопок и перекрытий
 */

class SimpleAmbilight {
    constructor(player) {
        this.player = player;
        this.isActive = true; // Всегда включён
        this.animationFrame = null;
        this.currentGenre = 'flat';
        
        this.init();
    }
    
    init() {
        // Сразу активируем
        document.body.classList.add('visualizer-active');
        
        // Запускаем анимацию
        this.start();
        
        console.log('🎨 Simple Ambilight Ready!');
    }
    
    start() {
        this.animate();
    }
    
    animate() {
        this.animationFrame = requestAnimationFrame(() => this.animate());
        
        // Просто запускаем - без анализа басов
        // Пусть плавно пульсирует постоянно
    }
    
    updateGenre(genre) {
        // Убираем старые классы жанров
        const genres = ['power-metal', 'heavy-metal', 'rock', 'punk-rock', 'gothic', 'symphonic', 'flat'];
        genres.forEach(g => document.body.classList.remove('genre-' + g));
        
        // Добавляем новый
        document.body.classList.add('genre-' + genre);
        this.currentGenre = genre;
        
        console.log('🎨 Background color:', genre);
    }
    
    getAverage(array, start, end) {
        let sum = 0;
        let count = 0;
        
        for (let i = start; i < end && i < array.length; i++) {
            sum += array[i];
            count++;
        }
        
        return count > 0 ? sum / count : 0;
    }
}

// Инициализация после загрузки плеера
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        if (window.epicPlayer) {
            window.simpleAmbilight = new SimpleAmbilight(window.epicPlayer);
            
            // Обновляем цвет при смене пресета эквалайзера
            const originalApplyEQ = window.epicPlayer.applyEQPreset.bind(window.epicPlayer);
            window.epicPlayer.applyEQPreset = function(preset) {
                originalApplyEQ(preset);
                if (window.simpleAmbilight) {
                    window.simpleAmbilight.updateGenre(preset);
                }
            };
            
            console.log('✅ Simple Ambilight Active!');
        }
    }, 1000);
});