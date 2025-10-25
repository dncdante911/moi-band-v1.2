<?php
/**
 * Файл: pages/chat.php
 * Страница чата с комнатами
 */

$page_css = '/assets/css/chat.css';
require_once __DIR__ . '/../include_config/config.php';
require_once __DIR__ . '/../include_config/db_connect.php';
require_once __DIR__ . '/../include_config/Auth.php';

$auth = new Auth($pdo);

// Проверить авторизацию
if (!$auth->isLoggedIn()) {
    header('Location: /pages/auth/login.php');
    exit;
}

$user = $auth->getCurrentUser();
$room_slug = $_GET['room'] ?? 'general';

// Получить информацию о комнате
$stmt = $pdo->prepare("SELECT id, name, description, icon FROM Rooms WHERE slug = ?");
$stmt->execute([$room_slug]);
$room = $stmt->fetch();

if (!$room) {
    header('Location: /pages/chat.php?room=general');
    exit;
}

// Получить все комнаты
$stmt = $pdo->query("SELECT id, name, slug, icon FROM Rooms ORDER BY name ASC");
$rooms = $stmt->fetchAll();

// Получить последние 50 сообщений
$stmt = $pdo->prepare("
    SELECT rm.id, rm.user_id, rm.message, rm.created_at, u.username, u.avatar_path
    FROM RoomMessages rm
    JOIN Users u ON rm.user_id = u.id
    WHERE rm.room_id = ? AND rm.is_deleted = FALSE
    ORDER BY rm.created_at DESC
    LIMIT 50
");
$stmt->execute([$room['id']]);
$messages = array_reverse($stmt->fetchAll());

require_once '../include_config/header.php';
?>

<div class="container chat-container">
    <div class="chat-wrapper">
        <!-- Левая панель - Комнаты -->
        <aside class="chat-sidebar">
            <h3>💬 Комнаты чата</h3>
            <div class="rooms-list">
                <?php foreach ($rooms as $r): ?>
                    <a href="?room=<?= htmlspecialchars($r['slug']) ?>" 
                       class="room-link <?= ($r['id'] === $room['id']) ? 'active' : '' ?>">
                        <span class="room-icon"><?= htmlspecialchars($r['icon']) ?></span>
                        <span class="room-name"><?= htmlspecialchars($r['name']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>
        
        <!-- Основная область чата -->
        <main class="chat-main">
            <!-- Заголовок чата -->
            <div class="chat-header">
                <h2>
                    <span class="room-icon"><?= htmlspecialchars($room['icon']) ?></span>
                    <?= htmlspecialchars($room['name']) ?>
                </h2>
                <p class="room-description"><?= htmlspecialchars($room['description']) ?></p>
            </div>
            
            <!-- Область сообщений -->
            <div class="chat-messages" id="messagesContainer" data-room-id="<?= intval($room['id']) ?>">
                <?php if (empty($messages)): ?>
                    <div class="empty-messages">
                        <p>📭 В этой комнате нет сообщений</p>
                        <p style="font-size: 0.9rem; color: #888;">Будьте первым, кто напишет!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message" data-message-id="<?= intval($msg['id']) ?>">
                            <div class="message-avatar">
                                <?php if (!empty($msg['avatar_path'])): ?>
                                    <img src="/<?= htmlspecialchars(ltrim($msg['avatar_path'], '/')) ?>" 
                                         alt="<?= htmlspecialchars($msg['username']) ?>"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                <div class="avatar-placeholder" 
                                     style="display:<?= empty($msg['avatar_path']) ? 'flex' : 'none' ?>">
                                    <?= strtoupper(substr($msg['username'], 0, 1)) ?>
                                </div>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="username"><?= htmlspecialchars($msg['username']) ?></span>
                                    <span class="timestamp" title="<?= date('d.m.Y H:i:s', strtotime($msg['created_at'])) ?>">
                                        <?= date('H:i', strtotime($msg['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="message-text"><?= htmlspecialchars($msg['message']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Поле для ввода сообщения -->
            <div class="chat-input-area">
                <form id="messageForm" class="message-form">
                    <textarea 
                        id="messageInput" 
                        class="message-input"
                        placeholder="Введите сообщение... (Shift+Enter для новой строки)"
                        rows="3"
                    ></textarea>
                    <button type="button" class="send-button">
                        📤 Отправить
                    </button>
                </form>
            </div>
        </main>
        
        <!-- Правая панель - Активные пользователи -->
        <aside class="chat-users">
            <h3>🟢 Онлайн</h3>
            <div class="users-list" id="usersContainer">
                <p style="color: #888; font-size: 0.9rem;">Загрузка...</p>
            </div>
        </aside>
    </div>
</div>

<!-- Подключить JavaScript для чата -->
<script src="/assets/js/chat.js"></script>

<?php require_once '../include_config/footer.php'; ?>