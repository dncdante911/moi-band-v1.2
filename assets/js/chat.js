/**
 * Файл: assets/js/chat.js
 * Управление чатом и отправкой сообщений
 */

class ChatClient {
    constructor() {
        this.roomId = document.getElementById('messagesContainer')?.dataset.roomId;
        this.userId = null;
        this.username = null;
        this.init();
    }
    
    async init() {
        console.log('🎸 Инициализация чата...');
        
        // Получить информацию о пользователе
        try {
            const res = await fetch('/api/user/profile.php');
            const data = await res.json();
            
            if (data.success) {
                this.userId = data.profile.id;
                this.username = data.profile.username;
                console.log('✅ Пользователь:', this.username);
            }
        } catch (e) {
            console.error('❌ Ошибка получения профиля:', e);
        }
        
        // Установить обработчики событий
        this.setupEventListeners();
        
        // Загрузить историю
        this.loadHistory();
        
        // Обновлять сообщения каждые 2 секунды
        setInterval(() => this.pollMessages(), 2000);
        
        // Обновлять онлайн пользователей каждые 5 секунд
        setInterval(() => this.loadOnlineUsers(), 5000);
        
        this.loadOnlineUsers();
    }
    
    setupEventListeners() {
        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');
        const sendBtn = document.querySelector('.send-button');
        
        if (!form || !input || !sendBtn) {
            console.error('❌ Не найдены элементы формы');
            return;
        }
        
        // Отправка при нажатии кнопки
        sendBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.sendMessage();
        });
        
        // Отправка при нажатии Enter (без Shift)
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        
        console.log('✅ Обработчики событий установлены');
    }
    
    async sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        
        if (!message) {
            console.warn('⚠️ Сообщение пусто');
            return;
        }
        
        if (!this.roomId) {
            alert('❌ Ошибка: комната не выбрана');
            return;
        }
        
        console.log('📤 Отправка сообщения...');
        
        try {
            const response = await fetch('/api/chat/send.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    room_id: this.roomId,
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('✅ Сообщение отправлено:', data.message_id);
                input.value = '';
                this.loadHistory();
            } else {
                console.error('❌ Ошибка отправки:', data.error);
                alert('Ошибка: ' + data.error);
            }
        } catch (e) {
            console.error('❌ Ошибка при отправке:', e);
            alert('Ошибка соединения: ' + e.message);
        }
    }
    
    async loadHistory() {
        if (!this.roomId) return;
        
        try {
            const response = await fetch(`/api/chat/messages.php?room_id=${this.roomId}&limit=50`);
            const data = await response.json();
            
            if (data.success) {
                const container = document.getElementById('messagesContainer');
                container.innerHTML = '';
                
                data.messages.forEach(msg => {
                    this.displayMessage(msg);
                });
                
                this.scrollToBottom();
                console.log('✅ История загружена:', data.messages.length, 'сообщений');
            }
        } catch (e) {
            console.error('❌ Ошибка загрузки истории:', e);
        }
    }
    
    async pollMessages() {
        if (!this.roomId) return;
        
        try {
            const response = await fetch(`/api/chat/messages.php?room_id=${this.roomId}&limit=1`);
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                const lastMsg = data.messages[0];
                const existing = document.querySelector(`[data-message-id="${lastMsg.id}"]`);
                
                if (!existing) {
                    this.displayMessage(lastMsg);
                    this.scrollToBottom();
                }
            }
        } catch (e) {
            console.error('Poll error:', e);
        }
    }
    
    displayMessage(msg) {
        const container = document.getElementById('messagesContainer');
        
        // Проверить, есть ли уже такое сообщение
        if (document.querySelector(`[data-message-id="${msg.id}"]`)) {
            return;
        }
        
        const div = document.createElement('div');
        div.className = 'message';
        div.setAttribute('data-message-id', msg.id);
        
        const avatarLetter = msg.username.charAt(0).toUpperCase();
        
        div.innerHTML = `
            <div class="message-avatar">
                ${msg.avatar_path ? 
                    `<img src="/${msg.avatar_path.replace(/^\\//, '')}" alt="${msg.username}" onerror="this.style.display='none'">` :
                    `<div class="avatar-placeholder">${avatarLetter}</div>`
                }
            </div>
            <div class="message-content">
                <div class="message-header">
                    <span class="username">${this.escapeHtml(msg.username)}</span>
                    <span class="timestamp" title="${msg.created_at}">
                        ${msg.timestamp}
                        ${msg.is_edited ? '<span class="edited">(изменено)</span>' : ''}
                    </span>
                </div>
                <p class="message-text">${this.escapeHtml(msg.message)}</p>
            </div>
        `;
        
        container.appendChild(div);
    }
    
    async loadOnlineUsers() {
        try {
            const response = await fetch('/api/user/online.php');
            const data = await response.json();
            
            if (data.success) {
                const container = document.getElementById('usersContainer');
                
                if (!container) return;
                
                container.innerHTML = '';
                
                data.users.forEach(user => {
                    const div = document.createElement('div');
                    div.className = 'user-item';
                    
                    const statusClass = user.status === 'online' ? 'online' : 
                                       user.status === 'away' ? 'away' : 'offline';
                    
                    div.innerHTML = `
                        <div class="user-status ${statusClass}"></div>
                        <span class="user-name" title="${user.username}">
                            ${this.escapeHtml(user.username)}
                        </span>
                    `;
                    container.appendChild(div);
                });
                
                console.log('✅ Онлайн пользователей:', data.count);
            }
        } catch (e) {
            console.error('❌ Ошибка загрузки онлайн:', e);
        }
    }
    
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
}

// Инициализировать чат при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    console.log('🎸 Загрузка страницы чата...');
    new ChatClient();
});