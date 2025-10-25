/**
 * MASTER OF ILLUSION - EPIC PLAYER v4.0 STABLE
 * Полностью переписано с нуля - работает!
 */

class EpicPlayer {
    constructor(containerId = 'epic-player') {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('❌ Плеер контейнер не найден:', containerId);
            return;
        }
        
        this.queue = [];
        this.currentIndex = 0;
        this.isPlaying = false;
        this.currentMode = 'audio';
        this.repeatMode = 'none';
        this.isShuffle = false;
        
        console.log('🎸 Инициализация EpicPlayer v4.0...');
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        
        // Загружаем плейлист из URL параметра
        const urlParams = new URLSearchParams(window.location.search);
        const albumId = urlParams.get('id');
        
        if (albumId) {
            console.log('📀 Загружаем альбом ID:', albumId);
            this.loadPlaylist(parseInt(albumId));
        } else {
            console.warn('⚠️ ID альбома не найден в URL');
        }
    }
    
    setupEventListeners() {
        // Кнопки управления
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
        
        // Режимы просмотра
        const modeButtons = this.container?.querySelectorAll('.mode-btn');
        modeButtons?.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const mode = e.target.dataset.mode;
                if (mode) this.switchMode(mode);
            });
        });
        
        // Прогресс бар
        const progressBar = this.container?.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.addEventListener('click', (e) => this.seekTo(e));
        }
        
        // Медиа элементы
        const audio = this.container?.querySelector('audio');
        const video = this.container?.querySelector('video');
        
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
        
        console.log('✅ Event listeners установлены');
    }
    
    async loadPlaylist(albumId) {
        try {
            console.log('🔄 Загружаю плейлист альбома:', albumId);
            
            const url = `/api/player/queue.php?album_id=${albumId}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Неизвестная ошибка API');
            }
            
            if (!data.tracks || data.tracks.length === 0) {
                console.warn('⚠️ Плейлист пуст');
                return;
            }
            
            this.queue = data.tracks;
            this.currentIndex = 0;
            this.isShuffle = false;
            this.repeatMode = 'none';
            
            console.log('✅ Плейлист загружен:', this.queue.length, 'треков');
            console.log('🎵 Первый трек:', this.queue[0].title);
            
            this.renderQueue();
            this.loadTrack(0);
            
        } catch (error) {
            console.error('❌ Ошибка загрузки плейлиста:', error);
            alert('Ошибка загрузки плейлиста: ' + error.message);
        }
    }
    
    loadTrack(index) {
        if (index < 0 || index >= this.queue.length) {
            console.warn('⚠️ Индекс трека вне границ:', index);
            return;
        }
        
        this.currentIndex = index;
        const track = this.queue[index];
        
        console.log(`🎵 Загружаем трек [${index + 1}/${this.queue.length}]:`, track.title);
        
        // Обновляем информацию
        this.updateTrackInfo(track);
        
        // Загружаем в зависимости от режима
        if (this.currentMode === 'video' && track.videoPath) {
            console.log('📺 Загружаем видео');
            this.loadVideo(track);
        } else {
            console.log('🔊 Загружаем аудио');
            this.loadAudio(track);
        }
        
        // Загружаем текст
        this.loadLyrics(track.id);
        
        // Обновляем очередь
        this.updateQueueHighlight();
    }
    
    loadAudio(track) {
        const audio = this.container?.querySelector('#audio-player');
        if (!audio || !track.fullAudioPath) {
            console.error('❌ Аудио элемент или путь не найдены');
            return;
        }
        
        const audioPath = track.fullAudioPath.startsWith('/') 
            ? track.fullAudioPath 
            : '/' + track.fullAudioPath;
        
        audio.src = audioPath;
        console.log('✅ Аудио загружено:', audioPath);
    }
    
    loadVideo(track) {
        const video = this.container?.querySelector('video');
        
        if (!video) {
            console.error('❌ Видео элемент не найден');
            return;
        }
        
        if (!track.videoPath) {
            console.warn('⚠️ Видео для трека не указано');
            return;
        }
        
        const videoPath = track.videoPath.startsWith('/') 
            ? track.videoPath 
            : '/' + track.videoPath;
        
        console.log('📹 Загружаем видео:', videoPath);
        
        video.src = videoPath;
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
                lyricsText.innerHTML = '<div class="no-lyrics">🎵 Текст песни отсутствует</div>';
            }
        } catch (e) {
            console.error('Ошибка загрузки текста:', e);
        }
    }
    
    updateTrackInfo(track) {
        const title = this.container?.querySelector('.track-title');
        const artist = this.container?.querySelector('.track-artist');
        const album = this.container?.querySelector('.track-album');
        
        if (title) title.textContent = track.title || 'Неизвестный трек';
        if (artist) artist.textContent = 'Master of Illusion';
        if (album) album.textContent = track.albumTitle || 'Альбом';
        
        // Обновляем обложку
        const cover = this.container?.querySelector('.player-cover img');
        if (cover && track.coverImagePath) {
            const coverPath = track.coverImagePath.startsWith('/') 
                ? track.coverImagePath 
                : '/' + track.coverImagePath;
            cover.src = coverPath;
        }
    }
    
    renderQueue() {
        const queueList = this.container?.querySelector('.queue-list');
        if (!queueList) return;
        
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
        
        // Добавляем события для элементов очереди
        queueList.querySelectorAll('.queue-item').forEach((item, index) => {
            item.addEventListener('click', () => this.playTrack(index));
        });
        
        console.log('✅ Очередь отрисована:', this.queue.length, 'треков');
    }
    
    updateQueueHighlight() {
        const items = this.container?.querySelectorAll('.queue-item');
        items?.forEach((item, index) => {
            if (index === this.currentIndex) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }
    
    updateDuration() {
        const media = this.getCurrentMedia();
        if (!media || !media.duration) return;
        
        const duration = Math.floor(media.duration);
        if (this.queue[this.currentIndex]) {
            this.queue[this.currentIndex].duration = duration;
            const item = this.container?.querySelector(`.queue-item[data-index="${this.currentIndex}"] .queue-duration`);
            if (item) item.textContent = this.formatTime(duration);
        }
    }
    
    togglePlay() {
        const media = this.getCurrentMedia();
        if (!media) return;
        
        if (this.isPlaying) {
            media.pause();
        } else {
            media.play();
        }
    }
    
    playTrack(index) {
        if (index < 0 || index >= this.queue.length) return;
        this.loadTrack(index);
        const media = this.getCurrentMedia();
        if (media) media.play();
    }
    
    nextTrack() {
        if (this.queue.length === 0) return;
        
        console.log('➡️ Следующий трек (repeat:', this.repeatMode, 'shuffle:', this.isShuffle + ')');
        
        if (this.repeatMode === 'one') {
            this.loadTrack(this.currentIndex);
        } else if (this.isShuffle) {
            this.currentIndex = Math.floor(Math.random() * this.queue.length);
            this.loadTrack(this.currentIndex);
        } else {
            this.currentIndex++;
            if (this.currentIndex >= this.queue.length) {
                if (this.repeatMode === 'all') {
                    this.currentIndex = 0;
                    this.loadTrack(this.currentIndex);
                } else {
                    this.currentIndex = this.queue.length - 1;
                    this.isPlaying = false;
                    this.updatePlayButton();
                    return;
                }
            } else {
                this.loadTrack(this.currentIndex);
            }
        }
        
        const media = this.getCurrentMedia();
        if (media) media.play();
    }
    
    prevTrack() {
        if (this.queue.length === 0) return;
        
        console.log('⬅️ Предыдущий трек');
        
        this.currentIndex--;
        if (this.currentIndex < 0) {
            this.currentIndex = this.queue.length - 1;
        }
        
        this.loadTrack(this.currentIndex);
        const media = this.getCurrentMedia();
        if (media) media.play();
    }
    
    toggleRepeat() {
        const modes = ['none', 'all', 'one'];
        const idx = modes.indexOf(this.repeatMode);
        this.repeatMode = modes[(idx + 1) % modes.length];
        
        const btn = this.container?.querySelector('[data-action="repeat"]');
        if (btn) {
            const icons = { none: '🔁', all: '🔁', one: '🔂' };
            const titles = { none: 'Без повтора', all: 'Повтор всех', one: 'Повтор одного' };
            
            btn.textContent = icons[this.repeatMode];
            btn.title = titles[this.repeatMode];
            btn.classList.toggle('active', this.repeatMode !== 'none');
        }
        
        console.log('🔄 Повтор:', this.repeatMode);
    }
    
    toggleShuffle() {
        this.isShuffle = !this.isShuffle;
        
        const btn = this.container?.querySelector('[data-action="shuffle"]');
        if (btn) {
            btn.classList.toggle('active', this.isShuffle);
            btn.title = this.isShuffle ? 'Выключить перемешивание' : 'Включить перемешивание';
        }
        
        console.log('🔀 Перемешивание:', this.isShuffle ? 'ВКЛ' : 'ВЫКЛ');
    }
    
    switchMode(mode) {
        console.log('📺 Переключение режима:', mode);
        this.currentMode = mode;
        
        const display = this.container?.querySelector('.player-display');
        const queue = this.container?.querySelector('.queue-container');
        const lyrics = this.container?.querySelector('.lyrics-container');
        const audio = this.container?.querySelector('audio');
        const video = this.container?.querySelector('video');
        const cover = this.container?.querySelector('.player-cover');
        
        // Обновляем кнопки режимов
        const buttons = this.container?.querySelectorAll('.mode-btn');
        buttons?.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });
        
        // Скрываем контейнеры
        if (queue) queue.style.display = 'none';
        if (lyrics) lyrics.style.display = 'none';
        if (display) display.style.display = 'none';
        
        // Показываем нужный контейнер
        const track = this.queue[this.currentIndex];
        
        switch(mode) {
            case 'audio':
                if (display) display.style.display = 'block';
                if (cover) cover.style.display = 'block';
                if (video) video.style.display = 'none';
                if (audio) audio.style.display = 'block';
                this.loadAudio(track);
                console.log('🎵 Режим АУДИО активирован');
                break;
                
            case 'video':
                if (display) display.style.display = 'block';
                if (cover) cover.style.display = 'none';
                if (video) video.style.display = 'block';
                if (audio) audio.style.display = 'none';
                
                if (track?.videoPath) {
                    this.loadVideo(track);
                    console.log('🎬 Режим ВИДЕО активирован');
                } else {
                    alert('⚠️ Видео для этого трека недоступно');
                    this.switchMode('audio');
                }
                break;
                
            case 'queue':
                if (queue) queue.style.display = 'block';
                console.log('📋 Режим ОЧЕРЕДЬ активирован');
                break;
                
            case 'lyrics':
                if (lyrics) lyrics.style.display = 'block';
                console.log('📝 Режим ТЕКСТ активирован');
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
        const handle = this.container?.querySelector('.progress-handle');
        const times = this.container?.querySelectorAll('.time');
        
        if (fill) fill.style.width = percent + '%';
        if (handle) handle.style.left = percent + '%';
        if (times?.[0]) times[0].textContent = this.formatTime(media.currentTime);
        if (times?.[1]) times[1].textContent = this.formatTime(media.duration);
    }
    
    updatePlayButton() {
        const btn = this.container?.querySelector('.play-btn');
        if (btn) {
            btn.textContent = this.isPlaying ? '⏸' : '▶';
            btn.title = this.isPlaying ? 'Пауза' : 'Воспроизвести';
        }
    }
    
    onTrackEnded() {
        console.log('⏹️ Трек завершён');
        this.nextTrack();
    }
    
    getCurrentMedia() {
        if (this.currentMode === 'video') {
            return this.container?.querySelector('video');
        }
        return this.container?.querySelector('audio');
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

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('epic-player')) {
        window.epicPlayer = new EpicPlayer('epic-player');
        console.log('🎸 Epic Player v4.0 готов к работе!');
    }
});