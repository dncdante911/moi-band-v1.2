<?php
/**
 * ФАЙЛ: pages/gallery.php
 * Страница галереи с изображениями
 */

require_once __DIR__ . '/../include_config/header.php';

// Получаем изображения из БД
$query = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$images = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="gallery-banner">
        <div class="banner-content">
            <h1>🖼️ Галерея</h1>
            <p>Фотографии со студии, концертов и событий</p>
        </div>
    </div>

    <div class="page-content gallery-page">
        
        <!-- === ФИЛЬТР ПО КАТЕГОРИЯМ === -->
        <div class="gallery-filter">
            <button class="filter-btn active" data-filter="all">Все</button>
            <button class="filter-btn" data-filter="studio">Студия</button>
            <button class="filter-btn" data-filter="concert">Концерты</button>
            <button class="filter-btn" data-filter="event">События</button>
            <button class="filter-btn" data-filter="promo">Промо</button>
        </div>

        <!-- === ГАЛЕРЕЯ === -->
        <div class="gallery-grid">
            <?php if (count($images) > 0): ?>
                <?php foreach ($images as $img): ?>
                    <div class="gallery-item" data-category="<?= htmlspecialchars($img['category']) ?>">
                        <div class="gallery-image-wrapper">
                            <img src="<?= htmlspecialchars($img['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($img['title']) ?>"
                                 loading="lazy">
                            <div class="gallery-overlay">
                                <button class="gallery-view" data-image="<?= htmlspecialchars($img['image_url']) ?>"
                                        data-title="<?= htmlspecialchars($img['title']) ?>">
                                    👁️ Просмотр
                                </button>
                            </div>
                        </div>
                        <div class="gallery-info">
                            <p class="gallery-title"><?= htmlspecialchars($img['title']) ?></p>
                            <p class="gallery-category"><?= htmlspecialchars($img['category']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-content">
                    <p>Галерея пока пуста. Скоро появятся новые фото!</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- === МОДАЛЬНОЕ ОКНО ДЛЯ ПРОСМОТРА === -->
<div id="gallery-modal" class="gallery-modal">
    <div class="modal-content">
        <button class="modal-close">&times;</button>
        <img id="modal-image" src="" alt="">
        <p id="modal-title"></p>
    </div>
</div>

<!-- === CSS === -->
<style>
.gallery-banner {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.05));
    padding: 60px 20px;
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid #FFD700;
}

.gallery-banner h1 {
    font-size: 2.5rem;
    color: #FFD700;
    margin-bottom: 10px;
}

.gallery-banner p {
    color: #ccc;
    font-size: 1.1rem;
}

.gallery-filter {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid #666;
    color: #ccc;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: bold;
}

.filter-btn:hover,
.filter-btn.active {
    border-color: #FFD700;
    color: #FFD700;
    background: rgba(255, 215, 0, 0.1);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.gallery-item {
    background: rgba(26, 20, 16, 0.85);
    border: 2px solid #666;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
}

.gallery-item:hover {
    border-color: #FFD700;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    transform: translateY(-5px);
}

.gallery-image-wrapper {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #000;
}

.gallery-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.gallery-item:hover .gallery-image-wrapper img {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-view {
    background: linear-gradient(135deg, #FFD700, #FFA500);
    color: #000;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}

.gallery-view:hover {
    transform: scale(1.1);
}

.gallery-info {
    padding: 15px;
}

.gallery-title {
    color: #FFD700;
    font-weight: bold;
    margin: 0 0 5px 0;
}

.gallery-category {
    color: #999;
    font-size: 0.9rem;
    margin: 0;
    text-transform: capitalize;
}

/* === МОДАЛЬНОЕ ОКНО === */

.gallery-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.gallery-modal.active {
    display: flex;
}

.modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
}

#modal-image {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 10px;
}

#modal-title {
    color: #FFD700;
    text-align: center;
    margin-top: 20px;
    font-size: 1.2rem;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 215, 0, 0.2);
    border: 2px solid #FFD700;
    color: #FFD700;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 28px;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-close:hover {
    background: #FFD700;
    color: #000;
}

.no-content {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .gallery-banner h1 {
        font-size: 2rem;
    }

    .modal-content {
        max-width: 95%;
        max-height: 95vh;
    }
}
</style>

<!-- === JAVASCRIPT === -->
<script>
const modal = document.getElementById('gallery-modal');
const modalImage = document.getElementById('modal-image');
const modalTitle = document.getElementById('modal-title');
const modalClose = document.querySelector('.modal-close');

// Открыть модальное окно
document.querySelectorAll('.gallery-view').forEach(btn => {
    btn.addEventListener('click', function() {
        const image = this.dataset.image;
        const title = this.dataset.title;
        
        modalImage.src = image;
        modalTitle.textContent = title;
        modal.classList.add('active');
    });
});

// Закрыть модальное окно
modalClose.addEventListener('click', () => {
    modal.classList.remove('active');
});

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.remove('active');
    }
});

// Фильтр
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        document.querySelectorAll('.gallery-item').forEach(item => {
            if (filter === 'all' || item.dataset.category === filter) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Закрыть модал по Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        modal.classList.remove('active');
    }
});
</script>

<?php require_once __DIR__ . '/../include_config/footer.php'; ?>