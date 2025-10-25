<?php
/**
 * Компонент: Epic Player - КРАСИВАЯ И РАБОЧАЯ ВЕРСИЯ
 */
?>

<div id="epic-player" class="epic-player" role="region" aria-label="Музыкальный плеер">
    
    <!-- === ЭКРАН ПЛЕЕРА (главный контейнер) === -->
    <div class="player-display">
        
        <!-- АУДИО: Обложка по умолчанию -->
        <div class="player-cover" style="display: flex; width: 100%; height: 100%;">
            <img src="/assets/images/placeholder.png" alt="Обложка" style="width: 100%; height: 100%; object-fit: contain; background: #000;">
        </div>
        
        <!-- ВИДЕО: Скрыто по умолчанию -->
        <video 
            id="video-player"
            controls 
            controlsList="nodownload"
            style="display: none; width: 100%; height: 100%; object-fit: contain; background: #000;">
        </video>
        
        <!-- АУДИО (невидимый плеер для фона) -->
        <audio 
            id="audio-player"
            style="display: none;">
        </audio>
        
    </div>
    
    <!-- === ИНФОРМАЦИЯ О ТРЕКЕ === -->
    <div class="player-info">
        <h2 class="track-title">Название трека</h2>
        <p class="track-artist">Master of Illusion</p>
        <p class="track-album">Альбом</p>
    </div>
    
    <!-- === ПРОГРЕСС БАР === -->
    <div class="progress-container">
        <span class="time">0:00</span>
        <div class="progress-bar">
            <div class="progress-fill"></div>
            <div class="progress-handle"></div>
        </div>
        <span class="time">0:00</span>
    </div>
    
    <!-- === КНОПКИ УПРАВЛЕНИЯ === -->
    <div class="player-controls">
        <button class="control-btn" data-action="shuffle" title="Перемешивание">🔀</button>
        <button class="control-btn" data-action="prev" title="Предыдущий">⏮</button>
        <button class="control-btn play-btn" title="Проиграть">▶</button>
        <button class="control-btn" data-action="next" title="Следующий">⏭</button>
        <button class="control-btn" data-action="repeat" title="Повтор">🔁</button>
    </div>
    
    <!-- === РЕЖИМЫ === -->
    <div class="player-modes" role="tablist">
        <button class="mode-btn active" data-mode="audio" role="tab" aria-selected="true" title="Аудио режим">🎵 АУДИО</button>
        <button class="mode-btn" data-mode="video" role="tab" aria-selected="false" title="Видео режим">🎬 ВИДЕО</button>
        <button class="mode-btn" data-mode="queue" role="tab" aria-selected="false" title="Очередь треков">📋 ОЧЕРЕДЬ</button>
        <button class="mode-btn" data-mode="lyrics" role="tab" aria-selected="false" title="Текст песни">📄 ТЕКСТ</button>
    </div>
    
    <!-- === СПИСОК ТРЕКОВ (ОЧЕРЕДЬ) === -->
    <div class="queue-container" style="display: none;">
        <div class="queue-title">📋 Очередь воспроизведения</div>
        <ul class="queue-list">
            <!-- Заполняется JavaScript -->
        </ul>
    </div>
    
    <!-- === ТЕКСТ ПЕСНИ (LYRICS) === -->
    <div class="lyrics-container" style="display: none;">
        <div class="lyrics-text">
            <!-- Заполняется JavaScript -->
        </div>
    </div>
    
</div>

<!-- Подключаем стили -->
<link rel="stylesheet" href="/assets/css/epic-player.css">

<!-- Подключаем скрипт -->
 <script src="/assets/js/epic-player-mobile-fix.js"></script>
 <script src="/assets/js/epic-player.js"></script>

<script>
// Инициализация плеера при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    if (window.epicPlayer) {
        console.log('✅ Плеер уже инициализирован');
    }
});
</script>