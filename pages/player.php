<?php
/**
 * Epic Player v4.4 - С ЭКВАЛАЙЗЕРОМ И КАЧЕСТВЕННЫМ ЗВУКОМ
 */
?>

<div id="epic-player" class="epic-player" role="region" aria-label="Музыкальный плеер">
    
    <!-- ЭКРАН ПЛЕЕРА -->
    <div class="player-display">
        <div class="player-cover" style="display: flex; width: 100%; height: 100%;">
            <img src="/assets/images/placeholder.png" alt="Обложка" style="width: 100%; height: 100%; object-fit: contain; background: #000;">
        </div>
        
        <video id="video-player" controls controlsList="nodownload" style="display: none; width: 100%; height: 100%; object-fit: contain; background: #000;"></video>
        <audio id="audio-player" crossorigin="anonymous" style="display: none;"></audio>
    </div>
    
    <!-- ИНФОРМАЦИЯ О ТРЕКЕ -->
    <div class="player-info">
        <h2 class="track-title">Название трека</h2>
        <p class="track-artist">Master of Illusion</p>
        <p class="track-album">Альбом</p>
    </div>
    
    <!-- ПРОГРЕСС БАР -->
    <div class="progress-container">
        <span class="time">0:00</span>
        <div class="progress-bar">
            <div class="progress-fill"></div>
            <div class="progress-handle"></div>
        </div>
        <span class="time">0:00</span>
    </div>
    
    <!-- РЕГУЛЯТОР ГРОМКОСТИ -->
    <div class="volume-container">
        <span class="volume-icon" title="Вкл/выкл звук">🔊</span>
        <input type="range" class="volume-slider" min="0" max="100" value="80" title="Громкость" aria-label="Регулятор громкости">
        <span class="volume-percentage">80%</span>
    </div>
    
    <!-- КНОПКИ УПРАВЛЕНИЯ -->
    <div class="player-controls">
        <button class="control-btn" data-action="shuffle" title="Перемешивание">🔀</button>
        <button class="control-btn" data-action="prev" title="Предыдущий">⏮</button>
        <button class="control-btn play-btn" title="Проиграть">▶</button>
        <button class="control-btn" data-action="next" title="Следующий">⏭</button>
        <button class="control-btn" data-action="repeat" title="Повтор">🔁</button>
    </div>
    
    <!-- РЕЖИМЫ -->
    <div class="player-modes" role="tablist">
        <button class="mode-btn active" data-mode="audio" role="tab">🎵 АУДИО</button>
        <button class="mode-btn" data-mode="video" role="tab">🎬 ВИДЕО</button>
        <button class="mode-btn" data-mode="queue" role="tab">📋 ОЧЕРЕДЬ</button>
        <button class="mode-btn" data-mode="lyrics" role="tab">📄 ТЕКСТ</button>
        <button class="mode-btn" data-mode="equalizer" role="tab">🎚️ ЭКВАЛАЙЗЕР</button>
    </div>
    
    <!-- ЭКВАЛАЙЗЕР - НОВОЕ! -->
    <div class="equalizer-container" style="display: none;">
        <div class="equalizer-title">🎚️ Эквалайзер</div>
        
        <div class="eq-presets">
            <button class="eq-preset-btn active" data-preset="flat">Flat</button>
            <button class="eq-preset-btn" data-preset="power-metal">Power Metal</button>
            <button class="eq-preset-btn" data-preset="heavy-metal">Heavy Metal</button>
            <button class="eq-preset-btn" data-preset="rock">Rock</button>
            <button class="eq-preset-btn" data-preset="punk-rock">Punk Rock</button>
            <button class="eq-preset-btn" data-preset="gothic">Gothic</button>
            <button class="eq-preset-btn" data-preset="symphonic">Symphonic</button>
        </div>
        
        <div class="eq-info">
            <strong>🎸 Настроено под металл!</strong><br>
            Эквалайзер оптимизирован для жанров: Power Metal, Heavy Metal, Rock, Punk Rock, Gothic, Symphonic Metal.
            <br><br>
            <strong>Качество звука:</strong> Web Audio API обеспечивает максимальное качество воспроизведения ваших WAV файлов без потерь.
            <br><br>
            <small style="opacity: 0.7;">📱 На некоторых мобильных устройствах эквалайзер может быть недоступен из-за ограничений браузера.</small>
        </div>
    </div>
    
    <!-- ОЧЕРЕДЬ -->
    <div class="queue-container" style="display: none;">
        <div class="queue-title">📋 Очередь воспроизведения</div>
        <ul class="queue-list"></ul>
    </div>
    
    <!-- ТЕКСТ ПЕСНИ -->
    <div class="lyrics-container" style="display: none;">
        <div class="lyrics-text"></div>
    </div>
    
</div>

<link rel="stylesheet" href="/assets/css/epic-player.css">
<script src="/assets/js/epic-player.js"></script>