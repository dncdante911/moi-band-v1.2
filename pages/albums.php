<?php
/**
 * Файл: pages/albums.php
 * ИСПРАВЛЕННАЯ ВЕРСИЯ - без бесконечного редиректа
 */

$page_css = '/assets/css/albums.css';
require_once __DIR__ . '/../include_config/db_connect.php';


// Проверяем, что ID альбома передан и является числом
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Если ID не передан, показываем список всех альбомов
    require_once __DIR__ . '/../include_config/header.php';
    
    // Получаем все альбомы
    $stmt = $pdo->query('SELECT * FROM Albums ORDER BY releaseDate DESC');
    $albums = $stmt->fetchAll();
    ?>
    
    <div class="container page-content">
        <h1>💿 Все альбомы</h1>
        
        <?php if (empty($albums)): ?>
            <p>🎵 Альбомов пока нет</p>
        <?php else: ?>
            <div class="album-showcase-grid">
                <?php $index = 0; foreach ($albums as $album): $index++; ?>
                    <a href="?id=<?= (int)$album['id'] ?>" class="album-showcase-card">
                        <div class="album-showcase-inner">
                            <div class="album-number"><?= sprintf('%02d', $index) ?></div>
                            
                            <div class="album-frame-wrapper">
                                <div class="album-frame-outer">
                                    <div class="album-frame-inner">
                                        <img src="/<?= htmlspecialchars(ltrim($album['coverImagePath'], '/')) ?>" 
                                             alt="<?= htmlspecialchars($album['title']) ?>"
                                             loading="lazy"
                                             class="album-image">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="album-info">
                                <h3 class="album-title">
                                    <?= htmlspecialchars($album['title']) ?>
                                </h3>
                                
                                <?php if ($album['releaseDate']): ?>
                                    <div class="album-year">
                                        📅 <?= date('Y', strtotime($album['releaseDate'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
    require_once __DIR__ . '/../include_config/footer.php';
    exit;
}

// Если ID передан - показываем конкретный альбом
$albumId = (int)$_GET['id'];

// Получаем информацию об альбоме
$stmt = $pdo->prepare("SELECT * FROM Albums WHERE id = ?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

// Если альбома нет - показываем список
if (!$album) {
    header('Location: /pages/albums.php');
    exit;
}

// Получаем все треки альбома
$stmt = $pdo->prepare("SELECT * FROM Track WHERE albumId = ? ORDER BY id ASC");
$stmt->execute([$albumId]);
$tracks = $stmt->fetchAll();

require_once __DIR__ . '/../include_config/header.php';
?>

<!-- === БАННЕР АЛЬБОМА === -->
<div class="album-page-banner">
    <div class="banner-overlay"></div>
    <div class="banner-glow"></div>
    
    <div class="banner-content">
        <h1 class="banner-title">💿 <?= htmlspecialchars($album['title']) ?></h1>
        <p class="banner-subtitle">
            <?php if ($album['releaseDate']): ?>
                📅 <?= date('Y', strtotime($album['releaseDate'])) ?>
            <?php endif; ?>
            • 🎵 <?= count($tracks) ?> треков
        </p>
    </div>
</div>

<div class="container page-content album-page">
    
    <!-- === ИНФОРМАЦИЯ АЛЬБОМА === -->
    <section class="album-header">
        <div class="album-cover">
            <img src="/<?= htmlspecialchars(ltrim($album['coverImagePath'], '/')) ?>" 
                 alt="<?= htmlspecialchars($album['title']) ?>"
                 class="album-cover-image">
        </div>
        
        <div class="album-info-section">
            <h2 class="album-title-main">
                <?= htmlspecialchars($album['title']) ?>
            </h2>
            
            <?php if ($album['releaseDate']): ?>
                <p class="album-release-date">
                    📅 Дата релиза: <?= date('d.m.Y', strtotime($album['releaseDate'])) ?>
                </p>
            <?php endif; ?>
            
            <div class="album-stats">
                <span class="stat">🎵 <?= count($tracks) ?> треков</span>
            </div>
            
            <?php if ($album['description']): ?>
                <div class="album-description">
                    <h3>Описание</h3>
                    <p><?= nl2br(htmlspecialchars($album['description'])) ?></p>
                </div>
            <?php endif; ?>
            
            <a href="/pages/albums.php" class="back-link">← Вернуться в каталог</a>
        </div>
    </section>
<?php
require_once __DIR__ . '/../pages/player.php';
?>
    <!-- === ТРЕКЛИСТ === -->
    <section class="album-tracklist">
        <h2 class="tracklist-title">📋 Треклист</h2>
        
        <?php if (empty($tracks)): ?>
            <div class="empty-tracklist">
                <p>🎵 В этом альбоме пока нет треков</p>
            </div>
        <?php else: ?>
            <div class="tracks-container">
                <?php foreach ($tracks as $index => $track): ?>
                    <div class="track-item track-playable" 
                         data-track-id="<?= (int)$track['id'] ?>"
                         data-track-title="<?= htmlspecialchars($track['title']) ?>"
                         data-track-url="/<?= htmlspecialchars(ltrim($track['fullAudioPath'], '/')) ?>"
                         data-track-cover="/<?= htmlspecialchars(ltrim($track['coverImagePath'], '/')) ?>"
                    >
                        <div class="track-number">
                            <?= sprintf('%02d', $index + 1) ?>
                        </div>
                        
                        <div class="track-cover">
                            <img src="/<?= htmlspecialchars(ltrim($track['coverImagePath'], '/')) ?>" 
                                 alt="<?= htmlspecialchars($track['title']) ?>"
                                 loading="lazy">
                        </div>
                        
                        <div class="track-info">
                            <h3 class="track-title">
                                <?= htmlspecialchars($track['title']) ?>
                            </h3>
                            <?php if ($track['description']): ?>
                                <p class="track-description">
                                    <?= htmlspecialchars(mb_substr($track['description'], 0, 100)) ?>
                                    <?php if (strlen($track['description']) > 100): ?>
                                        ...
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    
    <!-- === КНОПКА НАЗАД === -->
    <div class="album-footer">
        <a href="/pages/albums.php" class="footer-link">← Вернуться в каталог альбомов</a>
    </div>
    
</div>

<?php require_once __DIR__ . '/../include_config/footer.php'; ?>