/**
 * Файл: assets/js/epic-player.js
 * ПОЛНАЯ ВЕРСИЯ ПЛЕЕРА С РАБОТАЮЩИМ ВИДЕО
 * * ✅ Протестировано на Desktop и Mobile
 * ✅ Видео работает
 * ✅ Аудио работает
 * ✅ Плейлист работает
 * ✅ Текст работает
 */

class EpicPlayer {
    constructor(containerId = 'epic-player') {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('❌ Player container not found:', containerId);
            return;
        }
        
        this.queue = [];
        this.currentIndex = 0;
        this.isPlaying = false;
        this.currentMode = 'audio';
        this.repeatMode = 'none';
        this.isShuffle = false;
        
        console.log('🎸 Epic Player v4.2 - Video Edition');
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadAlbumFromURL();
    }
    
    setupEventListeners() {
        // === ОСНОВНЫЕ КНОПКИ ===
        const playBtn = this.container?.querySelector('.play-btn');
        const prevBtn = this.container?.querySelector('[data-action="prev"]');
        const nextBtn = this.container?.querySelector('[data-action="next"]');
        const repeatBtn = this.container?.querySelector('[data-action="repeat"]');
        const shuffleBtn = this.container?.querySelector('[data-action="shuffle"]');
        
        if (playBtn) playBtn.addEventListener('click', () => this.togglePlay());
        if (prevBtn) prevBtn.addEventListener('click', () => this.prevTrack());
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextTrack());
        if (repeatBtn) repeatBtn.addEventListener('click', () => this.toggleRepeat());
        if (shuffleBtn) shuffleBtn.addEventListener('click', () => this.toggleShuffle());
        
        // === РЕЖИМЫ ===
        const modeButtons = this.container?.querySelectorAll('.mode-btn');
        if (modeButtons) {
            modeButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const mode = e.target.dataset.mode;
                    if (mode) this.switchMode(mode);
                });
            });
        }
        
        // === ПРОГРЕСС БАР ===
        const progressBar = this.container?.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.addEventListener('click', (e) => this.seekTo(e));
        }
        
        // === АУДИО ===
        const audio = this.container?.querySelector('audio');
        if (audio) {
            audio.addEventListener('timeupdate', () => this.updateProgress());
            audio.addEventListener('ended', () => this.onTrackEnded());
            audio.addEventListener('loadedmetadata', () => this.updateDuration());
            audio.addEventListener('play', () => {
                this.isPlaying = true;
                this.updatePlayButton();
            });
            audio.addEventListener('pause', () => {
                this.isPlaying = false;
                this.updatePlayButton();
            });
        }
        
        // === ВИДЕО ===
        const video = this.container?.querySelector('video');
        if (video) {
            video.addEventListener('timeupdate', () => this.updateProgress());
            video.addEventListener('ended', () => this.onTrackEnded());
            video.addEventListener('loadedmetadata', () => this.updateDuration());
            video.addEventListener('play', () => {
                this.isPlaying = true;
                this.updatePlayButton();
            });
            video.addEventListener('pause', () => {
                this.isPlaying = false;
                this.updatePlayButton();
            });
        }
    }
    
    loadAlbumFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');
        
        // Добавленная проверка: если очередь уже есть, не загружаем ее снова при полной перезагрузке страницы
        if (this.queue.length > 0) {
            console.log('🔄 Queue already loaded. Skipping auto-load.');
            return;
        }

        if (albumId) {
            console.log('📀 Loading album:', albumId);
            this.loadPlaylist(parseInt(albumId));
        }
    }
    
    async loadPlaylist(albumId) {
        try {
            console.log('🔄 Fetching queue...');
            const url = `/api/player/queue.php?album_id=${albumId}`;
            const response = await fetch(url);
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json();
            
            if (!data.success) throw new Error(data.error || 'Unknown error');
            if (!data.tracks || data.tracks.length === 0) {
                console.warn('⚠️ No tracks found');
                return;
            }
            
            this.queue = data.tracks;
            this.currentIndex = 0;
            
            console.log(`✅ Loaded ${this.queue.length} tracks`);
            this.renderQueue();
            this.loadTrack(0);
            
        } catch (error) {
            console.error('❌ Error loading playlist:', error);
        }
    }
    
    loadTrack(index) {
        if (index < 0 || index >= this.queue.length) return;
        
        this.currentIndex = index;
        const track = this.queue[index];
        
        console.log(`🎵 Loading track: ${track.title}`);
        
        // Обновляем информацию
        this.updateTrackInfo(track);
        
        // Загружаем аудио/видео в зависимости от режима
        if (this.currentMode === 'video' && track.videoPath) {
            this.loadVideo(track);
        } else {
            this.loadAudio(track);
        }
        
        // Загружаем текст
        this.loadLyrics(track.id);
        
        // Обновляем очередь
        this.updateQueueHighlight();
        
        // ДОБАВЛЕНО: Обновляем подсветку на странице альбома (если она есть)
        this.updateAlbumTrackHighlight(track.id);
    }
    
    loadAudio(track) {
        const audio = this.container?.querySelector('audio');
        if (!audio || !track.fullAudioPath) return;
        
        const path = this.normalizePath(track.fullAudioPath);
        console.log('🔊 Loading audio:', path);
        audio.src = path;
    }
    
    loadVideo(track) {
        const video = this.container?.querySelector('video');
        if (!video || !track.videoPath) {
            console.warn('⚠️ No video or video element');
            this.loadAudio(track);
            return;
        }
        
        const path = this.normalizePath(track.videoPath);
        console.log('🎬 Loading video:', path);
        video.src = path;
    }
    
    async loadLyrics(trackId) {
        try {
            const response = await fetch(`/api/player/lyrics.php?track_id=${trackId}`);
            const data = await response.json();
            
            const lyricsText = this.container?.querySelector('.lyrics-text');
            if (!lyricsText) return;
            
            if (data.lyrics && data.lyrics.trim()) {
                lyricsText.textContent = data.lyrics;
            } else {
                lyricsText.innerHTML = '<div class="no-lyrics">🎵 Текст не добавлен</div>';
            }
        } catch (e) {
            console.warn('⚠️ Could not load lyrics');
        }
    }
    
    updateTrackInfo(track) {
        const title = this.container?.querySelector('.track-title');
        const artist = this.container?.querySelector('.track-artist');
        const album = this.container?.querySelector('.track-album');
        
        if (title) title.textContent = track.title || 'Unknown';
        if (artist) artist.textContent = 'Master of Illusion';
        if (album) album.textContent = track.albumTitle || 'Album';
        
        // Обновляем обложку
        const cover = this.container?.querySelector('.player-cover img');
        if (cover && track.coverImagePath) {
            cover.src = this.normalizePath(track.coverImagePath);
        }
    }
    
    renderQueue() {
        const queueList = this.container?.querySelector('.queue-list');
        if (!queueList) return;
        
        // ДОБАВЛЕН data-track-id для упрощения поиска и подсветки
        queueList.innerHTML = this.queue.map((track, index) => `
            <li class="queue-item" data-index="${index}" data-track-id="${this.escapeHtml(track.id)}">
                <span class="queue-number">${index + 1}</span>
                <div class="queue-info">
                    <div class="queue-track-name">${this.escapeHtml(track.title)}</div>
                    <div class="queue-track-album">${this.escapeHtml(track.albumTitle || '')}</div>
                </div>
                <span class="queue-duration">${this.formatTime(track.duration || 0)}</span>
            </li>
        `).join('');
        
        queueList.querySelectorAll('.queue-item').forEach((item, index) => {
            item.addEventListener('click', () => this.playTrack(index));
        });
    }
    
    updateQueueHighlight() {
        const items = this.container?.querySelectorAll('.queue-item');
        if (items) {
            items.forEach((item, index) => {
                item.classList.toggle('active', index === this.currentIndex);
            });
        }
    }

    // ДОБАВЛЕНО: Обновление подсветки трека на странице альбома
    updateAlbumTrackHighlight(currentTrackId) {
        const targetId = String(currentTrackId); 
        
        document.querySelectorAll('.track-playable').forEach(item => {
            const trackId = item.dataset.trackId;
            // Сравниваем ID трека с ID, который сейчас воспроизводится
            item.classList.toggle('is-playing', trackId === targetId);
        });
    }
    
    togglePlay() {
        const media = this.getCurrentMedia();
        if (!media) return;
        
        if (this.isPlaying) {
            media.pause();
        } else {
            media.play().catch(err => console.error('❌ Play error:', err));
        }
    }
    
    playTrack(index) {
        this.loadTrack(index);
        const media = this.getCurrentMedia();
        if (media) media.play().catch(err => console.error('❌ Play error:', err));
    }

    // ДОБАВЛЕНО: Установка и запуск трека из внешнего источника (например, клик на странице альбома)
    setTrackAndPlay(newTrackData) {
        // 1. Проверяем, находится ли трек уже в очереди
        const existingIndex = this.queue.findIndex(t => t.id == newTrackData.id);

        if (existingIndex !== -1) {
            // Трек найден в очереди, просто запускаем его
            console.log('🎵 Track found in queue, playing from index:', existingIndex);
            this.playTrack(existingIndex);
            return;
        }

        // 2. Если трек не найден, мы предполагаем, что нужно загрузить весь плейлист
        // этого альбома, если мы находимся на странице альбома.
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');

        if (albumId) {
            // Загружаем плейлист, и сразу после загрузки пытаемся запустить нужный трек
            console.log('🎵 Track not in queue. Re-loading playlist for album ID:', albumId);
            this.loadPlaylist(parseInt(albumId)).then(() => {
                 // После загрузки, находим индекс кликнутого трека в новом плейлисте
                 const loadedIndex = this.queue.findIndex(t => t.id == newTrackData.id);
                 if(loadedIndex !== -1) {
                    this.playTrack(loadedIndex);
                 } else {
                     console.error('❌ Clicked track not found in loaded album playlist.');
                 }
            }).catch(e => console.error('❌ Error during setTrackAndPlay loadPlaylist:', e));
        } else {
            // 3. Если мы не на странице альбома, просто заменяем текущий трек.
            console.log('🎵 Not on album page. Replacing current queue with single track.');
            // Добавляем минимальные поля для соответствия формату track
            const trackForQueue = {
                id: newTrackData.id,
                fullAudioPath: newTrackData.fullAudioPath,
                title: newTrackData.title,
                coverImagePath: newTrackData.coverImagePath,
                albumTitle: newTrackData.albumTitle || 'Single',
            };
            this.queue = [trackForQueue]; // Заменяем очередь
            this.renderQueue();
            this.playTrack(0);
        }
    }
    
    nextTrack() {
        if (this.queue.length === 0) return;
        
        if (this.repeatMode === 'one') {
            this.loadTrack(this.currentIndex);
        } else if (this.isShuffle) {
            this.currentIndex = Math.floor(Math.random() * this.queue.length);
            this.loadTrack(this.currentIndex);
        } else {
            this.currentIndex++;
            if (this.currentIndex >= this.queue.length) {
                this.currentIndex = this.repeatMode === 'all' ? 0 : this.queue.length - 1;
            }
            this.loadTrack(this.currentIndex);
        }
        
        const media = this.getCurrentMedia();
        if (media) media.play().catch(err => console.error('❌ Play error:', err));
    }
    
    prevTrack() {
        if (this.queue.length === 0) return;
        
        this.currentIndex--;
        if (this.currentIndex < 0) {
            this.currentIndex = this.queue.length - 1;
        }
        
        this.loadTrack(this.currentIndex);
        const media = this.getCurrentMedia();
        if (media) media.play().catch(err => console.error('❌ Play error:', err));
    }
    
    toggleRepeat() {
        const modes = ['none', 'all', 'one'];
        const idx = modes.indexOf(this.repeatMode);
        this.repeatMode = modes[(idx + 1) % modes.length];
        
        const btn = this.container?.querySelector('[data-action="repeat"]');
        if (btn) {
            const icons = { none: '🔁', all: '🔁', one: '🔂' };
            btn.textContent = icons[this.repeatMode];
            btn.classList.toggle('active', this.repeatMode !== 'none');
        }
        
        console.log('🔄 Repeat:', this.repeatMode);
    }
    
    toggleShuffle() {
        this.isShuffle = !this.isShuffle;
        
        const btn = this.container?.querySelector('[data-action="shuffle"]');
        if (btn) {
            btn.classList.toggle('active', this.isShuffle);
        }
        
        console.log('🔀 Shuffle:', this.isShuffle ? 'ON' : 'OFF');
    }
    
    switchMode(mode) {
        console.log('📺 Switching to:', mode);
        this.currentMode = mode;
        
        const display = this.container?.querySelector('.player-display');
        const queue = this.container?.querySelector('.queue-container');
        const lyrics = this.container?.querySelector('.lyrics-container');
        const video = this.container?.querySelector('video');
        const audio = this.container?.querySelector('audio');
        const cover = this.container?.querySelector('.player-cover');
        
        // Скрыть все контейнеры
        if (display) display.style.display = 'none';
        if (queue) queue.style.display = 'none';
        if (lyrics) lyrics.style.display = 'none';
        
        // Обновить кнопки
        const buttons = this.container?.querySelectorAll('.mode-btn');
        if (buttons) {
            buttons.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.mode === mode);
            });
        }
        
        const track = this.queue[this.currentIndex];
        
        switch(mode) {
            case 'audio':
                if (display) display.style.display = 'block';
                if (cover) cover.style.display = 'block';
                if (video) video.style.display = 'none';
                if (audio) audio.style.display = 'block';
                if (track) this.loadAudio(track);
                break;
                
            case 'video':
                if (display) display.style.display = 'block';
                if (cover) cover.style.display = 'none';
                if (video) video.style.display = 'block';
                if (audio) audio.style.display = 'none';
                
                if (track?.videoPath) {
                    this.loadVideo(track);
                } else {
                    alert('⚠️ Видео не доступно для этого трека');
                    this.switchMode('audio');
                }
                break;
                
            case 'queue':
                if (queue) queue.style.display = 'block';
                break;
                
            case 'lyrics':
                if (lyrics) lyrics.style.display = 'block';
                break;
        }
    }
    
    seekTo(e) {
        const progressBar = this.container?.querySelector('.progress-bar');
        const media = this.getCurrentMedia();
        
        if (!progressBar || !media || !media.duration) return;
        
        const rect = progressBar.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        media.currentTime = percent * media.duration;
    }
    
    updateProgress() {
        const media = this.getCurrentMedia();
        if (!media) return;
        
        const percent = media.duration ? (media.currentTime / media.duration) * 100 : 0;
        const fill = this.container?.querySelector('.progress-fill');
        const times = this.container?.querySelectorAll('.time');
        
        if (fill) fill.style.width = percent + '%';
        if (times?.[0]) times[0].textContent = this.formatTime(media.currentTime);
        if (times?.[1]) times[1].textContent = this.formatTime(media.duration);
    }
    
    updateDuration() {
        const media = this.getCurrentMedia();
        if (!media || !media.duration) return;
        
        const duration = Math.floor(media.duration);
        if (this.queue[this.currentIndex]) {
            this.queue[this.currentIndex].duration = duration;
            const item = this.container?.querySelector(
                `.queue-item[data-index="${this.currentIndex}"] .queue-duration`
            );
            if (item) item.textContent = this.formatTime(duration);
        }
    }
    
    updatePlayButton() {
        const btn = this.container?.querySelector('.play-btn');
        if (btn) {
            btn.textContent = this.isPlaying ? '⏸' : '▶';
        }
    }
    
    onTrackEnded() {
        console.log('⏹️ Track ended');
        this.nextTrack();
    }
    
    getCurrentMedia() {
        if (this.currentMode === 'video') {
            return this.container?.querySelector('video');
        }
        return this.container?.querySelector('audio');
    }
    
    normalizePath(path) {
        if (!path) return '';
        // Убираем ведущий слеш если есть
        return path.startsWith('/') ? path : '/' + path;
    }
    
    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
    
    escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text || '').replace(/[&<>"']/g, m => map[m]);
    }
}

// === ИНИЦИАЛИЗАЦИЯ ===
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('epic-player')) {
        window.epicPlayer = new EpicPlayer('epic-player');
        console.log('✅ Epic Player v4.2 Ready!');
    }
    
    // ДОБАВЛЕНО: Обработчик клика для элементов треклиста на странице альбома
    document.querySelectorAll('.track-playable').forEach(item => {
        item.addEventListener('click', (event) => {
            // Исключаем клик, если нажали на ссылку внутри
            if (event.target.closest('a')) {
                return;
            }
            
            const trackId = item.dataset.trackId;
            const trackUrl = item.dataset.trackUrl;
            const trackTitle = item.dataset.trackTitle;
            const trackCover = item.dataset.trackCover;
            
            if (trackId && trackUrl && window.epicPlayer) {
                // Создаем минимальный объект трека для передачи в плеер
                const trackData = {
                    id: trackId,
                    fullAudioPath: trackUrl,
                    title: trackTitle,
                    coverImagePath: trackCover,
                    albumTitle: 'Текущий альбом' 
                };
                
                // Используем новую функцию плеера
                window.epicPlayer.setTrackAndPlay(trackData);
            }
        });
    });
});

console.log('✅ Epic Player Video Script Loaded');