/**
 * Файл: assets/js/epic-player-debug.js
 * ОТЛАДОЧНАЯ ВЕРСИЯ С РАСШИРЕННЫМ ЛОГИРОВАНИЕМ
 * 
 * ПОДКЛЮЧЕНИЕ В HTML ВМЕСТО epic-player.js:
 * <script src="/assets/js/epic-player-debug.js"></script>
 */

class EpicPlayerDebug {
    constructor(containerId = 'epic-player') {
        this.containerId = containerId;
        this.container = null;
        this.queue = [];
        this.currentIndex = 0;
        this.isPlaying = false;
        this.currentMode = 'audio';
        this.repeatMode = 'none';
        this.isShuffle = false;
        
        // Логирование старта
        console.log('%c🎸 EPIC PLAYER v4.1 DEBUG MODE', 'font-size: 16px; color: #FFD700; font-weight: bold;');
        console.log('Device:', {
            userAgent: navigator.userAgent.slice(0, 60),
            width: window.innerWidth,
            height: window.innerHeight,
            language: navigator.language
        });
        
        this.init();
    }
    
    init() {
        console.log('📍 [INIT] Starting initialization...');
        
        // 1. Найти контейнер
        this.container = document.getElementById(this.containerId);
        if (!this.container) {
            console.error('%c❌ [CRITICAL] Player container NOT found!', 'color: red; font-weight: bold;');
            console.error('Looking for ID:', this.containerId);
            return;
        }
        console.log('✅ [INIT] Container found:', this.container);
        
        // 2. Проверить все элементы
        this.checkElements();
        
        // 3. Настроить слушатели
        this.setupEventListeners();
        console.log('✅ [INIT] Event listeners set up');
        
        // 4. Загрузить альбом
        this.loadAlbumFromURL();
        
        console.log('✅ [INIT] Initialization complete!');
    }
    
    /**
     * Проверить наличие всех критических элементов
     */
    checkElements() {
        console.group('🔍 [ELEMENTS CHECK]');
        
        const elements = {
            audio: document.getElementById('audio-player'),
            video: document.getElementById('video-player'),
            display: this.container.querySelector('.player-display'),
            info: this.container.querySelector('.player-info'),
            title: this.container.querySelector('.track-title'),
            artist: this.container.querySelector('.track-artist'),
            album: this.container.querySelector('.track-album'),
            progress: this.container.querySelector('.progress-container'),
            progressBar: this.container.querySelector('.progress-bar'),
            progressFill: this.container.querySelector('.progress-fill'),
            progressHandle: this.container.querySelector('.progress-handle'),
            controls: this.container.querySelector('.player-controls'),
            playBtn: this.container.querySelector('.play-btn'),
            modes: this.container.querySelector('.player-modes'),
            modeBtns: this.container.querySelectorAll('.mode-btn'),
            queueContainer: this.container.querySelector('.queue-container'),
            queueList: this.container.querySelector('.queue-list'),
            lyricsContainer: this.container.querySelector('.lyrics-container'),
            lyricsText: this.container.querySelector('.lyrics-text')
        };
        
        // Логируем каждый элемент
        Object.entries(elements).forEach(([key, el]) => {
            if (Array.isArray(el)) {
                console.log(`  ${key}: ${el.length} items`, el);
            } else {
                const status = el ? '✅' : '❌';
                console.log(`  ${status} ${key}`, el);
            }
        });
        
        console.groupEnd();
        
        // Проверяем критические элементы
        const critical = ['audio', 'display', 'title', 'controls', 'modes'];
        const missing = critical.filter(key => !elements[key]);
        
        if (missing.length > 0) {
            console.error('%c❌ CRITICAL ELEMENTS MISSING: ' + missing.join(', '), 'color: red; font-weight: bold;');
        } else {
            console.log('%c✅ All critical elements present!', 'color: green; font-weight: bold;');
        }
        
        return elements;
    }
    
    /**
     * Настроить слушатели событий
     */
    setupEventListeners() {
        console.log('📍 [SETUP EVENTS]');
        
        // Кнопки управления
        const playBtn = this.container.querySelector('.play-btn');
        const prevBtn = this.container.querySelector('[data-action="prev"]');
        const nextBtn = this.container.querySelector('[data-action="next"]');
        
        if (!playBtn) {
            console.warn('⚠️ Play button not found!');
        } else {
            playBtn.addEventListener('click', () => {
                console.log('▶️ [EVENT] Play button clicked');
                this.togglePlay();
            });
        }
        
        if (!prevBtn) {
            console.warn('⚠️ Prev button not found!');
        } else {
            prevBtn.addEventListener('click', () => {
                console.log('⏮️ [EVENT] Prev button clicked');
                this.prevTrack();
            });
        }
        
        if (!nextBtn) {
            console.warn('⚠️ Next button not found!');
        } else {
            nextBtn.addEventListener('click', () => {
                console.log('⏭️ [EVENT] Next button clicked');
                this.nextTrack();
            });
        }
        
        // Режимы
        const modeBtns = this.container.querySelectorAll('.mode-btn');
        console.log(`📋 Found ${modeBtns.length} mode buttons`);
        
        modeBtns.forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                const mode = e.target.dataset.mode;
                console.log(`📺 [EVENT] Mode button clicked: ${mode}`);
                this.switchMode(mode);
            });
        });
        
        // Прогресс бар
        const progressBar = this.container.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.addEventListener('click', (e) => {
                console.log('🎚️ [EVENT] Progress bar clicked');
                this.seekTo(e);
            });
        }
        
        // Аудио элемент
        const audio = document.getElementById('audio-player');
        if (audio) {
            audio.addEventListener('timeupdate', () => this.updateProgress());
            audio.addEventListener('ended', () => {
                console.log('⏹️ [EVENT] Audio ended');
                this.onTrackEnded();
            });
            audio.addEventListener('play', () => {
                console.log('▶️ [EVENT] Audio playing');
                this.isPlaying = true;
                this.updatePlayButton();
            });
            audio.addEventListener('pause', () => {
                console.log('⏸️ [EVENT] Audio paused');
                this.isPlaying = false;
                this.updatePlayButton();
            });
            audio.addEventListener('error', (e) => {
                console.error('❌ [EVENT] Audio error:', e);
            });
        }
    }
    
    /**
     * Загрузить альбом из URL
     */
    loadAlbumFromURL() {
        console.log('📍 [LOAD ALBUM]');
        
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');
        
        if (!albumId) {
            console.warn('⚠️ No album ID in URL');
            return;
        }
        
        console.log(`🔄 Loading album ID: ${albumId}`);
        this.loadPlaylist(parseInt(albumId));
    }
    
    /**
     * Загрузить плейлист
     */
    async loadPlaylist(albumId) {
        console.group(`🎵 [LOAD PLAYLIST] Album #${albumId}`);
        
        try {
            const url = `/api/player/queue.php?album_id=${albumId}`;
            console.log(`🌐 Fetching: ${url}`);
            
            const response = await fetch(url);
            console.log(`📊 Response status: ${response.status}`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            console.log('📦 API Response:', data);
            
            if (!data.success) {
                throw new Error(data.error || 'API error');
            }
            
            if (!data.tracks || data.tracks.length === 0) {
                console.warn('⚠️ Playlist is empty!');
                console.groupEnd();
                return;
            }
            
            this.queue = data.tracks;
            this.currentIndex = 0;
            
            console.log(`✅ Loaded ${this.queue.length} tracks`);
            console.table(this.queue.map(t => ({
                id: t.id,
                title: t.title,
                duration: t.duration,
                audio: t.fullAudioPath ? '✅' : '❌'
            })));
            
            this.renderQueue();
            this.loadTrack(0);
            
        } catch (error) {
            console.error('%c❌ [ERROR] Failed to load playlist:', 'color: red; font-weight: bold;');
            console.error(error);
        }
        
        console.groupEnd();
    }
    
    /**
     * Загрузить трек
     */
    loadTrack(index) {
        console.group(`🎵 [LOAD TRACK] Index #${index}`);
        
        if (index < 0 || index >= this.queue.length) {
            console.error(`❌ Invalid index: ${index}`);
            console.groupEnd();
            return;
        }
        
        this.currentIndex = index;
        const track = this.queue[index];
        
        console.log(`Track: ${track.title}`);
        console.log(`Duration: ${track.duration}s`);
        console.log(`Audio path: ${track.fullAudioPath}`);
        console.log(`Video path: ${track.videoPath || 'none'}`);
        
        // Обновляем информацию
        this.updateTrackInfo(track);
        
        // Загружаем аудио
        if (track.fullAudioPath) {
            this.loadAudio(track);
        } else {
            console.error('❌ No audio path provided!');
        }
        
        // Загружаем очередь
        this.updateQueueHighlight();
        
        console.groupEnd();
    }
    
    /**
     * Загрузить аудио
     */
    loadAudio(track) {
        console.log('🔊 [LOAD AUDIO]');
        
        const audio = document.getElementById('audio-player');
        if (!audio) {
            console.error('❌ Audio element not found!');
            return;
        }
        
        const audioPath = track.fullAudioPath.startsWith('/') 
            ? track.fullAudioPath 
            : '/' + track.fullAudioPath;
        
        console.log(`Path: ${audioPath}`);
        audio.src = audioPath;
        console.log('✅ Audio loaded');
    }
    
    /**
     * Обновить информацию трека
     */
    updateTrackInfo(track) {
        console.log('📝 [UPDATE INFO]');
        
        const title = this.container.querySelector('.track-title');
        const artist = this.container.querySelector('.track-artist');
        const album = this.container.querySelector('.track-album');
        
        if (title) {
            title.textContent = track.title || 'Unknown';
            console.log(`Title: ${title.textContent}`);
        }
        
        if (artist) {
            artist.textContent = 'Master of Illusion';
        }
        
        if (album) {
            album.textContent = track.albumTitle || 'Album';
            console.log(`Album: ${album.textContent}`);
        }
        
        // Обложка
        const cover = this.container.querySelector('.player-cover img');
        if (cover && track.coverImagePath) {
            const coverPath = track.coverImagePath.startsWith('/') 
                ? track.coverImagePath 
                : '/' + track.coverImagePath;
            cover.src = coverPath;
            console.log(`Cover: ${coverPath}`);
        }
    }
    
    /**
     * Отрендерить очередь
     */
    renderQueue() {
        console.log('📋 [RENDER QUEUE]');
        
        const queueList = this.container.querySelector('.queue-list');
        if (!queueList) {
            console.warn('⚠️ Queue list not found!');
            return;
        }
        
        console.log(`Rendering ${this.queue.length} items...`);
        
        queueList.innerHTML = this.queue.map((track, index) => `
            <li class="queue-item" data-index="${index}">
                <span class="queue-number">${index + 1}</span>
                <div class="queue-info">
                    <div class="queue-track-name">${this.escapeHtml(track.title)}</div>
                    <div class="queue-track-album">${this.escapeHtml(track.albumTitle || '')}</div>
                </div>
                <span class="queue-duration">${this.formatTime(track.duration || 0)}</span>
            </li>
        `).join('');
        
        // Добавляем события
        queueList.querySelectorAll('.queue-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                console.log(`🎵 Queue item clicked: ${index}`);
                this.playTrack(index);
            });
        });
        
        console.log('✅ Queue rendered');
    }
    
    /**
     * Обновить подсветку текущего трека
     */
    updateQueueHighlight() {
        const items = this.container.querySelectorAll('.queue-item');
        items.forEach((item, index) => {
            if (index === this.currentIndex) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }
    
    /**
     * Переключить воспроизведение
     */
    togglePlay() {
        console.log(`🎵 [TOGGLE PLAY] Current: ${this.isPlaying}`);
        
        const audio = document.getElementById('audio-player');
        if (!audio) {
            console.error('❌ Audio element not found!');
            return;
        }
        
        if (this.isPlaying) {
            audio.pause();
        } else {
            audio.play().catch(err => {
                console.error('❌ Play error:', err);
            });
        }
    }
    
    /**
     * Воспроизвести трек
     */
    playTrack(index) {
        console.log(`▶️ [PLAY TRACK] Index: ${index}`);
        this.loadTrack(index);
        const audio = document.getElementById('audio-player');
        if (audio) {
            audio.play().catch(err => console.error('❌ Play error:', err));
        }
    }
    
    /**
     * Следующий трек
     */
    nextTrack() {
        console.log(`⏭️ [NEXT TRACK]`);
        if (this.queue.length === 0) return;
        
        this.currentIndex++;
        if (this.currentIndex >= this.queue.length) {
            this.currentIndex = 0;
        }
        
        this.playTrack(this.currentIndex);
    }
    
    /**
     * Предыдущий трек
     */
    prevTrack() {
        console.log(`⏮️ [PREV TRACK]`);
        if (this.queue.length === 0) return;
        
        this.currentIndex--;
        if (this.currentIndex < 0) {
            this.currentIndex = this.queue.length - 1;
        }
        
        this.playTrack(this.currentIndex);
    }
    
    /**
     * Переключить режим
     */
    switchMode(mode) {
        console.log(`📺 [SWITCH MODE] ${mode}`);
        this.currentMode = mode;
        
        const display = this.container.querySelector('.player-display');
        const queue = this.container.querySelector('.queue-container');
        const lyrics = this.container.querySelector('.lyrics-container');
        
        // Скрыть все
        [display, queue, lyrics].forEach(el => {
            if (el) el.style.display = 'none';
        });
        
        // Показать нужный
        switch(mode) {
            case 'audio':
            case 'video':
                if (display) display.style.display = 'block';
                break;
            case 'queue':
                if (queue) queue.style.display = 'block';
                break;
            case 'lyrics':
                if (lyrics) lyrics.style.display = 'block';
                break;
        }
    }
    
    /**
     * Поиск в прогрессе
     */
    seekTo(e) {
        console.log('🎚️ [SEEK]');
        const progressBar = this.container.querySelector('.progress-bar');
        const audio = document.getElementById('audio-player');
        
        if (!progressBar || !audio || !audio.duration) return;
        
        const rect = progressBar.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        audio.currentTime = percent * audio.duration;
    }
    
    /**
     * Обновить прогресс
     */
    updateProgress() {
        const audio = document.getElementById('audio-player');
        if (!audio) return;
        
        const percent = audio.duration ? (audio.currentTime / audio.duration) * 100 : 0;
        const fill = this.container.querySelector('.progress-fill');
        const times = this.container.querySelectorAll('.time');
        
        if (fill) fill.style.width = percent + '%';
        if (times[0]) times[0].textContent = this.formatTime(audio.currentTime);
        if (times[1]) times[1].textContent = this.formatTime(audio.duration);
    }
    
    /**
     * Обновить кнопку play
     */
    updatePlayButton() {
        const btn = this.container.querySelector('.play-btn');
        if (btn) {
            btn.textContent = this.isPlaying ? '⏸' : '▶';
        }
    }
    
    /**
     * Трек завершён
     */
    onTrackEnded() {
        console.log('⏹️ [TRACK ENDED]');
        this.nextTrack();
    }
    
    /**
     * Формат времени
     */
    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
    
    /**
     * Escape HTML
     */
    escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text || '').replace(/[&<>"']/g, m => map[m]);
    }
}

// ============================================
// ИНИЦИАЛИЗАЦИЯ
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('%c🎸 DOM LOADED - Initializing EpicPlayer...', 'font-size: 14px; color: #FFD700;');
    
    if (document.getElementById('epic-player')) {
        window.epicPlayer = new EpicPlayerDebug('epic-player');
        console.log('%c✅ EPIC PLAYER READY!', 'font-size: 16px; color: green; font-weight: bold;');
    } else {
        console.error('%c❌ Epic player container not found in DOM!', 'color: red; font-weight: bold;');
    }
});

console.log('%c✅ Epic Player Debug Script Loaded', 'font-size: 12px; color: #FFD700;');